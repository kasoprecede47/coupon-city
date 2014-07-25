<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH . 'presenters/category_presenter.php';
require_once APPPATH . 'presenters/coupon_presenter.php';

class Merchant extends MY_Controller {

    const USER_SESSION_VARIABLE = "merchant";

    public function __construct() {
        parent::__construct();
        $this->load->model('merchant_model', 'merchant');
        $this->load->model('category_model', 'category');
        $this->load->model('coupon_model', 'coupons');
        $this->load->library('pagination');
        $this->load->helper('file');
    }

    public function index() {
        $this->data['logged_in'] = FALSE;
        $error = !$this->session->flashdata('error_msg') ? 'Please Login or Create an Account' :
                $this->session->flashdata('error_msg');
        $this->session->flashdata('error_msg', $error);

        $merchant = $this->session->userdata('merchant');
        $this->data['logged_in'] = $this->session->userdata('logged_in');
        $this->data['merchant'] = $this->merchant->get($merchant['id']);
    }

    public function create() {
        $this->load->helper('url');

        $this->load->library('form_validation');

        $this->form_validation->set_rules('password', 'Password', 'required|matches[re_password]');
        $this->form_validation->set_rules('re_password', 'Repeat Password', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[merchants.email]');

        if ($this->form_validation->run() == FALSE) {
            $this->view = TRUE;
        } else {
            $this->view = FALSE;
            $password = $this->input->post('password');
            $email = $this->input->post('email');
            $this->_process_signup($email, $password);
        }
    }

    public function login() {
        $this->load->helper('url');

        $this->load->library('form_validation');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

        if ($this->form_validation->run() == FALSE) {
            $this->view = TRUE;
        } else {
            $this->view = FALSE;
            $password = $this->input->post('password');
            $email = $this->input->post('email');
            $this->_process_login($email, $password);
        }
    }

    public function logout() {
        $data = $this->session->userdata('merchant-image');
        if (!empty($data)) {
            delete_files('./uploads/temps');
        }
        $this->session->sess_destroy();
        redirect('merchant/index');
    }

    public function add_coupon() {
        $this->data['logged_in'] = FALSE;
        $merchant = $this->session->userdata('merchant');
        $this->data['logged_in'] = $this->session->userdata('logged_in');
        $this->data['merchant'] = $this->merchant->get($merchant['id']);
        $this->data['categories'] = new Category_presenter($this->category->get_all(), site_url('home/index/0/'));
    }

    public function my_coupons($page = 0, $category = 'all') {
        $merchant = $this->session->userdata('merchant');
        $error = !$this->session->flashdata('error_msg') ? 'Please Login or Create an Account' :
                $this->session->flashdata('error_msg');
        $this->session->flashdata('error_msg', $error);

        $this->data['logged_in'] = $this->session->userdata('logged_in');
        $this->data['merchant'] = $this->merchant->get($merchant['id']);

        $limit = 20;
        $total = $this->_count_coupons($category);
        $base_url = site_url('merchant/my_coupons/');

        $coupons = $this->_coupons($limit, $page, $category);
        $coupon_presenter = new Coupon_presenter($coupons);
        $this->data['categories'] = new Category_presenter($this->category->get_all(), site_url('merchant/my_coupons/0/'));
        $this->data['coupons'] = $coupon_presenter;
        $config = $this->_use_pagination($total, $limit, $base_url);
        $config['cur_page'] = $page;
        $this->pagination->initialize($config);
        $this->data['links'] = $this->pagination->create_links();
    }

    private function _coupons($limit, $page, $category = 'all') {
        $merchant = $this->session->userdata('merchant');
        if (!is_numeric($category)) {
            $coupons = $this->coupons
                    ->limit($limit, $page * $limit)
                    ->with('coupon_media')
                    ->get_many_by(array('merchant_id' => $merchant['id']));
        } else {
            $coupons = $this->coupons
                    ->limit($limit, $page * $limit)
                    ->with('coupon_media')
                    ->get_many_by(array('merchant_id' => $merchant['id'], 'category_id' => $category));
        }

        return $coupons;
    }

    private function _count_coupons($category = 'all') {
        $merchant = $this->session->userdata('merchant');
        if (!is_numeric($category)) {
            $count = $this->coupons->count_by(array('merchant_id' => $merchant['id']));
        } else {
            $count = $this->coupons
                    ->count_by(
                    array(
                        'category_id' => $category,
                        'merchant_id' => $merchant['id']
            ));
        }
        return $count;
    }

    private function _use_pagination($total, $per_page, $base_url) {
        $config['base_url'] = $base_url;
        $config['total_rows'] = $total;
        $config['per_page'] = $per_page;
        $config['use_page_numbers'] = TRUE;
        $config['full_tag_open'] = '<ul>';
        $config['full_tag_close'] = '</ul>';
        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li class="next">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        return $config;
    }

    public function _is_logged_in() {
        $data = $this->session->userdata('merchant');
        if (!empty($data) && is_array($data) && is_numeric($data['id'])) {
            $status = TRUE;
        } else {
            $status = FALSE;
        }
        if (!$status || !$this->session->userdata('logged_in')) {
            redirect('merchant/index');
        }
    }

    private function _process_login($email, $password) {
        $this->view = FALSE;
        if ($password !== FALSE && $email !== FALSE) {
            $response = $this->merchant->login_email($email, $password);
            if (!$response) {
                $this->session->set_flashdata('error_msg', 'Invalid Username/Password!');
                redirect(base_url('sell/login'));
            } else {
                redirect(base_url('sell'), 'refresh');
            }
        } else {
            $this->session->set_flashdata('error_msg', 'Invalid Username and/or Password Supplied');
            redirect(base_url('sell/login'));
        }
    }

    private function _process_signup($email, $password) {
        $response = $this->merchant->create_email($email, $password);
        if (!$response) {
            //var_dump($response);
            $this->session->set_flashdata('error_msg', 'Something went wrong!');
            redirect(base_url('sell/signup'));
        } else {
            redirect('merchant/index', 'refresh');
        }
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */