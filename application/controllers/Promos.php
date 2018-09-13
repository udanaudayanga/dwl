<?php

/**
 * Description of Promos
 *
 * @author Udana
 */
class Promos extends Admin_Controller
{
    public function __construct() {
        parent::__construct();
        
        $this->load->model('Promos_model','promos');
        $this->load->model('Queue_model','queue');
    }
    
    public function index()
    {
        $this->data['bc1'] = "Promos";
        $this->data['bc2'] = "View All";
        
        $this->data['promos'] = $this->promos->getAll();
        $this->load->view('promos/index',$this->data);
    }
    
    public function add()
    {
        $this->data['bc1'] = "Promos";
        $this->data['bc2'] = "Add";
        
        $this->data['promo_types'] = $this->promos->getPromoTypes();
        $this->data['allPros'] = getAllProWithCats();
        
        $this->load->view('promos/add',$this->data);
    }
    
    public function addPromo()
    {
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('start', 'Start Date', 'trim|required');
        $this->form_validation->set_rules('end', 'End Date', 'trim|required');
        $this->form_validation->set_rules('type', 'Promo Type', 'trim|required');
        
        $post = $this->input->post();
        
        if($post['type'] == 1)
        {
            $this->form_validation->set_rules('wave_off_amount', 'Wave Off Amount', 'trim|required');
        }
        elseif ($post['type'] == 2) 
        {
            $this->form_validation->set_rules('product', 'Product', 'trim|required');
            $this->form_validation->set_rules('wave_off_amount', 'Wave Off Amount', 'trim|required|numeric');
        }
        elseif($post['type'] == 3)
        {
            $this->form_validation->set_rules('product', 'Product', 'trim|required');
        }
        elseif($post['type'] == 5)
        {
            $this->form_validation->set_rules('pro_1', 'Product One', 'trim|required');
            $this->form_validation->set_rules('wave_off_1', 'Wave Off %', 'trim|required|numeric');
            $this->form_validation->set_rules('pro_2', 'Product Two', 'trim|required');
            $this->form_validation->set_rules('wave_off_2', 'Wave Off %', 'trim|required|numeric');
        }
        elseif($post['type'] == 6)
        {
            $this->form_validation->set_rules('pro_1', 'Product One', 'trim|required');
            $this->form_validation->set_rules('wave_off_1', 'Wave Off %', 'trim|required|numeric');
            $this->form_validation->set_rules('pro_2', 'Product Two', 'trim|required');
            $this->form_validation->set_rules('wave_off_2', 'Wave Off %', 'trim|required|numeric');
            $this->form_validation->set_rules('pro_3', 'Product Two', 'trim|required');
            $this->form_validation->set_rules('wave_off_3', 'Wave Off %', 'trim|required|numeric');
        }
        
        $result = array();
        
        if($this->form_validation->run() == TRUE)
	{
             
            if($post['type'] == 1)
            {
                $promoXML = new SimpleXMLElement("<promo></promo>");
                $offer = $promoXML->addChild('offer');
                $offer->addAttribute('type', $post['wave_off_type']);
                $offer->addAttribute('value', $post['wave_off_amount']);
                $post['logic'] = $promoXML->asXML();
                
                unset($post['wave_off_type'],$post['wave_off_amount']);                
            }
            elseif($post['type'] == 2)
            {
                $promoXML = new SimpleXMLElement("<promo></promo>");
                $condition  = $promoXML->addChild('condition');
                $condition->addAttribute('pro_id', $post['product']);
                $condition->addAttribute('qty', 1);
                $offer = $promoXML->addChild('offer');
                $offer->addAttribute('type', $post['wave_off_type']);
                $offer->addAttribute('value', $post['wave_off_amount']);
                $post['logic'] = $promoXML->asXML();
                
                unset($post['product'],$post['wave_off_amount'],$post['wave_off_type']);           
            }
            elseif($post['type']== 3)
            {
                $promoXML = new SimpleXMLElement("<promo></promo>");
                $condition  = $promoXML->addChild('condition');
                $condition->addAttribute('pro_id', $post['product']);
                $condition->addAttribute('qty', 1);
                $offer = $promoXML->addChild('offer');
                $offer->addAttribute('type', 'product');
                $offer->addAttribute('value', 'free');
                $post['logic'] = $promoXML->asXML();
                
                unset($post['product']);           
            }
            elseif($post['type']== 4)
            {
                $promoXML = new SimpleXMLElement("<promo></promo>");
                $condition = $promoXML->addChild('condition');
                $condition->addAttribute('pro_id',$post['product']);
                $condition->addAttribute('qty',2);
                $offer = $promoXML->addChild('offer');                
                $offer->addAttribute('value', $post['wave_off_amount']);;
                $post['logic'] = $promoXML->asXML();
                
                unset($post['product'],$post['wave_off_amount']);    
            }
            elseif($post['type'] == 5)
            {
                $promoXML = new SimpleXMLElement("<promo></promo>");
                $condition = $promoXML->addChild('condition');
                $condition->addAttribute('pro1',$post['pro_1']);
                $condition->addAttribute('pro2',$post['pro_2']);
                $offer = $promoXML->addChild('offer');
                $offer->addAttribute('pro1',$post['wave_off_1']);
                $offer->addAttribute('pro2',$post['wave_off_2']);
                
                $post['logic'] = $promoXML->asXML();
                
                unset($post['pro_1'],$post['pro_2'],$post['wave_off_1'],$post['wave_off_2']);
            }
            elseif($post['type'] == 6)
            {
                $promoXML = new SimpleXMLElement("<promo></promo>");
                $condition = $promoXML->addChild('condition');
                $condition->addAttribute('pro1',$post['pro_1']);
                $condition->addAttribute('pro2',$post['pro_2']);
                $condition->addAttribute('pro3',$post['pro_3']);
                $offer = $promoXML->addChild('offer');
                $offer->addAttribute('pro1',$post['wave_off_1']);
                $offer->addAttribute('pro2',$post['wave_off_2']);
                $offer->addAttribute('pro3',$post['wave_off_3']);
                
                $post['logic'] = $promoXML->asXML();
                
                unset($post['pro_1'],$post['pro_2'],$post['pro_3'],$post['wave_off_1'],$post['wave_off_2'],$post['wave_off_3']);
            }
            
            $post['created'] = date('Y-m-d');
            $this->promos->add($post);
            $this->session->set_flashdata('message','Promotion added successfully');
            $result['status'] = 'success';
        }
        else
        {
            $result['status'] = 'error';
            $result['errors'] = validation_errors('<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" style="padding:0 5px;"><div style="padding:5px;" role="alert" class="alert fresh-color alert-danger"><strong>','</strong></div></div>');             
        }
        
        echo json_encode($result);
    }
    
