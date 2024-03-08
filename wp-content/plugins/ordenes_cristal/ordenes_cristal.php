<?php



require_once('classes/cart.php');

/*
Plugin Name: Cristal POS
Description: Plugin para gestionar órdenes de productos
Version: 1.0
Author: Tu Nombre
*/


//desactivar plugin
register_deactivation_hook( __FILE__, 'my_plugin_remove_database' );
function my_plugin_remove_database() {
     global $wpdb;
     $table_name_order_items = $wpdb->prefix . 'orden_items';
     $sql = "DROP TABLE IF EXISTS $table_name_order_items";
     $wpdb->query($sql);
    
     $table_name_order = $wpdb->prefix . 'orden';
     $sql = "DROP TABLE IF EXISTS $table_name_order";
     $wpdb->query($sql);
    

    }  

// Activar el plugin
register_activation_hook(__FILE__, 'cristal_pos_activate');

function cristal_pos_activate()
{
    global $wpdb;
    $orden_table_name = $wpdb->prefix . 'orden';
    $orden_items_table_name = $wpdb->prefix . 'orden_items';
    $tabla_valores_ideales = $wpdb->prefix . 'valores_ideales';

    $charset_collate = $wpdb->get_charset_collate();

    $orden_sql = "CREATE TABLE $orden_table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        fecha_orden datetime NOT NULL,
        cliente varchar(100) NOT NULL,
        cliente_name varchar(255) NOT NULL,
        totalOrden decimal(10,2) NOT NULL,
        fichero_adjunto varchar(100) NOT NULL,
        marca varchar(100) NOT NULL,
        image_marca varchar(100) NOT NULL,
        name_marca varchar(100) NOT NULL,
        links varchar(255) NOT NULL,
        is_send varchar(255) NOT NULL,
        tienda varchar(255) NOT NULL,
        tienda_name varchar(255) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    $orden_items_sql = "CREATE TABLE $orden_items_table_name (
        id_item mediumint(9) NOT NULL AUTO_INCREMENT,
        order_id mediumint(9) NOT NULL,
        ID varchar(100) NOT NULL,
        post_title varchar(100) NOT NULL,
        post_content longtext,
        post_id int(10) NOT NULL,
        price decimal(10,2) NOT NULL,
        categorias longtext NOT NULL,
        cnt int NOT NULL,
        subtotal decimal(10,2) NOT NULL,
        observacion longtext ,
        marca varchar(100) NOT NULL,
        sku varchar(100) NOT NULL,
        image_url   longtext NOT NULL, 
        PRIMARY KEY  (id_item),
        FOREIGN KEY  (order_id) REFERENCES $orden_table_name(id) ON DELETE CASCADE
    ) $charset_collate;";

    $sql_valores_ideales = "CREATE TABLE IF NOT EXISTS $tabla_valores_ideales (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        marca_id mediumint(9) NOT NULL,
        categoria_id mediumint(9) NOT NULL,
        valor_ideal float NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";



    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($orden_sql);
    dbDelta($orden_items_sql);
    dbDelta($sql_valores_ideales);
}


// Agregar página de administración valores ideales
add_action('admin_menu', 'cristal_pos_admin_menu_valores_ideales');


function agregar_estilos_y_scripts()
{
?>

    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.7/angular-resource.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        var plugins_url = '<?= plugins_url() ?>'
    </script>
<?php

    // Registrar y encolar el archivo JS
    wp_register_script('dropzone-plugin', plugins_url('assets/js/file-dropzone.js', __FILE__), array('jquery'), '1.0', true);
    wp_enqueue_script('dropzone-plugin');

    // Registrar y encolar el archivo CSS
    wp_register_style('estilos-plugin', plugins_url('assets/css/cart_order.css', __FILE__));
    wp_enqueue_style('estilos-plugin');

    // Registrar y encolar el archivo JS
    wp_register_script('segbar-plugin', plugins_url('assets/js/segbar.js', __FILE__), array('jquery'), '1.0', true);
    wp_enqueue_script('segbar-plugin');

    wp_register_style('cart_order-plugin', plugins_url('assets/css/cart_order.css', __FILE__));
    wp_enqueue_style('cart_order-plugin');



    // Registrar y encolar el archivo JS
    wp_register_script('order-plugin', plugins_url('assets/js/order_cart.js', __FILE__), array('jquery'), '1.0', true);
    wp_enqueue_script('order-plugin');

    wp_localize_script('order-plugin',
                        'dcmsUpload',
                        [ 'ajaxurl'=>admin_url('admin-ajax.php'),
                          'nonce' => wp_create_nonce('ajax-nonce-upload')]);

    // Registrar y encolar el archivo JS
    wp_register_script('validate-plugin', plugins_url('assets/js/jquery.validate.js', __FILE__), array('jquery'), '1.0', true);
    wp_enqueue_script('validate-plugin');

    // Registrar y encolar el archivo JS noty
    wp_register_script('noty-plugin', plugins_url('assets/js/noty/noty.min.js', __FILE__), array('jquery'), '1.0', true);
    wp_enqueue_script('noty-plugin');


  wp_register_style('noty-plugin-css', plugins_url('assets/js/noty/noty.css', __FILE__));
  wp_enqueue_style('noty-plugin-css');

  wp_register_style('noty-plugin-css-theme', plugins_url('assets/js/noty/themes/mint.css', __FILE__));
    wp_enqueue_style('noty-plugin-css-theme');

}

