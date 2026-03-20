<?php

use yii\db\Migration;

/**
 * Handles adding about to table `{{%settings}}`.
 */
class m190417_084239_add_about_column_to_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%settings}}', 'about', $this->text());
        $this->addColumn('{{%settings}}', 'logo1', $this->string());
        $this->addColumn('{{%settings}}', 'logo2', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%settings}}', 'about');
        $this->dropColumn('{{%settings}}', 'logo1');
        $this->dropColumn('{{%settings}}', 'logo2');
    }
}
