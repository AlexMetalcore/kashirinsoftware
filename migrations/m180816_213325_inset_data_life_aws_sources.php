<?php

use yii\db\Migration;
use app\components\AWS;
/**
 * Class m180816_213325_inset_data_life_aws_sources
 */
class m180816_213325_inset_data_life_aws_sources extends Migration
{
   public function safeUp()
   {
       $this->insert('life_aws_sources', [
           'name' => AWS::bucket_one,
            'region' => 'us-east-1',
        ]);
       $this->insert('life_aws_sources', [
           'name' => AWS::bucket_two,
           'region' => 'eu-west-2',
       ]);
       $this->insert('life_aws_sources', [
           'name' => AWS::bucket_three,
           'region' => 'eu-west-3',
       ]);
   }
}
