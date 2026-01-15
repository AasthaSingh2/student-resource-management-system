<?php
/*
Plugin Name: Student Resource Management System
Description: Manage and display student study resources using a custom post type and shortcode.
Version: 1.0
Author: Aastha Singh
*/

if (!defined('ABSPATH')) exit;

/* -------------------------
   Register Custom Post Type
--------------------------*/
function srms_register_study_resources() {
    register_post_type('study_resource', array(
        'labels' => array(
            'name' => 'Study Resources',
            'singular_name' => 'Study Resource'
        ),
        'public' => true,
        'menu_icon' => 'dashicons-welcome-learn-more',
        'supports' => array('title', 'editor'),
        'has_archive' => true
    ));
}
add_action('init', 'srms_register_study_resources');

/* -------------------------
   Shortcode to Display Resources
--------------------------*/
function srms_display_resources() {
    $query = new WP_Query(array(
        'post_type' => 'study_resource',
        'posts_per_page' => -1
    ));

    if (!$query->have_posts()) {
        return '<p>No study resources available.</p>';
    }

    $output = '<div class="study-resources-grid">';

    while ($query->have_posts()) {
        $query->the_post();

        $output .= '
        <div class="study-card">
            <h3>' . get_the_title() . '</h3>
            <div class="study-content">' . get_the_content() . '</div>
        </div>';
    }

    wp_reset_postdata();

    $output .= '</div>';
    return $output;
}
add_shortcode('study_resources', 'srms_display_resources');

/* -------------------------
   CSS Styling (UI)
--------------------------*/
add_action('wp_head', function () {
    echo '
    <style>
        .study-resources-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .study-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 22px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .study-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.12);
        }

        .study-card h3 {
            margin-bottom: 12px;
            color: #1f2937;
            font-size: 20px;
        }

        .study-content {
            color: #4b5563;
            font-size: 15px;
            line-height: 1.6;
        }

        .study-content a {
            display: inline-block;
            margin-top: 10px;
            color: #2563eb;
            font-weight: 600;
            text-decoration: none;
        }

        .study-content a:hover {
            text-decoration: underline;
        }
    </style>';
});
