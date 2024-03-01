<?php
/**
 * Theme functions and definitions.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * https://developers.elementor.com/docs/hello-elementor-theme/
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_CHILD_VERSION', '2.0.0' );

/**
 * Load child theme scripts & styles.
 *
 * @return void
 */
function hello_elementor_child_scripts_styles() {

	wp_enqueue_style(
		'hello-elementor-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		[
			'hello-elementor-theme-style',
		],
		HELLO_ELEMENTOR_CHILD_VERSION
	);

    wp_enqueue_style(
        'login-styles',
        get_stylesheet_directory_uri() . '/styles/login-styles.css', // Ruta del nuevo archivo CSS
        [],
        '1.0.0' // Cambia esta versión según sea necesario
    );

    wp_enqueue_style(
        'login-styles',
        get_stylesheet_directory_uri() . '/styles/home-styles.css', // Ruta del nuevo archivo CSS
        [],
        '1.0.0' // Cambia esta versión según sea necesario
    );
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_scripts_styles', 20 );

if (! function_exists('fa_custom_setup_kit') ) {
	function fa_custom_setup_kit($kit_url = '') {
	  foreach ( [ 'wp_enqueue_scripts', 'admin_enqueue_scripts', 'login_enqueue_scripts' ] as $action ) {
		add_action(
		  $action,
		  function () use ( $kit_url ) {
			wp_enqueue_script( 'font-awesome-kit', $kit_url, [], null );
		  }
		);
	  }
	}
  }

fa_custom_setup_kit('https://kit.fontawesome.com/d59d369536.js');

function enqueue_jquery() {
    wp_enqueue_script('jquery');
}
add_action('wp_enqueue_scripts', 'enqueue_jquery');

/* function enqueue_bootstrap() {
    // Registrar e incluir el archivo CSS de Bootstrap desde un CDN
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css', array(), '5.2.3');
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js', array(), '5.2.3');
}
add_action('wp_enqueue_scripts', 'enqueue_bootstrap'); */



function wp_dropdown_product_subcategories_shortcode($atts) {
    ob_start();

    // Definir los atributos del shortcode y sus valores predeterminados
    $atts = shortcode_atts(
        array(
            'show_count' => 0,
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => 1,
            'class'      => '',
            'taxonomy'   => 'product_cat',
            'name'       => 'product_cat_dropdown',
            'id'         => 'product_cat_dropdown',
            'show_option_all' => 'Todas las categorías',
            'parent_category' => 0, // ID de la categoría principal
            'redirect_url' => '', // URL de redirección base
        ),
        $atts,
        'wp_dropdown_product_subcategories'
    );

    // Obtener las subcategorías de la categoría principal
    $args = array(
        'show_count' => $atts['show_count'],
        'orderby'    => $atts['orderby'],
        'order'      => $atts['order'],
        'hide_empty' => $atts['hide_empty'],
        'taxonomy'   => $atts['taxonomy'],
        'child_of'   => $atts['parent_category'],
    );

    $subcategories = get_terms($args);

    if ($subcategories) {
        ?>
        <select name="<?php echo esc_attr($atts['name']); ?>" id="<?php echo esc_attr($atts['id']); ?>" class="<?php echo esc_attr($atts['class']); ?>" onchange="redirectOnSelect(this)">
            <option value=""><?php echo esc_html($atts['show_option_all']); ?></option>
            <?php
            foreach ($subcategories as $subcategory) {
                ?>
                <option value="<?php echo esc_attr($subcategory->slug); ?>"><?php echo esc_html($subcategory->name); ?><?php echo $atts['show_count'] ? ' (' . esc_html($subcategory->count) . ')' : ''; ?></option>
                <?php
            }
            ?>
        </select>

        <script>
            function redirectOnSelect(element) {
                var selectedValue = element.value;
                if (selectedValue !== '') {
                    var redirectUrl = '<?php echo esc_url($atts['redirect_url']); ?>' + selectedValue;
                    window.location.href = redirectUrl;
                }
            }
        </script>
        <?php
    }

    $output = ob_get_clean();
    return $output;
}

// Registra el shortcode
add_shortcode('wp_dropdown_product_subcategories', 'wp_dropdown_product_subcategories_shortcode');


