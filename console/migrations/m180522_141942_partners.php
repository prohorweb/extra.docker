<?php

use yii\db\Migration;

/**
 * Class m180522_141942_partners
 */
class m180522_141942_partners extends Migration
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

        $this->createTable('{{%partners}}', [
            'id' => $this->primaryKey(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'position' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'content' => $this->text()->notNull(),
            'img' => $this->string()->notNull(),
            'discount' => $this->string(),
            'is_gift' => $this->smallInteger()->notNull()->defaultValue(0),
            'address' => $this->string()->notNull(),
            'coordinates' => $this->string(),
            'site' => $this->string(),
            'tel' => $this->string(),
            'alias' => $this->string()->notNull()->unique(),
            'meta_title' => $this->string(),
            'meta_keywords' => $this->string(),
            'meta_description' => $this->string(),
            'created_at' => $this->timestamp()->null(),
            'updated_at' => $this->timestamp()->null(),
        ], $tableOptions);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //echo "m180522_141942_partners cannot be reverted.\n";

        $this->dropTable('{{%partners}}');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180522_141942_partners cannot be reverted.\n";

        return false;
    }
    */
}
