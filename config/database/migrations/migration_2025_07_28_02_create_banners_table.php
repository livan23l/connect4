<?php
return new class {
    public static function up()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS banners (
            id INT PRIMARY KEY AUTO_INCREMENT,
            `name` VARCHAR(25) NOT NULL UNIQUE,
            price INT NOT NULL DEFAULT 300,
            `position` VARCHAR(15),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );';
        return $sql;
    }

    public static function down()
    {
        $sql = 'DROP TABLE IF EXISTS banners;';
        return $sql;
    }
};
