<?php

require_once __DIR__ . '/../config.php';

class Database
{
    private $connection;
    private $migrationRoute;

    /**
     * Constructor.
     * Initializes the database connection using configuration constants.
     * Additionally, it obtains the root path of the migrations.
     */
    public function __construct()
    {
        $db_mang = DB_MANAGER;
        $db_host = DB_HOST;
        $db_port = DB_PORT;
        $db_name = DB_DATABASE;
        $db_user = DB_USER;
        $db_pass = DB_PASSWORD;

        $dsn = "$db_mang:host=$db_host;dbname=$db_name;port=$db_port";

        try {
            $this->connection = new PDO($dsn, $db_user, $db_pass);
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }

        $this->migrationRoute = BASE . 'config/database/migrations/';
    }

    /**
     * Executes the SQL code of a single migration, either 'up' or 'down'.
     *
     * @param string $file The migration file name.
     * @param string $type The type of migration to execute: 'up' or 'down'.
     */
    private function executeMigration($file, $type)
    {
        $migration = include $this->migrationRoute . $file;
        $sql = $migration::$type();

        try {
            $this->connection->exec($sql);
            $message = match ($type) {
                'up' => 'Create',
                'down' => 'Revert',
            };
            echo "$message migration: $file\n";
        } catch (PDOException $e) {
            echo 'SQL error: ' . $e->getMessage() . "\n";
            die();
        }
    }

    /**
     * Executes the up or down method for one or all migrations based on the
     * given type.
     *
     * @param string $migration The name of the migration to execute. If the
     * method receives an empty string it will run all the migrations.
     * @param string $type The type of migration to execute: 'up' or 'down'.
     */
    private function upOrDown($migration, $type)
    {
        // Check if it's just one migration
        if ($migration !== '') {
            $file = $this->migrationRoute . $migration . '.php';

            // Check that the current migration file exists
            if (file_exists($file)) {
                $this->executeMigration("$migration.php", $type);
            } else {
                echo 'The specified migration does not exist.';
                die();
            }

            return;
        }

        // Get all the migration files
        $files = scandir($this->migrationRoute);
        // Delete the first two position of the files array
        array_shift($files);  // Delete '.'
        array_shift($files);  // Delete '..'

        // Check if there is at least one migration
        if (count($files) == 0) {
            echo 'There are no migration files';
            die();
        }

        // Reverse the files array if the type is 'down' to avoid errors
        if ($type == 'down') {
            $files = array_reverse($files);
        }

        // Execute all the migrations
        foreach ($files as $file) {
            $this->executeMigration($file, $type);
        }
    }

    /**
     * Runs the given migration. If no migration is specified, runs all the
     * migrations.
     *
     * @param string $migration The name of the migration to run (optional).
     */
    public function up($migration = '')
    {
        $this->upOrDown($migration, 'up');
    }

    /**
     * Reverts the given migration. If no migration is specified, reverts all
     * migrations.
     *
     * @param string $migration The name of the migration to revert (optional).
     */
    public function down($migration = '')
    {
        $this->upOrDown($migration, 'down');
    }

    /**
     * Refreshes the given migration by running its down method followed by its
     * up method. If no migration is specified, refreshes all migrations.
     *
     * @param string $migration The name of the migration to refresh (optional).
     */
    public function refresh($migration = '')
    {
        $this->down($migration);
        $this->up($migration);
    }
}

$database = new Database();

// Executes one migration method
$database->refresh();
