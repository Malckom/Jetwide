<?php
/**
 * Blog API - Get blog posts
 * Returns blog posts in JSON format
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

define('DATA_FILE', '../data/blog-posts.json');

// Get query parameters
$post_id = isset($_GET['id']) ? $_GET['id'] : null;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$category = isset($_GET['category']) ? $_GET['category'] : null;
$status = isset($_GET['status']) ? $_GET['status'] : 'published';

try {
    // Check if data file exists
    if (!file_exists(DATA_FILE)) {
        echo json_encode([
            'success' => true,
            'count' => 0,
            'posts' => []
        ]);
        exit();
    }

    // Load posts
    $posts_json = file_get_contents(DATA_FILE);
    $posts = json_decode($posts_json, true);

    if (!is_array($posts)) {
        throw new Exception('Invalid data format');
    }

    // If requesting a specific post
    if ($post_id) {
        $post = null;
        foreach ($posts as $key => $p) {
            if ($p['id'] === $post_id || $p['slug'] === $post_id) {
                $post = $p;
                
                // Increment views
                $posts[$key]['views'] = isset($posts[$key]['views']) ? $posts[$key]['views'] + 1 : 1;
                file_put_contents(DATA_FILE, json_encode($posts, JSON_PRETTY_PRINT));
                
                break;
            }
        }

        if ($post) {
            echo json_encode([
                'success' => true,
                'post' => $post
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'error' => 'Post not found'
            ]);
        }
        exit();
    }

    // Filter by status
    $filtered_posts = array_filter($posts, function($post) use ($status) {
        return !isset($post['status']) || $post['status'] === $status;
    });

    // Filter by category if specified
    if ($category) {
        $filtered_posts = array_filter($filtered_posts, function($post) use ($category) {
            return isset($post['category']) && $post['category'] === $category;
        });
    }

    // Sort by publish date (newest first)
    usort($filtered_posts, function($a, $b) {
        return strtotime($b['publish_date']) - strtotime($a['publish_date']);
    });

    // Apply limit
    $filtered_posts = array_slice($filtered_posts, 0, $limit);

    // Return results
    echo json_encode([
        'success' => true,
        'count' => count($filtered_posts),
        'posts' => array_values($filtered_posts)
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
