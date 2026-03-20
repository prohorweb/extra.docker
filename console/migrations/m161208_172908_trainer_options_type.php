<?php

use yii\db\Migration;

class m161208_172908_trainer_options_type extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%trainer_options_type}}', [
            'id' => $this->primaryKey(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'position' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
        ], $tableOptions);

    }

    public function down()
    {
        //echo "m161208_172908_trainer_options cannot be reverted.\n";

        $this->dropTable('{{%trainer_options_type}}');

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
