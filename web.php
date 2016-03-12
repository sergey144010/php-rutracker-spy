<?php
namespace RutrackerSpy;

require_once("autoload.php");

use sergey144010\RutrackerSpy\Configuration as Config;
use sergey144010\RutrackerSpy\Db;
use sergey144010\RutrackerSpy\Event;

class Web
{
    public function __construct()
    {
        // Отключаем лог
        Event::$configAfter = function (){Config::$logTurn=false;};
        $config = new Config();
        Event::$configAfter = null;
        #var_dump(get_class_vars(get_class($config)));
        #var_dump(get_class_vars(get_class((new Event()))));
    }

    public function index()
    {
        $db = new Db();
        $db->tableSet("f=2200");
        $array = $db->tableTake();

        echo "<br>";
        foreach ($array as $row) {
            print_r(base64_decode($row['name']));
            echo " - ".$row['themeId'];
            echo "<br>";
        };
        echo "<br>";
        echo "##############################################";
        echo "<br>";

        $db->tableSet("f=2093");
        $array = $db->tableTake();

        echo "<br>";
        foreach ($array as $row) {
            print_r(base64_decode($row['name']));
            echo " - ".$row['themeId'];
            echo "<br>";
        };
    }

}

$web = new Web();
$web->index();