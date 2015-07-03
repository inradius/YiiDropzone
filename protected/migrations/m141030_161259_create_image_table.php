<?php

class m141030_161259_create_image_table extends CDbMigration
{
    public function safeUp()
    {
        $this->createTable('image', array(
            'id'                    => 'pk',
            'user_id'               => 'int(3) NOT NULL',
            'long_id'               => 'varchar(31) DEFAULT NULL COMMENT "Provides a download URL"',
            'image_resolution'      => 'varchar(12) DEFAULT NULL',
            'image_created'         => 'datetime DEFAULT NULL',
            'image_updated'         => 'datetime DEFAULT NULL',
            'image_file_type'       => 'varchar(63) DEFAULT NULL',
            'image_file_size'       => 'int(11) DEFAULT NULL',
            'image_file_name'       => 'varchar(255) DEFAULT NULL',
            'image_file_content'    => 'longblob',
        ), 'ENGINE InnoDB');
    }

    public function down()
    {
        $this->dropTable('image');
    }
}