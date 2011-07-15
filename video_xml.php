<?php
################################################################################################################################
/**
 * @package Video_sitemap_xml
 * @Create sitemap xml for videos
 */
###############################################################################################################################


/* 
Plugin Name: Youtube and self-hosted Video Sitemap
Plugin URI: http://wdtmedia.com
Description: A plugin that build a Video sitemap for wordpress blog. Users either add videos of youtube or self-hosted videos to theit site with a shortcode, the plugin has its own options pages with a button to generate video sitemap(an xml file), it also has option to upload default thumbnail.
Author: WDT Media Inc
Version: 1.0
Author URI: http://wdtmedia.com
*/

include_once('sitemap_fun.php');
add_action( 'media_buttons', 'wdtsitemap_buttons', 999 );

add_action ('admin_menu', 'wdtgenerate_sitemappage');

//admin_menu();
 
add_action('add_meta_boxes', 'createSitemap');
add_action('save_post', 'createSitemap');
//add_action('publish_post', 'createSitemap');
//add_filter('wp_insert_post_data', 'createSitemap');
?>
