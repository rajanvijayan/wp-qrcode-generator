<form id="qr-code-form">
    <input type="text" name="qr_text" id="qr_text" placeholder="Enter text or URL" required>
    <input type="hidden" name="qr_nonce" value="<?php echo wp_create_nonce('generate_qr'); ?>">
    <button type="submit">Generate QR Code</button>
</form>
<div id="qr-code-result"></div>

<script>
document.getElementById('qr-code-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    let formData = new FormData(this);
    
    fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
        method: 'POST',
        body: new URLSearchParams(new FormData(this))
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('qr-code-result').innerHTML = `<img src="${data.qr_image}" alt="QR Code">`;
        } else {
            alert(data.message);
        }
    });
});
</script>