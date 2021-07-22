<?php
/**
 * The template for displaying search page.
 *
 * @package Acker Wines Search Content
 */
 class AW_Search_Page {

    public function __construct() {
        wp_localize_script( 'ajax-search', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
        add_shortcode('aw_search_page', array($this, 'aw_search_page_shortcode'));
        add_action( 'wp_ajax_aw_update_search_results_page', array($this,'aw_update_search_results_page' ));
        add_action( 'wp_ajax_nopriv_aw_update_search_results_page', array($this,'aw_update_search_results_page' ));
    }
    public function aw_search_page_shortcode( $atts ){
        $a = shortcode_atts( array(
            'query' => 'query',
            'option' => 'all'
        ), $atts );
        $this->aw_get_search_results_page($a);
    }
    public function aw_get_query_args($s, $value){
        $args = array(
             's' => $s
        );
        if(isset($value)){
            $args = array(
                 's' => $s,
                 'post_type' => $value
            );
        }
        if($value == 'all'){
            $args = array(
                's' => $s,
                'post_type' => array('post', 'page', 'product')
            );
        }
        if($value == 'post'){
            $args = array(
                's' => $s,
                'post_type' => array('post', 'page')
            );
        }
        if($value == 'product'){
            $args = array(
                's' => $s,
                'post_type' => 'product'
            );
            global $product;
        }
        return $args;
    }

    public function aw_has_no_posts($s, $category){
        echo '<p>No Search Results for `' . $s . '` in ' . $category . '</p>';
        // add contact form 7 shortcode for form to request a product
        //do_shortcode('[contact-form-7 id="6897" title="Request Item (no search results)"]');
    }
    public function aw_get_query($s, $value){
        $labelArr = [ 'Retail', 'Auction', 'Events', 'Site Content',  'FAQ' ];
        $valueArr = [ 'product', 'auction', 'event', 'post', 'faq' ];
        //echo '<p>' . var_dump($value) . '</p>';
        $post = array('post', 'page');
        //$all = array('post', 'page', 'product', 'event', 'faq');
        $index = array_search($value, $valueArr);
        $product_types = ['shop_order', 'auction', 'tickets', 'shop_subscription'];
        $product_titles = ['Wines', 'Auction', 'Tickets', 'Wine Club'];
        $args = $this->aw_get_query_args($s, $value);
        //echo '<p>' . var_dump($args) . '</p>';
        $post_query = new WP_Query( $args );
        if($post_query->have_posts()) {
            $length = 30;
            ?>
            <section class="search-section-<?php echo $value; ?>">
            <h1><?php echo $labelArr[$index]; ?></h1>
            <?php
            while($post_query->have_posts()) {
                $post_query->the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <div class="search-entry-content clr">
                        <p class="search-entry-title entry-title">
                            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a>
                        </p>
                        <?php if( has_post_thumbnail() ) { ?>
                        <div class="thumbnail">
                            <a href="<?php the_permalink(); ?>" class="thumbnail-link">
                                <div class="woo-entry-image clr">
                                    <?php do_action( 'ocean_before_product_entry_image' ); ?>
                                    <a href="<?php the_permalink(); ?>" class="woocommerce-LoopProduct-link">
                                        <?php
                                        // Single Image
                                        echo the_post_thumbnail( 'thumbnail' ); ?>
                                    </a>
                                    <?php do_action( 'ocean_after_product_entry_image' ); ?>
                                </div><!-- .woo-entry-image -->
                            </a>
                        </div><!-- .thumbnail -->
                        <div class="has-thumb search-entry-summary clr">
                            <p>
                            <?php the_excerpt();  //woocommerce_template_single_excerpt(); ?>
                            </p>
                        </div><!-- .search-entry-summary -->
                        <?php } // end if has thumbnail
                        else {
                        ?>
                        <div class="search-entry-summary clr">
                            <p>
                            <?php the_excerpt();  //woocommerce_template_single_excerpt(); ?>
                            </p>
                        </div><!-- .search-entry-summary -->

                        <?php } // end else ?>
                    </div><!-- .search-entry-content -->
                    <?php if($value == 'product'){ ?>
                    <div class="search-inner-retail" >
                        <?php
                        woocommerce_template_loop_price();
                        woocommerce_template_loop_add_to_cart();
                        ?>
                    </div><!-- .search-entry-retail -->
                    <?php } // end if option
                    ?>
                </article> <?php
                } // end while
            ?>
            </section>
            <?php
        } //if has posts
    }
    public function aw_show_search_results_page($s, $option){
        $labelArr = [ 'All', 'Retail', 'Auction', 'Events', 'Site Content',  'FAQ' ];
        $valueArr = [ 'all', 'product', 'auction', 'event', 'post', 'faq' ];
        $catArr = ['product', 'auction', 'event', 'post', 'faq' ];

        $index = array_search($option, $labelArr);
        //echo '<p>' . var_dump($index) . '</p>';
        $category = $valueArr[$index];
        //echo '<p>' . var_dump($category) . '</p>';
        $args = $this->aw_get_query_args($s, $category);
        $has_query = new WP_Query( $args );
        if($has_query->have_posts()) {
            //echo '<p>option inside show search results' . var_dump($category) . '</p>';

            if($category == 'all'){
                foreach( $catArr as $value ){
                    $this->aw_get_query($s, $value);

                } // end foreach
            } // end if option = 'all'
            else {
                if(isset($category)){
                    $this->aw_get_query($s, $category, $valueArr, $labelArr);
                } //end if
            } // end else
        } // end if has query
        else {
            //echo 'has no query';
            $this->aw_has_no_posts($s, $category);
        }
    }

    public function aw_get_search_results_page($a){
        $query = $a['query'];
        $option = $a['option'];
        ?>
        <h1>Search Results Page</h1>
        <div id="page-loading">
            <svg width="200px"  height="200px"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="lds-ellipsis" style="background: rgba(0, 0, 0, 0) none repeat scroll 0% 0%;"><!--circle(cx="16",cy="50",r="10")--><circle cx="16" cy="50" r="10" fill="#a22e29"><animate attributeName="r" values="12;0;0;0;0" keyTimes="0;0.25;0.5;0.75;1" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" calcMode="spline" dur="2.6s" repeatCount="indefinite" begin="0s"></animate><animate attributeName="cx" values="84;84;84;84;84" keyTimes="0;0.25;0.5;0.75;1" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" calcMode="spline" dur="2.6s" repeatCount="indefinite" begin="0s"></animate></circle><circle cx="16" cy="50" r="10" fill="#f7943c"><animate attributeName="r" values="0;12;12;12;0" keyTimes="0;0.25;0.5;0.75;1" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" calcMode="spline" dur="2.6s" repeatCount="indefinite" begin="-1.3s"></animate><animate attributeName="cx" values="16;16;50;84;84" keyTimes="0;0.25;0.5;0.75;1" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" calcMode="spline" dur="2.6s" repeatCount="indefinite" begin="-1.3s"></animate></circle><circle cx="16" cy="50" r="10" fill="#f24a3c"><animate attributeName="r" values="0;12;12;12;0" keyTimes="0;0.25;0.5;0.75;1" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" calcMode="spline" dur="2.6s" repeatCount="indefinite" begin="-0.65s"></animate><animate attributeName="cx" values="16;16;50;84;84" keyTimes="0;0.25;0.5;0.75;1" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" calcMode="spline" dur="2.6s" repeatCount="indefinite" begin="-0.65s"></animate></circle><circle cx="16" cy="50" r="10" fill="#a22e29"><animate attributeName="r" values="0;12;12;12;0" keyTimes="0;0.25;0.5;0.75;1" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" calcMode="spline" dur="2.6s" repeatCount="indefinite" begin="0s"></animate><animate attributeName="cx" values="16;16;50;84;84" keyTimes="0;0.25;0.5;0.75;1" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" calcMode="spline" dur="2.6s" repeatCount="indefinite" begin="0s"></animate></circle><circle cx="16" cy="50" r="10" fill="#a22e29"><animate attributeName="r" values="0;0;12;12;12" keyTimes="0;0.25;0.5;0.75;1" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" calcMode="spline" dur="2.6s" repeatCount="indefinite" begin="0s"></animate><animate attributeName="cx" values="16;16;16;50;84" keyTimes="0;0.25;0.5;0.75;1" keySplines="0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1;0 0.5 0.5 1" calcMode="spline" dur="2.6s" repeatCount="indefinite" begin="0s"></animate></circle></svg>
        </div><BR />
        <div id="searchpage-results-content">
            <?php
            $this->aw_show_search_results_page($query, $option);
            ?>
        </div>
        <?php
    }
    public function aw_update_search_results_page(){
        $query = $_POST['query'];
        $option = $_POST['option'];
        //echo '<p>' . var_dump($query) . '</p>';
        //echo '<p>' . var_dump($option) . '</p>';
        $this->aw_show_search_results_page($query, $option);
    }
}
new AW_Search_Page();
