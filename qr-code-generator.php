<?php
/**
 * Plugin Name: Simple QR Code Generator with Logo
 * Description: Generates a QR code with a logo and label, then saves it in the uploads folder.
 * Version: 1.3
 * Author: Rajan Vijayan
 * Text Domain: qr-code-generator
 */

if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

// Include Composer autoload
require_once __DIR__ . '/vendor/autoload.php';

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

// Function to generate and save QR Code with logo and label
function generate_qr_code_image() {
    $url = 'https://emeraldnational.com/external-requisition/';

    // Get WordPress uploads directory
    $upload_dir = wp_upload_dir();
    $file_path = $upload_dir['path'] . '/qr-code-with-logo.png';

    // Check if the QR code already exists
    if (!file_exists($file_path)) {
        // Create QR Code
        $qrCode = new QrCode(
            data: $url,
            encoding: new Encoding('UTF-8'),
            size: 300,
            margin: 10,
            foregroundColor: new Color(0, 93, 55),
            backgroundColor: new Color(255, 255, 255)
        );

        // Create logo
        $logo = new Logo(
            path: __DIR__ . '/assets/logo.png', // Make sure this file exists
            resizeToWidth: 60,
            punchoutBackground: true
        );

        // Write QR code with logo and label
        $writer = new PngWriter();
        $result = $writer->write($qrCode, $logo);

        // Validate the result
        $writer->validateResult($result, $url);

        // Save QR Code Image
        $result->saveToFile($file_path);
    }

    return $file_path;
}

// Run QR code generation on plugin activation
function qr_code_generator_activate() {
    generate_qr_code_image();
}
register_activation_hook(__FILE__, 'qr_code_generator_activate');

// Shortcode to display QR Code
function qr_code_generator_display() {
    $upload_dir = wp_upload_dir();
    $file_url = $upload_dir['url'] . '/qr-code-with-logo.png';

    return '<img src="' . esc_url($file_url) . '" alt="QR Code with Logo" style="max-width: 300px;">';
}
add_shortcode('qr_code_generator', 'qr_code_generator_display');