<?php
    header("Access-Control-Allow-Origin: *"); #defino la api como publica --> *
    header('Access-Control-Allow-Credentials: true');#permite el uso de credenciales
    header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');#define los metodos que va a usar a api
    header("Access-Control-Allow-Headers: X-Requested-With");
    header('Content-Type: text/html; charset=utf-8');
    header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"');#cualquier tipo de cliente puede acceder a la API via P3P

    include_once '../include/Config.php';

    require '../libs/Slim/Slim.php';
    \Slim\Slim::registerAutoloader();
    $app = new \Slim\Slim();


    /*---------- FUNCIONES UTILITARIAS ---------- */
    function echoResponse($status_code, $response) 
    {
        $app = \Slim\Slim::getInstance();
        // Http response code
        $app->status($status_code);
        
        // setting response content type to json
        $app->contentType('application/json');
        
        echo json_encode($response);
    }


    #authenticate valida las consultas
    function authenticate(\Slim\Route $route) 
    {
        // Getting request headers
        $headers = apache_request_headers();
        $response = array();
        $app = \Slim\Slim::getInstance();
        
        // Verifying Authorization Header
        if (isset($headers['Authorization'])) {

            //$db = new DbHandler(); //utilizar para manejar autenticacion contra base de datos
        
            // get the api key
            $token = $headers['Authorization'];
        
            // validating api key
            if (!($token == API_KEY)) { //API_KEY declarada en Config.php
        
                // api key is not present in users table
                $response["error"] = true;
                $response["message"] = "Acceso denegado. Token inválido";
                echoResponse(401, $response);
        
                $app->stop(); //Detenemos la ejecución del programa al no validar
        
            } else {
            //procede utilizar el recurso o metodo del llamado
            }
            } else {
            // api key is missing in header
            $response["error"] = true;
            $response["message"] = "Falta token de autorización";
            echoResponse(400, $response);
            
            $app->stop();
        }
    }

?>