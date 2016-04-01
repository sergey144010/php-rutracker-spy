<?php
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use yii\bootstrap\Button;
use yii\bootstrap\Html;
use yii\helpers\Url;

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

if($isFileConfig){
    echo Button::widget([
        "label" => iconv("cp1251","utf-8","Файл конфига найден"),
        'options' => ['class' => 'btn btn-success btn-xs'],
    ]);
}else{
    echo Button::widget([
        "label" => iconv("cp1251","utf-8","Файл конфига не найден"),
        'options' => ['class' => 'btn btn-danger btn-xs'],
    ]);
};

echo "<br><br>";

if($configCheck){
    echo Button::widget([
        "label" => iconv("cp1251","utf-8","Конфиг правильно настроен"),
        'options' => ['class' => 'btn btn-success btn-xs'],
    ]);
}else{
    echo Button::widget([
        "label" => iconv("cp1251","utf-8","Конфиг не настроен"),
        'options' => ['class' => 'btn btn-danger btn-xs'],
    ]);
};

echo "<br><br>";

if($filtrCheck){
    echo Button::widget([
        "label" => iconv("cp1251","utf-8","Файлы фильтров найдены и фильтры настроены"),
        'options' => ['class' => 'btn btn-success btn-xs'],
    ]);
}else{
    echo Button::widget([
        "label" => iconv("cp1251","utf-8","Файлы фильтров не настроены"),
        'options' => ['class' => 'btn btn-danger btn-xs'],
    ]);
};
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Congratulations!</h1>

        <p class="lead">You have successfully installed your RutrackerSpy application.</p>

    </div>

</div>
