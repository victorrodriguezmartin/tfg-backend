
<?php

define("DB_HOST", "localhost");
define("DB_NAME", "TFG");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "");

define("DATABASE_QUERY_FULL", PDO::FETCH_ASSOC);
define("DATABASE_QUERY_VALUES", PDO::FETCH_COLUMN);

class Service
{
    private $database;

    public function __construct()
    {
        try
        {
            $this->database = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
                DB_USERNAME, DB_PASSWORD);

            $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->database->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
            $this->database->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, TRUE);
        }
        catch (\PDOException $e)
        {
            return db_connection_error($e->message);
        }
    }

    function format_data($success, $data)
    {
        return [ "success" => $success, "data" => $data ];
    }
    
    function database_query($sql, $type = DATABASE_QUERY_FULL)
    {
        try
        {
            $request = $this->database->query($sql);
            $response = $request->fetchAll($type);

            return $response;
        }
        catch (\PDOException $e)
        {
            return $e;
        }
    }

    function formatted_database_query($sql, $type = DATABASE_QUERY_FULL)
    {
        try
        {
            $request = $this->database->query($sql);
            $response = $request->fetchAll($type);

            return $this->format_data(1, $response);
        }
        catch (\PDOException $e)
        {
            return $this->format_data(0, $e->getMessage());
        }
    }
}

?>
