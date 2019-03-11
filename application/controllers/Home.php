<?php

/**
 * Description of Home
 *
 * @author Udana
 */
class Home extends Admin_Controller
{
    public function __construct()
    {
	parent::__construct();
        
        $this->load->model('Location_model','location');
         $this->load->model('Queue_model','queue');
    }
    
    public function index()
    {
        redirect('home/stats');
    }
    
    public function stats()
    {
        $this->data['bc1'] = 'Dashboard';
	$this->data['bc2'] = 'Stats';
        $this->data['is_all'] = false;
        $sg = FALSE;
        
        if($this->input->server('REQUEST_METHOD') === 'POST')
    	{  
            $post = $this->input->post();
            $user = $this->session->userdata('user');
            if($user->type == 1){
                $this->form_validation->set_rules('start', 'Start Date', 'trim|required');
                $this->form_validation->set_rules('end', 'End Date', 'trim|required');
            }else{
                $this->form_validation->set_rules('date', 'Date', 'trim|required');
            }
            if(!isset($post['location']))
            $this->form_validation->set_rules('location', 'Location', 'trim|required');
            
            
            if($this->form_validation->run() == TRUE)
	    {
                $stats = $orders = $injs = array();
                
                $locations = implode(",", $post['location']);
                
                if($user->type == 1)
                {
                    $this->data['start'] = $post['start'];
                    $this->data['end'] = $post['end'];
                    $orders = $this->patient->getStatsForDashboard($post['start'],$post['end'],$locations);
                    $injs = $this->getInjUsage($post['start'], $post['end']);
                    if($post['start'] == $post['end'])
                    {
                        $sg = TRUE;
                        $this->data['wd'] = date('w', strtotime($post['start']));
                        $this->data['date'] = $post['start'];
                    }                    
                }
                else
                {
                    $this->data['date'] = $post['date'];
                    $orders = $this->patient->getStatsForDashboardStaff($post['date'],$locations);
                    $injs = $this->getInjUsage($post['date'], $post['date']);
                    $sg = TRUE;
                    $this->data['wd'] = date('w', strtotime($post['date']));
//                    $this->data['b12u'] = $this->patient->getB12Used($post['date'],$post['date']);
                }
                $this->data['injs'] = $injs;    
                
                foreach($orders as $order)
                {
                    $crd = $csh = 0;
                    
                    if($order->payment_type == 'mix')
                    {
                        $crd = $order->credit_amount;
                        $csh = $order->net_total - $order->credit_amount;
                    }
                    
                    if(!$order->visit_date || date('Y-m-d',strtotime($order->created)) == date('Y-m-d',strtotime($order->visit_date)))
                    {
                        if($order->payment_type == 'cash') $csh = $order->net_total;
                        if($order->payment_type == 'credit') $crd = $order->net_total;
                    }
                    
                    
                    if($order->visit == 1 && empty($order->last_status_date))
                    {
                        $stats[$order->location_id]['new']['count'] = isset($stats[$order->location_id]['new']['count'])? $stats[$order->location_id]['new']['count'] + 1:1;
//                        $all['new']['count'] = isset($all['new']['count'])? $all['new']['count'] + 1:1;
                        
                        $stats[$order->location_id]['new']['cash'] = isset($stats[$order->location_id]['new']['cash'])? $stats[$order->location_id]['new']['cash'] + $csh: $csh;
                        $stats[$order->location_id]['new']['crd'] = isset($stats[$order->location_id]['new']['crd'])? $stats[$order->location_id]['new']['crd'] + $crd: $crd;
                        
                        
                    }
                    else 
                    {
                        $stats[$order->location_id]['ex']['count'] = isset($stats[$order->location_id]['ex']['count'])? $stats[$order->location_id]['ex']['count'] + 1:1;
//                        $all['ex']['count'] = isset($all['ex']['count'])? $all['ex']['count'] + 1:1;
                        
                        $stats[$order->location_id]['ex']['cash'] = isset($stats[$order->location_id]['ex']['cash'])? $stats[$order->location_id]['ex']['cash'] + $csh: $csh;
                        $stats[$order->location_id]['ex']['crd'] = isset($stats[$order->location_id]['ex']['crd'])? $stats[$order->location_id]['ex']['crd'] + $crd: $crd;
                        
                        if($order->status == 3)
                        {
                            $stats[$order->location_id]['so'] = isset($stats[$order->location_id]['so'])?$stats[$order->location_id]['so'] + 1 : 1;
                        }
                    }
                    
                    if($order->is_med == 1)$stats[$order->location_id]['med'] = isset($stats[$order->location_id]['med'])? $stats[$order->location_id]['med'] + 1: 1;
                    if($order->visit && $order->is_med == 0)$stats[$order->location_id]['nomed'] = isset($stats[$order->location_id]['nomed'])? $stats[$order->location_id]['nomed'] + 1: 1;
                    
                    if($order->is_med == 1)
                    {
                        $medsForId = $this->config->item('meds_for_id');
                        if(!empty($order->med1))
                        {
                            $med1 = $medsForId[$order->med1];
                            $stats[$order->location_id][$med1] = isset($stats[$order->location_id][$med1])? $stats[$order->location_id][$med1]+($order->med_days * $order->meds_per_day): ($order->med_days * $order->meds_per_day); 
                        }
                        if(!empty($order->med2))
                        {
                            $med2 = $medsForId[$order->med2];
                            $stats[$order->location_id][$med2] = isset($stats[$order->location_id][$med2])? $stats[$order->location_id][$med2]+($order->med_days * $order->meds_per_day): ($order->med_days * $order->meds_per_day); 
                        }
                        if(!empty($order->med3))
                        {
                            $med3 = $medsForId[$order->med3];
                            $stats[$order->location_id][$med3] = isset($stats[$order->location_id][$med3])? $stats[$order->location_id][$med3]+($order->med_days * $order->meds_per_day): ($order->med_days * $order->meds_per_day); 
                        }
                    }
                }
                
                $this->data['stats'] = $stats;   
            }
        }
        
        $sg = FALSE;
        $this->data['sg'] = $sg;
        $this->data['mig'] = $this->config->item('mig');
        
        $this->data['locations'] = $this->location->getAll(TRUE);
        
        $this->load->view('home/stats',$this->data);
    }
    
