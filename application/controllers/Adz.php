<?php
/**
 * Description of Adz
 *
 * @author Udana
 */
class Adz extends Admin_Controller
{
    public function __construct() {
        parent::__construct();
        
        $this->load->model('Adz_model','adz');
    }
    public function index()
    {
        $this->data['bc1'] = 'Adz';
	$this->data['bc2'] = 'All';
        
        $this->load->helper('text');
        
        $this->data['adz'] = $this->adz->getAll();
        
        $this->load->view('adz/index',$this->data);
    }
    
    public function add()
    {
        $this->data['bc1'] = 'Adz';
	$this->data['bc2'] = 'Add';
        $this->data['errors'] ='';
        
        if($this->input->server('REQUEST_METHOD') === 'POST')
    	{ 
            $this->form_validation->set_rules('text', 'Text', 'trim');
            
            if($this->form_validation->run() == TRUE)
	    {
                $uploaded = $this->upload();
                
                if(!isset($uploaded['error']))
		{    
                    $post = $this->input->post();
		    $post['updated'] = date('Y-m-d H:i:s');
		    $post['img'] = $uploaded['img'];
                    $active_ad = $this->adz->getActiveAd();
                    if(!$active_ad) $post['status'] = 1;
                    
                    $this->adz->add($post);
                    $this->session->set_flashdata('message','Added Successfully');
                    redirect('adz');
                
                }
		else
		{
		    $this->data['errors'] = $uploaded['error'];
		}
            }
            else
	    {
		$this->data['errors'] = validation_errors('<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12" style="padding:0 5px;"><div role="alert" class="alert fresh-color alert-danger"><strong>','</strong></div></div>');
	    }
        }
        
        $this->load->view('adz/add',$this->data);        
    }
    
    public function upload()
    {	
	// Upload photo
	$config['upload_path'] = './assets/upload/adz/';;
	$config['allowed_types'] = 'gif|jpg|png|jpeg';
	$config['encrypt_name'] = TRUE;
	$data = array();
	$this->load->library('upload', $config);

	if (!$this->upload->do_upload('photo'))
	{
	    $data['error'] = $this->upload->display_errors('<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12" style="padding:0 5px;"><div role="alert" class="alert fresh-color alert-danger"><strong>','</strong></div></div>');
	}
	else
	{
	    $upload_data = $this->upload->data();
	    $data['img'] = $upload_data['file_name'];
	}

	return $data;
    }
    
    public function edit($id)
    {
        $this->data['bc1'] = 'Adz';
	$this->data['bc2'] = 'Edit';
        $this->data['errors'] ='';
        
        $ad = $this->adz->get($id);
        $this->data['ad'] = $ad;
        
        if($this->input->server('REQUEST_METHOD') === 'POST')
    	{ 
            $this->form_validation->set_rules('text', 'Text', 'trim');
            
            if($this->form_validation->run() == TRUE)
	    {
                $post = $this->input->post();
                
                $uploaded = array();
		
		if (!empty($_FILES['photo']['name'])) {
		    $uploaded = $this->upload();
		}
                
                if(!isset($uploaded['error']))
		{
		    $post['updated'] = date('Y-m-d H:i:s');
		    if(isset($uploaded['img']))
		    {
			$post['img'] = $uploaded['img'];
                        if(file_exists("./assets/upload/adz/$ad->img"))unlink("./assets/upload/adz/$ad->img");
		    }
                
                    $this->adz->update($id,$post);
                    $this->session->set_flashdata('message','Updated Successfully');
                    redirect('adz');
                }
            }
        }
        
        $this->load->view('adz/edit',$this->data);
    }
    
    public function delete($id)
    {
        $ad = $this->adz->get($id);
        
        if($ad->img)
                if(file_exists("./assets/upload/adz/$ad->img"))unlink("./assets/upload/adz/$ad->img");
        
        $this->adz->delete($id);
        $this->session->set_flashdata('message','Removed Successfully');
        redirect('adz');
    }
}
