<?php
return new class {
    public static function run()
    {
        $sql = 'INSERT INTO banners (`name`, price, `position`) VALUES
        ("city-background", 300, "50% 60%"),
        ("digital-background", 300, NULL),
        ("battle-ships-background", 300, "50% 64%"),
        ("arcade-background", 300, "50% 69%"),
        ("blue-disc-background", 300, NULL),
        ("red-disc-background", 300, NULL),
        ("explosion-background", 300, "50% 54%"),
        ("cartoon-background", 300, "50% 40%");';
        return $sql;
    }
};
