app.controller('cotiCtrl', function ($scope, $modal, $filter, Data, $sce) {
    $scope.cotizacion = {};
    
    Data.get('cotizaciones').then ( function (data){
        $scope.data = data;
        $scope.cotizaciones = data.data;
        for (i in $scope.cotizaciones) { 
            if($scope.cotizaciones[i].estatus === 'ENTREGADA POR MAIL Y EN ESPERA') {
                $sce.trustAsHtml($scope.cotizaciones[i].enlace = "<a target='_blank' href='/cotizadormasoko/cotizaciones/cotizacion_" +
                $scope.cotizaciones[i].id_cotizacion +".html?id=00'>" +
                $scope.cotizaciones[i].id_cotizacion +
                "</a>");

            } 
            else{
                $scope.cotizaciones[i].enlace = "";
            }
        }
    });
    $scope.deleteCotizacion = function(cotizacion){
        if(confirm("¿Realmente desea borrar la cotización?")){
            Data.delete("cotizaciones/"+cotizacion.id_cotizacion).then(function(result){
                $scope.cotizaciones = _.without($scope.cotizaciones, _.findWhere($scope.cotizaciones, {id_cotizacion:cotizacion.id_cotizacion}));
            });           
        }
    };  
    $scope.addServicesCot = function (cotizacion) {
        if(confirm("¿Quiere agregar los servicios para cotizar?")){
                Data.put('addServicesCot/'+cotizacion.id_cotizacion).then(function (result) {
                    if(result.status != 'error'){
                        $scope.products = {};
                        Data.get('pricingServices').then(function(data){
                            $scope.products = data.data;
                        });   
                    }else{
                        console.log(result);
                    }
                });
        };
    }
    $scope.open = function (p,size) {
        if($scope.cotizacion.subtotal == 0){
            alert("Actualiza el subtotal o agrega servicios a la cotizacion");
        }else{
        var modalInstance = $modal.open({
          templateUrl: 'partials/cotizacionEdit.html',
          controller: 'cotizacionEditCtrl',
          size: size,
          resolve: {
            item: function () {
              return p;
            }
          }
        });
        modalInstance.result.then(function(x){
            if(x == "insert"){
                $scope.cotizaciones = {};
                Data.get('cotizaciones').then ( function (data){
                $scope.data = data;
                $scope.cotizaciones = data.data;
                for (i in $scope.cotizaciones) { 
                    if($scope.cotizaciones[i].estatus === 'ENTREGADA POR MAIL Y EN ESPERA') {
                        $sce.trustAsHtml($scope.cotizaciones[i].enlace = "<a target='_blank' href='../../cotizaciones/cotizacion_" +
                        $scope.cotizaciones[i].id_cotizacion +".html?id=00'>" +
                        $scope.cotizaciones[i].id_cotizacion +
                        "</a>");

                    } 
                    else{
                        $scope.cotizaciones[i].enlace = "";
                    }
                }
                });
                $scope.products = {};
                Data.get('pricingServices').then(function(data){
                $scope.products = data.data;
                });
                $scope.cotizacion.subtotal = 0;
                $scope.cotizacion.iva = 0;
                $scope.cotizacion.total = 0;
            }else if(x == "update"){
                $scope.cotizaciones = {};
                Data.get('cotizaciones').then ( function (data){
                $scope.data = data;
                $scope.cotizaciones = data.data;
                for (i in $scope.cotizaciones) { 
                    if($scope.cotizaciones[i].estatus === 'ENTREGADA POR MAIL Y EN ESPERA') {
                        $sce.trustAsHtml($scope.cotizaciones[i].enlace = "<a target='_blank' href='../../cotizaciones/cotizacion_" +
                        $scope.cotizaciones[i].id_cotizacion +".html?id=00'>" +
                        $scope.cotizaciones[i].id_cotizacion +
                        "</a>");

                    } 
                    else{
                        $scope.cotizaciones[i].enlace = "";
                    }
                }
                });
                $scope.products = {};
                Data.get('pricingServices').then(function(data){
                $scope.products = data.data;
                $scope.cotizacion.subtotal = 0;
                $scope.cotizacion.iva = 0;
                $scope.cotizacion.total = 0;
                });
            }else if(x == "cerrar"){
                
            }
        });
        }     
    };  
 $scope.columns = [ {text:"Ver",predicate:"ver",sortable:true},
                    {text:"ID",predicate:"id",sortable:true,dataType:"number"},
                    {text:"Estatus",predicate:"estatus",sortable:true},
                    {text:"Tema",predicate:"tema",sortable:true},
                    {text:"Cliente",predicate:"cliente",sortable:true},
                    {text:"Email",predicate:"email",sortable:true},
                    {text:"Fecha",predicate:"fecha",sortable:true},
                    {text:"Opciones",predicate:"Opciones",sortable:true}
                ]; 
    $scope.openPricing = function () {
        $location.path('cotizaciones');
    };
//empieza product list
    $scope.product = {};
    Data.get('pricingServices').then(function(data){
        $scope.products = data.data;
    });
    
    $scope.changeProductStatus = function(product){
    product.tipoprecio = (product.tipoprecio=="PUBLICO" ? "PAQUETE" : "PUBLICO");
    Data.put("products/"+product.id,{tipoprecio:product.tipoprecio});
    };
        Data.get('pricingServicesSubTotal').then(function(data){
            $scope.arreglo = data.data;
            $scope.cotizacion.subtotal = $scope.arreglo[0].total;
            $scope.cotizacion.iva = parseFloat($scope.cotizacion.subtotal)*0.16;
            $scope.cotizacion.total = parseFloat($scope.cotizacion.subtotal)+(parseFloat($scope.cotizacion.iva)); 
        });
    
    $scope.updatesubtotal = function(){
            Data.get('pricingServicesSubTotal').then(function(data){
            $scope.arreglo = data.data;
            $scope.cotizacion.subtotal = $scope.arreglo[0].total;
            $scope.cotizacion.iva = parseFloat($scope.cotizacion.subtotal)*0.16;
            $scope.cotizacion.total = parseFloat($scope.cotizacion.subtotal)+(parseFloat($scope.cotizacion.iva)); 
        });
        }
    
    $scope.columns2 = [
        {text:"",predicate:"id",sortable:true},
        {text:"Nombre",predicate:"nombre",sortable:true},
        {text:"Descripción",predicate:"descripcion",sortable:true},
        {text:"Precio Publico",predicate:"precioPublico",sortable:true,dataType:"number"},
        {text:"Precio Paquete",predicate:"precioEspecial",sortable:true,dataType:"number"},
        {text:"Precio",predicate:"precio",sortable:true},
        {text:"Quitar",predicate:"Quitar",sortable:true}        
    ];
    $scope.pricingOffProduct = function (product) {
        if(confirm("¿Quiere quitar de la cotización a éste servicio?")){
                    Data.delete('pricingOffServices/'+product.id).then(function (result) {
                        if(result.status != 'error'){                    
                                $scope.product = {};
                                Data.get('pricingServices').then(function(data){
                                $scope.products = data.data;
                                });
                            
                        }else{
                            console.log(result);
                        }
                    });
            };
        }
    $scope.upServiceCot = function (product) {
        Data.put('upServiceCot/'+product.id).then(function (result) {
            if(result.status != 'error'){
                    $scope.product = {};
                    Data.get('pricingServices').then(function(data){
                    $scope.products = data.data;
                    }); 
            }else{
                console.log(result);
            }
        });        
    }
    $scope.downServiceCot = function (product) {
        Data.put('downServiceCot/'+product.id).then(function (result) {
            if(result.status != 'error'){
                    $scope.product = {};
                    Data.get('pricingServices').then(function(data){
                    $scope.products = data.data;
                    }); 
            }else{
                console.log(result);
            }
        });        
    }
    //termina product list
    
});


