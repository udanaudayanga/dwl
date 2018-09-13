<?php
/**
 * Description of Category
 *
 * @author Udana
 */
class Category extends Admin_Controller
{
    public function __construct() {
        parent::__construct();
        
        $this->load->model('Category_model','category');
    }
    
    public function index()
    {
        $this->data['bc1'] = 'Category';
	$this->data['bc2'] = 'View';
        $this->load->helper('text');
        
        $this->data['categories'] = $this->category->getAll();
        $this->load->view('category/index',$this->data);
    }
    
    public function edit($id)
    {
        $this->data['bc1'] = 'Category';
	$this->data['bc2'] = 'Edit';
        
        $this->data['category'] = $this->category->get($id);
        
        if($this->input->server('REQUEST_METHOD') === 'POST')
    	{ 
            $this->form_validation->set_rules('name', 'Name', 'trim|required');
            
            if($this->form_validation->run() == TRUE)
	    {
                $post = $this->input->post();
                $this->category->update($id,$post);
                $this->session->set_flashdata('message','Updated Successfully');
                redirect('category');
            }
        }
        
        $this->load->view('category/edit',$this->data);
    }
    
    public function delete($id)
    {
        $this->category->delete($id);
        $this->session->set_flashdata('message','Removed Successfully');
        redirect('category');
    }
    
    public function add()
    {
        $this->data['bc1'] = 'Category';
	$this->data['bc2'] = 'Add';
        
        
        if($this->input->server('REQUEST_METHOD') === 'POST')
    	{ 
            $this->form_validation->set_rules('name', 'Name', 'trim|is_unique[categories.name]|required');
            
            if($this->form_validation->run() == TRUE)
	    {
                $post = $this->input->post();
                $this->category->add($post);
                $this->session->set_flashdata('message','Added Successfully');
                redirect('category');
            }
        }
        
        $this->load->view('category/add',$this->data);        
    }
}
