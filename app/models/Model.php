<?php

/**
 * Base Model class for the application.
 * 
 * This class provides common functionality for all models, such as database
 * interaction and data manipulation.
 * 
 * Extend this class to create specific models.
 * 
 * @property string        $table       Name of the table
 * @property string        $pk_column   Name of the primary key column
 * @property string        $orderBy     Name of the column to order the table
 * @property array         $fillable    Fillable fields within the table
 * @property array         $guarded     Guarded fields within the table
 * @property array         $columns     Array with the table fields
 * @property PDO           $connection  Database connection instance
 * @property PDOStatement  $query       Query builder or raw query resource
 */
#[\AllowDynamicProperties]
class Model
{
    protected string $table;
    protected string $pk_column = 'id';
    protected string $orderBy = 'created_at';
    protected array $fillable = [];
    protected array $guarded = [];
    protected array $columns = [];
    protected PDO $connection;
    protected PDOStatement $query;

    /**
     * Constructor.
     * Initializes the database connection using configuration constants.
     * 
     * Assign columns for massive assignment based on $this->$fillable or $this->$guarded.
     */
    public function __construct()
    {
        // Get the connection
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
            die;
        }

        // Define the columns in the current model
        $columns = $this->connection->query("SHOW COLUMNS FROM $this->table");
        $columns = $columns->fetchAll(PDO::FETCH_ASSOC);

