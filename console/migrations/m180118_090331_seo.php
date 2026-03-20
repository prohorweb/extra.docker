<?php

use yii\db\Migration;

/**
 * Class m180118_090331_seo
 */
class m180118_090331_seo extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%seo}}', [
            'id' => $this->primaryKey(),
            'type' => $this->string()->notNull(),
            'title' => $this->string(),
            'keywords' => $this->string(),
            'description' => $this->string(),
            'created_at' => $this->timestamp()->null(),
            'updated_at' => $this->timestamp()->null(),
        ], $tableOptions);

        $this->insert('{{%seo}}', [
            'id' => 1,
            'type' => 'news',
            'title' => 'Новости',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $this->insert('{{%seo}}', [
            'id' => 2,
            'type' => 'share',
            'title' => 'Акции',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $this->insert('{{%seo}}', [
            'id' => 3,
            'type' => 'article',
            'title' => 'Советы тренеров',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $this->insert('{{%seo}}', [
            'id' => 4,
            'type' => 'services',
            'title' => 'Услуги',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $this->insert('{{%seo}}', [
            'id' => 5,
            'type' => 'rasp',
            'title' => 'Расписание',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $this->insert('{{%seo}}', [
            'id' => 6,
            'type' => 'trainer',
            'title' => 'Наша команда',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $this->insert('{{%seo}}', [
            'id' => 7,
            'type' => 'event',
            'title' => 'Мероприятия',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $this->insert('{{%seo}}', [
            'id' => 8,
            'type' => 'jobs',
            'title' => 'Вакансии',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $this->insert('{{%seo}}', [
            'id' => 9,
            'type' => 'history',
            'title' => 'Истории успеха',
            'created_at' => date('Y-m-d H:i:s')
        ]);

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        //echo "m180118_090331_seo cannot be reverted.\n";
        $this->dropTable('{{%seo}}');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180118_090331_seo cannot be reverted.\n";

        return false;
    }
    */
}
