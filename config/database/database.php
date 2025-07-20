<?php

require_once __DIR__ . '/../config.php';

class Database
{
    /**
     * The PDO database connection
     */
    private static PDO $connection;

    /**
     * Initializes the database connection using configuration constants.
     * If the connection fails, print an error message and finish the execution.
     * 
     * @return void
     */
    protected static function init()
    {
        $db_mang = DB_MANAGER;
        $db_host = DB_HOST;
        $db_port = DB_PORT;
        $db_name = DB_DATABASE;
        $db_user = DB_USER;
        $db_pass = DB_PASSWORD;

        $dsn = "$db_mang:host=$db_host;dbname=$db_name;port=$db_port";

        try {
            self::$connection = new PDO($dsn, $db_user, $db_pass);
        } catch (PDOException $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    /**
     * Executes SQL code from a given file and method.
     *
     * @param string $filePath Path to the file containing SQL.
     * @param string $method Name of the static method to call on the file class.
     * @return void
     */
    private static function runSQL($filePath, $method)
    {
        // Check if the file exists
        if (!file_exists($filePath)) {
            die("Error: The file '$filePath' does not exists\n");
        }

        // Get the SQL code
        $file = include $filePath;
        $sql = $file::$method();

        // Try to execute the SQL
        try {
            self::$connection->exec($sql);
        } catch (PDOException $e) {
            die("SQL error: " . $e->getMessage() . "\n");
        }
    }

    /**
     * Returns an array of files in the given base directory.
     * If a specific file is provided, returns an array with only that file.
     * Exits with an error if no files are found.
     *
     * @param string $base Base directory.
     * @param string $file Specific file name (optional).
     * @return array List of file paths.
     */
    private static function getFiles($base, $file = '')
    {
        // Check if it's just one file
        if ($file !== '') return ["$file.php"];

        // Get all the files in the base
        $files = scandir($base);
        //--Delete the first two position of the files array
        array_shift($files);  // Delete '.'
        array_shift($files);  // Delete '..'

        // Check if there are no files
        if (count($files) == 0) {
            die("There are no files in '$base'.\n");
        }

        return $files;
    }

    /**
     * Executes all SQL files in the given base path in order.
     * If a specific file is provided, only that file is executed.
     * Prints a message for each executed file.
     *
     * @param string $base    Base directory containing the files.
     * @param string $method  Name of the static method in each file to execute.
     * @param string $message Message to print for each executed file.
     * @param string $file    Specific file name to execute (optional).
     * @return void
     */
    protected static function executeForwards($base, $method, $message, $file)
    {
        // Start the database connection
        self::init();

        // Get the files
        $files = self::getFiles($base, $file);

        // Execute all the files
        foreach ($files as $file) {
            self::runSQL("$base$file", $method);
            echo "$message: $file\n";
        }
    }

    /**
     * Executes all SQL files in the given base path in reverse order.
     * If a specific file is provided, only that file is executed.
     * Prints a message for each executed file.
     * Useful for rollbacks.
     *
     * @param string $base    Base directory containing the files.
     * @param string $method  Name of the static method in each file to execute.
     * @param string $message Message to print for each executed file.
     * @param string $file    Specific file name to execute (optional).
     * @return void
     */
    protected static function executeBackwards($base, $method, $message, $file)
    {
        // Start the database connection
        self::init();

        // Get the files
        $files = array_reverse(self::getFiles($base, $file));

        // Execute all the files
        foreach ($files as $file) {
            self::runSQL("$base$file", $method);
            echo "$message: $file\n";
        }
    }
}

class Migration extends Database
{
    /**
     * Base path where migration files are stored.
     */
    private static string $migrationRoute = BASE . 'config/database/migrations/';

    /**
     * Runs the given migration. If no migration is specified, runs all migrations.
     * The migration name should match the file name (without extension).
     *
     * @param string $migration Name of the migration to run (optional).
     * @return void
     */
    public static function up($migration = '')
    {
        echo "--------------------------------------------------------------\n";
        self::executeForwards(
            self::$migrationRoute,
            'up',
            'Create migration',
            $migration
        );
    }

    /**
     * Reverts the given migration. If no migration is specified, reverts all migrations.
     * The migration name should match the file name (without extension).
     *
     * @param string $migration Name of the migration to revert (optional).
     * @return void
     */
    public static function down($migration = '')
    {
        echo "--------------------------------------------------------------\n";
        self::executeBackwards(
            self::$migrationRoute,
            'down',
            'Revert migration',
            $migration
        );
    }

    /**
     * Refreshes the given migration by reverting and re-running it.
     * If no migration is specified, refreshes all migrations.
     * This will undo and reapply all changes.
     *
     * @param string $migration Name of the migration to refresh (optional).
     * @return void
     */
    public static function refresh($migration = '')
    {
        self::down($migration);
        self::up($migration);
    }
}

class Seeder extends Database
{
    /**
     * Base path where seeder files are stored.
     */
    private static $seedersRoute = BASE . 'config/database/seeders/';

    /**
     * Runs the given seeder. If no seeder is specified, runs all seeders.
     * The seeder name should match the file name (without extension).
     *
     * @param string $seeder Name of the seeder to run (optional).
     * @return void
     */
    public static function run($seeder = '')
    {
        echo "--------------------------------------------------------------\n";
        self::executeForwards(
            self::$seedersRoute,
            'run',
            'Run seeder',
            $seeder
        );
    }
}

Migration::refresh();
Seeder::run();