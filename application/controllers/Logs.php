<?php
/**
 * Description of Logs
 *
 * @author Udana
 */
class Logs extends Admin_Controller
{
    public function __construct()
    {
	parent::__construct();
        
        $this->load->model('Location_model','location');
    }
    
    public function index()
    {
        $this->data['bc1'] = 'Logs';
	$this->data['bc2'] = 'Print';
        $this->data['locations'] = $this->location->getAll();
        
        
        $this->load->view('logs/index',$this->data);
    }
    
    public function report($location_id,$start,$end)
    {
        $location = $this->location->get($location_id);
        $this->data['loc'] = $location;
        
        $rawLogs = $this->patient->getLocDateLog($location_id,$start,$end);
        
        $logs = array();
        $medCounts = array();
        
        $medsById = $this->config->item('meds_for_id');
        $medInfo = $this->config->item('meds_info');
        
//        $rawLogs  = array_merge($rawLogs,$rawLogs);
//        $rawLogs  = array_merge($rawLogs,$rawLogs);
//        $rawLogs  = array_merge($rawLogs,$rawLogs);
//        $rawLogs  = array_merge($rawLogs,$rawLogs);
        
        foreach($rawLogs as $log)
        {
            if($log->med3 > 0)
            {
                $med  = $medsById[$log->med3];
                $medin = $medInfo[$med];
                
                $temp = new stdClass();
                $temp->fname = $log->fname;
                $temp->lname = $log->lname;
                $temp->dob = $log->dob;
                $temp->phone = $log->phone;
                $temp->med = $log->med3;
                $temp->address = $log->address;
                $temp->city = $log->city;
                $temp->zip = $log->zip;
                $temp->abbr = $log->abbr;
                $temp->prescription_no = $log->prescription_no;
                $temp->days = $log->med_days;
                $temp->qty = $log->med_days * $log->meds_per_day;
                $temp->visit_date = $log->visit_date;
                $temp->mfg = $medin['mfg'];
                $temp->ndc = $medin['ndc'];
                $temp->medi = $medin['med'];
                $temp->pres_date = $log->ori_pres_date? date('m/d/Y',strtotime($log->ori_pres_date)) : date('m/d/Y',strtotime($log->visit_date));
                $temp->refill = $log->refill;
                
                array_push($logs, $temp);
                
                $medCounts[$med] = isset($medCounts[$med])? $medCounts[$med] + $temp->qty : $temp->qty;
                
            }
            else
            {
                if($log->med1 > 0)
                {
                    $med  = $medsById[$log->med1];
                    $medin = $medInfo[$med];
                    
                    
                    $temp = new stdClass();
                    $temp->fname = $log->fname;
                    $temp->lname = $log->lname;
                    $temp->dob = $log->dob;
                    $temp->phone = $log->phone;
                    $temp->med = $log->med1;
                    $temp->address = $log->address;
                    $temp->city = $log->city;
                    $temp->zip = $log->zip;
                    $temp->abbr = $log->abbr;
                    $temp->prescription_no = $log->prescription_no;
                    $temp->days = $log->med_days;
                    $temp->qty = $log->med_days * $log->meds_per_day;
                    $temp->visit_date = $log->visit_date;                    
                    $temp->mfg = $medin['mfg'];
                    $temp->ndc = $medin['ndc'];
                    $temp->medi = $medin['med'];
                    $temp->pres_date = $log->ori_pres_date? date('m/d/Y',strtotime($log->ori_pres_date)) : date('m/d/Y',strtotime($log->visit_date));
                    $temp->refill = $log->refill;

                    array_push($logs, $temp);
                    
                    $medCounts[$med] = isset($medCounts[$med])? $medCounts[$med] + $temp->qty : $temp->qty;
                }
                
                if($log->med2 > 0)
                {
                    $med  = $medsById[$log->med2];
                    $medin = $medInfo[$med];
                    
                    $temp = new stdClass();
                    $temp->fname = $log->fname;
                    $temp->lname = $log->lname;
                    $temp->dob = $log->dob;
                    $temp->phone = $log->phone;
                    $temp->med = $log->med2;
                    $temp->address = $log->address;
                    $temp->city = $log->city;
                    $temp->zip = $log->zip;
                    $temp->abbr = $log->abbr;
                    $temp->prescription_no = $log->prescription_no;
                    $temp->days = $log->med_days;
                    $temp->qty = $log->med_days * $log->meds_per_day;
                    $temp->visit_date = $log->visit_date;
                    $temp->mfg = $medin['mfg'];
                    $temp->ndc = $medin['ndc'];
                    $temp->medi = $medin['med'];
                    $temp->pres_date = $log->ori_pres_date? date('m/d/Y',strtotime($log->ori_pres_date)) : date('m/d/Y',strtotime($log->visit_date));
                    $temp->refill = $log->refill;

                    array_push($logs, $temp);
                    
                    $medCounts[$med] = isset($medCounts[$med])? $medCounts[$med] + $temp->qty : $temp->qty;
                }
            }
        }
        
        $this->data['logs'] = $logs;
        $this->data['start'] = $start;
        $this->data['end'] = $end;
        $this->data['counts'] = $medCounts;
        
        $html = $this->load->view('logs/report',  $this->data,TRUE);
        
        create_mp_logs($html);
    }
    
