<?php

use yii\db\Migration;

/**
 * Class m191031_120455_alter_timer_column_to_settings_table
 */
class m191031_120455_alter_timer_column_to_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%settings}}', 'timer_start', $this->dateTime());
        $this->alterColumn('{{%settings}}', 'timer_end', $this->dateTime());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //echo "m191031_120455_alter_timer_column_to_settings_table cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191031_120455_alter_timer_column_to_settings_table cannot be reverted.\n";

        return false;
    }
    */
}
