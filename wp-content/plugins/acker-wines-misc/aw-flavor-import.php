<?php
/*
Plugin Name: Acker Wines // Import Flavors File Import
Plugin URI: https://www.ackerwines.com/
Description: Acker Wines WooCommerce
Version: 1.0
Author: Acker Wines // Anna
Author URI: https://www.ackerwines.com/
*/

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

function aw_import_get_wine_attr_list(){
    $headings_array = array("SKU", "Vintage", "Producer", "Vineyard", "Designation", "Color", "Region", "Varietal", "Intensity", "Age", "Fruit 1", "Fruit 2", "Fruit 3","Fruit Character","Non Fruit 1", "Non Fruit 2", "Non Fruit 3", "Non Fruit 4", "Earth", "Mineral", "Wood Character", "Tannin", "Acid", "Alcohol","Body", "Texture" );
    return $headings_array;
} // end func get wine attr
function aw_import_get_flavor_search_attr_list(){
    $wine_attributes = array("Fruit 1", "Fruit 2", "Fruit 3","Fruit Character","Non Fruit 1", "Non Fruit 2", "Non Fruit 3", "Non Fruit 4", "Earth", "Mineral", "Wood Character");
    return $wine_attributes;
}
function aw_import_get_flavor_attr_list(){
    $flavor_attr = array('fruit-1', 'fruit-2', 'fruit-3', 'fruit-character', 'non-fruit-1', 'non-fruit-2', 'non-fruit-3', 'non-fruit-4', 'earth', 'mineral', 'wood');
    //$flavor_attr = array{"SKU", "Vintage", "Producer", "Vineyard", "Designation", "Color", "Region", "Varietal", "Intensity", "Age", "Fruit 1", "Fruit 2", "Fruit 3","Fruit Character","Non Fruit 1", "Non Fruit 2", "Non Fruit 3", "Non Fruit 4", "Earth", "Mineral", "Wood Character"};
    return $flavor_attr;
}//end get flavor attr list

function aw_import_find_col_match($headings, $column_counter, $flavor_counter, $item, $wine_attr){
    $attr_array = aw_import_get_wine_attr_list();
    $flavor_headings_array = aw_import_get_flavor_search_attr_list();
    if( $attr_array == $headings){
        $col_head = strval($attr_array[$column_counter]);
        $flavor_head = strval($flavor_headings_array[$flavor_counter]);
        //aw_logMessage('flavor header: ' . $flavor_head);
        //aw_logMessage('col header: ' . $col_head);
        if( $col_head == $flavor_head ){
            //aw_logMessage('yes, is a column match!');
            return true;
        } else { //aw_logMessage('not a column match');
            return false;
        }
    } else {
        echo 'check column header titles on csv file!\n';
    }
}
// function aw_import_set_flavor_attribute($product, $attribute_array) {
//     //aw_logMessage("aw_import_set_flavor_attribute");
//     echo '<p>' . var_dump($attribute_array) . '</p>';
//     $product_id = $product->id;
//     $attributes = (array) $product->get_attributes();
//     //echo '<p>' . var_dump($attributes) . '</p>';
//
//     $taxonomy = 'pa_flavor';
//     foreach($attribute_array as $position => $term_name){
//         $term_slug = sanitize_title($term_name);
//
//         // Check if the term exist and if not it create it (and get the term ID).
//         if( ! term_exists( $term_name, $taxonomy ) ){
//             aw_logMessage("Term {$term_name} not found.  Creating...");
//             $term_data = wp_insert_term( $term_name, $taxonomy );
//             echo '<p>' . var_dump($term_data) . '</p>';
//             $term_id   = $term_data->term_id;
//             aw_logMessage("Created term '{$term_name}' with id {$term_id}");
//         } else {
//             $term_id   = get_term_by( 'name', $term_name, $taxonomy )->term_id;
//             aw_logMessage("Term '{$term_name}' exists with id {$term_id}");
//         }
//
//         // 1. If the product attribute is set for the product
//         if ( array_key_exists( $taxonomy, $attributes ) ) {
//             aw_logMessage('the product attribute is set for the product');
//             foreach ( $attributes as $key => $attribute ){
//                 if ( $key == $taxonomy ){
//                     $options = (array) $attribute->get_options();
//                     $options[] = $term_id;
//                     $attribute->set_options($options);
//                     $attributes[$key] = $attribute;
//                     break;
//                 }
//             }
//         }
//         // 2. The product attribute is not set for the product
//         else {
//             aw_logMessage('the product attribute is NOT set for the product');
//             $attribute = new WC_Product_Attribute();
//
//             $attribute->set_id( $term_id );
//             $attribute->set_name( $taxonomy );
//             $attribute->set_options( array( $term_id ) );
//             $attribute->set_position( $position );
//             $attribute->set_visible( true );
//             $attribute->set_variation( false );
//             $attributes[] = $attribute;
//         }
//
//         // Append the new term in the product
//         $term_taxonomy_ids = wp_set_object_terms($product_id, $term_slug, $taxonomy, true );
//         if ( is_wp_error( $term_taxonomy_ids ) ) {
//             // There was an error somewhere and the terms couldn't be set.
//             aw_logMessage('ERROR');
//         }
//     }
//
//     $product->set_attributes( $attributes );
//     $product->save();
// }
function aw_import_get_flavor_data($item, $headings){
    //aw_logMessage("w_import_get_flavor_data");
    $column_counter = 0;
    $attr_array = aw_import_get_wine_attr_list();
    if( $attr_array == $headings){
        $flavor_headings_array = aw_import_get_flavor_search_attr_list();
        $first_flavor = $flavor_headings_array[0];
        $max_index = sizeof($flavor_headings_array);
        $last_flavor = $flavor_headings_array[($max_index-1)];
        $last_flavor_index = array_search($last_flavor, $attr_array);
        if(in_array($first_flavor, $attr_array)){
            $first_index = array_search($first_flavor, $attr_array);
            $flavor_count_min = $first_index - 1;
            $flavor_count_max = $last_flavor_index+1;
        }

        $attribute_array = array();
        foreach ($item as $wine_attr) {
            //echo '<p>wine attr ' . var_dump($wine_attr) . '</p>';
            $flavor_counter = $column_counter - $first_index;
            if(($flavor_count_max > $column_counter) && ($column_counter > $flavor_count_min)){
                $match = aw_import_find_col_match($headings, $column_counter, $flavor_counter, $item, $wine_attr);
                if($match == true){
                    if(isset($wine_attr) && $wine_attr != ''){
                        $i = 1;
                        $wine_attr_title = str_replace(" ", "-" , $wine_attr, $i);
                        $l = strlen($wine_attr_title);
                        if(strpos($wine_attr_title, $l) == "-"){
                            $wine_attr_title = chop($wine_attr_title, '-');
                        }
                        array_push($attribute_array, $wine_attr_title);
                    } //
                } //end if match = true
                if($match == false){ return; }
            }// if within flavor range
            $column_counter++;
        } // end for each
        //echo '<p>attr array ' . var_dump($attribute_array) . '</p>';
        if(sizeof($attribute_array) > 5){
            return $attribute_array;
        } else {
            echo 'need more attributes!\n';
        }

    } else {
        echo 'check column header titles on csv file!';
    }
}

