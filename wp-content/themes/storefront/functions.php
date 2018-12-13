<?php
/**
 * Storefront engine room
 *
 * @package storefront
 */

/**
 * Assign the Storefront version to a var
 */
$theme = wp_get_theme('storefront');
$storefront_version = $theme['Version'];

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if (!isset($content_width)) {
    $content_width = 980; /* pixels */
}

$storefront = (object)array(
    'version' => $storefront_version,

    /**
     * Initialize all the things.
     */
    'main' => require 'inc/class-storefront.php',
    'customizer' => require 'inc/customizer/class-storefront-customizer.php',
);

require 'inc/storefront-functions.php';
require 'inc/storefront-template-hooks.php';
require 'inc/storefront-template-functions.php';

if (class_exists('Jetpack')) {
    $storefront->jetpack = require 'inc/jetpack/class-storefront-jetpack.php';
}

if (storefront_is_woocommerce_activated()) {
    $storefront->woocommerce = require 'inc/woocommerce/class-storefront-woocommerce.php';

    require 'inc/woocommerce/storefront-woocommerce-template-hooks.php';
    require 'inc/woocommerce/storefront-woocommerce-template-functions.php';
}

if (is_admin()) {
    $storefront->admin = require 'inc/admin/class-storefront-admin.php';

    require 'inc/admin/class-storefront-plugin-install.php';
}

/**
 * NUX
 * Only load if wp version is 4.7.3 or above because of this issue;
 * https://core.trac.wordpress.org/ticket/39610?cversion=1&cnum_hist=2
 */
if (version_compare(get_bloginfo('version'), '4.7.3', '>=') && (is_admin() || is_customize_preview())) {
    require 'inc/nux/class-storefront-nux-admin.php';
    require 'inc/nux/class-storefront-nux-guided-tour.php';

    if (defined('WC_VERSION') && version_compare(WC_VERSION, '3.0.0', '>=')) {
        require 'inc/nux/class-storefront-nux-starter-content.php';
    }
}

/**
 * Note: Do not add any custom code here. Please use a custom plugin so that your customizations aren't lost during updates.
 * https://github.com/woocommerce/theme-customisations
 */


//remove_action('storefront_before_content', 'woocommerce_breadcrumb', 10);
//remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 10);
//remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);

//add_action('woocommerce_before_shop_loop', 'woocust_test', 40);

add_filter('woocommerce_checkout_fields', 'custom_override_checkout_fields');

function custom_override_checkout_fields($fields)
{

//    unset($fields['billing']['billing_first_name']);
    unset($fields['billing']['billing_last_name']);
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_address_1']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_city']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_country']);
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['ship_to_different_address']);
//    unset($fields['billing']['billing_phone']);
//    unset($fields['order']['order_comments']);
//    unset($fields['billing']['billing_email']);
    unset($fields['account']['account_username']);
    unset($fields['account']['account_password']);
    unset($fields['account']['account_password-2']);

    unset($fields['billing']['billing_country']);  //удаляем! тут хранится значение страны оплаты
    unset($fields['shipping']['shipping_country']); //удаляем! тут хранится значение страны доставки
    return $fields;
}

/**
 * Add custom field to the checkout page
 */

add_action('woocommerce_after_order_notes', 'custom_checkout_field');

function custom_checkout_field($checkout)

{

    echo '<div id="custom_checkout_field"><h2>' . __('Описание') . '</h2>';

    woocommerce_form_field('custom_description', array(

        'type' => 'text',

        'class' => array(

            'my-field-class form-row-wide'

        ),

        'label' => __(''),

        'placeholder' => __('Описание'),

    ),

        $checkout->get_value('custom_description'));

    echo '</div>';


    for ($i = 1; $i <= 19; $i++) {
        woocommerce_form_field('custom_img_' . $i, array(

            'type' => 'text',

            'class' => array(

                'my-field-class form-row-wide'

            ),

            'label' => __(''),

            'placeholder' => __(''),

        ),

            $checkout->get_value('custom_img_' . $i));
    }


    woocommerce_form_field('custom_background', array(

        'type' => 'text',

        'class' => array(

            'my-field-class form-row-wide'

        ),

        'label' => __(''),

        'placeholder' => __(''),

    ),

        $checkout->get_value('custom_background'));


}


/**
 * Checkout Process
 */

add_action('woocommerce_checkout_process', 'customised_checkout_field_process');

