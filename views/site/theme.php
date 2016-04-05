<?php

use sergey144010\RutrackerSpy\Configuration as Config;
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use yii\bootstrap\Html;
use yii\bootstrap\Button;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;

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
    ],
    'options' => ['class' => 'navbar-nav'],
]);

NavBar::end();

// отображаем ссылки на страницы
echo LinkPager::widget([
    'pagination' => $pages,
]);

$theadName = iconv("cp1251","utf-8","Название");
$theadStatus = iconv("cp1251","utf-8","");
$theadSize = iconv("cp1251","utf-8","Размер");
$theadFile = iconv("cp1251","utf-8","Скачать");

#$link = false;
echo "<table class='table'>";
echo "<thead>";
echo "<tr><th>$theadName</th><th>$theadStatus</th><th>$theadSize</th><th>$theadFile</th></tr>";
echo "</thead>";
foreach ($models as $topic) {
    echo "<tr><td>";
    echo "<a target='_blank' href='http://rutracker.org/forum/".$topic['href']."'>";
    echo iconv("cp1251","utf-8", base64_decode($topic['name']));
    echo "</a>";
    echo " ";
    echo "</td><td>";

    if($topic['watch']==1){
        $class = 'btn btn-success btn-xs';
    }else{
        $class = 'btn btn-default btn-xs';
    };
    $label = "<span class='glyphicon glyphicon-ok' aria-hidden='true'></span>";

    Pjax::begin(["enablePushState"=>false, "timeout"=>"10000"]);
    echo  Html::a($label, ["site/watch", "themeId"=>$themeId, "id"=>$topic['id']], ["class"=>$class]);
    Pjax::end();

    echo "</td><td>";
    echo base64_decode($topic['size']);
    echo "</td><td>";
    $id = substr($topic['themeId'],3);
    $href = "../".Config::$torrentDir."/t=".$id.".torrent";
    echo Html::a("Download", $href, [
        "class"=>"btn btn-success btn-xs",
        "type"=>"button",
    ]);
    echo "</td></tr>";
};
echo "</table>";

#echo $link;

// отображаем ссылки на страницы
echo LinkPager::widget([
    'pagination' => $pages,
]);