<?php
/**
 * Description of User
 *
 * @author Udana
 */
class User extends Admin_Controller
{
    public function __construct()
    {
	parent::__construct();
	
	$this->load->model('user_model','user');
	$this->load->model('Location_model','location');
	$this->load->model("Mail_model", "mail");
    }
    
    public function login()
    {
	if($this->input->server('REQUEST_METHOD') === 'POST')
    	{
	    $this->form_validation->set_rules('username', 'Username', 'required');
	    $this->form_validation->set_rules('password', 'Password', 'required|md5');
	    $this->form_validation->set_rules('location', 'Location', 'required');

	    $result = array();

	    if ($this->form_validation->run() == FALSE)
	    {
		$result['status'] = 'error';
		$result['errors'] = validation_errors();
	    }
	    else
	    {
		$post = $this->input->post();
		$user = $this->user->getUserByUsername($post['username']);
		
		if($user && $user->password == $post['password'])
		{      
		    $location = $this->location->get($post['location']);
		    if($location->ip == $_SERVER['REMOTE_ADDR'])
		    {
			$result['status'] = 'success';
			$this->session->set_userdata('user',$user);
			$this->session->set_userdata('location',$location);
			$this->session->set_userdata('logged',TRUE);
		    }
		    else 
		    {
			$result['status'] = 'warn';
			$result['msg'] = "Are you sure you are at $location->name ?";
			$result['user_id'] = $user->id;
			$result['location_id'] = $location->id;
			
//			$result['status'] = 'error';
//			$result['errors'] = "<p>Location IP doesn't match</p>";
		    }
		    
                    if($lastPage = $this->session->userdata('last_page'))
                    {
                        $result['rd'] = $lastPage;
                        $this->session->unset_userdata('last_page');
                    }
                    else
                    {
                        $result['rd'] = site_url();
					}
					
			//Add activity log
			addActivity($user->id,'SIGN_IN');
		}
		else
		{
		    $result['status'] = 'error';
		    $result['errors'] = "<p>Incorrect Username or password</p>";
		}            
	    }

	    

	    echo json_encode($result);
	}
	else
	{
	    
	    $this->data['locations'] = $this->location->getAll(TRUE);
	    $this->load->view('user/login',$this->data);
	}
    }
    
    public function index()
    {
	$this->data['bc1'] = 'Users';
	$this->data['bc2'] = 'View';
	$this->data['users'] = $this->user->getAll();
	$this->load->view('user/index',$this->data);
    }
    
