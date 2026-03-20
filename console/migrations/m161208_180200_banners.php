<?php

use yii\db\Migration;

class m161208_180200_banners extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%banners}}', [
            'id' => $this->primaryKey(),
            'group_program_id' => $this->integer(),
            'service_id' => $this->integer(),
            'img1440' => $this->string(),
            'img1200' => $this->string(),
            'img768' => $this->string(),
        ], $tableOptions);

        $this->createIndex('group_program_id', '{{%banners}}', 'group_program_id');
        $this->addForeignKey((!empty($this->getDb()->tablePrefix) ? 'en_' : '') . 'FK_banners_groupPrograms', '{{%banners}}', 'group_program_id', '{{%group_programs}}', 'id', 'CASCADE', 'CASCADE');

        $this->createIndex('service_id', '{{%banners}}', 'service_id');
        $this->addForeignKey((!empty($this->getDb()->tablePrefix) ? 'en_' : '') . 'FK_banners_services', '{{%banners}}', 'service_id', '{{%services}}', 'id', 'CASCADE', 'CASCADE');

    }

    public function down()
    {
        //echo "m161208_180200_banners cannot be reverted.\n";

        $this->dropForeignKey((!empty($this->getDb()->tablePrefix) ? 'en_' : '') . 'FK_banners_groupPrograms', '{{%banners}}');
        $this->dropIndex('group_program_id', '{{%banners}}');

        $this->dropForeignKey((!empty($this->getDb()->tablePrefix) ? 'en_' : '') . 'FK_banners_services', '{{%banners}}');
        $this->dropIndex('service_id', '{{%banners}}');

        $this->dropTable('{{%banners}}');

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
