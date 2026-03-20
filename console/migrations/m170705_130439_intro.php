<?php

use yii\db\Migration;

class m170705_130439_intro extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('{{%events}}', 'intro', $this->text()->notNull());
    }

    public function safeDown()
    {
        //echo "m170705_130439_intro cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170705_130439_intro cannot be reverted.\n";

        return false;
    }
    */
}
