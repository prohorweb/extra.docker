<?php

use yii\db\Migration;

class m210204_204424_add_bonus_timer_to_settings_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%settings}}', 'bonus_start', $this->dateTime());
        $this->addColumn('{{%settings}}', 'bonus_end', $this->dateTime());
    }

    public function safeDown()
    {
        $this->dropColumn('{{%settings}}', 'bonus_start');
        $this->dropColumn('{{%settings}}', 'bonus_end');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210204_204424_add_bonus_timer_to_settings_table cannot be reverted.\n";

        return false;
    }
    */
}