    public function prescriptions()
    {
        $this->data['bc1'] = 'Prescriptions';
	$this->data['bc2'] = 'Print';
        $this->data['locations'] = $this->location->getAll();
        
        
        $this->load->view('logs/prescriptions',$this->data);
    }
    
    public function print_prescription($location_id,$start,$end)
    {
        ini_set('max_execution_time', 300);
        
        $location = $this->location->get($location_id);
        $this->date['location'] = $location;
        
        $rawLogs = $this->patient->getLocDateLogPres($location_id,$start,$end);
        $logs = array();
        
        $medsById = $this->config->item('meds_for_id');
        $medInfo = $this->config->item('meds_info');
        
               
//        $this->data['date'] = $date;
//        $rawLogs  = array_merge($rawLogs,$rawLogs);
//        $rawLogs  = array_merge($rawLogs,$rawLogs);
        
        foreach($rawLogs as $log)
        {
            if($log->med3 > 0)
            {
                $med  = $medsById[$log->med3];
                $medin = $medInfo[$med];
                
                $temp = new stdClass();
                $temp->fname = $log->fname;
                $temp->lname = $log->lname;
                $temp->dob = $log->dob;
                $temp->med3 = $log->med3;
                $temp->address = $log->address;
                $temp->city = $log->city;
                $temp->zip = $log->zip;
                $temp->abbr = $log->abbr;
                $temp->prescription_no = $log->prescription_no;
                $temp->days = $log->med_days;
                $temp->qty = $log->med_days * $log->meds_per_day;
                $temp->visit_date = $log->visit_date;
                $temp->med3_med = $medin['med'];
                $temp->med3_msg = getMedsMsg($log->med3, NULL, $log->meds_per_day);
                $temp->dea = $log->dea;
                $temp->address = $log->address;
                $temp->caddress = $log->caddress;
                $temp->refill = $log->refill;
                $temp->pres_date = $log->ori_pres_date? date('m/d/Y',strtotime($log->ori_pres_date)) : date('m/d/Y',strtotime($log->visit_date));
                
                array_push($logs, $temp);
                
            }
            else
            {
                $temp = new stdClass();
                $temp->fname = $log->fname;
                $temp->lname = $log->lname;
                $temp->dob = $log->dob;                
                $temp->address = $log->address;
                $temp->city = $log->city;
                $temp->zip = $log->zip;
                $temp->abbr = $log->abbr;
                $temp->prescription_no = $log->prescription_no;
                $temp->days = $log->med_days;
                $temp->qty = $log->med_days * $log->meds_per_day;
                $temp->visit_date = $log->visit_date;
                $temp->dea = $log->dea;
                $temp->address = $log->address;
                $temp->caddress = $log->caddress;
                $temp->refill = $log->refill;
                $temp->pres_date = $log->ori_pres_date? date('m/d/Y',strtotime($log->ori_pres_date)) : date('m/d/Y',strtotime($log->visit_date));
                
                if($log->med1 > 0)
                {
                    $med  = $medsById[$log->med1];
                    $medin = $medInfo[$med];
                    
                    $temp->med1 = $log->med1;
                    $temp->med1_med = $medin['med'];
                    $temp->med1_msg = getMedsMsg($log->med1, NULL, $log->meds_per_day);
                    
                }
                
                if($log->med2 > 0)
                {
                    $med  = $medsById[$log->med2];
                    $medin = $medInfo[$med];
                    
                    $temp->med2 = $log->med2;
                    $temp->med2_med = $medin['med'];
                    $temp->med2_msg = getMedsMsg(NULL, $log->med2, $log->meds_per_day);
                }
                array_push($logs, $temp);
            }
        }
         $this->data['logs'] = $logs;
        $html = $this->load->view('logs/print_prescription',  $this->data,TRUE);
//        echo $html;
//        die();
        create_mp_ticket($html);
    }
    
