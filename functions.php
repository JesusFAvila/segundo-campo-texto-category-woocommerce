// Definimos una función que genera la configuración para el editor wp_editor
function dl_get_editor_settings($height = 75, $width = 80) {
    return array(
        'textarea_name' => 'seconddesc', // Define el nombre del textarea
        'quicktags' => array( 'buttons' => 'em,strong,link' ), // Define los botones de etiquetas rápidas disponibles
        'tinymce' => array(
            'theme_advanced_buttons1' => 'bold,italic,strikethrough,separator,bullist,numlist,separator,blockquote,separator,justifyleft,justifycenter,justifyright,separator,link,unlink,separator,undo,redo,separator',
            'theme_advanced_buttons2' => '', // Configuración de TinyMCE
        ),
        'editor_css' => "<style>#wp-excerpt-editor-container .wp-editor-area{height:{$height}px; width:{$width}%;}</style>", // CSS personalizado para el editor
    );
}

// Esta acción se dispara cuando se crea una nueva categoría de producto
add_action( 'product_cat_add_form_fields', 'dl_wc_anadir_editor_1', 10, 2 );
 
// Esta función muestra el campo de texto cuando creas la categoría
function dl_wc_anadir_editor_1() {
    ?>
    <div class="form-field">
        <label for="desc"><?php echo __( 'Descripción de abajo', 'woocommerce' ); ?></label>
       
        <?php 
        // Cargamos el editor con la configuración definida anteriormente
        wp_editor( '', 'seconddesc', dl_get_editor_settings() ); ?>
       
        <p class="description"><?php echo __( 'Este texto va en la zona de abajo de las categorías', 'woocommerce' ); ?></p>
    </div>
    <?php
}
 
// Esta acción se dispara cuando se edita una categoría de producto
add_action( 'product_cat_edit_form_fields', 'dl_wc_anadir_editor_2', 10, 2 );
 
// Esta función muestra el campo de editar texto en la edición de la categoría
function dl_wc_anadir_editor_2( $term ) {
    // Obtenemos el valor actual del segundo campo de descripción
    $second_desc = htmlspecialchars_decode( get_woocommerce_term_meta( $term->term_id, 'seconddesc', true ) );
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="second-desc"><?php echo __( 'Descripción de abajo', 'woocommerce' ); ?></label></th>
        <td>
            <?php 
            // Cargamos el editor con la configuración definida anteriormente y el valor actual de la segunda descripción
            wp_editor( $second_desc, 'seconddesc', dl_get_editor_settings(125, 100) ); ?>
       
            <p class="description"><?php echo __( 'Este texto va en la zona inferior de la categoría de productos', 'woocommerce' ); ?></p>
        </td>
    </tr>
    <?php
}

// Estas acciones se disparan cuando se guarda o se crea una categoría
add_action( 'edit_term', 'dl_wc_guardar_campo', 10, 3 );
add_action( 'created_term', 'dl_wc_guardar_campo', 10, 3 );
 
// Esta función se encarga de guardar el contenido del segundo campo de descripción
function dl_wc_guardar_campo( $term_id, $tt_id = '', $taxonomy = '' ) {
   if ( isset( $_POST['seconddesc'] ) && 'product_cat' === $taxonomy ) {
      // Actualiza el valor del segundo campo de descripción en la base de datos
      update_woocommerce_term_meta( $term_id, 'seconddesc', esc_attr( $_POST['seconddesc'] ) );
   }
}

// Esta acción se dispara después del bucle de la tienda en WooCommerce
add_action( 'woocommerce_after_shop_loop', 'dl_mostrar_desc_abajo_cat', 5 );
 
// Esta función se encarga de mostrar el texto en la categoría
function dl_mostrar_desc_abajo_cat() {
   if ( is_product_taxonomy() ) {
      // Obtenemos el término actual
      $term = get_queried_object();

      // Obtenemos el valor del segundo campo de descripción
      $second_desc = get_woocommerce_term_meta( $term->term_id, 'seconddesc', true );

      if ( $term && ! empty( $second_desc ) ) {
         // Si el término y la segunda descripción no están vacíos, mostramos la segunda descripción
         echo '<p class="term-description">' . wc_format_content( htmlspecialchars_decode( $second_desc ) ) . '</p>';
      }
   }
}
