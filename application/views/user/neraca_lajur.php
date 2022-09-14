  <!-- Main content -->
  <div class="main-content">
    <!-- Top navbar -->
    <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
      <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="<?= base_url('neraca_lajur') ?>">&laquo <?= $titleTag; ?></a>
        <!-- Form -->
        <form class="navbar-search navbar-search-dark form-inline mr-3 d-none d-md-flex ml-lg-auto">
          <div class="form-group mb-0">

          </div>
        </form>
        <!-- User -->
        <ul class="navbar-nav align-items-center d-none d-md-flex">
          <li class="nav-item dropdown">
            <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <div class="media align-items-center">
                <span class="avatar avatar-sm rounded-circle">
                  <img alt="Image placeholder" src="<?= base_url('assets/img/theme/team-4-800x800.jpg') ?>">
                </span>
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
      <div class="row mt-5">
        <div class="col mb-5 mb-xl-0">
          <div class="card shadow">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Neraca Saldo</h3>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <?php
              $b = 0;
              $debitNs = 0;
              $kreditNs = 0;
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
                  $totalDebitNs = 0;
                  $totalKreditNs = 0;
                  for ($i = 0; $i < $jumlahNs; $i++) :
                    $b++;
                    $t = 0;
                    $debNs = $saldoNs[$i];
                  ?>
                    <tr>
                      <td>
                        <?= $dataNs[$i][$t]->no_reff ?>
                      </td>
                      <td>
                        <?= $dataNs[$i][$t]->nama_reff ?>
                      </td>
                      <?php
                      for ($j = 0; $j < count($dataNs[$i]); $j++) :
                        if ($debNs[$j]->jenis_saldo == "debit") {
                          $debitNs = $debitNs + $debNs[$j]->saldo;
                        } else {
                          $kreditNs = $kreditNs + $debNs[$j]->saldo;
                        }
                        $hasilNs = $debitNs - $kreditNs;
                      endfor
                      ?>
                      <?php
                      if ($hasilNs >= 0) { ?>
                        <td><?= 'Rp. ' . number_format($hasilNs, 0, ',', '.') ?></td>
                        <td> - </td>
                        <?php $totalDebitNs += $hasilNs; ?>
                      <?php } else { ?>
                        <td> - </td>
                        <td><?= 'Rp. ' . number_format(abs($hasilNs), 0, ',', '.') ?></td>
                        <?php $totalKreditNs += $hasilNs; ?>
                      <?php } ?>
                      <?php
                      $debitNs = 0;
                      $kreditNs = 0;
                      ?>
                    </tr>
                  <?php endfor ?>
                  <?php if ($totalDebitNs != abs($totalKreditNs)) { ?>
                    <tr>
                      <td class="text-center" colspan="2"><b>Total</b></td>
                      <td class="text-danger"><?= 'Rp. ' . number_format($totalDebitNs, 0, ',', '.') ?></td>
                      <td class="text-danger"><?= 'Rp. ' . number_format(abs($totalKreditNs), 0, ',', '.') ?></td>
                    </tr>
                    <tr class="bg-danger text-center">
                      <td colspan="6" class="text-white" style="font-weight:bolder;font-size:19px">TIDAK SEIMBANG</td>
                    </tr>
                  <?php } else { ?>
                    <tr>
                      <td class="text-center" colspan="2"><b>Total</b></td>
                      <td class="text-success"><?= 'Rp. ' . number_format($totalDebitNs, 0, ',', '.') ?></td>
                      <td class="text-success"><?= 'Rp. ' . number_format(abs($totalKreditNs), 0, ',', '.') ?></td>
                    </tr>
                    <tr class="bg-success text-center">
                      <td colspan="6" class="text-white" style="font-weight:bolder;font-size:19px">SEIMBANG</td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-5">
        <div class="col mb-5 mb-xl-0">
          <div class="card shadow">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Ayat Jurnal Penyesuaian</h3>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <?php
              $c = 0;
              $debitJp = 0;
              $kreditJp = 0;
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
                  $totalDebitJp = 0;
                  $totalKreditJp = 0;
                  for ($i = 0; $i < $jumlahJp; $i++) :
                    $c++;
                    $u = 0;
                    $debJp = $saldoJp[$i];
                  ?>
                    <tr>
                      <td>
                        <?= $dataJp[$i][$u]->no_reff ?>
                      </td>
                      <td>
                        <?= $dataJp[$i][$u]->nama_reff ?>
                      </td>
                      <?php
                      for ($j = 0; $j < count($dataJp[$i]); $j++) :
                        if ($debJp[$j]->jenis_saldo == "debit") {
                          $debitJp = $debitJp + $debJp[$j]->saldo;
                        } else {
                          $kreditJp = $kreditJp + $debJp[$j]->saldo;
                        }
                        $hasilJp = $debitJp - $kreditJp;
                      endfor
                      ?>
                      <?php
                      if ($hasilJp >= 0) { ?>
                        <td><?= 'Rp. ' . number_format($hasilJp, 0, ',', '.') ?></td>
                        <td> - </td>
                        <?php $totalDebitJp += $hasilJp; ?>
                      <?php } else { ?>
                        <td> - </td>
                        <td><?= 'Rp. ' . number_format(abs($hasilJp), 0, ',', '.') ?></td>
                        <?php $totalKreditJp += $hasilJp; ?>
                      <?php } ?>
                      <?php
                      $debitJp = 0;
                      $kreditJp = 0;
                      ?>
                    </tr>
                  <?php endfor ?>
                  <?php if ($totalDebitJp != abs($totalKreditJp)) { ?>
                    <tr>
                      <td class="text-center" colspan="2"><b>Total</b></td>
                      <td class="text-danger"><?= 'Rp. ' . number_format($totalDebitJp, 0, ',', '.') ?></td>
                      <td class="text-danger"><?= 'Rp. ' . number_format(abs($totalKreditJp), 0, ',', '.') ?></td>
                    </tr>
                    <tr class="bg-danger text-center">
                      <td colspan="6" class="text-white" style="font-weight:bolder;font-size:19px">TIDAK SEIMBANG</td>
                    </tr>
                  <?php } else { ?>
                    <tr>
                      <td class="text-center" colspan="2"><b>Total</b></td>
                      <td class="text-success"><?= 'Rp. ' . number_format($totalDebitJp, 0, ',', '.') ?></td>
                      <td class="text-success"><?= 'Rp. ' . number_format(abs($totalKreditJp), 0, ',', '.') ?></td>
                    </tr>
                    <tr class="bg-success text-center">
                      <td colspan="6" class="text-white" style="font-weight:bolder;font-size:19px">SEIMBANG</td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-5">
        <div class="col mb-5 mb-xl-0">
          <div class="card shadow">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0"><?= $titleTag; ?></h3>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <?php
              $a = 0;
              $debit = 0;
              $kredit = 0;
              $deb = [];
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

                  for ($i = 0; $i < $jumlah; $i++) :
                    $a++;
                    $s = 0;
                    $deb = $saldo[$i];
                    for ($k = 0; $k < $jumlah; $k++) {
                      if ($j != $k) {
                        if ($deb == $saldo[$k]) {
                          $saldo[$k] = "";
                        }
                      }
                    }
                  ?>
                    <?php if ($deb != "") : ?>
                      <tr>
                        <td>
                          <?= $data[$i][$s]->no_reff ?>
                        </td>
                        <td>
                          <?= $data[$i][$s]->nama_reff ?>
                        </td>
                        <?php
                        for ($j = 0; $j < count($data[$i]); $j++) :
                          if ($deb[$j] != "") {
                            if ($deb[$j]->jenis_saldo == "debit") {
                              $debit = $debit + $deb[$j]->saldo;
                            } else {
                              $kredit = $kredit + $deb[$j]->saldo;
                            }
                            $hasil = $debit - $kredit;
                          }
                        endfor
                        ?>
                        <?php
                        if ($hasil >= 0) { ?>
                          <td><?= 'Rp. ' . number_format($hasil, 0, ',', '.') ?></td>
                          <td> - </td>
                          <?php $totalDebit += $hasil; ?>
                        <?php } else { ?>
                          <td> - </td>
                          <td><?= 'Rp. ' . number_format(abs($hasil), 0, ',', '.') ?></td>
                          <?php $totalKredit += $hasil; ?>
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
                    <tr class="bg-danger text-center">
                      <td colspan="6" class="text-white" style="font-weight:bolder;font-size:19px">TIDAK SEIMBANG</td>
                    </tr>
                  <?php } else { ?>
                    <tr>
                      <td class="text-center" colspan="2"><b>Total</b></td>
                      <td class="text-success"><?= 'Rp. ' . number_format($totalDebit, 0, ',', '.') ?></td>
                      <td class="text-success"><?= 'Rp. ' . number_format(abs($totalKredit), 0, ',', '.') ?></td>
                    </tr>
                    <tr class="bg-success text-center">
                      <td colspan="6" class="text-white" style="font-weight:bolder;font-size:19px">SEIMBANG</td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>