// Llamar a la función 'agregar_estilos_y_scripts' para que se ejecute cuando WordPress cargue los scripts y estilos
add_action('wp_enqueue_scripts', 'agregar_estilos_y_scripts');




function cristal_pos_admin_menu_valores_ideales()
{
    add_menu_page(
        'Valores ideales',
        'Valores ideales',
        'manage_options',
        'cristal_pos_valores_ideales',
        'cristal_pos_valores_ideales_page',
        'dashicons-money',
        20
    );
}

function get_valor_ideal($valores_ideales, $marca_id, $categoria_id)
{
    $valor_ideal_found = 0;
    foreach ($valores_ideales as $valor_ideal) {

        if ($valor_ideal->marca_id == $marca_id && $valor_ideal->categoria_id == $categoria_id) {

            $valor_ideal_found = $valor_ideal->valor_ideal;
            return $valor_ideal_found;
        }
    }

    return $valor_ideal_found;
}

add_filter('logout_redirect', 'custom_logout_redirect');

function custom_logout_redirect($redirect_to)
{
    return home_url('/login');
}

function restrict_access_to_logged_in_users()
{

    $post_d = get_post();


    if (!is_user_logged_in() && !is_page('login') && $post_d->post_name != 'login' && !is_page('wp-login.php')) {

        wp_redirect(site_url('/index.php/login'));
        exit;
    }
}
add_action('template_redirect', 'restrict_access_to_logged_in_users');


// Página de administración para mostrar órdenes
function cristal_pos_valores_ideales_page()
{
    global $wpdb;
    $valores_ideales_table_name = $wpdb->prefix . 'valores_ideales';
    $categorias = obtener_categorias();
    $marcas = obtener_marcas();


    $valores_ideales = $wpdb->get_results("SELECT * FROM $valores_ideales_table_name");

    echo '<div class="wrap">';
    echo '<h2>Valores ideales</h2>';

    include("templates/valores_ideales_admin.php");
    echo '</div>';
}

// Agregar página de administración
add_action('admin_menu', 'cristal_pos_admin_menu');

