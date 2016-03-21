<?php
namespace sergey144010\RutrackerSpy;

use sergey144010\RutrackerSpy\Configuration as Config;
use sergey144010\RutrackerSpy\Event;

class CreateThemeConfig
{
    public $fileName;
    public $content;
    public $argv;
    protected $status;

    public function init()
    {
        // Отключаем лог
        Event::$configAfter = function (){Config::$logTurn=false;};
        new Config();
    }

    public function run($argv)
    {
        $this->argv = $argv;
        $this->init();
        $this->setContent();
        $this->createFiles();
    }

    public function file()
    {
        if(!is_file($this->fileName)){
            $count = file_put_contents($this->fileName, $this->content);
            if($count===false){
                echo "Failed to create file ".$this->fileName.PHP_EOL;
                $this->status = 0;
            }else{
                echo "Create file ".$this->fileName.PHP_EOL;
                $this->status = 1;
            };
        }else{
            echo "File already exist ".$this->fileName.PHP_EOL;
            $this->status = 2;
        };
        return $this;
    }

    public function setContent()
    {
        $content = file_get_contents("theme_config_base64_encode.txt");
        $content = base64_decode($content);
        $this->content = $content;
    }

    public function create()
    {
        $time = time();
        $fileName = Config::$themeSpyDir.DIRECTORY_SEPARATOR.$time.".filtr.php";
        do{
            if(is_file($fileName)){
                $time++;
                $fileName = Config::$themeSpyDir.DIRECTORY_SEPARATOR.$time.".filtr.php";
            }else{
                break;
            };
        }while(true);
        $this->fileName = $fileName;
        return $this;
    }

    public function createFiles()
    {
        if(!empty($this->argv[1]) && empty($this->argv[2])){
            $this->argv[1] = (integer)$this->argv[1];
            if($this->argv[1]>0){
                for($i=1;$i<=$this->argv[1];$i++){
                    $this->create()->file();
                };
            };
        }elseif(!empty($this->argv[1]) && !empty($this->argv[2])){
            $fileName = Config::$themeSpyDir.DIRECTORY_SEPARATOR.$this->argv[2].".filtr.php";
            $this->fileName = $fileName;
            $this->file();
        }else{
            $this->create()->file();
        };
    }
}

require_once("autoload.php");
// Берём количество из командной строки
/*
 echo "Console command\n";
 echo $argc . PHP_EOL;
 print_r($argv);
 */
# $argv[1] = 5;
(new CreateThemeConfig())->run($argv);
echo "End process".PHP_EOL;