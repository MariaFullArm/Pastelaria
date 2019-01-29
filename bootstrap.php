<?php
//error_reporting(E_ALL);
//ini_set('display_errors',1);
$loader = require __DIR__.'/vendor/autoload.php';
\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

use Silex\Application as SilexApplication;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use DerAlex\Silex\YamlConfigServiceProvider;
use Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Pastelaria\Services\AuthenticationServiceApp;
use Pastelaria\Services\AuthenticationServiceApi;


// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server') {
    $path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if (__FILE__ !== $path && is_file($path)) {
        return false;
    }
    unset($path);
}

class Application extends SilexApplication
{

}

$app = new Application();

define('ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR);
$app['debug'] = getenv('FA_FULLARM_PASTELARIA_APP_DEBUG') ? getenv('FA_FULLARM_PASTELARIA_APP_DEBUG') : false;

$app->register(new YamlConfigServiceProvider(__DIR__.'/config/config.yml'));

$configMonolog     = $app['config']['monolog'];
$configDoctrine    = $app['config']['doctrine']['options'];
$configDoctrineOrm = $app['config']['doctrine']['orm'];
$configParams      = $app['config']['params'];

$configMonolog['monolog.level']   = getenv('FA_FULLARM_PASTELARIA_LOG_LEVEL');
$configMonolog['monolog.logfile'] = getenv('FA_FULLARM_PASTELARIA_LOG_LOGFILE');

$configDoctrine['db.options']['host']     = getenv('FA_FULLARM_PASTELARIA_DB_HOST');
$configDoctrine['db.options']['port']     = getenv('FA_FULLARM_PASTELARIA_DB_PORT');
$configDoctrine['db.options']['user']     = getenv('FA_FULLARM_PASTELARIA_DB_USER');
$configDoctrine['db.options']['password'] = getenv('FA_FULLARM_PASTELARIA_DB_PASSWORD');
$configDoctrine['db.options']['dbname']   = getenv('FA_FULLARM_PASTELARIA_DB_DBNAME');

$configDoctrineOrm['orm.proxies_dir'] = ROOT_PATH . 'data/DoctrineORM/Proxy';


$configParams['doc_link']   = getenv('FA_FULLARM_PASTELARIA_PARAMS_DOC_LINK');
$configParams['app_id']     = getenv('FA_FULLARM_PASTELARIA_PARAMS_PASTELARIA_APP_ID');
$configParams['app_secret'] = getenv('FA_FULLARM_PASTELARIA_PARAMS_PASTELARIA_APP_SECRECT');

$app->register(new MonologServiceProvider(), $configMonolog);
$app->register(new DoctrineServiceProvider, $configDoctrine);
$app->register(new DoctrineOrmServiceProvider(), $configDoctrineOrm);

$app['Pastelaria.params'] = $configParams;

$app['asset_path'] = '/assets';
$app['lib_path']   = '/lib';

$appCtrl = new Pastelaria\App();
$appCtrl->setAuthenticationService(new AuthenticationServiceApp());
$app->mount('/', $appCtrl);

$apiCtrl = new Pastelaria\Api();
$apiCtrl->setAuthenticationService(new AuthenticationServiceApi());
$app->mount('/api/v1', $apiCtrl);


return $app;