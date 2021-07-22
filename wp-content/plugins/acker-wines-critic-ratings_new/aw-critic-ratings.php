<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'AW_Critic_Rating' ) ) {

class AW_Critic_Rating {

    public static $num_of_critics = 3;

    protected static $_instance = null;

    // setup one instance of eventon
    public static function instance(){
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }


	/** Constructor. */
	public function __construct() {


        add_filter('woocommerce_product_data_tabs', array($this, 'aw_critic_ratings_settings_tabs' ));
        add_action( 'woocommerce_product_data_panels', array($this, 'aw_critic_ratings_panels' ));

        //add_action( 'woocommerce_api_create_product_attribute', array($this, 'aw_create_critic_rating_attr'));
        //add_action( 'woocommerce_after_register_taxonomy', array($this, 'aw_register_critic_rating_tax' ));
        //add_action('woocommerce_attribute_added', array($this, 'aw_create_critic_rating_attr') );

        //add_action('woocommerce_process_product_meta', array($this, 'aw_critic_ratings_save_fields'));
        //add_action('save_post_product', array($this, 'aw_critic_ratings_save_fields'));
        //add_action('woocommerce_api_edit_product_attribute',  array($this, 'aw_critic_ratings_save_fields'));
        add_action('woocommerce_process_product_meta', array($this, 'aw_critic_ratings_save_fields'), 10, 2);

	}
    public function aw_critic_ratings_shortcode( $atts ){
        $a = shortcode_atts( array(

    	), $atts );
        //$this->aw_critic_ratings_fo($a);
    }

    /*
     * Tab
     */
    //add_filter('woocommerce_product_data_tabs', 'aw_critic_ratings_settings_tabs' );
    public function aw_critic_ratings_settings_tabs( $tabs ){

    	$tabs['critic_rating'] = array(
    		'label'    => 'Critic Rating',
    		'target'   => 'critic_rating_data',
    		'priority' => 21,
    	);
    	return $tabs;

    }

    public function aw_create_critic_rating_attr(){
        $data = array(
            'attribute_label'   => 'pa_critic-rating',
            'attribute_name'    => 'critic-rating',
            'attribute_type'    => 'text',
            'attribute_type'    => 'select',
            'attribute_orderby' => 'menu_order',
            'attribute_public'  => 0, // Enable archives ==> true (or 1)
        );

        wc_create_attribute($data);
        WC_Helper_Product::create_attribute('pa_critic-rating');
    }


    public function aw_critic_ratings_get_attribute_values(){
        $taxonomy_label = 'pa_critic-rating';
        $post_id = get_the_ID();
        global $product;
        $product = wc_get_product($post_id);
        $product_id = $product->get_id();
        $attributes = $product->get_attributes();
        echo '<p>' . var_dump($attributes) . '</p>';

        $cr_terms; $cr_tax_label;

        foreach ( $attributes as $attribute ) {
            if ( $attribute->get_variation() ) {
                continue;
            }
            $name = $attribute->get_name();
            if($name == $taxonomy_label){
                //echo '<p>' . var_dump($attribute) . '</p>';

                if ( $attribute->is_taxonomy() ) {
                    $terms = wp_get_post_terms( $product_id, $name, 'all' );
                    //echo '<p>' . var_dump($terms) . '</p>';
                    $post_terms =  get_the_terms( $post_id, $name, 'all' );
                    //echo '<p>' . var_dump($post_terms) . '</p>';
                    $terms2 = wp_get_object_terms($product_id, $name, 'all' );
                    //echo '<p>' . var_dump($terms2) . '</p>';
                    $cwtax = $terms[0]->taxonomy;
                    $cw_object_taxonomy = get_taxonomy($cwtax);
                    if ( isset ($cw_object_taxonomy->labels->singular_name) ) {
                        $tax_label = $cw_object_taxonomy->labels->singular_name;
                    } elseif ( isset( $cw_object_taxonomy->label ) ) {
                        $tax_label = $cw_object_taxonomy->label;
                        if ( 0 === strpos( $tax_label, 'Product ' ) ) {
                            $tax_label = substr( $tax_label, 8 );
                        }
                    }
                    $cr_tax_label = $tax_label;
                    $cr_terms = $terms;
                }
            }
        }
        return $cr_terms;

    }


    public function aw_critic_ratings_save_terms( $post_id, $term_name, $taxonomy ){
        //echo '<p>' . var_dump($term_name) . '</p>';
        //echo '<p>' . var_dump($taxonomy) . '</p>';
        $ID = get_the_ID();
        $product = wc_get_product($ID);
        $product_id = $product->get_id();

        // $taxonomy = 'pa_years-of-construction'; // The taxonomy
        // $term_name = '2009'; // The term "NAME"
        $term_slug = sanitize_title($term_name); // The term "slug"

        // Check if the term exist and if not it create it (and get the term ID).
        if( ! term_exists( $term_name, $taxonomy ) ){
            $term_data = wp_insert_term( $term_name, $taxonomy );
            //echo '<p>' . var_dump($term_data) . '</p>';
            $term_id   = $term_data['term_id'];
        } else {
            $term_id   = get_term_by( 'name', $term_name, $taxonomy )->term_id;
        }

        $attributes = (array) $product->get_attributes();

        // 1. If the product attribute is set for the product
        if( array_key_exists( $taxonomy, $attributes ) ) {
            //echo '<p>he product attribute is set for the product</p>';
            foreach( $attributes as $key => $attribute ){
                if( $key == $taxonomy ){
                    $options = (array) $attribute->get_options();
                    $options[] = $term_id;
                    $attribute->set_options($options);
                    $attributes[$key] = $attribute;
                    break;
                }
            }
            $product->set_attributes( $attributes );
            update_post_meta( $product_id, '_product_attributes',  $attributes  );
            //echo '<p>' . var_dump($attributes) . '</p>';
        }
        // 2. The product attribute is not set for the product
        else {
            echo '<p>the product attribute is not set </p>';
            $attribute = new WC_Product_Attribute();

            $attribute->set_id( sizeof( $attributes) + 1 );
            $attribute->set_name( $taxonomy );
            $attribute->set_options( array( $term_id ) );
            $attribute->set_position( sizeof( $attributes) + 1 );
            $attribute->set_visible( true );
            $attribute->set_variation( false );
            $attributes[] = $attribute;
            //echo '<p>' . var_dump($attributes) . '</p>';
            $product->set_attributes( $attributes );
             update_post_meta( $product_id, '_product_attributes',  $attributes  );
        }

        $product->save();

        // Append the new term in the product
        if( ! has_term( $term_name, $taxonomy, $product_id )){
            wp_set_object_terms($product_id, $term_slug, $taxonomy, true );
        } else {
            wp_set_object_terms($product_id, $term_slug, $taxonomy, false );
        }
    }

    /*
     * Tab content
     */
    public function aw_critic_ratings_panels(){
        $ID = get_the_ID();
        $labels = array('critic_rating', 'critic_initials', 'rating_min', 'rating_max', 'rating_avg');
        $rating_title = $labels[0];
        $initials_title = $labels[1];
        $min_title = $labels[2];
        $max_title = $labels[3];
        $avg_title = $labels[4];
        $taxonomy_label = 'pa_critic-rating';

        $tax_initials = 'pa_' . $initials_title;
        $tax_min = 'pa_' . $min_title;
        $tax_max = 'pa_' . $max_title;
        $tax_avg = 'pa_' . $avg_title;

         echo '<div id="critic_rating_data" class="panel woocommerce_options_panel hidden">';
            $int = 0;
            for($i = 0; $i < self::$num_of_critics; $i++){
                $title_i = 'critic_rating_' . ($i+1);
                $initials_i = 'critic_initials_' . ($i+1);
                $rating_i = 'critic_rating_' . ($i+1);

                echo '<div id=' . $title_i . ' >';
                echo '<h2>Critic #' . ($i+1) . '</h2>';

                    // $cr_value = $group_term->$title_i;
                    // $cr_initials = $cr_value->{'initials'};
                    // $cr_rating = $cr_value->{'rating'};
                    woocommerce_wp_text_input( array(
                       'id'      => $initials_i,
                       'value'   => get_post_meta( get_the_ID(), $initials_i, true ),
                       'name'    => $initials_i,
                       'label'   => 'Critic Initials',
                       'desc_tip' => true,
		               'description' => 'Put wine critics initials here'
                   ) );
                   woocommerce_wp_text_input( array(
                      'id'      => $rating_i,
                      'value'   => get_post_meta( get_the_ID(), $rating_i, true ),
                      'name'    => $rating_i,
                      'label'   => 'Critic Rating',
                      'desc_tip' => true,
		              'description' => 'Put wine critics rating here'
                  ) );

                   $int++;

               echo '</div>';
            } //end for
            echo '<BR />';

         //    woocommerce_wp_text_input( array(
         //       'id'      => $min_title,
         //       'value'   => get_post_meta( get_the_ID(), $min_title, true ),
         //       'name'    => $min_title,
         //       'label'   => 'Critic Rating MIN'
         //   ) );
         //   woocommerce_wp_text_input( array(
         //      'id'      => $max_title,
         //      'value'   => get_post_meta( get_the_ID(), $max_title, true ),
         //      'name'    => $max_title,
         //      'label'   => 'Critic Rating MAX'
         //  ) );
         //  woocommerce_wp_text_input( array(
         //     'id'      => $avg_title,
         //     'value'   => get_post_meta( get_the_ID(), $avg_title, true ),
         //     'name'    => $avg_title,
         //     'label'   => 'Critic Rating Average'
         // ) );
         //    //$this->aw_critic_ratings_save_fields_test();

         echo '</div>';

        //}
    }


    public function aw_critic_ratings_calucuate($arr){
        $min_value; $max_value; $avg_value;
        $avg_arr = array();

        for($i = 0; $i < self::$num_of_critics; $i++){
            $rating_term = $arr[$i];
            array_push($avg_arr, $rating_term);
            if(!isset($min_value) || ( $min_value > $rating_term )){ $min_value =  $rating_term; }
            if(!isset($max_value) || ($max_value < $rating_term)){ $max_value =  $rating_term; }
        }
        $avg_value = ($avg_arr[0] + $avg_arr[1] + $avg_arr[2]) / 3 ;

    }
    public function aw_critic_ratings_get_min_max_values(){
        // min
        $min_term = $_POST[$min_title];
        if( isset($min_term) ){
            update_post_meta( $ID, $min_title, $min_term );
        }
        $this->aw_critic_ratings_save_terms( $post_id, $min_term, $tax_min );
        //max
        $max_term = $_POST[$max_title];
        if( isset($max_term) ){
            update_post_meta( $ID, $max_title, $max_term );
        }
        $this->aw_critic_ratings_save_terms( $post_id, $max_term, $tax_max );
        //avg
        $avg_term = $_POST[$avg_title];
        if( isset($avg_term) ){
            update_post_meta( $ID, $avg_title, $avg_term);
        }
        $this->aw_critic_ratings_save_terms( $post_id, $avg_term, $tax_avg );
    }


    //add_action('woocommerce_process_product_meta', 'aw_critic_ratings_save_fields');
    public function aw_critic_ratings_save_fields($post_id){
        $ID = get_the_ID();
        $labels = array('critic_rating', 'critic_initials', 'rating_min', 'rating_max', 'rating_avg');
        $rating_title = $labels[0];
        $initials_title = $labels[1];
        $min_title = $labels[2];
        $max_title = $labels[3];
        $avg_title = $labels[4];

        $product = wc_get_product($post_id);
        $product_id = $product->get_id();

        $tax_initials = 'pa_' . $initials_title;
        $tax_min = 'pa_' . $min_title;
        $tax_max = 'pa_' . $max_title;
        $tax_avg = 'pa_' . $avg_title;
        $args = array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'all');

        $min_value; $max_value; $avg_value;
        $avg_arr = array();
        $ratings_arr = array();
        $initials_arr = array();
        for($i = 0; $i < self::$num_of_critics; $i++){
            //$title_i = 'critic_rating_' . ($i+1);
            $initials_i = $initials_title . '_' . ($i+1);
            $rating_i = $rating_title . '_' . ($i+1);

            $initials_term = $_POST[$initials_i];
            $rating_term = $_POST[$rating_i];
            $initials_slug = sanitize_title($initials_term);
            $rating_slug = sanitize_title($rating_term);

            array_push($ratings_arr, $rating_term);
            array_push($initials_arr, $initials_term)
            //$this->aw_critic_ratings_save_terms( $post_id, $initials_term , $tax_initials );

            // update_post_meta(  get_the_ID(), $initials_term, $initials_title );
            // update_post_meta(  get_the_ID(), $rating_term, $rating_title );

            array_push($avg_arr, $rating_term);
            if(!isset($min_value) || ( $min_value > $rating_term )){ $min_value =  $rating_term; }
            if(!isset($max_value) || ($max_value < $rating_term)){ $max_value =  $rating_term; }

        }
        $avg_value = ($avg_arr[0] + $avg_arr[1] + $avg_arr[2]) / 3 ;

        update_post_meta( get_the_ID(), 'critic_ratings',  $ratings_arr);
        update_post_meta( get_the_ID(), 'critic_initials',  $initials_arr);
    	update_post_meta( get_the_ID(), 'critic_rating_min', $min_value );
        update_post_meta( get_the_ID(), 'critic_rating_max', $max_value );
        update_post_meta( get_the_ID(), 'critic_rating_avg', $avg_value );


        // $this->aw_critic_ratings_save_terms( $post_id, $min_value , $tax_min  );
        // $this->aw_critic_ratings_save_terms( $post_id, $max_value , $tax_max  );
        // $this->aw_critic_ratings_save_terms( $post_id, $avg_value , $tax_avg  );

        // update_post_meta( get_the_ID(), 'critic_initials_1', $_POST['critic_initials_1'] );
    	// update_post_meta( get_the_ID(), 'critic_rating_1', $_POST['critic_rating_1'] );
        // update_post_meta( get_the_ID(), 'critic_initials_2', $_POST['critic_initials_2'] );
    	// update_post_meta( get_the_ID(), 'critic_rating_2', $_POST['critic_rating_2'] );
        // update_post_meta( get_the_ID(), 'critic_initials_3', $_POST['critic_initials_3'] );
    	// update_post_meta( get_the_ID(), 'critic_rating_3', $_POST['critic_rating_3'] );


    }

}}
new AW_Critic_Rating();