    public function saveB12()
    {
        $post = $this->input->post();
        $this->patient->updateB12Val($post);
    }
    
    public function statpdf($location_id,$start,$end)
    { 
        $sg = FALSE;
        $rawLogs = $stats = $orders = $injs = array();
        if($end != 'NULL')
        {
            $rawLogs = $this->patient->getForStatPDF($location_id,$start,$end);
        }
        else 
        {
           $rawLogs = $this->patient->getForStatPDFStaff($location_id,$start); 
        }
        
        $user = $this->session->userdata('user');
        
        if($user->type == 1)
        {
            $this->data['start'] = $start;
            $this->data['end'] = $end;
            $orders = $this->patient->getStatsForDashboard($start,$end,$location_id);
            $injs = $this->getInjUsage($start, $end);
            
            if($start == $end)
            {
                $sg = TRUE;
                $this->data['wd'] = date('w', strtotime($start));
            }
        }
        else
        {
            $this->data['date'] = $start;
            $orders = $this->patient->getStatsForDashboardStaff($start,$location_id);
            $injs = $this->getInjUsage($start, $start);
            $sg = TRUE;
            $this->data['wd'] = date('w', strtotime($start));
            $end = $start;
        }
        
               
        $this->data['logs'] = $rawLogs;
        $medInfo = $this->config->item('meds_for_id');
        $this->data['medinfo'] = $medInfo;
        $this->data['location_id'] = $location_id;
        
        foreach($rawLogs as &$log)
        {
            $injecs = $this->getPatientInjUsage($start,$end,$log->patient_id,$location_id);
            $log->injs = $injecs;
        }
        
        foreach($orders as $order)
        {
            $crd = $csh = 0;

            if($order->payment_type == 'mix')
            {
                $crd = $order->credit_amount;
                $csh = $order->net_total - $order->credit_amount;
            }
            
            if(!$order->visit_date || date('Y-m-d',strtotime($order->created)) == date('Y-m-d',strtotime($order->visit_date)))
            {
                if($order->payment_type == 'cash') $csh = $order->net_total;
                if($order->payment_type == 'credit') $crd = $order->net_total;
            }

            if($order->visit == 1 && empty($order->last_status_date))
            {
                $stats[$order->location_id]['new']['count'] = isset($stats[$order->location_id]['new']['count'])? $stats[$order->location_id]['new']['count'] + 1:1;
                $stats[$order->location_id]['new']['cash'] = isset($stats[$order->location_id]['new']['cash'])? $stats[$order->location_id]['new']['cash'] + $csh: $csh;
                $stats[$order->location_id]['new']['crd'] = isset($stats[$order->location_id]['new']['crd'])? $stats[$order->location_id]['new']['crd'] + $crd: $crd;
            }
            else 
            {
                $stats[$order->location_id]['ex']['count'] = isset($stats[$order->location_id]['ex']['count'])? $stats[$order->location_id]['ex']['count'] + 1:1;
                $stats[$order->location_id]['ex']['cash'] = isset($stats[$order->location_id]['ex']['cash'])? $stats[$order->location_id]['ex']['cash'] + $csh: $csh;
                $stats[$order->location_id]['ex']['crd'] = isset($stats[$order->location_id]['ex']['crd'])? $stats[$order->location_id]['ex']['crd'] + $crd: $crd;
                
                if($order->status == 3)
                {
                    $stats[$order->location_id]['so'] = isset($stats[$order->location_id]['so'])?$stats[$order->location_id]['so'] + 1 : 1;
                }
                
            }

            if($order->is_med == 1)$stats[$order->location_id]['med'] = isset($stats[$order->location_id]['med'])? $stats[$order->location_id]['med'] + 1: 1;
            if($order->visit && $order->is_med == 0)$stats[$order->location_id]['nomed'] = isset($stats[$order->location_id]['nomed'])? $stats[$order->location_id]['nomed'] + 1: 1;

            if($order->is_med == 1)
            {
                $medsForId = $this->config->item('meds_for_id');
                if(!empty($order->med1))
                {
                    $med1 = $medsForId[$order->med1];
                    $stats[$order->location_id][$med1] = isset($stats[$order->location_id][$med1])? $stats[$order->location_id][$med1]+($order->med_days * $order->meds_per_day): ($order->med_days * $order->meds_per_day); 
                }
                if(!empty($order->med2))
                {
                    $med2 = $medsForId[$order->med2];
                    $stats[$order->location_id][$med2] = isset($stats[$order->location_id][$med2])? $stats[$order->location_id][$med2]+($order->med_days * $order->meds_per_day): ($order->med_days * $order->meds_per_day); 
                }
                if(!empty($order->med3))
                {
                    $med3 = $medsForId[$order->med3];
                    $stats[$order->location_id][$med3] = isset($stats[$order->location_id][$med3])? $stats[$order->location_id][$med3]+($order->med_days * $order->meds_per_day): ($order->med_days * $order->meds_per_day); 
                }
            }
        }
        
        $this->data['sg'] = FALSE;//$sg;
        $this->data['mig'] = $this->config->item('mig');         
        $this->data['injs'] = isset($injs[$location_id])?$injs[$location_id]:array();
        
        $this->data['stat'] = $stats[$location_id];  
        
                
        $html = $this->load->view('home/statpdf',  $this->data,TRUE);
        
        create_mp_logs($html);
    }
    
