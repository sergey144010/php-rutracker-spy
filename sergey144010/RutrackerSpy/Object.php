<?php
/**
 * Created by PhpStorm.
 * User: Мария
 * Date: 05.05.2016
 * Time: 16:44
 */

namespace sergey144010\RutrackerSpy;

use \Exception;

class Object
{
    public static function create($class, $interface = null)
    {
        if(isset($interface)){
            if(class_exists($class)){
                $class = new $class;
                if($class instanceof $interface){
                    return $class;
                }else{
                    throw new Exception ('Class '.$class.' not instance interface '.$interface);
                }
            }else{
                throw new Exception ('Class '.$class.' not found');
            }
        };
        if(class_exists($class)){
            return new $class;
        }else{
            throw new Exception ('Class '.$class.' not found');
        }
    }
}