function cristal_pos_admin_menu()
{
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
function cristal_pos_ordenes_page()
{
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
function mostrar_formulario_orden()
{
    // Aquí va el HTML del formulario de orden

    $cart = new Cart();



    $cart_items = $cart->contents();

    $categorias = obtener_categorias();
    $marcas = obtener_marcas();
    $tiendas = obtener_tiendas();

    ob_start();
?>
    <script>
        var categorias_data = <?= json_encode($categorias) ?>
    </script>

    <?php include('templates/cart2.php'); ?>



<?php

    return ob_get_clean();
}

$cart = new Cart();



function shortcode_generar_orden()
{
    return mostrar_formulario_orden();
}
add_shortcode('generar_orden', 'shortcode_generar_orden');


// Función para mostrar minicart
function mostrar_minicart()
{
    ob_start();
?>


    <?php include('templates/miniCart.php'); ?>

<?php

    return ob_get_clean();
}


function shortcode_mini_cart()
{
    return mostrar_minicart();
}
add_shortcode('mini_cart', 'shortcode_mini_cart');

// Crear la página "hacer_pedido" y asignar el shortcode
function crear_pagina_hacer_pedido()
{
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
        $template_path = 'template-nuevo-pedido.php';
        update_post_meta($page->ID, '_wp_page_template', $template_path);
    }
}
add_action('init', 'crear_pagina_hacer_pedido');


// Registra la ruta personalizada para el endpoint agregar carrito
function custom_add_to_cart_endpoint()
{
    register_rest_route('ordenes_cristal/v1', '/add_to_cart', array(
        'methods' => 'POST',
        'callback' => 'handle_add_to_cart_request',
    ));
}
add_action('rest_api_init', 'custom_add_to_cart_endpoint');

// Función para manejar las solicitudes POST al endpoint
function handle_add_to_cart_request($request)
{
    // Obtener los datos del cuerpo de la solicitud
    $params = $request->get_params();
    $cart = new Cart();


    // Verificar si se enviaron los datos del producto
    if (isset($params['ID']) && isset($params['post_title']) && isset($params['post_content']) && isset($params['price'])) {

        // Crear una instancia de la clase Cart


        // Crear un array con los datos del producto
        $producto = array(
            'ID' => $params['ID'],
            'post_title' => $params['post_title'],
            'image_url' => $params['image_url'],
            'post_content' => $params['post_content'],
            'price' => $params['price']
        );

        // Insertar el producto en el carrito
        $result = $cart->insert($producto);

        // Verificar si la inserción fue exitosa
        if ($result == true) {
            return array('success' => true, 'message' => 'Product added to cart successfully');
        } else {
            return array('success' => false, 'message' => 'Failed to add product to cart');
        }
    } else {
        // Si faltan datos del producto, devolver un mensaje de error
        return array('success' => false, 'message' => 'Error: Missing product data');
    }
}

// AÃ±adir el endpoint personalizado para recibir los datos
add_action('rest_api_init', function () {
    register_rest_route('ordenes_cristal/v1', '/list_cart_items/', array(
        'methods' => 'GET',
        'callback' => 'list_cart_items',
        'permission_callback' => function () {
            return current_user_can('manage_options'); // Puedes ajustar los permisos segÃºn tus necesidades
        },
    ));
});


function list_cart_items($request)
{
    $cart = new Cart();


    $cart_items = $cart->contents();


    echo json_encode(array("cart_items" => $cart_items));
    die();
}



function registrar_post_type_tiendas()
{
    $args = array(
        'label'               => __('Tiendas', 'text-domain'),
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array('slug' => 'tiendas'),
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => false,
        'menu_position'       => null,
        'supports'            => array('title', 'editor', 'thumbnail', 'custom-fields')
    );
    register_post_type('tiendas', $args);

    // Registrar el campo adicional para metros cuadrados
    register_meta('post', 'metros_cuadrados', array(
        'type' => 'string',
        'description' => 'Metros Cuadrados de la tienda',
        'single' => true,
        'show_in_rest' => true, // Para que se pueda utilizar en la API REST de WordPress
    ));

    // Agregar metabox para mostrar el campo "metros cuadrados"
    add_action('add_meta_boxes', 'agregar_metabox_metros_cuadrados');
    // Guardar el valor del campo "metros_cuadrados"
    add_action('save_post', 'guardar_valor_metros_cuadrados');
}

function agregar_metabox_metros_cuadrados()
{
    add_meta_box(
        'metros_cuadrados',
        __('Metros Cuadrados', 'text-domain'),
        'mostrar_campo_metros_cuadrados',
        'tiendas',
        'normal',
        'default'
    );
}

function mostrar_campo_metros_cuadrados($post)
{
    $metros_cuadrados = get_post_meta($post->ID, 'metros_cuadrados', true);
?>
    <label for="metros_cuadrados"><?php _e('Metros Cuadrados:', 'text-domain'); ?></label>
    <input type="text" id="metros_cuadrados" name="metros_cuadrados" value="<?php echo esc_attr($metros_cuadrados); ?>" />
<?php
}

function guardar_valor_metros_cuadrados($post_id)
{
    if (isset($_POST['metros_cuadrados'])) {
        update_post_meta($post_id, 'metros_cuadrados', sanitize_text_field($_POST['metros_cuadrados']));
    }
}

add_action('init', 'registrar_post_type_tiendas');

// Agregar columna personalizada a la lista de tiendas
function agregar_columna_metros_cuadrados($columns)
{
    $columns['metros_cuadrados'] = __('Metros Cuadrados', 'text-domain');
    return $columns;
}
add_filter('manage_tiendas_posts_columns', 'agregar_columna_metros_cuadrados');

// Mostrar el valor del campo "metros_cuadrados" en la columna personalizada
function mostrar_valor_metros_cuadrados_columna($column, $post_id)
{
    if ($column == 'metros_cuadrados') {
        $metros_cuadrados = get_post_meta($post_id, 'metros_cuadrados', true);
        echo esc_html($metros_cuadrados);
    }
}
add_action('manage_tiendas_posts_custom_column', 'mostrar_valor_metros_cuadrados_columna', 10, 2);





function registrar_post_type_marcas()
{
    $args = array(
        'label'               => __('Marcas', 'text-domain'),
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array('slug' => 'marcas'),
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => false,
        'menu_position'       => null,
        'supports'            => array('title', 'editor', 'thumbnail', 'custom-fields')
    );
    register_post_type('marcas', $args);
}
add_action('init', 'registrar_post_type_marcas');

function agregar_campos_marcas_productos()
{
    global $woocommerce, $post;

    echo '<div class="options_group">';

    // Campo de selección de marca
    woocommerce_wp_select(array(
        'id'          => '_marca_producto',
        'label'       => __('Marca', 'woocommerce'),
        'class'       => 'wc-enhanced-select',
        'options'     => obtener_marcas_disponibles(),
    ));

    echo '</div>';
}
add_action('woocommerce_product_options_general_product_data', 'agregar_campos_marcas_productos');

function guardar_datos_marcas_productos($post_id)
{
    $marca = isset($_POST['_marca_producto']) ? sanitize_text_field($_POST['_marca_producto']) : '';
    update_post_meta($post_id, '_marca_producto', $marca);
}
add_action('woocommerce_process_product_meta', 'guardar_datos_marcas_productos');

function obtener_marcas_disponibles()
{
    $marcas = get_posts(array(
        'post_type'      => 'marcas',
        'posts_per_page' => -1,
    ));

    $options = array();
    foreach ($marcas as $marca) {
        $options[$marca->ID] = $marca->post_title;
    }

    return $options;
}


// Mostrar la marca en la columna personalizada
function mostrar_marca_en_columna($column, $post_id)
{
    if ($column === 'marca') {
        $marca_id = get_post_meta($post_id, '_marca_id', true);
        if ($marca_id) {
            $marca = get_post($marca_id);
            if ($marca) {
                echo $marca->post_title;
            }
        } else {
            echo '-';
        }
    }
}
add_action('manage_product_posts_custom_column', 'mostrar_marca_en_columna', 10, 2);



// Registrar endpoint personalizado para buscar productos por POST
function registrar_endpoint_busqueda_productos_post()
{
    register_rest_route('ordenes_cristal/v1', '/buscar_productos', array(
        'methods' => 'POST',
        'callback' => 'buscar_productos_endpoint_post',
    ));
}
add_action('rest_api_init', 'registrar_endpoint_busqueda_productos_post');

// Función de devolución de llamada para buscar productos desde el endpoint por POST
function buscar_productos_endpoint_post($request)
{
    $params = $request->get_params();
    $nombre = isset($params['nombre']) ? $params['nombre'] : '';
    $marca = isset($params['marca']) ? $params['marca'] : null;
    $categoria = isset($params['categoria']) ? $params['categoria'] : null;
    $tienda = isset($params['categoria']) ? $params['categoria'] : null;

    // Llamar a la función buscar_productos con los parámetros proporcionados
    $productos_encontrados = buscar_productos($nombre, $marca, $categoria, $tienda);

    // Devolver los productos encontrados como respuesta JSON
    return rest_ensure_response($productos_encontrados);
}


function obtener_post_type_tienda_por_titulo($titulo_tienda)
{
    $args = array(
        'post_type' => 'tiendas',
        'post_title' => $titulo_tienda,
        'posts_per_page' => 1, // Solo necesitamos un post
    );

    $posts = get_posts($args);

    if (!empty($posts)) {
        return $posts[0];
    } else {
        return null;
    }
}


function obtener_post_type_marca_por_titulo($titulo_marca)
{
    $args = array(
        'post_type' => 'marcas',
        'post_title' => $titulo_marca,
        'posts_per_page' => 1, // Solo necesitamos un post
    );

    $posts = get_posts($args);
    if (!empty($posts)) {
        return $posts[0];
    } else {
        return null;
    }
}


function buscar_productos($nombre = '', $marca_id = null, $categoria_id = null, $tienda_id)
{
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1 // Obtener todos los productos
    );

    if ($marca_id !== null || $categoria_id !== null) {

        $args['tax_query'] = array('relation' => 'AND'); // Relación AND para ambas condiciones

        // Condición de búsqueda por marca si se proporciona
        if ($marca_id !== null) {
            //echo "por marca ".$marca_id;
            $args['meta_query'] = array(
                array(
                    'key' => '_marca_producto', // Clave del metadato de la marca
                    'value' => $marca_id, // ID de la marca específica
                    'compare' => '=' // Comparación de igualdad
                )
            );
        }

        // Condición de búsqueda por categoría si se proporciona
        if ($categoria_id !== null) {
            //echo "por categoria ".$categoria_id;
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'product_cat', // Taxonomía de categorías de productos
                    'field'    => 'term_id', // Campo a buscar
                    'terms'    => $categoria_id // ID de la categoría específica
                )
            );
        }
    }


    // Realizar la consulta
    $productos = new WP_Query($args);

    // Obtener la información (precio, imagen, SKU y categorías) para cada producto
    $productos_con_info = array();
    foreach ($productos->posts as $producto) {
        $precio_producto = get_post_meta($producto->ID, '_price', true);
        $imagen_producto_id = get_post_thumbnail_id($producto->ID);
        $imagen_producto_url = wp_get_attachment_url($imagen_producto_id);
        $sku_producto = get_post_meta($producto->ID, '_sku', true); // Obtener el SKU del producto
        $marca_id_producto = get_post_meta($producto->ID, '_marca_producto', true);

        // Obtener las categorías asociadas al producto con todos los campos y metacampos
        $categorias_producto = wp_get_post_terms($producto->ID, 'product_cat');

        // Almacenar la información relevante de cada categoría asociada al producto
        $categorias_producto_info = array();
        foreach ($categorias_producto as $categoria_producto) {
            // Obtener todos los detalles de la categoría, incluidos los metacampos
            $categoria_info = get_term_by('id', $categoria_producto->term_id, 'product_cat');
            $categorias_producto_info[] = $categoria_info;
        }



        $productos_con_info[] = array(
            'ID' => $producto->ID,
            'post_title' => $producto->post_title,
            'post_content' => $producto->post_content,
            'price' => $precio_producto,
            'image_url' => $imagen_producto_url,
            'sku' => $sku_producto,
            'categorias' => $categorias_producto_info, // Agregar las categorías del producto al resultado
            'marca' => $marca_id_producto,
            'observacion' => ''
        );
    }

    return ['productos' => $productos_con_info];
}