    public function remove($id)
    {
        $this->promos->delete($id);
        $this->session->set_flashdata('message','Promotion Removed Successfully');
        redirect("promos");
    }
    
    public function general()
    {
        $this->data['bc1'] = "Promos";
        $this->data['bc2'] = "General";
        $this->data['promos'] = $this->promos->getAllGeneral();
        $this->load->view('promos/general',$this->data);
    }
    
    public function addGeneral()
    {
        $this->data['bc1'] = "Promos";
        $this->data['bc2'] = "Add General";
        $this->data['promos'] = $this->promos->getAllActive();
        
        if($this->input->server('REQUEST_METHOD') === 'POST')
    	{ 
            $this->form_validation->set_rules('name', 'Name', 'trim|required');
            $this->form_validation->set_rules('promo_id', 'Promotion', 'trim|required');
            $this->form_validation->set_rules('code', 'Code', 'trim|required|min_length[4]|is_unique[general_promos.code]');
            
            if($this->form_validation->run() == TRUE)
	    {
                $post = $this->input->post();
                $post['created'] = date('Y-m-d H:i:s');
                $this->promos->addGeneral($post);
                
                $this->session->set_flashdata('message','General Promotion Added Successfully');
                redirect('promos/general');
            }
        }
        
        $this->load->view('promos/add_general',$this->data);
    }
    
    
    public function genRemove($id)
    {
        $this->promos->deleteGen($id);
        $this->session->set_flashdata('message','General Promotion Removed Successfully');
        redirect("promos/general");
    }
   
    
    public function test()
    {
        echo getToken(6);
//        $promoXML = new SimpleXMLElement("<promo></promo>");
//        $offer = $promoXML->addChild('offer');
//        $offer->addAttribute('type', 'percentage');
//        $offer->addAttribute('value', '50');
//        echo $promoXML->asXML();
    }
    
    public function winlist($promo_id)
    {
        $this->data['bc1'] = "Promos";
        $this->data['bc2'] = "Win List";
        
        $this->data['patients'] = $this->promos->getPromoWinList($promo_id);
        $this->load->view('promos/win_list',$this->data);
    }
    
    public function claimed($id,$promo_id)
    {
        $this->load->model('Ext_model','ext');
        $this->ext->updateClaimed($id);
        $this->session->set_flashdata('message',"Promotion claimed successufully.");
        redirect("promos/winlist/$promo_id");
    }
    
    public function queueMsg()
    {
        $this->data['bc1'] = "Queue";
        $this->data['bc2'] = "Alert Messages";
        
        $this->data['qms'] = $this->queue->getQueueMsgs();
        $this->load->view('queue/queue_msg',$this->data);
    }
    
