
<?php

require_once('ServerResponse.php');

require_once('core/Service.php');
require_once('core/Endpoints.php');

// http://localhost/backend/Server.php
if ($_SERVER['REQUEST_METHOD'] === 'GET')
{
    if (!isset($_GET["request"]) || empty($_GET["request"]))
        return bad_request();
    
    if (array_key_exists($_GET["request"], $endpoints))
    {
        $service = new $endpoints[$_GET["request"]]["service"]();
        $endpoint = $endpoints[$_GET["request"]]["endpoint"];

        $result = $service->$endpoint();

        if (!isset($result) || empty($result) || !isset($result["success"]) || !isset($result["data"]))
            return bad_response();

        if ($result["success"] == 0) return db_query_error($result["data"]);
        if ($result["success"] == 1) return success($result["data"]);
    }

    return bad_request();
}

?>

