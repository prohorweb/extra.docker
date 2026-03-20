<?php

use yii\db\Migration;

class m170704_111235_color extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('{{%program_classes}}', 'color', $this->string(30));
    }

    public function safeDown()
    {
        //echo "m170704_111235_color cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170704_111235_color cannot be reverted.\n";

        return false;
    }
    */
}
