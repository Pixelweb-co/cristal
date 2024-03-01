<?php
/*
Template Name: Catalog Template
*/

get_header();

$marcas = obtener_marcas();
$tiendas = obtener_tiendas();
$categorias = obtener_categorias();

$productos_encontrados = buscar_productos('',null,null,null);

?>
     

<div class="wrapper">
    
    <div id="primary" class="content-area" ng-controller="cartSearchController">
        <main id="main" class="site-main">
   

            <div class="container-fluid " id="filter-area">
                <div class="row">
                    <div class="col-md-7 pt-4 pl-5">
                     <?php
                        foreach ($marcas as $marca){
                            if($marca['image'] != ''){
                           ?> 
                           <a role="button" id="menuma-<?=$marca['ID']?>" class="marco-marca-m" ng-click="setMarca(<?=$marca['ID']?>)">
                           <img src="<?=$marca['image']?>" style="height:50px; width:70px"/>
                           </a>
                           <?php
                            }
                                }
                     ?>

                    </div>
                    <div class="col-md-5">
                    <div class="dropdown filter-dropdown text-center pt-4">
                    <select class="form-control" ng-model="tiendaSeleccionada">
                        <option value="">Tienda</option>
                                <?php foreach($tiendas as $tienda){
                                
                                    ?>
                                    <option <?=$tienda->ID?>><?=$tienda->post_title?></option>
                                <?php } 
                                
                                ?>    
                        </select>        
                    </div> 
                
                    <div class="dropdown filter-dropdown text-center pt-4">
                    <select class="form-control" ng-model="categoriaSeleccionada">
                                <option value="">Categoria</option>
                                <?php foreach($categorias as $cat){
                                    if($cat->name != 'Sin categorizar'){
                                    ?>
                                    <option value="<?=$cat->term_id?>"><?=$cat->name?></option>
                                <?php } 
                                }
                                ?>        
                            </select>
                    </div>  
                    
                    <div class="form-group text-center pt-4">

                    <button type="button" class="btn btn-outline-generic" ng-click="buscarProductos()">
                        <div class="cart-loader"></div> Buscar 
                        </button>
                    </div>

                    </div>
                </div>

            </div>
        
            <div class="container">
                

            <div ng-if="sresult.length == 0 " class="alert alert-secondary" style="margin-top: 25px;" role="alert">
                            No se enontraron resultados
                            </div>        

            
            <div class="row " id="angresults"  >
                    
                <div class="item col-xs-4 col-lg-4" ng-repeat="r in sresult">
                                <div class="thumbnail card">
                                    <div class="img-event">
                                        <img class="group list-group-image img-fluid"  ng-src="{{ r.image_url }}" alt="" />
                                </div>
                                <div class="caption card-body">
                                    <h4 class="group card-title inner list-group-item-heading">
                                        {{r.post_title}}</h4>
                                    <p class="group inner list-group-item-text">
                                        {{r.post.content}}</p>
                                    <div class="row">
                                        <div class="col-xs-12 col-md-12">
                                            <p class="lead">
                                                ${{r.price}}</p>
                                        </div>
                                        <div class="col-xs-12 col-md-12">
                                            <button class="btn btn-outline-generic" ng-click="addCart(r.ID)">Agregar a la orden</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>     

             </div> 
                             

            </div>   

        </main><!-- .site-main -->
    </div><!-- .content-area -->
</div><!-- .container -->

<?php get_footer(); ?>
<script>

var productos_encontrados = <?=json_encode($productos_encontrados)?>;                



</script>