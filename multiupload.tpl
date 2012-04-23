<!-- BOF Plupload -->
<!-- Load Queue widget CSS -->		
<style type="text/css">@import url({$module_url}js/jquery.plupload.queue/css/jquery.plupload.queue.css);</style>		

<!-- Thirdparty intialization scripts. Currently needed for BrowserPlus runtimes -->
<script type="text/javascript" src="http://bp.yahooapis.com/2.4.21/browserplus-min.js"></script>

<!-- Load Plupload and all it's runtimes -->			
<script type="text/javascript" src="{$module_path}js/plupload.full.js"></script>

<!-- Load Language file  -->
{$i18n_file}

<!-- Load the jQuery Queue widget - This is the actual GUI you see -->
<script type="text/javascript" src="{$module_path}js/jquery.plupload.queue/jquery.plupload.queue.js"></script>		

<script type="text/javascript">			
// Convert divs to queue widgets when the DOM is ready

{literal}
	$(function() {			

				$('<div id="uploader">...</div>').appendTo("#step2");

				// #uploader denotes div to be changed
				$("#uploader").pluploadQueue({
				
					// General settings
					runtimes : 'gears,flash,silverlight,browserplus,html5',
					preinit: functionCaller,
					
{/literal}	
					url : '{$module_url}upload.php',
{literal}						
		         //chunk_size : '1mb',
		         //unique_names : true,

					//multipart: true,
					// These multipart_params are loaded immediately from what is on the page at load
					
					multipart_params : {'id_product': '{/literal}{$obj_id}'{literal} }, {/literal}
					max_file_size : '{$maxImageSize}kb',
{literal}

					// Specify what files to browse for
					filters : [
						{title : "Image files", extensions : "jpg,jpeg,gif,png"}
						],
{/literal}
										
					// Flash settings
					flash_swf_url : '{$module_url}js/plupload.flash.swf',

					// Silverlight settings
					silverlight_xap_url : '{$module_url}js/plupload.silverlight.xap'
{literal}
				});
					// Client side form validation - change to #uploader because of submit button issue
					$('#uploader').submit(function(e) {		  			
								
						var uploader = $('#uploader').pluploadQueue();

						// Files in queue upload them first
						        if (uploader.files.length > 0) {
						            // When all files are uploaded submit form
						            uploader.bind('StateChanged', function() {
						                if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed)) {
						                    $('form')[0].submit();
						                }
						            });
						            uploader.start();
						        } else {
						            alert('You must queue at least one file.');
						        }
						        return false;
					});

				});
	
// BOF After Plupload is Activated Function Section - Note these are called with preinit above


// Calls multiple functions
function functionCaller(uploader)
{
		attachExtraParameters(uploader);
		attachCallbacks(uploader);
}

//Attach Extra Parameters (ie. new	values after page load)
function attachExtraParameters(uploader)
{

	uploader.bind('UploadFile', function(up, file) 
	{
		$.extend(up.settings.multipart_params, 
		{  
{/literal}			
	{foreach from=$languages key=k item=language name="languages"}
	'legend_{$language.id_lang}' : document.getElementById('legend_{$language.id_lang}').value,
	{/foreach}				 		
{literal}
		});
	});
}

//Refresh Page after all files uploaded
function attachCallbacks(uploader) {
	uploader.bind('FileUploaded', function(Up, File, Response) 
	{
	  if( (uploader.total.uploaded + 1) == uploader.files.length)
	  {
		reload_url = window.location.href + "&tabs=1";
//		reload_url = window.location.href;
		window.location = reload_url;
	  }		  
	});
}
{/literal}
</script>
		

