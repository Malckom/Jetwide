<?php
/**
 * Blog Upload Handler
 * Handles blog post uploads and stores them in JSON format
 */

// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set JSON response header
header('Content-Type: application/json');

// CORS headers (adjust origin as needed for security)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit();
}

// Configuration
define('UPLOAD_DIR', '../assets/images/blog/');
define('DATA_FILE', '../data/blog-posts.json');

// Create directories if they don't exist
if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}

$dataDir = dirname(DATA_FILE);
if (!file_exists($dataDir)) {
    mkdir($dataDir, 0755, true);
}

// Initialize blog posts file if it doesn't exist
if (!file_exists(DATA_FILE)) {
    file_put_contents(DATA_FILE, json_encode([]));
}

try {
    // Validate required fields
    $required_fields = ['title', 'excerpt', 'content', 'category', 'publish_date', 'author', 'tags'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Missing required field: $field");
        }
    }

    // Handle file upload
    if (!isset($_FILES['featured_image']) || $_FILES['featured_image']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Featured image is required');
    }

    $file = $_FILES['featured_image'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
    
    if (!in_array($file['type'], $allowed_types)) {
        throw new Exception('Invalid file type. Only JPG, PNG, and WebP are allowed');
    }

    // Generate unique filename
    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $unique_filename = 'blog_' . time() . '_' . uniqid() . '.' . $file_extension;
    $upload_path = UPLOAD_DIR . $unique_filename;

    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
        throw new Exception('Failed to upload image');
    }

    // Generate blog post ID
    $post_id = 'post_' . time() . '_' . substr(md5(uniqid()), 0, 8);

    // Process tags from comma-separated string to array
    $tags = array_map('trim', explode(',', $_POST['tags']));
    $tags = array_filter($tags); // Remove empty entries

    // Sanitize and prepare data
    $blog_post = [
        'id' => $post_id,
        'title' => htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8'),
        'slug' => generateSlug($_POST['title']),
        'excerpt' => htmlspecialchars($_POST['excerpt'], ENT_QUOTES, 'UTF-8'),
        'content' => $_POST['content'], // Content can contain HTML
        'featured_image' => 'assets/images/blog/' . $unique_filename,
        'category' => htmlspecialchars($_POST['category'], ENT_QUOTES, 'UTF-8'),
        'tags' => $tags, // Array of tags
        'author' => htmlspecialchars($_POST['author'], ENT_QUOTES, 'UTF-8'),
        'publish_date' => $_POST['publish_date'],
        'read_time' => isset($_POST['read_time']) ? (int)$_POST['read_time'] : 5,
        'status' => isset($_POST['status']) ? $_POST['status'] : 'published',
        'views' => 0,
        'seo_title' => !empty($_POST['seo_title']) ? htmlspecialchars($_POST['seo_title'], ENT_QUOTES, 'UTF-8') : htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8'),
        'seo_description' => !empty($_POST['seo_description']) ? htmlspecialchars($_POST['seo_description'], ENT_QUOTES, 'UTF-8') : htmlspecialchars($_POST['excerpt'], ENT_QUOTES, 'UTF-8'),
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];

    // Load existing posts
    $posts_json = file_get_contents(DATA_FILE);
    $posts = json_decode($posts_json, true);
    
    if (!is_array($posts)) {
        $posts = [];
    }

    // Add new post at the beginning
    array_unshift($posts, $blog_post);

    // Save to file
    if (file_put_contents(DATA_FILE, json_encode($posts, JSON_PRETTY_PRINT))) {
        // Return success response
        echo json_encode([
            'success' => true,
            'message' => 'Blog post published successfully',
            'post_id' => $post_id,
            'post_url' => 'blog-post.html?id=' . $post_id,
            'data' => $blog_post
        ]);
    } else {
        throw new Exception('Failed to save blog post');
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

/**
 * Generate URL-friendly slug from title
 */
function generateSlug($string) {
    $string = strtolower($string);
    $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
    $string = preg_replace('/[\s-]+/', '-', $string);
    $string = trim($string, '-');
    return $string;
}
?>
