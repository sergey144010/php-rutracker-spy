<?php

#use yii\helpers\Html;
use sergey144010\RutrackerSpy\Configuration as Config;
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use yii\grid\GridView;
use yii\bootstrap\Html;
use yii\helpers\Url;
use sergey144010\RutrackerSpy\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ThemeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


/* @var $this yii\web\View */
$this->title = 'RutrackerSpy';

$link = false;

NavBar::begin([
    "options" => ["class" => "navbar navbar-default navbar-fixed-top"],
    "brandLabel" => "RutrackerSpy"
]);

echo Nav::widget([
    'items' => [
        ['label' => 'Themes', 'url' => ['/site/list-theme']],
        ['label' => 'Log', 'url' => ['/site/log']],
        ['label' => 'Template', 'url' => ['/site/template']],
    ],
    'options' => ['class' => 'navbar-nav'],
]);

NavBar::end();

?>

    <?= GridView::widget([
        'tableOptions' => ['class' => 'table'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{summary}\n{pager}\n{items}\n{pager}",
        'columns' => [

            [
                'attribute' => 'name',
                'label' => iconv("cp1251", "utf-8", 'Название'),
                'content' => function($data){
                    return Html::a(
                        iconv("cp1251", "utf-8", $data->name),
                        #Url::to(['site/list-theme', 'themeId'=>$data->themeId]),
                        "http://rutracker.org/forum/".$data->href,
                        [
                            #"class"=>"btn btn-primary btn-xs",
                            #"type"=>"button",
                            "target" => '_blank',
                        ]
                    );
                }
            ],
            [
                #'attribute' => 'watch',
                'label' => '',
                'content' => function($data){
                    if($data->watch==1){
                        $class = 'btn btn-success btn-xs';
                    }else{
                        $class = 'btn btn-default btn-xs';
                    };
                    $label = "<span class='glyphicon glyphicon-ok' aria-hidden='true'></span>";

                    Pjax::begin(["enablePushState"=>false, "timeout"=>"50000"]);
                    Pjax::end();

                    $out = Pjax::$init;
                    $out .= Html::a($label, ["site/watch", "themeId"=>base64_encode($_GET['themeId']), "id"=>$data->id], ["class"=>$class]);
                    $out .= Pjax::$run;

                    return $out;
                }
            ],
            [
                #'attribute' => 'size',
                'label' => iconv("cp1251", "utf-8", 'Размер'),
                'content' => function($data){
                    return base64_decode($data->size);
                }
            ],
            [
                #'attribute' => 'torrentFile',
                'label' => iconv("cp1251", "utf-8", 'Скачать'),
                'content' => function($data){
                    $id = substr($data->themeId, 3);
                    $href = "../".Config::$torrentDir."/t=".$id.".torrent";
                    return Html::a("Download", $href, [
                        "class"=>"btn btn-success btn-xs",
                        "type"=>"button",
                    ]);
                }
            ],

        ],
    ]);
    ?>
