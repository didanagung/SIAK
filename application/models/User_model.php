<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
    private $table = 'user';

    public function getDefaultValuesLogin()
    {
        return [
            'username' => '',
            'password' => '',
        ];
    }

    public function getDefaultValuesRegister()
    {
        return [
            'nama' => '',
            'role' => '',
            'jk' => '',
            'alamat' => '',
            'email' => '',
            'username' => '',
            'password' => '',
        ];
    }

    public function getValidationRulesLogin()
    {
        return [
            [
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'trim|required'
            ]
        ];
    }

    public function getValidationRulesRegister()
    {
        return [
            [
                'field' => 'nama',
                'label' => 'Nama',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'role',
                'label' => 'Role',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'jk',
                'label' => 'Jenis Kelamin',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'alamat',
                'label' => 'Alamat',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'trim|required|is_unique[user.username]'
            ],
            [
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'trim|required'
            ],
        ];
    }

    public function insertUser($data)
    {
        $nama = $data->nama;
        $username = $data->username;
        $role = $data->role;
        $jk = $data->jk;
        $alamat = $data->alamat;
        $email = $data->email;
        $password = md5(sha1(md5($data->password)));
        $datas = array(
            'nama' => $nama,
            'username' => $username,
            'role' => $role,
            'jk' => $jk,
            'alamat' => $alamat,
            'email' => $email,
            'password' => $password,
        );
        $this->db->insert($this->table, $datas);
        return true;
    }

    public function validateLogin()
    {
        $rules = $this->getValidationRulesLogin();
        $this->form_validation->set_rules($rules);
        return $this->form_validation->run();
    }


    public function validateRegister()
    {
        $rules = $this->getValidationRulesRegister();
        $this->form_validation->set_rules($rules);
        return $this->form_validation->run();
    }

    public function run($data)
    {
        $username = $data->username;
        $password = md5(sha1(md5($data->password)));

        $user = $this->db->where('username', $username)
            ->where('password', $password)
            ->get($this->table)
            ->row();

        if (count($user)) {
            $sessionData = [
                'login' => true,
                'username' => $user->nama,
                'id' => $user->id_user,
                'role' => $user->role
            ];
            $this->session->set_userdata($sessionData);
            return true;
        }

        return false;
    }

    public function logout()
    {
        $sessionData = ['login', 'username', 'id', 'role'];
        $this->session->unset_userdata($sessionData);
        $this->session->sess_destroy();
    }

    public function updateUser($id, $data)
    {
        return $this->db->where('id_user', $id)->update($this->table, $data);
    }
}
