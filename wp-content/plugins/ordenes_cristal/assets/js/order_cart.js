

function closeModal() {
    console.log('closeModal',$('#modalSearchProducts'))
    $('#modalSearchProducts').modal('hide');

    }

  
var app = angular.module("shoppingCart", []);
   
    app.controller('cartSearchController', function($scope, $http) {
        console.log('controllerSearch')
        $scope.marcaSeleccionada = null
        $scope.categoriaSeleccionada = null
        $scope.nombreProducto = null
        $scope.tiendaSeleccionada = null
        $scope.sresult = []

    
    
        $scope.setMarca = (id) =>{
            console.log('setMarca', id)
            $scope.marcaSeleccionada = id
            console.log('setsel', id)
         jQuery("#filter-area .marco-marca-m").removeClass('selected');
         jQuery("#menuma-"+id).addClass('selected');
           
        }

        $scope.buscarProductos = function(){

            jQuery("#primary .cart-loader").show();

            var parametros = {};
            if($scope.marcaSeleccionada) {
                parametros.marca = $scope.marcaSeleccionada;
            }
            if($scope.categoriaSeleccionada) {
                parametros.categoria = $scope.categoriaSeleccionada;
            }
            if($scope.nombreProducto) {
                parametros.nombre = $scope.nombreProducto;
            }
            if($scope.tiendaSeleccionada) {
                parametros.tienda = $scope.tiendaSeleccionada;
            }
            console.log("parametros",parametros);
            // Lógica para realizar la búsqueda de productos con los parámetros seleccionados
            $http({
                method : "POST",
                url : base_url+'/wp-json/ordenes_cristal/v1/buscar_productos',
                params: parametros,
                headers:{'X-WP-Nonce' : nonce},
            }).then(function (response) {
                // Manejar la respuesta de la búsqueda de productos
                console.log("Tienda ",response.data.tienda)
                $scope.sresult = response.data.productos;
                $scope.smarca = response.data.marca;
                $scope.stienda = response.data.tienda;
                jQuery("#primary .cart-loader").hide();
            });
        }        

        $scope.addCart = (id_item,newProduct=false) => {
            
            if(!newProduct){
            var product_add = $scope.sresult.find(p=>p.ID === id_item);
            }else{
            var product_add = {
                post_title:'',
                cnt:0,
                categorias:[],
                price:0,
                marca:[],
                post_content:'',
                subtotal:0,
                sku:'',
                ID:74800
            }    
            }
            
            console.log("padd",product_add);

            // Lógica para realizar la búsqueda de productos con los parámetros seleccionados
            // $http({
            //     method : "POST",
            //     url : '<?=get_site_url()?>/wp-json/ordenes_cristal/v1/add_to_cart',
            //     params: product_add,
            //     headers:{'X-WP-Nonce' : nonce},
            // }).then(function (response) {
            //     // Manejar la respuesta de la búsqueda de productos
            //    // $scope.sresult = response.data;
            //     console.log("result",response);
            //     $scope.loadData()
            //    
           
            // });


            if(!localStorage.getItem('OrderCart')){
                console.log("no existe localstorass")

                localStorage.setItem('OrderCart',JSON.stringify([{...product_add,cnt:1}]))

            }else{
                
                
                var data = JSON.parse(localStorage.getItem('OrderCart'));
                console.log("carrito",data)
                
                var found = data.find(i=>i.ID==product_add.ID)
                
                            if(!found){
                                
                                console.log("no found",found)
               
                                data.push({...product_add,cnt:1,subtotal:product_add.price})    
                                
                                localStorage.setItem('OrderCart',JSON.stringify(data));
                            
                            }else{
                                    
                                
                                console.log("founddd ",found)
                            


                                found.cnt = found.cnt + 1
                                found.subtotal = found.price * found.cnt
                                
                                var new_data = data.filter(i=>i.ID != product_add.ID);
                                new_data.push(found);
                                console.log("nwdta",new_data);
                            
                                localStorage.setItem('OrderCart',JSON.stringify(new_data));
                                
                                console.log("actr localstorass",new_data)
                                
                            }    
            }

           showMiniCart()
           
        }
    })




