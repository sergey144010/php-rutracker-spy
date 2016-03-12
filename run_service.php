<?php
namespace RutrackerSpy;

require_once("autoload.php");

use sergey144010\RutrackerSpy\Configuration as Config;
use sergey144010\RutrackerSpy\Logger as Log;
use sergey144010\RutrackerSpy\Main;

new Config();

while (true){

    // Проверяем лог файл
    if(
        is_file(Config::$logDir.DIRECTORY_SEPARATOR.Config::$logFileName)
        &&
        filesize(Config::$logDir.DIRECTORY_SEPARATOR.Config::$logFileName)>(10*1024*1024)
    ){
        $handle = fopen(Config::$logDir.DIRECTORY_SEPARATOR.Config::$logFileName, "w");
        fwrite($handle,"");
        fclose($handle);
    };

    Log::add("Start");

    // Стартуем
    $program = new Main();
    $program->run();

    // Ждём
    Log::add("Wait ".Config::$timer." sec");
    time_sleep_until(time()+Config::$timer);
};