<?php
return new class {
    public static function up()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS achievements_profiles (
            achievement_id INT,
            profile_id char(9),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            CONSTRAINT pk_achievements_profiles PRIMARY KEY (achievement_id, profile_id),
            CONSTRAINT fk_achievements_profiles_achievements FOREIGN KEY (achievement_id) REFERENCES achievements(id) ON UPDATE CASCADE ON DELETE CASCADE,
            CONSTRAINT fk_achievements_profiles_profiles FOREIGN KEY (profile_id) REFERENCES profiles(id) ON UPDATE CASCADE ON DELETE CASCADE
        );';
        return $sql;
    }

    public static function down()
    {
        $sql = 'DROP TABLE IF EXISTS achievements_profiles;';
        return $sql;
    }
};