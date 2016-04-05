<?php
namespace RutrackerSpy;

require_once("autoload.php");

use sergey144010\RutrackerSpy\Configuration as Config;
use sergey144010\RutrackerSpy\Logger as Log;
use sergey144010\RutrackerSpy\Main;

#\sergey144010\RutrackerSpy\Event::$configAfter = function (){Config::$logTurn=false;};
new Config();

Log::add("Start");
try{
    $program = new Main();
    $program->run();
}catch (\Exception $error){
    Log::add($error->getMessage());
    Log::add($error->getCode());
    Log::add($error->getFile());
    Log::add($error->getLine());
};

