<?php

use yii\db\Migration;

/**
 * Class m180816_212811_life_aws_sources
 */
class m180816_212811_life_aws_sources extends Migration
{
    public function safeUp()
    {
        $this->createTable('life_aws_sources', [
                'name' => $this->string()->notNull(),
                'root' => $this->string()->defaultValue('/'),
            'region' => $this->string()->notNull(),
    ]);
        $this->addPrimaryKey('pk_life_aws_sources_name', 'life_aws_sources', 'name');
    }

    public function safeDown() {
        $this->dropTable('life_aws_sources');
    }

}
