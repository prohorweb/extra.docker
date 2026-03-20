<?php

use yii\db\Migration;

/**
 * Handles adding timer to table `{{%settings}}`.
 */
class m191030_152122_add_timer_column_to_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%settings}}', 'timer_title', $this->string());
        $this->addColumn('{{%settings}}', 'timer_intro', $this->string());
        $this->addColumn('{{%settings}}', 'timer_start', $this->time());
        $this->addColumn('{{%settings}}', 'timer_end', $this->time());
        $this->addColumn('{{%settings}}', 'timer', $this->boolean());
        $this->addColumn('{{%settings}}', 'email_timer', $this->string()->after('code_club_card'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%settings}}', 'timer');
        $this->dropColumn('{{%settings}}', 'timer_end');
        $this->dropColumn('{{%settings}}', 'timer_start');
        $this->dropColumn('{{%settings}}', 'timer_intro');
        $this->dropColumn('{{%settings}}', 'timer_title');
        $this->dropColumn('{{%settings}}', 'email_timer');
    }
}
