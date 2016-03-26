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
        $array = $db->showTables();
        foreach ($array as $table) {
            if($table[0]=="theme"){continue;};

            $db->tableSet($table[0]);
            $arrayTable = $db->tableTake();

            foreach ($arrayTable as $row) {
                echo "<a target='_blank' href='http://rutracker.org/forum/".$row['href']."'>";
                echo base64_decode($row['name']);
                echo "</a>";
                echo " - ";
                $id = substr($row['themeId'],3);
                echo "<a href='".Config::$torrentDir."/t=".$id.".torrent'>";
                echo $row['themeId'];
                echo "</a>";
                echo "<br>";
            };
            echo "##############################################";
            echo "<br>";

        };
    }

}

$web = new Web();
$web->index();