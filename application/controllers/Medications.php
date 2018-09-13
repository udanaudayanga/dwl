<?php
/**
 * Description of Medications
 *
 * @author Udana
 */
class Medications extends Admin_Controller
{
    public function __construct() {
        parent::__construct();
        
        $this->load->model('Medication_model','medication');
    }
    
    public function index()
    {
        $this->data['bc1'] = 'Medications';
	$this->data['bc2'] = 'View';
        $this->load->helper('text');
        
        $this->data['medications'] = $this->medication->getAll();
        $this->load->view('medications/index',$this->data);
    }
    
    public function edit($id)
    {
        $this->data['bc1'] = 'Medications';
	$this->data['bc2'] = 'Edit';
        
        $this->data['med'] = $this->medication->get($id);
        
        if($this->input->server('REQUEST_METHOD') === 'POST')
    	{ 
            $this->form_validation->set_rules('med', 'Medication', 'trim|required');
            
            if($this->form_validation->run() == TRUE)
	    {
                $post = $this->input->post();
                $this->medication->update($id,$post);
                $this->session->set_flashdata('message','Updated Successfully');
                redirect('medications');
            }
        }
        
        $this->load->view('medications/edit',$this->data);
    }
    
    public function delete($id)
    {
        $this->medication->delete($id);
        $this->session->set_flashdata('message','Removed Successfully');
        redirect('medications');
    }
    
    public function add()
    {
        $this->data['bc1'] = 'Medications';
	$this->data['bc2'] = 'Add';
        
        
        if($this->input->server('REQUEST_METHOD') === 'POST')
    	{ 
            $this->form_validation->set_rules('med', 'Medication', 'trim|is_unique[prev_meds.med]|required');
            $this->form_validation->set_rules('ailment', 'Ailment', 'trim|required');
            
            if($this->form_validation->run() == TRUE)
	    {
                $post = $this->input->post();
                $this->medication->add($post);
                $this->session->set_flashdata('message','Added Successfully');
                redirect('medications');
            }
        }
        
        $this->load->view('medications/add',$this->data);        
    }
}
