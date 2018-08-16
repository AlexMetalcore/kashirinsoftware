<?php

use yii\db\Migration;
use yii\db\Schema;
/**
 * Class m180813_152015_life_aws_sources
 */
class m180813_152015_life_aws_sources extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	$this->createTable('life_aws_sources', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
	    'root' => Schema::TYPE_STRING,
	    'region' => Schema::TYPE_STRING
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //echo "m180813_152015_life_aws_sources cannot be reverted.\n";
	//return false;
	$this->dropTable('life_aws_sources');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180813_152015_life_aws_sources cannot be reverted.\n";

        return false;
    }
    */
}
