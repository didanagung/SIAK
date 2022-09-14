<?php
defined('BASEPATH') or exit('No direct script access allowed');

class JurnalPenyesuaian_model extends CI_Model
{
    private $table = 'penyesuaian';

    public function getJurnal()
    {
        return $this->db->get($this->table)->result();
    }

    public function getJurnalById($id)
    {
        return $this->db->where('id_transaksi', $id)->get($this->table)->row();
    }

    public function countJurnalNoReff($noReff)
    {
        return $this->db->where('no_reff', $noReff)->get($this->table)->num_rows();
    }

    public function getJurnalByYear()
    {
        return $this->db->select('tgl_transaksi')
            ->from($this->table)
            ->group_by('year(tgl_transaksi)')
            ->get()
            ->result();
    }

    public function getJurnalByYearAndMonth()
    {
        return $this->db->select('tgl_transaksi')
            ->from($this->table)
            ->group_by('month(tgl_transaksi)')
            ->group_by('year(tgl_transaksi)')
            ->get()
            ->result();
    }

    public function getAkunInJurnal()
    {
        return $this->db->select('penyesuaian.no_reff,akun.no_reff,akun.nama_reff')
            ->from($this->table)
            ->join('akun', 'penyesuaian.no_reff = akun.no_reff')
            ->order_by('akun.no_reff', 'ASC')
            ->group_by('akun.nama_reff')
            ->get()
            ->result();
    }

    public function countAkunInJurnal()
    {
        return $this->db->select('penyesuaian.no_reff,akun.no_reff,akun.nama_reff')
            ->from($this->table)
            ->join('akun', 'penyesuaian.no_reff = akun.no_reff')
            ->order_by('akun.no_reff', 'ASC')
            ->group_by('akun.nama_reff')
            ->get()
            ->num_rows();
    }

    public function countAkunInJurnalU()
    {
        return $this->db->select('penyesuaian.no_reff,akun.no_reff,akun.nama_reff')
            ->from($this->table1)
            ->join('akun', 'penyesuaian.no_reff = akun.no_reff')
            ->order_by('akun.no_reff', 'ASC')
            ->group_by('akun.nama_reff')
            ->get()
            ->num_rows();
    }

    public function getJurnalByNoReff($noReff)
    {
        return $this->db->select('penyesuaian.id_transaksi,penyesuaian.tgl_transaksi,akun.nama_reff,penyesuaian.no_reff,penyesuaian.jenis_saldo,penyesuaian.saldo,penyesuaian.tgl_input')
            ->from($this->table)
            ->where('penyesuaian.no_reff', $noReff)
            ->join('akun', 'penyesuaian.no_reff = akun.no_reff')
            ->order_by('tgl_transaksi', 'ASC')
            ->get()
            ->result();
    }

    public function getJurnalByNoReffMonthYear($noReff, $bulan, $tahun)
    {
        return $this->db->select('penyesuaian.id_transaksi,penyesuaian.tgl_transaksi,akun.nama_reff,penyesuaian.no_reff,penyesuaian.jenis_saldo,penyesuaian.saldo,penyesuaian.tgl_input')
            ->from($this->table)
            ->where('penyesuaian.no_reff', $noReff)
            ->where('month(penyesuaian.tgl_transaksi)', $bulan)
            ->where('year(penyesuaian.tgl_transaksi)', $tahun)
            ->join('akun', 'penyesuaian.no_reff = akun.no_reff')
            ->order_by('tgl_transaksi', 'ASC')
            ->get()
            ->result();
    }

    public function getJurnalByNoReffSaldo($noReff)
    {
        return $this->db->select('penyesuaian.jenis_saldo,penyesuaian.saldo')
            ->from($this->table)
            ->where('penyesuaian.no_reff', $noReff)
            ->join('akun', 'penyesuaian.no_reff = akun.no_reff')
            ->order_by('tgl_transaksi', 'ASC')
            ->get()
            ->result();
    }

    public function getJurnalByNoReffSaldoMonthYear($noReff, $bulan, $tahun)
    {
        return $this->db->select('penyesuaian.jenis_saldo,penyesuaian.saldo')
            ->from($this->table)
            ->where('penyesuaian.no_reff', $noReff)
            ->where('month(penyesuaian.tgl_transaksi)', $bulan)
            ->where('year(penyesuaian.tgl_transaksi)', $tahun)
            ->join('akun', 'penyesuaian.no_reff = akun.no_reff')
            ->order_by('tgl_transaksi', 'ASC')
            ->get()
            ->result();
    }

    public function getJurnalJoinAkun()
    {
        return $this->db->select('penyesuaian.id_transaksi,penyesuaian.tgl_transaksi,akun.nama_reff,penyesuaian.no_reff,penyesuaian.jenis_saldo,penyesuaian.saldo,penyesuaian.tgl_input')
            ->from($this->table)
            ->join('akun', 'penyesuaian.no_reff = akun.no_reff')
            ->order_by('tgl_transaksi', 'ASC')
            ->order_by('tgl_input', 'ASC')
            ->order_by('jenis_saldo', 'ASC')
            ->get()
            ->result();
    }

