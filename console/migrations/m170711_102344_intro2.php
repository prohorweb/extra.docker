<?php

use yii\db\Migration;

class m170711_102344_intro2 extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('{{%program_classes}}', 'intro', $this->text()->notNull());
    }

    public function safeDown()
    {
        //echo "m170711_102344_intro2 cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170711_102344_intro2 cannot be reverted.\n";

        return false;
    }
    */
}
