<!-- Footer -->
<footer class="footer">
  <div class="row align-items-center justify-content-xl-between">
    <div class="col-xl-6">
      <div class="copyright text-center text-xl-left text-muted">
        &copy; 2022 <a class="font-weight-bold ml-1" target="_blank">PT.Mitra Sejati Konsultan</a>
      </div>
    </div>
  </div>
</footer>
</div>
</div>
<!-- Argon Scripts -->
<!-- Core -->
<script src="<?= base_url('assets/vendor/jquery/dist/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
<!-- Optional JS -->
<script src="<?= base_url('assets/vendor/chart.js/dist/Chart.min.js') ?>"></script>
<script src="<?= base_url('assets/vendor/chart.js/dist/Chart.extension.js') ?>"></script>
<!-- Argon JS -->
<script src="<?= base_url('assets/js/argon.js?v=1.0.0') ?>"></script>
<!-- Datepicker -->
<script src="<?= base_url('assets/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') ?>"></script>
<!-- SWEETALERT -->
<script src="<?= base_url('assets/vendor/sweetalert/sweetalert2.all.min.js') ?>"></script>
<!-- Custom JS -->
<script src="<?= base_url('assets/js/script.js') ?>"></script>
<!-- Script Pilih Data Tabel Transaksi -->
<script>
  let arr;
  let idTransaksi = $('#akun');
  console.log(idTransaksi);

  $('#buttonPilih').click(function() {
    arr = $('#tabelModalTransaksi').find('[type="radio"]:checked').map(function() {
      return $(this).closest('tr').find('td:nth-child(2)').text();
    }).get();

    idTransaksi.val(arr);
  });
</script>
<!-- Selesai Script Pilih Data Tabel Transaksi -->
<?php
$pesan = $this->session->flashdata('berhasil');
if (!empty($pesan)) :
?>
  <!-- SCRIPT SWEETALERT INLINE -->
  <script>
    $(window).on('load', function() {
      let pesan = "<?= $pesan ?>";
      swal('Berhasil!', pesan, 'success');
    });
  </script>
<?php endif; ?>

<?php
$pesan = $this->session->flashdata('berhasilHapus');
if (!empty($pesan)) :
?>
  <script>
    $(window).on('load', function() {
      let pesan = "<?= $pesan ?>";
      swal('Berhasil!', pesan, 'success');
    });
  </script>
<?php endif; ?>

<?php
$pesan = $this->session->flashdata('dataNull');
if (!empty($pesan)) :
?>
  <script>
    $(window).on('load', function() {
      let pesan = "<?= $pesan ?>";
      swal('Oops!', pesan, 'error');
    });
  </script>
<?php endif; ?>
</body>

</html>