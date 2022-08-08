
<?php

function formatted_response($code, $msg, $data = null)
{
    // if (is_null($code) || is_null($message))
    //     return server_error($NPE, __FILE__, __LINE__);

    $error = array();

    $error['code'] = $code;
    $error['msg'] = $msg;

    if ($data !== null)
        $error['data'] = $data;

    return json_encode($error);
}

function success($data)
{
    echo formatted_response(200, "Success", $data);
}

function bad_request()
{
    echo formatted_response(400, "Bad request");
}

function bad_response()
{
    echo formatted_response(500, "Server returned a bad response");
}

function db_connection_error($exception)
{
    echo formatted_response(500, "Could not establish a DB connection", $exception);
}

function db_query_error($exception)
{
    echo formatted_response(500, "Invalid database query", $exception);
}
?>
