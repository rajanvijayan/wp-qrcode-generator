<?php
/**
 * Plugin Name: QR Code Generator with Logo
 * Description: Generate QR codes with a custom logo for URLs or text.
 * Version: 1.0.0
 * Author: Rajan Vijayan
 * Author URI: https://rajanvijayan.com
 * Text Domain: qr-code-generator
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/QRGenerator.php';

use QRCodeGenerator\QRGenerator;

// Shortcode to display QR Code Generator Form
function qrcode_generator_shortcode() {
    ob_start();
    include __DIR__ . '/templates/form.php';
    return ob_get_clean();
}
add_shortcode('qr_code_generator', 'qrcode_generator_shortcode');

// Handle QR Code Generation
function qrcode_generator_generate() {
    if (!isset($_POST['qr_nonce']) || !wp_verify_nonce($_POST['qr_nonce'], 'generate_qr')) {
        wp_send_json_error(['message' => 'Invalid Request']);
    }

    $text = sanitize_text_field($_POST['qr_text'] ?? '');
    if (empty($text)) {
        wp_send_json_error(['message' => 'Text cannot be empty']);
    }

    $qr = new QRGenerator($text);
    $image_url = $qr->generate();

    wp_send_json_success(['qr_image' => $image_url]);
}
add_action('wp_ajax_generate_qr', 'qrcode_generator_generate');
add_action('wp_ajax_nopriv_generate_qr', 'qrcode_generator_generate');