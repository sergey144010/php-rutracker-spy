<?php
use sergey144010\RutrackerSpy\Configuration as Config;
use sergey144010\RutrackerSpy\Db;
use sergey144010\RutrackerSpy\Event;

defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'prod');

// ����������� ���������� ������� Composer
require(__DIR__ . '/../vendor/autoload.php');

// ����������� ����� ������ Yii
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

require('../autoload.php');
// ������ ���� �� ����� �������
Event::$configBefore = function (){Config::$configFileName="../config.php";};
// ��������� ���
Event::$configAfter = function (){Config::$logTurn=false;};
try{
    new Config();
    // �������� ������������ ����������
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
    // �������� ������������ ����������
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

// �������� � ������������ ����������, � ����� ����� ������ ��� ��������� ��������� �������

(new yii\web\Application($config))->run();