<?php

use yii\db\Migration;

class m161208_174110_program_classes extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%program_classes}}', [
            'id' => $this->primaryKey(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'position' => $this->integer()->notNull(),
            'title' => $this->string(),
            'duration' => $this->string(),
            'intro' => $this->text()->notNull(),
            'content' => $this->text(),
            'group_programs_id' => $this->integer()->notNull(),
            'color' => $this->string(30),
            'created_at' => $this->timestamp()->null(),
            'updated_at' => $this->timestamp()->null(),
        ], $tableOptions);

        $this->createIndex('group_programs_id', '{{%program_classes}}', 'group_programs_id');
        $this->addForeignKey((!empty($this->getDb()->tablePrefix) ? 'en_' : '') . 'FK_programClasses_groupPrograms', '{{%program_classes}}', 'group_programs_id', '{{%group_programs}}', 'id', 'CASCADE', 'CASCADE');

    }

    public function down()
    {
        //echo "m161208_173110_group_programs cannot be reverted.\n";

        $this->dropForeignKey((!empty($this->getDb()->tablePrefix) ? 'en_' : '') . 'FK_programClasses_groupPrograms', '{{%program_classes}}');
        $this->dropIndex('group_programs_id', '{{%program_classes}}');

        $this->dropTable('{{%program_classes}}');

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