    public function presNo()
    {
        $this->data['bc1'] = 'Prescriptions';
	$this->data['bc2'] = 'For Prescription Number';
        
        
        $this->load->view('logs/presno',$this->data);
    }
    
    public function print_presno($pres)
    {
        ini_set('max_execution_time', 300);
        
//        $location = $this->location->get($location_id);
//        $this->date['location'] = $location;
        
        $rawLogs = $this->patient->getPresByNumber($pres);
        $logs = array();
        
        $medsById = $this->config->item('meds_for_id');
        $medInfo = $this->config->item('meds_info');
        
        foreach($rawLogs as $log)
        {
            if($log->med3 > 0)
            {
                $med  = $medsById[$log->med3];
                $medin = $medInfo[$med];
                
                $temp = new stdClass();
                $temp->fname = $log->fname;
                $temp->lname = $log->lname;
                $temp->dob = $log->dob;
                $temp->med3 = $log->med3;
                $temp->address = $log->address;
                $temp->city = $log->city;
                $temp->zip = $log->zip;
                $temp->abbr = $log->abbr;
                $temp->prescription_no = $log->prescription_no;
                $temp->days = $log->med_days;
                $temp->qty = $log->med_days * $log->meds_per_day;
                $temp->visit_date = $log->visit_date;
                $temp->med3_med = $medin['med'];
                $temp->med3_msg = getMedsMsg($log->med3, NULL, $log->meds_per_day);
                $temp->dea = $log->dea;
                $temp->address = $log->address;
                $temp->caddress = $log->caddress;
                $temp->refill = $log->refill;
                $temp->pres_date = $log->ori_pres_date? date('m/d/Y',strtotime($log->ori_pres_date)) : date('m/d/Y',strtotime($log->visit_date));
                
                array_push($logs, $temp);
                
            }
            else
            {
                $temp = new stdClass();
                $temp->fname = $log->fname;
                $temp->lname = $log->lname;
                $temp->dob = $log->dob;                
                $temp->address = $log->address;
                $temp->city = $log->city;
                $temp->zip = $log->zip;
                $temp->abbr = $log->abbr;
                $temp->prescription_no = $log->prescription_no;
                $temp->days = $log->med_days;
                $temp->qty = $log->med_days * $log->meds_per_day;
                $temp->visit_date = $log->visit_date;
                $temp->dea = $log->dea;
                $temp->address = $log->address;
                $temp->caddress = $log->caddress;
                $temp->refill = $log->refill;
                $temp->pres_date = $log->ori_pres_date? date('m/d/Y',strtotime($log->ori_pres_date)) : date('m/d/Y',strtotime($log->visit_date));
                
                if($log->med1 > 0)
                {
                    $med  = $medsById[$log->med1];
                    $medin = $medInfo[$med];
                    
                    $temp->med1 = $log->med1;
                    $temp->med1_med = $medin['med'];
                    $temp->med1_msg = getMedsMsg($log->med1, NULL, $log->meds_per_day);
                    
                }
                
                if($log->med2 > 0)
                {
                    $med  = $medsById[$log->med2];
                    $medin = $medInfo[$med];
                    
                    $temp->med2 = $log->med2;
                    $temp->med2_med = $medin['med'];
                    $temp->med2_msg = getMedsMsg(NULL, $log->med2, $log->meds_per_day);
                }
                array_push($logs, $temp);
            }
        }
         $this->data['logs'] = $logs;
        $html = $this->load->view('logs/print_prescription',  $this->data,TRUE);
      
        create_mp_ticket($html);
    }
    
    public function test()
    {
        
        $this->load->model('Evaluate_model','evaluate');
        
        $this->data['bc1'] = 'HTML';
	$this->data['bc2'] = 'Test';
        if($this->input->server('REQUEST_METHOD') === 'POST')
    	{  
            $post = $this->input->post();
            $this->evaluate->testUpdate(1,array('text'=>$post['text']));
        }
        
        $this->data['test'] = $this->evaluate->getTest(1);
        
        $this->load->view('ext/test',$this->data);
    }
    
    public function preview()
    {
        $this->load->model('Evaluate_model','evaluate');
        $this->data['test'] = $this->evaluate->getTest(1);
        
        $this->load->view('ext/preview',$this->data);
    }
    
    public function patient()
    {
        $this->data['bc1'] = 'Logs';
	$this->data['bc2'] = 'Patient';
        $this->load->view('logs/patient',$this->data);
    }
    
