<?php

use yii\db\Migration;

/**
 * Class m180605_114206_add_intro
 */
class m180605_114206_add_intro extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%group_programs}}', 'intro', $this->string()->notNull()->after('title'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //echo "m180605_114206_add_intro cannot be reverted.\n";

        $this->dropColumn('{{%group_programs}}', 'intro');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180605_114206_add_intro cannot be reverted.\n";

        return false;
    }
    */
}
