<?php
namespace sergey144010\RutrackerSpy;

class Event
{
    public static $configBefore;
    public static $configAfter;

    public static function attach($event)
    {
        if(isset($event)){
            call_user_func($event);
        };
    }

}