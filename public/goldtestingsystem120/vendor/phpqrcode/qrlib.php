<?php
/*
 * Professional PHP QR Code Generator
 * Uses Google Charts API for reliable QR code generation
 * Includes proper error handling and validation
 */

// QR Code Error Correction Levels
define('QR_ECLEVEL_L', 'L'); // Low (7%)
define('QR_ECLEVEL_M', 'M'); // Medium (15%)
define('QR_ECLEVEL_Q', 'Q'); // Quartile (25%)
define('QR_ECLEVEL_H', 'H'); // High (30%)

class QRcode {
    /**
     * Generate QR code PNG image
     * @param string $text Data to encode
     * @param string|false $outfile Output file path or false for direct output
     * @param string $level Error correction level
     * @param int $size Module size (1-40)
     * @param int $margin Quiet zone margin
     * @param bool $saveandprint Whether to save and print
     * @return bool Success status
     */
    public static function png($text, $outfile = false, $level = QR_ECLEVEL_L, $size = 3, $margin = 4, $saveandprint = false) {
        try {
            if (empty($text)) {
                error_log("QR Error: Empty text provided");
                return false;
            }
            
            if (strlen($text) > 2953) {
                error_log("QR Error: Text too long (" . strlen($text) . " characters)");
                return false;
            }
            
            $imageSize = max(200, min(1000, $size * 50));
            
            $apiUrls = [
                'https://chart.googleapis.com/chart?' . http_build_query([
                    'chs' => $imageSize . 'x' . $imageSize,
                    'cht' => 'qr',
                    'chl' => $text,
                    'choe' => 'UTF-8',
                    'chld' => $level . '|' . max(0, min(10, $margin))
                ]),
                'https://api.qrserver.com/v1/create-qr-code/?' . http_build_query([
                    'size' => $imageSize . 'x' . $imageSize,
                    'data' => $text,
                    'format' => 'png',
                    'ecc' => strtoupper($level)
                ])
            ];
            
            $context = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'timeout' => 30,
                    'user_agent' => 'Mozilla/5.0 (compatible; PHP QR Generator)',
                    'header' => [
                        'Accept: image/png, image/*',
                        'Connection: close'
                    ],
                    'ignore_errors' => true
                ]
            ]);
            
            $imageData = false;
            
            foreach ($apiUrls as $index => $apiUrl) {
                error_log("QR Generation attempt " . ($index + 1) . " using: " . parse_url($apiUrl, PHP_URL_HOST));
                
                $imageData = @file_get_contents($apiUrl, false, $context);
                
                if ($imageData !== false && strlen($imageData) > 100) {
                    // Verify it's a valid PNG
                    if (substr($imageData, 0, 8) === "\x89PNG\r\n\x1a\n") {
                        error_log("QR Generation successful on attempt " . ($index + 1));
                        break;
                    } else {
                        error_log("Invalid PNG data received from " . parse_url($apiUrl, PHP_URL_HOST));
                        $imageData = false;
                    }
                } else {
                    error_log("Failed to get data from " . parse_url($apiUrl, PHP_URL_HOST));
                }
                
                if ($index < count($apiUrls) - 1) {
                    sleep(1); // Wait before trying next API
                }
            }
            
            if ($imageData === false) {
                error_log("QR Generation failed on all attempts");
                return false;
            }
            
            if ($outfile !== false) {
                $dir = dirname($outfile);
                if (!is_dir($dir)) {
                    if (!mkdir($dir, 0755, true)) {
                        error_log("Failed to create directory: " . $dir);
                        return false;
                    }
                }
                
                if (file_put_contents($outfile, $imageData) === false) {
                    error_log("Failed to save QR code to: " . $outfile);
                    return false;
                }
                
                if (!file_exists($outfile) || filesize($outfile) === 0) {
                    error_log("QR code file not created properly: " . $outfile);
                    return false;
                }
                
                error_log("QR code successfully saved: " . $outfile . " (" . strlen($imageData) . " bytes)");
                
            } else {
                if (!headers_sent()) {
                    header('Content-Type: image/png');
                    header('Content-Length: ' . strlen($imageData));
                    header('Cache-Control: public, max-age=3600');
                }
                echo $imageData;
            }
            
            return true;
            
        } catch (Exception $e) {
            error_log("QR Code generation error: " . $e->getMessage());
            return false;
        }
    }
}

class QRencode {
    private $level;
    private $size;
    private $margin;
    
    public function __construct($level = QR_ECLEVEL_L, $size = 3, $margin = 4) {
        $this->level = $level;
        $this->size = $size;
        $this->margin = $margin;
    }
    
    public static function factory($level = QR_ECLEVEL_L, $size = 3, $margin = 4) {
        return new QRencode($level, $size, $margin);
    }
    
    public function encodePNG($intext, $outfile = false, $saveandprint = false) {
        return QRcode::png($intext, $outfile, $this->level, $this->size, $this->margin, $saveandprint);
    }
}

/**
 * Simple function to generate QR codes
 */
function generateQRCode($text, $filename = null, $size = 200) {
    try {
        $sizeParam = max(1, min(10, intval($size / 50)));
        return QRcode::png($text, $filename, QR_ECLEVEL_H, $sizeParam, 2);
    } catch (Exception $e) {
        error_log("QR Code generation error: " . $e->getMessage());
        return false;
    }
}

/**
 * Validate QR code data before generation
 */
function validateQRData($data) {
    if (empty($data)) {
        return ['valid' => false, 'message' => 'QR code data cannot be empty'];
    }
    
    if (strlen($data) > 2953) {
        return ['valid' => false, 'message' => 'QR code data too long (max 2953 characters)'];
    }
    
    if (strpos($data, 'http') === 0) {
        if (!filter_var($data, FILTER_VALIDATE_URL)) {
            return ['valid' => false, 'message' => 'Invalid URL format'];
        }
    }
    
    return ['valid' => true, 'message' => 'Valid QR code data'];
}
?>
