  <!-- Main content -->
  <div class="main-content">
    <!-- Top navbar -->
    <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
      <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="<?= base_url('laporan_keuangan/labaRugi') ?>">&laquo <?= $titleTag; ?> Laba / Rugi</a>
        <ul class="navbar-nav align-items-center d-none d-md-flex">
          <li class="nav-item dropdown">
            <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <div class="media align-items-center">
                <div class="media-body ml-2 d-none d-lg-block">
                  <span class="mb-0 text-sm  font-weight-bold"><?= ucwords($this->session->userdata('username')) ?></span>
                </div>
              </div>
            </a>
            <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
              <a href="<?= base_url('logout') ?>" class="dropdown-item">
                <i class="ni ni-user-run"></i>
                <span>Logout</span>
              </a>
            </div>
          </li>
        </ul>
      </div>
    </nav>
    <!-- Header -->
    <div class="header bg-gradient-warning pb-8 pt-5 pt-md-8">
      <div class="container-fluid">
        <div class="header-body">
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--7">
      <div class="row">
        <div class="col-xl-8 mb-5 mb-xl-0">

        </div>
      </div>
      <div class="row mt-5">
        <div class="col mb-5 mb-xl-0">
          <div class="card shadow">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Laporan Keuangan Laba / Rugi Bulan <?= bulan($bulan); ?> Tahun <?= $tahun; ?></h3>
                  <?= form_open('laporan/excel/labaRugi', '', ['bulan' => $bulan, 'tahun' => $tahun]) ?>
                  <?= form_button(['type' => 'submit', 'content' => 'Unduh Laporan', 'class' => 'btn btn-success mr-3 mt-2']) ?>
                  <?= form_close() ?>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <?php
              $a = 0;
              $j = 0;
              $debit = 0;
              $kredit = 0;
              $debP = [];
              $debB = [];
              ?>
              <!-- Projects table -->
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Pendapatan : </th>
                    <th scope="col" class="text-right">Nominal</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $totalP = 0;
                  for ($i = 0; $i < $jumlahP; $i++) :
                    $a++;
                    $s = 0;
                    $debP = $saldoP[$i];
                    for ($k = 0; $k < $jumlahP; $k++) {
                      if ($j != $k) {
                        if ($debP == $saldoP[$k]) {
                          $saldoP[$k] = "";
                        }
                      }
                    }
                  ?>
                    <tr>
                      <?php if ($debP != "") : ?>
                        <td>
                          <?= $dataP[$i][$s]->nama_reff ?>
                        </td>
                        <?php
                        for ($j = 0; $j < count($dataP[$i]); $j++) :
                          if ($debP[$j] != "") {
                            $kredit = $kredit + $debP[$j]->saldo;
                            $hasil = $debit - $kredit;
                          }
                        endfor
                        ?>
                        <td class="text-right"><?= 'Rp. ' . number_format(abs($hasil), 0, ',', '.') ?></td>
                        <?php $totalP += $hasil; ?>
                        <?php
                        $debit = 0;
                        $kredit = 0;
                        ?>
                    </tr>
                  <?php endif ?>
                <?php endfor ?>
                <tr>
                  <td class="text-center"><b>Total Pendapatan</b></td>
                  <td class="text-primary text-right"><?= 'Rp. ' . number_format(abs($totalP), 0, ',', '.') ?></td>
                </tr>
                </tbody>
              </table>

              <!-- tabel beban -->
              <table class="table align-items-center table-flush mt-3">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Beban Operasional : </th>
                    <th scope="col" class="text-right">Nominal</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $totalB = 0;
                  for ($i = 0; $i < $jumlahB; $i++) :
                    $a++;
                    $s = 0;
                    $debB = $saldoB[$i];
                    for ($k = 0; $k < $jumlahB; $k++) {
                      if ($j != $k) {
                        if ($debB == $saldoB[$k]) {
                          $saldoB[$k] = "";
                        }
                      }
                    }
                  ?>
                    <tr>
                      <?php if ($debB != "") : ?>
                        <td>
                          <?= $dataB[$i][$s]->nama_reff ?>
                        </td>
                        <?php
                        for ($j = 0; $j < count($dataB[$i]); $j++) :
                          if ($debB[$j] != "") {
                            $kredit = $kredit + $debB[$j]->saldo;
                            $hasil = $debit - $kredit;
                          }
                        endfor
                        ?>
                        <td class="text-right"><?= 'Rp. ' . number_format(abs($hasil), 0, ',', '.') ?></td>
                        <?php $totalB += $hasil; ?>
                        <?php
                        $debit = 0;
                        $kredit = 0;
                        ?>
                    </tr>
                  <?php endif ?>
                <?php endfor ?>
                <tr>
                  <td class="text-center"><b>Total Beban Operasional</b></td>
                  <td class="text-primary text-right"><?= 'Rp. ' . number_format(abs($totalB), 0, ',', '.') ?></td>
                </tr>
                <tr>
                  <?php $nilaiTotal = $totalP - $totalB; ?>
                  <td class="text-center"><b><?= $nilaiTotal > 0 ? 'Rugi' : 'Laba Bersih'; ?></b></td>
                  <td class="text-right <?= $nilaiTotal > 0 ? 'text-danger' : 'text-success'; ?>"><?= 'Rp. ' . number_format(abs($nilaiTotal), 0, ',', '.') ?></td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>