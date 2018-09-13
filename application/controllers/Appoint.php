<?php
/**
 * Description of Appoint
 *
 * @author Udana
 */
class Appoint extends Admin_Controller
{
    public function __construct() {
        parent::__construct();
        
        $this->load->model('Location_model','location');
        $this->load->model('User_model','user');
        $this->load->model('Appoint_model','appoint');
        $this->load->model('Evaluate_model','evaluate');
    }
    public function index()
    {
        $this->data['bc1'] = 'Appointments';
	$this->data['bc2'] = 'View';
        $this->data['locations'] = $this->location->getAll(TRUE);
        $this->data['staff'] = $this->user->getUserByType(array(2,4));
        
        $apploc = $this->session->userdata('apploc')? $this->session->userdata('apploc'): 0;
        $appdate = $this->session->userdata('appdate')? $this->session->userdata('appdate'): date('Y-m-d');
        $this->data['apploc'] = $apploc;
        $this->data['appdate'] = $appdate;
        $appoints = $this->processApps($apploc,$appdate);
        $this->data['appoints'] = $appoints['appoints'];
        $this->data['appcount'] = $appoints['appcount'];
        $this->data['blocks'] = $this->processBlock($apploc, $appdate);
        $this->load->view('appoint/index',$this->data);
    }
    
    public function processApps($apploc,$appdate)
    {
        $appoints = $this->appoint->getAll($apploc,$appdate);
        $apps = array();
        
        foreach($appoints as $app)
        {            
            if(isset($apps[$app->location_id][date('G',strtotime($app->time))][intval(date('i',strtotime($app->time)))]))
            {
                $appArr = $apps[$app->location_id][date('G',strtotime($app->time))][intval(date('i',strtotime($app->time)))];
                $appArr[count($appArr)+1] = $app;
                $apps[$app->location_id][date('G',strtotime($app->time))][intval(date('i',strtotime($app->time)))] = $appArr;
            }
            else
            {
                $apps[$app->location_id][date('G',strtotime($app->time))][intval(date('i',strtotime($app->time)))][1] = $app;
            }
        }
        return array('appoints'=>$apps,'appcount'=> count($appoints));
    }
    
    public function processBlock($apploc,$appdate)
    {
        $blocks = $this->appoint->getBlocks($apploc,$appdate);
        $blks = array();
        foreach($blocks as $blk)
        {
            $first = TRUE;
            $start = new DateTime("$appdate $blk->start");
            $end = new DateTime("$appdate $blk->end");
            $current = clone $start;
            while ($current < $end) {
                
                if($first)
                {
                    $blk->first = TRUE;
                    $first = FALSE;
                }
                else 
                {
                    $blk->first = FALSE;
                }
                $blks[$blk->location_id][$current->format("G")][intval($current->format("i"))] = clone $blk;
                $current->modify("+15 minutes");
            }
           
        }
        
        return $blks;
    }


    public function addAppoint()
    {
       
        $this->form_validation->set_rules('location_id', 'Location', 'trim|required');
        $this->form_validation->set_rules('patient_id', 'Patient', 'trim|required',array('required'=>"Select patient from suggession list."));
        $this->form_validation->set_rules('date', 'Date', 'trim|required');
        $this->form_validation->set_rules('time', 'Time', 'trim|required');
        
        $result = array();
        if($this->form_validation->run() == TRUE)
	{
	    $post = $this->input->post();
            
            $post['date'] = date('Y-m-d',strtotime($post['date']));
            $post['time'] = date('H:i:s',strtotime($post['time']));
            
            $type_new = ($post['type'] == 1) ? TRUE:FALSE;
            
            if($this->appoint->isBlocked($post['date'],$post['location_id'],$post['time'],$type_new))
            {
                $result['status'] = 'error';
                $result['errors'] = '<div class="col-xs-12" style="padding:0 5px;"><div style="padding:5px;" role="alert" class="alert fresh-color alert-danger"><strong>This appointment time has blocked.</strong></div></div>';        
                 echo json_encode($result);
            }
            else 
            {
                $post['created'] = date('Y-m-d H:i:s');
                $this->appoint->addAppoint($post);

                $this->session->set_userdata('appdate',$post['date']);
                $this->calView();
            }
            
        }
        else
        {
            $result['status'] = 'error';
            $result['errors'] = validation_errors('<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" style="padding:0 5px;"><div style="padding:5px;" role="alert" class="alert fresh-color alert-danger"><strong>','</strong></div></div>');
             echo json_encode($result);
        }       
       
    }
    
