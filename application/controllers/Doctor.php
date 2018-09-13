<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Doctor
 *
 * @author Udana
 */
class Doctor extends Dr_controller
{
    public function __construct()
    {
	parent::__construct();
	
	$this->load->model('user_model','user');
	$this->load->model('Location_model','location');
	$this->load->model("Mail_model", "mail");
    }
    
    public function index()
    {
//        $this->data['bc1'] = 'Dr';
//	$this->data['bc2'] = 'Patient Search';
//        $patients = array();
//        if($this->input->server('REQUEST_METHOD') === 'POST')
//    	{ 
//            $post = $this->input->post();
//            $query = explode(" ", trim($post['query']));
//            $query=array_map('trim',$query);
//            $query = array_filter($query);
//            
//            if(count($query)>0)
//            {
//                $patients = $this->patient->search($query);
//                foreach($patients as &$patient)
//                {
//                    $patient->last_visit = $this->patient->getLatestVisit($patient->id);
//                    $patient->ecgs = $this->patient->getAllECG($patient->id);
//                    $patient->bws = $this->patient->getAllBW($patient->id);
//                }
//            }
//        }
//        
//        
//        
//        $this->data['patients'] = $patients;
//        $this->load->view('doctor/index',$this->data);
        
        
        $this->data['bc1'] = 'Patients';
	$this->data['bc2'] = 'Search';
        $this->data['locations'] = $this->location->getAll(TRUE);
        $this->data['staff'] = $this->user->getUserByType(array(2,4));
        $this->data['pps'] = $this->product->getPpPros();
        $this->load->view('doctor/search',$this->data);
    }
    
    public function dosearch()
    {
        $post = $this->input->post();
        $phase = $post['phase'];
        $phase = preg_replace('/\s+/S', " ", $phase);
        $phase = trim($phase);
        
        $result = array();
        if(empty($phase))
        {
            $result['status'] = 'error';
            $result['msg'] = "Please insert a name";
        }
        else
        {
            $this->data['patients'] = $this->patient->patientSearch($phase);
            
            $result['table'] = $this->load->view('doctor/_search',$this->data,TRUE);
        }
        
        echo json_encode($result);
    }
    
    public function addAlert()
    {
        $this->form_validation->set_rules('msg', 'Message', 'trim|required');
        $result = array();
        if($this->form_validation->run() == TRUE)
	{
	    $post = $this->input->post();
            
            $result['status'] = 'success';
            $post['created'] = date('Y-m-d');
            $post['doctor'] = 1;
            $this->patient->addAlert($post);
            $result['msg'] = '<div class="col-xs-12 no-padding" ><div style="padding:5px;" role="alert" class="alert fresh-color alert-success"><strong>Alert added successfully.</strong></div></div>';        
        }
        else
        {
            $result['status'] = 'error';
            $result['errors'] = validation_errors('<div class="col-xs-12 no-padding" ><div style="padding:5px;" role="alert" class="alert fresh-color alert-danger"><strong>','</strong></div></div>');
            
        }
        echo json_encode($result);
    }
    
    public function patient($id)
    {
        $this->data['bc1'] = 'Dr';
	$this->data['bc2'] = 'Patient View';
        
        $patient = $this->patient->getPatient($id);
        $this->data['patient'] = $patient;
        
        //First visit
        $this->data['first_visit'] = $this->patient->getPatientVisit($id,1);
        
        $visits = $this->patient->getPatientVisits($id);
        $this->data['visits'] = $visits;
        
        $this->load->view('doctor/patient',$this->data);
    }
    
    public function login()
    {
	if($this->input->server('REQUEST_METHOD') === 'POST')
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
		$user = $this->user->getUserByUsername($post['username']);
		
		if($user && $user->password == $post['password'])
		{      
		    
			$result['status'] = 'success';
			$this->session->set_userdata('user',$user);
			$this->session->set_userdata('dr_logged',TRUE);
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
	    
	   
	    $this->load->view('doctor/login',$this->data);
	}
    }
    
