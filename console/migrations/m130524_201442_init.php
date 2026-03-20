<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        if(!intval(substr($this->db->dsn, -1))) {
            $this->createTable('{{%user}}', [
                'id' => $this->primaryKey(),
                'access_token' => $this->string()->notNull(),
                'username' => $this->string()->notNull()->unique(),
                'auth_key' => $this->string(32)->notNull(),
                'password_hash' => $this->string()->notNull(),
                'password_reset_token' => $this->string()->unique(),
                'email' => $this->string()->notNull()->unique(),

                'status' => $this->smallInteger()->notNull()->defaultValue(10),
                'created_at' => $this->integer()->notNull(),
                'updated_at' => $this->integer()->notNull(),
            ], $tableOptions);

            $this->insert('{{%user}}', [
                'id' => 1,
                'access_token' => 'bd9615e2871c56dddd8b88b576f131f51c20f3bc',
                'username' => 'admin',
                'auth_key' => 'eaNR1jxmvDKBQ02a2Xai5Utt3w4QoRZV',
                'password_hash' => '$2y$13$mpSm4b9vKWIhfpYwV5.LZ.2dx58gKOf7LggS2UKOVYrXlDpJnvB8y',
                'password_reset_token' => null,
                'email' => 'illyar80@gmail.com',
                'status' => 10,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $this->insert('{{%user}}', [
                'id' => 2,
                'access_token' => 'hpeyyMDtOmXzWAAn9mBtCf2g6Ne7FCYE',
                'username' => 'admin2',
                'auth_key' => 'eaNR1jxmvDKBQ02a2Xai5Utt3w4QoRZV',
                'password_hash' => '$2y$13$mpSm4b9vKWIhfpYwV5.LZ.2dx58gKOf7LggS2UKOVYrXlDpJnvB8y',
                'password_reset_token' => null,
                'email' => 'illyar802@gmail.com',
                'status' => 10,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $this->insert('{{%user}}', [
                'id' => 3,
                'access_token' => 'JKZU0F7SB5pmdEUSW8HuLV2xqTdLqb0r',
                'username' => 'admin3',
                'auth_key' => 'eaNR1jxmvDKBQ02a2Xai5Utt3w4QoRZV',
                'password_hash' => '$2y$13$mpSm4b9vKWIhfpYwV5.LZ.2dx58gKOf7LggS2UKOVYrXlDpJnvB8y',
                'password_reset_token' => null,
                'email' => 'illyar803@gmail.com',
                'status' => 10,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    public function down()
    {
        if(!intval(substr($this->db->dsn, -1))) {
            $this->dropTable('{{%user}}');
        }
    }
}
