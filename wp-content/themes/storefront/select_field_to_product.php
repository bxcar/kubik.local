<?php
/**
 * WooCommerce: show all product attributes listed below each item on Cart page
 * ------
 */

function wp_woo_cart_attributes( $cart_item, $cart_item_key ) {

    $item_data = $cart_item_key['data'];
    $attributes = $item_data->get_attributes();


    if ( ! $attributes ) {
        return $cart_item;
    }

    $out = $cart_item . '<br />';

    foreach ( $attributes as $attribute ) {

        // skip variations
        if ( $attribute->get_variation() ) {
            continue;
        }
        $name = $attribute->get_name();
        if ( $attribute->is_taxonomy() ) {

            $product_id = $item_data->get_id();
            $terms = wp_get_post_terms( $product_id, $name, 'all' );

            if ( ! empty( $terms ) ) {
                if ( ! is_wp_error( $terms ) ) {

                    // get the taxonomy
                    $tax = $terms[0]->taxonomy;

                    // get the tax object
                    $tax_object = get_taxonomy($tax);

                    // get tax label
                    if ( isset ( $tax_object->labels->singular_name ) ) {
                        $tax_label = $tax_object->labels->singular_name;
                    } elseif ( isset( $tax_object->label ) ) {
                        $tax_label = $tax_object->label;
                        // Trim label prefix since WC 3.0
                        $label_prefix = 'Product ';
                        if ( 0 === strpos( $tax_label,  $label_prefix ) ) {
                            $tax_label = substr( $tax_label, strlen( $label_prefix ) );
                        }
                    }
                    $out .= $tax_label . ': ';
                    $tax_terms = array();
                    foreach ( $terms as $term ) {
                        $single_term = esc_html( $term->name );
                        array_push( $tax_terms, $single_term );
                    }
                    $out .= implode(', ', $tax_terms). '<br />';

                }
            }

        } else {

            // not a taxonomy

            $out .= $name . ': ';
            $out .= esc_html( implode( ', ', $attribute->get_options() ) ) . '<br />';
        }
    }
    echo $out;
}

add_filter( 'woocommerce_cart_item_name', 'wp_woo_cart_attributes', 10, 2 );