    public function addQueueMsg()
    {
        $this->form_validation->set_rules('start', 'Start', 'trim|required');
        $this->form_validation->set_rules('end', 'End', 'trim|required');
        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        $this->form_validation->set_rules('msg', 'Message', 'trim|required');
        
        $result = array();
        if($this->form_validation->run() == TRUE)
	{
            $uploaded = null;
            
            if (!empty($_FILES['photo']['name']))$uploaded = $this->upload();
            
            if($uploaded && isset($uploaded['error']))
            {
                $result['status'] = 'error';
                $result['errors'] = $uploaded['error'];
            }
            else 
            {
                $post = $this->input->post();
                $post['start'] = date('Y-m-d', strtotime($post['start']));
                $post['end'] = date('Y-m-d', strtotime($post['end']));
                $post['created'] = date('Y-m-d H:i:s');
                if($uploaded && isset($uploaded['img'])) $post['photo'] = $uploaded['img'];
                $this->queue->addQueueMsg($post);
                $result['status'] = 'success';
                $this->data['qms'] = $this->queue->getQueueMsgs();
                $result['msgs'] = $this->load->view('queue/_queue_msg_tbl',$this->data,TRUE);
            }
        }
        else
        {
            $result['status'] = 'error';
            $result['errors'] = validation_errors('<div class="col-xs-12" style="padding:0 5px;"><div style="padding:5px;" role="alert" class="alert fresh-color alert-danger"><strong>','</strong></div></div>');
            
        }
        echo json_encode($result);
    }
    
    public function upload()
    {	
	// Upload photo
	$config['upload_path'] = './assets/upload/queue_alerts/';;
	$config['allowed_types'] = 'gif|jpg|png|jpeg';
	$config['encrypt_name'] = TRUE;
	$data = array();
	$this->load->library('upload', $config);

	if (!$this->upload->do_upload('photo'))
	{
	    $data['error'] = $this->upload->display_errors('<div class="col-xs-12" style="padding:0 5px;"><div style="padding:5px;" role="alert" class="alert fresh-color alert-danger"><strong>','</strong></div></div>');
	}
	else
	{
	    $upload_data = $this->upload->data();
	    $data['img'] = $upload_data['file_name'];
	}

	return $data;
    }
    
    public function remQM($id)
    {
        $qm = $this->queue->getQueueMsg($id);
        if($qm->photo && file_exists("./assets/upload/queue_alerts/$qm->photo"))unlink("./assets/upload/queue_alerts/$qm->photo");
        
        $this->queue->deleteQm($id);
        $result['status'] = 'success';
        $this->data['qms'] = $this->queue->getQueueMsgs();
        $result['msgs'] = $this->load->view('queue/_queue_msg_tbl',$this->data,TRUE);
        echo json_encode($result);
    }
    
    public function updateQueueMsg($id)
    {
        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        $this->form_validation->set_rules('msg', 'Message', 'trim|required');
        
        $result = array();
        if($this->form_validation->run() == TRUE)
	{
            $uploaded = null;
            
            if (!empty($_FILES['photo']['name']))$uploaded = $this->upload();
            
            if($uploaded && isset($uploaded['error']))
            {
                $result['status'] = 'error';
                $result['errors'] = $uploaded['error'];
            }
            else 
            {
                $post = $this->input->post();            
                $post['created'] = date('Y-m-d H:i:s');
                
                $qm = $this->queue->getQueueMsg($id);
                
                if($uploaded && isset($uploaded['img']))
                {
                    $post['photo'] = $uploaded['img'];
                    if($qm->photo && file_exists("./assets/upload/queue_alerts/$qm->photo"))unlink("./assets/upload/queue_alerts/$qm->photo");
                }
                
                if($post['rem_photo'] == 1)
                {
                    if($qm->photo && file_exists("./assets/upload/queue_alerts/$qm->photo"))unlink("./assets/upload/queue_alerts/$qm->photo");
                }
                
                unset($post['rem_photo']);
                
                $this->queue->updateQueueMsg($id,$post);
                $result['status'] = 'success';
                $this->data['qms'] = $this->queue->getQueueMsgs();
                $result['msgs'] = $this->load->view('queue/_queue_msg_tbl',$this->data,TRUE);
            }
        }
        else
        {
            $result['status'] = 'error';
            $result['errors'] = validation_errors('<div class="col-xs-12" style="padding:0 5px;"><div style="padding:5px;" role="alert" class="alert fresh-color alert-danger"><strong>','</strong></div></div>');
            
        }
        echo json_encode($result);
    }
 
}
