<?php

class m140301_144408_create_users_table extends CDbMigration
{
    public function safeUp()
    {
        $this->createTable('user', array(
            'id'            => 'pk',
            'username'      => 'varchar(16) NOT NULL',
            'email'         => 'varchar(255) NOT NULL',
            'first_name'    => 'varchar(45) NULL DEFAULT NULL',
            'last_name'     => 'varchar(45) NULL DEFAULT NULL',
            'password'      => 'varchar(64) NOT NULL',
            'activate'      => 'varchar(64) NULL DEFAULT NULL',
            'last_login'    => 'datetime NOT NULL',
            'pass_reset'    => 'int(11) NULL DEFAULT NULL',
            'admin'         => 'tinyint(1) NOT NULL DEFAULT 0',
            'verified'      => 'tinyint(1) NOT NULL DEFAULT 0',
            'disabled'      => 'tinyint(1) NOT NULL DEFAULT 0',
        ));

        $this->createIndex('uq_username', 'user', 'username', true);
        $this->createIndex('uq_email', 'user', 'email', true);

        $this->insert('user', array(
            'username'      => 'admin',
            'email'         => 'travis@travisstroud.com',
            'first_name'    => 'Travis',
            'last_name'     => 'Stroud',
            'password'      => '$2a$10$RblB0RCDMa7f4FXA9ptXWeHfIgxx3HNaZhk3Is26lPkdSJQVeKjxa',
            'admin'         => 1,
            'verified'      => 1,
        ));
    }

    public function safeDown()
    {
        $this->dropTable('user');
    }
}
