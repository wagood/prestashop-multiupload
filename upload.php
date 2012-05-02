<?php

include_once('../../config/config.inc.php');
define (PS_ADMIN_DIR, Configuration::get('MULTIUPLOAD_ADMIN_PATH'));

include(PS_ADMIN_DIR.'/functions.php');

require_once(PS_ADMIN_DIR.'/../classes/AdminTab.php');
require_once(PS_ADMIN_DIR.'/tabs/AdminCatalog.php');
require_once(PS_ADMIN_DIR.'/tabs/AdminProducts.php');
include_once('../../init.php');

$tabObj = new AdminProducts();

// The Uploading Code

$result = array();
 
if (isset($_FILES['file'])and sizeof($_FILES['file'])>0 )
{
 	
	$_FILES['image_product'] = $_FILES['file'];
	
	$id_product = intval($_POST['id_product']);
	
	$product = new Product($id_product);
	
		   
	if (!isset($product->id) || ($info = $tabObj->addProductImage($product))!='') {
		$result['result'] = 'failed';
		$result['error'] = $info;
	}else{
		$result['result'] = 'success';
		$result['size'] = "Uploaded an image success.";
	}
			  
} else {
	$result['result'] = 'error';
	$result['error'] = 'Missing file or internal error!';
}
 
 // Send JSON Header and Variables
if (!headers_sent() )
{
	 header('Content-type: application/json');
}
   
echo json_encode($result);

//print_r ($_FILES);
//print_r ($_POST);
//$buffer = ob_get_flush();
//file_put_contents('buffer.txt', $buffer);
?>