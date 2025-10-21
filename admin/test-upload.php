<?php
/**
 * Test Upload Configuration
 * Check if all directories and permissions are correct
 */

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Upload Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .test-box {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .success {
            color: #27ae60;
            font-weight: bold;
        }
        .error {
            color: #e74c3c;
            font-weight: bold;
        }
        h1 {
            color: #14132A;
        }
        h2 {
            color: #FE9900;
            border-bottom: 2px solid #FE9900;
            padding-bottom: 10px;
        }
        pre {
            background: #f8f8f8;
            padding: 10px;
            border-radius: 4px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <h1>🔧 Blog Upload System Diagnostics</h1>

    <div class="test-box">
        <h2>1. PHP Configuration</h2>
        <?php
        echo "<p>PHP Version: <strong>" . phpversion() . "</strong></p>";
        echo "<p>Upload Max Filesize: <strong>" . ini_get('upload_max_filesize') . "</strong></p>";
        echo "<p>Post Max Size: <strong>" . ini_get('post_max_size') . "</strong></p>";
        echo "<p>Max Execution Time: <strong>" . ini_get('max_execution_time') . " seconds</strong></p>";
        ?>
    </div>

    <div class="test-box">
        <h2>2. Directory Structure</h2>
        <?php
        $uploadDir = '../assets/images/blog/';
        $dataDir = '../data/';
        $dataFile = '../data/blog-posts.json';
        
        echo "<p><strong>Upload Directory:</strong> " . realpath($uploadDir ?: '.') . "</p>";
        if (file_exists($uploadDir)) {
            echo "<p class='success'>✓ Upload directory exists</p>";
            if (is_writable($uploadDir)) {
                echo "<p class='success'>✓ Upload directory is writable</p>";
            } else {
                echo "<p class='error'>✗ Upload directory is NOT writable</p>";
            }
        } else {
            echo "<p class='error'>✗ Upload directory does NOT exist</p>";
            echo "<p>Attempting to create...</p>";
            if (mkdir($uploadDir, 0755, true)) {
                echo "<p class='success'>✓ Directory created successfully</p>";
            } else {
                echo "<p class='error'>✗ Failed to create directory</p>";
            }
        }

        echo "<p><strong>Data Directory:</strong> " . realpath($dataDir ?: '.') . "</p>";
        if (file_exists($dataDir)) {
            echo "<p class='success'>✓ Data directory exists</p>";
            if (is_writable($dataDir)) {
                echo "<p class='success'>✓ Data directory is writable</p>";
            } else {
                echo "<p class='error'>✗ Data directory is NOT writable</p>";
            }
        } else {
            echo "<p class='error'>✗ Data directory does NOT exist</p>";
            echo "<p>Attempting to create...</p>";
            if (mkdir($dataDir, 0755, true)) {
                echo "<p class='success'>✓ Directory created successfully</p>";
            } else {
                echo "<p class='error'>✗ Failed to create directory</p>";
            }
        }

        echo "<p><strong>Blog Posts File:</strong> $dataFile</p>";
        if (file_exists($dataFile)) {
            echo "<p class='success'>✓ blog-posts.json exists</p>";
            if (is_writable($dataFile)) {
                echo "<p class='success'>✓ blog-posts.json is writable</p>";
            } else {
                echo "<p class='error'>✗ blog-posts.json is NOT writable</p>";
            }
            $content = file_get_contents($dataFile);
            $posts = json_decode($content, true);
            if (is_array($posts)) {
                echo "<p class='success'>✓ JSON file is valid (contains " . count($posts) . " posts)</p>";
            } else {
                echo "<p class='error'>✗ JSON file is NOT valid</p>";
            }
        } else {
            echo "<p class='error'>✗ blog-posts.json does NOT exist</p>";
            echo "<p>Attempting to create...</p>";
            if (file_put_contents($dataFile, '[]')) {
                echo "<p class='success'>✓ File created successfully</p>";
            } else {
                echo "<p class='error'>✗ Failed to create file</p>";
            }
        }
        ?>
    </div>

    <div class="test-box">
        <h2>3. File Permissions</h2>
        <?php
        if (file_exists($uploadDir)) {
            $perms = substr(sprintf('%o', fileperms($uploadDir)), -4);
            echo "<p>Upload Directory Permissions: <strong>$perms</strong></p>";
        }
        if (file_exists($dataFile)) {
            $perms = substr(sprintf('%o', fileperms($dataFile)), -4);
            echo "<p>Blog Posts File Permissions: <strong>$perms</strong></p>";
        }
        ?>
    </div>

    <div class="test-box">
        <h2>4. Server Information</h2>
        <?php
        echo "<p>Server Software: <strong>" . $_SERVER['SERVER_SOFTWARE'] . "</strong></p>";
        echo "<p>Document Root: <strong>" . $_SERVER['DOCUMENT_ROOT'] . "</strong></p>";
        echo "<p>Current Script: <strong>" . __FILE__ . "</strong></p>";
        ?>
    </div>

    <div class="test-box">
        <h2>5. Test Actions</h2>
        <p><a href="blog-upload.html" style="display: inline-block; padding: 10px 20px; background: #14132A; color: white; text-decoration: none; border-radius: 5px;">Go to Blog Upload Form</a></p>
        <p><a href="upload-blog.php" style="display: inline-block; padding: 10px 20px; background: #FE9900; color: white; text-decoration: none; border-radius: 5px;">Test Upload Handler (GET)</a></p>
    </div>

    <div class="test-box">
        <h2>6. Recent Errors</h2>
        <?php
        $errorLog = '../error_log';
        if (file_exists($errorLog)) {
            echo "<p class='success'>✓ Error log exists</p>";
            $errors = file($errorLog);
            if ($errors && count($errors) > 0) {
                echo "<pre>" . htmlspecialchars(implode('', array_slice($errors, -20))) . "</pre>";
            } else {
                echo "<p>No errors logged</p>";
            }
        } else {
            echo "<p>No error log file found</p>";
        }
        ?>
    </div>
</body>
</html>
