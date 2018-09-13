<?php
/**
 * Description of Util
 *
 * @author Udana
 */
class Util extends Admin_Controller
{
    public function __construct() {
        parent::__construct();
        
        $this->load->model('Util_model','util');  
        $this->load->model('User_model','user');  
    }
    
    public function vp($order_id)
    {        
        
        $order  = $this->order->getOrder($order_id);
        
        $patient = $this->patient->getPatient($order->patient_id);
        $this->data['patient'] = $patient;
        $this->data['order'] = $order;
        $this->data['order_location'] = getLocation($order->location_id);
        
        $staff = $order->staff_id ? $this->user->get($order->staff_id):NULL;
        $this->data['staff'] = $staff? $staff->lname." ".$staff->fname:"-";
        
        //get first visit
        $first_visit = $this->patient->getPatientVisit($patient->id,1);
        $this->data['first_visit'] = $first_visit;
        
        $lastRestart = $this->order->getLastRestart($order->patient_id);
        if($lastRestart && $lastRestart->date > date('Y-m-d',strtotime($order->created))) $lastRestart = NULL;
        
        //get last 6 visits
        $latest_visits = $this->patient->getVisitsForVisitPage($patient->id,date('Y-m-d',strtotime($order->created)),$lastRestart);
        $latest_visits = array_reverse($latest_visits);
        
        if($lastRestart) 
        {
            $count = $this->patient->getVisitCountSinceLastRestart($order->patient_id,date('Y-m-d',strtotime($order->created)),$lastRestart);            
            $lastRestart->count = $count > 6 ? $count - 5 : 1;
        }
        $this->data['lastRestart'] = $lastRestart;
        
        $this->data['visits'] = $latest_visits;
        
        //Check order items for injections
       
        $this->data['injections'] = getTodayInjections($order_id);
        
        $this->data['avbl_prior'] = avblPPbeforeOrder($order_id);
        $this->data['redeem_exis'] = redeemedExisToday($order_id);
        
        $this->data['got_today'] = boughtToday($order_id);
        $this->data['redeem_new'] = redeemedNewToday($order_id);
        $this->data['pp_remaining'] = avblPPRemaining($order->patient_id, $order_id);
        $this->data['dralerts'] = $this->patient->getAlerts($order->patient_id,1); 
        
        $html = $this->load->view('util/visitpage',$this->data,true);
        return $html;
    }
    
    public function fp($order_id)
    {
        $this->load->model('User_model','user');  
         $order  = $this->order->getOrder($order_id);
        $patient = $this->patient->getPatient($order->patient_id);
        $this->data['patient'] = $patient;
        $this->data['order'] = $order; 
        $staff = $order->staff_id ? $this->user->get($order->staff_id):NULL;
        $this->data['staff'] = $staff? $staff->lname." ".$staff->fname:"-";
        $this->data['order_location'] = getLocation($order->location_id);
        
        //get first visit
        $first_visit = $this->patient->getPatientVisit($patient->id,1);
        $this->data['first_visit'] = $first_visit;
        
        $lastRestart = $this->order->getLastRestart($order->patient_id);
        if($lastRestart && $lastRestart->date > date('Y-m-d',strtotime($order->created))) $lastRestart = NULL;
        
        //get last 6 visits
        $latest_visits = $this->patient->getVisitsForVisitPage($order->patient_id,date('Y-m-d',strtotime($order->created)),$lastRestart);
        $latest_visits = array_reverse($latest_visits);
        
        if($lastRestart) 
        {
            $count = $this->patient->getVisitCountSinceLastRestart($order->patient_id,date('Y-m-d',strtotime($order->created)),$lastRestart);
            $lastRestart->count = $count > 6 ? $count - 5 : 1;
        }
        $this->data['lastRestart'] = $lastRestart;
        
        $order_items = $this->order->getOrderItemsWithNames($order_id);
        
        $this->data['visits'] = $latest_visits;
        
        $this_visit = $this->patient->getVisitByOrderId($order_id);
        $this->data['thisvisit'] = $this_visit;
        
        
        $this->data['injections'] = getTodayInjections($order_id);
       
        $days = $this_visit->is_med==1 ? $this_visit->med_days : $this_visit->no_med_days;
        if($days > 0)
        {
            $next_visit = date('m/d/Y', strtotime($this_visit->visit_date. " + $days days"));
            $this->data['next_visit'] = $next_visit;
            
        }
        
        $html = $this->load->view('util/finalpage',$this->data,true);
        
        return $html;
    }
    
    public function finalpages($date,$loc_id)
    {
        @ini_set('max_execution_time', 0);
        if(empty($date)) die('Date required');
        $visits = $this->util->getVisits($date,$loc_id);
        
        $html = '<!DOCTYPE html>
<html lang="en-US">
    <head>    
        <link rel="stylesheet" type="text/css" href="/assets/css/finalpage.css">
  
  </head>
  <body>';
        $start = FALSE;
        foreach($visits as $visit)
        {
            if($start)$html .= '<br style="page-break-before:always;">';
            $html .= $this->fp($visit->order_id);
            $start = TRUE;
        }
        
        $html .= '
  </body>
</html>';
        
        create_mp_ticket($html);
    }
    
    public function visitpages($date,$loc_id)
    {
        @ini_set('max_execution_time', 0);
        if(empty($date)) die('Date required');
        $visits = $this->util->getVisits($date,$loc_id);
        
        $html = '<!DOCTYPE html>
<html lang="en-US">
    <head>    
        <link rel="stylesheet" type="text/css" href="/assets/css/finalpage.css">
        <style>
            table.avbl_meds tr td{text-align: left;font-size: 16px;height: 18px;}
            table.bottom_tbl tr td{font-size: 19px;}
        </style>
  </head>
  <body>';       
        foreach($visits as $visit)
        {            
            $html .= $this->vp($visit->order_id);
        }
        
        $html .= '
  </body>
</html>';
        
        create_mp_ticket($html);
    }
    
    public function addblock()
    {
        // Start date
	$date = '2018-02-12';
	// End date
	$end_date = '2018-07-14';
        $data = array();
	while (strtotime($date) <= strtotime($end_date)) {                
            $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));

            $temp = array();
            $temp['date'] = $date;
            $temp['location_id'] = 4;
            $temp['start'] = '07:00:00';
            $temp['end'] = '19:00:00';
            $temp['type'] = 2;
            $temp['created'] = date('Y-m-d H:i:s');

            array_push($data,$temp);
	}
        
        $this->util->addBlock($data);
        die('done');
    }
}