    public function inventory()
    {
        $this->data['bc1'] = 'Dashboard';
	$this->data['bc2'] = 'Inventory';
        
        $locations = $this->location->getAll(TRUE);
        $this->data['locations'] = $locations;
        
        $sp = $this->product->getAllStockPros();
        foreach($sp as &$p)
        {
            $ps = $this->product->getProInv($p->id);
            $locs = array();
            foreach($ps as $s)
            {
                $locs[$s->location_id] = array('qty'=>$s->quantity,'msl'=>$s->min_stock_lvl);
            }
            $p->ls = $locs;
        }
        
        $this->data['products'] = $sp;
        
        $this->load->view('home/inventory',$this->data);
    }
    
    public function printinv()
    {
        $locations = $this->location->getAll(TRUE);
        $this->data['locations'] = $locations;
        
        $sp = $this->product->getAllStockPros();
        foreach($sp as &$p)
        {
            $ps = $this->product->getProInv($p->id);
            $locs = array();
            foreach($ps as $s)
            {
                $locs[$s->location_id] = array('qty'=>$s->quantity,'msl'=>$s->min_stock_lvl);
            }
            $p->ls = $locs;
        }
        
        $this->data['products'] = $sp;
        
        $html = $this->load->view('home/printinv',  $this->data,TRUE);
        
        create_mp_logs($html);
    }
    
