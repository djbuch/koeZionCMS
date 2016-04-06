<?php
define('DS', 		DIRECTORY_SEPARATOR); 	//Définition du séparateur dans le cas ou l'on est sur windows ou linux
define('ROOT', 		dirname(dirname(dirname(dirname(dirname(__FILE__)))))); 		//Chemin vers le dossier racine du site
define('WEBROOT', 	ROOT.DS."webroot"); 	//Chemin vers le dossier webroot

require_once WEBROOT.DS.'constants.php';

////////////////////////////////////////////////////////////////////////////////
//   CHARGEMENT DES ELEMENTS NECESSAIRES AU BON FONCTIONNEMENT DES SESSIONS   //
/*
define('DS', DIRECTORY_SEPARATOR); //Définition du séparateur dans le cas ou l'on est sur windows ou linux

define('CONFIGS', ROOT.DS.'configs'); //Chemin vers le dossier config

define('CORE', ROOT.DS.'core'); //Chemin vers le coeur de l'application
define('CAKEPHP', CORE.DS.'CakePhp'); //Chemin vers les librairies koeZion
define('SYSTEM', CORE.DS.'koeZion'.DS.'system'); //Chemin vers les librairies koeZion
define('LIBS', CORE.DS.'Libs'); //Chemin vers les librairies diverses

define('TMP', ROOT.DS.'tmp'); //Chemin vers le dossier temporaire
*/

require_once CAKEPHP.DS.'inflector.php'; //On charge le composant
require_once CAKEPHP.DS.'set.php'; //On charge le composant
require_once SYSTEM.DS.'session.php'; //On charge le composant
Session::init();

//Récupération des configurations du système
//require_once(LIBS.DS.'config_magik.php'); //Import de la librairie de gestion des fichiers de configuration
//$cfg 			= new ConfigMagik(CONFIGS_FILES.DS.'core.ini', true, false);
//$coreConfigs 	= $cfg->keys_values();
/*
 * CKFinder Configuration File
 *
 * For the official documentation visit http://docs.cksource.com/ckfinder3-php/
 */

/*============================ PHP Error Reporting ====================================*/
// http://docs.cksource.com/ckfinder3-php/debugging.html

// Production
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
ini_set('display_errors', 0);

// Development
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

/*============================ General Settings =======================================*/
// http://docs.cksource.com/ckfinder3-php/configuration.html

$config = array();

/*============================ Enable PHP Connector HERE ==============================*/
// http://docs.cksource.com/ckfinder3-php/configuration.html#configuration_options_authentication

$config['authentication'] = function () {
   
	/*return Session::check('Backoffice.User.id');
	echo '</pre>';
	
	
	
	return isset($_SESSION['Backoffice']);*/
	//return true;

	
	$session = Session::read('Backoffice.User.id');
	/*echo '<pre>';
	 print_r($session);
	echo '</pre>';*/
	
	return isset($session) && !empty($session);
};

/*============================ License Key ============================================*/
// http://docs.cksource.com/ckfinder3-php/configuration.html#configuration_options_licenseKey
$licenceName = 0;
if(defined('CKFINDER_LICENCE_NAME')) { $licenceName = CKFINDER_LICENCE_NAME; } 
$config['LicenseName'] = $licenceName;

$licenceKey = 0;
if(defined('CKFINDER_LICENCE_KEY')) { $licenceKey = CKFINDER_LICENCE_KEY; } 
$config['LicenseKey'] = $licenceKey;

/*============================ CKFinder Internal Directory ============================*/
// http://docs.cksource.com/ckfinder3-php/configuration.html#configuration_options_privateDir

$config['privateDir'] = array(
    'backend' => 'default',
    'tags'   => '.ckfinder/tags',
    'logs'   => '.ckfinder/logs',
    'cache'  => '.ckfinder/cache',
    'thumbs' => '.ckfinder/cache/thumbs',
);

/*============================ Images and Thumbnails ==================================*/
// http://docs.cksource.com/ckfinder3-php/configuration.html#configuration_options_images

$config['images'] = array(
    'maxWidth'  => 1600,
    'maxHeight' => 1200,
    'quality'   => 80,
    'sizes' => array(
        'small'  => array('width' => 480, 'height' => 320, 'quality' => 80),
        'medium' => array('width' => 600, 'height' => 480, 'quality' => 80),
        'large'  => array('width' => 800, 'height' => 600, 'quality' => 80)
    )
);

/*=================================== Backends ========================================*/
// http://docs.cksource.com/ckfinder3-php/configuration.html#configuration_options_backends

//Mise en place d'un dossier particulier dans le cas d'administrateur de site
$onlyOneFolder = 0;
if(defined('CKFINDER_ONLY_ONE_FOLDER')) { $onlyOneFolder = CKFINDER_ONLY_ONE_FOLDER; } 

if(Session::read('Backoffice.UsersGroup.role_id') == 1 || $onlyOneFolder) {
	
	$baseUrl = dirname(dirname(dirname(dirname(dirname(dirname(dirname($_SERVER['SCRIPT_NAME']))))))).'/upload/'; //Chemin relatif vers le dossier upload
	
} else {
	
	$userPath = Inflector::slug(Session::read('Backoffice.User.id').' '.Session::read('Backoffice.User.name'), '-');
	$baseUrl = dirname(dirname(dirname(dirname(dirname(dirname(dirname($_SERVER['SCRIPT_NAME']))))))).'/upload/'.$userPath.'/'; //Chemin relatif vers le dossier upload
	
}

