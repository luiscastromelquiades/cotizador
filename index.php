<!--
@author: Luis Enrique Castro Melquiades
@email: luiscastromelquiades@gmail.com
@application: Cotizador Masoko
@description: Aplicación web que realiza cotizaciones de los servicios que ofrece Masoko.
@technologies:
    ->AngularJS
    ->PHP
    ->HTML5
    ->MySQL
    ->SLIM PHP
    ->Bootstrap
    ->Toaster
    ->TextBoxOI
    ->Pikaday
    
toDevelopers: Para agregar, modificar o quitar funcionalidad a la aplicación debes saber->
    ->MVW (Model-View-Whatever do you need)
    ->Estructura y consumo de una RESTful API
    ->Store Procedures
    ->Single page applications
    ->MySQL PDO
-->
<!DOCTYPE html>
<html lang="es" ng-app="masokoapp">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cotizador Masoko</title>
    <link href="./css/bootstrap.min.css" rel="stylesheet" />
    <link href="./css/main.css" type="text/css" rel="stylesheet" />
    <link href="./css/toaster.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/font-awesome.min.css" type="text/css" />
    <link rel=”Shortcut Icon” href=”favicon.ico” type=”image/x-icon” />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
</head>
<body ng-cloak=""> 
        <div>
        <nav class="navbar navbar-default navbar-fixed-top menu" id="menu" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Cambiar Navegación</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="#/cotizaciones" ><i class="ace-icon fa fa-home"></i>&nbsp;INICIO</a></li>
                    <li><a href="#/servicios" ><i class="ace-icon fa fa-book"></i>&nbsp;SERVICIOS</a></li>
                    <li><a href="#/pricingServices" ><i class="ace-icon fa fa-tasks"></i>&nbsp;SERVICIOS SELECCIONADOS</a></li>
                    <li><a href="#/users" ><i class="ace-icon fa fa-users"></i>&nbsp;USUARIOS</a></li> 
                </ul>
                <ul class="nav navbar-nav navbar-right" ng-controller="authCtrl">
                    <li><a ng-click="logout()"><i class="ace-icon fa fa-sign-out"></i>&nbsp;Salir</a></li>
                </ul>
            </div>
        </nav>
        </div>
<div class="container animated" style="margin-top:50px;">
        <div data-ng-view="" id="ng-view" class=""></div>
    </div>
</body>
<toaster-container toaster-options="{'time-out': 3000}"></toaster-container>
      <!-- Libs -->  
    <script src="js/angular.min.js"></script>    
    <script src="js/angular-route.min.js"></script>
    <script src="js/angular-animate.min.js"></script>
    <script src="js/toaster.js"></script> 
      
    <!-- Some Bootstrap Helper Libraries -->
    <script src="js/jquery.min.js"></script> 
    <script src="js/ui-bootstrap-tpls-0.11.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    
    <script src="js/angular-local-storage.js"></script>
    <script src="js/angular-sanitize.min.js"></script>
    <script src="js/textboxoi/textboxio.js"></script>
    <script src="js/underscore.min.js"></script>
    <script src="js/textboxoi/directives/tbio.js"></script>
    <script src="js/textboxoi/factories/tbioConfigFactory.js"></script>
    <script src="js/textboxoi/factories/tbioValidationsFactory.js"></script>
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
    
    <script src="app/app.js"></script>
    <script src="app/data.js"></script>
    <script src="app/directives.js"></script>
    <script src="app/authCtrl.js"></script>
    <script src="app/productsCtrl.js"></script>
    <script src="app/cotiCtrl.js"></script>
</html>
