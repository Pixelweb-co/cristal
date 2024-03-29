<?php
/*
Template Name: Catalog Template
*/

get_header();

$marcas = obtener_marcas();
$tiendas = obtener_tiendas();
$categorias = obtener_categorias();

$productos_encontrados = buscar_productos('', null, null, null);


?>
<style>
    <?php include './../style.css'; ?>
</style>

<div class="wrapper">

    <div id="primary" class="content-area" ng-controller="cartSearchController">
        <main id="main" class="site-main">


            <div class="container-fluid " id="filter-area">
                <div class="row">
                
                    <div class="col-md-7 pt-4 pl-5">
                        <?php
                        foreach ($marcas as $marca) {
                            if ($marca['image'] != '') {
                        ?>
                                <a role="button" id="menuma-<?= $marca['ID'] ?>" class="marco-marca-m" ng-click="setMarca(<?= $marca['ID'] ?>,'<?= $marca['image'] ?>','<?= $marca['post_title'] ?>')">
                                    <img src="<?= $marca['image'] ?>" style="height:50px; width:70px" />
                                </a>
                        <?php
                            }
                        }
                        ?>

                    </div>
                    <div class="col-md-5">
                        <div class="dropdown filter-dropdown text-center pt-4">
                            <select class="form-control" ng-model="tiendaSeleccionada" id="sltienda">
                                <option value="">Tienda</option>
                                <?php foreach ($tiendas as $tienda) {

                                ?>
                                    <option value="<?= $tienda->metros_cuadrados ?>"><?= $tienda->post_title ?></option> 
                                <?php }

                                ?>
                            </select>
                        </div>

                        <div class="dropdown filter-dropdown text-center pt-4">
                            <select class="form-control" ng-model="categoriaSeleccionada">
                                <option value="">Categoria</option>
                                <?php foreach ($categorias as $cat) {
                                    if ($cat->name != 'Sin categorizar') {
                                ?>
                                        <option value="<?= $cat->term_id ?>"><?= $cat->name ?></option>
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
                <h3 ng-if="marcaNameSel">{{marcaNameSel}}</h3>                

                <div ng-if="sresult.length == 0 " class="d-flex  align-items-center justify-content-center text-center" style="height: 35em;" role="alert">

                    <img src="<?=site_url('wp-content/images/logo_1.png')?>" alt="Logo" style="margin-bottom: 3%;">
                    <p class="no-search"> Seleccione una tienda y una marca para obtener el listado </p>

                </div>


                <div class="row mt-5" id="angresults" ng-if="sresult.length > 0 ">


                    <div class="col-xs-12 col-md-6 col-lg-4 col-xl-3" ng-repeat="r in sresult">
                        <div class="thumbnail card position-relative">
                            <div class="img-event position-relative">
                                <img class="group list-group-image img-fluid" ng-src="{{ r.image_url }}" alt="Producto" />
                                <div class="overlay">
                                    <p class="text-justify p-2 text-white">{{r.post_content}} </p>
                                </div>
                            </div>
                            <div class="caption card-body">
                                <div class="text-item-block">Código:  {{r.sku}}</div>
                                <div class="text-item-block">{{r.post_title}}</div>
                                <div class="text-item-block">
                                    Catégoria:  
                                    <span class="lead-1"  ng-repeat="c in r.categorias">
                                            {{c.name}}
                                    </span>
                                </div>
                                <div class="d-flex text-item-block">
                                    
                                <div class="w-50">
                                Qty: <input type="number" ng-class="{'qtyMinicartInput item-id-': r.ID}"  ng-value="1" value="1">
                                </div>
                                <div class="w-50">

                                {{r.price | currency: '$'}}
                                </div>
                            
                                </div>
                                <div class="text-center text-item-block">
                                    <hr>    
                                    <button class="btn w-80 btn-outline-generic" ng-click="addCart(r.ID,$event)">Agregar a la orden</button>
                                </div>
                                
                                


                            </div>
                            
                        </div>
                    </div>


                </div>


            </div>

        </main><!-- .site-main -->
    </div><!-- .content-area -->

<?php include 'modalRelated.php'?>

</div><!-- .container -->

<?php get_footer(); ?>
<script>
    var productos_encontrados = <?= json_encode($productos_encontrados) ?>;
</script>