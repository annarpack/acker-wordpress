<?php
/**
 * The template for displaying search forms.
 *
 * @package Acker Wines Search Content
 */

class AW_Search_Form {

    public function __construct() {
        wp_localize_script( 'ajax-search', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
        add_shortcode('aw_search_form', array($this,'aw_search_form_shortcode'));
        add_action( 'wp_ajax_aw_show_search_results', array($this,'aw_show_search_results' ));
        add_action( 'wp_ajax_nopriv_aw_show_search_results', array($this,'aw_show_search_results' ));
    }

    public function aw_search_form_shortcode( $atts ){
        $a = shortcode_atts( array(
    		'query' => 'query',
            'option' => 'option'
    	), $atts );
        $this->aw_get_search_form($a);
    }

    public function aw_load_search_results($s, $option){
         //echo '<p>' . var_dump($s) . '</p>';
         //echo '<p>' . var_dump($option) . '</p>';
         $labelArr = [ 'All', 'Retail', 'Auction', 'Events', 'Blogs',  'FAQ' ];
         $valueArr = [ 'all', 'product', 'auction', 'event', 'post', 'faq' ];
         $post = array('post', 'page');
         $all = array('post', 'page', 'product', 'event', 'faq');
         $index = array_search($option, $labelArr);
         //echo '<p>' . var_dump($index) . '</p>';
         $category = $valueArr[$index];
         //echo '<p>' . var_dump($category) . '</p>';
         // if( $option == 'post' ){
         //     echo '<p>No Search Results for ' . $s . ' in ' . $option . '</p>';
         // }
         $args = array(
              's' => $s,
              'posts_per_page' => -1
         );
         if(isset($category)){
             $args = array(
                  's' => $s,
                  'post_type' => $category,
                  'posts_per_page' => -1
             );
         }
         if($category == 'post'){
             $args = array(
                 's' => $s,
                 'post_type' => array('post', 'page'),
                 'posts_per_page' => -1
             );
         }
         if($category == 'product'){
             $args = array(
                 's' => $s,
                 'post_type' => 'product',
                 'posts_per_page' => -1
             );
             global $product;
         }
         if($category == 'all'){
             $args = array(
                 's' => $s,
                 'post_type' => array('post', 'page', 'product'),
                 'posts_per_page' => -1
             );
         }
         //echo '<p>' . var_dump($args) . '</p>';
        $form_query = new WP_Query( $args );
        $q = $form_query->{'query_vars'};
        $q_post_count = $form_query->{'post_count'};
             //else {
                 echo '<div id="searchform-results-content">';
                     echo '<h3 class="results">Search results for `' . $s . '`</h3>';
                    echo ' <div id="search-results" data-id=' . $option . ' > ';
                         if($form_query->have_posts()){
                            //<h3> //echo $labelArr[$index];</h3> -->
                             echo '<ul>';
                                 while($form_query->have_posts()) : $form_query->the_post();
                                 echo '<li><a href="' . the_permalink() . '" title="' . the_title_attribute() . '" rel="bookmark"> ' . the_title() . ' </a></li>';
                                 endwhile;
                             echo '</ul> ';
                         }
                         else {
                             echo '<p>No Search Results for ' . $s . ' in ' . $option . '</p>';
                         }

                     echo '</div>';
                      echo '<div class="show-more">';
                          echo '<a href=" ' . home_url() . '/?=' . $s . ' " >';
                         echo 'Show More Results (' . $q_post_count . ')';
                        echo '</a>';
                     echo '</div>';
                 echo '</div>';


     }

    public function aw_get_search_form($a){
        $query = $a['query'];
        $option = $a['option'];

        echo '<div class="searchform-wrapper">';
            echo '<div id="searchform-container">';
                echo '<div id="searchform-filter-button">';
                    echo '<span class="search-filter-facade" style="width: auto;"></span>';
                    echo '<i class="search-icon"> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M143 352.3L7 216.3c-9.4-9.4-9.4-24.6 0-33.9l22.6-22.6c9.4-9.4 24.6-9.4 33.9 0l96.4 96.4 96.4-96.4c9.4-9.4 24.6-9.4 33.9 0l22.6 22.6c9.4 9.4 9.4 24.6 0 33.9l-136 136c-9.2 9.4-24.4 9.4-33.8 0z"/></svg></i>';
                echo '</div>';
             echo '<form method="get" class="aw-searchform" id="aw-searchform" action="' . esc_url( home_url( '/' ) ) . '">';
                 echo '<select id="search-filter-options">';
                     echo '<option data-value="all" class="filter-button">All</a>';
                     echo '<option data-value="product" class="filter-button">Retail</a>';
                     echo '<option data-value="auction" class="filter-button">Auction</a>';
                    echo '<option data-value="events" class="filter-button">Events</a>';
                    echo ' <option data-value="faq" class="filter-button">FAQ</a>';
                    echo '<option data-value="posts" class="filter-button">Blog</a>';
                echo '</select>';

                echo '<input type="text" class="field" name="s" id="s" placeholder="Search" />';
            echo '</form>';
             echo '<div id="searchform-submit">';
                echo '<svg id="search-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"/></svg>';
                echo '<svg id="dropdown-loading" width="200px"  height="200px"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">';
                    echo '<circle cx="50" cy="50" fill="none" ng-attr-stroke="{{config.color}}" ng-attr-stroke-width="{{config.width}}" ng-attr-r="{{config.radius}}" ng-attr-stroke-dasharray="{{config.dasharray}}" stroke="#7a7a7a" stroke-width="12" r="35" stroke-dasharray="164.93361431346415 56.97787143782138">';
                        echo '<animateTransform attributeName="transform" type="rotate" calcMode="linear" values="0 50 50;360 50 50" keyTimes="0;1" dur="1s" begin="0s" repeatCount="indefinite"></animateTransform>';
                    echo '</circle>';
                echo '</svg>';
            echo '</div>';
             echo '<div id="searchform-dropdown-wrapper">';
                echo '<div id="searchform-dropdown-filter-tabs" class="tabs">';
                        echo '<div class="tab">';
                            echo '<label for="product" data-value="product" > Retail </label>';
                            echo '<input type="radio" name="product" />';
                        echo '</div>';
                        echo '<div class="tab">';
                            echo '<label for="auction" data-value="auction" > Auction </label>';
                            echo '<input type="radio" name="auction" />';
                        echo '</div>';
                        echo '<div class="tab">';
                            echo '<label for="events" data-value="events" > Events </label>';
                            echo '<input type="radio" name="events" />';
                        echo '</div>';
                        echo '<div class="tab">';
                            echo '<label for="faq" data-value="faq" > FAQ </label>';
                            echo '<input type="radio" name="faq" />';
                        echo '</div>';
                        echo '<div class="tab">';
                            echo '<label for="posts" data-value="posts" > Blog </label>';
                            echo '<input type="radio" name="posts" />';
                        echo '</div>';
                echo '</div>';
                 echo '<div id="searchform-results-dropdown">';
                     $this->aw_load_search_results($query, $option);
                echo '</div>';
            echo '</div>';
        echo '</div>';

    }

    public function aw_show_search_results(){
        $query = $_POST['query'];
        $option = $_POST['option'];
        //echo '<p>' . var_dump($query) . '</p>';
        //echo '<p>' . var_dump($option) . '</p>';
        $this->aw_load_search_results($query, $option);
    }
}

new AW_Search_Form();