    public function addNpAppoint()
    {
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('phn', 'Phone', 'trim');
        $this->form_validation->set_rules('location_id', 'Location', 'trim|required');
        $this->form_validation->set_rules('date', 'Date', 'trim|required');
        $this->form_validation->set_rules('time', 'Time', 'trim|required');
        
        $result = array();
        if($this->form_validation->run() == TRUE)
	{
	    $post = $this->input->post();
            
            $post['phn'] = str_replace(array(' ', '(', ')', '-'), '', $post['phn']);
            $post['date'] = date('Y-m-d',strtotime($post['date']));
            $post['time'] = date('H:i:s',strtotime($post['time']));
            $post['created'] = date('Y-m-d H:i:s');
            
            if($this->appoint->isBlocked($post['date'],$post['location_id'],$post['time'],TRUE))
            {
                $result['status'] = 'error';
                $result['errors'] = '<div class="col-xs-12" style="padding:0 5px;"><div style="padding:5px;" role="alert" class="alert fresh-color alert-danger"><strong>This appointment time has blocked.</strong></div></div>';        
                 echo json_encode($result);
            }
            else 
            {
                $this->appoint->addAppoint($post);                
                $this->session->set_userdata('appdate',$post['date']);
                
                $location = getLocation($post['location_id']);
                
                $msg = $post['first_name'].", a reminder from Doctor's Weight Loss for your new patient visit on ".date("l, F d, Y",strtotime($post['date']))." ".date("g:ia",strtotime($post['time']))." EDT @($location->name). Please note: New Patient Initial Visit takes about 2 hrs. Take all your prescription meds prior to visit. Do not apply any body oils or lotions as that might hinder the EKG process. To reschedule (727-412-8208) \nType C to confirm or P to cancel.\nType STOP to stop these Msgs.\nMsg & Data rates may apply.";
                SendSMS($_POST['phn'], $msg); 
                
                $this->calView();
            }
        }
        else
        {
            $result['status'] = 'error';
            $result['errors'] = validation_errors('<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" style="padding:0 5px;"><div style="padding:5px;" role="alert" class="alert fresh-color alert-danger"><strong>','</strong></div></div>');
             echo json_encode($result);
        }       
    }
    
    public function addAppointPatient()
    {
       
        $this->form_validation->set_rules('location_id', 'Location', 'trim|required');
        $this->form_validation->set_rules('patient_id', 'Patient', 'trim|required',array('required'=>"Select patient from suggession list."));
        $this->form_validation->set_rules('date', 'Date', 'trim|required');
        $this->form_validation->set_rules('time', 'Time', 'trim|required');
        
        $result = array();
        if($this->form_validation->run() == TRUE)
	{
	    $post = $this->input->post();
            
            $post['date'] = date('Y-m-d',strtotime($post['date']));
            $post['time'] = date('H:i:s',strtotime($post['time']));
            
            
            if($this->appoint->isBlocked($post['date'],$post['location_id'],$post['time']))
            {
                $result['status'] = 'error';
                $result['errors'] = '<div class="col-xs-12" style="padding:0 5px;"><div style="padding:5px;" role="alert" class="alert fresh-color alert-danger"><strong>This appointment time has blocked.</strong></div></div>';        
                
            }
            else 
            {
                $post['created'] = date('Y-m-d H:i:s');
                $this->appoint->addAppoint($post);
                
                $result['status'] = 'success';
                $result['msg'] = '<div class="col-xs-12" style="padding:0 5px;"><div style="padding:5px;" role="alert" class="alert fresh-color alert-success"><strong>Appointment added successfully.</strong></div></div>';        
                
            }
            
        }
        else
        {
            $result['status'] = 'error';
            $result['errors'] = validation_errors('<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" style="padding:0 5px;"><div style="padding:5px;" role="alert" class="alert fresh-color alert-danger"><strong>','</strong></div></div>');
            
        }       
        echo json_encode($result);
    }
    