function obtener_categorias()
{
    $categorias = get_terms(array(
        'taxonomy' => 'product_cat', // Taxonomía de categorías de WooCommerce
        'hide_empty' => false, // Incluir categorías incluso si están vacías
    ));

    return $categorias;
}

function obtener_marcas()
{
    $marcas = get_posts(array(
        'post_type'      => 'marcas',
        'posts_per_page' => -1,
    ));


    $marcas_con_imagenes = array();

    foreach ($marcas as $marca) {
        $imagen_url = get_the_post_thumbnail_url($marca->ID, 'full');

        $marca_info = array(
            'ID'   => $marca->ID,
            'post_title'    => $marca->post_title,
            'post_content' => $marca->post_content,
            'image'    => $imagen_url,
            // Puedes añadir más información del post si la necesitas
        );

        $marcas_con_imagenes[] = $marca_info;
    }

    return $marcas_con_imagenes;
}


function obtener_tiendas()
{
    // Define los parámetros de la consulta
    $args = array(
        'post_type'      => 'tiendas',
        'posts_per_page' => -1,
        'meta_key'       => 'metros_cuadrados', // Especifica el meta campo que deseas recuperar
    );

    // Realiza la consulta
    $tiendas = get_posts($args);

    // Verifica si se encontraron tiendas
    if ($tiendas) {
        // Itera sobre cada tienda encontrada
        foreach ($tiendas as $tienda) {
            // Obtén el valor del meta campo 'metros_cuadrados' para cada tienda
            $metros_cuadrados = get_post_meta($tienda->ID, 'metros_cuadrados', true);

            // Agrega el valor del meta campo al objeto de la tienda
            $tienda->metros_cuadrados = $metros_cuadrados;
        }
    }

    // Devuelve el array de tiendas con los valores de 'metros_cuadrados' agregados
    return $tiendas;
}


