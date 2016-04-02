<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\data\Pagination;

use sergey144010\RutrackerSpy\Configuration as Config;
use sergey144010\RutrackerSpy\Check;
use sergey144010\RutrackerSpy\DbYii;
use sergey144010\RutrackerSpy\ActiveTable;
use sergey144010\RutrackerSpy\Logger as Log;
use sergey144010\RutrackerSpy\Main;

use app\models\Theme;

class SiteController extends Controller
{

    public function actionIndex()
    {
        $configFileName = "../config.php";
        $themeDir = "../".Config::$themeSpyDir.DIRECTORY_SEPARATOR;

        try{
            $isFileConfig = Check::isFile($configFileName);
        }catch (\Exception $error){
            $isFileConfig = false;
        }
        try{
            $configCheck = Check::configCheck($configFileName);
        }catch (\Exception $error){
            $configCheck = false;
        }

        try{
            $filtrCheck = Check::filtrCheck($themeDir);
        }catch (\Exception $error){
            $filtrCheck = false;
        }

        return $this->render('index', [
             "isFileConfig" => $isFileConfig,
             "configCheck" => $configCheck,
             "filtrCheck" => $filtrCheck
         ]);
    }

    public function actionListTheme()
    {
        if(Yii::$app->request->isGet && isset($_GET['themeId'])){

            ActiveTable::setTable($_GET['themeId']);
            #$topicArray = ActiveTable::find()->asArray()->all();

            $query = ActiveTable::find();
            $countQuery = clone $query;
            $pages = new Pagination(['totalCount' => $countQuery->count()]);
            $models = $query->offset($pages->offset)
                ->limit($pages->limit)
                #->asArray()
                ->all();

            return $this->render('theme',[
                #"topicArray"=>$topicArray,
                'models' => $models,
                'pages' => $pages
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
