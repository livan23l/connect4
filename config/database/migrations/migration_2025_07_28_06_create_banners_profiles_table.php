<?php
return new class {
    public static function up()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS banners_profiles (
            banner_id INT NOT NULL,
            profile_id CHAR(9) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT pk_banners_profiles PRIMARY KEY (banner_id, profile_id),
            CONSTRAINT fk_banners_profiles_banners FOREIGN KEY (banner_id) REFERENCES banners(id) ON UPDATE CASCADE ON DELETE CASCADE,
            CONSTRAINT fk_banners_profiles_profiles FOREIGN KEY (profile_id) REFERENCES profiles(id) ON UPDATE CASCADE ON DELETE CASCADE
        );';
        return $sql;
    }

    public static function down()
    {
        $sql = 'DROP TABLE IF EXISTS banners_profiles;';
        return $sql;
    }
};
