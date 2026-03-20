<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%news2}}`.
 */
class m220518_111802_create_news2_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if (Yii::$app->getDb()->username == 'pmauser' || Yii::$app->getDb()->username == 'balvyandex') {
            $this->createTable('{{%news2}}', [
                'id' => $this->primaryKey(),
                'status' => $this->smallInteger()->notNull()->defaultValue(10),
                'position' => $this->integer()->notNull(),
                'title' => $this->string()->notNull(),
                'date' => $this->date()->notNull(),
                'intro' => $this->string()->notNull(),
                'img' => $this->string(),
                'content' => $this->text()->notNull(),
                'alias' => $this->string()->notNull()->unique(),
                'meta_title' => $this->string(),
                'meta_keywords' => $this->string(),
                'meta_description' => $this->string(),
                'created_at' => $this->timestamp()->null(),
                'updated_at' => $this->timestamp()->null(),
            ]);

            $this->insert('{{%seo}}', [
                'type' => 'news2',
                'title' => 'Новости домена',
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if (Yii::$app->getDb()->username == 'pmauser' || Yii::$app->getDb()->username == 'balvyandex') {
            $this->dropTable('{{%news2}}');
        }
    }
}