    public function compare()
    {
        $this->data['bc1'] = 'Dashboard';
	$this->data['bc2'] = 'Compare';
        
        $locations = $this->location->getAll(TRUE);
        $this->data['locations'] = $locations;
        
        if($this->input->server('REQUEST_METHOD') === 'POST')
    	{ 
            $post = $this->input->post();
            $compare = array();
            
            if(isset($post['com_loc']))
            {
                $this->form_validation->set_rules('start', 'Start Date', 'trim|required');
                $this->form_validation->set_rules('end', 'End Date', 'trim|required');
                $this->form_validation->set_rules('loc_1','Location 1', 'trim|required|differs[loc_2]');
                $this->form_validation->set_rules('loc_2','Location 2', 'trim|required');
                
                if($this->form_validation->run() == TRUE)
                {
                    $visits = $this->patient->getStatsForDashboard($post['start'],$post['end'],$post['loc_1'].",".$post['loc_2']);
                    
                    foreach($visits as $visit)
                    {
                        if($visit->location_id == $post['loc_1'])
                        {
                            $compare['loc_1']['count'] = isset($compare['loc_1']['count'])? $compare['loc_1']['count'] + 1 : 1;
                            if($visit->payment_type == 'cash')
                            {
                                $compare['loc_1']['cash'] = isset($compare['loc_1']['cash'])? $compare['loc_1']['cash'] + $visit->net_total : $visit->net_total;
                            }
                            else 
                            {
                                $compare['loc_1']['crd'] = isset($compare['loc_1']['crd'])? $compare['loc_1']['crd'] + $visit->net_total : $visit->net_total;
                            }
                        }
                        elseif($visit->location_id == $post['loc_2']) 
                        {
                            $compare['loc_2']['count'] = isset($compare['loc_2']['count'])? $compare['loc_2']['count'] + 1 : 1;
                            if($visit->payment_type == 'cash')
                            {
                                $compare['loc_2']['cash'] = isset($compare['loc_2']['cash'])? $compare['loc_2']['cash'] + $visit->net_total : $visit->net_total;
                            }
                            else 
                            {
                                $compare['loc_2']['crd'] = isset($compare['loc_2']['crd'])? $compare['loc_2']['crd'] + $visit->net_total : $visit->net_total;
                            }
                        }
                    }
                    
                    $this->data['loc_1'] = $post['loc_1'];
                    $this->data['loc_2'] = $post['loc_2'];
                    
                    $this->data['locc'] = $compare;
                }
            }
            elseif(isset ($post['com_date']))
            {
                $this->form_validation->set_rules('d1_start', 'Date 1 start', 'trim|required');
                $this->form_validation->set_rules('d2_start', 'Date 2 start', 'trim|required');
                $this->form_validation->set_rules('d1_end', 'Date 1 End', 'trim|required');
                $this->form_validation->set_rules('d2_end', 'Date 2 End', 'trim|required');
                $this->form_validation->set_rules('location','Location', 'trim|required');
                
                if($this->form_validation->run() == TRUE)
                {
                    $visits = $this->patient->getStatsForDashboard($post['d1_start'],$post['d1_end'],$post['location']);                    
                    foreach($visits as $visit)
                    {
                        $compare['dr1']['count'] = isset($compare['dr1']['count'])? $compare['dr1']['count'] + 1 : 1;
                        if($visit->payment_type == 'cash')
                        {
                            $compare['dr1']['cash'] = isset($compare['dr1']['cash'])? $compare['dr1']['cash'] + $visit->net_total : $visit->net_total;
                        }
                        else 
                        {
                            $compare['dr1']['crd'] = isset($compare['dr1']['crd'])? $compare['dr1']['crd'] + $visit->net_total : $visit->net_total;
                        }
                    }
                    
                    $visits = $this->patient->getStatsForDashboard($post['d2_start'],$post['d2_end'],$post['location']);                    
                    foreach($visits as $visit)
                    {
                        $compare['dr2']['count'] = isset($compare['dr2']['count'])? $compare['dr2']['count'] + 1 : 1;
                        if($visit->payment_type == 'cash')
                        {
                            $compare['dr2']['cash'] = isset($compare['dr2']['cash'])? $compare['dr2']['cash'] + $visit->net_total : $visit->net_total;
                        }
                        else 
                        {
                            $compare['dr2']['crd'] = isset($compare['dr2']['crd'])? $compare['dr2']['crd'] + $visit->net_total : $visit->net_total;
                        }
                    }
                    
                    $this->data['dr1'] = $post['d1_start']." - ".$post['d1_end'];
                    $this->data['dr2'] = $post['d2_start']." - ".$post['d2_end'];
                    
                    $this->data['datec'] = $compare;
                
                }
            }
            
            
        }
        $this->load->view('home/compare',$this->data);
    }
    
