<?php
/**
 * Создаем директории
 * cookie, log, themeSpy, torrent
 */
namespace sergey144010\RutrackerSpy;

use sergey144010\RutrackerSpy\Configuration as Config;
use sergey144010\RutrackerSpy\Event;

class Create
{
    public function init()
    {
        // Отключаем лог
        Event::$configAfter = function (){Config::$logTurn=false;};
        new Config();
    }

    public function dir($name)
    {
        if(!is_dir($name)){
            if(mkdir($name)){
                echo "Create directory ".$name.PHP_EOL;
            }else{
                echo "Failed to create directory ".$name.PHP_EOL;
            };
        }else{
            echo "Directory already exist ".$name.PHP_EOL;
        };
        return $this;
    }

    public function file($name)
    {
        if(!is_file($name)){
            $count = file_put_contents($name, "");
            if($count===false){
                echo "Failed to create file ".$name.PHP_EOL;
            }else{
                echo "Create file ".$name.PHP_EOL;
            };
        }else{
            echo "File already exist ".$name.PHP_EOL;
        };
        return $this;
    }

    public function create()
    {
        $this->dir(Config::$cookieDir);
        $this->dir(Config::$logDir);
        $this->dir(Config::$themeSpyDir);
        $this->file(Config::$themeSpyDir.DIRECTORY_SEPARATOR.Config::$themeSpyFileName);
        $this->dir(Config::$torrentDir);
        $this->dir(Config::$torrentDirTemp);
    }

    public function run()
    {
        $this->init();
        $this->create();
    }
}

require_once("autoload.php");
(new Create())->run();
echo "End process".PHP_EOL;