app.controller('miniCartController', function($scope, $http) {

    console.log('miniCartController',$scope)

    
    
    $scope.totalize = ()=>{
        console.log("totalize")
        $scope.total_order = 0
        var data = JSON.parse(localStorage.getItem('OrderCart'));
        if(!data){
            return false
        }

        console.log("citems_t" ,data)


        data.map((item,index)=>{
            
            $scope.total_order += parseInt(item.subtotal)

        })

    }

    $scope.calculate = () => {
        // Objeto para almacenar los totales por categoría
        var totalsByCategory = {};
    
        // Obtener los datos del carrito del almacenamiento local
        var cartData = JSON.parse(localStorage.getItem('OrderCart'));
    
        // Verificar si hay datos en el carrito
        if (!cartData || cartData.length === 0) {
            return [];
        }
       var id_C = 0
        // Calcular los totales por categoría
        cartData.forEach(item => {
            // Obtener las categorías asociadas al producto
            var categories = item.categorias;
    
            // Verificar si el producto tiene categorías asociadas
            if (categories && categories.length > 0) {
                categories.forEach(category => {
                    // Obtener el nombre de la categoría
                    var categoryName = category.name;
                    if(categoryName != 'Sin categorizar'){
                     id_C = category.ID;
                    // Verificar si ya hay un total para esta categoría
                    if (!totalsByCategory[categoryName]) {
                        totalsByCategory[categoryName] = 0;
                    }
    
                    // Incrementar el total de la categoría con el subtotal del producto
                    totalsByCategory[categoryName] += parseInt(item.subtotal);
                }
                });
            }
        });
    
        // Convertir el objeto totalsByCategory a un array de objetos
        var result = [];
        Object.keys(totalsByCategory).forEach(categoryName => {
            result.push({
                ID: id_C,
                title: categoryName, // Usando el nombre de la categoría como título
                total_cat: parseInt(totalsByCategory[categoryName])
            });
        });
        console.log("res por cat",result);
        return result;
    };

    $scope.loadData = function() {
          
        $scope.items = localStorage.getItem('OrderCart') != null ? JSON.parse(localStorage.getItem('OrderCart')) : []
        
        console.log("items",$scope.items);
        
        $scope.totalize()
        $scope.stats = $scope.calculate()
        
    
    }

    $scope.loadData()
})

        
        
        app.controller('cartController', function($scope, $http) {
            
          $scope.total_order = 0 
          $file_order_upload = null 

            // Obtener las tiendas desde el endpoint
            $http.get(base_url+'/wp-json/ordenes_cristal/v1/obtener_tiendas')
                    .then(function(response) {
                        // Asignar las tiendas obtenidas a $scope.tiendas
                        console.log(response);
                        $scope.tiendas = response.data;
                    })
                    .catch(function(error) {
                        console.error('Error al obtener las tiendas:', error);
                    });

                // Función para filtrar las tiendas según la opción seleccionada en el select
                $scope.filtrarTiendas = function() {
                    // Realizar alguna acción con la tienda seleccionada (por ejemplo, realizar otra llamada al servidor)
                    console.log('Tienda seleccionada:', $scope.filtroTienda);
                };

                $scope.removeItem = function(id) {
                    // Encuentra el índice del elemento con el ID especificado
                    var index = $scope.items.findIndex(function(item) {
                        return item.ID === id;
                    });
                    // Si se encontró el elemento, elimínalo del arreglo
                    if (index !== -1) {
                        $scope.items.splice(index, 1);
                        // Actualiza localStorage eliminando el elemento correspondiente
                        var cartData = JSON.parse(localStorage.getItem('OrderCart'));
                        if (cartData) {
                            var updatedCartData = cartData.filter(function(item) {
                                return item.ID !== id;
                            });
                            localStorage.setItem('OrderCart', JSON.stringify(updatedCartData));
                        }
                    }
                    // Recalcula el total del pedido
                    $scope.totalize();
                };
                                      

          $scope.loadData = function() {
          
            $scope.items = localStorage.getItem('OrderCart') != null ? JSON.parse(localStorage.getItem('OrderCart')) : []
            
            
            $scope.totalize()
            $scope.stats = $scope.calculate()
            
        
        }

        $scope.totalize = ()=>{
            console.log("totalize")
            $scope.total_order = 0
            var data = JSON.parse(localStorage.getItem('OrderCart'));
            if(!data){
                return false
            }

            console.log("citems_t" ,data)


            data.map((item,index)=>{
                
                $scope.total_order += parseInt(item.subtotal)

            })

        }

        $scope.calculate = () => {
            // Objeto para almacenar los totales por categoría
            var totalsByCategory = {};
        
            // Obtener los datos del carrito del almacenamiento local
            var cartData = JSON.parse(localStorage.getItem('OrderCart'));
        
            // Verificar si hay datos en el carrito
            if (!cartData || cartData.length === 0) {
                return [];
            }
           var id_C = 0
            // Calcular los totales por categoría
            cartData.forEach(item => {
                // Obtener las categorías asociadas al producto
                var categories = item.categorias;
        
                // Verificar si el producto tiene categorías asociadas
                if (categories && categories.length > 0) {
                    categories.forEach(category => {
                        // Obtener el nombre de la categoría
                        var categoryName = category.name;
                        if(categoryName != 'Sin categorizar'){
                         id_C = category.ID;
                        // Verificar si ya hay un total para esta categoría
                        if (!totalsByCategory[categoryName]) {
                            totalsByCategory[categoryName] = 0;
                        }
        
                        // Incrementar el total de la categoría con el subtotal del producto
                        totalsByCategory[categoryName] += item.subtotal;
                    
                    }
                    });
                }
            });
        
            // Convertir el objeto totalsByCategory a un array de objetos
            var result = [];
            Object.keys(totalsByCategory).forEach(categoryName => {
                result.push({
                    ID: id_C,
                    title: categoryName, // Usando el nombre de la categoría como título
                    total_cat: totalsByCategory[categoryName]
                });
            });
            console.log("res por cat",result);
            return result;
        };
        

        $scope.addCart = (id_item) => {
            var product_add = $scope.sresult.find(p=>p.ID === id_item);
            console.log("padd",product_add);

            // Lógica para realizar la búsqueda de productos con los parámetros seleccionados
            // $http({
            //     method : "POST",
            //     url : '<?=get_site_url()?>/wp-json/ordenes_cristal/v1/add_to_cart',
            //     params: product_add,
            //     headers:{'X-WP-Nonce' : nonce},
            // }).then(function (response) {
            //     // Manejar la respuesta de la búsqueda de productos
            //    // $scope.sresult = response.data;
            //     console.log("result",response);
            //     $scope.loadData()
            //    
           
            // });


            if(!localStorage.getItem('OrderCart')){
                console.log("no existe localstorass")

                localStorage.setItem('OrderCart',JSON.stringify([{...product_add,cnt:1}]))

            }else{
                
                
                var data = JSON.parse(localStorage.getItem('OrderCart'));
                console.log("carrito",data)
                
                var found = data.find(i=>i.ID==product_add.ID)
                
                            if(!found){
                                
                                console.log("no found",found)
               
                                data.push({...product_add,cnt:1,subtotal:product_add.price})    
                                
                                localStorage.setItem('OrderCart',JSON.stringify(data));
                            
                            }else{
                                    
                                
                                console.log("founddd ",found)
                            


                                found.cnt = found.cnt + 1
                                found.subtotal = found.price * found.cnt
                                
                                var new_data = data.filter(i=>i.ID != product_add.ID);
                                new_data.push(found);
                                console.log("nwdta",new_data);
                            
                                localStorage.setItem('OrderCart',JSON.stringify(new_data));
                                
                                console.log("actr localstorass",new_data)
                                
                            }    
            }

            $scope.loadData();

           closeModal();
        }



        $scope.setQty = (action,id_item)=>{
             console.log("id_item",id_item);   
            var data = JSON.parse(localStorage.getItem('OrderCart'));
            var found = data.find(i=>i.ID==id_item)
            

            if(action=='add'){
                found.cnt = found.cnt + 1
  
            }else {
                found.cnt = found.cnt - 1

            }
            
            
            if(found.cnt <= 0){
                $scope.removeItem(id_item)
                return false
            }

            found.subtotal = parseInt(found.price) * parseInt(found.cnt)
            var new_data = data.filter(i=>i.ID != id_item);
            new_data.push(found);
            console.log("nwdta_rp",new_data);
                            
            localStorage.setItem('OrderCart',JSON.stringify(new_data));
                                
            console.log("update qty",new_data)
            $scope.items = new_data;
            $scope.loadData();
    
        }


        $scope.marcaSeleccionada = ""
        $scope.categoriaSeleccionada = ""
        $scope.nombreProducto = ""
        $scope.tiendaSeleccionada = ""

        $scope.buscarProductos = function(){
            var parametros = {};
            if($scope.marcaSeleccionada) {
                parametros.marca = $scope.marcaSeleccionada;
            }
            if($scope.categoriaSeleccionada) {
                parametros.categoria = $scope.categoriaSeleccionada;
            }
            if($scope.nombreProducto) {
                parametros.nombre = $scope.nombreProducto;
            }
            if($scope.tiendaSeleccionada) {
                parametros.tienda = $scope.tiendaSeleccionada;
            }

            // Lógica para realizar la búsqueda de productos con los parámetros seleccionados
            $http({
                method : "POST",
                url : base_url+'/wp-json/ordenes_cristal/v1/buscar_productos',
                params: parametros,
                headers:{'X-WP-Nonce' : nonce},
            }).then(function (response) {
                // Manejar la respuesta de la búsqueda de productos
                console.log("Tienda ",response.data.tienda)
                $scope.sresult = response.data.productos;
                $scope.smarca = response.data.marca;
                $scope.stienda = response.data.tienda;
            });
        }        

        $scope.loadData()
        
        
        
});


    function showMiniCart() {
        // Esperar a que AngularJS inicialice el elemento MiniCart
        angular.element(document).ready(function() {
            // Obtener el alcance de AngularJS del elemento MiniCart
            var scope = angular.element(document.getElementById("MiniCart")).scope();
    
            // Aplicar los cambios en el alcance
            scope.$apply(function() {
                // Llamar a la función loadData en el controlador correspondiente para actualizar los datos del mini carrito
                scope.loadData();
            });
        });
    
        // Mostrar el mini carrito
        jQuery('#cartResume').fadeIn();
        jQuery("#cartResume div.card").animate({
            width: '25%',
            left:'75%'
        });
    }
    

