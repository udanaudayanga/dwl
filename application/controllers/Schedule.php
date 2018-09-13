<?php
/**
 * Description of Schedule
 *
 * @author Udana
 */
class Schedule extends Admin_Controller
{
    public function __construct() {
        parent::__construct();
        
        $this->load->model('Schedule_model','schedule');
        $this->load->model('Location_model','location');
        $this->load->model('User_model','user');
    }
    
    public function index()
    {
        
        $this->genData();
        
        $this->data['bc1'] = 'Employee';
	$this->data['bc2'] = 'Weekly Schedule';
        
        $this->data['locations'] = $this->location->getAll(TRUE);
        $this->data['employees'] = $this->user->getAll();
        
        $this->load->view('schedule/index',$this->data);
    }
    
    private function genData()
    {
        $start_date = $this->session->userdata('startdate');
        $location = ($this->session->userdata('slocation'))?$this->session->userdata('slocation'):1;
        
        
        if(!$start_date)
        {
            $start_date = getWeekStartDate();
            $this->session->set_userdata('startdate',$start_date);
        }
        
        $weekDays = getWeekDatys($start_date);
        
        $this->data['week_days'] = $weekDays;
        
        $shifts = $this->schedule->getshifts($location,$weekDays[1],$weekDays[6]);
        $empShifts = array();
        $i = $total = 0;
        $colors = $this->config->item('colors');
        
        foreach($shifts as $shift)
        {
           if(isset($empShifts[$shift->user_id])) 
           {
               $shifts = $empShifts[$shift->user_id]['shifts'];
               if(!isset($shifts[$shift->date][1]))
               {
                   $shifts[$shift->date][1] = array('start'=>$shift->start,'end'=>$shift->end,'id'=>$shift->id,'date'=>$shift->date,'abbr'=>$shift->abbr,'note'=>$shift->note,'shift'=>$shift->shift);
               }
               elseif(!isset($shifts[$shift->date][2]))
               {
                   $shifts[$shift->date][2] = array('start'=>$shift->start,'end'=>$shift->end,'id'=>$shift->id,'date'=>$shift->date,'abbr'=>$shift->abbr,'note'=>$shift->note,'shift'=>$shift->shift);
               }
               elseif(!isset($shifts[$shift->date][3]))
               {
                    $shifts[$shift->date][3] = array('start'=>$shift->start,'end'=>$shift->end,'id'=>$shift->id,'date'=>$shift->date,'abbr'=>$shift->abbr,'note'=>$shift->note,'shift'=>$shift->shift);
               }
               $empShifts[$shift->user_id]['shifts'] = $shifts;
               $empShifts[$shift->user_id]['work'] += strtotime($shift->end) - strtotime($shift->start);
               if($shift->user_id != 14)$total += strtotime($shift->end) - strtotime($shift->start);
           }
           else
           {
               $empShifts[$shift->user_id]['name'] = $shift->lname." ".$shift->fname;
               $empShifts[$shift->user_id]['color'] = $colors[$i%2];
               $empShifts[$shift->user_id]['work'] = strtotime($shift->end) - strtotime($shift->start);
               if($shift->user_id != 14)$total += strtotime($shift->end) - strtotime($shift->start);
               $shifts = array();
               $shifts[$shift->date][1] = array('start'=>$shift->start,'end'=>$shift->end,'id'=>$shift->id,'date'=>$shift->date,'abbr'=>$shift->abbr,'note'=>$shift->note,'shift'=>$shift->shift); 
               $empShifts[$shift->user_id]['shifts'] = $shifts;
               $i++;
           }
        }
        
        $this->data['locations'] = $this->location->getAll(TRUE);
        $this->data['slocation'] = $location;
        $this->data['emp_shifts'] = $empShifts;  
        $this->data['total'] = $total;
    }
    
