<?php
$app->get('/users', function() {
    $db1 = new DbHandler();
    $rows = $db1->select("t_usuario","uid,name,email,nivel",array());
    echoResponse(200, $rows);
});
$app->get('/session', function() {
    $db1 = new DbHandler();
    $session = $db1->getSession();
    $response["uid"] = $session['uid'];
    $response["email"] = $session['email'];
    $response["name"] = $session['name'];
    echoResponse(200, $session);
});

$app->put('/users/:id', function($id) use ($app) { 
    $data = json_decode($app->request->getBody());
    $condition = array('uid'=>$id);
    $mandatory = array();
    $db1 = new DbHandler();
    $rows = $db1->update("t_usuario", $data, $condition, $mandatory);
    if($rows["status"]=="success")
        $rows["message"] = "Información actualizada correctamente.";
    echoResponse(200, $rows);
});

$app->delete('/users/:id', function($id) { 
    $db1 = new DbHandler();
    $rows = $db1->delete("t_usuario", array('uid'=>$id));
    if($rows["status"]=="success")
        $rows["message"] = "Servicio eliminado correctamente.";
    echoResponse(200, $rows);
    
});

$app->post('/login', function() use ($app) {
    require_once 'passwordHash.php';
    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('email', 'password'),$r->customer);
    $response = array();
    $db1 = new DbHandler();
    $password = $r->customer->password;
    $email = $r->customer->email;
    $user = $db1->getOneRecord("select uid,name,email,password from t_usuario where name='$email' or email='$email' and nivel='ADMINISTRADOR'");
    if ($user != NULL) {      
        if(passwordHash::check_password($user['password'],$password)){
            $response['status'] = "success";
            $response['message'] = 'Bienvenido.';
            $response['name'] = $user['name'];
            $response['uid'] = $user['uid'];
            $response['email'] = $user['email'];
            
            if (!isset($_SESSION)) {
                session_start();
            }
            $_SESSION['uid'] = $user['uid'];
            $_SESSION['email'] = $email;
            $_SESSION['name'] = $user['name'];
        } else {
            $response['status'] = "error";
            $response['message'] = 'Datos incorrectos, intentelo de nuevo';              
        }
    }else {
            $response['status'] = "error";
            $response['message'] = 'No hay usuario registrado';
        }
        echoResponse(200, $response);
    });

$app->get('/recoverpass/:email', function($email){ 
    $db1 = new DbHandler();
    $rows = $db1->recoverPass($email);
    echoResponse(200, $rows);
});


$app->post('/signUp', function() use ($app) {
        $response = array();
        $r = json_decode($app->request->getBody());
        verifyRequiredParams(array('email', 'name', 'password'),$r->customer);
        require_once 'passwordHash.php';
        $db1 = new DbHandler();
    
        $name = $r->customer->name;
        $email = $r->customer->email;
    
        $password = $r->customer->password;
        $isUserExists = $db1->getOneRecord("select 1 from t_usuario where name='$name' or email='$email'");
        if(!$isUserExists){
            $r->customer->password = passwordHash::hash($password);
            $tabble_name = "t_usuario";
            $column_names = array('name', 'email', 'password');
            $result = $db1->insertIntoTable($r->customer,$column_names,$tabble_name);
            if ($result != NULL) {
                $response["status"] = "success";
                $response["message"] = "Usuario creado correctamente";
                $response["uid"] = $result;
                echoResponse(200, $response);
            } else {
                $response["status"] = "error";
                $response["message"] = "Error al crear usuario, Intentalo de nuevo";
                echoResponse(201, $response);
            }
        }else{
            $response["status"] = "error";
            $response["message"] = "El usuario existe, por favor prueba con otro";
            echoResponse(201, $response);
        }
});
$app->get('/logout', function() {
    $db1 = new DbHandler();
    $session = $db1->destroySession();
    $response["status"] = "info";
    $response["message"] = "Sesión concluida";
    echoResponse(200, $response);
});                                  
?>