<?php
return new class {
    public static function up()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS friendships (
            profile_id_1 char(9),
            profile_id_2 char(9),
            `status` ENUM("pending", "accepted") NOT NULL DEFAULT "pending",
            CONSTRAINT pk_friendships PRIMARY KEY (profile_id_1, profile_id_2),
            CONSTRAINT fk_friendships_profiles_1 FOREIGN KEY (profile_id_1) REFERENCES profiles(id) ON UPDATE CASCADE ON DELETE CASCADE,
            CONSTRAINT fk_friendships_profiles_2 FOREIGN KEY (profile_id_2) REFERENCES profiles(id) ON UPDATE CASCADE ON DELETE CASCADE
        );';
        return $sql;
    }

    public static function down()
    {
        $sql = 'DROP TABLE IF EXISTS friendships;';
        return $sql;
    }
};