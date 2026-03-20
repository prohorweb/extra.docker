<?php

use yii\db\Migration;

class m170802_142206_sort_services extends Migration
{
    public function safeUp()
    {
        $this->update('{{%services}}', ['position' => 400], 'id = 4');
        $this->update('{{%services}}', ['position' => 40], 'id = 3');
        $this->update('{{%services}}', ['position' => 30], 'id = 2');
        $this->update('{{%services}}', ['position' => 20], 'id = 4');

    }

    public function safeDown()
    {
        echo "m170802_142206_sort_services cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170802_142206_sort_services cannot be reverted.\n";

        return false;
    }
    */
}
