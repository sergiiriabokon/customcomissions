<?php

class CustomComissions_Admin {

    public static function init() {
        add_action( 'init', array('CustomComissions_Admin', 'setup_post_type') );
        
        register_activation_hook( __FILE__, array('CustomComissions_Admin', 'plugin_activate'));
        register_deactivation_hook( __FILE__, array('CustomComissions_Admin', 'plugin_deactivate'));
        
        add_action( 'save_post', array('CustomComissions_Admin', 'save_postdata') );
        add_action( 'add_meta_boxes', array('CustomComissions_Admin','add_custom_box') );

        add_action( 'woocommerce_product_data_panels', array('CustomComissions_Admin','prod_edit_comission_tab') );

        add_filter( 'woocommerce_product_data_tabs', array('CustomComissions_Admin','custom_comission_tab'), 10, 1 );

    }

    public static function setup_post_type() {
        register_post_type( 'comission', ['public' => true, 'label' => 'Comissions', 'supports' => ['title'] ] ); 
    } 

    public static function plugin_activate() { 
        // Trigger our function that registers the custom post type plugin.
        CustomComissions_Admin::setup_post_type(); 
        // Clear the permalinks after the post type has been registered.
        flush_rewrite_rules(); 
    }

    /**
     * Deactivation hook.
     */
    public static function plugin_deactivate() {
        // Unregister the post type, so the rules are no longer in memory.
        unregister_post_type( 'comission' );
        // Clear the permalinks to remove our post type's rules from the database.
        flush_rewrite_rules();
    }

    public static function comission_settings( $post ) {
        $maxPrice = get_post_meta( $post->ID, 'cc_max_price', true );
        $minPrice = get_post_meta( $post->ID, 'cc_min_price', true );
        $ccValue = get_post_meta( $post->ID, 'cc_value', true );
        ?>

        <label for="cc_max_price_field">Max Price</label>
        <input name="cc_max_price_field" id="cc_max_price_field" class="postbox" value="<?=$maxPrice?>" />
       
        <br/>

        <label for="cc_min_price_field">Min Price</label>
        <input name="cc_min_price_field" id="cc_min_price_field" class="postbox" value="<?=$minPrice?>" />
        
        <br/>

        <label for="cc_value_field">Comission Value</label>
        <input name="cc_value_field" id="cc_value_field" class="postbox" value="<?=$ccValue?>" />
        <?php
    }
    
    public static function save_postdata( $post_id ) {
        if ( array_key_exists( 'cc_max_price_field', $_POST ) ) {
            update_post_meta(
                $post_id,
                'cc_max_price',
                $_POST['cc_max_price_field']
            );
        }
        if ( array_key_exists( 'cc_min_price_field', $_POST ) ) {
            update_post_meta(
                $post_id,
                'cc_min_price',
                $_POST['cc_min_price_field']
            );
        }
        if ( array_key_exists( 'cc_value_field', $_POST ) ) {
            update_post_meta(
                $post_id,
                'cc_value',
                $_POST['cc_value_field']
            );
        }
        if ( array_key_exists( 'cc_comission_type', $_POST )) {
            update_post_meta(
                $post_id,
                'cc_comission',
                $_POST['cc_comission_type']
            );
        }
    }
    //add_action( 'save_post', 'cc_save_postdata' );
    
    public static function add_custom_box() {
        $screens = [ 'comission' ];
        foreach ( $screens as $screen ) {
            add_meta_box(
                'cc_max_price',                 // Unique ID
                'Comission Settings',           // Box title
                array('CustomComissions_Admin', 'comission_settings'),        // Content callback, must be of type callable
                $screen                         // Post type
            );
        }
    }
    //add_action( 'add_meta_boxes', 'cc_add_custom_box' );

    public static function prod_edit_comission_tab() {
        global $post;
    
        $com_type = get_post_meta( $post->ID, 'cc_comission', true );
    
        $args = array(
            'post_type'      => 'comission'
        );
        $loop = new WP_Query($args);
    
        echo '<div id="cc_custom_tab_data" class="panel woocommerce_options_panel box">';
        echo '<select id="name="cc_comission_type" name="cc_comission_type"><option value="">No comission</option>';
        while ( $loop->have_posts() ) {
            $loop->the_post();
            ?>
                <option value="<?php the_id(); ?>" <?php selected($com_type, get_the_ID()); ?>><?php the_title(); ?></option>
            <?php
        }
        echo '</select>';
        echo '</div>';
    }

    public static function custom_comission_tab( $default_tabs ) {
        $default_tabs['cc_comission'] = array(
            'label'   =>  __( 'Comissions', 'domain' ),
            'target'  =>  'cc_custom_tab_data',
            'priority' => 10,
            'class'   => array()
        );
        return $default_tabs;
    }
}