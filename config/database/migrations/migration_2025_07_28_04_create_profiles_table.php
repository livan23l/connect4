<?php
return new class {
    public static function up()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS profiles (
            id CHAR(9) PRIMARY KEY,
            `name` VARCHAR(15) NOT NULL,
            `description` VARCHAR(255),
            avatar_id INT NOT NULL DEFAULT 1,
            banner_id INT,
            points INT DEFAULT(200),
            games_won INT DEFAULT(0),
            games_drawn INT DEFAULT(0),
            games_lost INT DEFAULT(0),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT fk_profiles_avatars FOREIGN KEY (avatar_id) REFERENCES avatars(id),
            CONSTRAINT fk_profiles_banners FOREIGN KEY (banner_id) REFERENCES banners(id)
        );';
        return $sql;
    }

    public static function down()
    {
        $sql = 'DROP TABLE IF EXISTS profiles;';
        return $sql;
    }
};
