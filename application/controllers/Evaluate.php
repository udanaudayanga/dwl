<?php
/**
 * Description of Evaluate
 *
 * @author Udana
 */
class Evaluate extends Hybrid_controller
{
    public function __construct() {
        parent::__construct();
        
        $this->load->model('Evaluate_model','evaluate');
    }
    
    public function add()
    {
        $result = array();
        if($this->input->server('REQUEST_METHOD') === 'POST')
    	{ 
            $this->form_validation->set_rules('start', 'Start Date', 'trim|required');
            $this->form_validation->set_rules('end', 'End Date', 'trim|required');
            $this->form_validation->set_rules('phase', 'Phase', 'trim|required');
            
            if($this->form_validation->run() == TRUE)
	    {
                $post = $this->input->post();
                
                $post['created'] = date('Y-m-d H:i:s');
                $this->evaluate->add($post);
                $result['status'] = 'success';
                $this->session->set_flashdata('message','Phase Added Successfully');
            }
            else 
            {
                $result['status'] = 'error';
                $result['errors'] = validation_errors('<div class="col-sm-6 col-xs-12" style="padding:0 5px;"><div style="padding:5px;" role="alert" class="alert fresh-color alert-danger"><strong>','</strong></div></div>');            
            }
        }
        echo json_encode($result);
    }
    
    public function update()
    {
        $result = array();
        if($this->input->server('REQUEST_METHOD') === 'POST')
    	{ 
            $this->form_validation->set_rules('start', 'Start Date', 'trim|required');
            $this->form_validation->set_rules('end', 'End Date', 'trim|required');
            $this->form_validation->set_rules('phase', 'Phase', 'trim|required');
            
            if($this->form_validation->run() == TRUE)
	    {
                $post = $this->input->post();
                
                $post['created'] = date('Y-m-d H:i:s');
                $id = $post['id'];
                unset($post['id']);
                $this->evaluate->update($id,$post);
                $result['status'] = 'success';
                $this->session->set_flashdata('message','Phase Updated Successfully');
            }
            else 
            {
                $result['status'] = 'error';
                $result['errors'] = validation_errors('<div class="col-sm-6 col-xs-12" style="padding:0 5px;"><div style="padding:5px;" role="alert" class="alert fresh-color alert-danger"><strong>','</strong></div></div>');            
            }
        }
        echo json_encode($result);
    }
    
    public function view($patient_id)
    {
        $this->data['bc1'] = 'Evaluate';
	$this->data['bc2'] = 'View Phases';
        
        $patient = $this->patient->getPatient($patient_id);
        $this->data['patient'] = $patient;
        
        $this->data['phases'] = $this->evaluate->getPhases($patient_id);
        
        $this->load->view('evaluate/view',$this->data);
    }
    
    public function remAll($patient_id)
    {
        $this->evaluate->remAll($patient_id);
        $this->session->set_flashdata('message','All Phases removed Successfully');
        redirect("evaluate/view/$patient_id");
    }
    
    public function remove($patient_id,$id)
    {
        $this->evaluate->remove($id);
        $this->session->set_flashdata('message','Phase removed Successfully');
        redirect("evaluate/view/$patient_id");
    }
    
