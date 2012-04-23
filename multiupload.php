<?php
if (!defined('_PS_VERSION_'))
	exit;

class Multiupload extends Module
{
	private $_html;

	public $module_Path = '';
	public $module_URL = '';
	
	public function __construct()
 	{
 	 	$this->name = 'multiupload';
 	 	$this->version = '0.1';
		$this->author = 'Wagood';
 	 	$this->tab = 'administration';
		$this->need_instance = 0;
		
		parent::__construct();
		
		$this->displayName = $this->l('Images Multi Upload');
		$this->description = $this->l('Upload multiple images from admin product tab.');
		
		$this->module_Path = '../modules/'.$this->name.'/';

		$this->module_URL = 'http://'.Configuration::get('PS_SHOP_DOMAIN').'/modules/'.$this->name.'/';
		
 	}
	
 	public function install()
	{
	 	return (parent::install() AND $this->registerHook('backOfficeFooter') AND Configuration::updateValue('MULTIUPLOAD_ADMIN_PATH', getcwd()));
	}
 	
	public function uninstall()
	{
	 	return (parent::uninstall() AND $this->unregisterHook('backOfficeFooter') AND Configuration::deleteByName('MULTIUPLOAD_ADMIN_PATH'));
	}
	
	public function hookbackOfficeFooter($params) 
	{ 
		global $smarty, $cookie, $images;
		
		// Quit if not images or not object loaded
		$obj = new Product((int)(Tools::getValue('id_product')));
		//Validate::isLoadedObject($obj);
		if (!isset($obj->id)) 
			return;
			
		
		
			
		$languages = Language::getLanguages(false);

		// Get right language iso code and check for js file with translation
		$lang_iso = Language::getIsoById((int)$cookie->id_lang);
		$i18n_file = $this->module_Path.'js/i18n/'.$lang_iso.'.js';  	
		
		if (!file_exists($i18n_file))
			$i18n_file = '';
		else 
			$i18n_file = '<script type="text/javascript" src="'.$i18n_file.'"></script>';
		
		$smarty->assign(array(
			'module_url' => $this->module_URL,
			'module_path' => $this->module_Path,
			'obj_id' => $obj->id,
			'PS_IMG_DIR' => _PS_IMG_DIR_,
			'maxImageSize' => (Configuration::get('PS_LIMIT_UPLOAD_IMAGE_VALUE') * 1000),	
			'languages' => $languages,
			'i18n_file' => $i18n_file,	
			)
		);		
		
		return $this->display(__FILE__, 'multiupload.tpl');
	}
	
	public function initModule()
	{
		$this->module_Path = _PS_MODULE_DIR_.$this->name.'/';

		$protocol = (Configuration::get('PS_SSL_ENABLED') || (!empty($_SERVER['HTTPS']) 
			&& strtolower($_SERVER['HTTPS']) != 'off')) ? 'https://' : 'http://';
		
		$endURL = __PS_BASE_URI__.'modules/'.$this->name.'/';
	
		if (method_exists('Tools', 'getShopDomainSsl'))
			self::$moduleURL = $protocol.Tools::getShopDomainSsl().$endURL;
		else
			self::$moduleURL = $protocol.$_SERVER['HTTP_HOST'].$endURL;			
	}

}
?>