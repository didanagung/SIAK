  <!-- Main content -->
  <div class="main-content">
    <!-- Top navbar -->
    <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
      <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="<?= base_url('laporan_keuangan/perubahanModal') ?>">&laquo Laporan Perubahan Modal</a>
        <!-- User -->
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
                  <h3 class="mb-0">Laporan Perubahan Modal <?= bulan($bulan); ?> Tahun <?= $tahun; ?></h3>
                  <?= form_open('laporan/excel/perubahanModal', '', ['bulan' => $bulan, 'tahun' => $tahun]) ?>
                  <?= form_button(['type' => 'submit', 'content' => 'Unduh Laporan', 'class' => 'btn btn-success mr-3 mt-2']) ?>
                  <?= form_close() ?>
                </div>
              </div>
            </div>


            <div class="table-responsive" hidden>
              <?php
              $a = 0;
              $debit = 0;
              $kredit = 0;
              $debLR = [];
              $j = 0;
              ?>
              <!-- Projects table -->
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">No. Akun</th>
                    <th scope="col">Nama Akun</th>
                    <th scope="col">Debit</th>
                    <th scope="col">Kredit</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $totalDebit = 0;
                  $totalKredit = 0;

                  for ($i = 0; $i < $jumlahLR; $i++) :
                    $a++;
                    $s = 0;
                    $debLR = $saldoLR[$i];
                    for ($k = 0; $k < $jumlahLR; $k++) {
                      if ($j != $k) {
                        if ($debLR == $saldoLR[$k]) {
                          $saldoLR[$k] = "";
                        }
                      }
                    }
                  ?>
                    <?php if ($debLR != "") : ?>
                      <tr>
                        <td>
                          <?= $dataLR[$i][$s]->no_reff ?>
                        </td>
                        <td>
                          <?= $dataLR[$i][$s]->nama_reff ?>
                        </td>
                        <?php
                        for ($j = 0; $j < count($dataLR[$i]); $j++) :
                          if ($debLR[$j] != "") {
                            if ($debLR[$j]->jenis_saldo == "debit") {
                              $debit = $debit + $debLR[$j]->saldo;
                            } else {
                              $kredit = $kredit + $debLR[$j]->saldo;
                            }
                            $hasilLR = $debit - $kredit;
                          }
                        endfor
                        ?>
                        <?php
                        if ($hasilLR >= 0) { ?>
                          <td><?= 'Rp. ' . number_format($hasilLR, 0, ',', '.') ?></td>
                          <td> - </td>
                          <?php $totalDebit += $hasilLR; ?>
                        <?php } else { ?>
                          <td> - </td>
                          <td><?= 'Rp. ' . number_format(abs($hasilLR), 0, ',', '.') ?></td>
                          <?php $totalKredit += $hasilLR; ?>
                        <?php } ?>
                        <?php
                        $debit = 0;
                        $kredit = 0;
                        ?>
                      </tr>
                    <?php endif ?>
                  <?php endfor ?>
                  <?php if ($totalDebit != abs($totalKredit)) { ?>
                    <tr>
                      <td class="text-center" colspan="2"><b>Total</b></td>
                      <td class="text-danger"><?= 'Rp. ' . number_format($totalDebit, 0, ',', '.') ?></td>
                      <td class="text-danger"><?= 'Rp. ' . number_format(abs($totalKredit), 0, ',', '.') ?></td>
                    </tr>
                    <?php $nilaiTotal = $totalDebit - abs($totalKredit) ?>
                  <?php } else { ?>
                    <tr>
                      <td class="text-center" colspan="2"><b>Total</b></td>
                      <td class="text-success"><?= 'Rp. ' . number_format($totalDebit, 0, ',', '.') ?></td>
                      <td class="text-success"><?= 'Rp. ' . number_format(abs($totalKredit), 0, ',', '.') ?></td>
                    </tr>
                    <?php $nilaiTotal = $totalDebit - abs($totalKredit) ?>
                  <?php } ?>
                </tbody>
              </table>
            </div>

            <div class="table-responsive">
              <?php
              $a = 0;
              $debit = 0;
              $kredit = 0;
              ?>
              <!-- Projects table -->
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Nama Akun</th>
                    <th scope="col" class="text-right">Nominal</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $totalM = 0;
                  for ($i = 0; $i < $jumlahM; $i++) :
                    $a++;
                    $s = 0;
                    $deb = $saldoM[$i];
                  ?>
                    <tr>
                      <td>
                        <?= $dataM[$i][$s]->nama_reff ?>
                      </td>
                      <?php
                      for ($j = 0; $j < count($dataM[$i]); $j++) :
                        $kredit = $kredit + $deb[$j]->saldo;
                        $hasilM = $debit - $kredit;
                      endfor
                      ?>
                      <td class="text-right"><?= 'Rp. ' . number_format(abs($hasilM), 0, ',', '.') ?></td>
                      <?php $totalM += $hasilM; ?>
                      <?php
                      $debit = 0;
                      $kredit = 0;
                      ?>
                    </tr>
                  <?php endfor ?>
                  <tr>
                    <td>Laba Bersih bulan <?= bulan($bulan); ?></td>
                    <td class="text-right"><?= 'Rp. ' . number_format(abs($nilaiTotal), 0, ',', '.') ?></td>
                  </tr>
                  <tr>
                    <td class="text-center"><b></b></td>
                    <td class="text-primary text-right"><?= 'Rp. ' . number_format(abs($totalM + $nilaiTotal), 0, ',', '.') ?></td>
                  </tr>
                  <?php
                  $totalPr = 0;
                  for ($i = 0; $i < $jumlahPr; $i++) :
                    $a++;
                    $s = 0;
                    $deb = $saldoPr[$i];
                  ?>
                    <tr>
                      <td>
                        <?= $dataPr[$i][$s]->nama_reff ?>
                      </td>
                      <?php
                      for ($j = 0; $j < count($dataPr[$i]); $j++) :
                        $kredit = $kredit + $deb[$j]->saldo;
                        $hasilPr = $debit - $kredit;
                      endfor
                      ?>
                      <td class="text-right"><?= 'Rp. ' . number_format(abs($hasilPr), 0, ',', '.') ?></td>
                      <?php $totalPr += $hasilPr; ?>
                      <?php
                      $debit = 0;
                      $kredit = 0;
                      ?>
                    </tr>
                  <?php endfor ?>
                  <tr>
                    <td><b>Modal Akhir</b></td>
                    <td class="text-success text-right"><?= 'Rp. ' . number_format(abs($totalM + $nilaiTotal - $totalPr), 0, ',', '.') ?></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>