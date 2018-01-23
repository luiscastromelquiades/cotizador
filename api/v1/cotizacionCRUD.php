<?php

$app->get('/cotizaciones', function() {
    $db1 = new DbHandler();
    $rows = $db1->selectInner(" t_cotizacion INNER JOIN t_cliente "," t_cotizacion.estatus, t_cotizacion.id_cotizacion, t_cotizacion.tema, t_cliente.nombre, t_cliente.email, t_cliente.phone, t_cliente.cellphone, t_cotizacion.fecha, t_cotizacion.vigencia, t_cotizacion.formadepago, t_cotizacion.cuatrodigitos, t_cotizacion.descuento, t_cotizacion.anticipo, t_cotizacion.comentario, t_cotizacion.notasyrestricciones",array());
    echoResponse(200, $rows);
});

$app->post('/cotizaciones', function() use ($app) { 
    $data = json_decode($app->request->getBody(),true);
    $mandatory = array('estatus');
    $db1 = new DbHandler();
    $rows = $db1->insertProcedure($data);
    if($rows["status"]=="success")
        $rows["message"] = "Cotizacion agregada correctamente.";
    echoResponse(200, $rows);
});

$app->put('/cotizaciones', function() use ($app) {
    $data = json_decode($app->request->getBody(),true);
    $db1 = new DbHandler();
    $rows = $db1->updateProcedure($data);
    if($rows["status"]=="success")
        $rows["message"] = "Información actualizada correctamente.";
    echoResponse(200, $rows);
});

$app->delete('/cotizaciones/:id_cotizacion', function($id) { 
    $db1 = new DbHandler();
    $rows = $db1->delete("t_cotizacion", array('id_cotizacion'=>$id));
    if($rows["status"]=="success")
        $rows["message"] = "Cotizacion eliminada correctamente.";
    echoResponse(200, $rows);
});

$app->put('/addServicesCot/:id_cotizacion', function($id) { 
    $db1 = new DbHandler();
    $rows = $db1->addServicesCot($id);
    if($rows["status"]=="success")
        $rows["message"] = "Información actualizada correctamente.";
    echoResponse(200, $rows);
});
?>