    public function isInBreak($patientId,$day)
    {
        $phase = $this->evaluate->getLastPhase($patientId);
        if($phase && $phase->phase == 3)
        {
            if($phase->end)
            {
                return $phase->end < $day ? FALSE:TRUE;
            }
            elseif(date('Y-m-d',strtotime("+4 weeks", strtotime($phase->start))) < $day)
            {
                return FALSE;
            }
            else
            {
                return TRUE;
            }
        }
        else
        {
            return FALSE;
        }
    }
    
    public function delAppoint()
    {
        $post = $this->input->post();
        $this->appoint->delAppoint($post['id']);
        $this->data['appoints'] = $this->appoint->getAll();
        $result = array();
        $result['html'] = $this->load->view('appoint/_table_partial',$this->data,true);
        $result['status'] = 'success';
        echo json_encode($result);
    }
    
    public function delAppCal()
    {
        $post = $this->input->post();
        $this->appoint->delAppoint($post['id']);
        $this->calView();
    }
    
    public function tableView()
    {
        $this->data['appoints'] = $this->appoint->getAll();
        $result = array();
        $result['html'] = $this->load->view('appoint/_table_partial',$this->data,true);
        $result['status'] = 'success';
        echo json_encode($result);
    }
    
    public function calView()
    {
        $this->data['locations'] = $this->location->getAll(TRUE);
        
        $apploc = $this->session->userdata('apploc')? $this->session->userdata('apploc'): 0;
        $appdate = $this->session->userdata('appdate')? $this->session->userdata('appdate'): date('Y-m-d');
        $this->data['apploc'] = $apploc;
        $this->data['appdate'] = $appdate;
        $appoints = $this->processApps($apploc,$appdate);
        $this->data['appoints'] = $appoints['appoints'];
        $this->data['appcount'] = $appoints['appcount'];
        $this->data['blocks'] = $this->processBlock($apploc, $appdate);
        
        $result = array();
        $result['html'] = $this->load->view('appoint/_cal_partial',$this->data,true);
        $result['status'] = 'success';
        echo json_encode($result);        
    }
    
    public function removeBlock()
    {
        $post = $this->input->post();
        $this->appoint->delBlock($post['id']);
        $apploc = $this->session->userdata('apploc')? $this->session->userdata('apploc'): 0;
        $appdate = $this->session->userdata('appdate')? $this->session->userdata('appdate'): date('Y-m-d');
        $this->data['apploc'] = $apploc;
        $this->data['appdate'] = $appdate;
        $this->data['locations'] = $this->location->getAll(TRUE);
        $this->data['blocks'] = $this->processBlock($apploc, $appdate);
        $appoints = $this->processApps($apploc,$appdate);
        $this->data['appoints'] = $appoints['appoints'];
        $this->data['appcount'] = $appoints['appcount'];
        $result = array();
        $result['html'] = $this->load->view('appoint/_cal_partial',$this->data,true);
        $result['status'] = 'success';
        echo json_encode($result);
    }
    
    public function changeDate()
    {
        $post = $this->input->post();
        $post['date'] = date('Y-m-d',strtotime($post['date']));
        $this->session->set_userdata('appdate',$post['date']);
        
        $this->calView();
    }
    
    public function changeLocation()
    {
        $post = $this->input->post();
        $this->session->set_userdata('apploc',$post['loc_id']);
        
        $this->calView();
    }
    
