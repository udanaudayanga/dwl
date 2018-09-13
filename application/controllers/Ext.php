<?php
/**
 * Description of Ext
 *
 * @author Udana
 */
class Ext extends CI_Controller
{
    
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('Evaluate_model','evaluate');
        $this->load->model('Ext_model','ext');
    }
    
    public function weekly($patient_id)
    {
        $data = array();
        
        $patient = $this->patient->getPatient($patient_id);
        $data['patient'] = $patient;
        
        $phase = $this->evaluate->getLastPhase($patient_id);
        
        if(!$phase)die();
        
//        $phase = $this->evaluate->getPhase($id);
        $data['phase'] = $phase;
        $expect_diff = 0;//$phase->start_weight - $phase->target_weight;
        $data['expect_gap'] = $expect_diff;
        
        $visits = $this->patient->getPatientVisitsInPeriod($patient_id,$phase->start,$phase->end);
        
        $start_date  = new DateTime($phase->start);
        $interval    = new DateInterval('P1W');
        $recurrences = 16;
        $start = $start_date;
        $last_visit = null;
        
        
        $expected = $actual = array();
        $i = 1;
        foreach (new DatePeriod($start_date, $interval, $recurrences) as $date) 
        {
            if($start_date == $date) continue;
            $null = true;            
            foreach($visits as $visit)
            {
                $visit_date = new DateTime($visit->visit_date);
                if(dateIsInBetween($start, $date, $visit_date))
                { 
                    $actual[$i] = $visit->weight;
                    $visit->week_no = $i;
                    $last_visit = $visit;
                    $null = false;
                    break;
                }
            }
            
            if($null)
            {
                $actual[$i] = null;
            }
            $start = $date;
            $i++;            
        }
        
        
        $expect_gap = $expect_diff / 16;
        $expect[0] = 160;//$phase->start_weight;
        for ($index = 1; $index < 16; $index++) 
        {
            $expect[$index] = $expect[$index - 1] - $expect_gap;
        }
        
        $data['actual'] = implode(',', $actual);
        $data['expect'] = implode(',', $expect);
        $data['last_visit'] = $last_visit;
        
        $best_weeks = array();
        $lweek = null;
        for ($index = 1; $index < 16; $index++) 
        {
            if($lweek == null || $actual[$index] ==NULL) 
            {
                $lweek = $actual[$index];
                continue;
            }
            else 
            {
                $best_weeks[$index - 1] = $lweek - $actual[$index];
                $lweek = $actual[$index];
            }
        }
        
        arsort($best_weeks);
        $data['best_weeks'] = $best_weeks;
        
        if($last_visit != null)
        {
            $start_date = new DateTime($phase->start);
            $last_date = new DateTime(date('Y-m-d',strtotime($last_visit->visit_date)));

            $no_of_dates = $last_date->diff($start_date)->format("%a");
            $lost_weight = $phase->start_weight - $last_visit->weight;

            $data['daily_avg'] = $lost_weight/$no_of_dates;
            $data['weekly_avg'] = $lost_weight/$last_visit->week_no;
            $data['weight_loss_to_go'] = $expect_diff - $lost_weight;      
        }
        
        $this->load->view('ext/weekly',  $data);
    }
    
    public function remind($patient_id)
    {
        $data = array();
        $data['success'] = false;
        
        if($this->input->server('REQUEST_METHOD') === 'POST')
    	{
            $pr = array();
            $post = $this->input->post();
            foreach($post['reminder'] as $r)
            {
                if(!$this->ext->isReminderExist($patient_id,$r))
                {
                    $temp = array();
                    $temp['patient_id'] = $patient_id;
                    $temp['reminder_id'] = $r;
                    $temp['created'] = date('Y-m-d');
                    
                    array_push($pr,$temp);
                }
            }
            
            if(!empty($pr))$this->ext->insert($pr);
            $data['success'] = TRUE;
        }
        
        $data['reminders'] = $this->ext->getReminders();
        
        $this->load->view('ext/remind',  $data);
    }
    
    public function freeB12($code = null)
    {
        $this->load->library('encrypt');
        
        if(!$code || $code =='') die("INVALID URL.");
        $code = urldecode(urldecode($code));
        $code = (int)$this->encrypt->decode($code);
        if(!$code || $code == 0) die("INVALID URL.");
        
        $patient = $this->patient->getPatient($code);
        if(!$patient)  die("INVALID URL.");
        $data = array();
        $data['patient'] = $patient;
        $won = $this->ext->isOrderExist($patient->id,120)? 1:0;
        $data['won'] = $won;
        $promo_id = 1;
        $data['promo_id'] = $promo_id;
        
        if($won == 1) $this->ext->saveScratchPromo($patient->id,$promo_id);
        
        $this->load->view('ext/free_b12',$data);
    }
    
    public function updateScratch()
    {
        $post = $this->input->post();
        $this->ext->updateScratch($post['patient_id'],$post['promo_id']);
        echo 'success';
    }
}
