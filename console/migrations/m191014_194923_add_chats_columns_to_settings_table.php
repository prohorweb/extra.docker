<?php

use yii\db\Migration;

/**
 * Handles adding chats to table `{{%settings}}`.
 */
class m191014_194923_add_chats_columns_to_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%settings}}', 'wa', $this->string());
        $this->addColumn('{{%settings}}', 'vk', $this->string());
        $this->addColumn('{{%settings}}', 'tg', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%settings}}', 'wa');
        $this->dropColumn('{{%settings}}', 'vk');
        $this->dropColumn('{{%settings}}', 'tg');
    }
}
