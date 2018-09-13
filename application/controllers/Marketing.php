<?php
/**
 * Description of Marketing
 *
 * @author Udana
 */
class Marketing extends Admin_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('marketing_model','marketing');
        $this->load->model('Promos_model','promos');
        $this->load->model("Mail_model", "mail");
    }
    public function customList()
    {
        $this->data['bc1'] = "Marketing";
        $this->data['bc2'] = "Custom Lists";
        $this->data['cls'] = $this->marketing->getCustomLists();
        $this->load->view('marketing/customLists',$this->data);
    }
    
    public function manageCL($id = null)
    {
        
        $this->data['bc1'] = "Custom List";
        $this->data['bc2'] = "Manage";
        
        $members = array();
        
        if($this->input->server('REQUEST_METHOD') === 'POST')
    	{ 
            $this->form_validation->set_rules('name', 'List Name', 'trim|required');
            
            if($this->form_validation->run() == TRUE)
	    {
                $post = $this->input->post();
                
                if(!$id)
                {
                    $post['created'] = date('Y-m-d');
                    $id = $this->marketing->addCL($post);
                    
                    $this->session->set_flashdata('message','Custom List added Successfully');
                }
                else
                {
                    $this->db->update($id,$post);
                    $this->session->set_flashdata('message','Custom List Name Updated Successfully');
                }
                redirect("marketing/manageCL/$id");
            }
        }
        
        if($id)
        {
            $this->data['list'] = $this->marketing->getCL($id);
            $members = $this->marketing->getCLMems($id);
        }
        $this->data['id'] = $id;
        $this->data['members'] = $members;
        $this->data['promos'] = $this->promos->getAll();
        $this->data['tmpls'] = $this->mail->getTemplates();
        
        $this->load->view('marketing/manage_cl',$this->data);
    }
    
    public function deleteCL($id)
    {
        $this->marketing->delCL($id);
        $this->session->set_flashdata('message','Custom List Removed Successfully');
        redirect("marketing/customList");
    }
    
    public function addListMember()
    {
        $this->form_validation->set_rules('patient_id', 'Patient', 'trim|required',array('required'=>"Select patient from suggession list."));
        
        $result = array();
        if($this->form_validation->run() == TRUE)
	{
	    $post = $this->input->post();
            $list_id = $post['list_id'];
            $patient_id = $post['patient_id'];
            
            if($this->marketing->isInCList($list_id,$patient_id))
            {
                $result['status'] = 'error';
                $result['errors'] = '<div class="col-xs-12" style="padding:0 5px;"><div style="padding:5px;" role="alert" class="alert fresh-color alert-danger"><strong>Patient already in the list.</strong></div></div>';                        
            }
            else 
            {
                $data = array('cl_id'=>$list_id,'patient_id'=>$patient_id);
                $this->marketing->addMemToCL($data);
                $result['status'] = 'success';
                $this->data['members'] = $this->marketing->getCLMems($list_id);
                $this->data['id'] = $list_id;
                $html  = $this->load->view('marketing/_cl_manage_table',$this->data,true);
                $result['html'] = $html;
            }
        }
        else
        {
            $result['status'] = 'error';
            $result['errors'] = validation_errors('<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" style="padding:0 5px;"><div style="padding:5px;" role="alert" class="alert fresh-color alert-danger"><strong>','</strong></div></div>');             
        } 
        echo json_encode($result);
    }
    
    
    
    public function removeCLMem()
    {
        $post = $this->input->post();
        $this->marketing->remCLMem($post['id']);
        $this->data['id'] = $post['list'];
        $result = array();
        $result['status'] = 'success';
        $this->data['members'] = $this->marketing->getCLMems($post['list']);
                
        $html  = $this->load->view('marketing/_cl_manage_table',$this->data,true);
        $result['html'] = $html;
        
        echo json_encode($result);
    }
    
    public function createMCList()
    {
        $result = array();
        if($this->input->server('REQUEST_METHOD') === 'POST')
    	{  
            $this->form_validation->set_rules('name', 'List Name', 'trim|required');
            
            if($this->form_validation->run() == TRUE)
	    {
                $post = $this->input->post();
                $pds = $this->marketing->getCLMems($post['id']);
                
                if(!empty($pds))
                {
                    $this->load->helper('mc');
        
                    $data = array();

                    $data['name'] = $post['name'];

                    $contact = array();
                    $contact['company'] = "DWLC";
                    $contact['address1'] = '3395 East Bay Drive';
                    $contact['city'] = 'Largo';
                    $contact['state'] = 'Florida';
                    $contact['zip'] = '33771';
                    $contact['country'] = 'USA';

                    $data['contact'] = $contact;
                    $data['permission_reminder'] = "You're receiving this email because you were enrolled with Doctors weight loss center.";
                    $data['email_type_option'] = true;

                    $cd = array();
                    $cd['from_name'] = "DWLC";
                    $cd['from_email'] = "doctorsweightloss@gmail.com";
                    $cd['subject'] = "";
                    $cd['language'] = "EN";
                    $data['campaign_defaults'] = $cd;
                    $list = createList($data);
                    $list_id = $list['id'];
                    $path = "lists/$list_id/members";
                    
                    $batch = getMCBatchObj();
                    
                    foreach($pds as $pd)
                    {
                        if(empty($pd->email)) continue;
                        
                        $temp = array();
                        
                        $temp['email_address'] = $pd->email;
                        $temp['status'] = 'subscribed';
                        $temp['merge_fields'] = array('FNAME'=>$pd->fname,'LNAME'=>$pd->lname);
                        
//                        $res = addMemToList($list_id,$temp);
                        
                        
                        $batch->post($pd->patient_id,$path,$temp);
                    }
                    
                    $batch->execute(60);
                    
                    $result['status'] = 'success';
                    $result['msg'] ='<div class="col-xs-12" style="padding:0 5px;margin:10px 0px 0px 0px;"><div style="padding:5px;margin:0px;" role="alert" class="alert fresh-color alert-success"><strong>Members list created in Mailchimp successfully.</strong></div></div>';                            
                }
                else 
                {
                    $result['status'] = 'error';
                    $result['errors'] ='<div class="col-xs-12" style="padding:0 5px;margin:10px 0px 0px 0px;"><div style="padding:5px;margin:0px;" role="alert" class="alert fresh-color alert-danger"><strong>No Past Due Patients Found.</strong></div></div>';            
                }
            }
            else 
            {
                $result['status'] = 'error';
                $result['errors'] = validation_errors('<div class="col-xs-12" style="padding:0 5px;margin:10px 0px 0px 0px;"><div style="padding:5px;margin:0px;" role="alert" class="alert fresh-color alert-danger"><strong>','</strong></div></div>');
            }
        }
        echo json_encode($result);
    }
    
    public function sendPromoMails()
    {
        $result = array();
        if($this->input->server('REQUEST_METHOD') === 'POST')
    	{ 
            $this->form_validation->set_rules('promo_id', 'Promotion', 'trim|required');
            $this->form_validation->set_rules('tmpl_id', 'Template', 'trim|required');
            $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
            $this->form_validation->set_rules('promo_date', 'Delivery Date', 'trim|required');
            $this->form_validation->set_rules('promo_time', 'Delivery Time', 'trim|required');
            
            if($this->form_validation->run() == TRUE)
	    {
                $post = $this->input->post();
                
                $list_id = $post['list_id'];
                $mems = $this->marketing->getCLMems($list_id);
                $template = $this->mail->getTemplate($post['tmpl_id']);
                $html = $this->load->view($template->file,$this->data,true);
                $search = array('[FNAME]','[COUPON]');
                
                foreach($mems as $mem)
                {
                    $coup = array();
                    $coup['promo_id'] = $post['promo_id'];
                    $coup['patient_id'] = $mem->patient_id;
                    $coup['tmpl_id'] = $post['tmpl_id'];
                    $coup['coupon'] = getPromoCoupon();
                    $coup['created'] = date('Y-m-d H:i:s');
                    
                    $this->marketing->addCoupon($coup);
                    
                    $replace = array($mem->fname,$coup['coupon']);
                    $content = str_replace($search, $replace, $html);
                    
                    $queue = array();
                    $queue['patient_id'] = $mem->patient_id;
                    $queue['subject'] = $post['subject'];
                    $queue['deliver_after'] = $post['promo_date']." ".$post['promo_time'].":00";
                    $queue['content'] = $content;
                    $queue['created'] = date('Y-m-d H:i:s');
                    
                    $this->mail->add_mail($queue);
                }
                
                
                $result['status'] = 'success';
                $result['msg'] ='<div class="col-xs-12" style="padding:0 5px;margin:10px 0px 0px 0px;"><div style="padding:5px;margin:0px;" role="alert" class="alert fresh-color alert-success"><strong>Promotion Mails added to mail queue.</strong></div></div>';                            
                
            }
            else 
            {
                $result['status'] = 'error';
                $result['errors'] = validation_errors('<div class="col-xs-12" style="padding:0 5px;margin:10px 0px 0px 0px;"><div style="padding:5px;margin:0px;" role="alert" class="alert fresh-color alert-danger"><strong>','</strong></div></div>');
            }
        }
        echo json_encode($result);
    }
    
    public function coupons()
    {
        $this->data['bc1'] = "Marketing";
        $this->data['bc2'] = "Coupons";
        
        $this->data['coupons'] = $this->marketing->getCoupons();
        
        $this->load->view('marketing/coupons',$this->data);
    }
}