// Registrar la ruta del endpoint
function registrar_endpoint_obtener_tiendas()
{
    register_rest_route('ordenes_cristal/v1', '/obtener_tiendas', array(
        'methods'  => 'GET',
        'callback' => 'obtener_tiendas_callback',
    ));
}
add_action('rest_api_init', 'registrar_endpoint_obtener_tiendas');

// Callback para obtener las tiendas y sus metadatos
function obtener_tiendas_callback($data)
{
    $args = array(
        'post_type'      => 'tiendas',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
    );

    $query = new WP_Query($args);
    $tiendas = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $tienda = array(
                'id'            => get_the_ID(),
                'titulo'        => get_the_title(),
                'contenido'     => get_the_content(),
                'imagen'        => get_the_post_thumbnail_url(),
                'metros_cuadrados' => get_post_meta(get_the_ID(), 'metros_cuadrados', true),
            );
            $tiendas[] = $tienda;
        }
        wp_reset_postdata();
    }

    return $tiendas;
}

// Hook para asignar valores ideales cuando se crea una nueva marca
function asignar_valores_ideales_por_defecto($post_id, $post, $update)
{


    if ($post->post_type == 'marcas' && !$update) {
        global $wpdb;
        $tabla_valores_ideales = $wpdb->prefix . 'valores_ideales';

        echo "asignano valores ideales por defecto " . $tabla_valores_ideales;

        // Obtener todas las categorías de productos
        $categorias = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
        ));

        // Insertar registros en la tabla valores_ideales con valor por defecto
        foreach ($categorias as $categoria) {
            if ($categoria->slug != 'sin-categorizar') {
                $wpdb->insert(
                    $tabla_valores_ideales,
                    array(
                        'marca_id' => $post_id,
                        'categoria_id' => $categoria->term_id,
                        'valor_ideal' => 0,
                    )
                );
            }
        }
    }
}
add_action('save_post', 'asignar_valores_ideales_por_defecto', 10, 3);

