<?php
return new class {
    public static function up()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS profiles (
            id CHAR(9) PRIMARY KEY,
            avatar VARCHAR(15) NOT NULL,
            `name` VARCHAR(50) NOT NULL,
            `description` VARCHAR(255) NULL DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );';
        return $sql;
    }

    public static function down()
    {
        $sql = 'DROP TABLE IF EXISTS profiles;';
        return $sql;
    }
};