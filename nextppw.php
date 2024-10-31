<?php
/*
Plugin Name: Next Previous Products for Woocommerce FREE 
Plugin URI: http://starblank.com/nssw
Description: This free plugin shows next and previous product links in product view
Version: 1.0
Author: starblank.com
Author URI: https://github.com/saulbustos/
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: next-previous-product-woocommerce-free
Domain Path: /languages
*/


//Stuff
defined( 'ABSPATH' ) or die( 'aborting!' );
require "include/next-admin.php";
$dir = plugin_dir_path( __FILE__ );

//Languages
function nssw_star_free_load_plugin_textdomain() {
    load_plugin_textdomain( 'next-previous-product-woocommerce-free', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'nssw_star_free_load_plugin_textdomain' );


//If not active, exit
if (!get_option('nssw-enabled')) return;


//get saved values
$nssw_enabled = get_option('nssw-enabled' );
$nssw_location = get_option('nssw-location' );
$nssw_applies = get_option('nssw-applies' );
$nssw_useimage = get_option('nssw-useimage' );
$nssw_arrow = get_option('nssw-arrow' );
$nssw_arrow_left = get_option('nssw-arrow-left' );
$nssw_arrow_right = get_option('nssw-arrow-right' );
$nssw_text_size = nssw_star_free_clean_number(get_option('nssw-text-size' ));
$nssw_text_color = get_option('nssw-text-color' );
$nssw_usetitle = get_option('nssw-usetitle');


//Hook, some posibilities
$nssw_priority=10;
$isfloat='';
switch($nssw_location){
	default: //0 - over the product
		$nssw_location='woocommerce_before_single_product_summary';
		$css="_over";
		$nssw_priority=10;
	break;

}

//CSS load and stuff
add_action( 'wp_enqueue_scripts', 'nssw_star_free_register_plugin_styles' );
function nssw_star_free_register_plugin_styles(){
	global $css,$nssw_custom_css,$nssw_float_background,$nssw_float_border;
	
	wp_register_style( 'next_previous_product_woocommerce_free', plugins_url( 'next-previous-product-woocommerce-free/css/common.css' ) );
	wp_enqueue_style( 'next_previous_product_woocommerce_free' );

}

//Style defined by user
$nssw_style='style="color:'.$nssw_text_color.'; font-size:'.$nssw_text_size.'px;"';

//Product filtering type
switch($nssw_applies){

	default: //0 - default - Same subcategory
		$nssw_applies='samecategory';
		add_action( $nssw_location, 'nssw_star_free_start_draw', $nssw_priority );
	break;
}


////////////////////////////////////////////////////////////////////////////
//FUNCTIONS


//prints products in same category or subcategory 
function nssw_star_free_start_draw(){

        global $isfloat, $css, $product, $nssw_applies, $nssw_style;
        $id = $product->id;

        //obtener categoria actual
                $term_list = wp_get_post_terms($id,'product_cat',array('fields'=>'ids'));
                $cateID = $term_list[count($term_list)-1];
                $cate=get_term_by( 'id', $cateID, 'product_cat' );
                $cate=$cate->slug;
        
	//obtener los productos de esa categoria
        $args = array( 'post_type' => 'product', 'posts_per_page' => 1000,'product_cat' => $cate, 'orderby' => 'name', 'order' => 'ASC' );
        $query = new WP_Query($args);

        //obtener la posicion del producto actual en el array
        $posts=$query->posts;
        $i=0;
        foreach($posts as $post){
                $ids[$i]=$post->ID;
                $i++;
        }
        $current=array_search($id, $ids);

        //ajustamos posicion
        if ($current <= 0 ){
                $prev=-1;
        }else{
                $prev=$current-1;
        }

        if ($current==(count($ids)-1)){
                $next=-1;
        }else{
                $next=$current+1;
        }

        //Pintamos
        echo '<div class="otros_productos'.$css.'">';
        if ($prev!=-1) echo '<div class="product_prev nssw_left"><a class="nssw_link_left" '.$nssw_style.' href="'.get_post_permalink($ids[$prev]).'">'.nssw_star_free_get_product_title($ids[$prev],'left').'</a></div>';
        if ($next!=-1) echo '<div class="product_next nssw_right"><a class="nssw_link_right" '.$nssw_style.' href="'.get_post_permalink($ids[$next]).'"> '.nssw_star_free_get_product_title($ids[$next],'right').'</a></div>';
	echo "</div>";
}


//This function returns a product image URL by given product id
function nssw_star_free_get_product_image($id){
	$image = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'single-post-thumbnail' );
	return $image[0];
}


//This function returns either the title or product image based on user settings
function nssw_star_free_get_product_title($id,$pos){
	global $isfloat, $css, $nssw_useimage, $nssw_usetitle, $nssw_float_background;
	
	if ($nssw_usetitle){
		$title=get_the_title($id);
		if (strlen($title)>40) $title=substr($title,0,37)."...";
		$tit='<td class="nssw_title_'.$pos.$isfloat.'" '.$fBack.'>'.$title.'</td>';
	}
	
	$arrow=nssw_star_free_get_arrow($pos);
	
	if ($pos=='left') return '<table class="nssw_fulltitle"><tr>'.$arrow.$img.$tit.'</tr></table>';
	else
	    if ($css=='_float') return '<table class="nssw_fulltitle"><tr>'.$arrow.$img.$tit.'</tr></table>';
	    else return '<table class="nssw_fulltitle"><tr>'.$tit.$img.$arrow.'</tr></table>';
}

//This function returns the applicable arrow. $pos is 0-left arrow, 1-right arrow
//$pos is left or right
//possible values for nssw_arrow: 0-default arrows, 1- no arrows, 2-custom uploaded arrows
function nssw_star_free_get_arrow($pos){
   global $nssw_arrow, $nssw_arrow_left, $nssw_arrow_right, 
	  $nssw_arrow_width, $nssw_arrow_height, $isfloat,$nssw_float_background;

   if ($isfloat=='_float') $fBack='style="background-color:'.$nssw_float_background.';"';

   switch ($nssw_arrow){

	default: //default - text arrows
		if ($pos=='left') return '<td '.$fBack.' class="nssw_arrow_symbol_left'.$isfloat.'">&#9668;</td>'; 
		else return '<td '.$fBack.' class="nssw_arrow_symbol_right'.$isfloat.'">&#9658;</td>';
	break;
	
   }

}


//this function clean string allowing only numbers
function nssw_star_free_clean_number($n){
	return preg_replace("/[^0-9]/","",$n);
}