function aw_import_load_wine_data(){
    aw_logStart("Flavor Picker file upload");
    $shortopts = "";
    $longopts = array("input-file:");
    $options = getopt($shortopts, $longopts);
    // Validate parameters
    $input_file = aw_validateValue($options, "input-file");

    if (empty($input_file)) {
        aw_logMessage("Must specify an input file!");
        die(1);
    }

    if (!file_exists($input_file)) {
        aw_logMessage("Input file not found!");
        die(1);
    }
    aw_logMessage("Load wine data");
    $sku_list = array(); $csv_data = array(); $headings = array();
    $first_row = TRUE;
    $handle = fopen($input_file, "r");
    if ($handle) {
        while (($data = fgetcsv($handle)) !== FALSE) {
            if ($first_row) {
                $headings = $data;
                $first_row = FALSE;
            } else {
                $csv_sku = intval($data[0]);
                if($csv_sku && is_numeric($csv_sku)){
                    $csv_data[$csv_sku] = $data;
                    array_push( $sku_list, $csv_sku);
                }
            }
        }
        fclose($handle);
    } else {
        aw_logMessage("Unable to open input file for processing!");
        die(1);
    }


    $args = array(
        'post_type' => 'product',
        'order' => 'ASC',
        'paging' => false,
        'posts_per_page' => 4000,
        'nopaging' => true
    );
    $loop = new WP_Query($args);
    $wines = 0;
    if ($loop->have_posts()) {
        while ($loop->have_posts()) {
            $loop->the_post();
            global $product;
            $ID = get_the_ID();
            $the_product = wc_get_product($ID);
            $sku = $the_product->get_sku();
            if($sku != NULL){$sku = intval($sku);}
            if( in_array($sku, $sku_list) ){
                $index = array_search($sku, $sku_list);
                if($index != NULL){
                    $item = $csv_data[$sku];
                    //echo '<p>' . var_dump($item) . '</p>';
                    $csv_sku = $item[0];
                    if($csv_sku != NULL){$csv_sku = intval($csv_sku);}
                    aw_logStart("Process wine $ID with SKU $sku and CVSKU $csv_sku");
                    if( $csv_sku == $sku ){
                        echo " ==>  " . the_title() . " is $" . $product->get_price() . "\n";
                        $flavor_attributes = aw_import_get_flavor_data($item, $headings);
                        echo '<p>' . var_dump($flavor_attributes) . '</p>';
                        //if(isset($flavor_attributes) && (sizeof($flavor_attributes) > 5)){
                            //$attrs = implode(',', $flavor_attributes);
                            //$return = shell_exec('php exec-flavor-import.php --ID=' . $ID . ' --attrs=' . $attrs);
                        //}
                    }
                }
                $wines++;
            }
            // else {
            //     aw_logMessage('No products found');
            // }

            aw_logEnd();
            wp_reset_postdata();

            if($wines == 30){ break; }
        } // end while
    } // end if has posts


}
aw_import_load_wine_data();
?>
