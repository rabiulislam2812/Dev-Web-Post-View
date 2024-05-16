<?php

namespace Devweb\PostViewCount;

class Dev_Web_View_Count_Shortcode {

    function __construct() {
        add_action('init', [$this, 'init']);

    }

    // Plugin initialization method
    function init() {

        add_shortcode('post_view_count', [$this, 'post_view_count_shortcode']);
    }

    // Shortcode to display view count for a specific post
    function post_view_count_shortcode($atts) {
        $atts = shortcode_atts(array(
            'post_id' => null,
        ), $atts);

        if (!$atts['post_id']) {
            $custom_content = '<div class="views">';
            $custom_content .= '<p class="notice">' . esc_html('Please specify a post ID.', 'dev-web') . '</p>';
            $custom_content .= '</div>';
            return $custom_content;
        }

        $views = get_post_meta($atts['post_id'], 'view_count', true);
        $total_view = ($views == '') ? 0 : $views;

        $custom_content = '<div class="views">';
        $custom_content .= '<h2>Post Views</h2>';
        $custom_content .= '<p>Total Views</p>';
        $custom_content .= '<h1 class="blink">' . esc_html($total_view + 1) . '</h1>';
        $custom_content .= '</div>';
        return $custom_content;
    }

}

new Dev_Web_View_Count_Shortcode();