// Agregar endpoint personalizado para manejar la actualización del valor ideal
add_action('rest_api_init', 'registrar_endpoint_actualizar_valor_ideal');

function registrar_endpoint_actualizar_valor_ideal()
{
    register_rest_route('ordenes_cristal/v1', '/actualizar_valor_ideal/', array(
        'methods' => 'POST',
        'callback' => 'actualizar_valor_ideal_callback',
        'permission_callback' => '__return_true' // Permitir acceso a todos
    ));
}

// Función que maneja la lógica para actualizar el valor ideal
function actualizar_valor_ideal_callback($request)
{
    global $wpdb;

    // Obtener los parámetros enviados en la solicitud
    $marca_id = $request->get_param('marca_id');
    $categoria_id = $request->get_param('categoria_id');
    $nuevo_valor_ideal = $request->get_param('valor_ideal');

    // Actualizar el valor ideal existente
    $result = $wpdb->update(
        $wpdb->prefix . 'valores_ideales',
        array('valor_ideal' => $nuevo_valor_ideal),
        array('marca_id' => $marca_id, 'categoria_id' => $categoria_id),
        array('%f'),
        array('%d', '%d')
    );

    if ($result !== false) {
        return new WP_REST_Response(array('message' => 'Valor ideal actualizado correctamente'), 200);
    } else {
        return new WP_Error('actualizacion_error', 'Error al actualizar el valor ideal', array('status' => 500));
    }
}


// Agregar endpoint personalizado para manejar la actualización del valor ideal
add_action('rest_api_init', 'obtener_valores_ideales');

function obtener_valores_ideales()
{
    register_rest_route('ordenes_cristal/v1', '/obtener_valores_ideales/', array(
        'methods' => 'POST',
        'callback' => 'consultar_valores_ideales_callback',
        'permission_callback' => '__return_true' // Permitir acceso a todos
    ));
}



function consultar_valores_ideales_callback($request)
{
    global $wpdb;

    // Obtener los parámetros enviados en la solicitud
    $marca_id = $request->get_param('marca');
    $categorias_string = $request->get_param('categorias');


    // Realizar la consulta para obtener los valores ideales
    $query = "
        SELECT *
        FROM {$wpdb->prefix}valores_ideales
        WHERE marca_id = $marca_id
        AND categoria_id IN ($categorias_string)
    ";



    $valores_ideales = $wpdb->get_results($query);

    if ($valores_ideales) {
        // Devolver los valores ideales encontrados
        return new WP_REST_Response($valores_ideales, 200);
    } else {
        // No se encontraron valores ideales para los parámetros dados
        return new WP_Error('sin_valores_ideales', 'No se encontraron valores ideales para la marca y categorías indicadas', array('status' => 404));
    }
}



// Agregar campo select para relacionar marca al producto
add_action('woocommerce_product_options_general_product_data', 'agregar_metabox_marca_producto');


function agregar_metabox_marca_producto()
{
    add_meta_box(
        '_marca_producto',
        __('Marca', 'text-domain'),
        'mostrar_metabox_marca_producto',
        'product',
        'normal',
        'default'
    );
}

function mostrar_campo_marca($post)
{
    $marca_producto = get_post_meta($post->ID, '_marca_producto', true);
?>
    <label for="marca_producto"><?php _e('Marcca:', 'text-domain'); ?></label>
    <input type="text" id="marca" name="marca" value="<?php echo esc_attr($marca_producto); ?>" />
<?php
}

