<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

// LOGIN
$route['default_controller'] = 'login/login';
$route['login'] = 'login/login';
$route['register'] = 'login/register';
$route['regis'] = 'login/regis';
$route['logout'] = 'user/logout';

// DASHBOARD
$route['dashboard'] = 'user/index';

// DATA AKUN
$route['data_akun'] = 'user/dataAkun';
$route['data_akun/tambah'] = 'user/createAkun';
$route['data_akun/edit/(:num)'] = 'user/editAkun/$1';
$route['data_akun/hapus'] = 'user/deleteAkun';

// JURNAL UMUM
$route['jurnal_umum'] = 'user/jurnalUmum';
$route['jurnal_umum/detail'] = 'user/jurnalUmumDetail';
$route['jurnal_umum/tambah'] = 'user/createJurnal';
$route['jurnal_umum/edit'] = 'user/editJurnal';
$route['jurnal_umum/edit_form'] = 'user/editForm';
$route['jurnal_umum/hapus'] = 'user/deleteJurnal';

// BUKU BESAR
$route['buku_besar'] = 'user/bukuBesar';
$route['buku_besar/detail'] = 'user/bukuBesarDetail';

// NERACA SALDO
$route['neraca_saldo'] = 'user/neracaSaldo';
$route['neraca_saldo/detail'] = 'user/neracaSaldoDetail';

// JURNAL PENYESUAIAN
$route['jurnal_penyesuaian'] = 'user/jurnalPenyesuaian';
$route['jurnal_penyesuaian/detail'] = 'user/jurnalPenyesuaianDetail';
$route['jurnal_penyesuaian/tambah'] = 'user/createJurnalPenyesuaian';
$route['jurnal_penyesuaian/edit'] = 'user/editJurnalPenyesuaian';
$route['jurnal_penyesuaian/edit_form'] = 'user/editFormJPenyesuaian';
$route['jurnal_penyesuaian/hapus'] = 'user/deleteJurnalPenyesuaian';

// NERACA LAJUR
$route['neraca_lajur'] = 'user/neracaLajur';
$route['neraca_lajur/detail'] = 'user/neracaLajurDetail';


//LAPORAN KEUANGAN
$route['laporan_keuangan'] = 'user/laporanKeuangan';
$route['laporan_keuangan/labaRugi'] = 'user/laporanKeuanganLabaRugi';
$route['laporan_keuangan/labaRugi/detail'] = 'user/laporanKeuanganLabaRugiDetail';
$route['laporan_keuangan/perubahanModal'] = 'user/laporanKeuanganPerubahanModal';
$route['laporan_keuangan/perubahanModal/detail'] = 'user/laporanKeuanganPerubahanModalDetail';
$route['laporan_keuangan/neraca'] = 'user/laporanKeuanganNeraca';
$route['laporan_keuangan/neraca/detail'] = 'user/laporanKeuanganNeracaDetail';
$route['laporan_keuangan/arusKas'] = 'user/laporanKeuanganArusKas';
$route['laporan_keuangan/arusKas/detail'] = 'user/laporanKeuanganArusKasDetail';
$route['laporan_keuangan/posisiKeuangan'] = 'user/laporanPosisiKeuangan';
$route['laporan_keuangan/posisiKeuangan/detail'] = 'user/laporanPosisiKeuanganDetail';

// LAPORAN
// $route['laporan'] = 'user/laporan';
// $route['laporan/cetak'] = 'user/laporanCetak';
$route['laporan/excel/labaRugi'] = 'user/excelLaporanLabaRugi';
$route['laporan/excel/perubahanModal'] = 'user/excelLaporanPerubahanModal';
$route['laporan/excel/neraca'] = 'user/excelLaporanNeraca';
$route['laporan/excel/arusKas'] = 'user/excelLaporanArusKas';
$route['laporan/excel/posisiKeuangan'] = 'user/excelLaporanPosisiKeuangan';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
