<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url', 'form', 'sia', 'tgl_indo']);
        $this->load->library(['session', 'form_validation']);
        $this->load->model('Akun_model', 'akun', true);
        $this->load->model('Jurnal_model', 'jurnal', true);
        $this->load->model('JurnalPenyesuaian_model', 'jurnalPenyesuaian', true);
        $this->load->model('User_model', 'user', true);
        $login = $this->session->userdata('login');
        if (!$login) {
            redirect('login');
        }
    }

    public function index()
    {
        $titleTag = 'Dashboard';
        $content = 'user/dashboard';
        $dataAkun = $this->akun->getAkun();
        $dataAkunTransaksi = $this->jurnal->getAkunInJurnal();

        foreach ($dataAkunTransaksi as $row) {
            $data[] = (array) $this->jurnal->getJurnalByNoReff($row->no_reff);
            $saldo[] = (array) $this->jurnal->getJurnalByNoReffSaldo($row->no_reff);
        }
        $jumlah = count($data);

        $jurnals = $this->jurnal->getJurnalJoinAkun();
        $totalDebit = $this->jurnal->getTotalSaldo('debit');
        $totalKredit = $this->jurnal->getTotalSaldo('kredit');
        $this->load->view('template', compact('content', 'dataAkun', 'titleTag', 'jurnals', 'totalDebit', 'totalKredit', 'jumlah', 'data', 'saldo', 'dataAkunTransaksi'));
    }

    public function dataAkun()
    {
        $content = 'user/data_akun';
        $titleTag = 'Data Akun';
        $dataAkun = $this->akun->getAkun();
        $this->load->view('template', compact('content', 'dataAkun', 'titleTag'));
    }

    public function isNamaAkunThere($str)
    {
        $namaAkun = $this->akun->countAkunByNama($str);
        if ($namaAkun >= 1) {
            $this->form_validation->set_message('isNamaAkunThere', 'Nama Akun Sudah Ada');
            return false;
        }
        return true;
    }

    public function isNoAkunThere($str)
    {
        $noAkun = $this->akun->countAkunByNoReff($str);
        if ($noAkun >= 1) {
            $this->form_validation->set_message('isNoAkunThere', 'No.Reff Sudah Ada');
            return false;
        }
        return true;
    }

    public function createAkun()
    {
        $title = 'Tambah';
        $titleTag = 'Data Akun';
        $action = 'data_akun/tambah';
        $content = 'user/form_akun';

        if (!$_POST) {
            $data = (object) $this->akun->getDefaultValues();
        } else {
            $data = (object) $this->input->post(null, true);
            $data->id_user = $this->session->userdata('id');
        }

        if (!$this->akun->validate()) {
            $this->load->view('template', compact('content', 'title', 'action', 'data', 'titleTag'));
            return;
        }

        $this->akun->insertAkun($data);
        $this->session->set_flashdata('berhasil', 'Data Akun Berhasil Di Tambahkan');
        redirect('data_akun');
    }

    // public function editAkun($no_reff = null)
    // {
    //     // if ($this->session->userdata('role') != 'direktur') {
    //     //     show_404();
    //     // } else {
    //     $title = 'Edit';
    //     $titleTag = 'Data Akun';
    //     $action = 'data_akun/edit/' . $no_reff;
    //     $content = 'user/form_akun';

    //     if (!$_POST) {
    //         $data = (object) $this->akun->getAkunByNo($no_reff);
    //     } else {
    //         $data = (object) $this->input->post(null, true);
    //         $data->id_user = $this->session->userdata('id');
    //     }

    //     if (!$this->akun->validate()) {
    //         $this->load->view('template', compact('content', 'title', 'action', 'data', 'titleTag'));
    //         return;
    //     }

    //     $this->akun->updateAkun($no_reff, $data);
    //     $this->session->set_flashdata('berhasil', 'Data Akun Berhasil Di Ubah');
    //     redirect('data_akun');
    //     // }
    // }

    public function deleteAkun()
    {
        $id = $this->input->post('id', true);
        $noReffTransaksi = $this->jurnal->countJurnalNoReff($id);
        $noReffPenyesuaian = $this->jurnalPenyesuaian->countJurnalNoReff($id);
        if ($noReffTransaksi || $noReffPenyesuaian > 0) {
            $this->session->set_flashdata('dataNull', 'No.Reff ' . $id . ' Tidak Bisa Di Hapus Karena Data Akun Ada Di Jurnal Umum');
            redirect('data_akun');
        }
        $this->akun->deleteAkun($id);
        $this->session->set_flashdata('berhasilHapus', 'Data akun dengan No.Reff ' . $id . ' berhasil di hapus');
        redirect('data_akun');
    }

    public function jurnalUmum()
    {
        $titleTag = 'Jurnal Umum';
        $content = 'user/jurnal_umum_main';
        $listJurnal = $this->jurnal->getJurnalByYearAndMonth();
        $tahun = $this->jurnal->getJurnalByYear();
        $this->load->view('template', compact('content', 'listJurnal', 'titleTag', 'tahun'));
    }

    public function jurnalUmumDetail()
    {
        $content = 'user/jurnal_umum';
        $titleTag = 'Jurnal Umum';

        $bulan = $this->input->post('bulan', true);
        $tahun = $this->input->post('tahun', true);
        $jurnals = null;

        if (empty($bulan) || empty($tahun)) {
            redirect('jurnal_umum');
        }

        $jurnals = $this->jurnal->getJurnalJoinAkunDetail($bulan, $tahun);
        $totalDebit = $this->jurnal->getTotalSaldoDetail('debit', $bulan, $tahun);
        $totalKredit = $this->jurnal->getTotalSaldoDetail('kredit', $bulan, $tahun);

        if ($jurnals == null) {
            $this->session->set_flashdata('dataNull', 'Data Jurnal Dengan Bulan ' . bulan($bulan) . ' Pada Tahun ' . date('Y', strtotime($tahun)) . ' Tidak Di Temukan');
            redirect('jurnal_umum');
        }

        $this->load->view('template', compact('content', 'jurnals', 'totalDebit', 'totalKredit', 'titleTag'));
    }

    public function createJurnal()
    {
        $title = 'Tambah';
        $content = 'user/form_jurnal';
        $action = 'jurnal_umum/tambah';
        $tgl_input = date('Y-m-d H:i:s');
        $id_user = $this->session->userdata('id');
        $titleTag = 'Jurnal Umum';

        if (!$_POST) {
            $data = (object) $this->jurnal->getDefaultValues();
        } else {
            $data = (object) [
                'id_user' => $id_user,
                'no_reff' => $this->input->post('no_reff', true),
                'tgl_input' => $tgl_input,
                'tgl_transaksi' => $this->input->post('tgl_transaksi', true),
                'jenis_saldo' => $this->input->post('jenis_saldo', true),
                'saldo' => $this->input->post('saldo', true)
            ];
        }

        if (!$this->jurnal->validate()) {
            $this->load->view('template', compact('content', 'title', 'action', 'data', 'titleTag'));
            return;
        }

        $this->jurnal->insertJurnal($data);
        $this->session->set_flashdata('berhasil', 'Data Jurnal Berhasil Di Tambahkan');
        redirect('jurnal_umum');
    }

    public function editForm()
    {
        if ($_POST) {
            $id = $this->input->post('id', true);
            $title = 'Edit';
            $content = 'user/form_jurnal';
            $action = 'jurnal_umum/edit';
            $titleTag = 'Edit Jurnal Umum';

            $data = (object) $this->jurnal->getJurnalById($id);

            $this->load->view('template', compact('content', 'title', 'action', 'data', 'id', 'titleTag'));
        } else {
            redirect('jurnal_umum');
        }
    }

    public function editJurnal()
    {
        $title = 'Edit';
        $content = 'user/form_jurnal';
        $action = 'jurnal_umum/edit';
        $tgl_input = date('Y-m-d H:i:s');
        $id_user = $this->session->userdata('id');
        $titleTag = 'Jurnal Umum';

        if ($_POST) {
            $data = (object) [
                'id_user' => $id_user,
                'no_reff' => $this->input->post('no_reff', true),
                'tgl_input' => $tgl_input,
                'tgl_transaksi' => $this->input->post('tgl_transaksi', true),
                'jenis_saldo' => $this->input->post('jenis_saldo', true),
                'saldo' => $this->input->post('saldo', true)
            ];
            $id = $this->input->post('id', true);
        }

        if (!$this->jurnal->validate()) {
            $this->load->view('template', compact('content', 'title', 'action', 'data', 'id', 'titleTag'));
            return;
        }

        $this->jurnal->updateJurnal($id, $data);
        $this->session->set_flashdata('berhasil', 'Data Jurnal Berhasil Di Ubah');
        redirect('jurnal_umum');
    }

    public function deleteJurnal()
    {
        $id = $this->input->post('id', true);
        $this->jurnal->deleteJurnal($id);
        $this->session->set_flashdata('berhasilHapus', 'Data Jurnal berhasil di hapus');
        redirect('jurnal_umum');
    }

    public function bukuBesar()
    {
        $titleTag = 'Buku Besar';
        $content = 'user/buku_besar_main';
        $listJurnal = $this->jurnal->getJurnalByYearAndMonth();
        $tahun = $this->jurnal->getJurnalByYear();
        $this->load->view('template', compact('content', 'listJurnal', 'titleTag', 'tahun'));
    }

    public function bukuBesarDetail()
    {
        $content = 'user/buku_besar';
        $titleTag = 'Buku Besar';

        $bulan = $this->input->post('bulan', true);
        $tahun = $this->input->post('tahun', true);

        if (empty($bulan) || empty($tahun)) {
            redirect('buku_besar');
        }

        $dataAkun = $this->akun->getAkunByMonthYear($bulan, $tahun);
        $data = null;
        $saldo = null;

        foreach ($dataAkun as $row) {
            $data[] = (array) $this->jurnal->getJurnalByNoReffMonthYear($row->no_reff, $bulan, $tahun);
            $saldo[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYear($row->no_reff, $bulan, $tahun);
        }

        if ($data == null || $saldo == null) {
            $this->session->set_flashdata('dataNull', 'Data Buku Besar Dengan Bulan ' . bulan($bulan) . ' Pada Tahun ' . date('Y', strtotime($tahun)) . ' Tidak Di Temukan');
            redirect('buku_besar');
        }

        $jumlah = count($data);

        $this->load->view('template', compact('content', 'titleTag', 'dataAkun', 'data', 'jumlah', 'saldo'));
    }

    public function neracaSaldo()
    {
        $titleTag = 'Neraca Saldo';
        $content = 'user/neraca_saldo_main';
        $listJurnal = $this->jurnal->getJurnalByYearAndMonth();
        $tahun = $this->jurnal->getJurnalByYear();
        $this->load->view('template', compact('content', 'listJurnal', 'titleTag', 'tahun'));
    }

    public function neracaSaldoDetail()
    {
        $content = 'user/neraca_saldo';
        $titleTag = 'Neraca Saldo';

        $bulan = $this->input->post('bulan', true);
        $tahun = $this->input->post('tahun', true);

        if (empty($bulan) || empty($tahun)) {
            redirect('neraca_saldo');
        }

        $dataAkun = $this->akun->getAkunByMonthYear($bulan, $tahun);
        $data = null;
        $saldo = null;

        foreach ($dataAkun as $row) {
            $data[] = (array) $this->jurnal->getJurnalByNoReffMonthYear($row->no_reff, $bulan, $tahun);
            $saldo[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYear($row->no_reff, $bulan, $tahun);
        }

        if ($data == null || $saldo == null) {
            $this->session->set_flashdata('dataNull', 'Neraca Saldo Dengan Bulan ' . bulan($bulan) . ' Pada Tahun ' . date('Y', strtotime($tahun)) . ' Tidak Di Temukan');
            redirect('neraca_saldo');
        }

        $jumlah = count($data);

        $this->load->view('template', compact('content', 'titleTag', 'dataAkun', 'data', 'jumlah', 'saldo'));
    }

    public function jurnalPenyesuaian()
    {
        $titleTag = 'Jurnal Penyesuaian';
        $content = 'user/jurnal_penyesuaian_main';
        $listJurnal = $this->jurnalPenyesuaian->getJurnalByYearAndMonth();
        $tahun = $this->jurnalPenyesuaian->getJurnalByYear();
        $this->load->view('template', compact('content', 'listJurnal', 'titleTag', 'tahun'));
    }

    public function jurnalPenyesuaianDetail()
    {
        $content = 'user/jurnal_penyesuaian';
        $titleTag = 'Jurnal Penyesuaian';
        $bulan = $this->input->post('bulan', true);
        $tahun = $this->input->post('tahun', true);

        if (empty($bulan) || empty($tahun)) {
            redirect('jurnal_penyesuaian');
        }

        $jurnals = $this->jurnalPenyesuaian->getJurnalJoinAkunDetail($bulan, $tahun);
        $totalDebit = $this->jurnalPenyesuaian->getTotalSaldoDetail('debit', $bulan, $tahun);
        $totalKredit = $this->jurnalPenyesuaian->getTotalSaldoDetail('kredit', $bulan, $tahun);

        if ($jurnals == null) {
            $this->session->set_flashdata('dataNull', 'Data Jurnal Dengan Bulan ' . bulan($bulan) . ' Pada Tahun ' . date('Y', strtotime($tahun)) . ' Tidak Di Temukan');
            redirect('jurnal_penyesuaian');
        }

        $this->load->view('template', compact('content', 'jurnals', 'totalDebit', 'totalKredit', 'titleTag'));
    }

    public function createJurnalPenyesuaian()
    {

        $title = 'Tambah Penyesuaian';
        $content = 'user/form_jurnal_penyesuaian';
        $action = 'jurnal_penyesuaian/tambah';
        $tgl_input = date('Y-m-d H:i:s');
        $id_user = $this->session->userdata('id');
        $titleTag = 'Jurnal Penyesuaian';

        if (!$_POST) {
            $data = (object) $this->jurnalPenyesuaian->getDefaultValues();
        } else {
            $data = (object) [
                'id_user' => $id_user,
                'no_reff' => $this->input->post('no_reff', true),
                'tgl_input' => $tgl_input,
                'tgl_transaksi' => $this->input->post('tgl_transaksi', true),
                'jenis_saldo' => $this->input->post('jenis_saldo', true),
                'saldo' => $this->input->post('saldo', true)
            ];
        }

        if (!$this->jurnalPenyesuaian->validate()) {
            $this->load->view('template', compact('content', 'title', 'action', 'data', 'titleTag'));
            return;
        }

        $this->jurnalPenyesuaian->insertJurnal($data);
        $this->session->set_flashdata('berhasil', 'Data Jurnal Penyesuaian Berhasil Di Tambahkan');
        redirect('jurnal_penyesuaian');
    }

    public function editFormJPenyesuaian()
    {
        if ($_POST) {
            $id = $this->input->post('id', true);
            $title = 'Edit';
            $content = 'user/form_jurnal_penyesuaian';
            $action = 'jurnal_penyesuaian/edit';
            $titleTag = 'Jurnal Penyesuaian';
            $jurnals = $this->jurnal->getJurnalJoinAkun();

            $data = (object) $this->jurnalPenyesuaian->getJurnalById($id);

            $this->load->view('template', compact('content', 'title', 'action', 'data', 'id', 'titleTag', 'jurnals'));
        } else {
            redirect('jurnal_penyesuaian');
        }
    }

    public function editJurnalPenyesuaian()
    {
        $title = 'Edit';
        $content = 'user/form_jurnal_penyesuaian';
        $action = 'jurnal_penyesuaian/edit';
        $tgl_input = date('Y-m-d H:i:s');
        $id_user = $this->session->userdata('id');
        $titleTag = 'Edit Jurnal Penyesuaian';

        if ($_POST) {
            $data = (object) [
                'id_user' => $id_user,
                'no_reff' => $this->input->post('no_reff', true),
                'tgl_input' => $tgl_input,
                'tgl_transaksi' => $this->input->post('tgl_transaksi', true),
                'jenis_saldo' => $this->input->post('jenis_saldo', true),
                'saldo' => $this->input->post('saldo', true)
            ];
            $id = $this->input->post('id', true);
        }

        if (!$this->jurnalPenyesuaian->validate()) {
            $this->load->view('template', compact('content', 'title', 'action', 'data', 'id', 'titleTag'));
            return;
        }

        $this->jurnalPenyesuaian->updateJurnal($id, $data);
        $this->session->set_flashdata('berhasil', 'Data Jurnal Penyesuaian Berhasil Di Ubah');
        redirect('jurnal_penyesuaian');
    }

    public function deleteJurnalPenyesuaian()
    {
        $id = $this->input->post('id', true);
        $this->jurnalPenyesuaian->deleteJurnalPenyesuaian($id);
        $this->session->set_flashdata('berhasilHapus', 'Data Jurnal Penyesuaian berhasil di hapus');
        redirect('jurnal_penyesuaian');
    }

    public function neracaLajur()
    {
        $titleTag = 'Neraca Lajur';
        $content = 'user/neraca_lajur_main';
        $listJurnal = $this->jurnal->getJurnalByYearAndMonth();
        $tahun = $this->jurnal->getJurnalByYear();
        $this->load->view('template', compact('content', 'listJurnal', 'titleTag', 'tahun'));
    }

    public function neracaLajurDetail()
    {
        $content = 'user/neraca_lajur';
        $titleTag = 'Neraca Lajur';

        $bulan = $this->input->post('bulan', true);
        $tahun = $this->input->post('tahun', true);

        if (empty($bulan) || empty($tahun)) {
            redirect('neraca_lajur');
        }

        // Ns adalah data yang diambil dari neraca saldo

        $dataAkun = $this->akun->getAkunByMonthYearAfterMerge($bulan, $tahun);
        $dataAkunNs = $this->akun->getAkunByMonthYear($bulan, $tahun);
        $dataAkunJp = $this->akun->getAkunByMonthYearJp($bulan, $tahun);
        $data = null;
        $saldo = null;
        $dataNs = null;
        $saldoNs = null;
        $dataJp = null;
        $saldoJp = null;


        foreach ($dataAkun as $row) {
            $data[] = (array) $this->jurnal->getJurnalByNoReffMonthYearAfterMerge($row->no_reff, $bulan, $tahun);
            $saldo[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearAfterMerge($row->no_reff, $bulan, $tahun);
        }
        $jumlah = count($data);

        foreach ($dataAkunNs as $rowNs) {
            $dataNs[] = (array) $this->jurnal->getJurnalByNoReffMonthYear($rowNs->no_reff, $bulan, $tahun);
            $saldoNs[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYear($rowNs->no_reff, $bulan, $tahun);
        }
        $jumlahNs = count($dataNs);

        foreach ($dataAkunJp as $rowJp) {
            $dataJp[] = (array) $this->jurnalPenyesuaian->getJurnalByNoReffMonthYear($rowJp->no_reff, $bulan, $tahun);
            $saldoJp[] = (array) $this->jurnalPenyesuaian->getJurnalByNoReffSaldoMonthYear($rowJp->no_reff, $bulan, $tahun);
        }
        $jumlahJp = count($dataJp);

        if ($data == null || $saldo == null) {
            $this->session->set_flashdata('dataNull', 'Neraca Lajur Dengan Bulan ' . bulan($bulan) . ' Pada Tahun ' . date('Y', strtotime($tahun)) . ' Tidak Di Temukan');
            redirect('neraca_lajur');
        }


        $this->load->view('template', compact('content', 'titleTag', 'dataAkun', 'data', 'jumlah', 'saldo', 'dataAkunNs', 'dataNs', 'jumlahNs', 'saldoNs', 'dataAkunJp', 'dataJp', 'jumlahJp', 'saldoJp'));
    }

    public function laporanKeuangan()
    {
        if ($this->session->userdata('role') != 'direktur') {
            show_404();
        } else {
            $titleTag = 'Laporan Keuangan';
            $content = 'user/laporan_keuangan';
            $this->load->view('template', compact('content', 'titleTag'));
        }
    }

    public function laporanKeuanganLabaRugi()
    {
        if ($this->session->userdata('role') != 'direktur') {
            show_404();
        } else {
            $titleTag = 'Laporan Keuangan';
            $content = 'user/laporan_keuangan_laba_rugi_main';
            $listJurnal = $this->jurnal->getJurnalByYearAndMonth();
            $tahun = $this->jurnal->getJurnalByYear();
            $this->load->view('template', compact('content', 'listJurnal', 'titleTag', 'tahun'));
        }
    }

    public function laporanKeuanganLabaRugiDetail()
    {
        if ($this->session->userdata('role') != 'direktur') {
            show_404();
        } else {
            $content = 'user/laporan_keuangan_laba_rugi';
            $titleTag = 'Laporan Keuangan';

            $bulan = $this->input->post('bulan', true);
            $tahun = $this->input->post('tahun', true);

            if (empty($bulan) || empty($tahun)) {
                redirect('laporan_keuangan/labaRugi');
            }

            $dataAkunP = $this->akun->getAkunByMonthYearLRP($bulan, $tahun);
            $dataAkunB = $this->akun->getAkunByMonthYearLRB($bulan, $tahun);
            $dataP = null;
            $dataB = null;
            $saldoP = null;
            $saldoB = null;
            $hasil = null;
            $totalP = null;
            $totalB = null;
            $s = null;

            foreach ($dataAkunP as $rowP) {
                $dataP[] = (array) $this->jurnal->getJurnalByNoReffMonthYearP($rowP->no_reff, $bulan, $tahun);
                $saldoP[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearP($rowP->no_reff, $bulan, $tahun);
            }

            foreach ($dataAkunB as $rowB) {
                $dataB[] = (array) $this->jurnal->getJurnalByNoReffMonthYearB($rowB->no_reff, $bulan, $tahun);
                $saldoB[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearB($rowB->no_reff, $bulan, $tahun);
            }

            if ($dataP == null || $saldoP == null || $dataP == null || $saldoP == null) {
                $this->session->set_flashdata('dataNull', 'Laporan Keuangan Laba / Rugi Dengan Bulan ' . bulan($bulan) . ' Pada Tahun ' . $tahun . ' Tidak Di Temukan');
                redirect('laporan_keuangan/labaRugi');
            }

            $jumlahP = count($dataP);
            $jumlahB = count($dataB);

            $this->load->view('template', compact('content', 'titleTag', 'dataAkunP', 'dataAkunB', 'dataP', 'dataB', 'jumlahP', 'jumlahB', 'saldoP', 'saldoB', 'hasil', 'totalP', 'totalB', 's', 'bulan', 'tahun'));
        }
    }

    public function excelLaporanLabaRugi()
    {
        if ($this->session->userdata('role') != 'direktur') {
            show_404();
        } else {
            $bulan = $this->input->post('bulan', true);
            $tahun = $this->input->post('tahun', true);

            $dataAkunP = $this->akun->getAkunByMonthYearLRP($bulan, $tahun);
            $dataAkunB = $this->akun->getAkunByMonthYearLRB($bulan, $tahun);

            foreach ($dataAkunP as $row) {
                $dataP[] = (array) $this->jurnal->getJurnalByNoReffMonthYearP($row->no_reff, $bulan, $tahun);
                $saldoP[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearP($row->no_reff, $bulan, $tahun);
            }

            foreach ($dataAkunB as $row) {
                $dataB[] = (array) $this->jurnal->getJurnalByNoReffMonthYearB($row->no_reff, $bulan, $tahun);
                $saldoB[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearB($row->no_reff, $bulan, $tahun);
            }

            $jumlahP = count($dataP);
            $jumlahB = count($dataB);

            $spreadsheet = new Spreadsheet();
            $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman')->setSize(12);
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->getColumnDimension('A')->setWidth(25);
            $sheet->getColumnDimension('B')->setWidth(25);
            $sheet->getColumnDimension('C')->setWidth(25);
            $sheet->getColumnDimension('D')->setWidth(25);
            // $sheet->getStyle('C:D')->getNumberFormat('Accounting');

            $sheet->setCellValue('A1', 'PT. Mitra Sejati Konsultan');
            $sheet->setCellValue('A2', 'Laporan Laba/Rugi');
            $sheet->setCellValue('A3', 'Per ' . bulan($bulan) . " Tahun $tahun");
            $sheet->mergeCells('A1:D1');
            $sheet->mergeCells('A2:D2');
            $sheet->mergeCells('A3:D3');
            $sheet->getStyle('A1:D3')->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A1:D3')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 14
                ]
            ]);

            $sheet->setCellValue('A5', 'Pendapatan');
            $sheet->getStyle('A5')->applyFromArray([
                'font' => ['bold' => true]
            ]);
            $x = 6;
            $totalP = 0;
            $totalB = 0;
            $debitP = 0;
            $debitB = 0;
            $kreditP = 0;
            $kreditB = 0;
            $j = 0;
            $debP = [];
            $debB = [];

            for ($i = 0; $i < $jumlahP; $i++) {
                $s = 0;
                $debP = $saldoP[$i];
                for ($k = 0; $k < $jumlahP; $k++) {
                    if ($j != $k) {
                        if ($debP == $saldoP[$k]) {
                            $saldoP[$k] = "";
                        }
                    }
                }
                if ($debP != "") {
                    $sheet->setCellValue('A' . $x, $dataP[$i][$s]->nama_reff);
                    for ($j = 0; $j < count($dataP[$i]); $j++) {
                        if ($debP[$j] != "") {
                            $kreditP = $kreditP + $debP[$j]->saldo;
                            $hasilP = $kreditP - $debitP;
                        }
                    }
                    $sheet->setCellValue("C$x", $hasilP);
                    $totalP += $hasilP;
                    $debitP = 0;
                    $kreditP = 0;
                    $x++;
                }
            }
            $x--;

            $sheet->getStyle("C$x")->applyFromArray([
                'borders' => [
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                        'color' => ['argb' => '00000000'],
                    ],
                ],
            ]);

            $x++;

            $sheet->getStyle("A$x:D$x")->applyFromArray([
                'font' => ['bold' => true]
            ]);
            $sheet->setCellValue('B' . $x, 'Total Pendapatan');
            $sheet->setCellValue('D' . $x, $totalP);
            $sheet->getStyle('D' . $x)->applyFromArray([
                'borders' => [
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                        'color' => ['argb' => '00000000'],
                    ],
                ],
            ]);
            $x++;
            $x++;
            $sheet->setCellValue("A$x", 'Beban');
            $sheet->getStyle("A$x")->applyFromArray([
                'font' => ['bold' => true]
            ]);
            $x++;

            for ($i = 0; $i < $jumlahB; $i++) {
                $s = 0;
                $debB = $saldoB[$i];
                for ($k = 0; $k < $jumlahB; $k++) {
                    if ($j != $k) {
                        if ($debB == $saldoB[$k]) {
                            $saldoB[$k] = "";
                        }
                    }
                }
                if ($debB != "") {
                    $sheet->setCellValue('A' . $x, $dataB[$i][$s]->nama_reff);
                    for ($j = 0; $j < count($dataB[$i]); $j++) {
                        if ($debB[$j] != "") {
                            $kreditB = $kreditB + $debB[$j]->saldo;
                            $hasilB = $kreditB - $debitB;
                        }
                    }
                    $sheet->setCellValue("C$x", $hasilB);
                    $totalB += $hasilB;
                    $debitB = 0;
                    $kreditB = 0;
                    $x++;
                }
            }

            $x--;

            $sheet->getStyle("C$x")->applyFromArray([
                'borders' => [
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                        'color' => ['argb' => '00000000'],
                    ],
                ],
            ]);

            $x++;

            $sheet->getStyle("A$x:D$x")->applyFromArray([
                'font' => ['bold' => true]
            ]);
            $sheet->setCellValue('B' . $x, 'Total Beban');
            $sheet->setCellValue('D' . $x, $totalB);
            $sheet->getStyle('D' . $x)->applyFromArray([
                'borders' => [
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                        'color' => ['argb' => '00000000'],
                    ],
                ],
            ]);
            $x++;
            $sheet->getStyle("A$x:D$x")->applyFromArray([
                'font' => ['bold' => true]
            ]);
            $sheet->setCellValue('B' . $x, ($totalP - $totalB < 0) ? 'Beban' : 'Laba Bersih');
            $sheet->setCellValue('D' . $x, $totalP - $totalB);
            $sheet->getStyle("A5:D$x")->applyFromArray([
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                        'color' => ['argb' => '00000000'],
                    ],
                ],
            ]);

            $writer = new Xlsx($spreadsheet);
            $filename = 'Laporan Laba / Rugi ' . bulan($bulan) . ' ' . $tahun;

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
        }
    }

    public function laporanKeuanganPerubahanModal()
    {
        if ($this->session->userdata('role') != 'direktur') {
            show_404();
        } else {
            $titleTag = 'Laporan Keuangan';
            $content = 'user/laporan_keuangan_perubahan_modal_main';
            $listJurnal = $this->jurnal->getJurnalByYearAndMonth();
            $tahun = $this->jurnal->getJurnalByYear();
            $this->load->view('template', compact('content', 'listJurnal', 'titleTag', 'tahun'));
        }
    }

    public function laporanKeuanganPerubahanModalDetail()
    {
        if ($this->session->userdata('role') != 'direktur') {
            show_404();
        } else {
            $content = 'user/laporan_keuangan_perubahan_modal';
            $titleTag = 'Laporan Keuangan';

            $bulan = $this->input->post('bulan', true);
            $tahun = $this->input->post('tahun', true);

            if (empty($bulan) || empty($tahun)) {
                redirect('laporan_keuangan/perubahanModal');
            }

            $dataAkunM = $this->akun->getAkunByMonthYearM($bulan, $tahun);
            $dataAkunLR = $this->akun->getAkunByMonthYearLR($bulan, $tahun);
            $dataAkunPr = $this->akun->getAkunByMonthYearPr($bulan, $tahun);
            $hasilM = null;
            $hasilLR = null;
            $hasilPr = null;
            $dataM = null;
            $dataLR = null;
            $dataPr = null;
            $saldoM = null;
            $saldoLR = null;
            $saldoPr = null;
            $totalM = null;
            $totalLR = null;
            $totalPr = null;
            $s = null;

            foreach ($dataAkunM as $row) {
                $dataM[] = (array) $this->jurnal->getJurnalByNoReffMonthYearM($row->no_reff, $bulan, $tahun);
                $saldoM[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearM($row->no_reff, $bulan, $tahun);
            }

            foreach ($dataAkunLR as $row) {
                $dataLR[] = (array) $this->jurnal->getJurnalByNoReffMonthYearLR($row->no_reff, $bulan, $tahun);
                $saldoLR[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearLR($row->no_reff, $bulan, $tahun);
            }

            foreach ($dataAkunPr as $row) {
                $dataPr[] = (array) $this->jurnal->getJurnalByNoReffMonthYearPr($row->no_reff, $bulan, $tahun);
                $saldoPr[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearPr($row->no_reff, $bulan, $tahun);
            }

            if ($dataM == null || $saldoM == null || $dataLR == null || $saldoLR == null || $saldoPr == null || $dataPr == null) {
                $this->session->set_flashdata('dataNull', 'Laporan Perubahan Modal Dengan Bulan ' . bulan($bulan) . ' Pada Tahun ' . $tahun . ' Tidak Di Temukan');
                redirect('laporan_keuangan/perubahanModal');
            }

            $jumlahM = count($dataM);
            $jumlahLR = count($dataLR);
            $jumlahPr = count($dataPr);

            $this->load->view('template', compact('content', 'titleTag', 'dataAkunM', 'dataAkunLR', 'dataAkunPr', 'dataM', 'dataLR', 'dataPr', 'jumlahM', 'jumlahLR', 'jumlahPr', 'saldoM', 'saldoLR', 'saldoPr', 'hasilM', 'hasilLR', 'hasilPr', 'totalM', 'totalLR', 'totalPr', 's', 'bulan', 'tahun'));
        }
    }


    public function excelLaporanPerubahanModal()
    {
        $bulan = $this->input->post('bulan', true);
        $tahun = $this->input->post('tahun', true);

        if (empty($bulan) || empty($tahun)) {
            redirect('laporan_keuangan/perubahanModal');
        }

        $dataAkunM = $this->akun->getAkunByMonthYearM($bulan, $tahun);
        $dataAkunLR = $this->akun->getAkunByMonthYearLR($bulan, $tahun);
        $dataAkunPr = $this->akun->getAkunByMonthYearPr($bulan, $tahun);
        $dataM = null;
        $dataLR = null;
        $dataPr = null;
        $saldoM = null;
        $saldoLR = null;
        $saldoPr = null;


        foreach ($dataAkunM as $row) {
            $dataM[] = (array) $this->jurnal->getJurnalByNoReffMonthYearM($row->no_reff, $bulan, $tahun);
            $saldoM[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearM($row->no_reff, $bulan, $tahun);
        }

        foreach ($dataAkunLR as $row) {
            $dataLR[] = (array) $this->jurnal->getJurnalByNoReffMonthYearLR($row->no_reff, $bulan, $tahun);
            $saldoLR[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearLR($row->no_reff, $bulan, $tahun);
        }

        foreach ($dataAkunPr as $row) {
            $dataPr[] = (array) $this->jurnal->getJurnalByNoReffMonthYearPr($row->no_reff, $bulan, $tahun);
            $saldoPr[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearPr($row->no_reff, $bulan, $tahun);
        }

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman')->setSize(12);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);

        $sheet->setCellValue('A1', 'PT. Mitra Sejati Konsultan');
        $sheet->setCellValue('A2', 'Laporan Perubahan Modal');
        $sheet->setCellValue('A3', 'Per ' . bulan($bulan) . " Tahun $tahun");
        $sheet->mergeCells('A1:C1');
        $sheet->mergeCells('A2:C2');
        $sheet->mergeCells('A3:C3');
        $sheet->getStyle('A1:C3')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1:C3')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14
            ]
        ]);

        $debit = 0;
        $kredit = 0;
        $hasilM = null;
        $hasilLR = null;
        $jumlahM = count($dataM);
        $jumlahLR = count($dataLR);
        $jumlahPr = count($dataPr);
        $x = 4;
        $j = 0;

        $totalDebit = 0;
        $totalKredit = 0;
        $a = 0;

        for ($i = 0; $i < $jumlahLR; $i++) {
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
            if ($debLR != "") {
                for ($j = 0; $j < count($dataLR[$i]); $j++) {
                    if ($debLR[$j] != "") {
                        if ($debLR[$j]->jenis_saldo == "debit") {
                            $debit = $debit + $debLR[$j]->saldo;
                        } else {
                            $kredit = $kredit + $debLR[$j]->saldo;
                        }
                        $hasilLR = $debit - $kredit;
                    }
                }
                if ($hasilLR >= 0) {
                    $totalDebit += $hasilLR;
                } else {
                    $totalKredit += $hasilLR;
                }
            }
            $debit = 0;
            $kredit = 0;
        }

        for ($i = 0; $i < $jumlahM; $i++) {
            $s = 0;
            $debM = $saldoM[$i];
            for ($k = 0; $k < $jumlahM; $k++) {
                if ($j != $k) {
                    if ($debM == $saldoM[$k]) {
                        $saldoP[$k] = "";
                    }
                }
            }
            if ($debM != "") {
                $sheet->setCellValue('A' . $x, $dataM[$i][$s]->nama_reff);
                for ($j = 0; $j < count($dataM[$i]); $j++) {
                    if ($debM[$j] != "") {
                        $kredit = $kredit + $debM[$j]->saldo;
                        $hasilM = $debit - $kredit;
                    }
                    $sheet->setCellValue("C$x", abs($hasilM));
                }
            }
            $debit = 0;
            $kredit = 0;
        }
        $x++;
        $sheet->setCellValue("A$x", 'Ditambah :');
        $x++;
        $nilaiTotal = $totalDebit - abs($totalKredit);
        $sheet->setCellValue("A$x", 'Laba Bersih bulan ' . bulan($bulan));
        $sheet->setCellValue("B$x", abs($nilaiTotal));
        $x++;
        $sheet->setCellValue("A$x", 'Dikurangi :');
        $x++;
        for ($i = 0; $i < $jumlahPr; $i++) {
            $s = 0;
            $debPr = $saldoPr[$i];
            for ($k = 0; $k < $jumlahM; $k++) {
                if ($j != $k) {
                    if ($debPr == $saldoPr[$k]) {
                        $saldoP[$k] = "";
                    }
                }
            }
            if ($debPr != "") {
                $sheet->setCellValue("A$x", $dataPr[$i][$s]->nama_reff);
                for ($j = 0; $j < count($dataPr[$i]); $j++) {
                    if ($debPr[$j] != "") {
                        $kredit = $kredit + $debPr[$j]->saldo;
                        $hasilPr = $debit - $kredit;
                    }
                    $sheet->setCellValue("B$x", $hasilPr);
                }
            }
            $debit = 0;
            $kredit = 0;
        }
        $x++;
        $nilaiLPR = abs($nilaiTotal - $hasilPr);
        $sheet->setCellValue("C$x", $nilaiLPR);
        $x++;
        $sheet->setCellValue("A$x", "Modal Akhir bulan" . bulan($bulan) . " " . $tahun);
        $sheet->setCellValue("C$x", abs($hasilM - $nilaiLPR));



        $writer = new Xlsx($spreadsheet);
        $filename = 'Laporan Perubahan Modal ' . bulan($bulan) . ' ' . $tahun;

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function laporanKeuanganNeraca()
    {
        if ($this->session->userdata('role') != 'direktur') {
            show_404();
        } else {
            $titleTag = 'Laporan Keuangan';
            $content = 'user/laporan_keuangan_neraca_main';
            $listJurnal = $this->jurnal->getJurnalByYearAndMonth();
            $tahun = $this->jurnal->getJurnalByYear();
            $this->load->view('template', compact('content', 'listJurnal', 'titleTag', 'tahun'));
        }
    }

    public function laporanKeuanganNeracaDetail()
    {
        $content = 'user/laporan_keuangan_neraca';
        $titleTag = 'Laporan Keuangan';

        $bulan = $this->input->post('bulan', true);
        $tahun = $this->input->post('tahun', true);

        if (empty($bulan) || empty($tahun)) {
            redirect('laporan_keuangan/neraca');
        }

        // Ns adalah data yang diambil dari neraca saldo

        $dataAkun = $this->akun->getAkunByMonthYearAfterMerge($bulan, $tahun);
        $data = null;
        $saldo = null;


        foreach ($dataAkun as $row) {
            $data[] = (array) $this->jurnal->getJurnalByNoReffMonthYearAfterMerge($row->no_reff, $bulan, $tahun);
            $saldo[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearAfterMerge($row->no_reff, $bulan, $tahun);
        }
        $jumlah = count($data);

        if ($data == null || $saldo == null) {
            $this->session->set_flashdata('dataNull', 'Laporan Neraca Dengan Bulan ' . bulan($bulan) . ' Pada Tahun ' . date('Y', strtotime($tahun)) . ' Tidak Di Temukan');
            redirect('laporan_keuangan/neraca');
        }


        $this->load->view('template', compact('content', 'titleTag', 'dataAkun', 'data', 'jumlah', 'saldo', 'bulan', 'tahun'));
    }

    public function excelLaporanNeraca()
    {
        $bulan = $this->input->post('bulan', true);
        $tahun = $this->input->post('tahun', true);

        if (empty($bulan) || empty($tahun)) {
            redirect('laporan_keuangan/neraca');
        }

        $dataAkun = $this->akun->getAkunByMonthYearAfterMerge($bulan, $tahun);
        $data = null;
        $saldo = null;

        foreach ($dataAkun as $row) {
            $data[] = (array) $this->jurnal->getJurnalByNoReffMonthYearAfterMerge($row->no_reff, $bulan, $tahun);
            $saldo[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearAfterMerge($row->no_reff, $bulan, $tahun);
        }
        $jumlah = count($data);

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman')->setSize(12);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);

        $sheet->setCellValue('A1', 'PT. Mitra Sejati Konsultan');
        $sheet->setCellValue('A2', 'Laporan Neraca');
        $sheet->setCellValue('A3', 'Per ' . bulan($bulan) . " Tahun $tahun");
        $sheet->mergeCells('A1:D1');
        $sheet->mergeCells('A2:D2');
        $sheet->mergeCells('A3:D3');
        $sheet->getStyle('A1:D5')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1:D3')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14
            ]
        ]);

        $sheet->setCellValue('A5', 'NOMOR AKUN');
        $sheet->setCellValue('B5', 'NAMA AKUN');
        $sheet->setCellValue('C5', 'DEBIT');
        $sheet->setCellValue('D5', 'KREDIT');
        $sheet->getStyle('A5:D5')->applyFromArray([
            'font' => ['bold' => true]
        ]);

        $x = 6;
        $a = 0;
        $j = 0;
        $debit = 0;
        $kredit = 0;
        $total_debit = 0;
        $total_kredit = 0;
        $debNs = [];

        for ($i = 0; $i < $jumlah; $i++) {
            $a++;
            $s = 0;
            $debNs = $saldo[$i];
            for ($k = 0; $k < $jumlah; $k++) {
                if ($j != $k) {
                    if ($debNs == $saldo[$k]) {
                        $saldo[$k] = "";
                    }
                }
            }

            if ($debNs != "") {
                $sheet->setCellValue('A' . $x, $data[$i][$s]->no_reff);
                $sheet->setCellValue('B' . $x, $data[$i][$s]->nama_reff);

                for ($j = 0; $j < count($data[$i]); $j++) {
                    if ($debNs[$j] != "") {
                        if ($debNs[$j]->jenis_saldo == "debit") {
                            $debit = $debit + $debNs[$j]->saldo;
                        } else {
                            $kredit = $kredit + $debNs[$j]->saldo;
                        }
                        $hasil = $debit - $kredit;
                    }
                }

                if ($hasil >= 0) {
                    $sheet->setCellValue("C$x", $hasil);
                    $sheet->setCellValue('D' . $x, 0);
                    $total_debit += $hasil;
                } else {
                    $sheet->setCellValue("C$x", 0);
                    $sheet->setCellValue('D' . $x, $hasil);
                    $total_kredit += $hasil;
                }
                $debit = 0;
                $kredit = 0;
                $x++;
            }
        }

        $sheet->setCellValue("A$x", 'Total');
        $sheet->mergeCells("A$x:B$x");
        $sheet->setCellValue("C$x", $total_debit);
        $sheet->setCellValue("D$x", $total_kredit);
        $sheet->getStyle("A$x:D$x")->getAlignment()->setHorizontal('center');
        $sheet->getStyle("A$x:D$x")->applyFromArray([
            'font' => [
                'bold' => true,
            ]
        ]);

        $writer = new Xlsx($spreadsheet);
        $filename = 'Laporan Neraca ' . bulan($bulan) . ' ' . $tahun;

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function laporanKeuanganArusKas()
    {
        if ($this->session->userdata('role') != 'direktur') {
            show_404();
        } else {
            $titleTag = 'Laporan Keuangan';
            $content = 'user/laporan_keuangan_arus_kas_main';
            $listJurnal = $this->jurnal->getJurnalByYearAndMonth();
            $tahun = $this->jurnal->getJurnalByYear();
            $this->load->view('template', compact('content', 'listJurnal', 'titleTag', 'tahun'));
        }
    }

    public function laporanKeuanganArusKasDetail()
    {
        $content = 'user/laporan_keuangan_arus_kas';
        $titleTag = 'Laporan Keuangan';
        $bulan = $this->input->post('bulan', true);
        $tahun = $this->input->post('tahun', true);
        $jurnals = null;

        if (empty($bulan) || empty($tahun)) {
            redirect('laporan_keuangan/arusKas');
        }

        $jurnals = $this->jurnal->getJurnalJoinAkunDetailFilter($bulan, $tahun);
        $totalKredit = $this->jurnal->getTotalSaldoDetailFilter('kredit', $bulan, $tahun);
        $totalDebit = $this->jurnal->getTotalSaldoDetailFilter('debit', $bulan, $tahun);
        // $labaRugi = null;

        if ($jurnals == null) {
            $this->session->set_flashdata('dataNull', 'Data Laporan Keuangan Dengan Bulan ' . bulan($bulan) . ' Pada Tahun ' . $tahun . ' Tidak Di Temukan');
            redirect('laporan_keuangan/arusKas');
        }

        $this->load->view('template', compact('content', 'jurnals', 'totalDebit', 'totalKredit', 'titleTag', 'bulan', 'tahun'));
    }

    public function excelLaporanArusKas()
    {
        $bulan = $this->input->post('bulan', true);
        $tahun = $this->input->post('tahun', true);
        $jurnals = null;

        if (empty($bulan) || empty($tahun)) {
            redirect('laporan_keuangan/arusKas');
        }

        $jurnals = $this->jurnal->getJurnalJoinAkunDetailFilter($bulan, $tahun);
        $totalKredit = $this->jurnal->getTotalSaldoDetailFilter('kredit', $bulan, $tahun);
        $totalDebit = $this->jurnal->getTotalSaldoDetailFilter('debit', $bulan, $tahun);
        $jumlah = count($jurnals);

        if ($jurnals == null) {
            $this->session->set_flashdata('dataNull', 'Data Laporan Keuangan Dengan Bulan ' . bulan($bulan) . ' Pada Tahun ' . $tahun . ' Tidak Di Temukan');
            redirect('laporan_keuangan/arusKas');
        }

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman')->setSize(12);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(8);
        $sheet->getColumnDimension('D')->setWidth(12);
        $sheet->getColumnDimension('E')->setWidth(12);

        $sheet->setCellValue('A1', 'PT. Mitra Sejati Konsultan');
        $sheet->setCellValue('A2', 'Laporan Posisi Keuangan');
        $sheet->setCellValue('A3', 'Per ' . bulan($bulan) . " Tahun $tahun");
        $sheet->mergeCells('A1:E1');
        $sheet->mergeCells('A2:E2');
        $sheet->mergeCells('A3:E3');
        $sheet->getStyle('A1:E3')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1:E3')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14
            ]
        ]);

        $sheet->setCellValue('A4', 'TANGGAL');
        $sheet->setCellValue('B4', 'NAMA AKUN');
        $sheet->setCellValue('C4', 'REF');
        $sheet->setCellValue('D4', 'DEBET');
        $sheet->setCellValue('E4', 'KREDIT');
        $sheet->getStyle('A4:E4')->applyFromArray([
            'font' => ['bold' => true]
        ]);

        $x = 5;

        foreach ($jurnals as $row) {
            $sheet->setCellValue("A$x", date_indo($row->tgl_transaksi));
            $sheet->setCellValue("B$x", $row->nama_reff);
            $sheet->setCellValue("C$x", $row->no_reff);
            if ($row->jenis_saldo == 'kredit') {
                $sheet->setCellValue("D$x", 0);
                $sheet->setCellValue("E$x", $row->saldo);
            } else {
                $sheet->setCellValue("D$x", $row->saldo);
                $sheet->setCellValue("E$x", 0);
            }
            $x++;
        }

        $sheet->setCellValue("A$x", 'Jumlah Total');
        $sheet->mergeCells("A$x:C$x");
        $sheet->getStyle("A$x:C$x")->getAlignment()->setHorizontal('center');
        $sheet->getStyle("A$x:C$x")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14
            ]
        ]);
        $sheet->setCellValue("D$x", $totalDebit->saldo);
        $sheet->setCellValue("E$x", $totalKredit->saldo);

        $writer = new Xlsx($spreadsheet);
        $filename = 'Laporan Arus Kas ' . bulan($bulan) . ' ' . $tahun;

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function laporanPosisiKeuangan()
    {
        if ($this->session->userdata('role') != 'direktur') {
            show_404();
        } else {
            $titleTag = 'Laporan Keuangan';
            $content = 'user/laporan_keuangan_posisi_keuangan_main';
            $listJurnal = $this->jurnal->getJurnalByYearAndMonth();
            $tahun = $this->jurnal->getJurnalByYear();
            $this->load->view('template', compact('content', 'listJurnal', 'titleTag', 'tahun'));
        }
    }

    public function laporanPosisiKeuanganDetail()
    {
        if ($this->session->userdata('role') != 'direktur') {
            show_404();
        } else {
            $content = 'user/laporan_keuangan_posisi_keuangan';
            $titleTag = 'Laporan Keuangan';

            $bulan = $this->input->post('bulan', true);
            $tahun = $this->input->post('tahun', true);

            if (empty($bulan) || empty($tahun)) {
                redirect('laporan_keuangan/posisiKeuangan');
            }

            $dataAkun = $this->akun->getAkunByMonthYearAfterMerge($bulan, $tahun);
            $dataAkunAT = $this->akun->getAkunByMonthYearAT($bulan, $tahun);
            $dataAkunL = $this->akun->getAkunByMonthYearL($bulan, $tahun);
            $dataAkunM = $this->akun->getAkunByMonthYearM($bulan, $tahun);
            $dataAkunLR = $this->akun->getAkunByMonthYearLR($bulan, $tahun);
            $dataAkunPr = $this->akun->getAkunByMonthYearPr($bulan, $tahun);
            $hasilM = null;
            $hasilLR = null;
            $hasilPr = null;
            $dataM = null;
            $dataLR = null;
            $dataPr = null;
            $saldoM = null;
            $saldoLR = null;
            $saldoPr = null;
            $totalM = null;
            $totalLR = null;
            $totalPr = null;
            $hasil = null;
            $hasilAT = null;
            $hasilL = null;
            $data = null;
            $dataAT = null;
            $dataL = null;
            $saldo = null;
            $saldoAT = null;
            $saldoL = null;
            $total = null;
            $totalAT = null;
            $totalL = null;
            $s = null;

            foreach ($dataAkunM as $row) {
                $dataM[] = (array) $this->jurnal->getJurnalByNoReffMonthYearM($row->no_reff, $bulan, $tahun);
                $saldoM[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearM($row->no_reff, $bulan, $tahun);
            }

            foreach ($dataAkunLR as $row) {
                $dataLR[] = (array) $this->jurnal->getJurnalByNoReffMonthYearLR($row->no_reff, $bulan, $tahun);
                $saldoLR[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearLR($row->no_reff, $bulan, $tahun);
            }

            foreach ($dataAkunPr as $row) {
                $dataPr[] = (array) $this->jurnal->getJurnalByNoReffMonthYearPr($row->no_reff, $bulan, $tahun);
                $saldoPr[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearPr($row->no_reff, $bulan, $tahun);
            }

            foreach ($dataAkun as $row) {
                $data[] = (array) $this->jurnal->getJurnalByNoReffMonthYearAfterMerge($row->no_reff, $bulan, $tahun);
                $saldo[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearAfterMerge($row->no_reff, $bulan, $tahun);
            }
            foreach ($dataAkunAT as $row) {
                $dataAT[] = (array) $this->jurnal->getJurnalByNoReffMonthYearAT($row->no_reff, $bulan, $tahun);
                $saldoAT[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearAT($row->no_reff, $bulan, $tahun);
            }
            foreach ($dataAkunL as $row) {
                $dataL[] = (array) $this->jurnal->getJurnalByNoReffMonthYearL($row->no_reff, $bulan, $tahun);
                $saldoL[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearL($row->no_reff, $bulan, $tahun);
            }

            if ($data == null || $saldo == null) {
                $this->session->set_flashdata('dataNull', 'Laporan Perubahan Modal Dengan Bulan ' . bulan($bulan) . ' Pada Tahun ' . $tahun . ' Tidak Di Temukan');
                redirect('laporan_keuangan/posisiKeuangan');
            }

            $jumlah = count($data);
            $jumlahAT = count($dataAT);
            $jumlahL = count($dataL);
            $jumlahM = count($dataM);
            $jumlahLR = count($dataLR);
            $jumlahPr = count($dataPr);

            $this->load->view('template', compact('content', 'titleTag', 'data', 'dataAT', 'dataL', 'dataM', 'dataLR', 'dataPr', 'jumlah', 'jumlahAT', 'jumlahL', 'jumlahM', 'jumlahLR', 'jumlahPr', 'saldo', 'saldoAT', 'saldoL', 'saldoM', 'saldoLR', 'saldoPr', 'hasil', 'hasilAT', 'hasilL', 'hasilM', 'hasilLR', 'hasilPr', 'total', 'totalAT', 'totalL', 'totalM', 'totalLR', 'totalPr', 's', 'bulan', 'tahun'));
        }
    }

    public function excelLaporanPosisiKeuangan()
    {
        $bulan = $this->input->post('bulan', true);
        $tahun = $this->input->post('tahun', true);

        if (empty($bulan) || empty($tahun)) {
            redirect('laporan_keuangan/neraca');
        }

        $dataAkunAL = $this->akun->getAkunByMonthYearAL($bulan, $tahun);
        $dataAkunAT = $this->akun->getAkunByMonthYearAT($bulan, $tahun);
        $dataAkunL = $this->akun->getAkunByMonthYearL($bulan, $tahun);
        $dataAkunM = $this->akun->getAkunByMonthYearM($bulan, $tahun);
        $dataAkunLR = $this->akun->getAkunByMonthYearLR($bulan, $tahun);
        $dataAkunPr = $this->akun->getAkunByMonthYearPr($bulan, $tahun);

        foreach ($dataAkunM as $row) {
            $dataM[] = (array) $this->jurnal->getJurnalByNoReffMonthYearM($row->no_reff, $bulan, $tahun);
            $saldoM[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearM($row->no_reff, $bulan, $tahun);
        }

        foreach ($dataAkunLR as $row) {
            $dataLR[] = (array) $this->jurnal->getJurnalByNoReffMonthYearLR($row->no_reff, $bulan, $tahun);
            $saldoLR[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearLR($row->no_reff, $bulan, $tahun);
        }

        foreach ($dataAkunPr as $row) {
            $dataPr[] = (array) $this->jurnal->getJurnalByNoReffMonthYearPr($row->no_reff, $bulan, $tahun);
            $saldoPr[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearPr($row->no_reff, $bulan, $tahun);
        }

        foreach ($dataAkunAL as $row) {
            $dataAL[] = (array) $this->jurnal->getJurnalByNoReffMonthYearAL($row->no_reff, $bulan, $tahun);
            $saldoAL[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearAL($row->no_reff, $bulan, $tahun);
        }
        foreach ($dataAkunAT as $row) {
            $dataAT[] = (array) $this->jurnal->getJurnalByNoReffMonthYearAT($row->no_reff, $bulan, $tahun);
            $saldoAT[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearAT($row->no_reff, $bulan, $tahun);
        }
        foreach ($dataAkunL as $row) {
            $dataL[] = (array) $this->jurnal->getJurnalByNoReffMonthYearL($row->no_reff, $bulan, $tahun);
            $saldoL[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYearL($row->no_reff, $bulan, $tahun);
        }


        $jumlahAL = count($dataAL);
        $jumlahAT = count($dataAT);
        $jumlahL = count($dataL);
        $jumlahM = count($dataM);
        $jumlahLR = count($dataLR);
        $jumlahPr = count($dataPr);

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman')->setSize(12);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(5);
        $sheet->getColumnDimension('E')->setWidth(30);
        $sheet->getColumnDimension('F')->setWidth(30);

        $sheet->setCellValue('A1', 'PT. Mitra Sejati Konsultan');
        $sheet->setCellValue('A2', 'Laporan Posisi Keuangan');
        $sheet->setCellValue('A3', 'Per ' . bulan($bulan) . " Tahun $tahun");
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:F2');
        $sheet->mergeCells('A3:F3');
        $sheet->getStyle('A1:F3')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1:D3')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14
            ]
        ]);

        $sheet->mergeCells('A1:B1');
        $sheet->mergeCells('D1:E1');
        $sheet->setCellValue('A4', 'ASSET');
        $sheet->setCellValue('A5', 'Asset Lancar');
        $sheet->setCellValue('D4', 'LIABILITAS');
        $sheet->setCellValue('D5', 'Liabilitas');
        $sheet->getStyle('A4:F4')->applyFromArray([
            'font' => ['bold' => true]
        ]);

        $x = 6;
        $a = 0;
        $j = 0;
        $debit = 0;
        $kredit = 0;
        $total_debit = 0;
        $total_kredit = 0;
        $debAL = [];
        $debLR = [];

        for ($i = 0; $i < $jumlahLR; $i++) {
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
            if ($debLR != "") {
                for ($j = 0; $j < count($dataLR[$i]); $j++) {
                    if ($debLR[$j] != "") {
                        if ($debLR[$j]->jenis_saldo == "debit") {
                            $debit = $debit + $debLR[$j]->saldo;
                        } else {
                            $kredit = $kredit + $debLR[$j]->saldo;
                        }
                        $hasil = $debit - $kredit;
                    }
                }
                if ($hasil >= 0) {
                    $total_debit += $hasil;
                } else {
                    $total_kredit += $hasil;
                }
                $debit = 0;
                $kredit = 0;
            }
        }
        $totalLR = $total_debit + $total_kredit;

        $debit = 0;
        $kredit = 0;
        $total_debit = 0;
        $total_kredit = 0;

        for ($i = 0; $i < $jumlahAL; $i++) {
            $a++;
            $s = 0;
            $debAL = $saldoAL[$i];
            for ($k = 0; $k < $jumlahAL; $k++) {
                if ($j != $k) {
                    if ($debAL == $saldoAL[$k]) {
                        $saldoAL[$k] = "";
                    }
                }
            }
            if ($debAL != "") {
                $sheet->setCellValue("B$x", $dataAL[$i][$s]->nama_reff);
                for ($j = 0; $j < count($dataAL[$i]); $j++) {
                    if ($debAL[$j] != "") {
                        if ($debAL[$j]->jenis_saldo == "debit") {
                            $debit = $debit + $debAL[$j]->saldo;
                        } else {
                            $kredit = $kredit + $debAL[$j]->saldo;
                        }
                        $hasil = $debit - $kredit;
                    }
                }
                if ($hasil >= 0) {
                    $sheet->setCellValue("C$x", $hasil);
                    $total_debit += $hasil;
                } else {
                    $sheet->setCellValue("C$x", $hasil);
                    $total_kredit += $hasil;
                }
                $debit = 0;
                $kredit = 0;
                $x++;
            }
        }

        $totalAL = $total_debit + $total_kredit;
        $sheet->setCellValue("A$x", 'Total Asset Lancar');
        $sheet->mergeCells("A$x:B$x");
        $sheet->setCellValue("C$x", $totalAL);
        $sheet->getStyle("A$x:C$x")->applyFromArray([
            'font' => [
                'bold' => true,
            ]
        ]);
        $x++;
        $sheet->setCellValue("A$x", 'Asset Tetap');
        $x++;

        $total_debit = 0;
        $total_kredit = 0;
        $debAT = [];

        for ($i = 0; $i < $jumlahAT; $i++) {
            $a++;
            $s = 0;
            $debAT = $saldoAT[$i];
            for ($k = 0; $k < $jumlahAT; $k++) {
                if ($j != $k) {
                    if ($debAT == $saldoAT[$k]) {
                        $saldoAT[$k] = "";
                    }
                }
            }
            if ($debAT != "") {
                $sheet->setCellValue("B$x", $dataAT[$i][$s]->nama_reff);
                for ($j = 0; $j < count($dataAT[$i]); $j++) {
                    if ($debAT[$j] != "") {
                        if ($debAT[$j]->jenis_saldo == "debit") {
                            $debit = $debit + $debAT[$j]->saldo;
                        } else {
                            $kredit = $kredit + $debAT[$j]->saldo;
                        }
                        $hasil = $debit - $kredit;
                    }
                }
                if ($hasil >= 0) {
                    $sheet->setCellValue("C$x", $hasil);
                    $total_debit += $hasil;
                } else {
                    $sheet->setCellValue("C$x", $hasil);
                    $total_kredit += $hasil;
                }
                $debit = 0;
                $kredit = 0;
                $x++;
            }
        }

        $totalAT = $total_debit + $total_kredit;
        $sheet->setCellValue("A$x", 'Total Asset Tetap');
        $sheet->mergeCells("A$x:B$x");
        $sheet->setCellValue("C$x", $totalAT);
        $sheet->getStyle("A$x:C$x")->applyFromArray([
            'font' => [
                'bold' => true,
            ]
        ]);
        $x++;
        $sheet->setCellValue("A$x", 'TOTAL ASSET');
        $sheet->mergeCells("A$x:B$x");
        $sheet->setCellValue("C$x", $totalAL + $totalAT);
        $sheet->getStyle("A$x:C$x")->applyFromArray([
            'font' => [
                'bold' => true,
            ]
        ]);

        $x = 6;

        $total_debit = 0;
        $total_kredit = 0;
        $debL = [];

        for ($i = 0; $i < $jumlahL; $i++) {
            $a++;
            $s = 0;
            $debL = $saldoL[$i];
            for ($k = 0; $k < $jumlahL; $k++) {
                if ($j != $k) {
                    if ($debL == $saldoL[$k]) {
                        $saldoL[$k] = "";
                    }
                }
            }
            if ($debL != "") {
                $sheet->setCellValue("E$x", $dataL[$i][$s]->nama_reff);
                for ($j = 0; $j < count($dataL[$i]); $j++) {
                    if ($debL[$j] != "") {
                        if ($debL[$j]->jenis_saldo == "debit") {
                            $debit = $debit + $debL[$j]->saldo;
                        } else {
                            $kredit = $kredit + $debL[$j]->saldo;
                        }
                        $hasil = $debit - $kredit;
                    }
                }
                if ($hasil >= 0) {
                    $sheet->setCellValue("F$x", $hasil);
                    $total_debit += $hasil;
                } else {
                    $sheet->setCellValue("F$x", abs($hasil));
                    $total_kredit += $hasil;
                }
                $debit = 0;
                $kredit = 0;
                $x++;
            }
        }

        $totalL = $total_debit + $total_kredit;
        $sheet->setCellValue("D$x", 'Total Liabilitas');
        $sheet->mergeCells("D$x:E$x");
        $sheet->setCellValue("F$x", abs($totalL));
        $sheet->getStyle("D$x:E$x")->applyFromArray([
            'font' => [
                'bold' => true,
            ]
        ]);
        $x++;
        $sheet->setCellValue("D$x", 'Equitas');
        $x++;

        $total_debit = 0;
        $total_kredit = 0;
        $debM = [];
        $debPr = [];

        for ($i = 0; $i < $jumlahM; $i++) {
            $a++;
            $s = 0;
            $debM = $saldoM[$i];
            for ($k = 0; $k < $jumlahM; $k++) {
                if ($j != $k) {
                    if ($debM == $saldoM[$k]) {
                        $saldoM[$k] = "";
                    }
                }
            }
            if ($debM != "") {
                for ($j = 0; $j < count($dataM[$i]); $j++) {
                    if ($debM[$j] != "") {
                        if ($debM[$j]->jenis_saldo == "debit") {
                            $debit = $debit + $debM[$j]->saldo;
                        } else {
                            $kredit = $kredit + $debM[$j]->saldo;
                        }
                        $hasil = $debit - $kredit;
                    }
                }
                if ($hasil >= 0) {
                    $total_debit += $hasil;
                } else {
                    $total_kredit += $hasil;
                }
                $debit = 0;
                $kredit = 0;
            }
        }
        $totalM = $total_debit + $total_kredit;


        $debit = 0;
        $kredit = 0;
        $total_debit = 0;
        $total_kredit = 0;

        for ($i = 0; $i < $jumlahPr; $i++) {
            $a++;
            $s = 0;
            $debPr = $saldoPr[$i];
            for ($k = 0; $k < $jumlahPr; $k++) {
                if ($j != $k) {
                    if ($debPr == $saldoPr[$k]) {
                        $saldoPr[$k] = "";
                    }
                }
            }
            if ($debPr != "") {
                for ($j = 0; $j < count($dataPr[$i]); $j++) {
                    if ($debPr[$j] != "") {
                        if ($debPr[$j]->jenis_saldo == "debit") {
                            $debit = $debit + $debPr[$j]->saldo;
                        } else {
                            $kredit = $kredit + $debPr[$j]->saldo;
                        }
                        $hasil = $debit - $kredit;
                    }
                }
                if ($hasil >= 0) {
                    $total_debit += $hasil;
                } else {
                    $total_kredit += $hasil;
                }
                $debit = 0;
                $kredit = 0;
            }
        }
        $totalPr = $total_debit + $total_kredit;

        $totalEquitas = $totalM + $totalLR + $totalPr;

        $sheet->setCellValue("E$x", 'Modal');
        $sheet->setCellValue("F$x", abs($totalEquitas));
        $x++;
        $sheet->setCellValue("D$x", 'Total Equitas');
        $sheet->setCellValue("F$x", abs($totalEquitas));
        $sheet->getStyle("D$x:F$x")->applyFromArray([
            'font' => [
                'bold' => true,
            ]
        ]);

        $x++;

        $sheet->setCellValue("D$x", 'TOTAL LIABILITAS + EQUITAS');
        $sheet->mergeCells("D$x:E$x");
        $sheet->setCellValue("F$x", abs($totalL + $totalEquitas));
        $sheet->getStyle("D$x:F$x")->applyFromArray([
            'font' => [
                'bold' => true,
            ]
        ]);

        $writer = new Xlsx($spreadsheet);
        $filename = 'Laporan Posisi Keuangan ' . bulan($bulan) . ' ' . $tahun;

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    // public function laporan()
    // {
    //     $titleTag = 'Laporan';
    //     $content = 'user/laporan_main';
    //     $listJurnal = $this->jurnal->getJurnalByYearAndMonth();
    //     $tahun = $this->jurnal->getJurnalByYear();
    //     $this->load->view('template', compact('content', 'listJurnal', 'titleTag', 'tahun'));
    // }

    // public function laporanCetak()
    // {
    //     $bulan = $this->input->post('bulan', true);
    //     $tahun = $this->input->post('tahun', true);
    //     $titleTag = 'Laporan ' . bulan($bulan) . ' ' . $tahun;

    //     $dataAkun = $this->akun->getAkunByMonthYear($bulan, $tahun);
    //     var_dump($dataAkun);
    //     die;

    //     $jurnals = $this->jurnal->getJurnalJoinAkunDetail($bulan, $tahun);
    //     $totalDebit = $this->jurnal->getTotalSaldoDetail('debit', $bulan, $tahun);
    //     $totalKredit = $this->jurnal->getTotalSaldoDetail('kredit', $bulan, $tahun);

    //     $data = null;
    //     $saldo = null;
    //     foreach ($dataAkun as $row) {
    //         $data[] = (array) $this->jurnal->getJurnalByNoReffMonthYear($row->no_reff, $bulan, $tahun);
    //         $saldo[] = (array) $this->jurnal->getJurnalByNoReffSaldoMonthYear($row->no_reff, $bulan, $tahun);
    //     }

    //     if ($data == null || $saldo == null) {
    //         $this->session->set_flashdata('dataNull', 'Laporan Dengan Bulan ' . bulan($bulan) . ' Pada Tahun ' . $tahun . ' Tidak Di Temukan');
    //         redirect('laporan');
    //     }

    //     $jumlah = count($data);

    //     // $this->load->library('pdf');
    //     // $this->pdf->setPaper('A4', 'landscape');
    //     // $this->pdf->filename = "laporan_".bulan($bulan).'_'.$tahun;
    //     // $this->pdf->load_view('user/laporan', $data);
    // }

    public function logout()
    {
        $this->user->logout();
        redirect('');
    }
}
