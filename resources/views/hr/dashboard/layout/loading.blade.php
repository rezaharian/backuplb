<div class="loader"></div>

<style>
  body {
    background-color: black; /* Warna hitam */
  }
  
  .loader {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 50px;
    height: 50px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #3498db;
    border-radius: 50%;
    animation: spin 2s linear infinite;
  }

  @keyframes spin {
    0% { transform: translate(-50%, -50%) rotate(0deg); }
    100% { transform: translate(-50%, -50%) rotate(360deg); }
  }
</style>
<script>
  // Menghilangkan elemen loading setelah halaman selesai dimuat
  window.addEventListener("load", function() {
    var loader = document.querySelector(".loader");
    loader.style.display = "none";
  });
</script>
