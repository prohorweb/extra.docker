<?php

use yii\db\Migration;

/**
 * Handles adding trainer_id to table `{{%banners}}`.
 */
class m190412_111443_add_trainer_id_column_to_banners_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%banners}}', 'trainer_id', $this->integer()->after('service_id'));

        $this->createIndex('trainer_id', '{{%banners}}', 'service_id');
        $this->addForeignKey('FK_banners_trainers', '{{%banners}}', 'trainer_id', '{{%trainers}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('FK_banners_trainers', '{{%banners}}');
        $this->dropIndex('trainer_id', '{{%banners}}');

        $this->dropColumn('{{%banners}}', 'trainer_id');
    }
}
