<?php
// upload_baca_qr.php
?>
<h2>Upload & Baca QR Code</h2>
<form id="formUploadQR" enctype="multipart/form-data">
    <input type="file" name="qr_file" accept="image/*" required>
    <button type="submit">Upload & Baca</button>
</form>

<div id="hasilBacaQR" style="margin-top:20px;"></div>

<script>
    $('#formUploadQR').on('submit', function(e) {
        e.preventDefault(); // Jangan biarkan form reload halaman

        var formData = new FormData(this);

        $.ajax({
            url: '../proses/proses_upload_qr.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                $('#hasilBacaQR').html(res); // Tampilkan hasil di bawah form
            },
            error: function() {
                $('#hasilBacaQR').html('Gagal membaca QR.');
            }
        });
    });
</script>
