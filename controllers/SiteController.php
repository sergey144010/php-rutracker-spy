<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

use sergey144010\RutrackerSpy\Configuration as Config;
use sergey144010\RutrackerSpy\DbYii;
use sergey144010\RutrackerSpy\ActiveTable;

use app\models\Theme;

class SiteController extends Controller
{

    public function actionIndex123()
    {
        $link = false;
        $tables = false;

        $array = Yii::$app->db->createCommand('SHOW TABLES')->queryAll();

        foreach ($array as $table) {
            if($table["Tables_in_rutracker_spy"]=="theme"){continue;};

            $tables .= $table["Tables_in_rutracker_spy"]."|";

            ActiveTable::setTable($table["Tables_in_rutracker_spy"]);

            $arrayTable = ActiveTable::find()->all();

            foreach ($arrayTable as $row) {
                $link .= "<a target='_blank' href='http://rutracker.org/forum/".$row['href']."'>";
                $link .= iconv("cp1251","utf-8", base64_decode($row['name']));
                $link .= "</a>";
                $link .= " - ";
                $id = substr($row['themeId'],3);
                $link .= "<a href='../".Config::$torrentDir."/t=".$id.".torrent'>";
                $link .= $row['themeId'];
                $link .= "</a>";
                $link .= "<br>";$link .= "<br>";
            };


            $link .= "##############################################";
            $link .= "<br>";

        };
    }

    public function actionIndex()
    {
        if(Yii::$app->request->isGet && isset($_GET['themeId'])){
            ActiveTable::setTable($_GET['themeId']);
            $topicArray = ActiveTable::find()->asArray()->all();
            return $this->render('theme',[
                "topicArray"=>$topicArray,
            ]);

        }else{
            $themeArray = Theme::find()->asArray()->all();
            return $this->render('index',[
                "themeArray"=>$themeArray,
            ]);
        }

    }


}