    public function note($visit_id)
    {
        $this->data['bc1'] = 'Dr';
	$this->data['bc2'] = 'Note';
        
        $visit = $this->patient->getVisitById($visit_id);
        
        if($this->input->server('REQUEST_METHOD') === 'POST')
    	{
            $this->form_validation->set_rules('assessment_and_plan', 'Assessment and Plan', 'trim');
            $this->form_validation->set_rules('meds_ok_to_continue', 'Meds ok to continue', 'trim');
            $this->form_validation->set_rules('alerts', 'Alerts', 'trim|required');
            $this->form_validation->set_rules('other_instructions', 'Other Instructions', 'trim');
            
            if($this->form_validation->run() == TRUE)
	    {
                $post = $this->input->post();
                
//                print_r($post);
//                die();
                                
                if(isset($post['change_meds']) && isset($post['change_meds_options']))
                {
                    $post['change_meds'] = implode(',', $post['change_meds_options']);
                    
                }
                else 
                {
                    $post['change_meds'] = NULL;
                }
                
                if(isset($post['change_meds_status']) && isset($post['change_meds_status_options']))
                {
                    $post['change_meds_status'] = $post['change_meds_status_options'];
                }
                else
                {
                    $post['change_meds_status'] = 0;
                }
                
                if(isset($post['wld']) && isset($post['wld_options']))
                {
                    $post['wld'] = $post['wld_options'];
                }
                else 
                {
                    $post['wld'] = 0;
                }
                
                if(!isset($post['bp_not_controlled'])) $post['bp_not_controlled'] = 0;
                if(!isset($post['give_blood_pressure_log'])) $post['give_blood_pressure_log'] = 0;
                if(!isset($post['new_ekg'])) $post['new_ekg'] = 0;
                if(!isset($post['new_blood_work'])) $post['new_blood_work'] = 0;
                if(!isset($post['see_your_primary_care_physician'])) $post['see_your_primary_care_physician'] = 0;
                if(!isset($post['abnormal_lab_work'])) $post['abnormal_lab_work'] = 0;
                if(!isset($post['schedule_to_see_the_dr'])) $post['schedule_to_see_the_dr'] = 0;
                if(!isset($post['clearance_letter'])) $post['clearance_letter'] = 0;
                if(!isset($post['reduce_dosage_of_current_medication'])) $post['reduce_dosage_of_current_medication'] = 0;
                
                unset($post['change_meds_options'],$post['wld_options'],$post['change_meds_status_options']);
                
//                print_r($post);
//                die();
                $config['global_xss_filtering'] = FALSE;
                $this->patient->updateDrNote($visit_id,$post);
                $config['global_xss_filtering'] = TRUE;
                
                $this->session->set_flashdata('message',"Doctor Note Updated Successfully.");
                redirect("doctor/patient/$visit->patient_id");
            }           
        }
        
        
        $patient = $this->patient->getPatient($visit->patient_id);
        $this->data['patient'] = $patient;
        
        
        $this->data['this_visit'] = $visit;
        $this->data['last_visit'] = ($visit->visit > 1)? $this->patient->getPatientVisit($patient->id,$visit->visit - 1):FALSE;
        
        //First visit
        $this->data['first_visit'] = $this->patient->getPatientVisit($patient->id,1);
        
        
        $this->load->view('doctor/note',$this->data);
    }
    
    public function note_pdf($visit_id)
    {
        $visit = $this->patient->getVisitById($visit_id);
        $patient = $this->patient->getPatient($visit->patient_id);
        $this->data['patient'] = $patient;
        
        
        $this->data['this_visit'] = $visit;
        $this->data['last_visit'] = ($visit->visit > 1)? $this->patient->getPatientVisit($patient->id,$visit->visit - 1):FALSE;
        
        //First visit
        $this->data['first_visit'] = $this->patient->getPatientVisit($patient->id,1);
        
        $html = $this->load->view('doctor/note_pdf',$this->data,true);
        create_mp_ticket($html);
    }
    
    public function logout()
    {
	$this->session->unset_userdata('user');
	$this->session->unset_userdata('dr_logged');
	redirect('/doctor');
    }
}