function customised_checkout_field_process()

{

// Show an error message if the field is not set.

    if (!$_POST['custom_description']) wc_add_notice(__('Please enter value!'), 'error');
    for ($i = 1; $i <= 9; $i++) {
        if (!$_POST['custom_img_' . $i]) wc_add_notice(__('Please enter value!'), 'error');
    }
//    if (!$_POST['custom_background']) wc_add_notice(__('Please enter value!') , 'error');

}

/**
 * Update the value given in custom field
 */

add_action('woocommerce_checkout_update_order_meta', 'custom_checkout_field_update_order_meta');

function custom_checkout_field_update_order_meta($order_id)

{

    if (!empty($_POST['custom_description'])) {

        /*update_post_meta($order_id, 'test_description',sanitize_text_field($_POST['custom_description']));*/
        update_post_meta($order_id, 'custom_description', $_POST['custom_description']);

    }

    for ($i = 1; $i <= 19; $i++) {
        if (!empty($_POST['custom_img_' . $i])) {
            update_post_meta($order_id, 'custom_img_' . $i, $_POST['custom_img_' . $i]);

        }
    }

    if (!empty($_POST['custom_background'])) {
        update_post_meta($order_id, 'custom_background', $_POST['custom_background']);

    }

}


/**
 * Outputs a rasio button form field
 */
function woocommerce_form_field_radio($key, $args, $value = '')
{
    global $woocommerce;
    $defaults = array(
        'type' => 'radio',
        'label' => '',
        'placeholder' => '',
        'required' => false,
        'class' => array(),
        'label_class' => array(),
        'return' => false,
        'options' => array()
    );
    $args = wp_parse_args($args, $defaults);
    if ((isset($args['clear']) && $args['clear']))
        $after = '<div class="clear"></div>';
    else
        $after = '';
    $required = ($args['required']) ? ' <abbr class="required" title="' . esc_attr__('required', 'woocommerce') . '">*</abbr>' : '';
    switch ($args['type']) {
        case "select":
            $options = '';
            if (!empty($args['options']))
                $i = 1;
            foreach ($args['options'] as $option_key => $option_text) {
                if ($i == 3) {
                    $options .= '<input type="radio" name="' . $key . '" id="' . $key . '" value="' . $option_key . '" ' . selected($value, $option_key, false) . 'class="select"><br>' . $option_text . '' . "\r\n";
                } else {
                    $options .= '<input type="radio" name="' . $key . '" id="' . $key . '" value="' . $option_key . '" ' . selected($value, $option_key, false) . 'class="select">' . $option_text . '' . "\r\n";
                }
                $i++;
            }
            $field = '<p class="form-row ' . implode(' ', $args['class']) . '" id="' . $key . '_field">
<label for="' . $key . '" class="' . implode(' ', $args['label_class']) . '">' . $args['label'] . $required . '</label>
' . $options . '
</p>' . $after;
            break;
    } //$args[ 'type' ]
    if ($args['return'])
        return $field;
    else
        echo $field;
}

/**
 * Add the field to the checkout
 **/
add_action('woocommerce_after_checkout_billing_form', 'hear_about_us_field', 10);
function hear_about_us_field($checkout)
{

    woocommerce_form_field_radio('hear_about_us', array(
        'type' => 'select',
        'class' => array(
            'here-about-us form-row-wide'
        ),
        'label' => __(''),
        'placeholder' => __(''),
        'required' => false,
        'options' => array(
            '1' => '',
            '2' => '',
            '3' => '',
            '4' => '',
            '5' => '',
            '6' => ''

        )
    ), $checkout->get_value('hear_about_us'));
}

/**
 * Process the checkout
 **/
add_action('woocommerce_checkout_process', 'my_custom_checkout_field_process');
function my_custom_checkout_field_process()
{
    global $woocommerce;
    // Check if set, if its not set add an error.
    /*if ( !$_POST[ 'hear_about_us' ] )
        $woocommerce->add_error( __( 'Please enter something into this new shiny field.' ) );*/
}

/**
 * Update the order meta with field value
 **/
add_action('woocommerce_checkout_update_order_meta', 'hear_about_us_field_update_order_meta');
function hear_about_us_field_update_order_meta($order_id)
{
    if ($_POST['hear_about_us'])
        update_post_meta($order_id, 'Background', esc_attr($_POST['hear_about_us']));
}

include "select_field_to_product.php";