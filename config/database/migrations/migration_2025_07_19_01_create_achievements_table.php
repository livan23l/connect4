<?php
return new class {
    public static function up()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS achievements (
            id INT PRIMARY KEY AUTO_INCREMENT,
            `image` VARCHAR(50) NOT NULL,
            `description` VARCHAR(255) NULL DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );';
        return $sql;
    }

    public static function down()
    {
        $sql = 'DROP TABLE IF EXISTS achievements;';
        return $sql;
    }
};