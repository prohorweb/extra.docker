<?php

use yii\db\Migration;

/**
 * Class m180716_095407_club_main_content
 */
class m180716_095407_club_main_content extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%club}}', 'main_content', $this->text()->after('content'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //echo "m180716_095407_club_main_content cannot be reverted.\n";
        $this->dropColumn('{{%club}}', 'main_content');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180716_095407_club_main_content cannot be reverted.\n";

        return false;
    }
    */
}
