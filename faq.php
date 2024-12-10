<?php
include './includes/navbar_user.php';
?>

<style>
  .accordion-button {
    /* Border tombol */
    font-weight: bold;
    /* Ketebalan font untuk pertanyaan */
  }
</style>

<div class="container mt-5">
  <h1 class="text-center mb-5">Pertanyaan Yang Sering Ditanyakan</h1>
  <div class="accordion" id="accordionExample">
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingOne">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
        Apakah harus login untuk melakukan pembelian di toko ini?
        </button>
      </h2>
      <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
        <div class="accordion-body">
        Ya, Anda dapat melakukan transaksi jika sudah login. Jika belum memiliki akun, silakan daftar terlebih dahulu.
        </div>
      </div>
    </div>
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingTwo">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          Apakah ada garansi untuk sepeda yang dibeli?
        </button>
      </h2>
      <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
        <div class="accordion-body">
          Ya, semua sepeda yang kami jual dilengkapi dengan garansi pabrik. Garansi ini mencakup kerusakan akibat cacat produksi. Silakan cek syarat dan ketentuan garansi untuk informasi lebih lanjut.
        </div>
      </div>
    </div>
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingThree">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          Bagaimana cara merawat sepeda agar tetap awet?
        </button>
      </h2>
      <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
        <div class="accordion-body">
          Untuk merawat sepeda Anda, pastikan untuk rutin membersihkan dan melumasi rantai, memeriksa tekanan ban, dan memastikan rem berfungsi dengan baik. Kami juga menyediakan layanan perawatan sepeda di toko kami.
        </div>
      </div>
    </div>
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingFour">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
          Apakah toko ini menyediakan layanan perbaikan sepeda?
        </button>
      </h2>
      <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
        <div class="accordion-body">
          Ya, kami menyediakan layanan perbaikan sepeda untuk semua jenis sepeda. Tim teknisi kami siap membantu Anda dengan perbaikan dan pemeliharaan sepeda Anda.
        </div>
      </div>
    </div>
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingFive">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
          Apakah ada diskon untuk pembelian dalam jumlah banyak?
        </button>
      </h2>
      <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
        <div class="accordion-body">
          Kami menawarkan diskon untuk pembelian dalam jumlah banyak. Silakan hubungi kami untuk informasi lebih lanjut mengenai harga dan penawaran khusus.
        </div>
      </div>
    </div>
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingSix">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
          Bagaimana cara melakukan pemesanan sepeda secara online?
        </button>
      </h2>
      <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#accordionExample">
        <div class="accordion-body">
          Anda dapat melakukan pemesanan sepeda secara online melalui website kami. Pilih sepeda yang Anda inginkan, masukkan ke keranjang belanja, dan ikuti langkah-langkah untuk menyelesaikan pembayaran.
        </div>
      </div>
    </div>
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingSeven">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
          Apakah ada program loyalitas untuk pelanggan?
        </button>
      </h2>
      <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#accordionExample">
        <div class="accordion-body">
          Ya, kami memiliki program loyalitas untuk pelanggan setia. Anda dapat mengumpulkan poin setiap kali berbelanja dan menukarkannya dengan diskon atau hadiah menarik.
        </div>
      </div>
    </div>
  </div>
</div>