    public function changeWeek()
    {
        $post = $this->input->post();
        $startDate = $this->session->userdata('startdate');
        if($post['nav']=='next')
        {
            $startDate = date('Y-m-d', strtotime($startDate.' +7 days'));
        }
        else
        {
            $startDate = date('Y-m-d', strtotime($startDate.' -7 days'));
        }
        $this->session->set_userdata('startdate',$startDate);
        $this->genData();
        
        echo $this->load->view('schedule/_shifts',$this->data,true);
    }
    
    public function changeLocation()
    {
        $post = $this->input->post();
        $this->session->set_userdata('slocation',$post['location']);
        $this->genData();
        
        echo $this->load->view('schedule/_shifts',$this->data,true);
    }
    
    public function addShift()
    {
        $this->form_validation->set_rules('location_id', 'Location', 'trim|required');
        $this->form_validation->set_rules('user_id', 'Employee', 'trim|required');
        $this->form_validation->set_rules('date', 'Shift Date', 'trim|required');
        $this->form_validation->set_rules('start', 'Shift Start', 'trim|required');
        $this->form_validation->set_rules('end', 'Shift End', 'trim|required');
        
        $result = array();
        if($this->form_validation->run() == TRUE)
	{
	    $post = $this->input->post();
            if(strtotime($post['start']) >= strtotime($post['end']))
            {
                $result['status'] = 'error';
                 $result['errors'] = '<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6" style="padding:0 5px;"><div style="padding:5px;" role="alert" class="alert fresh-color alert-danger"><strong>Start and End times are invalid</strong></div></div>';
            }
            elseif($this->schedule->isShiftExist($post))
            {
                $result['status'] = 'error';
                 $result['errors'] = '<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6" style="padding:0 5px;"><div style="padding:5px;" role="alert" class="alert fresh-color alert-danger"><strong>Shift overlap with another shift.</strong></div></div>';
            }
            elseif($this->schedule->isShiftTypeExist($post))
            {
                $result['status'] = 'error';
                $result['errors'] = '<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6" style="padding:0 5px;"><div style="padding:5px;" role="alert" class="alert fresh-color alert-danger"><strong>This shift type already existing.</strong></div></div>';
            }
            else
            {
                $this->schedule->addShift($post);
                $this->genData();
                $result['table'] = $this->load->view('schedule/_shifts',$this->data,true);
                $result['status'] = 'success';
            }
            
        }
        else
        {
            $result['status'] = 'error';
            $result['errors'] = validation_errors('<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6" style="padding:0 5px;"><div style="padding:5px;" role="alert" class="alert fresh-color alert-danger"><strong>','</strong></div></div>');
        }
        
        echo json_encode($result);
    }
    
    public function editShift()
    {
        $this->form_validation->set_rules('date', 'Shift Date', 'trim|required');
        $this->form_validation->set_rules('start', 'Shift Start', 'trim|required');
        $this->form_validation->set_rules('end', 'Shift End', 'trim|required');
        
        $result = array();
        if($this->form_validation->run() == TRUE)
	{
	    $post = $this->input->post();
            if(strtotime($post['start']) >= strtotime($post['end']))
            {
                $result['status'] = 'error';
                 $result['errors'] = '<div class="col-xs-12" style="padding:0 5px;"><div style="padding:5px;" role="alert" class="alert fresh-color alert-danger"><strong>Start and End times are invalid</strong></div></div>';
            }
            else
            {
                $id = $post['shift_id'];
                unset($post['shift_id']);
                $this->schedule->updateShift($id,$post);
                $this->genData();
                $result['table'] = $this->load->view('schedule/_shifts',$this->data,true);
                $result['status'] = 'success';
                $result['msg'] = '<div role="alert" class="alert fresh-color alert-success"><strong>Shift Updated Successfully.</strong></div>';
            }          
            
        }
        else
        {
            $result['status'] = 'error';
            $result['errors'] = validation_errors('<div class="col-xs-12" style="padding:0 5px;"><div style="padding:5px;" role="alert" class="alert fresh-color alert-danger"><strong>','</strong></div></div>');
        }
        
        echo json_encode($result);
    }
    
