<?php

require_once BASE . 'app/models/Model.php';

class Profile extends Model
{
    protected string $table = 'profiles';

    public static function createProfile()
    {
        // Prepare all the attributes to generate the id
        //--Define the function to get the characters
        $characters = [
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
            'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
            'U', 'V', 'W', 'X', 'Y', 'Z',
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j',
            'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't',
            'u', 'v', 'w', 'x', 'y', 'z'
        ];
        $charsAmount = count($characters);
        $getCharacters = function ($value, $letters) use ($characters, $charsAmount) {
            // Subtract one from the value to bring it into range
            $value--;

            // Get the word
            $word = '';
            for ($i = $letters; $i > 0; $i--) {
                // Get the character index
                $base = $charsAmount ** ($i - 1);
                $division = (int) ($value / $base);

                // Add the 'division' character to the word
                $word .= $characters[$division];

                // Update the value
                $value -= $division * $base;
            }

            return $word;
        };

        //--Get the current date
        $datetime = explode(' ', date('Y-m-d H:i:s'));
        $date = array_map(
            function ($el) {
                return (int)$el;
            },
            explode('-', $datetime[0])
        );
        $time = array_map(
            function ($el) {
                return (int)$el;
            },
            explode(':', $datetime[1])
        );

        //--Get the amount of days of the current year
        $DateTime_now = new DateTime("$date[0]-$date[1]-$date[2]");
        $DateTime_start = new DateTime("$date[0]-01-01");
        $days = $DateTime_now->diff($DateTime_start)->days + 1;

        //--Get the amount of seconds of today
        $seconds = ($time[0] * 3600) + ($time[1] * 60) + $time[2];

        //--Refactor the current year based on 2025
        $date[0] -= 2024;


        // Generate the id based on the characters;
        $id = '';

        //--First two letters (based on the refactored year)
        $id .= $getCharacters($date[0], 2);
        //--Two letters for the amount of days
        $id .= $getCharacters($days, 2);
        //--Three letters for the amount of seconds
        $id .= $getCharacters($seconds, 3);
        //--Two random chars
        $id .= $characters[rand(0, $charsAmount - 1)];
        $id .= $characters[rand(0, $charsAmount - 1)];
        //--Revert the id
        $id = strrev($id);

        // Get the rest of the profile data
        $name = "User-$id";

        // Create the profile
        return Profile::create([
            'id' => $id,
            'name' => $name,
        ]);
    }

    public function avatar()
    {
        return $this->hasOne('Avatar');
    }

    public function unlockedAchievements()
    {
        // Get the SQL and values
        $table = 'achievements_profiles';
        $sql = "SELECT * FROM $table WHERE profile_id = :id;";
        $values = [':id' => $this->id];

        $this->preparedQuery($sql, $values);
        $achievements = $this->query->fetchAll(PDO::FETCH_ASSOC);

        return $achievements;
    }
}