//Réécriture du dossier de stockage via une variable de session
if(Session::check('REWRITE_CK_FINDER_FOLDER')) {
	
	$userPath = Session::read('REWRITE_CK_FINDER_FOLDER');
	$baseUrl = dirname(dirname(dirname(dirname(dirname(dirname(dirname($_SERVER['SCRIPT_NAME']))))))).'/upload/'.$userPath.'/'; //Chemin relatif vers le dossier upload	
}

$config['backends'][] = array(
    'name'         => 'default',
    'adapter'      => 'local',
    'baseUrl'      => $baseUrl,
//  'root'         => '', // Can be used to explicitly set the CKFinder user files directory.
    'chmodFiles'   => 0777,
    'chmodFolders' => 0755,
    'filesystemEncoding' => 'UTF-8',
);

/*================================ Resource Types =====================================*/
// http://docs.cksource.com/ckfinder3-php/configuration.html#configuration_options_resourceTypes

$config['defaultResourceTypes'] = '';


$filesExtensions = '7z,aiff,asf,avi,bmp,csv,doc,docx,fla,flv,gif,gz,gzip,jpeg,jpg,mid,mov,mp3,mp4,mpc,mpeg,mpg,ods,odt,pdf,png,ppt,pptx,pxd,qt,ram,rar,rm,rmi,rmvb,rtf,sdc,sitd,swf,sxc,sxw,tar,tgz,tif,tiff,txt,vsd,wav,wma,wmv,xls,xlsx,zip,kml,css,html,js';
$ckfinderFilesType = defined('CKFINDER_FILES_TYPE') ? CKFINDER_FILES_TYPE : '';
if(!empty($ckfinderFilesType)) { $filesExtensions = $ckfinderFilesType; }

$imagesExtensions = 'bmp,gif,jpeg,jpg,png'; 
$ckfinderImagesType = defined('CKFINDER_IMAGES_TYPE') ? CKFINDER_IMAGES_TYPE : '';
if(!empty($ckfinderImagesType)) { $imagesExtensions = $ckfinderImagesType; }


$config['resourceTypes'][] = array(
    'name'              => 'Files', // Single quotes not allowed.
    'directory'         => $baseDir . 'files',
    'maxSize'           => 0,
    'allowedExtensions' => $filesExtensions,
    'deniedExtensions'  => '',
    'backend'           => 'default'
);

$config['resourceTypes'][] = array(
    'name'              => 'Images',
    'directory'         => $baseDir . 'images',
    'maxSize'           => 0,
    'allowedExtensions' => $imagesExtensions,
    'deniedExtensions'  => '',
    'backend'           => 'default'
);

/*================================ Access Control =====================================*/
// http://docs.cksource.com/ckfinder3-php/configuration.html#configuration_options_roleSessionVar

$config['roleSessionVar'] = 'CKFinder_UserRole';

$ressourceType = '*';
if(Session::check('REWRITE_CK_FINDER_RESSOURCE_TYPE')) { $ressourceType = Session::read('REWRITE_CK_FINDER_RESSOURCE_TYPE'); }

// http://docs.cksource.com/ckfinder3-php/configuration.html#configuration_options_accessControl
$config['accessControl'][] = array(
    'role'                => '*',
    'resourceType'        => $ressourceType,
    'folder'              => '/',

    'FOLDER_VIEW'         => true,
    'FOLDER_CREATE'       => true,
    'FOLDER_RENAME'       => true,
    'FOLDER_DELETE'       => true,

    'FILE_VIEW'           => true,
    'FILE_CREATE'         => true,
    'FILE_RENAME'         => true,
    'FILE_DELETE'         => true,

    'IMAGE_RESIZE'        => true,
    'IMAGE_RESIZE_CUSTOM' => true
);


/*================================ Other Settings =====================================*/
// http://docs.cksource.com/ckfinder3-php/configuration.html

$config['overwriteOnUpload'] = false;
$config['checkDoubleExtension'] = true;
$config['disallowUnsafeCharacters'] = false;
$config['secureImageUploads'] = true;
$config['checkSizeAfterScaling'] = true;
$config['htmlExtensions'] = array('html', 'htm', 'xml', 'js');
$config['hideFolders'] = array('.*', 'CVS', '__thumbs');
$config['hideFiles'] = array('.*');
$config['forceAscii'] = false;
$config['xSendfile'] = false;

// http://docs.cksource.com/ckfinder3-php/configuration.html#configuration_options_debug
$config['debug'] = false;

/*==================================== Plugins ========================================*/
// http://docs.cksource.com/ckfinder3-php/configuration.html#configuration_options_plugins

$config['pluginsDirectory'] = __DIR__ . '/plugins';
$config['plugins'] = array();

/*================================ Cache settings =====================================*/
// http://docs.cksource.com/ckfinder3-php/configuration.html#configuration_options_cache

$config['cache'] = array(
    'imagePreview' => 24 * 3600,
    'thumbnails'   => 24 * 3600 * 365,
    'proxyCommand' => 0
);

/*============================ Temp Directory settings ================================*/
// http://docs.cksource.com/ckfinder3-php/configuration.html#configuration_options_tempDirectory

$config['tempDirectory'] = sys_get_temp_dir();

/*============================ Session Cause Performance Issues =======================*/
// http://docs.cksource.com/ckfinder3-php/configuration.html#configuration_options_sessionWriteClose

$config['sessionWriteClose'] = true;

/*================================= CSRF protection ===================================*/
// http://docs.cksource.com/ckfinder3-php/configuration.html#configuration_options_csrfProtection

$config['csrfProtection'] = true;

/*============================== End of Configuration =================================*/

// Config must be returned - do not change it.
return $config;
