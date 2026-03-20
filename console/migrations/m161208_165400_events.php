<?php

use yii\db\Migration;

class m161208_165400_events extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%events}}', [
            'id' => $this->primaryKey(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'title' => $this->string()->notNull(),
            'date' => $this->date()->notNull(),
            'is_pay' => $this->smallInteger()->notNull()->defaultValue(0),
            'is_open' => $this->smallInteger()->notNull()->defaultValue(0),
            'intro' => $this->text()->notNull(),
            'img' => $this->string()->notNull(),
            'content' => $this->text()->notNull(),
            'alias' => $this->string()->unique(),
            'meta_title' => $this->string(),
            'meta_keywords' => $this->string(),
            'meta_description' => $this->string(),
            'created_at' => $this->timestamp()->null(),
            'updated_at' => $this->timestamp()->null(),
        ], $tableOptions);

    }

    public function down()
    {
        //echo "m161208_165400_events cannot be reverted.\n";
        $this->dropTable('{{%events}}');

        return true;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
