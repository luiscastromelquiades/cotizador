<?php

class DbHandler {
    
    private $conn;
    
    function __construct() {
        require_once 'dbConnect.php';
        // opening db connection
        $db1 = new dbConnect();
        $this->conn = $db1->connect();
    }
    /**
     * Fetching single record
     */
    public function getOneRecord($query) {
        $r = $this->conn->query($query.' LIMIT 1') or die($this->conn->error.__LINE__);
        return $result = $r->fetch();
        
    }
    public function recoverPass($email) {
        try{
            $stmt = $this->conn->prepare("SELECT email, password FROM t_usuario WHERE email=".$email);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(count($rows)<=0){
                $response["status"] = "warning";
                $response["message"] = "No hay usuario registrado";
            }else{
                
                $response["status"] = "success";
                $response["message"] = "correo enviado";
            }
                $response["data"] = $rows;
        }catch(PDOException $e){
            $response["status"] = "error";
            $response["message"] = 'Select Failed: ' .$e->getMessage();
            $response["data"] = null;
        }
        return $response;
    }
    /**
     * Creating new record
     */
    public function insertIntoTable($obj, $column_names, $table_name) {
        
        $c = (array) $obj;
        $keys = array_keys($c);
        $columns = '';
        $values = '';
        foreach($column_names as $desired_key){ // Check the obj received. If blank insert blank into the array.
           if(!in_array($desired_key, $keys)) {
                $$desired_key = '';
            }else{
                $$desired_key = $c[$desired_key];
            }
            $columns = $columns.$desired_key.',';
            $values = $values."'".$$desired_key."',";
        }
        $query = "INSERT INTO ".$table_name."(".trim($columns,',').") VALUES(".trim($values,',').")";
        $r = $this->conn->query($query) or die($this->conn->error.__LINE__);

        if ($r) {
            $new_row_id = $this->conn->lastInsertId();
            return $new_row_id;
            } else {
            return NULL;
        }
    }
    

    
    public function getSession(){
        if (!isset($_SESSION)) {
            session_start();
        }
        $sess = array();
        if(isset($_SESSION['uid']))
        {
            $sess["uid"] = $_SESSION['uid'];
            $sess["name"] = $_SESSION['name'];
            $sess["email"] = $_SESSION['email'];
        }
        else
        {
            $sess["uid"] = '';
            $sess["name"] = 'Invitado';
            $sess["email"] = '';
        }
        return $sess;
    }

    
public function destroySession(){
    if (!isset($_SESSION)) {
    session_start();
    }
    if(isSet($_SESSION['uid']))
    {
        unset($_SESSION['uid']);
        unset($_SESSION['name']);
        unset($_SESSION['email']);
        $info='info';
        if(isSet($_COOKIE[$info]))
        {
            setcookie ($info, '', time() - $cookie_time);
        }
        $msg="Sesión concluida correctamente.";
    }
    else
    {
        $msg = "No ha iniciado sesión.";
    }
    return $msg;
}
    
public function select($table, $columns, $where){
        try{
            $a = array();
            $w = "";
            foreach ($where as $key => $value) {
                $w .= " and " .$key. " like :".$key;
                $a[":".$key] = $value;
            }
            $stmt = $this->conn->prepare("select ".$columns." from ".$table." where 1=1 ". $w);
            $stmt->execute($a);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(count($rows)<=0){
                $response["status"] = "warning";
                $response["message"] = "No data found.";
            }else{
                $response["status"] = "success";
                $response["message"] = "Data selected from database";
            }
                $response["data"] = $rows;
        }catch(PDOException $e){
            $response["status"] = "error";
            $response["message"] = 'Select Failed: ' .$e->getMessage();
            $response["data"] = null;
        }
        return $response;
    }
    
public function selectsub(){
        try{
            $stmt = $this->conn->prepare("CALL PA_Subtotal()");
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);        
            if(count($rows)<=0){
                $response["status"] = "warning";
                $response["message"] = "No data found.";
            }else{
                $response["status"] = "success";
                $response["message"] = "Data selected from database";
            }
                $response["data"] = $rows;
        }catch(PDOException $e){
            $response["status"] = "error";
            $response["message"] = 'Select Failed: ' .$e->getMessage();
            $response["data"] = null;
        }
        return $response;
    }
    
