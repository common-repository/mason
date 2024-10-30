<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/* 
 * Options Page
 * Add ACF items to an options page 
 * */

if ( function_exists('acf_add_options_page') ) {

    acf_add_options_page(
        array(
            'page_title'    => 'Mason Settings',
            'menu_title'    => 'Mason Settings',
            'menu_slug'     => 'mason-options',
            'capability'    => 'edit_posts',
            'parent_slug'   => '',
            'position'      => false,
            'icon_url'      => false
        )
    );

    acf_add_options_sub_page(
        array(
            'page_title'    => 'Mason Settings',
            'menu_title'    => 'Mason Settings',
            'menu_slug'     => 'mason-settings',
            'capability'    => 'edit_posts',
            'parent_slug'   => 'mason-options',
            'position'      => false,
            'icon_url'      => false
        )
    ); 
        acf_add_options_sub_page(
        array(
            'page_title'    => 'Global Modules',
            'menu_title'    => 'Global Modules',
            'menu_slug'     => 'global-modules',
            'capability'    => 'edit_posts',
            'parent_slug'   => 'mason-options',
            'position'      => 3.3,
            'icon_url'      => false
        )
    );    
    acf_add_options_page(
        array(
            'page_title'    => 'Mason Templates',
            'menu_title'    => 'Mason Templates',
            'menu_slug'     => 'mason-templates',
            'capability'    => 'edit_posts',
            'parent_slug'   => '',
            'position'      => false,
            'icon_url'      => false
        )
    );
    acf_add_options_sub_page(
        array(
            'page_title'    => 'Mason Templates - Declare Pages',
            'menu_title'    => 'Template Settings',
            'menu_slug'     => 'template-settings',
            'capability'    => 'edit_posts',
            'parent_slug'   => 'mason-templates',
            'icon_url'      => false
        )
    );
   
}
?>