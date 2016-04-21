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

                $type = gettype($string);

                #$type = "is undefined";
/*
                if(is_array($string)){$type = "is array";};
                if(is_object($string)){$type = "is object";};
                if(is_resource($string)){$type = "is resource";};
                if(is_bool($string)){$type = "is bool";};
                if(is_callable($string)){$type = "is callable";};
                if(is_double($string)){$type = "is double";};
                if(is_float($string)){$type = "is float";};
                if(is_int($string)){$type = "is int";};
                if(is_long($string)){$type = "is long";};
                if(is_null($string)){$type = "is null";};
                if(is_numeric($string)){$type = "is numeric";};
                if(is_real($string)){$type = "is real";};
                if(is_scalar($string)){$type = "is scalar";};
                if(is_string($string)){$type = "is string";};
*/

                self::add("ERROR: (Logger) : Given variable is not a string - ".$type);
            };
        };
    }

    public static function addToFile($file, $in_type)
    {
            if(is_string($in_type)){
                $time = date("[Y-m-d H:i:s]");
                $string = $time." ".$in_type.PHP_EOL;
                #echo $string;
                file_put_contents($file, $string, FILE_APPEND);
                return;
            };

            if(is_array($in_type)){

                $time = date("[Y-m-d H:i:s]");
                $string = $time.PHP_EOL;
                $string .= self::iterateArray($in_type);
                file_put_contents($file, $string, FILE_APPEND);
                return;
            };

        echo "Type input variable is ".gettype($in_type).", it not supported.".PHP_EOL;
        return;

    }

    /*
     * @return array || false
     */
    public static function iterateArray($array)
    {
        $string = false;
        foreach ($array as $key => $val ) {
            if(is_array($val)){
                $string .= $key." => ".PHP_EOL;
                $string .= self::iterateArray($val);
            };
            if(is_string($val)){
                $string .= $key." => ".$val.PHP_EOL;
            };
        };
        return $string;
    }
}