<?php
return new class {
    public static function run()
    {
        $sql = 'INSERT INTO banners (`name`, price) VALUES
        ("city-background", 300),
        ("digital-background", 300),
        ("battle-ships-background", 300),
        ("blue-disc-background", 300),
        ("red-disc-background", 300),
        ("arcade-background", 300),
        ("exposion-background", 300),
        ("cartoon-background", 300);';
        return $sql;
    }
};
