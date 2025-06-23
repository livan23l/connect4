<?php
return new class {
    public static function up()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS users (
            user VARCHAR(50) PRIMARY KEY,
            `password` VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );';
        return $sql;
    }

    public static function down()
    {
        $sql = 'DROP TABLE IF EXISTS users;';
        return $sql;
    }
};