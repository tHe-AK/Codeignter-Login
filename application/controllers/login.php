<?php
   

class Login extends CI_Controller {
    
    public function __construct() {
        parent::__construct();

        // Load form helper library
        $this->load->helper('form');

        // Load form validation library
        $this->load->library('form_validation');

        // Load session library
        $this->load->library('session');

        // Load database
        $this->load->model('frontend/model_login');
        }
        public function index()
	{
		$data['title']='Login';
                $this->load->view('frontend/include/header',$data);
		$this->load->view('frontend/view_user_login');
		$this->load->view('frontend/include/footer');
        }
		
        public function user_login_process() {

        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            if(isset($this->session->userdata['user_logged_in'])){
            redirect(base_url().'user/dashboard/');
            }
            else{
                    $data['title']='Login';
                    $this->load->view('frontend/include/header',$data);
                    $this->load->view('frontend/view_user_login');
                    $this->load->view('frontend/include/footer');
                }
            }
        else {
                $data = array(
                'username' => $this->input->post('username'),
                'password' => $this->input->post('password')
                );
                $result = $this->model_login->login($data);
                if ($result == TRUE) 
                    {

                    $username = $this->input->post('username');
                    $result = $this->model_login->read_user_information($username);
                    if ($result != false) {
                    $session_data = array(
                        'username' => $result[0]->clg_usr,
                        'id' => $result[0]->id

                    );
                    // Add user data in session
                    $this->session->set_userdata('user_logged_in', $session_data);
                    redirect(base_url().'user/dashboard');
                    }
                    }
                else 
                {
                    $data = array(
                    'error_message' => '*Invalid Username or Password'
                    );
                    $data['title']='Login';
                    $this->load->view('frontend/include/header',$data);
                    $this->load->view('frontend/view_user_login');
                    $this->load->view('frontend/include/footer');
                }
            }
        }

            
        }
 