    public function updatemsl()
    {
        $post = $this->input->post();
        $result = array();
        if($post['value'] > 0)
        {
            $psl = $this->product->getProlocStock($post['pk'],$post['name']);

            if($psl)
            {
                $this->product->updateProLocMinStock($post['pk'],$post['name'],$post['value']);
            }
            else
            {
                $this->product->addProLocStock(array('product_id'=>$post['pk'],'location_id'=>$post['name'],'min_stock_lvl'=>$post['value']));
            }
            $result['success'] = TRUE;
        }
        else
        {
            $result['success'] = FALSE;
            $result['msg'] = 'Should be positive integer.';
            $result['newValue'] = 0;
        }
        
        echo json_encode($result);
    }
    
    public function updatePS()
    {
        $post = $this->input->post();
        $result = array();
        if($post['value'] > 0)
        {
            $psl = $this->product->getProlocStock($post['pk'],$post['name']);

            if($psl)
            {
                $this->product->updateProLocStock($post['pk'],$post['name'],$post['value']);
            }
            else
            {
                $this->product->addProLocStock(array('product_id'=>$post['pk'],'location_id'=>$post['name'],'quantity'=>$post['value']));
            }
            $result['success'] = TRUE;
        }
        else
        {
            $result['success'] = FALSE;
            $result['msg'] = 'Should be positive integer.';
            $result['newValue'] = 0;
        }
        
        echo json_encode($result);
    }
    
    public function updateMS()
    {
        $post = $this->input->post();
        $result = array();
        if($post['value'] > 0)
        {
            
            $this->product->update($post['pk'],array('stock'=>$post['value']));
            
            $result['success'] = TRUE;
        }
        else
        {
            $result['success'] = FALSE;
            $result['msg'] = 'Should be positive integer.';
            $result['newValue'] = 0;
        }
        
        echo json_encode($result);
    }
    