    public function remShift()
    {
        $post = $this->input->post();
        $this->schedule->removeShift($post['id']);
        $this->genData();
        echo $this->load->view('schedule/_shifts',$this->data,true);
    }
    
    public function daily($date)
    {
        $this->data['bc1'] = 'Employee';
	$this->data['bc2'] = 'Daily Schedule';
        
        $location = ($this->session->userdata('slocation'))?$this->session->userdata('slocation'):1;
        $shifts = $this->schedule->getDailyShifts($date,$location);
        $colors = $this->config->item('colors');
        $i = 0;
        $empShifts = array();
        foreach($shifts as $shift)
        {
           if(isset($empShifts[$shift->user_id])) 
           {
               $shifts = $empShifts[$shift->user_id]['shifts'];
               if(!isset($shifts[1]))
               {
                   $shifts[1] = array('start'=>$shift->start,'end'=>$shift->end,'id'=>$shift->id,'abbr'=>$shift->abbr);
               }
               elseif(!isset($shifts[2]))
               {
                    $shifts[2] = array('start'=>$shift->start,'end'=>$shift->end,'id'=>$shift->id,'abbr'=>$shift->abbr);
               }
               elseif(!isset($shifts[3]))
               {
                    $shifts[3] = array('start'=>$shift->start,'end'=>$shift->end,'id'=>$shift->id,'abbr'=>$shift->abbr);
               }
               $empShifts[$shift->user_id]['shifts'] = $shifts;
               $empShifts[$shift->user_id]['work'] += strtotime($shift->end) - strtotime($shift->start);
               if($shift->shift == 1)$empShifts[$shift->user_id]['s1'] = strtotime($shift->end) - strtotime($shift->start);
               if($shift->shift == 2)$empShifts[$shift->user_id]['s2'] = strtotime($shift->end) - strtotime($shift->start);     
               if($shift->shift == 3)$empShifts[$shift->user_id]['s3'] = strtotime($shift->end) - strtotime($shift->start);     
           }
           else
           {
               $empShifts[$shift->user_id]['name'] = $shift->lname." ".$shift->fname;
               $empShifts[$shift->user_id]['work'] = strtotime($shift->end) - strtotime($shift->start);
               if($shift->shift == 1)$empShifts[$shift->user_id]['s1'] = strtotime($shift->end) - strtotime($shift->start);
               if($shift->shift == 2)$empShifts[$shift->user_id]['s2'] = strtotime($shift->end) - strtotime($shift->start);  
               if($shift->shift == 3)$empShifts[$shift->user_id]['s3'] = strtotime($shift->end) - strtotime($shift->start); 
               $empShifts[$shift->user_id]['color'] = $colors[$i%2];
               $shifts = array();
               $shifts[1] = array('start'=>$shift->start,'end'=>$shift->end,'id'=>$shift->id,'abbr'=>$shift->abbr); 
               $empShifts[$shift->user_id]['shifts'] = $shifts;
           }
           $i++;
        }
       
        
        $this->data['daily_loc'] = $location;
        $this->data['date'] = $date;
        $this->data['emp_shifts'] = $empShifts;
        $this->session->unset_userdata("print_daily");
        $this->session->set_userdata("print_daily",array("emp_shifts"=>$empShifts,"daily_loc"=>$location,"date"=>$date));
        $this->load->view('schedule/daily',$this->data);
    }
    
    public function printDay()
    {
        $this->data += $this->session->userdata("print_daily");
        
        $html = $this->load->view('schedule/print_daily',  $this->data,TRUE);
//        echo $html;
//        die();
        create_mp_logs($html);
    }
    
