<?php

/*
Plugin Name: Dev Web Post View
Description: This plugin is record the number of views a post has received. It display the view
count for each post in the admin post list using a custom column and show the total post view.
Version: 1.0
Author: Md Rabiul Islam
Author URI: www.rabiulislam.net
Text Domain: dev-web
Domain Path: /languages
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

require_once (plugin_dir_path(__FILE__) . 'vendor/autoload.php');

class Dev_Web_Post_View_Count {
    function __construct() {
        add_action('init', [$this, 'init']);

        new Devweb\PostViewCount\Dev_Web_View_Count_Shortcode();
    }

    // Plugin initialization method
    function init() {
        add_action('wp_head', [$this, 'increment_post_view_count']);
        add_filter('manage_posts_columns', [$this, 'add_view_count_column']);
        add_action('manage_posts_custom_column', [$this, 'display_view_count_column'], 10, 2);
        add_filter('manage_edit-post_sortable_columns', [$this, 'make_view_count_column_sortable']);
        add_action('pre_get_posts', [$this, 'sort_posts_by_view_count']);

        add_action('wp_enqueue_scripts', [$this, 'dev_web_enqueue_styles']);
    }

    // Enque custom style
    public function dev_web_enqueue_styles() {
        wp_enqueue_style('dev-web-related-posts-style', plugins_url('assets/css/style.css', __FILE__));
    }

    // Increment view count
    function increment_post_view_count() {
        if (is_single()) {
            $post_id = get_the_ID();
            $views = get_post_meta($post_id, 'view_count', true);
            $views = $views ? $views : 0;
            $views++;
            update_post_meta($post_id, 'view_count', $views);

        }
    }

    // Add view count column to admin post list screen
    function add_view_count_column($columns) {
        $columns['view_count'] = 'View Count';
        return $columns;
    }

    // Display view count for each post in admin column
    function display_view_count_column($column, $post_id) {
        if ($column === 'view_count') {
            $views = get_post_meta($post_id, 'view_count', true);
            echo ($views == '') ? 0 : $views;
        }
    }

    // Make view count column sortable
    function make_view_count_column_sortable($columns) {
        $columns['view_count'] = 'view_count';
        return $columns;
    }

    // Sort posts by view count
    function sort_posts_by_view_count($query) {
        if (!is_admin() || !$query->is_main_query()) {
            return;
        }

        if ($query->get('orderby') === 'view_count') {
            $query->set('meta_key', 'view_count');
            $query->set('orderby', 'meta_value_num');
        }
    }


}

new Dev_Web_Post_View_Count();