    public function addBlock()
    {
        $post = $this->input->post();
        $post['created'] = date("Y-m-d H:i:s");
        $post['date'] = date('Y-m-d',strtotime($post['date']));
        $result = array();
        $post['start'] = date('H:i:s',strtotime($post['start']));
        $post['end'] = date('H:i:s',strtotime($post['end']));
        
        
        
        if($this->appoint->hasAppoints($post['date'],$post['location_id'],$post['start'],$post['end'],$post['type']))
        {
            $result['status'] = 'error';
            $result['errors'] = '<div class="col-xs-12" style="padding:0 5px;"><div style="padding:5px;" role="alert" class="alert fresh-color alert-danger"><strong>There are already appointments in this slot.</strong></div></div>';
        }
        elseif($this->appoint->hasBlock($post['date'],$post['location_id'],$post['start'],$post['end']))
        {
            $result['status'] = 'error';
            $result['errors'] = '<div class="col-xs-12" style="padding:0 5px;"><div style="padding:5px;" role="alert" class="alert fresh-color alert-danger"><strong>Overlapping with existing block slot.</strong></div></div>';
        
        }
        elseif($post['start'] >= $post['end'])
        {
            $result['status'] = 'error';
            $result['errors'] = '<div class="col-xs-12" style="padding:0 5px;"><div style="padding:5px;" role="alert" class="alert fresh-color alert-danger"><strong>Please correct start and end time </strong></div></div>';
        
        }
        else 
        {
            $this->appoint->addBlock($post);
            $this->session->set_userdata('appdate',$post['date']);

            $apploc = $this->session->userdata('apploc')? $this->session->userdata('apploc'): 0;
            $appdate = $post['date'];
            $this->data['apploc'] = $apploc;
            $this->data['appdate'] = $appdate;
            $this->data['locations'] = $this->location->getAll(TRUE);
            $this->data['blocks'] = $this->processBlock($apploc, $appdate);
            $appoints = $this->processApps($apploc,$appdate);
            $this->data['appoints'] = $appoints['appoints'];
            $this->data['appcount'] = $appoints['appcount'];

            $result['html'] = $this->load->view('appoint/_cal_partial',$this->data,true);
            $result['status'] = 'success';
        }
        echo json_encode($result);
    }
    
    public function updateAppoint()
    {
        $post = $this->input->post();
        $post['date'] = date('Y-m-d',strtotime($post['date']));
        $post['time'] = date('H:i:s',strtotime($post['time']));
        
        $type_new = ($post['type'] == 1) ? TRUE:FALSE;
            
        if($this->appoint->isBlocked($post['date'],$post['location_id'],$post['time'],$type_new))
        {
            $result['status'] = 'error';
            $result['errors'] = '<div class="col-xs-12" style="padding:0 5px;"><div style="padding:5px;" role="alert" class="alert fresh-color alert-danger"><strong>This appointment time has blocked.</strong></div></div>';        
            echo json_encode($result);
        }
        else
        {
            $id = $post['id'];
            unset($post['id']);
            $this->appoint->update($id,$post);
            $this->calView(); 
        }
    }
    
    public function getNextVisitDate()
    {
        $post = $this->input->post();
        $res = getNextVisitDate($post['id']);
        
        if($res['status'] == 'success')
        {
            echo ($res['ed'] < 7)? "Next Visit Date ".date('m/d/Y',strtotime($res['rnv'])): "Based on last 4 visits, your next visit should be on: ".date('m/d/Y',strtotime($res['nvd']));
        }
        else
        {
            echo $res['msg'];
        }
       
    }
    
    public function setNoShow()
    {
        $post = $this->input->post();
        $data = array('no_show'=>$post['ns']);
        $this->appoint->update($post['id'],$data);
        $this->calView();
    }
    
    public function getHistory($patient_id)
    {
        $patient = $this->patient->getPatient($patient_id);
        $history = $this->appoint->getHistory($patient_id);
        
        $this->data['history'] = $history;
        
        $result = array();
        $result['pname'] = $patient->lname." ".$patient->fname;
        $result['table'] = $this->load->view("appoint/_history_partial",$this->data,TRUE);
        
        echo json_encode($result);
    }
    
