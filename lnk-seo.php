<?php

/**
 * Plugin Name: LNK Legis SEO
 * Plugin URI: https://github.com/linkerx
 * Description: Cambia la URL de la API para seguridad
 * Version: 0.1
 * Author: Diego Martinez Diaz
 * Author URI: https://github.com/linkerx
 * License: GPLv3
 */

function fpw_post_info( $id, $post ) {
    $site = "";
    $type = "";
    $cat = "";
    $slug = "";
    $image = "";
    $blog_data = get_blog_details();
    $site = ltrim($blog_data->path,"/");
    if($post->post_type != 'post'){
        $type = $post->post_type."/";
    } else {
        $cat_obj = get_the_category($post->ID);
        if($cat_obj && $cat_obj[0]){
            $cat = $cat_obj[0]->slug."/";
        }
    }
    $slug = $post->post_name;
    $url = "https://web.legisrn.gov.ar/".$site.$type.$cat.$slug;
    $data = array();
    $data['type'] = 'article';
    $data['title'] = $post->post_title;
    $data['description'] = $post->post_excerpt;
    if (has_post_thumbnail($post)){
        $image = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
    } else {
        $image = "https://webadmin.legisrn.gov.ar/assets/images/legisrn.jpg";
    }
    $data['image'] = $image;
    $metadata = json_encode($data,JSON_UNESCAPED_UNICODE);
    global $wpdb;
    $save = array("url" => $url,"metadata" => $metadata);
    $wpdb->replace('lnk_seo',$save);
}
add_action( 'publish_post', 'fpw_post_info', 10, 2 );
