app.controller('categCtrl', function ($scope, Data) {
    $scope.category = {};
    Data.get('servicios').then(function(data){
       $scope.servicios = data.data;
    });   
});


app.controller('productsCtrl', function ($scope, $modal, $filter, Data, $routeParams, $location) {
    $scope.product = {};
    Data.get('products/'+$routeParams.id).then(function(data){
        $scope.products = data.data;
    });
    $scope.deleteProduct = function(product){
        if(confirm("¿Esta seguro que quiere remover el servicio?")){
            Data.delete("products/"+product.id).then(function(result){
                $scope.products = _.without($scope.products, _.findWhere($scope.products, {id:product.id}));
            });
        }
    };
    $scope.open = function (p,size) {
        var modalInstance = $modal.open({
          templateUrl: 'partials/productEdit.html',
          controller: 'productEditCtrl',
          size: size,
          resolve: {
            item: function () {
              return p;
            }
          }
        });
        modalInstance.result.then(function(selectedObject) {
            if(selectedObject == "insert"){
                $scope.products = {};
                Data.get('products/'+$routeParams.id).then(function(data){
                $scope.products = data.data;
                    });
            }else if(selectedObject == "update"){
                $scope.products = {};
                Data.get('products/'+$routeParams.id).then(function(data){
                $scope.products = data.data;
                    });
            }
        });
    };

    
    
 $scope.columns = [
                    {text:"ID",predicate:"id",sortable:true,dataType:"number"},
                    {text:"Nombre",predicate:"nombre",sortable:true},
                    {text:"Descripción",predicate:"descripcion",sortable:true},
                    {text:"Precio Publico",predicate:"precioPublico",sortable:true,dataType:"number"},
                    {text:"IVA Pub",predicate:"IVAPub",sortable:true,dataType:"number"},
                    {text:"Precio Paquete",predicate:"precioEspecial",sortable:true,dataType:"number"},
                    {text:"IVA Paq",predicate:"IVAPaq",sortable:true,dataType:"number"},
                    {text:"Nota",predicate:"nota",sortable:true},
                    {text:"Opciones",predicate:"opciones",sortable:true}
                ];
$scope.priceProduct = function (product) {
    if(product.save != undefined) {
        delete product.save;
    }
    
    if(confirm("¿Quiere agregar a la cotización a éste servicio?")){
                Data.put('priceProducts/'+product.id).then(function (result) {
                    if(result.status != 'error'){
                            $scope.product = {};
                            Data.get('products/'+$routeParams.id[0]).then(function(data){
                            $scope.products = data.data;
                            });     
                    }else{
                        console.log(result);
                    }
                });        
        };
}
});


app.controller('productEditCtrl', function ($scope, $modalInstance, item, Data, $routeParams ) {
    
    
  $scope.product = angular.copy(item);
    
    $scope.calculariva = function(){
        $scope.product.IVAPub=parseFloat($scope.product.precioPublico)*(0.16);
        $scope.product.IVAPaq=parseFloat($scope.product.precioEspecial)*(0.16);
    }
    $scope.$watch($scope.calculariva);
        
        $scope.cancel = function () {
            $modalInstance.dismiss('Close');
        };
        $scope.title = (item.id > 0) ? 'Editar Servicio' : 'Agregar Servicio';
        $scope.buttonText = (item.id > 0) ? 'Actualizar Servicio' : 'Agregar Nuevo Servicio';
        var original = item;
        $scope.isClean = function() {
            return angular.equals(original, $scope.product);
        }
        $scope.saveProduct = function (product) { 
            if($scope.product.nota === undefined) {
                $scope.product.nota = '-';
            }
            $scope.product.precioEspecial = ($scope.product.precioEspecial === undefined) ? '0' : $scope.product.precioEspecial;
            $scope.product.IVAPaq = ($scope.product.IVAPaq === undefined) ? '0' : $scope.product.IVAPaq;
            if(product.id > 0){
                
                Data.put('products/'+product.id, product).then(function (result) {
                    if(result.status != 'error'){
                        var x = 'update';
                        $modalInstance.close(x);
                    }else{
                        console.log(result);
                    }
                });
            }else{              
                Data.post('products/'+$routeParams.id, product).then(function (result) {                 
                    if(result.status != 'error'){                                   
                        var x = 'insert';
                        $modalInstance.close(x);
                    }else{
                        console.log(result);
                    }
                });
            }
        };
});

app.controller('productsCotCtrl', function ($scope, $filter, Data) {
    
    
    
    $scope.product = {};
    $scope.cotizacion = {};
    
    Data.get('pricingServices').then(function(data){
        $scope.products = data.data;
    });
    
    

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
    

    
    $scope.changeProductStatus = function(product){
    product.tipoprecio = (product.tipoprecio=="PUBLICO" ? "PAQUETE" : "PUBLICO");
    Data.put("products/"+product.id,{tipoprecio:product.tipoprecio});
    };
    $scope.columns = [
        {text:"ID",predicate:"id",sortable:true,dataType:"number"},
        {text:"Nombre",predicate:"nombre",sortable:true},
        {text:"Descripción",predicate:"descripcion",sortable:true},
        {text:"Precio Publico",predicate:"precioPublico",sortable:true,dataType:"number"},
        {text:"IVA Pub",predicate:"IVAPub",sortable:true,dataType:"number"},
        {text:"Precio Paquete",predicate:"precioEspecial",sortable:true,dataType:"number"},
        {text:"IVA Paq",predicate:"IVAPaq",sortable:true,dataType:"number"},
        {text:"Precio",predicate:"precio",sortable:true}
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
    
});