    public function patientlog($patientid,$start=null,$end=null)
    {
        $patient = $this->patient->getPatient($patientid);
        $this->data['patient'] = $patient;
        
        $rawLogs = $this->patient->getPatientPresLog($patientid,$start,$end);
        
        $logs = array();
        $medCounts = array();
        
        $medsById = $this->config->item('meds_for_id');
        $medInfo = $this->config->item('meds_info');
        
//        $rawLogs  = array_merge($rawLogs,$rawLogs);
//        $rawLogs  = array_merge($rawLogs,$rawLogs);
//        $rawLogs  = array_merge($rawLogs,$rawLogs);
//        $rawLogs  = array_merge($rawLogs,$rawLogs);
        
        foreach($rawLogs as $log)
        {
            if($log->med3 > 0)
            {
                $med  = $medsById[$log->med3];
                $medin = $medInfo[$med];
                
                $temp = new stdClass();
                $temp->loc = $log->loc;
                $temp->dob = $log->dob;
                $temp->med = $log->med3;
                $temp->address = $log->address;
                $temp->city = $log->city;
                $temp->zip = $log->zip;
                $temp->abbr = $log->abbr;
                $temp->prescription_no = $log->prescription_no;
                $temp->days = $log->med_days;
                $temp->qty = $log->med_days * $log->meds_per_day;
                $temp->visit_date = $log->visit_date;
                $temp->mfg = $medin['mfg'];
                $temp->ndc = $medin['ndc'];
                $temp->medi = $medin['med'];
                $temp->pres_date = $log->ori_pres_date? date('m/d/Y',strtotime($log->ori_pres_date)) : date('m/d/Y',strtotime($log->visit_date));
                $temp->refill = $log->refill;
                
                array_push($logs, $temp);
                
                $medCounts[$med] = isset($medCounts[$med])? $medCounts[$med] + $temp->qty : $temp->qty;
                
            }
            else
            {
                if($log->med1 > 0)
                {
                    $med  = $medsById[$log->med1];
                    $medin = $medInfo[$med];
                    
                    
                    $temp = new stdClass();
                    $temp->loc = $log->loc;
                    $temp->dob = $log->dob;
                    $temp->med = $log->med1;
                    $temp->address = $log->address;
                    $temp->city = $log->city;
                    $temp->zip = $log->zip;
                    $temp->abbr = $log->abbr;
                    $temp->prescription_no = $log->prescription_no;
                    $temp->days = $log->med_days;
                    $temp->qty = $log->med_days * $log->meds_per_day;
                    $temp->visit_date = $log->visit_date;                    
                    $temp->mfg = $medin['mfg'];
                    $temp->ndc = $medin['ndc'];
                    $temp->medi = $medin['med'];
                    $temp->pres_date = $log->ori_pres_date? date('m/d/Y',strtotime($log->ori_pres_date)) : date('m/d/Y',strtotime($log->visit_date));
                    $temp->refill = $log->refill;

                    array_push($logs, $temp);
                    
                    $medCounts[$med] = isset($medCounts[$med])? $medCounts[$med] + $temp->qty : $temp->qty;
                }
                
                if($log->med2 > 0)
                {
                    $med  = $medsById[$log->med2];
                    $medin = $medInfo[$med];
                    
                    $temp = new stdClass();
                    $temp->loc = $log->loc;
                    $temp->dob = $log->dob;
                    $temp->med = $log->med2;
                    $temp->address = $log->address;
                    $temp->city = $log->city;
                    $temp->zip = $log->zip;
                    $temp->abbr = $log->abbr;
                    $temp->prescription_no = $log->prescription_no;
                    $temp->days = $log->med_days;
                    $temp->qty = $log->med_days * $log->meds_per_day;
                    $temp->visit_date = $log->visit_date;
                    $temp->mfg = $medin['mfg'];
                    $temp->ndc = $medin['ndc'];
                    $temp->medi = $medin['med'];
                    $temp->pres_date = $log->ori_pres_date? date('m/d/Y',strtotime($log->ori_pres_date)) : date('m/d/Y',strtotime($log->visit_date));
                    $temp->refill = $log->refill;

                    array_push($logs, $temp);
                    
                    $medCounts[$med] = isset($medCounts[$med])? $medCounts[$med] + $temp->qty : $temp->qty;
                }
            }
        }
        
        $this->data['logs'] = $logs;
        
        $this->data['counts'] = $medCounts;
        
        $html = $this->load->view('logs/patient_log',  $this->data,TRUE);
        
        create_mp_ticket($html);
    }

    public function activities()
    {
        $this->data['bc1'] = 'Logs';
	    $this->data['bc2'] = 'Activities';
        $this->load->model('Util_model','util');
        
        $this->data['activities'] = $this->util->getActivities();

        $this->load->view('logs/activities',$this->data);
    }
}
