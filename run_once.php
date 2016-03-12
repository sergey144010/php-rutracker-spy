<?php
namespace RutrackerSpy;

require_once("autoload.php");

use sergey144010\RutrackerSpy\Configuration as Config;
use sergey144010\RutrackerSpy\Logger as Log;
use sergey144010\RutrackerSpy\Main;

new Config();

Log::add("Start");
$program = new Main();
$program->run();
