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
                                <a role="button" id="menuma-<?= $marca['ID'] ?>" class="marco-marca-m" ng-click="setMarca(<?= $marca['ID'] ?>,'<?= $marca['image'] ?>')">
                                    <img src="<?= $marca['image'] ?>" style="height:50px; width:70px" />
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


                <div ng-if="sresult.length == 0 " class="d-flex  align-items-center justify-content-center text-center" style="height: 35em;" role="alert">

                    <img src="wp-content/images/logo_1.png" alt="Logo" style="margin-bottom: 3%;">
                    <p class="no-search"> Seleccione una tienda y una marca para obtener el listado </p>

                </div>


                <div class="row mt-5" id="angresults" ng-if="sresult.length > 0 ">


                    <div class="col-xs-12 col-md-6 col-lg-4 col-xl-3" ng-repeat="r in sresult">
                        <div class="thumbnail card position-relative">
                            <div class="img-event">
                                <img class="group list-group-image img-fluid" ng-src="{{ r.image_url }}" alt="Producto" />
                            </div>
                            <div class="caption card-body">
                                <h4 class="group card-title inner list-group-item-heading">
                                    {{r.post_title}}
                                </h4>
                                <p class="group inner list-group-item-text">
                                    {{r.post.content}}
                                    Base en acrílico cristal calibre 5 mm, adhesivos colores corporativos, respaldo en mdf calibre 4mm y cinta de espuma. Área aviso 30 cm x 15 cm, Plotter según arte, adhesivo fotoluminiscente.
                                </p>
                                <div class="row">
                                    <div class="col-xs-12 col-md-12">
                                        <p class="lead-1">
                                            Mobiliario
                                        </p>
                                        <p class="lead mt-2">
                                            {{r.price | currency: '$'}}
                                        </p>
                                    </div>

                                </div>
                            </div>
                            <div class="product-code position-absolute top-0 start-0 p-2">
                                <p class="m-0">Código: 340006974 </p>
                            </div>
                            <div class="overlay">
                                <button class="btn btn-outline-generic" ng-click="addCart(r.ID)">Agregar a la orden</button>

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
    var productos_encontrados = <?= json_encode($productos_encontrados) ?>;
</script>