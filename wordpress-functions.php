<?php
/**
 * Jetwide WordPress Functions
 * Add this content to your WordPress theme's functions.php file
 */

// Register Custom Post Types for Jetwide
function jetwide_custom_post_types() {
    
    // Destinations Post Type
    register_post_type('destinations', array(
        'labels' => array(
            'name' => 'Destinations',
            'singular_name' => 'Destination',
            'add_new' => 'Add New Destination',
            'add_new_item' => 'Add New Destination',
            'edit_item' => 'Edit Destination',
            'new_item' => 'New Destination',
            'view_item' => 'View Destination',
            'search_items' => 'Search Destinations',
            'not_found' => 'No destinations found',
            'not_found_in_trash' => 'No destinations found in trash'
        ),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'destinations'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-location-alt',
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'custom-fields'),
        'show_in_rest' => true, // Enable REST API
    ));
    
    // Special Events Post Type  
    register_post_type('special_events', array(
        'labels' => array(
            'name' => 'Special Events',
            'singular_name' => 'Special Event',
            'add_new' => 'Add New Event',
            'add_new_item' => 'Add New Special Event',
            'edit_item' => 'Edit Special Event',
            'new_item' => 'New Special Event',
            'view_item' => 'View Special Event',
            'search_items' => 'Search Special Events',
            'not_found' => 'No events found',
            'not_found_in_trash' => 'No events found in trash'
        ),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'special-events'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 6,
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'custom-fields'),
        'show_in_rest' => true,
    ));
    
    // Tour Packages Post Type
    register_post_type('tour_packages', array(
        'labels' => array(
            'name' => 'Tour Packages',
            'singular_name' => 'Tour Package',
            'add_new' => 'Add New Package',
            'add_new_item' => 'Add New Tour Package',
            'edit_item' => 'Edit Tour Package',
            'new_item' => 'New Tour Package',
            'view_item' => 'View Tour Package',
            'search_items' => 'Search Tour Packages',
            'not_found' => 'No packages found',
            'not_found_in_trash' => 'No packages found in trash'
        ),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'tour-packages'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 7,
        'menu_icon' => 'dashicons-palmtree',
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'custom-fields'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'jetwide_custom_post_types');

// Add Custom Meta Boxes
function jetwide_add_meta_boxes() {
    // Destination meta box
    add_meta_box(
        'destination_details',
        'Destination Details',
        'jetwide_destination_meta_callback',
        'destinations',
        'normal',
        'high'
    );
    
    // Special event meta box
    add_meta_box(
        'event_details',
        'Event Details',
        'jetwide_event_meta_callback',
        'special_events',
        'normal',
        'high'
    );
    
    // Tour package meta box
    add_meta_box(
        'package_details',
        'Package Details',
        'jetwide_package_meta_callback',
        'tour_packages',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'jetwide_add_meta_boxes');

// Destination Meta Box Callback
function jetwide_destination_meta_callback($post) {
    wp_nonce_field('jetwide_meta_nonce', 'jetwide_meta_nonce');
    
    $price = get_post_meta($post->ID, '_jetwide_price', true);
    $duration = get_post_meta($post->ID, '_jetwide_duration', true);
    $difficulty = get_post_meta($post->ID, '_jetwide_difficulty', true);
    
    echo '<table class="form-table">';
    echo '<tr><td><label for="jetwide_price"><strong>Price (e.g., $199, 120k):</strong></label></td>';
    echo '<td><input type="text" id="jetwide_price" name="jetwide_price" value="' . esc_attr($price) . '" style="width:100%;" /></td></tr>';
    echo '<tr><td><label for="jetwide_duration"><strong>Duration:</strong></label></td>';
    echo '<td><input type="text" id="jetwide_duration" name="jetwide_duration" value="' . esc_attr($duration) . '" placeholder="e.g., 3 Days, 1 Week" style="width:100%;" /></td></tr>';
    echo '<tr><td><label for="jetwide_difficulty"><strong>Difficulty Level:</strong></label></td>';
    echo '<td><select id="jetwide_difficulty" name="jetwide_difficulty" style="width:100%;">';
    echo '<option value="Easy"' . selected($difficulty, 'Easy', false) . '>Easy</option>';
    echo '<option value="Moderate"' . selected($difficulty, 'Moderate', false) . '>Moderate</option>';
    echo '<option value="Challenging"' . selected($difficulty, 'Challenging', false) . '>Challenging</option>';
    echo '</select></td></tr>';
    echo '</table>';
}

// Special Event Meta Box Callback
function jetwide_event_meta_callback($post) {
    wp_nonce_field('jetwide_meta_nonce', 'jetwide_meta_nonce');
    
    $price = get_post_meta($post->ID, '_jetwide_price', true);
    $duration = get_post_meta($post->ID, '_jetwide_duration', true);
    $group_size = get_post_meta($post->ID, '_jetwide_group_size', true);
    $event_date = get_post_meta($post->ID, '_jetwide_event_date', true);
    
    echo '<table class="form-table">';
    echo '<tr><td><label for="jetwide_price"><strong>Price:</strong></label></td>';
    echo '<td><input type="text" id="jetwide_price" name="jetwide_price" value="' . esc_attr($price) . '" placeholder="e.g., 120k, $299" style="width:100%;" /></td></tr>';
    echo '<tr><td><label for="jetwide_duration"><strong>Duration/Schedule:</strong></label></td>';
    echo '<td><input type="text" id="jetwide_duration" name="jetwide_duration" value="' . esc_attr($duration) . '" placeholder="e.g., EVERY DAY, MONDAY" style="width:100%;" /></td></tr>';
    echo '<tr><td><label for="jetwide_group_size"><strong>Group Size:</strong></label></td>';
    echo '<td><input type="text" id="jetwide_group_size" name="jetwide_group_size" value="' . esc_attr($group_size) . '" placeholder="e.g., 3-10 PP, 10-50 PP" style="width:100%;" /></td></tr>';
    echo '<tr><td><label for="jetwide_event_date"><strong>Event Date:</strong></label></td>';
    echo '<td><input type="date" id="jetwide_event_date" name="jetwide_event_date" value="' . esc_attr($event_date) . '" style="width:100%;" /></td></tr>';
    echo '</table>';
}

// Tour Package Meta Box Callback
function jetwide_package_meta_callback($post) {
    wp_nonce_field('jetwide_meta_nonce', 'jetwide_meta_nonce');
    
    $price = get_post_meta($post->ID, '_jetwide_price', true);
    $duration = get_post_meta($post->ID, '_jetwide_duration', true);
    $includes = get_post_meta($post->ID, '_jetwide_includes', true);
    $excludes = get_post_meta($post->ID, '_jetwide_excludes', true);
    
    echo '<table class="form-table">';
    echo '<tr><td><label for="jetwide_price"><strong>Price:</strong></label></td>';
    echo '<td><input type="text" id="jetwide_price" name="jetwide_price" value="' . esc_attr($price) . '" placeholder="e.g., $199, $299" style="width:100%;" /></td></tr>';
    echo '<tr><td><label for="jetwide_duration"><strong>Duration:</strong></label></td>';
    echo '<td><input type="text" id="jetwide_duration" name="jetwide_duration" value="' . esc_attr($duration) . '" placeholder="e.g., 5 Days 4 Nights" style="width:100%;" /></td></tr>';
    echo '<tr><td><label for="jetwide_includes"><strong>Package Includes:</strong></label></td>';
    echo '<td><textarea id="jetwide_includes" name="jetwide_includes" rows="4" style="width:100%;" placeholder="Accommodation, Meals, Transport, etc.">' . esc_textarea($includes) . '</textarea></td></tr>';
    echo '<tr><td><label for="jetwide_excludes"><strong>Package Excludes:</strong></label></td>';
    echo '<td><textarea id="jetwide_excludes" name="jetwide_excludes" rows="4" style="width:100%;" placeholder="Flight tickets, Personal expenses, etc.">' . esc_textarea($excludes) . '</textarea></td></tr>';
    echo '</table>';
}

// Save Custom Meta Fields
function jetwide_save_meta_fields($post_id) {
    // Check if nonce is valid
    if (!isset($_POST['jetwide_meta_nonce']) || !wp_verify_nonce($_POST['jetwide_meta_nonce'], 'jetwide_meta_nonce')) {
        return;
    }
    
    // Check if user has permissions to save
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save meta fields
    $fields = array('jetwide_price', 'jetwide_duration', 'jetwide_group_size', 'jetwide_event_date', 'jetwide_difficulty', 'jetwide_includes', 'jetwide_excludes');
    
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post', 'jetwide_save_meta_fields');

// Enable CORS for API access from your main site
function jetwide_enable_cors() {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
}
add_action('rest_api_init', 'jetwide_enable_cors');

// Add custom fields to REST API response
function jetwide_add_custom_fields_to_rest($response, $post, $request) {
    $custom_fields = array();
    
    // Get all custom fields for this post
    $meta = get_post_meta($post->ID);
    
    foreach ($meta as $key => $value) {
        if (strpos($key, '_jetwide_') === 0) {
            $field_name = str_replace('_jetwide_', '', $key);
            $custom_fields[$field_name] = $value[0];
        }
    }
    
    $response->data['jetwide_fields'] = $custom_fields;
    return $response;
}
add_filter('rest_prepare_destinations', 'jetwide_add_custom_fields_to_rest', 10, 3);
add_filter('rest_prepare_special_events', 'jetwide_add_custom_fields_to_rest', 10, 3);
add_filter('rest_prepare_tour_packages', 'jetwide_add_custom_fields_to_rest', 10, 3);

// Add admin notices for setup guidance
function jetwide_admin_notices() {
    $screen = get_current_screen();
    if ($screen->post_type === 'destinations' || $screen->post_type === 'special_events' || $screen->post_type === 'tour_packages') {
        echo '<div class="notice notice-info"><p><strong>Jetwide CMS:</strong> Add images using "Set Featured Image" and fill in the custom fields below for your website display.</p></div>';
    }
}
add_action('admin_notices', 'jetwide_admin_notices');

?>