    public function chart($patient_id,$id)
    {
        $this->data['bc1'] = 'Evaluate';
	$this->data['bc2'] = 'Graphical Analysis';
        
        $patient = $this->patient->getPatient($patient_id);
        $this->data['patient'] = $patient;
        
        $phase = $this->evaluate->getPhase($id);
        $this->data['phase'] = $phase;
        $expect_diff = $phase->start_weight - $phase->target_weight;
        $this->data['expect_gap'] = $expect_diff;
        
        $visits = $this->patient->getPatientVisitsInPeriod($patient_id,$phase->start,$phase->end);
        
        $start_date  = new DateTime($phase->start);
        $interval    = new DateInterval('P1W');
        $recurrences = 12;
        $no_med_days = 0;
        $start = $start_date;
        $last_visit = $last_med = null;
        
        
        $expected = $actual = array();
        $i = 1;
        foreach (new DatePeriod($start_date, $interval, $recurrences) as $date) 
        {
           // echo $date->format('Y-m-d H:i:s')."<br>";
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
                    
                    if($last_med)
                    {
                        $last_med_date = new DateTime($last_med);
                        $this_visit = new DateTime(date('Y-m-d',strtotime($visit->visit_date)));
                        if($last_med_date < $this_visit)
                        {
                            $no_show_dates = $this_visit->diff($last_med_date)->format("%a");
                            $no_med_days = $no_med_days + $no_show_dates;
                        }
                    }
                    $med_days  = ($visit->is_med==1)? $visit->med_days : $visit->no_med_days;
                    if($med_days > 0)
                    {
                         $last_med = date('Y-m-d', strtotime($visit->visit_date. '+'.$med_days.' days'));
                    }
                    
                    
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
        $now = new DateTime('NOW');
        if($last_med)
        {
            $last_med_date = new DateTime($last_med);
            if($start < $now)
            {                
                if($last_med_date < $start)
                {
                    $no_show_dates = $start->diff($last_med_date)->format("%a");
                    $no_med_days = $no_med_days + $no_show_dates;
                }
            } 
            else 
            {
                if($last_med_date < $now)
                {
                    $no_show_dates = $now->diff($last_med_date)->format("%a");
                    $no_med_days = $no_med_days + $no_show_dates;
                }
            }
        }
        
        $expect_gap = $expect_diff / 12;
        $expect[0] = $phase->start_weight;
        for ($index = 1; $index < 12; $index++) 
        {
            $expect[$index] = $expect[$index - 1] - $expect_gap;
        }
        
        $this->data['actual'] = implode(',', $actual);
        $this->data['expect'] = implode(',', $expect);
        $this->data['last_visit'] = $last_visit;
        $this->data['no_show'] = ($no_med_days/84)*100;
        
        
        $best_weeks = array();
        $lweek = null;
        for ($index = 1; $index < 12; $index++) 
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
        $this->data['best_weeks'] = $best_weeks;
        
        if($last_visit != null)
        {
            $start_date = new DateTime($phase->start);
            $last_date = new DateTime(date('Y-m-d',strtotime($last_visit->visit_date)));

            $no_of_dates = $last_date->diff($start_date)->format("%a");
            $lost_weight = $phase->start_weight - $last_visit->weight;

            $this->data['daily_avg'] = $lost_weight/$no_of_dates;
            $this->data['weekly_avg'] = $lost_weight/$last_visit->week_no;
            $this->data['weight_loss_to_go'] = $expect_diff - $lost_weight;      
        }
        
        $this->load->view('evaluate/chart',$this->data); 
    }
    
    public function assignPhase()
    {
        $this->form_validation->set_rules('start', 'Start Date', 'trim|required');
        $this->form_validation->set_rules('phase', 'Phase', 'trim|required');
        
        $result = array();
        if($this->form_validation->run() == TRUE)
	{
	    $post = $this->input->post();
            $post['cycle'] = 1;
            $post['created'] = date('Y-m-d H:i:s');
            
            $this->evaluate->addPhase($post);
            
            $result['status'] = 'success';
            $result['msg'] = '<div role="alert" class="alert fresh-color alert-success"><strong>Phase Assigned Successfully.</strong></div>';
                      
            
        }
        else
        {
            $result['status'] = 'error';
            $result['errors'] = validation_errors('<div class="col-xs-12" style="padding:0 5px;"><div style="padding:5px;" role="alert" class="alert fresh-color alert-danger"><strong>','</strong></div></div>');
        }
        
        echo json_encode($result);
    }
    
    public function changePhase($phase_id)
    {
        
        $lastPhase = $this->evaluate->getPhaseById($phase_id);
        
        if($lastPhase->phase == 3)
        {
            $twoPhase = $this->evaluate->getPhasesByPhaseInfo($lastPhase->patient_id,$lastPhase->cycle,2);
            if($twoPhase->attempt > 2)
            {
                $this->session->set_flashdata('error','All three attempts have finished');
            }
            else
            {
                $this->evaluate->updatePhase($phase_id,array('phase'=>2,'attempt'=>($twoPhase->attempt + 1)));
                $this->session->set_flashdata('message','Phase changed successfuly.');
            }            
        }
        else 
        {
            $this->session->set_flashdata('error','Only phase 12.2 can change.');
        }
        
        redirect("evaluate/view/$lastPhase->patient_id");
    }
    
    public function report($eval_id)
    {
        $eval = $this->evaluate->getEvalById($eval_id);
        $patient = $this->patient->getPatient($eval->patient_id);
        
        //get first visit
        $first_visit = $this->patient->getPatientVisit($patient->id,1);
        $this->data['first_visit'] = $first_visit;
        
        $this->data['patient'] = $patient;
        $this->data['eval'] = $eval;
        $start = $eval->start;
        $end = empty($eval->end)? date('Y-m-d'):$eval->end;
        $visits = $this->patient->getPatientVisitsInPeriod($eval->patient_id,$start,$end);
        
        $this->getGraph($eval_id, $visits);
        $this->data['visits'] = $visits;
        $html = $this->load->view('evaluate/report',$this->data,true);
        
        create_mp_ticket($html);
    }
    
    
    public function getGraph($eval,$visits)
    {
        $axis = array();
        $min = $max = $i = 0;
        
        foreach($visits as $visit)
        {
            $x = date("m/d",strtotime($visit->visit_date));
            $axis[$visit->visit] = $visit->weight;
            $min = ($min == 0 || $min > $visit->weight) ? $visit->weight : $min;
            $max = $max < $visit->weight ? $visit->weight : $max;
            $i++;
        }
        
        $file =  './assets/upload/eval/'.$eval.'.png';
        $this->load->helper('file');
        require_once(APPPATH . 'libraries/phpgraphlib/phpgraphlib.php');
        $graph = new PHPGraphLib(490,290,$file);

        $graph->addData($axis);
        
        $graph->setupYAxis(0,'#ffffff');
        $graph->setupXAxis(6);
        $graph->setXValuesHorizontal(TRUE);
        $graph->setRange($max + 1,$min - 1);
        $graph->setBars(false);
        $graph->setLine(true);
        $graph->setDataPoints(true);
        $graph->setDataPointColor('#666666');
        $graph->setDataValues(true);
        $graph->setDataValueColor("#000000");
        $graph->setYValues(false);
        $graph->setXAxisTextColor("#000000");
        $graph->setGrid(false);
        $graph->setDataPointSize(4);
        $graph->createGraph();
        
    }
}
