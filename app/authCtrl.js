app.controller('authCtrl', function ($scope, $rootScope, $routeParams, $location, $http, Data) {
    //initially set those objects to null to avoid undefined error
    $scope.login = {};
    $scope.signup = {};
    $scope.doLogin = function (customer) {
        Data.post('login', {
            customer: customer
        }).then(function (results) {
            Data.toast(results);
            if (results.status == "success") {
                $location.path('servicios');         
            }else{
                document.getElementById('menu').style.display = 'none';
            }
        });
    };
$scope.signup = {email:'', password:'',name:''};
    $scope.signUp = function (customer) {
        Data.post('signUp', {
            customer: customer
        }).then(function (results) {
            Data.toast(results);
            if (results.status == "success") {
                $location.path('login');
            }
        });
    };
    $scope.logout = function () {
        Data.get('logout').then(function (results) {
            Data.toast(results);
            $location.path('login');
        });
    }
    $scope.users = {};
    Data.get('users').then(function(data){
        $scope.users = data.data;
    });
    $scope.changeUserStatus = function(user){
    user.nivel = (user.nivel=="ADMINISTRADOR" ? "USUARIO" : "ADMINISTRADOR");
    Data.put("users/"+user.uid,{nivel:user.nivel});
    };
    $scope.deleteUser = function(user){
        if(confirm("¿Está seguro que quiere eliminar este usuario?")){
            Data.delete("users/"+user.uid).then(function(result){
                $scope.users = _.without($scope.users, _.findWhere($scope.users, {uid:user.uid}));
            });
        }
    };
    $scope.columns = [
        {text:"ID",predicate:"id",sortable:true,dataType:"number"},
        {text:"Nombre",predicate:"nombre",sortable:true},
        {text:"Email",predicate:"email",sortable:true},
        {text:"Tipo de Usuario",predicate:"tipo de usuario",sortable:true},
        {text:"Eliminar",predicate:"eliminar",sortable:true}
    ];
});
                        