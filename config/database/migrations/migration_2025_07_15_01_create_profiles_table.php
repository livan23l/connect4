<?php
return new class {
    public static function up()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS profiles (
            id CHAR(9) PRIMARY KEY,
            `name` VARCHAR(15) NOT NULL,
            `description` VARCHAR(255) NULL DEFAULT NULL,
            avatar VARCHAR(20) NOT NULL,
            banner VARCHAR(25) NULL DEFAULT NULL,
            points INT DEFAULT(200),
            games_won INT DEFAULT(0),
            games_drawn INT DEFAULT(0),
            games_lost INT DEFAULT(0),
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