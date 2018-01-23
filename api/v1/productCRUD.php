<?php
$app->get('/servicios', function() {
    $db1 = new DbHandler();
    $rows = $db1->select("t_categoria","id_categoria,categoria",array());
    echoResponse(200, $rows);
});
$app->get('/products/:id', function($id) {
    $db1 = new DbHandler();
    $rows = $db1->selectid("t_servicio","id,nombre,descripcion,precioPublico,IVAPub,precioEspecial,IVAPaq,nota",array('id_categoria'=>$id));
    echoResponse(200, $rows);
});


$app->post('/products/:id', function($id) use ($app) { 
    $data = json_decode($app->request->getBody());
    $idcat = $id;
    $db1 = new DbHandler();
    $rows = $db1->insertid("t_servicio", $data,$idcat);
    if($rows["status"]=="success")
        $rows["message"] = "Servicio agregado correctamente.";
    echoResponse(200, $rows);
});

$app->put('/products/:id', function($id) use ($app) { 
    $data = json_decode($app->request->getBody());
    $condition = array('id'=>$id);
    $mandatory = array();
    $db1 = new DbHandler();
    $rows = $db1->update("t_servicio", $data, $condition, $mandatory);
    if($rows["status"]=="success")
        $rows["message"] = "Información actualizada correctamente.";
    echoResponse(200, $rows);
});

$app->delete('/products/:id', function($id) { 
    $db1 = new DbHandler();
    $rows = $db1->delete("t_servicio", array('id'=>$id));
    if($rows["status"]=="success")
        $rows["message"] = "Servicio eliminado correctamente.";
    echoResponse(200, $rows);
    
});

$app->put('/priceProducts/:id', function($id) { 
    $ics = $id;
    $db1 = new DbHandler();
    $rows = $db1->pricingUpdate($ics);
    if($rows["status"]=="success")
        $rows["message"] = "Información actualizada correctamente.";
    echoResponse(200, $rows);
});

$app->put('/upServiceCot/:id', function($id) { 
    $db1 = new DbHandler();
    $rows = $db1->upServiceCot($id);
    if($rows["status"]=="success")
        $rows["message"] = "Información actualizada correctamente.";
    echoResponse(200, $rows);
});

$app->put('/downServiceCot/:id', function($id) { 
    $db1 = new DbHandler();
    $rows = $db1->downServiceCot($id);
    if($rows["status"]=="success")
        $rows["message"] = "Información actualizada correctamente.";
    echoResponse(200, $rows);
});

$app->get('/pricingServices', function() {
    $db1 = new DbHandler();
    $rows = $db1->select("t_servicio","id,nombre,descripcion,precioPublico,IVAPub,precioEspecial,IVAPaq,nota,tipoprecio,orden",array('estado'=>1));
    echoResponse(200, $rows);
});

$app->get('/pricingServicesSubTotal', function() {
    $db1 = new DbHandler();
    $rows = $db1->selectsub();
    echoResponse(200, $rows);
});

$app->delete('/pricingOffServices/:id', function($id){ 
    $ids = $id;
    $db1 = new DbHandler();
    $rows = $db1->pricingOffUpdate($ids);
    if($rows["status"]=="success")
        $rows["message"] = "Información actualizada correctamente.";
    echoResponse(200, $rows);
});

?>