// Agregar campos personalizados a las categorías de WooCommerce
function add_custom_category_fields() {
    $fields = array(
        'muebles_herrajes' => array(
            'label' => 'MUEBLES Y HERRAJES (5715014501)',
            'type'  => 'text',
        ),
        'iluminacion' => array(
            'label' => 'ILUMINACIÓN (5715014510)',
            'type'  => 'text',
        ),
        'maniquies_ambientacion' => array(
            'label' => 'MANIQUIES Y AMBIENTACIÓN (5715019001)',
            'type'  => 'text',
        ),
        'dotacion' => array(
            'label' => 'DOTACIÓN (5715016001)',
            'type'  => 'text',
        ),
        'imagenes_graficas' => array(
            'label' => 'IMÁGENES GRÁFICAS (5715019501)',
            'type'  => 'text',
        ),
        'tecnologia_omnicalidad_pantalla' => array(
            'label' => 'TECNOLOGIA Y OMNICALIDAD / PANTALLA (5715014502)',
            'type'  => 'text',
        ),
    );

    // foreach ( $fields as $key => $field ) {
    //     add_term_meta( get_term_by( 'slug', $key, 'product_cat' )->term_id, $key, '', true );
    // }
}
add_action( 'init', 'add_custom_category_fields' );

// Show custom fields on category page
function display_custom_category_fields( $term ) {
    $fields = array(
        'muebles_herrajes' => 'MUEBLES Y HERRAJES (5715014501)',
        'iluminacion' => 'ILUMINACIÓN (5715014510)',
        'maniquies_ambientacion' => 'MANIQUIES Y AMBIENTACIÓN (5715019001)',
        'dotacion' => 'DOTACIÓN (5715016001)',
        'imagenes_graficas' => 'IMÁGENES GRÁFICAS (5715019501)',
        'tecnologia_omnicalidad_pantalla' => 'TECNOLOGIA Y OMNICALIDAD / PANTALLA (5715014502)',
    );

    foreach ( $fields as $key => $label ) {
        $value = get_term_meta( $term->term_id, $key, true );
        echo '<tr class="form-field">';
        echo '<th scope="row"><label for="' . esc_attr( $key ) . '">' . esc_html( $label ) . '</label></th>';
        echo '<td><input type="text" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" /></td>';
        echo '</tr>';
    }
}
add_action( 'product_cat_edit_form_fields', 'display_custom_category_fields', 10, 1 );

// Save the custom categories data
function save_custom_category_fields( $term_id ) {
    $fields = array(
        'muebles_herrajes',
        'iluminacion',
        'maniquies_ambientacion',
        'dotacion',
        'imagenes_graficas',
        'tecnologia_omnicalidad_pantalla',
    );

    foreach ( $fields as $key ) {
        if ( isset( $_POST[ $key ] ) ) {
            $value = sanitize_text_field( $_POST[ $key ] );
            update_term_meta( $term_id, $key, $value );
        }
    }
}
add_action( 'edited_product_cat', 'save_custom_category_fields', 10, 1 );



function dropdown_subcategorias_shortcode($atts) {
    // Asegúrate de que WooCommerce esté activo
    if (!class_exists('WooCommerce')) {
        return '';
    }

    // Configura los atributos del shortcode
    $atts = shortcode_atts(
        array(
            'parent_category' => '', // Define la categoría principal por su slug
        ),
        $atts,
        'dropdown_subcategorias'
    );

    // Obtiene la categoría principal
    $parent_category = get_term_by('slug', $atts['parent_category'], 'product_cat');

    if (!$parent_category) {
        return 'Categoría principal no encontrada';
    }

    // Obtiene las subcategorías de la categoría principal
    $subcategories = get_terms(
        array(
            'taxonomy'   => 'product_cat',
            'child_of'   => $parent_category->term_id,
            'hide_empty' => false,
        )
    );

    if (empty($subcategories)) {
        return 'No hay subcategorías disponibles';
    }

    // Obtiene los valores actuales de los filtros de categoría si existen
    $categorias_actuales = isset($_GET['product_cat']) ? (array)$_GET['product_cat'] : array();

    // Genera el HTML de la lista desplegable
    $output = '<form action="' . esc_url(home_url('/')) . '" method="get">';
    $output .= '<label for="subcategoria_dropdown">Selecciona subcategorías:</label>';
    $output .= '<select name="product_cat[]" id="subcategoria_dropdown" multiple>';

    foreach ($subcategories as $subcategory) {
        $output .= '<option value="' . esc_attr($subcategory->slug) . '"';
        // Marca la opción seleccionada si corresponde al filtro actual
        $output .= in_array($subcategory->slug, $categorias_actuales) ? ' selected' : '';
        $output .= '>' . esc_html($subcategory->name) . '</option>';
    }

    $output .= '</select>';
    
    // Incluye los filtros existentes
    foreach ($_GET as $key => $value) {
        if ($key !== 'product_cat') {
            // Si es un filtro distinto a las categorías, agrégalo
            $output .= '<input type="hidden" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '">';
        }
    }

    $output .= '<input type="submit" value="Filtrar">';
    $output .= '</form>';

    return $output;
}

add_shortcode('dropdown_subcategorias', 'dropdown_subcategorias_shortcode');

wp_enqueue_script( 'custom-script', get_stylesheet_directory_uri() . '/custom-script.js', array( 'jquery' ), '1.0', true );