function hideMiniCart() {
    
    jQuery('#cartResume').fadeOut();
    jQuery("#cartResume div.card").animate({
        width: '0%',
        left:'100%'
    });
}


jQuery(document).ready(function($) {

    $(document).on("click","#cartController .obs-toggle", function () {
   
      if($(this).find('i').hasClass('fa-caret-up')){
        $(this).find('i').removeClass('fa-caret-up').addClass('fa-caret-down');
        console.log($(this).closest('tr').find('.observ_field'))
        $(this).closest('tr').find('.observ-field').show();

        
      }else{
        $(this).find('i').removeClass('fa-caret-down').addClass('fa-caret-up');
        $(this).closest('tr').find('.observ-field').hide();
      }
    
    })        

});


function readURL(input) {
        if (input.files && input.files[0]) {
      
          var reader = new FileReader();
      
          reader.onload = function(e) {
            jQuery('.image-upload-wrap').hide();
      
            jQuery('.file-upload-image').attr('src', e.target.result);
            jQuery('.file-upload-content').show();
      
            jQuery('.image-title').html(input.files[0].name);
          };
      
          angular.element(document).ready(function() {
        
             // Actualizar el valor de $scope.file_order_upload en el controlador de AngularJS
          var scope_cart = angular.element(document.getElementById("cartController")).scope()
            console.log(scope_cart)
            scope_cart.$apply(()=> {
                scope_cart.file_order_upload = input.files[0];
                console.log(scope_cart.file_order_upload)
            })
        });


         
          reader.readAsDataURL(input.files[0]);
      
        } else {
          removeUpload();
        }
      }
      
      function removeUpload() {
        jQuery('.file-upload-input').replaceWith(jQuery('.file-upload-input').clone());
        jQuery('.file-upload-content').hide();
        jQuery('.image-upload-wrap').show();
      }
      jQuery('.image-upload-wrap').bind('dragover', function () {
              jQuery('.image-upload-wrap').addClass('image-dropping');
          });
          jQuery('.image-upload-wrap').bind('dragleave', function () {
              jQuery('.image-upload-wrap').removeClass('image-dropping');
      });