    public function getJurnalJoinAkunDetail($bulan, $tahun)
    {
        return $this->db->select('penyesuaian.id_transaksi,penyesuaian.tgl_transaksi,akun.nama_reff,penyesuaian.no_reff,penyesuaian.jenis_saldo,penyesuaian.saldo,penyesuaian.tgl_input')
            ->from($this->table)
            ->where('month(penyesuaian.tgl_transaksi)', $bulan)
            ->where('year(penyesuaian.tgl_transaksi)', $tahun)
            ->join('akun', 'penyesuaian.no_reff = akun.no_reff')
            ->order_by('tgl_transaksi', 'ASC')
            ->order_by('tgl_input', 'ASC')
            ->order_by('jenis_saldo', 'ASC')
            ->get()
            ->result();
    }

    // public function getJurnalJoinAkunDetailFilterP($bulan, $tahun)
    // {
    //     return $this->db->select('penyesuaian.id_transaksi,penyesuaian.tgl_transaksi,akun.nama_reff,penyesuaian.no_reff,penyesuaian.jenis_saldo,penyesuaian.saldo,penyesuaian.tgl_input')
    //         ->from($this->table)
    //         ->where('month(penyesuaian.tgl_transaksi)', $bulan)
    //         ->where('year(penyesuaian.tgl_transaksi)', $tahun)
    //         ->like('penyesuaian.no_reff', '4')
    //         ->join('akun', 'penyesuaian.no_reff = akun.no_reff')
    //         ->order_by('tgl_transaksi', 'ASC')
    //         ->order_by('tgl_input', 'ASC')
    //         ->order_by('jenis_saldo', 'ASC')
    //         ->get()
    //         ->result();
    // }

    // public function getJurnalJoinAkunDetailFilterB($bulan, $tahun)
    // {
    //     return $this->db->select('penyesuaian.id_transaksi,penyesuaian.tgl_transaksi,akun.nama_reff,penyesuaian.no_reff,penyesuaian.jenis_saldo,penyesuaian.saldo,penyesuaian.tgl_input')
    //         ->from($this->table)
    //         ->where('month(penyesuaian.tgl_transaksi)', $bulan)
    //         ->where('year(penyesuaian.tgl_transaksi)', $tahun)
    //         ->like('penyesuaian.no_reff', '6')
    //         ->join('akun', 'penyesuaian.no_reff = akun.no_reff')
    //         ->order_by('tgl_transaksi', 'ASC')
    //         ->order_by('tgl_input', 'ASC')
    //         ->order_by('jenis_saldo', 'ASC')
    //         ->get()
    //         ->result();
    // }

    public function getTotalSaldoDetail($jenis_saldo, $bulan, $tahun)
    {
        return $this->db->select_sum('saldo')
            ->from($this->table)
            ->where('month(penyesuaian.tgl_transaksi)', $bulan)
            ->where('year(penyesuaian.tgl_transaksi)', $tahun)
            ->where('jenis_saldo', $jenis_saldo)
            ->get()
            ->row();
    }

    // public function getTotalSaldoDetailFilterP($jenis_saldo, $bulan, $tahun)
    // {
    //     return $this->db->select_sum('saldo')
    //         ->from($this->table)
    //         ->where('month(penyesuaian.tgl_transaksi)', $bulan)
    //         ->where('year(penyesuaian.tgl_transaksi)', $tahun)
    //         ->where('jenis_saldo', $jenis_saldo)
    //         ->like('penyesuaian.no_reff', '4')
    //         ->get()
    //         ->row();
    // }

    // public function getTotalSaldoDetailFilterB($jenis_saldo, $bulan, $tahun)
    // {
    //     return $this->db->select_sum('saldo')
    //         ->from($this->table)
    //         ->where('month(penyesuaian.tgl_transaksi)', $bulan)
    //         ->where('year(penyesuaian.tgl_transaksi)', $tahun)
    //         ->where('jenis_saldo', $jenis_saldo)
    //         ->like('penyesuaian.no_reff', '6')
    //         ->get()
    //         ->row();
    // }

    public function getTotalSaldo($jenis_saldo)
    {
        return $this->db->select_sum('saldo')
            ->from($this->table)
            ->where('jenis_saldo', $jenis_saldo)
            ->get()
            ->row();
    }

    public function insertJurnal($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function updateJurnal($id, $data)
    {
        return $this->db->where('id_transaksi', $id)->update($this->table, $data);
    }

    public function deleteJurnalPenyesuaian($id)
    {
        return $this->db->where('id_transaksi', $id)->delete($this->table);
    }

    public function getDefaultValues()
    {
        return [
            'tgl_transaksi' => date('Y-m-d'),
            'no_reff' => '',
            'id_transaksi' => '',
            'jenis_saldo' => '',
            'saldo' => '',
        ];
    }

    public function getValidationRules()
    {
        return [
            [
                'field' => 'tgl_transaksi',
                'label' => 'Tanggal Penyesuaian',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'no_reff',
                'label' => 'Nama Akun',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'jenis_saldo',
                'label' => 'Jenis Saldo',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'saldo',
                'label' => 'Saldo',
                'rules' => 'trim|required|numeric'
            ],
        ];
    }

    public function validate()
    {
        $rules = $this->getValidationRules();
        $this->form_validation->set_rules($rules);
        $this->form_validation->set_error_delimiters('<span class="text-danger" style="font-size:14px">', '</span>');
        return $this->form_validation->run();
    }
}