// Mostrar la metabox para seleccionar la marca
function mostrar_metabox_marca_producto()
{
    global $post;

    // Obtenemos la marca asociada al producto, si existe
    $marca_id = get_post_meta($post->ID, '_marca_producto', true);

    // Obtenemos todas las marcas
    $marcas = get_posts(array(
        'post_type' => 'marcas',
        'posts_per_page' => -1,
    ));

    // Creamos un select para seleccionar la marca
    echo '<label for="marca_producto">Marca del Producto:</label>';
    echo '<select id="marca_producto" name="_marca_producto">';
    echo '<option value="">Seleccione una marca</option>';

    foreach ($marcas as $marca) {
        $selected = ($marca_id == $marca->ID) ? 'selected' : '';
        echo '<option value="' . $marca->ID . '" ' . $selected . '>' . $marca->post_title . '</option>';
    }

    echo '</select>';
}

function guardar_valor_marca_producto($post_id)
{
    if (isset($_POST['_marca_producto'])) {
        update_post_meta($post_id, '_marca_producto', sanitize_text_field($_POST['_marca_producto']));
    }
}


// Guardar el valor seleccionado en el campo marca del producto
add_action('woocommerce_process_product_meta', 'guardar_valor_marca_producto');



// Nonce validation
function validate_nonce( $nonce_name ){
    if ( ! wp_verify_nonce( $_POST['nonce'], $nonce_name ) ) {
        $res = [
            'status' => 0,
            'message' => '✋ Error nonce validation!!'
        ];
        echo json_encode($res);
        wp_die();
    }
}


// Agregar la columna 'Marca' a las columnas permitidas en la importación CSV
add_filter('woocommerce_csv_product_import_mapping_default_columns', 'agregar_columna_marca');
function agregar_columna_marca($columns) {
    $columns['marca'] = 'Marca';
    return $columns;
}

// Procesar la importación de la marca desde el archivo CSV
add_filter('woocommerce_product_import_pre_insert_product_object', 'procesar_importacion_marca', 10, 2);
function procesar_importacion_marca($object, $data) {
    if (isset($data['marca'])) {
        $marca_name = $data['marca'];

        // Buscar la marca por el nombre en el tipo de publicación 'marcas'
        $marca_query = new WP_Query(array(
            'post_type' => 'marcas',
            'posts_per_page' => 1,
            'title' => $marca_name,
            'fields' => 'ids'
        ));

        // Si no se encuentra la marca, crearla
        if (!$marca_query->have_posts()) {
            $marca_id = wp_insert_post(array(
                'post_type' => 'marcas',
                'post_title' => $marca_name,
                'post_status' => 'publish'
            ));
        } else {
            $marca_id = $marca_query->posts[0];
        }

        // Asociar la marca al producto
        update_post_meta($object->get_id(), '_marca_producto', $marca_id);

        wp_reset_postdata(); // Restablecer los datos del post
    }
    return $object;
}

// Registrar la ruta del endpoint para guardar la orden
function registrar_endpoint_guardar_orden()
{
    register_rest_route('ordenes_cristal/v1', '/guardar_orden', array(
        'methods' => 'POST',
        'callback' => 'handle_order_save_request',
    ));
}
add_action('rest_api_init', 'registrar_endpoint_guardar_orden');

