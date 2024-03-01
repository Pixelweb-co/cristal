<?php
/*
Plugin Name: Cristal POS
Description: Plugin para gestionar órdenes de productos
Version: 1.0
Author: Tu Nombre
*/

// Activar el plugin
register_activation_hook( __FILE__, 'cristal_pos_activate' );

function cristal_pos_activate() {
    global $wpdb;
    $orden_table_name = $wpdb->prefix . 'orden';
    $orden_items_table_name = $wpdb->prefix . 'orden_items';

    $charset_collate = $wpdb->get_charset_collate();

    $orden_sql = "CREATE TABLE $orden_table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        fecha_orden datetime NOT NULL,
        cliente varchar(100) NOT NULL,
        totalOrden decimal(10,2) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    $orden_items_sql = "CREATE TABLE $orden_items_table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        order_id mediumint(9) NOT NULL,
        nombre varchar(100) NOT NULL,
        referencia varchar(100) NOT NULL,
        precio decimal(10,2) NOT NULL,
        peso decimal(10,2) NOT NULL,
        categoria varchar(100) NOT NULL,
        cnt int NOT NULL,
        PRIMARY KEY  (id),
        FOREIGN KEY  (order_id) REFERENCES $orden_table_name(id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $orden_sql );
    dbDelta( $orden_items_sql );
}

// Agregar página de administración
add_action('admin_menu', 'cristal_pos_admin_menu');

function cristal_pos_admin_menu() {
    add_menu_page(
        'Órdenes',
        'Órdenes',
        'manage_options',
        'cristal_pos_ordenes',
        'cristal_pos_ordenes_page',
        'dashicons-cart',
        20
    );
}

// Página de administración para mostrar órdenes
function cristal_pos_ordenes_page() {
    global $wpdb;
    $orden_table_name = $wpdb->prefix . 'orden';

    $ordenes = $wpdb->get_results("SELECT * FROM $orden_table_name");

    echo '<div class="wrap">';
    echo '<h2>Órdenes</h2>';
    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr><th>ID</th><th>Fecha de Orden</th><th>Cliente</th><th>Total de la Orden</th></tr></thead>';
    echo '<tbody>';
    foreach ($ordenes as $orden) {
        echo '<tr>';
        echo '<td>' . $orden->id . '</td>';
        echo '<td>' . $orden->fecha_orden . '</td>';
        echo '<td>' . $orden->cliente . '</td>';
        echo '<td>' . $orden->totalOrden . '</td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
    echo '</div>';
}

// Función para mostrar formulario de orden
function mostrar_formulario_orden() {
    // Aquí va el HTML del formulario de orden
    ob_start();
    ?>
  
  
  
  <!-- Aquí va tu formulario HTML -->
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>

<div class="container-fluid">
<div class="card shopping-cart">
            <div class="card-header bg-dark text-light">
                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                Hacer Pedido
                <a href="" class="btn btn-outline-info btn-sm pull-right">Buscar productos</a>

                <a href="" class="btn btn-outline-info btn-sm pull-right">Agregar nuevo producto</a>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
                    <!-- PRODUCT -->
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-2 text-center">
                                <img class="img-responsive" src="http://placehold.it/120x80" alt="prewiew" width="120" height="80">
                        </div>
                        <div class="col-12 text-sm-center col-sm-12 text-md-left col-md-6">
                            <h4 class="product-name"><strong>Nombre Item</strong></h4>
                            <h4>
                                <small>Descripcion</small>
                            </h4>
                        </div>
                        <div class="col-12 col-sm-12 text-sm-center col-md-4 text-md-right row">
                            <div class="col-3 col-sm-3 col-md-6 text-md-right" style="padding-top: 5px">
                                <h6><strong>0 <span class="text-muted">x</span></strong></h6>
                            </div>
                            <div class="col-4 col-sm-4 col-md-4">
                                <div class="quantity">
                                    <input type="button" value="+" class="plus">
                                    <input type="number" step="1" max="99" min="1" value="1" title="Qty" class="qty"
                                           size="4">
                                    <input type="button" value="-" class="minus">
                                </div>
                            </div>
                            <div class="col-2 col-sm-2 col-md-2 text-right">
                                <button type="button" class="btn btn-outline-danger btn-xs">
                                    <i class="fa fa-trash" aria-hidden="true"></i>X
                                </button>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <!-- END PRODUCT -->
<!-- PRODUCT -->
<div class="row">
                        <div class="col-12 col-sm-12 col-md-2 text-center">
                                <img class="img-responsive" src="http://placehold.it/120x80" alt="prewiew" width="120" height="80">
                        </div>
                        <div class="col-12 text-sm-center col-sm-12 text-md-left col-md-6">
                            <h4 class="product-name"><strong>Nombre Item</strong></h4>
                            <h4>
                                <small>Descripcion</small>
                            </h4>
                        </div>
                        <div class="col-12 col-sm-12 text-sm-center col-md-4 text-md-right row">
                            <div class="col-3 col-sm-3 col-md-6 text-md-right" style="padding-top: 5px">
                                <h6><strong>0 <span class="text-muted">x</span></strong></h6>
                            </div>
                            <div class="col-4 col-sm-4 col-md-4">
                                <div class="quantity">
                                    <input type="button" value="+" class="plus">
                                    <input type="number" step="1" max="99" min="1" value="1" title="Qty" class="qty"
                                           size="4">
                                    <input type="button" value="-" class="minus">
                                </div>
                            </div>
                            <div class="col-2 col-sm-2 col-md-2 text-right">
                                <button type="button" class="btn btn-outline-danger btn-xs">
                                    <i class="fa fa-trash" aria-hidden="true"></i>X
                                </button>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <!-- END PRODUCT -->
               
            </div>
            <div class="card-footer">
                <div class="coupon col-md-5 col-sm-5 no-padding-left pull-left">
                    <div class="row">
                        <div class="col-6">
                            <input type="text" class="form-control" placeholder="cupone code">
                        </div>
                        <div class="col-6">
                            <input type="submit" class="btn btn-default" value="Use cupone">
                        </div>
                    </div>
                </div>
                <div class="pull-right" style="margin: 10px">
                    <a href="" class="btn btn-success pull-right">Hacer pedido</a>
                    <div class="pull-right" style="margin: 5px">
                        Total pedido: <b>$100.00</b>
                    </div>
                </div>
            </div>
        </div>
</div>
  
    <?php
    return ob_get_clean();
}

// Asociar la función con la página de WordPress
function shortcode_generar_orden() {
    return mostrar_formulario_orden();
}
add_shortcode('generar_orden', 'shortcode_generar_orden');

// Crear la página "hacer_pedido" y asignar el shortcode
function crear_pagina_hacer_pedido() {
    $post_content = '[generar_orden]';
    $page_check = get_page_by_title('hacer_pedido');

    // Si la página no existe, entonces la creamos
    if (empty($page_check)) {
        $page_id = wp_insert_post(array(
            'post_title'     => 'hacer_pedido',
            'post_content'   => $post_content,
            'post_status'    => 'publish',
            'post_type'      => 'page',
            'ping_status'    => 'closed',
            'comment_status' => 'closed',
        ));
    // Asociar la plantilla del plugin
    update_post_meta($page_id, '_wp_page_template', 'templates/order_template.php');    
   
    }
}
add_action('init', 'crear_pagina_hacer_pedido');
