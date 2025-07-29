<?php
return new class {
    public static function run()
    {
        $sql = 'INSERT INTO avatars (`name`, price) VALUES
        ("anonymous", 0),
        ("blue-disc", 0),
        ("red-disc", 0),
        ("futuristic-glasses-man", 200),
        ("futuristic-glasses-woman", 200),
        ("pixelart-man", 200),
        ("pixelart-woman", 200),
        ("improved-blue-robot", 200),
        ("improved-red-robot", 200),
        ("pirate-man", 200),
        ("pirate-woman", 200),
        ("glitch-man", 200),
        ("glitch-woman", 200),
        ("blue-hacker", 200),
        ("red-hacker", 200),
        ("cartoon-blue-disc", 200),
        ("cartoon-red-disc", 200);';
        return $sql;
    }
};
