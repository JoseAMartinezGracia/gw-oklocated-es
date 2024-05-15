<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';

include_once ABSPATH_COMMON.'src/Epi.php';
include_once ABSPATH.'libs/access.php';

include_once ABSPATH.'controllers/CronController.php';
include_once ABSPATH.'controllers/HomeController.php';
include_once ABSPATH.'controllers/JsonController.php';
include_once ABSPATH.'controllers/gw.php';
include_once ABSPATH.'controllers/gw2.php';
include_once ABSPATH.'controllers/msr01bController.php';



Epi::setPath('base', ABSPATH_COMMON.'src');
Epi::setPath('css', ABSPATH.'css');
Epi::setPath('config', ABSPATH);
Epi::setPath('view', ABSPATH.'views');
Epi::setSetting('exceptions', true);
Epi::init('database','template','session');
EpiSession::employ(array(EpiSession::PHP));
EpiDatabase::employ('mysql', DB_NAME, DB_HOST, DB_USER, DB_PASSWORD);
		


CronController::cron();

//CronController::insert();

	
	
	
	
	
	
	

?>