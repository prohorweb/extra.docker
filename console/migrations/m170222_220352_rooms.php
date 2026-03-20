<?php

use yii\db\Migration;

class m170222_220352_rooms extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%rooms}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()
        ], $tableOptions);
    }

    public function down()
    {
        //echo "m170223_220352_rooms cannot be reverted.\n";
        $this->dropTable('{{%rooms}}');

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
