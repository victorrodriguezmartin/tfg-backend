
<?php

    require_once('ServerResponse.php');

    require_once('core/Service.php');
    require_once('core/Endpoints.php');

    if ($_SERVER['REQUEST_METHOD'] === 'GET') return contactServer($_GET);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') return contactServer($_POST);

    
    function contactServer($requestType)
    {
        if (!isset($requestType["request"]) || empty($requestType["request"]))
        return bad_request();

        $endpoints = $GLOBALS["endpoints"];

        if (array_key_exists($requestType["request"], $endpoints))
        {
            $service = new $endpoints[$requestType["request"]]["service"]();
            $endpoint = $endpoints[$requestType["request"]]["endpoint"];

            $result = $service->$endpoint($requestType);

            if (!isset($result) || empty($result) || !isset($result["success"]) || !isset($result["data"]))
                return bad_response();

            if ($result["success"] == 0) return db_query_error($result["data"]);
            if ($result["success"] == 1) return success($result["data"]);
        }

        return bad_request();
    }

?>

