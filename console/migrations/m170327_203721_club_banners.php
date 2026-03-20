<?php

use yii\db\Migration;

class m170327_203721_club_banners extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%club_banners}}', [
            'id' => $this->primaryKey(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'position' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'comment' => $this->string(),
            'url' => $this->string()->notNull(),
            'url_category' => $this->string(),
            'url_id' => $this->integer(),
            'open_new_tab' => $this->smallInteger()->notNull()->defaultValue(0),
            'title2' => $this->string()->notNull(),
            'intro' => $this->string()->notNull(),
            'img1440' => $this->string()->notNull(),
            'img1200' => $this->string()->notNull(),
            'img768' => $this->string()->notNull(),
        ], $tableOptions);

    }

    public function down()
    {
        //echo "m170327_203721_club_banners cannot be reverted.\n";
        $this->dropTable('{{%club_banners}}');

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
