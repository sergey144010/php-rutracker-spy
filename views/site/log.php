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
        ['label' => 'Themes', 'url' => ['/site/index']],
        ['label' => 'Log', 'url' => ['/site/log']],
    ],
    'options' => ['class' => 'navbar-nav'],
]);

NavBar::end();

echo $log;
?>
