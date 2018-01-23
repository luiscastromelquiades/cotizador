function ocultar(){
            document.getElementById('menu').style.display = 'none';
        }
        function mostrar(){
            document.getElementById('menu').style.display = 'block';
        }
        window.onload = function() {
            if(window.location.href === "http://127.0.0.1:81/cotizadormasoko/#/login") {
                document.getElementById('menu').style.display = 'none';
                console.log("entro");
            }else if(window.location.href === "http://127.0.0.1:81/cotizadormasoko/#/cotizaciones"){
                document.getElementById('menu').style.display = 'block';
                console.log("entro 2");
            }else if(window.location.href === "http://127.0.0.1:81/cotizadormasoko/#"){
                document.getElementById('menu').style.display = 'none';
                console.log("entro 3");
            }
        }