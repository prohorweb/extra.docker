<?php

use yii\db\Migration;

/**
 * Class m180829_130228_add_text_seo
 */
class m180829_130228_add_text_seo extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%seo}}', 'text', $this->text()->after('description'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //echo "m180829_130228_add_text_seo cannot be reverted.\n";
        $this->dropColumn('{{%seo}}', 'text');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180829_130228_add_text_seo cannot be reverted.\n";

        return false;
    }
    */
}
