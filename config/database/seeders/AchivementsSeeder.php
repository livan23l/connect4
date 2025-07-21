<?php
return new class {
    public static function run()
    {
        $sql = 'INSERT INTO achievements (`image`, `description`) VALUES
        ("robot-easy", "Defeat the robot on easy difficulty"),
        ("robot-normal", "Defeat the robot on normal difficulty"),
        ("robot-hard", "Defeat the robot on hard difficulty"),
        ("store-1", "Buy 1 item in the store"),
        ("store-2", "Buy 10 items in the store"),
        ("store-3", "Buy all the items in the store"),
        ("victory-1", "Beat 1 player in quick matches"),
        ("victory-2", "Beat 5 players in quick matches"),
        ("victory-3", "Beat 10 players in quick matches"),
        ("special-friend", "Play a game against a friend"),
        ("special-connect5", "Win a game by connecting five discs"),
        ("special-unlock", "Unlock all achievements on the page");';
        return $sql;
    }
};