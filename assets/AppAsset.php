<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
		#'//allfont.ru/css/?fonts=lobster',
		#'//allfont.ru/css/?fonts=heinrichscript',
		#'css/my.css',
    ];
	public $cssOptions = [
		
	];
    public $js = [
	    #'js/site123.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        #'yii\bootstrap\BootstrapPluginAsset',
        #'yii\bootstrap\BootstrapThemeAsset',
		#'ivs\w1\TestAsset',
		#'tinymce\tinymce\TinymcePluginAsset',
    ];
}
