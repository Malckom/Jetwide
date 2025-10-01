<?php
/**
 * Jetwide Visa Services WordPress Integration
 * Add this code to your theme's functions.php file
 */

// Enqueue styles and scripts for visa services page
function jetwide_visa_enqueue_scripts() {
    if (is_page_template('page-visa-services.php')) {
        wp_enqueue_script('jquery');
        
        // Add custom CSS for visa page
        wp_add_inline_style('theme-style', '
            /* Visa Page WordPress Fixes */
            .visa-hero {
                margin-top: 0 !important;
                padding-top: 0 !important;
            }
            
            .site-header + .visa-hero {
                margin-top: -80px !important;
            }
            
            .visa-main .container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 20px;
            }
            
            /* Fix WordPress theme conflicts */
            .visa-section h2,
            .visa-section h3,
            .visa-section h4 {
                font-family: inherit !important;
                line-height: 1.4 !important;
            }
            
            .visa-service-card,
            .visa-country-card,
            .visa-process-step,
            .why-choose-item {
                border: none !important;
                outline: none !important;
            }
            
            /* Ensure proper spacing */
            .visa-section {
                clear: both;
                overflow: hidden;
            }
            
            /* Mobile fixes for WordPress */
            @media (max-width: 768px) {
                .visa-hero-title {
                    font-size: 1.8rem !important;
                }
                
                .visa-section-title {
                    font-size: 1.5rem !important;
                }
                
                .visa-contact-title {
                    font-size: 1.8rem !important;
                }
            }
        ');
    }
}
add_action('wp_enqueue_scripts', 'jetwide_visa_enqueue_scripts');

// Add page template for visa services
function jetwide_add_visa_page_template($templates) {
    $templates['page-visa-services.php'] = 'Visa Services';
    return $templates;
}
add_filter('theme_page_templates', 'jetwide_add_visa_page_template');

// Load the visa services template
function jetwide_load_visa_template($template) {
    if (get_page_template_slug() == 'page-visa-services.php') {
        $template = get_stylesheet_directory() . '/page-visa-services.php';
    }
    return $template;
}
add_filter('page_template', 'jetwide_load_visa_template');

// Create visa services page programmatically (run once)
function jetwide_create_visa_page() {
    $page_title = 'Visa Services';
    $page_slug = 'visa-services';
    
    // Check if page already exists
    $page_exists = get_page_by_path($page_slug);
    
    if (!$page_exists) {
        $page_data = array(
            'post_title' => $page_title,
            'post_name' => $page_slug,
            'post_content' => 'This page uses a custom template for visa services.',
            'post_status' => 'publish',
            'post_type' => 'page',
            'meta_input' => array(
                '_wp_page_template' => 'page-visa-services.php'
            )
        );
        
        wp_insert_post($page_data);
    }
}

// Uncomment the line below to create the page (run once, then comment it back)
// add_action('init', 'jetwide_create_visa_page');

// Fix image paths for WordPress
function jetwide_fix_visa_image_paths($content) {
    if (is_page_template('page-visa-services.php')) {
        $template_dir = get_template_directory_uri();
        $content = str_replace('src="../assets/images/', 'src="' . $template_dir . '/assets/images/', $content);
        $content = str_replace('href="../assets/', 'href="' . $template_dir . '/assets/', $content);
    }
    return $content;
}
add_filter('the_content', 'jetwide_fix_visa_image_paths');
?>