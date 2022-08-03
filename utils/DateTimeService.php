
<?php

class DateTimeService extends Service
{
    // http://localhost/backend/server.php?request=getDate
    public function get_date()
    {
        $sql = "SELECT CONVERT(NOW(), date)";
        $response = $this->formatted_database_query($sql, DATABASE_QUERY_VALUES);

        if ($response["success"] == 0)
            return $response;
        
        $string = explode("-", $response["data"][0]);
        $string = implode("-", array_reverse($string));

        return $this->format_data($response["success"], $string);

    }

    // http://localhost/backend/server.php?request=getTime
    public function get_time()
    {
        $sql = "SELECT CONVERT(NOW(), time)";

        return $this->formatted_database_query($sql, DATABASE_QUERY_VALUES);
    }
}

?>
