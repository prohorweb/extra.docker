<?php

use yii\db\Migration;

/**
 * Class m180827_143741_add_content
 */
class m180827_143741_add_content extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%group_programs}}', 'content', $this->text()->after('intro'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //echo "m180827_143741_add_content cannot be reverted.\n";
        $this->dropColumn('{{%group_programs}}', 'content');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180827_143741_add_content cannot be reverted.\n";

        return false;
    }
    */
}