    public function add()
    {
	$this->data['bc1'] = 'User';
	$this->data['bc2'] = 'Add';
        
        
	if($this->input->server('REQUEST_METHOD') === 'POST')
    	{
	    $this->form_validation->set_rules('username', 'Username', 'trim|required|callback_isUsernameExist');
	    $this->form_validation->set_rules('fname', 'First Name', 'trim|required');
	    $this->form_validation->set_rules('lname', 'Last Name', 'trim|required');
	    $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
	    $this->form_validation->set_rules('email', 'Email', 'trim');
	    $this->form_validation->set_rules('type', 'Type', 'trim');
	    $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]|callback_check_pass');
	    $this->form_validation->set_rules('repassword', 'Re Password', 'required|matches[password]');
	    
	    if($this->form_validation->run() == TRUE)
	    {
		$post = $this->input->post();
		$post['created'] = date('Y-m-d h:i:s');
                $post['password'] = md5($post['password']);
		unset($post['repassword']);
		$this->user->add($post);
		$this->session->set_flashdata('message','User Added Successfully');
		redirect('user');
	    }
	}
	$this->load->view('user/add',  $this->data);
    }
    
    public function check_pass($pass)
    {
        if (empty($pass))
        {
            return TRUE;
        }
        
        $ret = true;
        
        if(!(bool)preg_match('/\d/', $pass))
        {
            $ret = false;
            $this->form_validation->set_message('check_pass', 'Password needs to have at least one number.');
        }
        elseif(!(bool)preg_match('/[a-z]/', $pass))
        {
            $ret = false;
            $this->form_validation->set_message('check_pass', 'Password needs to have at least one lowercase letter.');
        }
        elseif(!(bool)preg_match('/[A-Z]/', $pass))
        {
            $ret = false;
            $this->form_validation->set_message('check_pass', 'Password needs to have at least one Uppercase Letter.');
        }
        elseif(!(bool)preg_match('/\W/', $pass))
        {
            $ret = false;
            $this->form_validation->set_message('check_pass', 'Password needs to have at least one Spcial Charactor.');
        }
        
        return $ret;
    }
    
    public function remove($id)
    {
	$this->user->remove($id);
	$this->session->set_flashdata('message','User Removed Successfully');
	redirect('user');
    }
    
    public function edit($id)
    {
	$this->data['bc1'] = 'User';
	$this->data['bc2'] = 'Edit';
	
	$this->data['usere'] = $this->user->get($id);
	
	if($this->input->server('REQUEST_METHOD') === 'POST')
    	{
	    $post = $this->input->post();
	    
	    $this->form_validation->set_rules('username', 'Username', 'trim|required');
	    $this->form_validation->set_rules('fname', 'First Name', 'trim|required');
	    $this->form_validation->set_rules('lname', 'Last Name', 'trim|required');
	    $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
	    $this->form_validation->set_rules('email', 'Email', 'trim');
	    $this->form_validation->set_rules('type', 'Type', 'trim');
	    if(isset($post['chng_psw_chkbox']))
	    {
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]|callback_check_pass');
		$this->form_validation->set_rules('repassword', 'Re Password', 'required|matches[password]');
	    }
	    
	    if($this->form_validation->run() == TRUE)
	    {
		$post = $this->input->post();
		$post['created'] = date('Y-m-d h:i:s');
                if(isset($post['chng_psw_chkbox']))$post['password'] = md5($post['password']);
		unset($post['repassword'],$post['chng_psw_chkbox']);
                
		$this->user->update($id,$post);
		$this->session->set_flashdata('message','User Updated Successfully');
		redirect('user');
	    }
	}
	$this->load->view('user/edit',  $this->data);
    }
    
    public function isUsernameExist($username)
    {
        if ($this->user->isUsernameExist($username))
        {
                $this->form_validation->set_message('isUsernameExist', 'This username already exist.');
                return FALSE;
        }
        else
        {
                return TRUE;
        }
    }
    
    public function signin()
    {
	$this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required|md5');
        
	
        $result = array();
        
        if ($this->form_validation->run() == FALSE)
        {
            $result['status'] = 'error';
            $result['errors'] = validation_errors();
        }
        else
        {
            $post = $this->input->post();
            $user = $this->user->getUserByEmail($post['email']);
            
            if($user && $user->password == $post['password'])
            {                
                $result['status'] = 'success';
                $this->session->set_userdata('user',$user);                
            }
            else
            {
                $result['status'] = 'error';
                $result['errors'] = "<p>Incorrect email or password</p>";
            }            
        }
        
        $result['redirect'] = ($this->cart->contents())? site_url('shop/checkout'):site_url('user/account');
        
        echo json_encode($result);
    }
    
    public function loggedInAndEmail()
    {
	$post = $this->input->post();
	$user = $this->user->get($post['id']);
	$location = $this->location->get($post['lid']);
	
	$this->session->set_userdata('user',$user);
	$this->session->set_userdata('location',$location);
	$this->session->set_userdata('logged',TRUE);
	
	$data = array();
	$data['user'] = $user;
	$data['location'] = $location;
	
	$subject = "Update $location->name IP address";
	$message =  $this->load->view('emails/wrong_location',$data,true);
	$alertEmail = $this->config->item('location_update');
	
//	$this->mail->send_mail($alertEmail,$subject,$message);	    
	
	$result = array();
	$result['status'] = 'success';
	
	echo json_encode($result);
    }
    
    public function logout()
    {
	$this->session->unset_userdata('user');
	$this->session->unset_userdata('logged');
	redirect('/');
    }
    
    public function sms()
    {
        $result = SendSMSnew('+94777367567', 'This is a test');
        print_r($result);
    }
}
