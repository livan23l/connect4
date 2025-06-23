<?php

/**
 * Base Model class for the application.
 * 
 * This class provides common functionality for all models, such as database
 * interaction and data manipulation.
 * 
 * Extend this class to create specific models.
 * 
 * @property string $table      Name of the table
 * @property string $pk_column  Name of the primary key column
 * @property mixed  $connection Database connection instance
 * @property mixed  $query      Query builder or raw query resource
 */
class Model
{
    protected string $table;
    protected string $pk_column = 'id';
    protected $connection;
    protected $query;

    /**
     * Constructor.
     * Initializes the database connection using configuration constants.
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
        } catch (PDOException) {
            http_response_code(500);
            die();
        }
    }

    /**
     * Executes a raw SQL query and stores the result.
     * @param string $sql The SQL query to execute.
     * @return $this
     */
    protected function query($sql)
    {
        $this->query = $this->connection->query($sql);
        return $this;
    }

    /**
     * Executes a prepared SQL query with parameters.
     * @param string $sql The SQL query to prepare.
     * @param array $params The parameter names.
     * @param array $values The parameter values.
     */
    protected function preparedQuery($sql, $params, $values)
    {
        $this->query = $this->connection->prepare($sql);
        for ($i = 0; $i < count($params); $i++) {
            $this->query->bindParam(":$params[$i]", $values[$i]);
        }
        $this->query->execute();
    }

    /**
     * Fetches the first result from the last executed query.
     * @return array|false
     */
    public function first()
    {
        return $this->query->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Fetches all results from the last executed query.
     * @return array
     */
    public function get()
    {
        return $this->query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves all records from the table.
     * @return array
     */
    public function all()
    {
        return $this->query("SELECT * FROM $this->table;")->get();
    }

    /**
     * Finds a record by its primary key.
     * @param mixed $id The primary key value.
     * @return array|false
     */
    public function find($id)
    {
        return $this
            ->query("SELECT * FROM $this->table WHERE $this->pk_column = '$id';")
            ->first();
    }

    /**
     * Retrieves the last record ordered by a specific column.
     * @param string $column The column to order by (optional).
     * @return array|false
     */
    public function last($column = 'created_at')
    {
        $this->query("SELECT * FROM $this->table ORDER BY $column DESC LIMIT 1;");
        return $this->first();
    }

    /**
     * Retrieves records matching a specific condition.
     * @param string $column The column name.
     * @param mixed $value The value to compare.
     * @param string $operator The comparison operator ('=' by default).
     * @return $this
     */
    public function where($column, $value, $operator = '=')
    {
        return $this->query("SELECT * FROM $this->table WHERE $column $operator '$value';");
    }

    /**
     * Retrieves records matching multiple conditions.
     * @param array $conditions Array of condition arrays as described above.
     * @return $this
     * 
     * Accepts an array of conditions, each condition being an array with the
     * following structure:
     * 
     * [
     * 
     *   column (string): The column name to compare,
     * 
     *   value (mixed): The value to compare against,
     * 
     *   operator (string, optional): The comparison operator (default '=')
     * 
     *   conditional_operator (string, optional): Logical operator to combine
     * with the next condition (default 'AND')
     * 
     * ]
     *
     * Conditions are combined in the order given using the specified conditional operators.
     *
     * Examples:
     * 1) [['premium', 1], ['age', 18, '>']]
     *    Generates: WHERE premium = 1 AND age > 18
     *
     * 2) [['premium', 1, '=', 'OR'], ['age', 18, '>']]
     *    Generates: WHERE premium = 1 OR age > 18
     */
    public function whereConditions($conditions)
    {
        $where = "";
        $last = count($conditions) - 1;
        for ($i = 0; $i < $last; $i++) {
            $cond = $conditions[$i];
            $action = $cond[2] ?? "=";
            $op = $cond[3] ?? "AND";
            $where .= "$cond[0] $action '$cond[1]' $op ";
        }
        $cond = $conditions[$last];
        $action = $cond[2] ?? "=";
        $where .= "$cond[0] $action '$cond[1]'";

        return $this->query("SELECT * FROM $this->table WHERE $where;");
    }

    /**
     * Inserts a new record into the table.
     * @param array $data The data to insert.
     * @return array|false
     */
    public function create($data)
    {
        $columns = array_keys($data);
        $columns_names = implode(", ", $columns);
        $columns_params = ":" . implode(", :", $columns);
        $values = array_values($data);

        $sql = "INSERT INTO $this->table ($columns_names) VALUES ($columns_params);";

        $this->preparedQuery($sql, $columns, $values);

        return $this->last();
    }

    /**
     * Updates a record by its primary key.
     * @param mixed $id The primary key value.
     * @param array $data The data to update.
     * @return array|false
     */
    public function update($id, $data)
    {
        $columns = array_keys($data);
        $values = array_values($data);

        $sql = "UPDATE $this->table SET ";

        $last = count($columns) - 1;
        for ($i = 0; $i < $last; $i++) {
            $sql .= "$columns[$i] = :$columns[$i], ";
        }
        $sql .= "$columns[$last] = :$columns[$last] WHERE $this->pk_column = $id";

        $this->preparedQuery($sql, $columns, $values);

        return $this->find($id);
    }

    /**
     * Deletes a record by its primary key.
     * @param mixed $id The primary key value.
     * @return array|false
     */
    public function delete($id)
    {
        $object = $this->find($id);
        $this->query("DELETE FROM $this->table WHERE $this->pk_column = $id;");
        return $object;
    }
}
