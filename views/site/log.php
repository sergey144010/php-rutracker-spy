<?php
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use yii\bootstrap\Html;
use \yii\helpers\Url;

/* @var $this yii\web\View */
$this->title = 'RutrackerSpy';

NavBar::begin([
    "options" => ["class" => "navbar navbar-default navbar-fixed-top"],
    "brandLabel" => "RutrackerSpy"
]);

echo Nav::widget([
    'items' => [
        ['label' => 'Themes', 'url' => ['/site/list-theme']],
        ['label' => 'Log', 'url' => ['/site/log']],
    ],
    'options' => ['class' => 'navbar-nav'],
]);

NavBar::end();

echo Html::a(
    "Clean Log",
    Url::to(['site/log', 'clear'=>'ok']),
    [
        "class"=>"btn btn-primary",
        "type"=>"button",
    ]
);
echo "&nbsp;";
echo Html::a(
    "Make Report",
    Url::to(['site/log', 'report'=>'ok']),
    [
        "class"=>"btn btn-primary",
        "type"=>"button",
    ]
);

echo "<br>";
echo "<br>";

echo $log;
?>
