<?php

use yii\db\Migration;

class m170223_192450_rasp extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%rasp}}', [
            'id' => $this->primaryKey(),
            'date' => $this->date()->notNull(),
            'time' => $this->time()->notNull(),
            'is_pay' => $this->smallInteger()->notNull()->defaultValue(0),
            'is_preliminary' => $this->smallInteger()->notNull()->defaultValue(0),
            'is_new' => $this->smallInteger()->notNull()->defaultValue(0),
            'comment' => $this->text(),
            'program_classes_id' => $this->integer()->notNull(),
            'type_rasp_id' => $this->integer(),
            'group_programs_id' => $this->integer()->notNull(),
            'rooms_id' => $this->integer()->notNull(),
            'trainer_id' => $this->integer(),
            'created_at' => $this->timestamp()->null(),
            'updated_at' => $this->timestamp()->null(),
        ], $tableOptions);

        $this->createIndex('program_classes_id', '{{%rasp}}', 'program_classes_id');
        $this->addForeignKey((!empty($this->getDb()->tablePrefix) ? 'en_' : '') . 'FK_rasp_programClasses', '{{%rasp}}', 'program_classes_id', '{{%program_classes}}', 'id', 'CASCADE', 'CASCADE');

        $this->createIndex('type_rasp_id', '{{%rasp}}', 'type_rasp_id');
        $this->addForeignKey((!empty($this->getDb()->tablePrefix) ? 'en_' : '') . 'FK_rasp_typeRasp', '{{%rasp}}', 'type_rasp_id', '{{%type_rasp}}', 'id', 'CASCADE', 'CASCADE');

        $this->createIndex('group_programs_id', '{{%rasp}}', 'group_programs_id');
        $this->addForeignKey((!empty($this->getDb()->tablePrefix) ? 'en_' : '') . 'FK_rasp_groupPrograms', '{{%rasp}}', 'group_programs_id', '{{%group_programs}}', 'id', 'CASCADE', 'CASCADE');

        $this->createIndex('rooms_id', '{{%rasp}}', 'rooms_id');
        $this->addForeignKey((!empty($this->getDb()->tablePrefix) ? 'en_' : '') . 'FK_rasp_rooms', '{{%rasp}}', 'rooms_id', '{{%rooms}}', 'id', 'CASCADE', 'CASCADE');

        $this->createIndex('trainer_id', '{{%rasp}}', 'trainer_id');
        $this->addForeignKey((!empty($this->getDb()->tablePrefix) ? 'en_' : '') . 'FK_rasp_trainers', '{{%rasp}}', 'trainer_id', '{{%trainers}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        //echo "m170223_192450_rasp cannot be reverted.\n";

        $this->dropForeignKey((!empty($this->getDb()->tablePrefix) ? 'en_' : '') . 'FK_rasp_trainers', '{{%rasp}}');
        $this->dropIndex('trainer_id', '{{%rasp}}');

        $this->dropForeignKey((!empty($this->getDb()->tablePrefix) ? 'en_' : '') . 'FK_rasp_rooms', '{{%rasp}}');
        $this->dropIndex('rooms_id', '{{%rasp}}');

        $this->dropForeignKey((!empty($this->getDb()->tablePrefix) ? 'en_' : '') . 'FK_rasp_groupPrograms', '{{%rasp}}');
        $this->dropIndex('group_programs_id', '{{%rasp}}');

        $this->dropForeignKey((!empty($this->getDb()->tablePrefix) ? 'en_' : '') . 'FK_rasp_typeRasp', '{{%rasp}}');
        $this->dropIndex('type_rasp_id', '{{%rasp}}');

        $this->dropForeignKey((!empty($this->getDb()->tablePrefix) ? 'en_' : '') . 'FK_rasp_programClasses', '{{%rasp}}');
        $this->dropIndex('program_classes_id', '{{%rasp}}');

        $this->dropTable('{{%rasp}}');

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
