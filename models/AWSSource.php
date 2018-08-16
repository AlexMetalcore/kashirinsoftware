<?php

namespace app\models;

use yii\db\ActiveRecord;

class AWSSource extends ActiveRecord {

    public static function tableName()
    {
        return 'life_aws_sources';
    }
}