<?php

namespace app\models;

use yii\db\ActiveRecord;

class Theme extends ActiveRecord
{
    /**
     * @return string �������� �������, �������������� � ���� ActiveRecord-�������.
     */
    public static function tableName()
    {
        return 'theme';
    }
}