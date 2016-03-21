<?php
namespace sergey144010\RutrackerSpy;

use sergey144010\RutrackerSpy\Logger as Log;
use sergey144010\RutrackerSpy\Event;

class Configuration
{
    public static $configStatus;

    public static $dbClass;
    public static $dbType;
    public static $dbHost;
    public static $dbName;
    public static $dbUser;
    public static $dbPass;

    public static $clientUser;
    public static $clientPass;

    public static $torrentDir;
    public static $torrentDirTemp;
    public static $torrentAttempt;

    public static $logDir;
    public static $logFileName;
    public static $logTurn;

    public static $cookieDir;
    public static $cookieFileName;
    public static $cookieFileBbData;

    public static $themeSpyDir;
    public static $themeSpyFileName;

    public static $filtrClass;
    public static $filtrTurn;
    public static $filtrManyFiltr;
    public static $filtrSetting;

    static $timer;

    public function __construct()
    {
        Event::attach(Event::$configBefore);
        $this->run();
        Event::attach(Event::$configAfter);
        if(self::$configStatus){
            Log::add("Config set up successful");
        }else{
            Log::add("Config set up failed");
        };
    }

    public function run()
    {
        require_once("config.php");
        if(isset($config) && !isset(self::$configStatus)){

            // $config
            if(!isset(self::$configStatus)){
                self::$configStatus = true;
            };

            // $db
            if(!isset(self::$dbClass)){
                if(isset($config["db"]["class"])){
                    self::$dbClass = $config["db"]["class"];
                };
            };
            if(!isset(self::$dbType)) {
                if (isset($config["db"]["type"])) {
                    self::$dbType = $config["db"]["type"];
                };
            };
            if(!isset(self::$dbHost)) {
                if (isset($config["db"]["host"])) {
                    self::$dbHost = $config["db"]["host"];
                };
            };
            if(!isset(self::$dbName)) {
                if (isset($config["db"]["name"])) {
                    self::$dbName = $config["db"]["name"];
                };
            };
            if(!isset(self::$dbUser)) {
                if (isset($config["db"]["user"])) {
                    self::$dbUser = $config["db"]["user"];
                };
            };
            if(!isset(self::$dbPass)) {
                if (isset($config["db"]["pass"])) {
                    self::$dbPass = $config["db"]["pass"];
                };
            };

            // $client
            if(!isset(self::$clientUser)) {
                if (isset($config["client"]["user"])) {
                    self::$clientUser = $config["client"]["user"];
                };
            };
            if(!isset(self::$clientPass)) {
                if (isset($config["client"]["pass"])) {
                    self::$clientPass = $config["client"]["pass"];
                };
            };

            // $torrent
            if(!isset(self::$torrentDir)) {
                if (isset($config["torrent"]["dir"])) {
                    self::$torrentDir = $config["torrent"]["dir"];
                };
            };
            if(!isset(self::$torrentDirTemp)) {
                if (isset($config["torrent"]["dirTemp"])) {
                    self::$torrentDirTemp = $config["torrent"]["dirTemp"];
                };
            };
            if(!isset(self::$torrentAttempt)) {
                if (isset($config["torrent"]["attempt"])) {
                    self::$torrentAttempt = $config["torrent"]["attempt"];
                };
            };

            // $log
            if(!isset(self::$logDir)) {
                if (isset($config["log"]["dir"])) {
                    self::$logDir = $config["log"]["dir"];
                };
            };
            if(!isset(self::$logFileName)) {
                if (isset($config["log"]["fileName"])) {
                    self::$logFileName = $config["log"]["fileName"];
                };
            };
            if(!isset(self::$logTurn)) {
                if (isset($config["log"]["turn"])) {
                    self::$logTurn = $config["log"]["turn"];
                };
            };

            // $cookie
            if(!isset(self::$cookieDir)) {
                if (isset($config["cookie"]["dir"])) {
                    self::$cookieDir = $config["cookie"]["dir"];
                };
            };
            if(!isset(self::$cookieFileName)) {
                if (isset($config["cookie"]["fileName"])) {
                    self::$cookieFileName = $config["cookie"]["fileName"];
                };
            };
            if(!isset(self::$cookieFileBbData)) {
                if (isset($config["cookie"]["fileBbData"])) {
                    self::$cookieFileBbData = $config["cookie"]["fileBbData"];
                };
            };

            // $themeSpy
            if(!isset(self::$themeSpyDir)) {
                if (isset($config["themeSpy"]["dir"])) {
                    self::$themeSpyDir = $config["themeSpy"]["dir"];
                };
            };
            if(!isset(self::$themeSpyFileName)) {
                if (isset($config["themeSpy"]["fileName"])) {
                    self::$themeSpyFileName = $config["themeSpy"]["fileName"];
                };
            };

            // $timer
            if(!isset(self::$timer)) {
                if (isset($config["timer"]["time"])) {
                    self::$timer = $config["timer"]["time"];
                };
            };

            // $filtr
            if(!isset(self::$filtrClass)) {
                if (isset($config["filtr"]["class"])) {
                    self::$filtrClass = $config["filtr"]["class"];
                };
            };
            if(!isset(self::$filtrTurn)) {
                if (isset($config["filtr"]["turn"])) {
                    self::$filtrTurn = $config["filtr"]["turn"];
                };
            };
            if(!isset(self::$filtrManyFiltr)) {
                if (isset($config["filtr"]["manyFiltr"])) {
                    self::$filtrManyFiltr = $config["filtr"]["manyFiltr"];
                };
            };
            if(!isset(self::$filtrSetting)) {
                if (isset($config["filtr"]["setting"])) {
                    self::$filtrSetting = $config["filtr"]["setting"];
                };
            };

        }
    }
}