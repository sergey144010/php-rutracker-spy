<?php

use sergey144010\RutrackerSpy\Configuration as Config;
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use yii\bootstrap\Html;
use \yii\helpers\Url;

/* @var $this yii\web\View */
$this->title = 'RutrackerSpy';

$link = false;

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
$link = false;
$link .= "<table class='table'>";
foreach ($topicArray as $topic) {
    $link .= "<tr><td>";
    $link .= "<a target='_blank' href='http://rutracker.org/forum/".$topic['href']."'>";
    $link .= iconv("cp1251","utf-8", base64_decode($topic['name']));
    $link .= "</a>";
    $link .= " ";

    $link .= "</td><td>";
    $link .= base64_decode($topic['size']);
    $link .= "</td><td>";
    $id = substr($topic['themeId'],3);
    $href = "../".Config::$torrentDir."/t=".$id.".torrent";
    $link .= Html::a("Download", $href, [
        "class"=>"btn btn-success btn-xs",
        "type"=>"button",
    ]);
    #$link .= "<a href='../".Config::$torrentDir."/t=".$id.".torrent'>";
    #$link .= $topic['themeId'];
    #$link .= "</a>";
    $link .= "</td></tr>";
};
$link .= "</table>";

echo $link;