<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Akun_model extends CI_Model
{
    private $table = 'akun';

    public function getAkun()
    {
        return $this->db->get($this->table)->result();
    }

    public function getAkunByMonthYear($bulan, $tahun)
    {
        return $this->db->select('akun.no_reff,akun.nama_reff,akun.keterangan,transaksi.tgl_transaksi')
            ->from($this->table)
            ->where('month(transaksi.tgl_transaksi)', $bulan)
            ->where('year(transaksi.tgl_transaksi)', $tahun)
            ->join('transaksi', 'transaksi.no_reff = akun.no_reff')
            ->group_by('akun.nama_reff')
            ->order_by('akun.no_reff')
            ->get()
            ->result();
    }

    public function getAkunByMonthYearJp($bulan, $tahun)
    {
        return $this->db->select('akun.no_reff,akun.nama_reff,akun.keterangan,penyesuaian.tgl_transaksi')
            ->from($this->table)
            ->where('month(penyesuaian.tgl_transaksi)', $bulan)
            ->where('year(penyesuaian.tgl_transaksi)', $tahun)
            ->join('penyesuaian', 'penyesuaian.no_reff = akun.no_reff')
            ->group_by('akun.nama_reff')
            ->order_by('akun.no_reff')
            ->get()
            ->result();
    }

    public function getAkunByMonthYearAfterMerge($bulan, $tahun)
    {
        $query1 = $this->db->select('akun.no_reff,akun.nama_reff,akun.keterangan,transaksi.tgl_transaksi')
            ->from($this->table)
            ->where('month(transaksi.tgl_transaksi)', $bulan)
            ->where('year(transaksi.tgl_transaksi)', $tahun)
            ->join('transaksi', 'transaksi.no_reff = akun.no_reff')
            ->group_by('akun.nama_reff')
            ->order_by('akun.no_reff')
            ->get()
            ->result();

        $query2 = $this->db->select('akun.no_reff,akun.nama_reff,akun.keterangan,penyesuaian.tgl_transaksi')
            ->from($this->table)
            ->where('month(penyesuaian.tgl_transaksi)', $bulan)
            ->where('year(penyesuaian.tgl_transaksi)', $tahun)
            ->join('penyesuaian', 'penyesuaian.no_reff = akun.no_reff')
            ->group_by('akun.nama_reff')
            ->order_by('akun.no_reff')
            ->get()
            ->result();

        $query = array_merge($query1, $query2);
        return $query;
    }

    // public function getAkunByMonthYearP($bulan, $tahun)
    // {
    //     return $this->db->select('akun.no_reff,akun.nama_reff,akun.keterangan,transaksi.tgl_transaksi')
    //         ->from($this->table)
    //         ->where('month(transaksi.tgl_transaksi)', $bulan)
    //         ->where('year(transaksi.tgl_transaksi)', $tahun)
    //         ->like('akun.nama_reff', 'pendapatan')
    //         ->join('transaksi', 'transaksi.no_reff = akun.no_reff')
    //         ->group_by('akun.nama_reff')
    //         ->order_by('akun.no_reff')
    //         ->get()
    //         ->result();
    // }

    public function getAkunByMonthYearPr($bulan, $tahun)
    {
        $query1 = $this->db->select('akun.no_reff,akun.nama_reff,akun.keterangan,transaksi.tgl_transaksi')
            ->from($this->table)
            ->where('month(transaksi.tgl_transaksi)', $bulan)
            ->where('year(transaksi.tgl_transaksi)', $tahun)
            ->like('akun.nama_reff', 'prive')
            ->join('transaksi', 'transaksi.no_reff = akun.no_reff')
            ->group_by('akun.nama_reff')
            ->order_by('akun.no_reff')
            ->get()
            ->result();

        $query2 = $this->db->select('akun.no_reff,akun.nama_reff,akun.keterangan,penyesuaian.tgl_transaksi')
            ->from($this->table)
            ->where('month(penyesuaian.tgl_transaksi)', $bulan)
            ->where('year(penyesuaian.tgl_transaksi)', $tahun)
            ->like('akun.nama_reff', 'prive')
            ->join('penyesuaian', 'penyesuaian.no_reff = akun.no_reff')
            ->group_by('akun.nama_reff')
            ->order_by('akun.no_reff')
            ->get()
            ->result();

        $query = array_merge($query1, $query2);
        return $query;
    }

    // public function getAkunByMonthYearB($bulan, $tahun)
    // {
    //     return $this->db->select('akun.no_reff,akun.nama_reff,akun.keterangan,transaksi.tgl_transaksi')
    //         ->from($this->table)
    //         ->where('month(transaksi.tgl_transaksi)', $bulan)
    //         ->where('year(transaksi.tgl_transaksi)', $tahun)
    //         ->like('akun.nama_reff', 'beban')
    //         ->join('transaksi', 'transaksi.no_reff = akun.no_reff')
    //         ->group_by('akun.nama_reff')
    //         ->order_by('akun.no_reff')
    //         ->get()
    //         ->result();
    // }

    public function getAkunByMonthYearM($bulan, $tahun)
    {
        return $this->db->select('akun.no_reff,akun.nama_reff,akun.keterangan,transaksi.tgl_transaksi')
            ->from($this->table)
            ->where('month(transaksi.tgl_transaksi)', $bulan)
            ->where('year(transaksi.tgl_transaksi)', $tahun)
            ->like('akun.nama_reff', 'modal')
            ->join('transaksi', 'transaksi.no_reff = akun.no_reff')
            ->group_by('akun.nama_reff')
            ->order_by('akun.no_reff')
            ->get()
            ->result();
    }

    public function getAkunByMonthYearLR($bulan, $tahun)
    {
        $query1 = $this->db->select('akun.no_reff,akun.nama_reff,akun.keterangan,transaksi.tgl_transaksi')
            ->from($this->table)
            ->where('month(transaksi.tgl_transaksi)', $bulan)
            ->where('year(transaksi.tgl_transaksi)', $tahun)
            ->like('akun.no_reff', '4', 'after')
            ->join('transaksi', 'transaksi.no_reff = akun.no_reff')
            ->group_by('akun.nama_reff')
            ->order_by('akun.no_reff')
            ->get()
            ->result();

        $query2 = $this->db->select('akun.no_reff,akun.nama_reff,akun.keterangan,penyesuaian.tgl_transaksi')
            ->from($this->table)
            ->where('month(penyesuaian.tgl_transaksi)', $bulan)
            ->where('year(penyesuaian.tgl_transaksi)', $tahun)
            ->like('akun.no_reff', '4', 'after')
            ->join('penyesuaian', 'penyesuaian.no_reff = akun.no_reff')
            ->group_by('akun.nama_reff')
            ->order_by('akun.no_reff')
            ->get()
            ->result();

        $query3 = $this->db->select('akun.no_reff,akun.nama_reff,akun.keterangan,transaksi.tgl_transaksi')
            ->from($this->table)
            ->where('month(transaksi.tgl_transaksi)', $bulan)
            ->where('year(transaksi.tgl_transaksi)', $tahun)
            ->like('akun.no_reff', '5', 'after')
            ->join('transaksi', 'transaksi.no_reff = akun.no_reff')
            ->group_by('akun.nama_reff')
            ->order_by('akun.no_reff')
            ->get()
            ->result();

        $query4 = $this->db->select('akun.no_reff,akun.nama_reff,akun.keterangan,penyesuaian.tgl_transaksi')
            ->from($this->table)
            ->where('month(penyesuaian.tgl_transaksi)', $bulan)
            ->where('year(penyesuaian.tgl_transaksi)', $tahun)
            ->like('akun.no_reff', '5', 'after')
            ->join('penyesuaian', 'penyesuaian.no_reff = akun.no_reff')
            ->group_by('akun.nama_reff')
            ->order_by('akun.no_reff')
            ->get()
            ->result();

        $query = array_merge($query1, $query2, $query3, $query4);
        return $query;
    }

    public function getAkunByMonthYearLRP($bulan, $tahun)
    {
        $query1 = $this->db->select('akun.no_reff,akun.nama_reff,akun.keterangan,transaksi.tgl_transaksi')
            ->from($this->table)
            ->where('month(transaksi.tgl_transaksi)', $bulan)
            ->where('year(transaksi.tgl_transaksi)', $tahun)
            ->like('akun.no_reff', '4', 'after')
            ->join('transaksi', 'transaksi.no_reff = akun.no_reff')
            ->group_by('akun.nama_reff')
            ->order_by('akun.no_reff')
            ->get()
            ->result();

        $query2 = $this->db->select('akun.no_reff,akun.nama_reff,akun.keterangan,penyesuaian.tgl_transaksi')
            ->from($this->table)
            ->where('month(penyesuaian.tgl_transaksi)', $bulan)
            ->where('year(penyesuaian.tgl_transaksi)', $tahun)
            ->like('akun.no_reff', '4', 'after')
            ->join('penyesuaian', 'penyesuaian.no_reff = akun.no_reff')
            ->group_by('akun.nama_reff')
            ->order_by('akun.no_reff')
            ->get()
            ->result();

        $query = array_merge($query1, $query2);
        return $query;
    }

    public function getAkunByMonthYearAT($bulan, $tahun)
    {
        $query1 = $this->db->select('akun.no_reff,akun.nama_reff,akun.keterangan,transaksi.tgl_transaksi')
            ->from($this->table)
            ->where('month(transaksi.tgl_transaksi)', $bulan)
            ->where('year(transaksi.tgl_transaksi)', $tahun)
            ->like('akun.no_reff', '12', 'after')
            ->join('transaksi', 'transaksi.no_reff = akun.no_reff')
            ->group_by('akun.nama_reff')
            ->order_by('akun.no_reff')
            ->get()
            ->result();

        $query2 = $this->db->select('akun.no_reff,akun.nama_reff,akun.keterangan,penyesuaian.tgl_transaksi')
            ->from($this->table)
            ->where('month(penyesuaian.tgl_transaksi)', $bulan)
            ->where('year(penyesuaian.tgl_transaksi)', $tahun)
            ->like('akun.no_reff', '12', 'after')
            ->join('penyesuaian', 'penyesuaian.no_reff = akun.no_reff')
            ->group_by('akun.nama_reff')
            ->order_by('akun.no_reff')
            ->get()
            ->result();

        $query = array_merge($query1, $query2);
        return $query;
    }
    public function getAkunByMonthYearL($bulan, $tahun)
    {
        $query1 = $this->db->select('akun.no_reff,akun.nama_reff,akun.keterangan,transaksi.tgl_transaksi')
            ->from($this->table)
            ->where('month(transaksi.tgl_transaksi)', $bulan)
            ->where('year(transaksi.tgl_transaksi)', $tahun)
            ->like('akun.no_reff', '21', 'after')
            ->join('transaksi', 'transaksi.no_reff = akun.no_reff')
            ->group_by('akun.nama_reff')
            ->order_by('akun.no_reff')
            ->get()
            ->result();

        $query2 = $this->db->select('akun.no_reff,akun.nama_reff,akun.keterangan,penyesuaian.tgl_transaksi')
            ->from($this->table)
            ->where('month(penyesuaian.tgl_transaksi)', $bulan)
            ->where('year(penyesuaian.tgl_transaksi)', $tahun)
            ->like('akun.no_reff', '21', 'after')
            ->join('penyesuaian', 'penyesuaian.no_reff = akun.no_reff')
            ->group_by('akun.nama_reff')
            ->order_by('akun.no_reff')
            ->get()
            ->result();

        $query = array_merge($query1, $query2);
        return $query;
    }

    public function getAkunByMonthYearE($bulan, $tahun)
    {
        $query1 = $this->db->select('akun.no_reff,akun.nama_reff,akun.keterangan,transaksi.tgl_transaksi')
            ->from($this->table)
            ->where('month(transaksi.tgl_transaksi)', $bulan)
            ->where('year(transaksi.tgl_transaksi)', $tahun)
            ->like('akun.no_reff', '30', 'after')
            ->join('transaksi', 'transaksi.no_reff = akun.no_reff')
            ->group_by('akun.nama_reff')
            ->order_by('akun.no_reff')
            ->get()
            ->result();

        $query2 = $this->db->select('akun.no_reff,akun.nama_reff,akun.keterangan,penyesuaian.tgl_transaksi')
            ->from($this->table)
            ->where('month(penyesuaian.tgl_transaksi)', $bulan)
            ->where('year(penyesuaian.tgl_transaksi)', $tahun)
            ->like('akun.no_reff', '30', 'after')
            ->join('penyesuaian', 'penyesuaian.no_reff = akun.no_reff')
            ->group_by('akun.nama_reff')
            ->order_by('akun.no_reff')
            ->get()
            ->result();

        $query = array_merge($query1, $query2);
        return $query;
    }

    public function getAkunByMonthYearLRB($bulan, $tahun)
    {
        $query1 = $this->db->select('akun.no_reff,akun.nama_reff,akun.keterangan,transaksi.tgl_transaksi')
            ->from($this->table)
            ->where('month(transaksi.tgl_transaksi)', $bulan)
            ->where('year(transaksi.tgl_transaksi)', $tahun)
            ->like('akun.no_reff', '5', 'after')
            ->join('transaksi', 'transaksi.no_reff = akun.no_reff')
            ->group_by('akun.nama_reff')
            ->order_by('akun.no_reff')
            ->get()
            ->result();

        $query2 = $this->db->select('akun.no_reff,akun.nama_reff,akun.keterangan,penyesuaian.tgl_transaksi')
            ->from($this->table)
            ->where('month(penyesuaian.tgl_transaksi)', $bulan)
            ->where('year(penyesuaian.tgl_transaksi)', $tahun)
            ->like('akun.no_reff', '5', 'after')
            ->join('penyesuaian', 'penyesuaian.no_reff = akun.no_reff')
            ->group_by('akun.nama_reff')
            ->order_by('akun.no_reff')
            ->get()
            ->result();

        $query = array_merge($query1, $query2);
        return $query;
    }

    // public function getAkunByMonthYearAt($bulan, $tahun)
    // {
    //     return $this->db->select('akun.no_reff,akun.nama_reff,akun.keterangan,transaksi.tgl_transaksi')
    //         ->from($this->table)
    //         ->where('month(transaksi.tgl_transaksi)', $bulan)
    //         ->where('year(transaksi.tgl_transaksi)', $tahun)
    //         ->like('akun.no_reff', '12', 'after')
    //         ->join('transaksi', 'transaksi.no_reff = akun.no_reff')
    //         ->group_by('akun.nama_reff')
    //         ->order_by('akun.no_reff')
    //         ->get()
    //         ->result();
    // }

    // public function getAkunByMonthYearU($bulan, $tahun)
    // {
    //     return $this->db->select('akun.no_reff,akun.nama_reff,akun.keterangan,transaksi.tgl_transaksi')
    //         ->from($this->table)
    //         ->where('month(transaksi.tgl_transaksi)', $bulan)
    //         ->where('year(transaksi.tgl_transaksi)', $tahun)
    //         ->like('akun.nama_reff', 'utang', 'after')
    //         ->join('transaksi', 'transaksi.no_reff = akun.no_reff')
    //         ->group_by('akun.nama_reff')
    //         ->order_by('akun.no_reff')
    //         ->get()
    //         ->result();
    // }

    // public function getAkunByMonthYearMp($bulan, $tahun)
    // {
    //     return $this->db->select('akun.no_reff,akun.nama_reff,akun.keterangan,transaksi.tgl_transaksi')
    //         ->from($this->table)
    //         ->where('month(transaksi.tgl_transaksi)', $bulan)
    //         ->where('year(transaksi.tgl_transaksi)', $tahun)
    //         ->like('akun.no_reff', '311')
    //         ->join('transaksi', 'transaksi.no_reff = akun.no_reff')
    //         ->group_by('akun.nama_reff')
    //         ->order_by('akun.no_reff')
    //         ->get()
    //         ->result();
    // }

    public function countAkunByNama($str)
    {
        return $this->db->where('nama_reff', $str)->get($this->table)->num_rows();
    }

    public function countAkunByNoReff($str)
    {
        return $this->db->where('no_reff', $str)->get($this->table)->num_rows();
    }

    public function getAkunByNo($noReff)
    {
        return $this->db->where('no_reff', $noReff)->get($this->table)->row();
    }

    public function insertAkun($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function updateAkun($noReff, $data)
    {
        return $this->db->where('no_reff', $noReff)->update($this->table, $data);
    }

    public function deleteAkun($noReff)
    {
        return $this->db->where('no_reff', $noReff)->delete($this->table);
    }

    public function getDefaultValues()
    {
        return [
            'no_reff' => '',
            'nama_reff' => '',
            'keterangan' => ''
        ];
    }

    public function getValidationRules()
    {
        return [
            [
                'field' => 'no_reff',
                'label' => 'No.Reff',
                'rules' => 'trim|required|numeric|callback_isNoAkunThere'
            ],
            [
                'field' => 'nama_reff',
                'label' => 'Nama Reff',
                'rules' => 'trim|required|callback_isNamaAkunThere'
            ],
            [
                'field' => 'keterangan',
                'label' => 'Keterangan',
                'rules' => 'trim|required'
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
