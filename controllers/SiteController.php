<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\data\Pagination;

use yii\bootstrap\Html;

use sergey144010\RutrackerSpy\Configuration as Config;
use sergey144010\RutrackerSpy\Check;
use sergey144010\RutrackerSpy\DbYii;
use sergey144010\RutrackerSpy\ActiveTable;
use sergey144010\RutrackerSpy\ActiveTableSearch;
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

            /*
            ActiveTable::setTable($_GET['themeId']);
            $themeId = $_GET['themeId'];
            #$topicArray = ActiveTable::find()->asArray()->all();

            $query = ActiveTable::find();
            $countQuery = clone $query;
            $pages = new Pagination(['totalCount' => $countQuery->count()]);
            $models = $query->offset($pages->offset)
                ->limit($pages->limit)->orderBy(['id' => SORT_DESC])
                #->asArray()
                ->all();

            return $this->render('theme',[
                #"topicArray"=>$topicArray,
                'models' => $models,
                'pages' => $pages,
                'themeId' => base64_encode($themeId)
            ]);
            */

            ActiveTable::setTable($_GET['themeId']);

            $searchModel = new ActiveTableSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('theme', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
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

    public function actionTest()
    {
        if($_GET['themeId']){
            # &themeId=f%3D2093
            ActiveTable::setTable($_GET['themeId']);
            $themeId = $_GET['themeId'];

            $searchModel = new ActiveTableSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('test', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);

        };
        return false;
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

    public function actionWatch()
    {
        #return "ok";
/*
        if(Yii::$app->request->isPjax){
            if(isset($_GET['themeId'])){
                if(isset($_GET['id'])){
                    return "ok";
                }else{
                    return "NO GET id";
                };
            }else{
                return "NO GET themeId";
            };
        }else{
            return "NO PJAX";
        };

   */
/*
        if(Yii::$app->request->isPjax && isset($_GET['themeId']) && isset($_GET['id'])){
            $themeId = $_GET['themeId'];
            $id = $_GET['id'];
            $label = "<span class='glyphicon glyphicon-ok' aria-hidden='true'></span>";
            ActiveTable::setTable($themeId);
            $topic = ActiveTable::findOne($id);
            return "OK";
        }else{
            return "ERROR";
        };

       */
        if(Yii::$app->request->isPjax && isset($_GET['themeId']) && isset($_GET['id'])){

            $themeId = base64_decode($_GET['themeId']);
            #$themeId = $_GET['themeId'];
            $id = $_GET['id'];

            $label = "<span class='glyphicon glyphicon-ok' aria-hidden='true'></span>";

            ActiveTable::setTable($themeId);
            $topic = ActiveTable::findOne($id);
            /**
             * @var ActiveTable $topic
             */
            if($topic->watch == "1"){
                $topic->watch = 0;
                $topic->save();
                $class = 'btn btn-default btn-xs';
                return Html::a($label, ["site/watch", "themeId"=>base64_encode($themeId), "id"=>$id], ["class"=>$class]);
            }
            elseif($topic->watch == "0"){
                $topic->watch = 1;
                $topic->save();
                $class = 'btn btn-success btn-xs';
                return Html::a($label, ["site/watch", "themeId"=>base64_encode($themeId), "id"=>$id], ["class"=>$class]);
            }else{
                    $topic->watch = 1;
                    $topic->save();
                    $class = 'btn btn-success btn-xs';
                    return Html::a($label, ["site/watch", "themeId"=>base64_encode($themeId), "id"=>$id], ["class"=>$class]);
            };
            return "NO";
        }else{
            #var_dump(Yii::$app->request->queryParams);
            return "ERROR";
        };

    }

}