    public function copyNext()
    {
        $start_date = $this->session->userdata('startdate');
        $currweek = getWeekDatys($start_date);
        
        $currshifts = $this->schedule->getshifts('all',$currweek[1],$currweek[6]);
        
        $new_week = array();
        
        foreach($currshifts as $shift)
        {
            $temp = array();
            $temp['user_id'] = $shift->user_id;
            $temp['location_id'] = $shift->location_id;
            $temp['start'] = $shift->start;
            $temp['end'] = $shift->end;
            $temp['date'] = date('Y-m-d',strtotime("+7 days", strtotime($shift->date)));
            
            array_push($new_week, $temp);
        }
        
        $this->schedule->addShiftBatch($new_week);
        
        $new_start = date('Y-m-d',strtotime("+7 days", strtotime($start_date)));
        $this->session->set_userdata('startdate',$new_start);
        
        $this->genData();
        echo $this->load->view('schedule/_shifts',$this->data,true);
    }
    
    public function reports()
    {
        $this->data['bc1'] = 'Employee';
	$this->data['bc2'] = 'Schedule Reports';
        
        $this->data['locations'] = $this->location->getAll(TRUE);
        $this->data['employees'] = $this->user->getAll();
        
        $this->load->view('schedule/reports',$this->data);
    }
    
    public function reportsn()
    {
        $this->data['bc1'] = 'Employee';
	$this->data['bc2'] = 'Schedule Reports';
        
        $this->data['locations'] = $this->location->getAll(TRUE);
        $this->data['employees'] = $this->user->getUserByType(array(2,4));
        
        $this->load->view('schedule/reportsn',$this->data);
    }
    
