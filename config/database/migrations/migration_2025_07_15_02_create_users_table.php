<?php
return new class {
    public static function up()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS users (
            username VARCHAR(50) PRIMARY KEY,
            `password` VARCHAR(255) NOT NULL,
            profile_id CHAR(9),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT fk_users_profiles FOREIGN KEY (profile_id) REFERENCES profiles(id),
            CONSTRAINT ck_users_min_username_len CHECK (CHAR_LENGTH(username) >= 5)
        );';
        return $sql;
    }

    public static function down()
    {
        $sql = 'DROP TABLE IF EXISTS users;';
        return $sql;
    }
};