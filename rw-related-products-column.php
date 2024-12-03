<?php
/**
 * Plugin Name: Related Products column on admin
 * Description: Adds a non-editable "Related Products" column to the WooCommerce product list.
 * Version: 1.0.0
 * Author: Jared Nolt
 * Text Domain: rw-editable-related-products
 * related_products_view is the ACF machine name on this file. Change it to what you ACF machine name is.
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;


// Define new columns
function jsn_set_custom_columns($columns) {
    $columns['related_products_view'] = __('Related Products', 'cs-text');
    return $columns;
}
add_filter('manage_product_posts_columns', 'jsn_set_custom_columns');

// Show custom field in a new column
function jsn_custom_column($column, $post_id) {
    switch ($column) {
        case 'related_products_view': // This has to match the defined column in the function above
            $related_products = get_field('related_products_view', $post_id); // Assume this returns an array of product IDs
            if (is_array($related_products) && !empty($related_products)) {
                // Map product IDs to their titles
                $product_titles = array_map(function($product_id) {
                    return get_the_title($product_id);
                }, $related_products);

                // Display as a comma-separated list of product titles
                echo implode(', ', $product_titles);
            } else {
                echo __('No related products', 'cs-text');
            }
            break;
    }
}
add_action('manage_product_posts_custom_column', 'jsn_custom_column', 10, 2);
