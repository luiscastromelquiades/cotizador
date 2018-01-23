<?php

require_once 'dbHandler.php';

require_once 'passwordHash.php';
require '.././libs/Slim/Slim.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$conn = new dbHandler();

// User id from db - Global Variable
$user_id = NULL;

require_once 'authentication.php';
require_once 'productCRUD.php';
require_once 'cotizacionCRUD.php';
require_once 'mailAPI.php';

/**
 * Verifying required params posted or not
 */




function verifyRequiredParams($required_fields,$request_params) {
    $error = false;
    $error_fields = "";
    foreach ($required_fields as $field) {
        if (!isset($request_params->$field) || strlen(trim($request_params->$field)) <= 0) {
            $error = true;
            $error_fields .= $field . ', ';
        }
    }

    if ($error) {
        // Required field(s) are missing or empty
        // echo error json and stop the app
        $response = array();
        $app = \Slim\Slim::getInstance();
        $response["status"] = "error";
        $response["message"] = 'Campos requeridos(s) ' . substr($error_fields, 0, -2) . ' Falta o esta vacio';
        echoResponse(200, $response);
        $app->stop();
    }
}



function echoResponse($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    global $app;
    $app->status($status_code);
    // setting response content type to json
    $app->contentType('application/json');

    echo json_encode($response,JSON_NUMERIC_CHECK);
}

$app->run();
