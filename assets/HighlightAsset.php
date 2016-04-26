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
class HighlightAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/highlight';
    public $css = [
        'styles/arduino-light.css',
    ];
	public $cssOptions = [
	];
    public $js = [
        'highlight.pack.js'
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];

    public static function register($view)
    {
        $view->registerJs('hljs.initHighlightingOnLoad();');
        parent::register($view);
    }
}
