<?php
/**
 * Applies the comission on the frontend shop.
 * 
 * The commission should is applied by following these conditions:
 * a. The product is linked to a commission
 * b. The product price is between min and max values defined in the commission settings
 * c. The calculation formula is:
 *     FP = PP + CV 
 *    (FPP - Final Product Price, PP - Product Price, CV - commission value)
 */
class CustomComissions_Product_Price {

    public static function init() {

       add_filter('woocommerce_get_price', array('CustomComissions_Product_Price', 'comissioned_price'), 99, 2);

    }

    public static function comissioned_price( $orginal_price, $prod_obj ) {
        $new_price = $orginal_price; 
    
        $commision_type = get_post_meta( $prod_obj->id, 'cc_comission', true );
        
        if ($commision_type) {
            $max = get_post_meta( $commision_type, 'cc_max_price', true );
            $min = get_post_meta( $commision_type, 'cc_min_price', true );
            $val = get_post_meta( $commision_type, 'cc_value', true );
            
            //your logic for calculating the new price here
            if ($orginal_price <= $max && $orginal_price >= $min) {
                $new_price += $val;
            }
        }
    
        //Return the new price (this is the price that will be used everywhere in the store)
        return $new_price;
    }
}