    public function genReportn()
    {
        $get = $this->input->get();
        
        
        if(empty($get['from']))
        {
            echo "FROM field are required";
            die();
        }
        
        $start = $get['from'];
        $end = date('Y-m-d',strtotime("+13 days", strtotime($start)));
        
        $shifts = $this->schedule->getShifts('all',$start,$end,$get['se']);
        
        
        $week = array();
        $week['wk1']['start'] = $start;
        $week['wk1']['end'] = date('Y-m-d',strtotime("+6 days", strtotime($start)));
        $week['wk2']['start'] = date('Y-m-d',strtotime("+7 days", strtotime($start)));
        $week['wk2']['end'] = date('Y-m-d',strtotime("+13 days", strtotime($start)));
                
        
        $empShifts = array();
        
        foreach($shifts as $shift)
        {
            if(isset($empShifts[$shift->user_id]))
            {
                if((strtotime($week['wk1']['start']) <= strtotime($shift->date)) && (strtotime($week['wk1']['end']) >= strtotime($shift->date)))
                {
                    $wk1 = isset($empShifts[$shift->user_id]['locs'][$shift->location_id]['wk1'])? $empShifts[$shift->user_id]['locs'][$shift->location_id]['wk1']:array();
                    $wk1c = isset($empShifts[$shift->user_id]['locs'][$shift->location_id]['wk1c'])? $empShifts[$shift->user_id]['locs'][$shift->location_id]['wk1c']:0;
                    
                    if(isset($wk1[$shift->date][1])) $wk1[$shift->date][2] = array('start'=>$shift->start,'end'=>$shift->end);  
                    else $wk1[$shift->date][1] = array('start'=>$shift->start,'end'=>$shift->end);
                    
                    $wk1c += strtotime($shift->end) - strtotime($shift->start);
                    
                    $empShifts[$shift->user_id]['locs'][$shift->location_id]['wk1'] = $wk1;
                    $empShifts[$shift->user_id]['locs'][$shift->location_id]['wk1c'] = $wk1c;
                    $empShifts[$shift->user_id]['work'] += strtotime($shift->end) - strtotime($shift->start);
                }
                if((strtotime($week['wk2']['start']) <= strtotime($shift->date)) && (strtotime($week['wk2']['end']) >= strtotime($shift->date)))
                {
                    $wk2 = isset($empShifts[$shift->user_id]['locs'][$shift->location_id]['wk2'])? $empShifts[$shift->user_id]['locs'][$shift->location_id]['wk2']:array();
                    $wk2c = isset($empShifts[$shift->user_id]['locs'][$shift->location_id]['wk2c'])? $empShifts[$shift->user_id]['locs'][$shift->location_id]['wk2c']:0;
                    
                    if(isset($wk2[$shift->date][1])) 
                        $wk2[$shift->date][2] = array('start'=>$shift->start,'end'=>$shift->end);  
                    else 
                        $wk2[$shift->date][1] = array('start'=>$shift->start,'end'=>$shift->end);
                    
                    $wk2c += strtotime($shift->end) - strtotime($shift->start);
                    
                    $empShifts[$shift->user_id]['locs'][$shift->location_id]['wk2'] = $wk2;   
                    $empShifts[$shift->user_id]['locs'][$shift->location_id]['wk2c'] = $wk2c;
                    $empShifts[$shift->user_id]['work'] += strtotime($shift->end) - strtotime($shift->start);
                }
                $empShifts[$shift->user_id]['locs'][$shift->location_id]['abbr'] = $shift->abbr;
            }
            else 
            {
                $empShifts[$shift->user_id]['name'] = $shift->lname." ".$shift->fname;
                
                
                $wk1 = $wk2 = array();
                $wk1c = $wk2c = 0;
                if((strtotime($week['wk1']['start']) <= strtotime($shift->date)) && (strtotime($week['wk1']['end']) >= strtotime($shift->date)))
                {
                    $wk1[$shift->date][1] = array('start'=>$shift->start,'end'=>$shift->end);  
                    $wk1c = strtotime($shift->end) - strtotime($shift->start);
                    $empShifts[$shift->user_id]['work'] = $wk1c;
                }
                if((strtotime($week['wk2']['start']) <= strtotime($shift->date)) && (strtotime($week['wk2']['end']) >= strtotime($shift->date)))
                {
                    $wk2[$shift->date][1] = array('start'=>$shift->start,'end'=>$shift->end);  
                    $wk2c = strtotime($shift->end) - strtotime($shift->start);
                    $empShifts[$shift->user_id]['work'] = $wk2c;
                }
                
                $empShifts[$shift->user_id]['locs'][$shift->location_id]['wk1'] = $wk1;
                $empShifts[$shift->user_id]['locs'][$shift->location_id]['wk2'] = $wk2;
                $empShifts[$shift->user_id]['locs'][$shift->location_id]['wk1c'] = $wk1c;
                $empShifts[$shift->user_id]['locs'][$shift->location_id]['wk2c'] = $wk2c;
                $empShifts[$shift->user_id]['locs'][$shift->location_id]['abbr'] = $shift->abbr;
                
            }
        }
        
        $this->data['week'] = $week;
        $this->data['shifts'] = $empShifts;
        $this->data['from'] = $get['from'];
        $this->data['to'] = $end;
       
        
        $html = $this->load->view('schedule/report_pdfn',  $this->data,TRUE);
        
        create_mp_logs($html);
    }
    
    public function genReport()
    {
        $get = $this->input->get();
        
        if(empty($get['from']) || empty($get['to']))
        {
            echo "FROM and TO fields are required";
            die();
        }
        
        $shifts = $this->schedule->getShifts($get['location_id'],$get['from'],$get['to']);
        $empShifts = array();
        
        foreach($shifts as $shift)
        {
            if(isset($empShifts[$shift->date][$shift->user_id][$shift->location_id])) 
            {
                $empShifts[$shift->date][$shift->user_id][$shift->location_id]['work'] += strtotime($shift->end) - strtotime($shift->start);
            }
            else
            {
                $empShifts[$shift->date][$shift->user_id][$shift->location_id]['shift'] = $shift;
                $empShifts[$shift->date][$shift->user_id][$shift->location_id]['work'] = strtotime($shift->end) - strtotime($shift->start);
            }
        }
        
        
        $this->data['shifts'] = $empShifts;
        $this->data['from'] = $get['from'];
        $this->data['to'] = $get['to'];
        
        $html = $this->load->view('schedule/report_pdf',  $this->data,TRUE);

        create_mp_ticket($html);
    }
}
