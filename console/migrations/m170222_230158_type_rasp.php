<?php

use yii\db\Migration;

class m170222_230158_type_rasp extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%type_rasp}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()
        ], $tableOptions);

        $this->insert('{{%type_rasp}}', [
            'id' => 1,
            'title' => 'Групповые программы',
        ]);
    }

    public function down()
    {
        //echo "m170223_230158_type_rasp cannot be reverted.\n";
        $this->dropTable('{{%type_rasp}}');

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
