// Mostrar el campo de texto cuando creas la categoría
// Añadir función en el archivo functions.php
 
add_action( 'product_cat_add_form_fields', 'dl_wc_anadir_editor_1', 10, 2 );
 
function dl_wc_anadir_editor_1() {
    ?>
    <div class="form-field">
        <label for="
					desc"><?php echo __( 'Descripción de abajo', 'woocommerce' ); ?></label>
       
      <?php
      $settings = array(
         'textarea_name' => 'seconddesc',
         'quicktags' => array( 'buttons' => 'em,strong,link' ),
         'tinymce' => array(
            'theme_advanced_buttons1' => 'bold,italic,strikethrough,separator,bullist,numlist,separator,blockquote,separator,justifyleft,justifycenter,justifyright,separator,link,unlink,separator,undo,redo,separator',
            'theme_advanced_buttons2' => '',
         ),
         'editor_css' => '<style>#wp-excerpt-editor-container .wp-editor-area{height:75px; width:80%;}</style>',
      );
 
      wp_editor( '', 'seconddesc', $settings );
      ?>
       
        <p class="description"><?php echo __( 'Este texto va en la zona de abajo de las categorías', 'woocommerce' ); ?></p>
    </div>
    <?php
}
 
// Mostrar el campo de editar texto en la edición de la categoría
 
add_action( 'product_cat_edit_form_fields', 'dl_wc_anadir_editor_2', 10, 2 );
 
function dl_wc_anadir_editor_2( $term ) {
    $second_desc = htmlspecialchars_decode( get_woocommerce_term_meta( $term->term_id, 'seconddesc', true ) );
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="second-desc"><?php echo __( 'Descripción de abajo', 'woocommerce' ); ?></label></th>
        <td>
            <?php
          
         $settings = array(
            'textarea_name' => 'seconddesc',
            'quicktags' => array( 'buttons' => 'em,strong,link' ),
            'tinymce' => array(
               'theme_advanced_buttons1' => 'bold,italic,strikethrough,separator,bullist,numlist,separator,blockquote,separator,justifyleft,justifycenter,justifyright,separator,link,unlink,separator,undo,redo,separator',
               'theme_advanced_buttons2' => '',
            ),
            'editor_css' => '<style>#wp-excerpt-editor-container .wp-editor-area{height:125px; width:100%;}</style>',
         );
 
         wp_editor( $second_desc, 'seconddesc', $settings );
         ?>
       
            <p class="description"><?php echo __( 'Este texto va en la zona inferior de la categoría de productos', 'woocommerce' ); ?></p>
        </td>
    </tr>
    <?php
}
 
// Que se pueda guardar el contenido
 
add_action( 'edit_term', 'dl_wc_guardar_campo', 10, 3 );
add_action( 'created_term', 'dl_wc_guardar_campo', 10, 3 );
 
function dl_wc_guardar_campo( $term_id, $tt_id = '', $taxonomy = '' ) {
   if ( isset( $_POST['seconddesc'] ) && 'product_cat' === $taxonomy ) {
      update_woocommerce_term_meta( $term_id, 'seconddesc', esc_attr( $_POST['seconddesc'] ) );
   }
}
 
// Mostrar el texto en la categoría
 
add_action( 'woocommerce_after_shop_loop', 'dl_mostrar_desc_abajo_cat', 5 );
 
function dl_mostrar_desc_abajo_cat() {
   if ( is_product_taxonomy() ) {
      $term = get_queried_object();
      if ( $term && ! empty( get_woocommerce_term_meta( $term->term_id, 'seconddesc', true ) ) ) {
         echo '<p class="term-description">' . wc_format_content( htmlspecialchars_decode( get_woocommerce_term_meta( $term->term_id, 'seconddesc', true ) ) ) . '</p>';
      }
   }
}
