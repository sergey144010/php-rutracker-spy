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
         #['label' => 'About', 'url' => ['/site/about']],
     ],
     'options' => ['class' => 'navbar-nav'],
]);

NavBar::end();

echo "<table class='table'>";
foreach ($themeArray as $theme) {
    echo "<tr><td>";
    echo Html::a(
        iconv("cp1251","utf-8",base64_decode($theme["name"])),
        Url::to(['site/index', 'themeId'=>$theme["themeId"]]),
        [
            #"class"=>"btn btn-primary btn-xs",
            "type"=>"button",
        ]
    );
    echo "</td></tr>";
};
echo "</table>";

?>
