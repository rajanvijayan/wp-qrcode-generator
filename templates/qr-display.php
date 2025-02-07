<?php
if (!defined('ABSPATH')) {
    exit;
}

?>

<div id="qr-code-container">
    <h3>Your QR Code</h3>
    <div id="qr-code-result">
        <!-- QR Code Image will be displayed here -->
    </div>
    <button id="download-qr" style="display:none;">Download QR Code</button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('qr-code-form').addEventListener('submit', function (e) {
        e.preventDefault();
        
        let formData = new FormData(this);

        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            body: new URLSearchParams(new FormData(this))
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let qrImage = document.createElement('img');
                qrImage.src = data.qr_image;
                qrImage.alt = "Generated QR Code";
                qrImage.id = "generated-qr-code";
                qrImage.style = "max-width: 100%; height: auto; margin-top: 10px;";

                let resultContainer = document.getElementById('qr-code-result');
                resultContainer.innerHTML = '';
                resultContainer.appendChild(qrImage);

                // Show download button
                let downloadBtn = document.getElementById('download-qr');
                downloadBtn.style.display = "inline-block";
                downloadBtn.onclick = function () {
                    let link = document.createElement('a');
                    link.href = data.qr_image;
                    link.download = "qr-code.png";
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                };
            } else {
                alert(data.message);
            }
        });
    });
});
</script>