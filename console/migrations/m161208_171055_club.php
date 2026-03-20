<?php

use yii\db\Migration;

class m161208_171055_club extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%club}}', [
            'id' => $this->primaryKey(),
            'meta_title' => $this->string(),
            'meta_keywords' => $this->string(),
            'meta_description' => $this->string(),
            'tel' => $this->string()->notNull(),
            'address' => $this->string()->notNull(),
            'url_appstore' => $this->string(),
            'url_googleplay' => $this->string(),
            'url_windowsmarket' => $this->string(),
            'url_vk' => $this->string(),
            'url_facebook' => $this->string(),
            'url_instagram' => $this->string(),
            'url_ok' => $this->string(),
            'pdf' => $this->string(),
            'email' => $this->string()->notNull(),
            'start_work' => $this->string(),
            'start_work_weekend' => $this->string(),
            'url_3d_tour' => $this->string(),
            'start_year' => $this->date(),
            'square' => $this->integer()->notNull(),
            'img' => $this->string()->notNull(),
            'img2' => $this->string(),
            'title' => $this->string()->notNull(),
            'content' => $this->text()->notNull(),
            'content_cards' => $this->string(),
            'coordinates' => $this->string(),
            //'latitude' => $this->decimal(6,4),
            //'longitude' => $this->decimal(7,4)
        ], $tableOptions);

        $this->insert('{{%club}}', [
            'id' => 1,
            'tel' => '',
            'address' => '',
            'email' => '',
            'square' => 0,
            'img' => '',
            'title' => '',
            'content' => ''
        ]);

    }

    public function down()
    {
        //echo "m161208_171055_club cannot be reverted.\n";
        $this->dropTable('{{%club}}');

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
