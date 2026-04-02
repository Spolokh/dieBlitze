<?php

session_start();

define('root', dirname(__DIR__));
define('rootpath', dirname(__FILE__));
define('VENDOR', root. '/libs');
define('UPLOADS', root. '/uploads');
define('VIEWS_PATH', rootpath.'/views');
define('MODEL_PATH', rootpath.'/models');
define('CONTR_PATH', rootpath.'/controllers');
define('MINIMUM_PHP', '7.0.33');

if (version_compare(PHP_VERSION, MINIMUM_PHP, '<')) {
    exit('Your host needs to use PHP ' .MINIMUM_PHP. ' or higher to run this version CMS! This version ' . PHP_VERSION);
}

require_once 'core/view.php';
require_once 'core/model.php';
require_once 'core/controller.php';
require_once 'core/route.php';
require_once 'core/functions.php';

spl_autoload_register('classRegister');

$config = include_once ('core/config.php');
Config::init($config);

ORM::configure(Config::dsn());
ORM::configure([
    'dbprefix' => Config::get('prefix'),
    'username' => Config::get('dbuser'),
    'password' => Config::get('dbpass')
]);

Route::start();

/*
$db = ORM::getDb();
$db->exec("CREATE TABLE IF NOT EXISTS users (
        id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
        date int(11) DEFAULT '0',
        username varchar(50) NOT NULL UNIQUE,
        password varchar(255) NOT NULL,
        mail varchar(50) NOT NULL UNIQUE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

$db->exec("CREATE TABLE IF NOT EXISTS books (
        id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        mail varchar(50) NOT NULL,
        phone varchar(255) NOT NULL,
        image varchar(255) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
*/