    public function test()
    {
              $this->data['bc1'] = 'Dashboard';
	$this->data['bc2'] = 'Stats';
        $prefs = array(
        'show_next_prev'  => TRUE,
        'next_prev_url'   => 'http://example.com/index.php/calendar/show/'
        );
        $this->load->library('calendar',$prefs);
        $this->load->view('home/cron',$this->data);
    }
    
    public function queue()
    {
        $this->data['bc1'] = 'Queue';
	$this->data['bc2'] = 'Today';
        
        $queue = $this->queue->getTodayQueue();
        $this->data['queue'] = $queue;
        
        $this->load->view('queue/view',$this->data);
    }
    
    private function getPatientInjUsage($start,$end,$patient_id,$location_id)
    {
        $injIds = array(5,18,41,107,108,109,113,115);
        $injStr = implode(',', $injIds);
        $orders = $this->patient->getPatientInjSaleForDuration($start,$end, $injStr,$patient_id,$location_id);
        
        $stats = $b12 = array();        
        $injForId = array("5" => "B-12","18" => "Lipogen","41" => "Ultraburn","107" => "Glutathione", "108"=>"AminoBlend","109" => "StressBuster","113" => "VitD3","115" => "Biotin");

        foreach($orders as $order)
        {                    
            if(!is_null($order->stock_item) && $order->stock_item > 0)
            {
                $inj = $injForId[$order->stock_item];
                if($order->stock_item == 5 && $order->quantity == 2)
                {
                    $stats['b121cc'] = isset($stats['b121cc'])? $stats['b121cc']+ 1 : 1; 
                }
                elseif($order->stock_item == 5 && $order->quantity == 1)
                {
                    $stats[$inj] = isset($stats[$inj])? $stats[$inj]+ $order->quantity : $order->quantity; 
                    $created = date('Y-m-d',strtotime($order->created));
                                        
                    if(isset($b12[$created][$order->patient_id]))
                    {
                        $stats['b121cc'] = isset($stats['b121cc'])? $stats['b121cc']+ 1 : 1; 
                        $stats[$inj] = $stats[$inj] > 1 ? $stats[$inj] - 2 : 0;
                        unset($b12[$created][$order->patient_id]);
                    }
                    else
                    {
                        $b12[$created][$order->patient_id] = 1;
                    }
                }
                else
                {
                    $stats[$inj] = isset($stats[$inj])? $stats[$inj]+ $order->quantity : $order->quantity; 
                }                

                if($order->is_combo == 1 && !is_null($order->combo_item) && $order->combo_item > 0)
                {
                    $injj = $injForId[$order->combo_item];
                    $stats[$injj] = isset($stats[$injj])? $stats[$injj]+ $order->quantity : $order->quantity; 
                }
            }
            else 
            {
                $inj = $injForId[$order->product_id];
                
                if($order->product_id == 5 && $order->quantity == 2)
                {
                    $stats['b121cc'] = isset($stats['b121cc'])? $stats['b121cc']+ 1 : 1; 
                }
                elseif($order->product_id == 5 && $order->quantity == 1)
                {
                    $stats[$inj] = isset($stats[$inj])? $stats[$inj]+ $order->quantity : $order->quantity; 
                    $created = date('Y-m-d',strtotime($order->created));
                    
                    if(isset($b12[$created][$order->patient_id]))
                    {
                        $stats['b121cc'] = isset($stats['b121cc'])? $stats['b121cc']+ 1 : 1; 
                        $stats[$inj] = $stats[$inj] > 1 ? $stats[$inj] - 2 : 0;
                        unset($b12[$created][$order->patient_id]);
                    }
                    else
                    {
                        $b12[$created][$order->patient_id] = 1;
                    }
                }
                else
                {
                    $stats[$inj] = isset($stats[$inj])? $stats[$inj]+ $order->quantity : $order->quantity;                   
                }
            }
        }
             
        return $stats;
        
    }
    
