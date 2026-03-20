<?php

use yii\db\Migration;

class m170704_122326_legal extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%club}}', 'legal', $this->text());
        $this->addColumn('{{%club}}', 'privacy', $this->text());
    }

    public function safeDown()
    {
        //echo "m170704_122326_legal cannot be reverted.\n";
        $this->dropColumn('{{%club}}', 'legal');
        $this->dropColumn('{{%club}}', 'privacy');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170704_122326_legal cannot be reverted.\n";

        return false;
    }
    */
}