public function selectInner($table, $columns, $where){
        try{
            $a = array();
            $w = "";
            foreach ($where as $key => $value) {
                $w .= " and " .$key. " like :".$key;
                $a[":".$key] = $value;
            }
            $stmt = $this->conn->prepare("select ".$columns." from ".$table. " ON t_cotizacion.id_cotizacion=t_cliente.id_cotizacion
ORDER BY t_cotizacion.id_cotizacion ");
            $stmt->execute($a);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(count($rows)<=0){
                $response["status"] = "warning";
                $response["message"] = "No data found.";
            }else{
                $response["status"] = "success";
                $response["message"] = "Data selected from database";
            }
                $response["data"] = $rows;
        }catch(PDOException $e){
            $response["status"] = "error";
            $response["message"] = 'Select Failed: ' .$e->getMessage();
            $response["data"] = null;
        }
        return $response;
    }

public function selectid($table, $columns, $where){
        try{
            $a = array();
            $w = "";
            foreach ($where as $key => $value) {
                $w .= " and " .$key. " like :".$key;
                $a[":".$key] = $value;
            }
            $stmt = $this->conn->prepare("select ".$columns." from ".$table." where 1=1 ". $w. " AND estado=0 ");
            $stmt->execute($a);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(count($rows)<=0){
                $response["status"] = "warning";
                $response["message"] = "No data found.";
            }else{
                $response["status"] = "success";
                $response["message"] = "Data selected from database";
            }
                $response["data"] = $rows;
        }catch(PDOException $e){
            $response["status"] = "error";
            $response["message"] = 'Select Failed: ' .$e->getMessage();
            $response["data"] = null;
        }
        return $response;
    }

    
    
 public function select2($table, $columns, $where, $order){
        try{
            $a = array();
            $w = "";
            foreach ($where as $key => $value) {
                $w .= " and " .$key. " like :".$key;
                $a[":".$key] = $value;
            }
            $stmt = $this->conn->prepare("select ".$columns." from ".$table." where 1=1 ". $w." ".$order);
            $stmt->execute($a);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(count($rows)<=0){
                $response["status"] = "warning";
                $response["message"] = "No data found.";
            }else{
                $response["status"] = "success";
                $response["message"] = "Data selected from database";
            }
                $response["data"] = $rows;
        }catch(PDOException $e){
            $response["status"] = "error";
            $response["message"] = 'Select Failed: ' .$e->getMessage();
            $response["data"] = null;
            
        }
        return $response;
    }
    
public function insert($table, $columnsArray, $requiredColumnsArray) {
        $this->verifyRequiredParams($columnsArray, $requiredColumnsArray);
        
        try{
            $a = array();
            $c = "";
            $v = "";
            foreach ($columnsArray as $key => $value) {
                $c .= $key. ", ";
                $v .= ":".$key. ", ";
                $a[":".$key] = $value;
            }
            $c = rtrim($c,', ');
            $v = rtrim($v,', ');
            $stmt =  $this->conn->prepare("INSERT INTO $table($c) VALUES($v)");
            $stmt->execute($a);
            $affected_rows = $stmt->rowCount();
            $lastInsertId = $this->conn->lastInsertId();
            $response["status"] = "success";
            $response["message"] = $affected_rows." row inserted into database";
            $response["data"] = $lastInsertId;
        }catch(PDOException $e){
            $response["status"] = "error";
            $response["message"] = 'Insert Failed: ' .$e->getMessage();
            $response["data"] = 0;
        }
        return $response;
    }
    
    

    
function sendMail($idc) {
    date_default_timezone_set("America/Mexico_City");
    $dia="";
    $mes="";
    if(strftime("%A")=="Monday"){
        $dia="Lunes";
    }else if(strftime("%A")=="Tuesday"){
        $dia="Martes";
    }
    else if(strftime("%A")=="Wednesday"){
        $dia="Miercoles";
    }
    else if(strftime("%A")=="Thursday"){
        $dia="Jueves";
    }else if(strftime("%A")=="Friday"){
        $dia="Viernes";
    }else if(strftime("%A")=="Saturday"){
        $dia="S&aacutebado";
    }else{
        $dia="Domingo";
    }
    
    if(strftime("%B")=="January"){
        $mes="Enero";
    }else if(strftime("%B")=="February"){
        $mes="Febrero";
    }else if(strftime("%B")=="March"){
        $mes="Marzo";
    }else if(strftime("%B")=="April"){
        $mes="Abril";
    }else if(strftime("%B")=="May"){
        $mes="Mayo";
    }else if(strftime("%B")=="June"){
        $mes="Junio";
    }else if(strftime("%B")=="July"){
        $mes="Julio";
    }else if(strftime("%B")=="August"){
        $mes="Agosto";
    }else if(strftime("%B")=="September"){
        $mes="Septiembre";
    }else if(strftime("%B")=="October"){
        $mes="Octubre";
    }else if(strftime("%B")=="November"){
        $mes="Noviembre";
    }else{
        $mes="Diciembre";
    }
    
    $fechacotizacion = $dia.strftime(", %d de ").$mes.strftime(" de %Y")."";
    $id_cotizacion = '';
    $tema = '';
    $cliente = '';
    $mail = '';
    $fecha = '';
    $vigencia = '';
    $descuento = '';
    $anticipo = '';
    $comentario = '';
    $notasyrestricciones = '';
    $total = '';
    $productospub = '';
    $productospaq = '';
    $precioespecial = '';
    $subtotal = '';
    $iva = '';
    $saldo = '';
    try{
            $stmt = $this->conn->prepare("SELECT t_cotizacion.id_cotizacion, t_cotizacion.tema, t_cliente.nombre, t_cliente.email, t_cotizacion.fecha, t_cotizacion.vigencia,  t_cotizacion.descuento, t_cotizacion.anticipo, t_cotizacion.comentario, t_cotizacion.notasyrestricciones, t_cotizacion.subtotal, t_cotizacion.iva, t_cotizacion.total, t_cotizacion.saldo FROM t_cotizacion INNER JOIN t_cliente ON t_cotizacion.id_cotizacion=t_cliente.id_cotizacion WHERE t_cotizacion.id_cotizacion=".$idc." ORDER BY t_cotizacion.id_cotizacion DESC LIMIT 1;");
            $stmt->execute();
            $rows = $stmt->fetchAll();
            
            foreach ($rows as $key => $value) {
                $id_cotizacion = $value['id_cotizacion'];
                $tema = $value['tema'];
                $cliente = $value['nombre'];
                $mail = $value['email'];
                $fecha = $value['fecha'];                
                $vigencia = $value['vigencia'];             
                $comentario = $value['comentario'];
                $notasyrestricciones = $value['notasyrestricciones'];
                if($value['descuento'] != '0'){
                    $descuento .= "<strong>Descuento:&nbsp;$&nbsp;".$value['descuento']."&nbsp;M.N.&nbsp;</strong>";
                }
                if($value['anticipo'] != '0'){
                    $anticipo .= "<strong>Anticipo:&nbsp;$&nbsp;".$value['anticipo']."&nbsp;M.N.&nbsp;</strong>";
                }
                
                if($value['iva'] != '0'){
                    $subtotal .="
                       <td align='right'><strong>Subtotal:&nbsp;$&nbsp;".$value['subtotal']."&nbsp;M.N.&nbsp;</strong></td>";
                    $iva .="
                       <td align='right'><strong>I.V.A.:&nbsp;$&nbsp;".$value['iva']."&nbsp;M.N.&nbsp;</strong></td>";
                    $saldo .="
                        <tr>
                      <td>&nbsp;<p align='right'></strong><p></td>
                       <td align='right'><strong >Total:&nbsp;$&nbsp;".$value['saldo']."&nbsp;M.N.&nbsp;</strong></td>
                       </tr>";
                }else{
                    $saldo .="<tr>
                      <td>&nbsp;<p align='right'></strong><p></td>
                       <td align='right'><strong >Total:&nbsp;$&nbsp;".$value['saldo']."&nbsp;M.N.&nbsp;</strong></td>
                    </tr>";
                }
                
            }
       
        $fixedfecha = date('Y-m-d', strtotime($fecha));
        $fixedvigencia = date('Y-m-d', strtotime($vigencia));
        }catch(PDOException $e){
          $error = "error: "  .$e->getMessage();
        $response["status"] = "error";
            $response["message"] = 'Insert Failed: ' .$e->getMessage();
            $response["data"] = 0;
        }
    
    try{
            $stmt2 = $this->conn->prepare("SELECT DISTINCT t_servicio.nombre, t_servicio.descripcion, t_servicio.precioPublico, t_servicio.tipoprecio, t_servicio.orden FROM t_servicio INNER JOIN t_serviciocotizado ON t_servicio.id=t_serviciocotizado.id_servicio WHERE t_serviciocotizado.id_cotizacion=".$idc." AND t_servicio.tipoprecio='PUBLICO' 
UNION 
SELECT DISTINCT t_servicio.nombre, t_servicio.descripcion, t_servicio.precioEspecial, t_servicio.tipoprecio, t_servicio.orden FROM t_servicio INNER JOIN t_serviciocotizado ON t_servicio.id=t_serviciocotizado.id_servicio WHERE t_serviciocotizado.id_cotizacion=".$idc." AND t_servicio.tipoprecio='PAQUETE' ORDER BY orden");
            $stmt2->execute();
            $rowspub = $stmt2->fetchAll();
            
            foreach ($rowspub as $key => $value) {
                $productospub .= "<tr><td colspan='2'><h6>".$value['nombre']."</h6></td></tr><tr><td><p>".$value['descripcion']."</p></td><td><p align='right'><strong>$&nbsp;".$value['precioPublico']."&nbsp;M.N.&nbsp;</strong></p></td></tr>";
                if($value['tipoprecio'] == 'PAQUETE') {
                    $precioespecial = "<tr><td colspan=2 style='vertical-align:middle;'><strong>Usted cuenta con precio especial!</strong><img src='http://masoko.mx/formularios/tripticos/img/especial.gif' width='35px' height='35px'></td></tr>";    
                } 
            }
       
        }catch(PDOException $e){
        $response["status"] = "error";
            $response["message"] = 'Insert Failed: ' .$e->getMessage();
            $response["data"] = 0;
        }
    
    
    /*try{
            $stmt3 = $this->conn->prepare("SELECT DISTINCT t_servicio.nombre, t_servicio.descripcion, t_servicio.precioEspecial FROM t_servicio INNER JOIN t_serviciocotizado WHERE t_servicio.id=t_serviciocotizado.id_servicio AND t_serviciocotizado.id_cotizacion=".$idc." AND t_servicio.tipoprecio='PAQUETE';");
            $stmt3->execute();
            $rowspaq = $stmt3->fetchAll();
            if (!empty($rowspaq)) {
                $precioespecial .= "<tr><td colspan=2 style='vertical-align:middle;'><strong>Usted cuenta con precio especial!</strong><img src='http://masoko.mx/formularios/tripticos/img/especial.gif' width='35px' height='35px'></td></tr>";
            }
            foreach ($rowspaq as $key => $value) {
                $productospaq .= "<tr><td colspan='2'><h6>".$value['nombre']."</h6></td></tr><tr><td><p>".$value['descripcion']."</p></td><td><p align='right'><strong>$&nbsp;".$value['precioEspecial']."&nbsp;M.N.&nbsp;</strong></p></td></tr>";
            }
       
        }catch(PDOException $e){
        $response["status"] = "error";
            $response["message"] = 'Insert Failed: ' .$e->getMessage();
            $response["data"] = 0;
        }*/
    
 
    $mensaje = '
<!doctype html>
<html>
<head>
<meta charset="latin1_spanish_ci">
<meta name="robots" content="index,nofollow" />
<link rel="shortcut icon" href="http://masoko.mx/formularios/tripticos/img/favicon.ico" />
<title>Masoko Publicidad</title>
<link rel="stylesheet" type="text/css" href="http://masoko.mx/formularios/tripticos/css/fuentes.css">
<style type="text/css">
body, td {
	font-family: calibri;
	font-size:18px;
}
h1 {
	 font-family: "brushedregular";
	 font-size:16pt;
	 color:#DD0033;
}
h5 {
	font-family: "thin_designregular";
	margin: 0px 25px;
    font-size: 18px;
	letter-spacing: 2px;
	font-weight:lighter;
}

h6 {
	font-family: "thin_designregular";
	margin: 0px 25px;
    font-size: 15px;
	letter-spacing: 2px;
	font-weight:lighter;
}
strong {	
	color: #DD0033; 
	font-size: 16px; 
}
b {
	color:#00ACDB; 
	font-style:italic;
}
a {
	color:#00ACDB; 
	text-decoration:none;
}

a:hover{
	text-decoration:underline;
}

p {
    padding-left:10px;
    padding-right:10px;
}

ul {
    margin-left:25px;
    padding-left:10px;
    padding-right:10px;
}

h3 {
    padding-left:10px;
    padding-right:10px;
}
</style>
</head>
<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
	  <td>
	    <table width="780" border="0" cellspacing="0" cellpadding="0">
		  <tr>
		    <td colspan="3">
   			  <img src= "http://masoko.mx/formularios/tripticos/img/cabecera-email.png" usemap="#Map" style="display:block" border="0">
		      </td>
		  </tr>       
		  <tr>
		    <td width="60" background="http://masoko.mx/formularios/tripticos/img/contenido-izquierda.png">
            </td>
			  <td width="660" background="http://masoko.mx/formularios/tripticos/img/contenido-centro.png">               
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tbody>
                    <tr>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2"><p align="right"><strong>Folio: '.$idc.'&nbsp;&nbsp;</strong></p></td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong style="margin-left:5px;">Fecha de emisión: '.$fixedfecha.'&nbsp;&nbsp;</strong></td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong style="margin-left:5px;">Cotización vigente hasta: '.$fixedvigencia.'&nbsp;&nbsp;</strong></td>
                    </tr>
                    <tr>
                      <td colspan="2"><p align="right" ><strong>México DF a '.$fechacotizacion.'</strong>&nbsp;&nbsp;</p></td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <h5>'.$cliente.'</h5>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <h1 align="center">'.$tema.'</h1>
                      </td>
                    </tr>
                    <tr> 
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="2"><p text-align="justify">&nbsp;&nbsp;Con el propósito de servirle en el desarrollo del proyecto de comunicación, tengo a bien presentarte la siguiente cotización.</p></td>
                    </tr>
                    
                    <tr> 
                      <td>&nbsp;</td>
                    </tr>
                    '.$productospub.'
                    <tr> 
                      <td>&nbsp;</td>
                    </tr>
                    '.$precioespecial.'
                    <tr><td colspan="2"><hr></td></tr>
                    <tr> 
                      <td align="right" width="67%">&nbsp;'.$anticipo.'</td>
                      '.$subtotal.'
                    </tr>
                    <tr>
                      <td align="right" width="67%">&nbsp;'.$descuento.'</td>
                    '.$iva.'
                    </tr>
                    '.$saldo.' 
                    <tr>
                      <td colspan="2">&nbsp;<strong> Notas y Restricciones</strong></td>
                    </tr>
                    <tr> 
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="2">
                      '.$notasyrestricciones.'        
                      </td>
                    </tr>
                    <tr> 
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td></td>
                    </tr>
                  </tbody>
                </table>
              </td>
			  <td width="60" background="http://masoko.mx/formularios/tripticos/img/contenido-derecha.png">
              </td>         
		  </tr>           
          <tr>
		    <td colspan="3">
   			   <img src= "http://masoko.mx/formularios/tripticos/img/footer-email.png" usemap="#Map2" style="display:block" border="0">
	        </td>
		  </tr>                      
	  </table>
    </td>
	</tr>    
	<tr>
		<td>      
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			  </tr>
			<tr>
				
                
    <td align="justify">
		<h5 align="center">Aviso de Privacidad</h5>
			<h6>En Masoko Publicidad nos tomamos muy en serio el carácter privado de su información personal. Nuestro objetivo es brindarle información relevante y dirigida a su interés, y al mismo tiempo proteger su privacidad. Con el fin de tener un contacto cercano con usted. Masoko Publicidad no recopila ninguna otra información personal que usted no proporciona explícitamente. Masoko Publicidad no divulgará su información personal a terceros sin su consentimiento.
            </h6>
   </td>				
			</tr>
			</table>
               
		</td>
	</tr>
    
	</table>

<map name="Map">
  <area shape="rect" coords="379,99,718,154" href="http://masoko.mx" target="_blank" alt="">
  <area shape="rect" coords="437,185,646,213" href="mailto:info@masoko.mx" alt="">
  <area shape="rect" coords="49,31,201,270" href="http://masoko.mx" target="_blank" alt="">
</map>

<map name="Map2">
  <area shape="rect" coords="78,97,122,139" href="https://twitter.com/masoko_ads" target="_blank" alt="">
  <area shape="rect" coords="132,95,173,138" href="https://www.facebook.com/masoko.ads" target="_blank" alt="">
  <area shape="rect" coords="185,94,227,138" href="https://plus.google.com/+MasokoMx/posts" target="_blank">
  <area shape="rect" coords="239,95,281,137" href="https://www.linkedin.com/company/masoko-publicidad" target="_blank">
  <area shape="rect" coords="295,96,338,137" href="https://www.pinterest.com/masokoads/pins/" target="_blank">
  <area shape="rect" coords="347,97,390,140" href="https://es.foursquare.com/masoko_ads" target="_blank">
  <area shape="rect" coords="402,96,448,139" href="https://delicious.com/masoko" target="_blank">
  <area shape="rect" coords="460,97,501,140" href="http://masokoads.blogspot.mx/" target="_blank">
  <area shape="rect" coords="511,94,556,139" href="https://instagram.com/_masoko/" target="_blank" alt="">
  <area shape="rect" coords="569,95,609,138" href="http://www.yelp.es/biz/agencias-de-publicidad-masoko-m%C3%A9xico?ytprail=1" target="_blank">
</map>
</body>
</html>';
    //datos para Email
    $cristina = 'cristina@masoko.mx';
    $mauricio = 'mlopez@masoko.mx';
    $luis = 'luiscastromelquiades@gmail.com';
    $nombre = 'Masoko Publicidad';
    $email = 'cotizaciones@masoko.mx';  
    $headers = "From: ".$nombre." <".$email."> \n";
    //$headers .= "Cc:".$luis." \n";
    $headers .= "Content-Type: text/html; charset='latin1_spanish_ci' \n";


if (mail($mail,"Su presupuesto de Masoko",$mensaje,$headers))
//    $ok = 'correcto';

    $hora = date("hisa");
    $fichero_salida="../../cotizaciones/cotizacion_".$id_cotizacion.".html";
    $fp=fopen($fichero_salida,"wa+") or die ("Error al crear");
    fwrite($fp,$mensaje);
    fclose($fp);


    $response["status"] = "success";
    $response["message"] = "archivo creado y correo enviado";
    $response["data"] = $fichero_salida;
	    
    
return $response;

    }//<--Termina mail

    
//->empieza email sin id
function sendMail2() {
    date_default_timezone_set("America/Mexico_City");
    $dia="";
    $mes="";
    if(strftime("%A")=="Monday"){
        $dia="Lunes";
    }else if(strftime("%A")=="Tuesday"){
        $dia="Martes";
    }
    else if(strftime("%A")=="Wednesday"){
        $dia="Miercoles";
    }
    else if(strftime("%A")=="Thursday"){
        $dia="Jueves";
    }else if(strftime("%A")=="Friday"){
        $dia="Viernes";
    }else if(strftime("%A")=="Saturday"){
        $dia="S&aacutebado";
    }else{
        $dia="Domingo";
    }
    
    if(strftime("%B")=="January"){
        $mes="Enero";
    }else if(strftime("%B")=="February"){
        $mes="Febrero";
    }else if(strftime("%B")=="March"){
        $mes="Marzo";
    }else if(strftime("%B")=="April"){
        $mes="Abril";
    }else if(strftime("%B")=="May"){
        $mes="Mayo";
    }else if(strftime("%B")=="June"){
        $mes="Junio";
    }else if(strftime("%B")=="July"){
        $mes="Julio";
    }else if(strftime("%B")=="August"){
        $mes="Agosto";
    }else if(strftime("%B")=="September"){
        $mes="Septiembre";
    }else if(strftime("%B")=="October"){
        $mes="Octubre";
    }else if(strftime("%B")=="November"){
        $mes="Noviembre";
    }else{
        $mes="Diciembre";
    }
    
    $fechacotizacion = $dia.strftime(", %d de ").$mes.strftime(" de %Y")."";
    $id_cotizacion = '';
    $tema = '';
    $cliente = '';
    $mail = '';
    $fecha = '';
    $vigencia = '';
    $descuento = '';
    $anticipo = '';
    $comentario = '';
    $notasyrestricciones = '';
    $total = '';
    $productospub = '';
    $productospaq = '';
    $precioespecial = '';
    $subtotal = '';
    $iva = '';
    $saldo = '';

    try{
            $stmt = $this->conn->prepare("SELECT t_cotizacion.id_cotizacion, t_cotizacion.tema, t_cliente.nombre, t_cliente.email, t_cotizacion.fecha, t_cotizacion.vigencia,  t_cotizacion.descuento, t_cotizacion.anticipo, t_cotizacion.comentario, t_cotizacion.notasyrestricciones, t_cotizacion.subtotal, t_cotizacion.iva, t_cotizacion.total, t_cotizacion.saldo FROM t_cotizacion INNER JOIN t_cliente ON t_cotizacion.id_cotizacion=t_cliente.id_cotizacion ORDER BY t_cotizacion.id_cotizacion DESC LIMIT 1;");
            $stmt->execute();
            $rows = $stmt->fetchAll();
            
            foreach ($rows as $key => $value) {
                $id_cotizacion = $value['id_cotizacion'];
                $tema = $value['tema'];
                $cliente = $value['nombre'];
                $mail = $value['email'];
                $fecha = $value['fecha'];                
                $vigencia = $value['vigencia'];             
                $comentario = $value['comentario'];
                $notasyrestricciones = $value['notasyrestricciones'];
                if($value['descuento'] != '0'){
                    $descuento .= "<strong>Descuento:&nbsp;$&nbsp;".$value['descuento']."&nbsp;M.N.&nbsp;</strong>";
                }
                if($value['anticipo'] != '0'){
                    $anticipo .= "<strong>Anticipo:&nbsp;$&nbsp;".$value['anticipo']."&nbsp;M.N.&nbsp;</strong>";
                }
                
                if($value['iva'] != '0'){
                    $subtotal .="
                       <td align='right'><strong>Subtotal:&nbsp;$&nbsp;".$value['subtotal']."&nbsp;M.N.&nbsp;</strong></td>";
                    $iva .="
                       <td align='right'><strong>I.V.A.:&nbsp;$&nbsp;".$value['iva']."&nbsp;M.N.&nbsp;</strong></td>";
                    $saldo .="
                        <tr>
                      <td>&nbsp;<p align='right'></strong><p></td>
                       <td align='right'><strong >Total:&nbsp;$&nbsp;".$value['saldo']."&nbsp;M.N.&nbsp;</strong></td>
                       </tr>";
                }else{
                    $saldo .="<tr>
                      <td>&nbsp;<p align='right'></strong><p></td>
                       <td align='right'><strong >Total:&nbsp;$&nbsp;".$value['saldo']."&nbsp;M.N.&nbsp;</strong></td>
                    </tr>";
                }
            }
       
        $fixedfecha = date('Y-m-d', strtotime($fecha));
        $fixedvigencia = date('Y-m-d', strtotime($vigencia));
        }catch(PDOException $e){
          $error = "error: "  .$e->getMessage();
        $response["status"] = "error";
            $response["message"] = 'Insert Failed: ' .$e->getMessage();
            $response["data"] = 0;
        }
    
    try{
            $stmt2 = $this->conn->prepare("SELECT DISTINCT t_servicio.nombre, t_servicio.descripcion, t_servicio.precioPublico, t_servicio.tipoprecio, t_servicio.orden FROM t_servicio INNER JOIN t_serviciocotizado ON t_servicio.id=t_serviciocotizado.id_servicio WHERE t_serviciocotizado.id_cotizacion=".$id_cotizacion." AND t_servicio.tipoprecio='PUBLICO' 
UNION 
SELECT DISTINCT t_servicio.nombre, t_servicio.descripcion, t_servicio.precioEspecial, t_servicio.tipoprecio, t_servicio.orden FROM t_servicio INNER JOIN t_serviciocotizado ON t_servicio.id=t_serviciocotizado.id_servicio WHERE t_serviciocotizado.id_cotizacion=".$id_cotizacion." AND t_servicio.tipoprecio='PAQUETE' ORDER BY orden");
            $stmt2->execute();
            $rowspub = $stmt2->fetchAll();
            
            foreach ($rowspub as $key => $value) {
                $productospub .= "<tr><td colspan='2'><h6>".$value['nombre']."</h6></td></tr><tr><td><p>".$value['descripcion']."</p></td><td><p align='right'><strong>$".$value['precioPublico']."</strong></p></td></tr>";
                if($value['tipoprecio'] == 'PAQUETE') {
                    $precioespecial = "<tr><td colspan=2 style='vertical-align:middle;'><strong>Usted cuenta con precio especial!</strong><img src='http://masoko.mx/formularios/tripticos/img/especial.gif' width='35px' height='35px'></td></tr>";    
                } 
            }
       
        }catch(PDOException $e){
        $response["status"] = "error";
            $response["message"] = 'Insert Failed: ' .$e->getMessage();
            $response["data"] = 0;
        }
    
    
    /*try{
            $stmt3 = $this->conn->prepare("SELECT DISTINCT t_servicio.nombre, t_servicio.descripcion, t_servicio.precioEspecial FROM t_servicio INNER JOIN t_serviciocotizado WHERE t_servicio.id=t_serviciocotizado.id_servicio AND t_serviciocotizado.id_cotizacion=".$id_cotizacion." AND t_servicio.tipoprecio='PAQUETE';");
            $stmt3->execute();
            $rowspaq = $stmt3->fetchAll();
            if (!empty($rowspaq)) {
                $precioespecial .= "<tr><td colspan=2 style='vertical-align:middle;'><strong>Usted cuenta con precio especial!</strong><img src='http://masoko.mx/formularios/tripticos/img/especial.gif' width='35px' height='35px'></td></tr>";
            }
            foreach ($rowspaq as $key => $value) {
                $productospaq .= "<tr><td colspan='2'><h6>".$value['nombre']."</h6></td></tr><tr><td><p>".$value['descripcion']."</p></td><td><p align='right'><strong>$".$value['precioEspecial']."</strong></p></td></tr>";
            }
       
        }catch(PDOException $e){
        $response["status"] = "error";
            $response["message"] = 'Insert Failed: ' .$e->getMessage();
            $response["data"] = 0;
        }*/
    
 
    $mensaje ='
<!doctype html>
<html>
<head>
<meta charset="latin1_spanish_ci">
<meta name="robots" content="index,nofollow" />
<link rel="shortcut icon" href="http://masoko.mx/formularios/tripticos/img/favicon.ico" />
<title>Masoko Publicidad</title>
<link rel="stylesheet" type="text/css" href="http://masoko.mx/formularios/tripticos/css/fuentes.css">
<style type="text/css">
body, td {
	font-family: calibri;
	font-size:18px;
}
h1 {
	 font-family: "brushedregular";
	 font-size:16pt;
	 color:#DD0033;
}
h5 {
	font-family: "thin_designregular";
	margin: 0px 25px;
    font-size: 18px;
	letter-spacing: 2px;
	font-weight:lighter;
}

h6 {
	font-family: "thin_designregular";
	margin: 0px 25px;
    font-size: 15px;
	letter-spacing: 2px;
	font-weight:lighter;
}
strong {	
	color: #DD0033; 
	font-size: 16px; 
}
b {
	color:#00ACDB; 
	font-style:italic;
}
a {
	color:#00ACDB; 
	text-decoration:none;
}

a:hover{
	text-decoration:underline;
}

p {
    
    padding-left:10px;
    padding-right:10px;
}

ul {
    margin-left:25px;
    padding-left:10px;
    padding-right:10px;
}

h3 {
    padding-left:10px;
    padding-right:10px;
}
</style>
</head>
<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
	  <td>
	    <table width="780" border="0" cellspacing="0" cellpadding="0">
		  <tr>
		    <td colspan="3">
   			  <img src= "http://masoko.mx/formularios/tripticos/img/cabecera-email.png" usemap="#Map" style="display:block" border="0">
		      </td>
		  </tr>       
		  <tr>
		    <td width="60" background="http://masoko.mx/formularios/tripticos/img/contenido-izquierda.png">
            </td>
			  <td width="660" background="http://masoko.mx/formularios/tripticos/img/contenido-centro.png">               
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tbody>
                    <tr>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2"><p align="right"><strong>Folio: '.$id_cotizacion.'&nbsp;&nbsp;</strong></p></td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong style="margin-left:5px;">Fecha de emisión: '.$fixedfecha.'&nbsp;&nbsp;</strong></td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong style="margin-left:5px;">Cotización vigente hasta: '.$fixedvigencia.'&nbsp;&nbsp;</strong></td>
                    </tr>
                    <tr>
                      <td colspan="2"><p align="right" ><strong>México DF a '.$fechacotizacion.'</strong>&nbsp;&nbsp;</p></td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <h5>'.$cliente.'</h5>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <h1 align="center">'.$tema.'</h1>
                      </td>
                    </tr>
                    <tr> 
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="2"><p text-align="justify">&nbsp;&nbsp;Con el propósito de servirle en el desarrollo del proyecto de comunicación, tengo a bien presentarte la siguiente cotización.</p></td>
                    </tr>
                    
                    <tr> 
                      <td>&nbsp;</td>
                    </tr>
                    '.$productospub.'
                    <tr> 
                      <td>&nbsp;</td>
                    </tr>
                    '.$precioespecial.'
                    <tr><td colspan="2"><hr></td></tr>
                    <tr> 
                      <td align="right" width="67%">&nbsp;'.$anticipo.'</td>
                      '.$subtotal.'
                    </tr>
                    <tr>
                      <td align="right" width="67%">&nbsp;'.$descuento.'</td>
                    '.$iva.'
                    </tr>
                    '.$saldo.' 
                    <tr>
                      <td colspan="2">&nbsp;<strong> Notas y Restricciones</strong></td>
                    </tr>
                    <tr> 
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="2">
                      '.$notasyrestricciones.'        
                      </td>
                    </tr>
                    <tr> 
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td></td>
                    </tr>
                  </tbody>
                </table>
              </td>
			  <td width="60" background="http://masoko.mx/formularios/tripticos/img/contenido-derecha.png">
              </td>         
		  </tr>           
          <tr>
		    <td colspan="3">
   			   <img src= "http://masoko.mx/formularios/tripticos/img/footer-email.png" usemap="#Map2" style="display:block" border="0">
	        </td>
		  </tr>                      
	  </table>
    </td>
	</tr>    
	<tr>
		<td>      
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			  </tr>
			<tr>
				
                
    <td align="justify">
		<h5 align="center">Aviso de Privacidad</h5>
			<h6>En Masoko Publicidad nos tomamos muy en serio el carácter privado de su información personal. Nuestro objetivo es brindarle información relevante y dirigida a su interés, y al mismo tiempo proteger su privacidad. Con el fin de tener un contacto cercano con usted. Masoko Publicidad no recopila ninguna otra información personal que usted no proporciona explícitamente. Masoko Publicidad no divulgará su información personal a terceros sin su consentimiento.
            </h6>
   </td>				
			</tr>
			</table>
               
		</td>
	</tr>
    
	</table>

<map name="Map">
  <area shape="rect" coords="379,99,718,154" href="http://masoko.mx" target="_blank" alt="">
  <area shape="rect" coords="437,185,646,213" href="mailto:info@masoko.mx" alt="">
  <area shape="rect" coords="49,31,201,270" href="http://masoko.mx" target="_blank" alt="">
</map>

<map name="Map2">
  <area shape="rect" coords="78,97,122,139" href="https://twitter.com/masoko_ads" target="_blank" alt="">
  <area shape="rect" coords="132,95,173,138" href="https://www.facebook.com/masoko.ads" target="_blank" alt="">
  <area shape="rect" coords="185,94,227,138" href="https://plus.google.com/+MasokoMx/posts" target="_blank">
  <area shape="rect" coords="239,95,281,137" href="https://www.linkedin.com/company/masoko-publicidad" target="_blank">
  <area shape="rect" coords="295,96,338,137" href="https://www.pinterest.com/masokoads/pins/" target="_blank">
  <area shape="rect" coords="347,97,390,140" href="https://es.foursquare.com/masoko_ads" target="_blank">
  <area shape="rect" coords="402,96,448,139" href="https://delicious.com/masoko" target="_blank">
  <area shape="rect" coords="460,97,501,140" href="http://masokoads.blogspot.mx/" target="_blank">
  <area shape="rect" coords="511,94,556,139" href="https://instagram.com/_masoko/" target="_blank" alt="">
  <area shape="rect" coords="569,95,609,138" href="http://www.yelp.es/biz/agencias-de-publicidad-masoko-m%C3%A9xico?ytprail=1" target="_blank">
</map>
</body>
</html>
';
    //datos para Email
    $cristina = 'cristina@masoko.mx';
    $mauricio = 'mlopez@masoko.mx';
    $luis = 'luiscastromelquiades@gmail.com';
    $nombre = 'Masoko Publicidad';
    $email = 'cotizaciones@masoko.mx';  
    $headers = "From: ".$nombre." <".$email."> \n";
    //$headers .= "Cc:".$luis." \n";
    $headers .= "Content-Type: text/html; charset='latin1_spanish_ci' \n";


if (mail($mail,"Su presupuesto de Masoko",$mensaje,$headers))
 //   $ok = 'correcto';

    $hora = date("hisa");
    $fichero_salida="../../cotizaciones/cotizacion_".$id_cotizacion.".html";
    $fp=fopen($fichero_salida,"wa+") or die ("Error al crear");
    fwrite($fp,$mensaje);
    fclose($fp);


    $response["status"] = "success";
    $response["message"] = "archivo creado y correo enviado";
    $response["data"] = $fichero_salida;
	    
    
return $response;

    }//<--Termina mail2
    
    //-->procedimiento almacenado para agregar servicios cotizados a listado de servicios
    
public function addServicesCot($id) { 
        try{
            $stmt =  $this->conn->prepare("CALL PA_AddServicesCot($id)");
            $stmt->execute();
            $affected_rows = $stmt->rowCount();
            $lastInsertId = $this->conn->lastInsertId();
            $response["status"] = "success";
            $response["message"] = $affected_rows." row inserted into database";
            $response["data"] = $lastInsertId;
        }catch(PDOException $e){
            $response["status"] = "error";
            $response["message"] = 'Insert Failed: ' .$e->getMessage();
            $response["data"] = 0;
        }
        return $response;
    }
    //-->procedimiento almacenado para insertar
    
public function insertProcedure($columnsArray) { 
       ksort($columnsArray);
        try{
            $a = array();
            $c = "";
            $v = "";
            foreach ($columnsArray as $key => $value) {
                $c .= $key. ", ";
                $v .= "'".$value. "',";
                $a[":".$key] = $value;
            }
            $c = rtrim($c,', ');
            $v = rtrim($v,', ');
            $stmt =  $this->conn->prepare("CALL PA_InsertaCotizacion($v)");
            $stmt->execute();
            $affected_rows = $stmt->rowCount();
            $lastInsertId = $this->conn->lastInsertId();
            $response["status"] = "success";
            $response["message"] = $affected_rows." row inserted into database";
            $response["data"] = $lastInsertId;
        }catch(PDOException $e){
            $response["status"] = "error";
            $response["message"] = 'Insert Failed: ' .$e->getMessage();
            $response["data"] = 0;
        }
        return $response;
    }
    
public function updateProcedure($columnsArray) { 
       ksort($columnsArray);
        try{
            $a = array();
            $c = "";
            $v = "";
            foreach ($columnsArray as $key => $value) {
                $c .= $key. ", ";
                $v .= "'".$value. "',";
                $a[":".$key] = $value;
            }
            $c = rtrim($c,', ');
            $v = rtrim($v,', ');
            $stmt =  $this->conn->prepare("CALL PA_UpdateCotizacion($v)");
            $stmt->execute();
            $affected_rows = $stmt->rowCount();
            $lastInsertId = $this->conn->lastInsertId();
            $response["status"] = "success";
            $response["message"] = $affected_rows." row inserted into database";
            $response["data"] = $lastInsertId;
        }catch(PDOException $e){
            $response["status"] = "error";
            $response["message"] = 'Insert Failed: ' .$e->getMessage();
            $response["data"] = 0;
        }
        return $response;
    }
    
public function insertid($table, $columnsArray, $idcat) {
        
        try{
            $a = array();
            $c = "";
            $v = "";
            foreach ($columnsArray as $key => $value) {
                $c .= $key. ", ";
                $v .= ":".$key. ", ";
                $a[":".$key] = $value;
            }
            $c = rtrim($c,', ');
            $v = rtrim($v,', ');
            $stmt =  $this->conn->prepare("INSERT INTO $table($c,id_categoria) VALUES($v,$idcat)");
            $stmt->execute($a);
            $affected_rows = $stmt->rowCount();
            $lastInsertId = $this->conn->lastInsertId();
            $response["status"] = "success";
            $response["message"] = $affected_rows." row inserted into database";
            $response["data"] = $lastInsertId;
        }catch(PDOException $e){
            $response["status"] = "error";
            $response["message"] = 'Insert Failed: ' .$e->getMessage();
            $response["data"] = 0;
        }
        return $response;
    }
    
public function update($table, $columnsArray, $where, $requiredColumnsArray){ 
        $this->verifyRequiredParams($columnsArray, $requiredColumnsArray);
        try{
            $a = array();
            $w = "";
            $c = "";
            foreach ($where as $key => $value) {
                $w .= " and " .$key. " = :".$key;
                $a[":".$key] = $value;
            }
            foreach ($columnsArray as $key => $value) {
                $c .= $key. " = :".$key.", ";
                $a[":".$key] = $value;
            }
                $c = rtrim($c,", ");

            $stmt =  $this->conn->prepare("UPDATE $table SET $c WHERE 1=1 ".$w);
            $stmt->execute($a);
            $affected_rows = $stmt->rowCount();
            if($affected_rows<=0){
                $response["status"] = "warning";
                $response["message"] = "No row updated";
            }else{
                $response["status"] = "success";
                $response["message"] = $affected_rows." row(s) updated in database";
            }
        }catch(PDOException $e){
            $response["status"] = "error";
            $response["message"] = "Update Failed: " .$e->getMessage();
        }
        return $response;
    }
    
public function updatemany($table, $columnsArray, $where, $requiredColumnsArray){ 
        $this->verifyRequiredParams($columnsArray, $requiredColumnsArray);
        try{
            $a = array();
            $w = "";
            $c = "";
            foreach ($where as $key => $value) {
                $w .= " and " .$key. " = :".$key;
                $a[":".$key] = $value;
            }
            foreach ($columnsArray as $key => $value) {
                $c .= $key. " = :".$key.", ";
                $a[":".$key] = $value;
            }
                $c = rtrim($c,", ");

            $stmt =  $this->conn->prepare("UPDATE $table SET $c WHERE 1=1 ".$w);
            $stmt->execute($a);
            $affected_rows = $stmt->rowCount();
            if($affected_rows<=0){
                $response["status"] = "warning";
                $response["message"] = "No row updated";
            }else{
                $response["status"] = "success";
                $response["message"] = $affected_rows." row(s) updated in database";
            }
        }catch(PDOException $e){
            $response["status"] = "error";
            $response["message"] = "Update Failed: " .$e->getMessage();
        }
        return $response;
    }
    
        
public function pricingOffUpdate($id){ 
        try{

            $stmt =  $this->conn->prepare("CALL PA_DeleteserviceCot($id)");
            $stmt->execute();
            $affected_rows = $stmt->rowCount();
            if($affected_rows<=0){
                $response["status"] = "warning";
                $response["message"] = "No row updated";
            }else{
                $response["status"] = "success";
                $response["message"] = $affected_rows." row(s) updated in database";
            }
        }catch(PDOException $e){
            $response["status"] = "error";
            $response["message"] = "Update Failed: " .$e->getMessage();
        }
        return $response;
    }
    
public function pricingUpdate($id){ 
        try{
            $stmt =  $this->conn->prepare("CALL PA_AddServiceCot($id)");
            $stmt->execute();
            $affected_rows = $stmt->rowCount();
            if($affected_rows<=0){
                $response["status"] = "warning";
                $response["message"] = "No row updated";
            }else{
                $response["status"] = "success";
                $response["message"] = $affected_rows." row(s) updated in database";
            }
        }catch(PDOException $e){
            $response["status"] = "error";
            $response["message"] = "Update Failed: " .$e->getMessage();
        }
        return $response;
    }

public function upServiceCot($id){ 
        try{
            $stmt =  $this->conn->prepare("CALL PA_UpServiceCot($id)");
            $stmt->execute();
            $affected_rows = $stmt->rowCount();
            if($affected_rows<=0){
                $response["status"] = "warning";
                $response["message"] = "No row updated";
            }else{
                $response["status"] = "success";
                $response["message"] = $affected_rows." row(s) updated in database";
            }
        }catch(PDOException $e){
            $response["status"] = "error";
            $response["message"] = "Update Failed: " .$e->getMessage();
        }
        return $response;
}
    
public function downServiceCot($id){ 
        try{
            $stmt =  $this->conn->prepare("CALL PA_DownServiceCot($id)");
            $stmt->execute();
            $affected_rows = $stmt->rowCount();
            if($affected_rows<=0){
                $response["status"] = "warning";
                $response["message"] = "No row updated";
            }else{
                $response["status"] = "success";
                $response["message"] = $affected_rows." row(s) updated in database";
            }
        }catch(PDOException $e){
            $response["status"] = "error";
            $response["message"] = "Update Failed: " .$e->getMessage();
        }
        return $response;
}
    
public function delete($table, $where){
        if(count($where)<=0){
            $response["status"] = "warning";
            $response["message"] = "Delete Failed: At least one condition is required";
        }else{
            try{
                $a = array();
                $w = "";
                foreach ($where as $key => $value) {
                    $w .= " and " .$key. " = :".$key;
                    $a[":".$key] = $value;
                }
                $stmt =  $this->conn->prepare("DELETE FROM $table WHERE 1=1 ".$w);
                $stmt->execute($a);
                $affected_rows = $stmt->rowCount();
                if($affected_rows<=0){
                    $response["status"] = "warning";
                    $response["message"] = "No row deleted";
                }else{
                    $response["status"] = "success";
                    $response["message"] = $affected_rows." row(s) deleted from database";
                }
            }catch(PDOException $e){
                $response["status"] = "error";
                $response["message"] = 'Delete Failed: ' .$e->getMessage();
            }
        }
        return $response;
    }
    
public function verifyRequiredParams($inArray, $requiredColumns) {
        $error = false;
        $errorColumns = "";
        foreach ($requiredColumns as $field) {
        // strlen($inArray->$field);
            if (!isset($inArray->$field) || strlen(trim($inArray->$field)) <= 0) {
                $error = true;
                $errorColumns .= $field . ', ';
            }
        }

        if ($error) {
            $response = array();
            $response["status"] = "error";
            $response["message"] = 'Required field(s) ' . rtrim($errorColumns, ', ') . ' is missing or empty';
            echoResponse(200, $response);
            exit;
        }
    }
 
}

?>