<?php

use yii\db\Migration;

/**
 * Class m171218_120606_settings_code
 */
class m171218_120606_settings_code extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('{{%settings}}', 'code_head', $this->text());
        $this->alterColumn('{{%settings}}', 'code_body', $this->text());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        //echo "m171218_120606_settings_code cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171218_120606_settings_code cannot be reverted.\n";

        return false;
    }
    */
}
