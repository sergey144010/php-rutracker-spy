<?php
use sergey144010\RutrackerSpy\Configuration as Config;
use sergey144010\RutrackerSpy\Db;
use sergey144010\RutrackerSpy\Event;

defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'prod');

// ğåãèñòğàöèÿ çàãğóç÷èêà êëàññîâ Composer
require(__DIR__ . '/../vendor/autoload.php');

// ïîäêëş÷åíèå ôàéëà êëàññà Yii
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

require('../autoload.php');
// Ìåíÿåì ïóòü äî ôàéëà êîíôèãà
Event::$configBefore = function (){Config::$configFileName="../config.php";};
// Îòêëş÷àåì ëîã
Event::$configAfter = function (){Config::$logTurn=false;};
try{
    new Config();
    // çàãğóçêà êîíôèãóğàöèè ïğèëîæåíèÿ
#$config = require(__DIR__ . '/../config/web.php');
    $config = [
        'id' => 'rutrackerSpy',
        'basePath' => dirname(__DIR__),
        'extensions' => require('../vendor/yiisoft/extensions.php'),
        'components' => [
            'db' => [
                'class' => 'yii\db\Connection',
                'dsn' => Config::$dbType.':host='.Config::$dbHost.';dbname='.Config::$dbName,
                'username' => Config::$dbUser,
                'password' => Config::$dbPass,
                'charset' => 'cp1251',
            ],
            'request' => [
                'cookieValidationKey' => '123',
            ],
        ],
    ];
}catch (\Exception $error){
    // çàãğóçêà êîíôèãóğàöèè ïğèëîæåíèÿ
#$config = require(__DIR__ . '/../config/web.php');
    $config = [
        'id' => 'rutrackerSpy',
        'basePath' => dirname(__DIR__),
        'extensions' => require('../vendor/yiisoft/extensions.php'),
        'components' => [
            'request' => [
                'cookieValidationKey' => '123',
            ],
        ],
    ];
};
Event::$configAfter = null;
Event::$configBefore = null;

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

// ñîçäàíèå è êîíôèãóğàöèÿ ïğèëîæåíèÿ, à òàêæå âûçîâ ìåòîäà äëÿ îáğàáîòêè âõîäÿùåãî çàïğîñà

(new yii\web\Application($config))->run();