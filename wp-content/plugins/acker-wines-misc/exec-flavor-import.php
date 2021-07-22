<?php
error_reporting(E_ERROR | E_PARSE);

$dir_prefix = '../../mu-plugins/acker-wines-shared/conf/';
include $dir_prefix . 'config.php';
include $dir_prefix . 'common.php';
include $dir_prefix . 'db.php';

function find_wordpress_base_path() {
    $dir = dirname(__FILE__);
    do {
        // it is possible to check for other files here
        if (file_exists($dir . "/wp-config.php")) {
            return $dir;
        }
    } while ($dir = realpath("$dir/.."));

    return NULL;
}

define('BASE_PATH', find_wordpress_base_path() . "/");
define('WP_USE_THEMES', FALSE);

global $wp, $wp_query, $wp_the_query, $wp_rewrite, $wp_did_header;
require(BASE_PATH . 'wp-load.php');

ini_set('memory_limit', '1028M');

$shortopts = "";
$longopts = array("ID:", "attrs:");
$options = getopt($shortopts, $longopts);

$p = $options['ID'];
$a = explode(',', $options['attrs']);

function aw_import_set_flavor_attribute($ID, $attribute_array) {
    global $product;
    $product = wc_get_product($ID);
    $product_id = $product->id;
    $attributes = (array) $product->get_attributes();

    $taxonomy = 'pa_flavor';
    foreach($attribute_array as $position => $term_name){
        $term_slug = sanitize_title($term_name);

        // Check if the term exist and if not it create it (and get the term ID).
        if( ! term_exists( $term_name, $taxonomy ) ){
            //aw_logMessage("Term {$term_name} not found.  Creating...");
            $term_data = wp_insert_term( $term_name, $taxonomy );
            //echo '<p>' . var_dump($term_data) . '</p>';
            $term_id   = $term_data->term_id;
            //aw_logMessage("Created term '{$term_name}' with id {$term_id}");
        } else {
            $term_id   = get_term_by( 'name', $term_name, $taxonomy )->term_id;
            //aw_logMessage("Term '{$term_name}' exists with id {$term_id}");
        }

        // 1. If the product attribute is set for the product
        if ( array_key_exists( $taxonomy, $attributes ) ) {
            //aw_logMessage('the product attribute is set for the product');
            foreach ( $attributes as $key => $attribute ){
                if ( $key == $taxonomy ){
                    $options = (array) $attribute->get_options();
                    $options[] = $term_id;
                    $attribute->set_options($options);
                    $attributes[$key] = $attribute;
                    break;
                }
            }
        }
        // 2. The product attribute is not set for the product
        else {
            $attribute = new WC_Product_Attribute();

            $attribute->set_id( $term_id );
            $attribute->set_name( $taxonomy );
            $attribute->set_options( array( $term_id ) );
            $attribute->set_position( $position );
            $attribute->set_visible( true );
            $attribute->set_variation( false );
            $attributes[] = $attribute;
        }

        // Append the new term in the product
        $term_taxonomy_ids = wp_set_object_terms($product_id, $term_slug, $taxonomy, true );
        if ( is_wp_error( $term_taxonomy_ids ) ) {
            // There was an error somewhere and the terms couldn't be set.
            aw_logMessage('ERROR');
        }
    }

    $product->set_attributes( $attributes );
    $product->save();
}

aw_import_set_flavor_attribute($p, $a);

 ?>
