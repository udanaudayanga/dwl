<?php
/**
 * Description of MY_Controller
 *
 * @author Udana
 */
class MY_Controller extends CI_Controller
{
    public $data = array();
    public function __construct()
    {
	parent::__construct();
	
	$this->data['class'] = $this->router->class;
	$this->data['action'] = $this->router->method;
	
//	if(!$this->session->userdata('logged'))
//	{
//	    if(!in_array($this->router->method, array('login','loggedInAndEmail'))) redirect('user/login','refresh');
//	}
//	else
//	{
//	    $this->data['user'] = $this->session->userdata('user');
//            $this->data['location'] = $this->session->userdata('location');
//	}
    }
}

class Admin_Controller extends MY_Controller
{
    public function __construct()
    {
	parent::__construct();
		
	if(!$this->session->userdata('logged'))
	{
	    if(!in_array($this->router->method, array('login','loggedInAndEmail'))) 
            {
                if($this->router->method != 'logout')$this->session->set_userdata('last_page', current_url());
                redirect('user/login','refresh');
            }
	}
	else
	{
	    $this->data['user'] = $this->session->userdata('user');
            $this->data['location'] = $this->session->userdata('location');
	}
    }
}

class Dr_controller extends MY_Controller
{
    public function __construct()
    {
	parent::__construct();
		
	if(!$this->session->userdata('dr_logged'))
	{
	    if(!in_array($this->router->method, array('login'))) redirect('doctor/login','refresh');
	}
	else
	{
	    $this->data['user'] = $this->session->userdata('user');
	}
    }
}

class Hybrid_controller extends MY_Controller
{
    public function __construct()
    { 
	parent::__construct();
		
	if(!$this->session->userdata('dr_logged') && !$this->session->userdata('logged'))
	{
	    if(!in_array($this->router->method, array('login'))) redirect('user/login','refresh');
	}
	else
	{
	    $this->data['user'] = $this->session->userdata('user');
            $this->data['location'] = $this->session->userdata('location');
	}
    }
}