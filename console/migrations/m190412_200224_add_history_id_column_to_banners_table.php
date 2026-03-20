<?php

use yii\db\Migration;

/**
 * Handles adding history_id to table `{{%banners}}`.
 */
class m190412_200224_add_history_id_column_to_banners_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%banners}}', 'history_id', $this->integer()->after('trainer_id'));

        $this->createIndex('history_id', '{{%banners}}', 'history_id');
        $this->addForeignKey('FK_banners_history', '{{%banners}}', 'history_id', '{{%history}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('FK_banners_history', '{{%banners}}');
        $this->dropIndex('history_id', '{{%banners}}');

        $this->dropColumn('{{%banners}}', 'history_id');
    }
}
