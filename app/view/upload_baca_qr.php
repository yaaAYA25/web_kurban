<?php
?>
<style>
  /* Reset margin dan height full untuk html & body */
  html, body {
    margin: 0;
    padding: 0;
    height: 100%;
    overflow: hidden;
  }
</style>

<div class="flex items-start justify-center h-screen bg-gray-100 pt-20">
  <div class="w-full max-w-md bg-white shadow-lg rounded-2xl animate-fade-in mb-10">

    <h2 class="text-2xl font-bold text-cyan-600 text-center py-6 m-0">
      Upload & Baca QR Code
    </h2>

    <form id="formUploadQR" enctype="multipart/form-data" class="px-6 pb-6 space-y-4 m-0">
      <input type="file" name="qr_file" accept="image/*" required
             class="w-full text-sm text-gray-700
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-md file:border-0
                    file:text-sm file:font-semibold
                    file:bg-cyan-100 file:text-cyan-700
                    hover:file:bg-cyan-200 transition duration-300"/>

      <button type="submit"
              class="w-full bg-cyan-600 text-white font-semibold py-2 rounded-lg shadow-sm
                     hover:bg-cyan-700 active:scale-95 transition-all duration-200">
        Upload & Baca
      </button>
    </form>

    <div id="hasilBacaQR"
         class="text-center text-gray-800 bg-gray-50 rounded-lg shadow-sm
                opacity-0 transition-opacity duration-500 mt-4 mx-6 py-3">
    </div>
  </div>
</div>

<style>
@keyframes fade-in {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
.animate-fade-in {
  animation: fade-in 0.7s ease-out forwards;
}
</style>

<script>
  $('#formUploadQR').on('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const hasilDiv = $('#hasilBacaQR');

    hasilDiv.removeClass('opacity-100').addClass('opacity-0');

    $.ajax({
      url: '../proses/proses_upload_qr.php',
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function (res) {
        hasilDiv.html(res)
                .removeClass('opacity-0')
                .addClass('opacity-100');
      },
      error: function () {
        hasilDiv.html('<span class="text-red-600">❌ Gagal membaca QR.</span>')
                .removeClass('opacity-0')
                .addClass('opacity-100');
      }
    });
  });
</script>