// Función para manejar las solicitudes POST al endpoint /order-save
function handle_order_save_request($request)
{
   

     // Obtener los datos del cuerpo de la solicitud
    $params = $request->get_params();
    
    //die();

    // Verificar si se enviaron los datos de la orden
    if (isset($params['order'])) {
        // Si el usuario está logueado, obtener su ID

        $newOrder = json_decode(base64_decode($params['order']));
        

        $user = wp_get_current_user();

        $user_id = $user->ID;

        if ($user_id) {
            global $wpdb;
            $orden_table_name = $wpdb->prefix . 'orden';
            $orden_items_table_name = $wpdb->prefix . 'orden_items';

            // Crear una nueva orden
            $fecha_actual = current_time('mysql'); // Obtener la fecha y hora actuales en formato MySQL
            $totalOrden = $newOrder->total_order; // El total de la orden

            $file_name = '';

              // Si se adjuntó un archivo, guardarlo relacionado con la orden
             if (isset($params['file_order'])) {
               // print_r($_FILES);
                //echo "tiene filesystem";

                foreach ($params['file_order'] as $file_order_upload){
                  //  echo "namefile ".$file_order_upload['name'];
                // Obtener el directorio de subidas de WordPress
                $upload_dir = wp_upload_dir();
                $base_dir = $upload_dir['basedir'];
                $ordenes_cristal_dir = $base_dir . '/ordenes_cristal';

                // Verificar si la carpeta ordenes_cristal existe, si no, crearla
                if (!file_exists($ordenes_cristal_dir)) {
                    mkdir($ordenes_cristal_dir, 0755, true); // Crear la carpeta con permisos 0755
                }

                // Guardar el archivo adjunto en la carpeta uploads/ordenes_cristal
                $file_path = $ordenes_cristal_dir . '/' . $file_order_upload['name'];
                move_uploaded_file($file_order_upload['tmp_name'], $file_path);

            }
            
            }


            $marca = $newOrder['marca'];
            $image_marca = $newOrder['image_marca'];
            $name_marca = $newOrder['name_marca'];
            $links = $params['links'];
            // Insertar la nueva orden en la tabla de órdenes
            $wpdb->insert(
                $orden_table_name,
                array(
                    'fecha_orden' => $fecha_actual,
                    'cliente' => $user_id,
                    'totalOrden' => $totalOrden,
                    'ficheros_adjunto' => $file_name,
                    'marca' => $marca,
                    'image_marca' => $image_marca,
                    'name_marca' => $name_marca,
                    'links'=>$links
                )
            );


            // Obtener el ID de la orden recién creada
            $order_id = $wpdb->insert_id;

            // Guardar los ítems de la orden en la tabla de items de la orden
            foreach ($newOrder['items'] as $item) {
                $wpdb->insert(
                    $orden_items_table_name,
                    array(
                        'order_id' => $order_id,
                        'ID' => $item['ID'],
                        'post_title' => $item['post_title'],
                        'post_content' => $item['post_content'],
                        'cnt' => $item['cnt'],
                        'observacion' => $item['observacion'],
                        
                        'price' => $item->price,
                        'categorias' => json_encode($item->categorias),
                        'subtotal' => $item->subtotal,
                        'sku' => $item->sku,
                        'image_url' => $item->image_url
                    )
                );
            }

           

            return array('success' => true, 'message' => 'Order saved successfully', 'order_id' => $order_id);
        } else {
            return array('success' => false, 'message' => 'User not logged in');
        }
    } else {
        // Si faltan datos de la orden, devolver un mensaje de error
        return array('success' => false, 'message' => 'Error: Missing order data');
    }
}


function get_orders_list()
{

    global $wpdb;
    $orden_table_name = $wpdb->prefix . 'orden';
    $orden_items_table_name = $wpdb->prefix . 'orden_items';

    $out_data = [];
    $orders = $wpdb->get_results("SELECT * FROM $orden_table_name");

    foreach ($orders as $order) {

        $order_items = $wpdb->get_results("SELECT * FROM $orden_items_table_name where order_id = $order->id");

        array_push($out_data, ['order' => $order, 'items' => $order_items]);
    }

    return $out_data;
}

// Hook para registrar un shortcode que mostrará el formulario de inicio de sesión
add_shortcode('custom_login_form', 'custom_login_form_shortcode');

function custom_login_form_shortcode($atts)
{
    if (is_user_logged_in()) {
        return '<p>Ya estás conectado.</p>';
    }

    // Mensaje de error si hay algún error de inicio de sesión
    $error = '';
    if (isset($_GET['login']) && $_GET['login'] === 'failed') {
        $error = '<p class="error">Credenciales inválidas. Inténtalo de nuevo.</p>';
    }

    // Mostrar formulario de inicio de sesión
    $form = '<form id="login-form">
    <p>
        <label for="username">Nombre de usuario:</label>
        <input type="text" name="username" id="username">
    </p>
    <p>
        <label for="password">Contraseña:</label>
        <input type="password" name="password" id="password">
    </p>
    <p>
        <input type="submit" value="Iniciar sesión">
    </p>

    <div id="login-error"></div>
</form>';

    return $error . $form;
}

add_action('wp_ajax_custom_login', 'custom_login');
add_action('wp_ajax_nopriv_custom_login', 'custom_login');

function custom_login()
{
    // Comprueba si se ha enviado el formulario
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $creds = array(
            'user_login'    => $_POST['username'],
            'user_password' => $_POST['password'],
            'remember'      => true
        );

        // Iniciar sesión del usuario
        $user = wp_signon($creds, false);

        if (is_wp_error($user)) {
            // Si hay un error de inicio de sesión, devuelve un mensaje de error
            wp_send_json_error(array('message' => $user->get_error_message()));
        } else {
            // Si el inicio de sesión es exitoso, devuelve un mensaje de éxito
            wp_send_json_success(array('message' => 'Inicio de sesión exitoso'));
        }
    }

    // Si no se ha enviado el formulario, devuelve un mensaje de error
    wp_send_json_error(array('message' => 'Error: los campos de inicio de sesión están vacíos'));
}