        // Add the columns and set its values to null
        foreach ($columns as $column) {
            $field = $column['Field'];
            $this->columns[] = $field;
            $this->$field = null;
        }
    }

    /**
     * Executes a raw SQL query and stores the result.
     * 
     * @param string $sql The SQL query to execute.
     * @return self
     */
    protected function query($sql)
    {
        $this->query = $this->connection->query($sql);
        return $this;
    }

    /**
     * Executes a prepared SQL statement with named parameters.
     * 
     * @param string $sql The SQL query with named placeholders (e.g., :name).
     * @param array $values An associative array of parameters (e.g., [':name' => 'value']).
     * @return void
     */
    protected function preparedQuery($sql, $values)
    {
        $this->query = $this->connection->prepare($sql);
        foreach ($values as $name => $value) {
            $this->query->bindValue($name, $value);
        }
        $this->query->execute();
    }

    /**
     * Check if a field is fillable or guarded
     * 
     * @param string $field The name of the field to be checked
     * @return bool
     */
    private function isFillable($field)
    {
        $isFillable = false;

        if (count($this->fillable)) {
            if (in_array($field, $this->fillable)) $isFillable = true;
        } else {
            if (!in_array($field, $this->guarded)) $isFillable = true;
        }

        return $isFillable;
    }

    /**
     * Sets values in the properties of the current model.
     * 
     * @param array $values The array of values to be set in the properties.
     * @return void
     */
    private function setValues($values)
    {
        foreach ($this->columns as $column) {
            $this->$column = $values[$column];
        }
    }

    /**
     * Fetches the first result from the last executed query.
     * 
     * @return self|false
     */
    public function first()
    {
        $element = $this->query->fetch(PDO::FETCH_ASSOC);
        if (!$element) return false;

        $this->setValues($element);
        return $this;
    }

    /**
     * Fetches all results from the last executed query.
     * 
     * @return array
     */
    public function get()
    {
        $elements = $this->query->fetchAll(PDO::FETCH_ASSOC);
        $data = [];

        foreach ($elements as $element) {
            $instance = new static;
            $instance->setValues($element);
            $data[] = $instance;
        }

        return $data;
    }

    /**
     * Serializes the current model to an array according to the fillable and
     * guarded properties.
     * 
     * @return array
     */
    public function toArray()
    {
        $data = [];

        foreach ($this->columns as $column) {
            if (!$this->isFillable($column)) continue;
            $data[$column] = $this->$column;
        }

        return $data;
    }

    /**
     * Retrieves all records from the table.
     * 
     * @static
     * @return array
     */
    public static function all()
    {
        $instance = new static;
        return $instance->query("SELECT * FROM $instance->table;")->get();
    }

    /**
     * Finds a record by its primary key.
     * 
     * @static
     * @param mixed $id The primary key value.
     * @return static|false
     */
    public static function find($id)
    {
        $instance = new static;

        $instance->preparedQuery(
            "SELECT * FROM $instance->table
            WHERE $instance->pk_column = :id;",
            [':id' => $id]
        );

        return $instance->first();
    }

    /**
     * Retrieves the last record.
     * 
     * @static
     * @return static|false
     */
    public static function last()
    {
        $instance = new static;

        $instance->query(
            "SELECT * FROM $instance->table
            ORDER BY $instance->orderBy DESC LIMIT 1;"
        );

        return $instance->first();
    }

    /**
     * Retrieves records matching a specific condition.
     * 
     * @static
     * @param string $column The column name.
     * @param mixed $value The value to compare.
     * @param string $operator The comparison operator ('=' by default).
     * @return static|false
     */
    public static function where($column, $value, $operator = '=')
    {
        $instance = new static;

        $instance->preparedQuery(
            "SELECT * FROM $instance->table WHERE $column $operator :val;",
            [':val' => $value]
        );

        return $instance;
    }

    /**
     * Retrieves records matching multiple conditions.
     *
     * @static
     * @param array $conditions Array of condition arrays as described above.
     * @return static|false
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
     *   conditional (string, optional): Logical operator to combine with the
     * next condition (default 'AND')
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
    public static function whereConditions($conditions)
    {
        $where = '';
        $values = [];
        $size = count($conditions);
        for ($i = 0; $i < $size; $i++) {
            $cond = $conditions[$i];  // The current condition

            // Get the condition elements
            $column = $cond[0];
            $value = $cond[1];
            $operator = $cond[2] ?? '=';
            $conditional = $cond[3] ?? 'AND';

            if ($i == $size - 1) $conditional = '';

            // Add the condition sentence and prepare the values
            $where .= "$column $operator :$column $conditional ";
            $values[":$column"] = "$value";
        }

        $instance = new static;

        $instance->preparedQuery(
            "SELECT * FROM $instance->table WHERE $where;",
            $values
        );

        return $instance;
    }

    /**
     * Generates the SQL code to create a new record in a table using the
     * table name and a list of columns.
     * 
     * @param string $table   The table name.
     * @param array $columns  The list of columns.
     * @return array $sql The resulting SQL code.
     */
    private function getCreateSQL($table, $columns)
    {
        $columns_names = implode(', ', $columns);
        $columns_params = ':' . implode(', :', $columns);
        $sql = "INSERT INTO $table ($columns_names) VALUES ($columns_params);";
        return $sql;
    }

    /**
     * Inserts a new record into the corresponding table using massive assignment.
     * 
     * @static
     * @param array $data The data to insert.
     * @return Model|false
     */
    public static function create($data)
    {
        $instance = new static;
        $columns = array_keys($data);

        // Check that all the columns are fillable
        foreach ($columns as $column) {
            if (!$instance->isFillable($column)) return false;
        }

        // Prepare and execute the query
        $sql = $instance->getCreateSQL($instance->table, $columns);
        $values = array_combine(
            explode(', ', ':' . implode(', :', $columns)),
            array_values($data)
        );
        $instance->preparedQuery($sql, $values);

        return $instance->last();
    }

    /**
     * Generates the SQL code to update an existent record in a table using the
     * table name and a list of columns.
     * 
     * @param string $table      The table name.
     * @param array $columns     The list of columns.
     * @param string $pk_column  The name of the primary key column.
     * @return array $sql The resulting SQL code.
     */
    private function getUpdateSQL($table, $columns, $pk_column)
    {
        $sql = "UPDATE $table SET ";
        $last = count($columns) - 1;
        for ($i = 0; $i < $last; $i++) {
            $sql .= "$columns[$i] = :$columns[$i], ";
        }
        $sql .= "$columns[$last] = :$columns[$last] WHERE $pk_column = :id;";
        return $sql;
    }

    /**
     * Updates a record by its primary key.
     * 
     * @static
     * @param mixed $id The primary key value.
     * @param array $data The data to update.
     * @return Model|false
     */
    public static function update($id, $data)
    {
        $instance = new static;
        $columns = array_keys($data);

        // Check that all the columns are fillable
        foreach ($columns as $column) {
            if (!$instance->isFillable($column)) return false;
        }

        // Prepare and execute the query
        $sql = $instance->getUpdateSQL($instance->table, $columns, $instance->pk_column);
        $values = array_combine(
            array_map(function ($col) {
                return ":$col";
            }, $columns),
            array_values($data)
        );
        $values[":id"] = $id;
        $instance->preparedQuery($sql, $values);

        return $instance->find($id);
    }

    /**
     * Saves the current record into the table.
     * If a primary key is not defined then create a new record.
     * 
     * @return void
     */
    public function save()
    {
        $pk = $this->pk_column;
        $action = $this::find($this->$pk) ? 'update' : 'create';
        $columns = array_filter($this->columns, function ($col) {
            if (!in_array($col, ['created_at', 'updated_at'])) return $col;
        });
        $sql = '';

        switch ($action) {
            case 'create':
                $sql .= $this->getCreateSQL($this->table, $columns);
                break;
            case 'update':
                $sql .= $this->getUpdateSQL($this->table, $columns, $pk);
                break;
        }

        // Prepare and execute the query
        $values = [];
        foreach ($columns as $column) {
            $values[":$column"] = $this->$column;
        }
        if ($action == 'update') $values[":id"] = $this->$pk;
        $this->preparedQuery($sql, $values);

        // Get the created or updated instance
        switch ($action) {
            case 'create':
                $instance = $this::last();
            case 'update':
                $instance = $this::find($this->$pk);
        }

        // Update all the properties
        foreach ($this->columns as $column) {
            $this->$column = $instance->$column;
        }
    }

    /**
     * Deletes a record by its primary key and returns the deleted element.
     * 
     * @static
     * @param mixed $id The primary key value.
     * @return static|false
     */
    public static function delete($id)
    {
        $instance = new static;
        $object = $instance->find($id);
        if (!$object) return false;

        $instance->preparedQuery(
            "DELETE FROM $instance->table WHERE $instance->pk_column = :id;",
            [':id' => $id]
        );

        return $object;
    }

    /**
     * Deletes a record by its primary key and returns the deleted element.
     * 
     * @return self
     */
    public function destroy()
    {
        $pk = $this->pk_column;
        $this->preparedQuery(
            "DELETE FROM $this->table WHERE $this->pk_column = :id;",
            [':id' => $this->$pk]
        );

        return $this;
    }

    /**
     * Deletes all records from the associated database table.
     *
     * This method executes a SQL DELETE statement to remove all rows from the table
     * represented by the current model. Use with caution, as this action is irreversible.
     * 
     * Returns the array with all deleted records
     *
     * @static
     * @return array
     */
    public static function deleteAll()
    {
        $instance = new static;
        $records = $instance->all();

        $instance->query("DELETE FROM $instance->table;");

        return $records;
    }

    /**
     * Define a one-to-one relationship.
     *
     * @param string $related          The related model name.
     * @param string|null $localKey    The local key on this model.
     * @param string|null $foreignKey  The primary key on the related model.
     * @return Model
     */
    public function hasOne($related, $localKey = null, $foreignKey = null)
    {
        // Require the related model
        require_once BASE . "app/models/$related.php";
        $relatedModel = new $related();

        // Define the foreign and local keys
        $relatedLower = strtolower($related);
        $localKey ??=  $relatedLower . '_id';
        $foreignKey ??= 'id';

        // Create the property
        $localKeyValue = $this->$localKey;
        return $relatedModel->where($foreignKey, $localKeyValue)->first();
    }

    // public function hasMany($related, $foreignKey = null, $localKey = null)
    // {
    // }
    // public function belongsTo($related, $foreignKey = null, $ownerKey = null)
    // {
    // }
}
