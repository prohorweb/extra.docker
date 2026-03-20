<?php

use yii\db\Migration;

/**
 * Handles adding bonus to table `settings`.
 */
class m210130_174025_add_bonus_column_to_settings_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%settings}}', 'bonus_percent', $this->string());
        $this->addColumn('{{%settings}}', 'bonus_time', $this->string());
        $this->addColumn('{{%settings}}', 'email_bonus', $this->string());
        $this->addColumn('{{%settings}}', 'bonus', $this->boolean()->defaultValue(0));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%settings}}', 'bonus_percent');
        $this->dropColumn('{{%settings}}', 'bonus_time');
        $this->dropColumn('{{%settings}}', 'email_bonus');
        $this->dropColumn('{{%settings}}', 'bonus');
    }
}
