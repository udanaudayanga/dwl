<?php

/**
 * Description of Locations
 *
 * @author Udana
 */
class Locations extends Admin_Controller
{
    public function __construct()
    {
	parent::__construct();
	
	$this->load->model('Location_model','location');
    }
    
    public function index()
    {
	$this->data['bc1'] = 'Locations';
	$this->data['bc2'] = 'View';
	$this->data['locations'] = $this->location->getAll();
	$this->load->view('locations/index',$this->data);
    }
    
    public function add()
    {
	$this->data['bc1'] = 'Location';
	$this->data['bc2'] = 'Add';
	
	if($this->input->server('REQUEST_METHOD') === 'POST')
    	{  
	    $this->form_validation->set_rules('name', 'Name', 'trim|is_unique[locations.name]|required');
	    $this->form_validation->set_rules('ip', 'IP', 'trim|valid_ip|required');
            $this->form_validation->set_rules('abbr', 'ABBR', 'trim');
            $this->form_validation->set_rules('dea', 'DEA#', 'trim');
            $this->form_validation->set_rules('address', 'Address', 'trim');
	    
	    if($this->form_validation->run() == TRUE)
	    {
		$post = $this->input->post();
		$this->location->add($post);
                $this->session->set_flashdata('message','Location Added Successfully');
		redirect('locations');
	    }
	}
	
	$this->load->view('locations/add',$this->data);
    }
    
    public function edit($id)
    {
	$this->data['bc1'] = 'Location';
	$this->data['bc2'] = 'Edit';
	$this->data['location'] = $this->location->get($id);
	
	if($this->input->server('REQUEST_METHOD') === 'POST')
    	{  
	    $this->form_validation->set_rules('name', 'Name', 'trim|required');
	    $this->form_validation->set_rules('ip', 'IP', 'trim|valid_ip|required');
            $this->form_validation->set_rules('abbr', 'ABBR', 'trim');
            $this->form_validation->set_rules('dea', 'DEA#', 'trim');
            $this->form_validation->set_rules('address', 'Address', 'trim');
	    
	    if($this->form_validation->run() == TRUE)
	    {
		$post = $this->input->post();
		$this->location->update($id,$post);
                $this->session->set_flashdata('message','Location Updated Successfully');
		redirect('locations');
	    }
	}
	
	$this->load->view('locations/edit',$this->data);
    }
    
    public function delete($id)
    {
        $this->location->delete($id);
        $this->session->set_flashdata('message','Location Removed Successfully');
        redirect('locations');
    }
}
