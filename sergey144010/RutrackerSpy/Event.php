<?php
namespace sergey144010\RutrackerSpy;

use sergey144010\RutrackerSpy\Logger as Log;
use sergey144010\RutrackerSpy\Buffer;
use sergey144010\RutrackerSpy\Configuration as Config;

class Event
{
    public static $configBefore;
    public static $configAfter;

    public static $downloadFileTorrentAfter;
    public static $elementAddAfter;

    public static function attach($event)
    {
        if(isset($event)){
            call_user_func($event);
        };
    }

    public function configBefore()
    {
        if(is_null(self::$configBefore)){
            return null;
        };
        return self::$configBefore;
    }
    public function configAfter()
    {
        if(is_null(self::$configAfter)){
            return null;
        };
        return self::$configAfter;
    }

    public function downloadFileTorrentAfter()
    {
        if(is_null(self::$downloadFileTorrentAfter)){
            return null;
        };
        return self::$downloadFileTorrentAfter;
    }
    public function elementAddAfter()
    {
        if(is_null(self::$elementAddAfter)){
            #$this->sendViaFtp();
            return null;
        };
        return self::$elementAddAfter;
    }

    public function sendViaFtp()
    {
        $torrentFile = Config::$torrentDir.DIRECTORY_SEPARATOR.Buffer::$theme['torrentFile'].'.torrent';
        /*
         * Здесь коннект к фтп серверу и отправка файла.
         * 5 попыток и переходим дальше
         */
        #Log::add(Buffer::$theme['torrentFile']);
    }

}