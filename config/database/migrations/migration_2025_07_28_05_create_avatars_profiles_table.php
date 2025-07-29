<?php
return new class {
    public static function up()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS avatars_profiles (
            avatar_id INT NOT NULL,
            profile_id CHAR(9) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT pk_avatars_profiles PRIMARY KEY (avatar_id, profile_id),
            CONSTRAINT fk_avatars_profiles_avatars FOREIGN KEY (avatar_id) REFERENCES avatars(id) ON UPDATE CASCADE ON DELETE CASCADE,
            CONSTRAINT fk_avatars_profiles_profiles FOREIGN KEY (profile_id) REFERENCES profiles(id) ON UPDATE CASCADE ON DELETE CASCADE
        );';
        return $sql;
    }

    public static function down()
    {
        $sql = 'DROP TABLE IF EXISTS avatars_profiles;';
        return $sql;
    }
};
