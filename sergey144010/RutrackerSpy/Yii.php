<?php
namespace sergey144010\RutrackerSpy;

use sergey144010\RutrackerSpy\Configuration as Config;

class Yii
{
    public static function init()
    {
        defined('YII_DEBUG') or define('YII_DEBUG', true);
        defined('YII_ENV') or define('YII_ENV', 'dev');

#require(__DIR__ . '/vendor/autoload.php');
#require(__DIR__ . '/vendor/yiisoft/yii2/Yii.php');
        #require('../../vendor/yiisoft/yii2/Yii.php');
        require('vendor/yiisoft/yii2/Yii.php');

        $config = [
            'id' => 'rutrackerSpy',
            'basePath' => dirname(__DIR__),
            'extensions' => require('vendor/yiisoft/extensions.php'),
            'components' => [
                'db' => [
                    'class' => 'yii\db\Connection',
                    'dsn' => Config::$dbType.':host='.Config::$dbHost.';dbname='.Config::$dbName,
                    'username' => Config::$dbUser,
                    'password' => Config::$dbPass,
                    'charset' => 'cp1251',
                ],
            ],
        ];
        return new \yii\console\Application($config);
    }
}