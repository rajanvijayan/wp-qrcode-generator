<?php
namespace QRCodeGenerator;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Color\Color;

class QRGenerator {
    private $text;

    public function __construct($text) {
        $this->text = $text;
    }

    public function generate() {
        $qrCode = QrCode::create($this->text)
            ->setSize(300)
            ->setMargin(10)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255))
            ->setWriter(new PngWriter());

        // Set logo path
        $logoPath = plugin_dir_path(__DIR__) . 'assets/logo.png';
        if (file_exists($logoPath)) {
            $qrCode->setLogo(new Logo($logoPath, 50));
        }

        // Save the QR Code
        $upload_dir = wp_upload_dir();
        $file_path = $upload_dir['path'] . '/' . md5($this->text) . '.png';
        file_put_contents($file_path, $qrCode->writeString());

        return $upload_dir['url'] . '/' . md5($this->text) . '.png';
    }
}