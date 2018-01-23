<?php
$app->put('/mail/:id_cotizacion', function($id) {
    $idc=$id;
    $db1 = new DbHandler();
    $rows = $db1->sendMail($idc);
    echoResponse(200, $rows);
});

$app->put('/newmail', function() {
    $db1 = new DbHandler();
    $rows = $db1->sendMail2();
    echoResponse(200, $rows);
});
?>