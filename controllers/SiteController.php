<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

use sergey144010\RutrackerSpy\Configuration as Config;
use sergey144010\RutrackerSpy\DbYii;
use sergey144010\RutrackerSpy\ActiveTable;
use sergey144010\RutrackerSpy\Logger as Log;
use sergey144010\RutrackerSpy\Main;

use app\models\Theme;

class SiteController extends Controller
{

    public function actionIndex()
    {
        $isFileConfig = false;
        if(!is_file("../config.php")){
            $isFileConfig = false;
        }else{
            $isFileConfig = true;
        };
         return $this->render('index', [
             "isFileConfig" => $isFileConfig
         ]);
    }

    public function actionListTheme()
    {
        if(Yii::$app->request->isGet && isset($_GET['themeId'])){

            ActiveTable::setTable($_GET['themeId']);
            $topicArray = ActiveTable::find()->asArray()->all();
            return $this->render('theme',[
                "topicArray"=>$topicArray,
            ]);

        }else{

            $error = false;
            $themeArray = false;
            try{
                $themeArray = Theme::find()->asArray()->all();
            }catch (\Exception $error){};

            return $this->render('listTheme',[
                "themeArray"=>$themeArray,
                "error"=>$error
            ]);
        }

    }

    public function actionLog()
    {
        $fileLog = "../".Config::$logDir.DIRECTORY_SEPARATOR.Config::$logFileName;

        if(isset($_GET['clear']) && $_GET['clear']=='ok'){
            file_put_contents($fileLog,"");
        };

        $log = false;
        if(is_file($fileLog)){
            $array = file($fileLog);
            foreach ($array as $row) {
                $log .= $row."<br>";
            }

        };
        return $this->render("log",["log"=>$log]);
    }


}