app.controller('cotizacionEditCtrl', function ($scope, $modalInstance, item, Data) {
    
    $scope.cotizacion = angular.copy(item);
    delete $scope.cotizacion.enlace;
    $scope.cotizacionctrl = {};
    $scope.cotizacionctrl.csiva = 'CON_IVA';
    Data.get('pricingServicesSubTotal').then(function(data){
            $scope.arreglo = data.data;
            $scope.cotizacion.subtotal = $scope.arreglo[0].total;
            $scope.cotizacion.iva = parseFloat($scope.cotizacion.subtotal)*0.16;
            $scope.cotizacion.total = parseFloat($scope.cotizacion.subtotal)+(parseFloat($scope.cotizacion.iva)); 
        });
    $scope.cotizacion.anticipo = ($scope.cotizacion.anticipo === undefined) ? 0 : $scope.cotizacion.anticipo;
    $scope.cotizacion.descuento = ($scope.cotizacion.descuento === undefined) ? 0 : $scope.cotizacion.descuento;
    $scope.calculo= function(){       
    $scope.changeiva = function(){
        if($scope.cotizacion.iva != 0){
            $scope.cotizacionctrl.csiva = "CON_IVA"; 
            $scope.cotizacion.iva = 0;
            $scope.cotizacion.total = parseFloat($scope.cotizacion.subtotal)+(parseFloat($scope.cotizacion.iva)); 
        }else{
            $scope.cotizacionctrl.csiva = "SIN_IVA";
            $scope.cotizacion.iva = parseFloat($scope.cotizacion.subtotal)*0.16;
            $scope.cotizacion.total = parseFloat($scope.cotizacion.subtotal)+(parseFloat($scope.cotizacion.iva));
        }
    }; 
    $scope.cotizacion.saldo=parseFloat($scope.cotizacion.total)-(
        parseFloat($scope.cotizacion.anticipo)+parseFloat($scope.cotizacion.descuento));
    }
    $scope.$watch($scope.calculo);
        $scope.cancel = function () {
            $modalInstance.dismiss('Close');
        };
        $scope.title = (item.id_cotizacion === undefined) ? 'Agregar Cotización' : 'Editar Cotización ID: ';
        $scope.buttonTextOne = (item.id_cotizacion === undefined) ? 'Agregar Cotización' : 'Actualizar Cotización';
        $scope.buttonTextTwo = (item.id_cotizacion === undefined) ? 'Agregar y Enviar Cotización' : 'Actualizar y Enviar Cotización';
        var original = item;
        $scope.isClean = function() {
            return angular.equals(original, $scope.cotizacion);
        }
        $scope.saveCotizacion = function (cotizacion) {
            $scope.cotizacion.phone = ($scope.cotizacion.phone === undefined) ? '0' : $scope.cotizacion.phone;
            $scope.cotizacion.cellphone = ($scope.cotizacion.cellphone === undefined) ? '0' : $scope.cotizacion.cellphone;  
            $scope.cotizacion.comentario = ($scope.cotizacion.comentario === undefined) ? '-' : $scope.cotizacion.comentario; 
            $scope.cotizacion.formadepago = ($scope.cotizacion.formadepago === undefined) ? '-' : $scope.cotizacion.formadepago;
            $scope.cotizacion.cuatrodigitos = ($scope.cotizacion.cuatrodigitos === undefined) ? '0' : $scope.cotizacion.cuatrodigitos;
            
            if(cotizacion.id_cotizacion > 0){
                Data.put('cotizaciones', cotizacion).then(function (result) {
                    console.log(result.status);
                        console.log(result.message);
                    if(result.status != ''){
                        //var x = angular.copy(cotizacion);
                        var x = 'update';  
                    $modalInstance.close(x);
                    
                    }else{
                        console.log(result);
                    }
                });
            }else{
                
                Data.post('cotizaciones', cotizacion).then(function (result) {
                    if(result.status != ''){
                        //var x = angular.copy(cotizacion);
                        var x = 'insert';
                        //x.id_cotizacion = result.data;
                        $modalInstance.close(x);
                    }else{
                        console.log(result);
                    }
                });
            }
        };
    $scope.saveandSendCotizacion = function (cotizacion) {
            $scope.cotizacion.phone = ($scope.cotizacion.phone === undefined) ? '0' : $scope.cotizacion.phone;
            $scope.cotizacion.cellphone = ($scope.cotizacion.cellphone === undefined) ? '0' : $scope.cotizacion.cellphone;  
            $scope.cotizacion.comentario = ($scope.cotizacion.comentario === undefined) ? '-' : $scope.cotizacion.comentario; 
            $scope.cotizacion.formadepago = ($scope.cotizacion.formadepago === undefined) ? '-' : $scope.cotizacion.formadepago;
            $scope.cotizacion.cuatrodigitos = ($scope.cotizacion.cuatrodigitos === undefined) ? '0' : $scope.cotizacion.cuatrodigitos;
            $scope.cotizacion.estatus = 'ENTREGADA POR MAIL Y EN ESPERA';
            if(cotizacion.id_cotizacion > 0){
                Data.put('cotizaciones', cotizacion).then(function (result) {

                    if(result.status != ''){
                        //var x = angular.copy(cotizacion);
                        var x = 'update';  
                    $modalInstance.close(x);
                    
                    }else{
                        console.log(result);
                    }
                });
                Data.put('mail/'+cotizacion.id_cotizacion).then(function (resultado) {
                      console.log(resultado.status);
                      console.log(resultado.message);
                      console.log(resultado.data);
                     var url =resultado.data;
                      window.open(url,'_blank','screen.height,screen.width,left=200,top=200,scrollbars=yes');
                    });
            }else{
                Data.post('cotizaciones', cotizacion).then(function (result) {
                    if(result.status != ''){
                        //var x = angular.copy(cotizacion);
                        var x = 'insert';
                        //x.id_cotizacion = result.data;
                        $modalInstance.close(x);
                    Data.put('newmail').then(function (resultado) {
                      console.log(resultado.status);
                      console.log(resultado.message);
                      console.log(resultado.data);
                        var url = resultado.data;
                      window.open(url,'_blank','screen.height,screen.width,left=200,top=200,scrollbars=yes');
                    });
                    }else{
                        console.log(result);
                    }
                });
            }
        };
});


