<?php
/**
 * Plugin Name: Custom Carrier Field in Shipping Method
 */

add_filter('woocommerce_shipping_instance_form_fields_free_shipping', 'add_shipping_carrier_id_field');
add_filter('woocommerce_shipping_instance_form_fields_flat_rate', 'add_shipping_carrier_id_field');
function add_shipping_carrier_id_field($settings)
{
    $settings['shipping_carrier_id_field'] = array(
        'title' => 'Carrier ID',
        'type' => 'text',
        'placeholder' => 'Carrier ID'
    );

    return $settings;
}

add_action('woocommerce_order_status_processing', 'add_carrier_order_meta_id');
function add_carrier_order_meta_id($order_id)
{
    // Get Order Detail
    $order = wc_get_order($order_id);

    // Get Shipping Method
    $shipping_detail = $order->get_items('shipping');
    $shipping_method = reset($shipping_detail);
    $shipping_method_id = $shipping_method->get_method_id();
    $shipping_method_instance_id = $shipping_method->get_instance_id();

    // Get Carrier ID From Option
    $get_option_shipping_method = get_option('woocommerce_' . $shipping_method_id . '_' . $shipping_method_instance_id . '_settings');
    $carrier_id = $get_option_shipping_method['shipping_carrier_id_field'];

    // Add Carrie Id to Order Meta
    update_post_meta($order_id, '​_carrier_id', $carrier_id);
}