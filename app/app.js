var app = angular.module('masokoapp', ['ngRoute', 'ui.bootstrap', 'ngAnimate', 'toaster', 'ephox.textboxio','LocalStorageModule','ngSanitize']); //Definición de la app y sus dependencias

app.filter("removeHtml",function() {
        return function(texto) {
            return String(texto).replace(/<[^>]+>/gm,'');
        }
    })

app.config(['$routeProvider', //Proveedor de rutas "friendly URLs"
    function ($routeProvider) {
        $routeProvider.
        when('/login', {
            title: 'Login',
            templateUrl: 'partials/login.html',
            controller: 'authCtrl'     
        })
        .when('/logout', {
            title: 'Logout',
            templateUrl: 'partials/login.html',
            controller: 'logoutCtrl'
        })
        .when('/signup', {
            title: 'Signup',
            templateUrl: 'partials/signup.html',
            controller: 'authCtrl'
        })
        .when('/servicios', {
            title: 'Servicios',
            templateUrl: 'partials/services.html',
            controller: 'categCtrl'
        })
        .when('/cotizaciones', {
            title: 'Cotizaciones',
            templateUrl: 'partials/cotizacion.html',
            controller: 'cotiCtrl'
        })
        .when('/cotizacionEdit', {
            title: 'Cotización',
            templateUrl: 'partials/cotizacionEdit.html',
            controller: 'cotiEditCtrl'
        })
        .when('/pricingServices' , {
            title: 'Servicios en Cotización',
            templateUrl: 'partials/productList.html',
            controller: 'productsCotCtrl'
        })
        .when('/products' , {
            title: 'Productos',
            templateUrl: 'partials/products.html',
            controller: 'productsCtrl'
        })
        .when('/users' , {
            title: 'Usuarios',
            templateUrl: 'partials/users.html',
            controller: 'authCtrl'
        })
        .when('/', {
            title: 'Login',
            templateUrl: 'partials/login.html',
            controller: 'authCtrl',
            role: '0'
        })
        .otherwise({
            redirectTo: '/login'
        });
    }])

.run(function ($rootScope, $location, Data) {
    $rootScope.$on("$routeChangeStart", function (event, next, current) {
        $rootScope.$authenticated = false;
        document.getElementById('menu').style.display = 'none';
        Data.get('session').then(function (results) {
            if (results.uid) {
                document.getElementById('menu').style.display = 'block';
                $rootScope.authenticated = true;
                $rootScope.uid = results.uid;
                $rootScope.name = results.name;
                $rootScope.email = results.email;
            } else {
                var nextUrl = next.$$route.originalPath;
                if (nextUrl == '/signup' || nextUrl == '/login' || nextUrl == '/recoverpass') {
                
                } else {
                    $location.path("/login");
                     
                }
            }
        });
    });
});



                
                
    
                
                
                    