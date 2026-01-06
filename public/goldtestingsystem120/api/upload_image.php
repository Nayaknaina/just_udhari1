<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

try {
    // Check if file was uploaded
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('No file uploaded or upload error occurred');
    }

    $file = $_FILES['image'];
    
    // Validate file type
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    $file_type = mime_content_type($file['tmp_name']);
    
    if (!in_array($file_type, $allowed_types)) {
        throw new Exception('Invalid file type. Only JPEG, PNG, GIF, and WebP images are allowed.');
    }
    
    // Validate file size (max 5MB)
    $max_size = 5 * 1024 * 1024; // 5MB in bytes
    if ($file['size'] > $max_size) {
        throw new Exception('File size too large. Maximum size is 5MB.');
    }
    
    // Create upload directory if it doesn't exist
    $upload_dir = '../uploads/images/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Generate unique filename
    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $unique_filename = 'sample_' . uniqid() . '_' . time() . '.' . $file_extension;
    $upload_path = $upload_dir . $unique_filename;
    
    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
        throw new Exception('Failed to save uploaded file');
    }
    
    // Optimize image (resize if too large)
    $optimized_path = optimizeImage($upload_path, $file_type);
    
    echo json_encode([
        'success' => true,
        'filename' => $unique_filename,
        'path' => 'uploads/images/' . basename($optimized_path),
        'size' => filesize($optimized_path),
        'type' => $file_type,
        'message' => 'Image uploaded successfully'
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}

function optimizeImage($image_path, $mime_type) {
    // Maximum dimensions
    $max_width = 800;
    $max_height = 600;
    $quality = 85;
    
    // Get image dimensions
    list($width, $height) = getimagesize($image_path);
    
    // Check if resizing is needed
    if ($width <= $max_width && $height <= $max_height) {
        return $image_path; // No optimization needed
    }
    
    // Calculate new dimensions
    $ratio = min($max_width / $width, $max_height / $height);
    $new_width = intval($width * $ratio);
    $new_height = intval($height * $ratio);
    
    // Create image resource based on type
    switch ($mime_type) {
        case 'image/jpeg':
        case 'image/jpg':
            $source = imagecreatefromjpeg($image_path);
            break;
        case 'image/png':
            $source = imagecreatefrompng($image_path);
            break;
        case 'image/gif':
            $source = imagecreatefromgif($image_path);
            break;
        case 'image/webp':
            $source = imagecreatefromwebp($image_path);
            break;
        default:
            return $image_path; // Unsupported type
    }
    
    if (!$source) {
        return $image_path; // Failed to create source
    }
    
    // Create new image
    $destination = imagecreatetruecolor($new_width, $new_height);
    
    // Preserve transparency for PNG and GIF
    if ($mime_type === 'image/png' || $mime_type === 'image/gif') {
        imagealphablending($destination, false);
        imagesavealpha($destination, true);
        $transparent = imagecolorallocatealpha($destination, 255, 255, 255, 127);
        imagefilledrectangle($destination, 0, 0, $new_width, $new_height, $transparent);
    }
    
    // Resize image
    imagecopyresampled($destination, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    
    // Save optimized image
    $optimized_path = str_replace('.', '_optimized.', $image_path);
    
    switch ($mime_type) {
        case 'image/jpeg':
        case 'image/jpg':
            imagejpeg($destination, $optimized_path, $quality);
            break;
        case 'image/png':
            imagepng($destination, $optimized_path, 9);
            break;
        case 'image/gif':
            imagegif($destination, $optimized_path);
            break;
        case 'image/webp':
            imagewebp($destination, $optimized_path, $quality);
            break;
    }
    
    // Clean up
    imagedestroy($source);
    imagedestroy($destination);
    
    // Remove original file
    unlink($image_path);
    
    return $optimized_path;
}
?>
