<?php

use yii\db\Migration;

/**
 * Class m180923_092609_buy
 */
class m180923_092609_buy extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%buy}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'cost' => $this->float()->notNull(),
            'title' => $this->string()->notNull(),
            'created_at' => $this->timestamp()->null(),
        ], $tableOptions);

        $this->createIndex('user_id', '{{%buy}}', 'user_id');
        $this->addForeignKey((!empty($this->getDb()->tablePrefix) ? 'en_' : '') . 'FK_buy_userClabis', '{{%buy}}', 'user_id', 'user_clabis', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //echo "m180923_092609_buy cannot be reverted.\n";

        $this->dropForeignKey((!empty($this->getDb()->tablePrefix) ? 'en_' : '') . 'FK_buy_userClabis', '{{%buy}}');
        $this->dropIndex('user_id', '{{%buy}}');

        $this->dropTable('{{%buy}}');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180923_092609_buy cannot be reverted.\n";

        return false;
    }
    */
}
