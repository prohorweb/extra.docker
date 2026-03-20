<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%club_banners}}`.
 */
class m220407_060159_add_video_column_to_club_banners_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%club_banners}}', 'video', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%club_banners}}', 'video');
    }
}