    public function app_print($loc_id,$date)
    {
        $appoints = $this->appoint->getAll($loc_id,$date);
        $data = array();
        $data['appoints'] = $appoints;
        $data['loc_id'] = $loc_id;
        $data['date'] = $date;
        
        $html = $this->load->view('appoint/_print',  $data,TRUE);
        
        create_mp_ticket($html);
    }
    
    public function freeB12()
    {
       
        $this->data['bc1'] = 'Mails';
	$this->data['bc2'] = 'Free B-12 Promotion';
        $this->load->model("Mail_model", "mail");
        
        if($this->input->server('REQUEST_METHOD') === 'POST')
    	{  
            $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|required');
            
            if($this->form_validation->run() == TRUE)
            {
                $post = $this->input->post();
                $to = $post['email'];
                $subject = "Start building your perfect body and grab a free B-12";
                $message = $this->load->view("emails/free_b12",$this->data,TRUE);
                $this->mail->send_mail($to,$subject,$message);
                $this->session->set_flashdata('message','Mail sent successfully');
            }
        }
        
        $this->load->view("appoint/freeb12",$this->data);
    }
    
    public function isPatientExist()
    {
        $post = $this->input->post();
        $phone = str_replace(array(' ', '(', ')', '-'), '', $post['phone']);
        
        $patient = $this->appoint->getByNames($post['fname'],$post['lname'],$phone);
       
        $result = array();
        $result['status'] = 'no';
        
        if($patient)
        {
            $result['status'] = 'exist';
            $result['msg'] = '<div class="col-xs-12" style="padding:0 5px;"><div style="padding:5px;" role="alert" class="alert fresh-color alert-danger"><strong>Patient with same name or phone # already exist.</strong> [ First Name:&nbsp;'.$patient->fname.',&nbsp;&nbsp; Last Name:&nbsp;'.$patient->lname.',&nbsp;&nbsp; Phone:&nbsp;'. getPhnFormat($patient->phone).' ] Click <a target="_blank" style="color: yellow;" href="'.site_url("patient/view/$patient->id").'">here</a> to view profile.</div></div>';
        }
        
        echo json_encode($result);
    }
    
    public function patient()
    {
        $this->data['bc1'] = 'Appts';
	$this->data['bc2'] = 'Patient';
        
        $this->data['locations'] = $this->location->getAll(TRUE);
        $this->data['staff'] = $this->user->getUserByType(array(2,4));
        
        $this->load->view('appoint/patient',$this->data);
    }
    
    public function patientApptTable()
    {
        $post = $this->input->post();
        
        $patient = $this->patient->getPatient($post['patient_id']);
        $history = $this->appoint->getHistory($post['patient_id']);
        
        $this->data['appoints'] = $history;
        $this->data['patient'] = $patient;
        $this->data['types'] = array(1 => 'New',2=>'Weekly',3=>'Dr Consult',4=>'Shots only');
        
        $result = array();
        $result['pname'] = $patient->lname." ".$patient->fname;
        $result['table'] = $this->load->view("appoint/_patient_table",$this->data,TRUE);
        
        echo json_encode($result);
    }
    
    public function delPatientAppt()
    {
        $post = $this->input->post();
        $this->appoint->delAppoint($post['id']);
        
        $patient = $this->patient->getPatient($post['patient_id']);
        $history = $this->appoint->getHistory($post['patient_id']);
        
        $this->data['appoints'] = $history;
        $this->data['patient'] = $patient;
        $this->data['types'] = array(1 => 'New',2=>'Weekly',3=>'Dr Consult',4=>'Shots only');
        
        $result = array();
        $result['pname'] = $patient->lname." ".$patient->fname;
        $result['table'] = $this->load->view("appoint/_patient_table",$this->data,TRUE);
        
        echo json_encode($result);
    }
}