    private function getInjUsage($start,$end)
    {
        $injIds = array(5,18,41,107,108,109,113,115);
        $orders = $this->patient->getInjSaleForDuration($start,$end, implode(',', $injIds));

        $stats = $b12 = array();
        $injForId = array("5" => "B-12","18" => "Lipogen","41" => "Ultraburn","107" => "Glutathione", "108"=>"AminoBlend","109" => "StressBuster","113" => "VitD3","115" => "Biotin");

        foreach($orders as $order)
        {                    
            if(!is_null($order->stock_item) && $order->stock_item > 0)
            {
                $inj = $injForId[$order->stock_item];
                if($order->stock_item == 5 && $order->quantity == 2)
                {
                    $stats[$order->location_id]['b121cc'] = isset($stats[$order->location_id]['b121cc'])? $stats[$order->location_id]['b121cc']+ 1 : 1; 
                }
                elseif($order->stock_item == 5 && $order->quantity == 1)
                {
                    $stats[$order->location_id][$inj] = isset($stats[$order->location_id][$inj])? $stats[$order->location_id][$inj]+ $order->quantity : $order->quantity; 
                    $created = date('Y-m-d',strtotime($order->created));
                                        
                    if(isset($b12[$created][$order->patient_id]))
                    {
                        $stats[$order->location_id]['b121cc'] = isset($stats[$order->location_id]['b121cc'])? $stats[$order->location_id]['b121cc']+ 1 : 1; 
                        $stats[$order->location_id][$inj] = $stats[$order->location_id][$inj] > 1 ? $stats[$order->location_id][$inj] - 2 : 0;
                        unset($b12[$created][$order->patient_id]);
                    }
                    else
                    {
                        $b12[$created][$order->patient_id] = 1;
                    }
                }
                else
                {
                    $stats[$order->location_id][$inj] = isset($stats[$order->location_id][$inj])? $stats[$order->location_id][$inj]+ $order->quantity : $order->quantity; 
                }                

                if($order->is_combo == 1 && !is_null($order->combo_item) && $order->combo_item > 0)
                {
                    $injj = $injForId[$order->combo_item];
                    $stats[$order->location_id][$injj] = isset($stats[$order->location_id][$injj])? $stats[$order->location_id][$injj]+ $order->quantity : $order->quantity; 
                }
            }
            else 
            {
                $inj = $injForId[$order->product_id];
                
                if($order->product_id == 5 && $order->quantity == 2)
                {
                    $stats[$order->location_id]['b121cc'] = isset($stats[$order->location_id]['b121cc'])? $stats[$order->location_id]['b121cc']+ 1 : 1; 
                }
                elseif($order->product_id == 5 && $order->quantity == 1)
                {
                    $stats[$order->location_id][$inj] = isset($stats[$order->location_id][$inj])? $stats[$order->location_id][$inj]+ $order->quantity : $order->quantity; 
                    $created = date('Y-m-d',strtotime($order->created));
                    
                    if(isset($b12[$created][$order->patient_id]))
                    {
                        $stats[$order->location_id]['b121cc'] = isset($stats[$order->location_id]['b121cc'])? $stats[$order->location_id]['b121cc']+ 1 : 1; 
                        $stats[$order->location_id][$inj] = $stats[$order->location_id][$inj] > 1 ? $stats[$order->location_id][$inj] - 2 : 0;
                        unset($b12[$created][$order->patient_id]);
                    }
                    else
                    {
                        $b12[$created][$order->patient_id] = 1;
                    }
                }
                else
                {
                    $stats[$order->location_id][$inj] = isset($stats[$order->location_id][$inj])? $stats[$order->location_id][$inj]+ $order->quantity : $order->quantity;                   
                }
            }
        }
             
        return $stats;
    }
}
