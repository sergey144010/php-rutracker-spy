<?php
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use yii\bootstrap\Button;
use yii\bootstrap\Html;
use yii\helpers\Url;
use app\assets\HighlightAsset;

/* @var $this yii\web\View */
$this->title = 'RutrackerSpy';

HighlightAsset::register($this);

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

<h2>Settings templates</h2>

<div class="row">
<div class="col-md-1"><h3>1.</h3></div>
<div class="col-md-11">
<pre><code class="php">
"url" => "http://rutracker.org/forum/viewforum.php?f=2093",
"extraction" => function($string){
    $array = explode("/", $string, 2);
    $name = $array[0];
    $name = trim($name);
    return $name;
},
</code></pre>
</div>
</div>

<div class="row">
<div class="col-md-1"><h3>2.</h3></div>
<div class="col-md-11">
<pre><code class="php">
"url" => "http://rutracker.org/forum/viewforum.php?f=2200",
"extraction" => function($string){
    $array = explode("/", $string, 2);
    $name = $array[0];
    $name = trim($name);
    return $name;
},
</code></pre>
</div>
</div>

<div class="row">
    <div class="col-md-1"><h3>3.</h3></div>
    <div class="col-md-11">
<pre><code class="php">
"url" => "http://rutracker.org/forum/viewforum.php?f=22",
"extraction" => function($string){
    $reg = "/.*\(/i";
    preg_match($reg, $string, $matches);
    if(isset($matches[0])){
        $name = substr($matches[0], 0 , -1);
        $name = trim($name);
        return $name;
    };
    return $string;
},
</code></pre>
</div>
</div>
