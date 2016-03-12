<?php
namespace sergey144010\RutrackerSpy;

use sergey144010\RutrackerSpy\Configuration as Config;

class Logger
{
    public static function add($string)
    {
        if(Config::$logTurn == "on"){
            if(is_string($string)){

                $time = date("[Y-m-d H:i:s]");

                $string = $time." ".$string.PHP_EOL;
                echo $string;

                $fileSave = Config::$logDir.DIRECTORY_SEPARATOR.Config::$logFileName;

                file_put_contents($fileSave, $string, FILE_APPEND);

            }else{
                self::add("ERROR: (Logger) : Given variable is not a string");
            };
        };
    }

    public function addToFile($file, $string)
    {
            if(is_string($string)){
                $time = date("[Y-m-d H:i:s]");
                $string = $time." ".$string.PHP_EOL;
                echo $string;
                file_put_contents($file, $string, FILE_APPEND);
            }else{
                echo "Is not string".PHP_EOL;
            };
    }
}