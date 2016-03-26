<?php

namespace sergey144010\RutrackerSpy;

use yii\db\ActiveRecord;

class ActiveTable extends ActiveRecord
{
    public static $tableName;

    public static function tableName()
    {
        return self::$tableName;
    }

    public static function setTable($tableName)
    {
        self::$tableName = $tableName;
    }
}