add_action( 'init', 'process_cart_attachment' );
function process_cart_attachment() {
    if ( isset( $_POST['action'] ) && $_POST['action'] === 'process_cart_attachment' ) {
        if ( ! isset( $_POST['process_cart_attachment_nonce'] ) || ! wp_verify_nonce( $_POST['process_cart_attachment_nonce'], 'process_cart_attachment' ) ) {
            return;
        }

        if ( ! empty( $_FILES['cart_attachment'] ) && $_FILES['cart_attachment']['error'] === 0 ) {
            $attachment_id = media_handle_upload( 'cart_attachment', 0 );
            if ( is_wp_error( $attachment_id ) ) {
                wc_add_notice( esc_html__( 'Error al cargar el archivo adjunto.', 'woocommerce' ), 'error' );
            } else {
                WC()->session->set( 'cart_attachment', $attachment_id );
                wc_add_notice( esc_html__( 'El archivo adjunto se cargó correctamente.', 'woocommerce' ) );
            }
        }
    }
}
add_action( 'init', 'process_cart_data' );
function process_cart_data() {
    if ( isset( $_POST['action'] ) && $_POST['action'] === 'process_cart_data' ) {
        if ( ! isset( $_POST['process_cart_data_nonce'] ) || ! wp_verify_nonce( $_POST['process_cart_data_nonce'], 'process_cart_data' ) ) {
            return;
        }

        if ( ! empty( $_POST['additional_products'] ) && is_array( $_POST['additional_products'] ) ) {
            foreach ( $_POST['additional_products'] as $product_details ) {
                $product_details = sanitize_textarea_field( $product_details );

                // Añadir el producto al carrito
                $cart_item_key = WC()->cart->add_to_cart( 0, 1, 0, array(), array('additional_product_details' => $product_details) );

                if ( $cart_item_key ) {
                    wc_add_notice( esc_html__( 'Productos adicionales agregados al carrito.', 'woocommerce' ) );
                } else {
                    wc_add_notice( esc_html__( 'Error al agregar productos adicionales al carrito.', 'woocommerce' ), 'error' );
                }
            }
        }
    }
}
add_action( 'woocommerce_checkout_create_order_line_item', 'add_custom_product_fields', 10, 4 );
function add_custom_product_fields( $item, $cart_item_key, $values, $order ) {
    if ( ! empty( $values['additional_product_details'] ) ) {
        $item->add_meta_data( 'additional_product_details', sanitize_textarea_field( $values['additional_product_details'] ) );
    }
}

add_action('wp_ajax_filter_cart_by_category', 'filter_cart_by_category');
add_action('wp_ajax_nopriv_filter_cart_by_category', 'filter_cart_by_category');

function filter_cart_by_category() {
    if (isset($_POST['category'])) {
        $category_slug = sanitize_text_field($_POST['category']);
        $category = get_term_by('slug', $category_slug, 'product_cat');
        $category_id = $category->term_id;

        // Obtener los datos de los campos personalizados de la categoría seleccionada
        $custom_fields = array(
            'muebles_herrajes',
            'iluminacion',
            'maniquies_ambientacion',
            'dotacion',
            'imagenes_graficas',
            'tecnologia_omnicalidad_pantalla'
        );

        $filtered_results = '';

        // Iterar sobre los campos personalizados y obtener sus valores
        foreach ($custom_fields as $field) {
            $field_value = get_term_meta($category_id, $field, true);
            if (!empty($field_value)) {
                $filtered_results .= '<p><strong>' . $field . ':</strong> ' . $field_value . '</p>';
            }
        }

        echo $filtered_results;
    }

    die();
}

// function custom_login_redirect( $redirect_to, $request, $user ) {
//     // si el usuario es un administrador, redirigir a la página de administración
//     if ( is_array( $user->roles ) && in_array( 'administrator', $user->roles ) ) {
//         return admin_url();
//     }
//     // de lo contrario, redirigir a la página deseada
//     return home_url( '/' );
// }
// add_filter( 'login_redirect', 'custom_login_redirect', 10, 3 );
function custom_login_redirect( $redirect_to, $request, $user ) {
    // Verificar si $user es un objeto de usuario válido
    if ( is_a( $user, 'WP_User' ) ) {
        // Si el usuario es un administrador, redirigir a la página de administración
        if ( in_array( 'administrator', $user->roles ) ) {
            return admin_url();
        }
    }
    // De lo contrario, redirigir a la página deseada
    return home_url( '/' );
}
add_filter( 'login_redirect', 'custom_login_redirect', 10, 3 );

function wp_stores_custom_post_type() {
	register_post_type('wp_stores',
		array(
			'labels'      => array(
				'name'          => __('Tiendas', 'textdomain'),
				'singular_name' => __('Tienda', 'textdomain'),
			),
				'public'      => true,
				'has_archive' => true,
		)
	);
}
add_action('init', 'wp_stores_custom_post_type');

