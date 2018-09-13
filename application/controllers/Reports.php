<?php

/**
 * Description of Reports
 *
 * @author Udana
 */
class Reports extends Admin_Controller
{
    public function __construct() 
    {
        parent::__construct();
        
        $this->load->model('Location_model','location');
        $this->load->model('marketing_model','marketing');
    }
    
    public function index()
    {
        $this->data['bc1'] = 'Reports';
	$this->data['bc2'] = 'Graphical Reports';
        
        $this->load->view('reports/index',$this->data);
    }
    
    public function getPatientLocation()
    {
        $result = array();
        if($this->input->server('REQUEST_METHOD') === 'POST')
    	{  
            $this->form_validation->set_rules('start', 'Start Date', 'trim|required');
            $this->form_validation->set_rules('end', 'End Date', 'trim|required');
            
            if($this->form_validation->run() == TRUE)
	    {
                $post = $this->input->post();
                $orders = $this->patient->getStatsForDashboardSR($post['start'],$post['end']);
                
                $stats = array();
                
                foreach($orders as $order)
                {
                    if($order->visit == 1 && empty($order->last_status_date))
                    {
                        $stats[$order->location_id]['new'] = isset($stats[$order->location_id]['new'])? $stats[$order->location_id]['new'] + 1:1;
                    }
                    else 
                    {
                        $stats[$order->location_id]['ex'] = isset($stats[$order->location_id]['ex'])? $stats[$order->location_id]['ex'] + 1:1;
                    }
                }
                
                $locations = $this->location->getAll(TRUE);
                
                if(count($stats)>0)
                {
                    $temp = $graph = $locs = array();
                    foreach($locations as $loc)
                    {
                        $locs[] = $loc->name;
                        $new = isset($stats[$loc->id]['new'])? $stats[$loc->id]['new']:0;
                        $ex = isset($stats[$loc->id]['ex'])? $stats[$loc->id]['ex']:0;
                        $temp['new'][] = $new;
                        $temp['ex'][] = $ex;
                        $temp['all'][] = $new + $ex;                        
                    }
                    $graph[] = array('name'=>"New",'data'=>$temp['new']);
                    $graph[] = array('name'=>"Existing",'data'=>$temp['ex']);
                    $graph[] = array('name'=>"All",'data'=>$temp['all']);
                    
                    $result['status'] = 'success';
                    $result['graph'] = $graph;
                    $result['locs'] = $locs;
                    $result['subtitle'] = date('m/d/Y',strtotime($post['start']))." - ".date('m/d/Y',strtotime($post['end']));
                    
                }
                else
                {
                    $result['status'] = 'error';
                    $result['errors'] = '<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12" style="padding:0 5px;margin:10px 0px 0px 0px;"><div style="padding:5px;margin:0px;" role="alert" class="alert fresh-color alert-danger"><strong>No data available in system</strong></div></div>';            
                }
            }
            else 
            {
                $result['status'] = 'error';
                $result['errors'] = validation_errors('<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12" style="padding:0 5px;margin:10px 0px 0px 0px;"><div style="padding:5px;margin:0px;" role="alert" class="alert fresh-color alert-danger"><strong>','</strong></div></div>');
            }
        }
        
        echo json_encode($result);
    }
    
    public function getMedsLocation()
    {
        $result = array();
        if($this->input->server('REQUEST_METHOD') === 'POST')
    	{  
            $this->form_validation->set_rules('start', 'Start Date', 'trim|required');
            $this->form_validation->set_rules('end', 'End Date', 'trim|required');
            
            if($this->form_validation->run() == TRUE)
	    {
                $post = $this->input->post();
                $orders = $this->patient->getStatsForDashboardSR($post['start'],$post['end']);
                
                $stats = array();
                $medsForId = $this->config->item('meds_for_id');
                foreach($orders as $order)
                {
                    if($order->is_med == 1)
                    {
                        
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
                
                
                $locations = $this->location->getAll(TRUE);
                
                if(count($stats)>0)
                {
                    $temp = $graph = $locs = array();
                    foreach($locations as $loc)
                    {
                        $locs[] = $loc->name;
                        
                        foreach($medsForId as $med)
                        {
                            $temp[$med][] = isset($stats[$loc->id][$med])? round($stats[$loc->id][$med]/7):0;
                        }                        
                    }
                    
                    foreach($medsForId as $med)
                    {
                        $graph[] = array('name'=>$med,'data'=>$temp[$med]);
                    }
                    
                    $result['status'] = 'success';
                    $result['graph'] = $graph;
                    $result['locs'] = $locs;
                    $result['subtitle'] = date('m/d/Y',strtotime($post['start']))." - ".date('m/d/Y',strtotime($post['end']));
                }
                else
                {
                    $result['status'] = 'error';
                    $result['errors'] = '<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12" style="padding:0 5px;margin:10px 0px 0px 0px;"><div style="padding:5px;margin:0px;" role="alert" class="alert fresh-color alert-danger"><strong>No data available in system</strong></div></div>';            
                }
            }
            else 
            {
                $result['status'] = 'error';
                $result['errors'] = validation_errors('<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12" style="padding:0 5px;margin:10px 0px 0px 0px;"><div style="padding:5px;margin:0px;" role="alert" class="alert fresh-color alert-danger"><strong>','</strong></div></div>');
            }
        }
        echo json_encode($result);
    }
    
    public function getInjLocation()
    {
        $result = array();
        if($this->input->server('REQUEST_METHOD') === 'POST')
    	{  
            $this->form_validation->set_rules('start', 'Start Date', 'trim|required');
            $this->form_validation->set_rules('end', 'End Date', 'trim|required');
            
            if($this->form_validation->run() == TRUE)
	    {
                $post = $this->input->post();
                $injIds = array(5,18,41);
                $orders = $this->patient->getInjSaleForDuration($post['start'],$post['end'], implode(',', $injIds));
                                
                $stats = array();
                $injForId = array(
                            "5" => "B-12",
                            "18" => "Lipogen",
                            "41" => "Ultraburn"
                          );
                
                foreach($orders as $order)
                {                    
                    if(!is_null($order->stock_item) && $order->stock_item > 0)
                    {
                        $inj = $injForId[$order->stock_item];
                        $stats[$order->location_id][$inj] = isset($stats[$order->location_id][$inj])? $stats[$order->location_id][$inj]+ $order->quantity : $order->quantity; 
                        
                        if($order->is_combo == 1 && !is_null($order->combo_item) && $order->combo_item > 0)
                        {
                            $injj = $injForId[$order->combo_item];
                            $stats[$order->location_id][$injj] = isset($stats[$order->location_id][$injj])? $stats[$order->location_id][$injj]+ $order->quantity : $order->quantity; 
                        }
                    }
                    else 
                    {
                        $inj = $injForId[$order->product_id];
                        $stats[$order->location_id][$inj] = isset($stats[$order->location_id][$inj])? $stats[$order->location_id][$inj]+ $order->quantity : $order->quantity;                   
                    }
                }
                
                
                $locations = $this->location->getAll(TRUE);
                
                if(count($stats)>0)
                {
                    $temp = $graph = $locs = array();
                    foreach($locations as $loc)
                    {
                        $locs[] = $loc->name;
                        
                        foreach($injForId as $inj)
                        {
                            $temp[$inj][] = isset($stats[$loc->id][$inj])? $stats[$loc->id][$inj]:0;
                        }                        
                    }
                    
                    foreach($injForId as $inj)
                    {
                        $graph[] = array('name'=>$inj,'data'=>$temp[$inj]);
                    }
                    
                    $result['status'] = 'success';
                    $result['graph'] = $graph;
                    $result['locs'] = $locs;
                    $result['subtitle'] = date('m/d/Y',strtotime($post['start']))." - ".date('m/d/Y',strtotime($post['end']));
                }
                else
                {
                    $result['status'] = 'error';
                    $result['errors'] = '<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12" style="padding:0 5px;margin:10px 0px 0px 0px;"><div style="padding:5px;margin:0px;" role="alert" class="alert fresh-color alert-danger"><strong>No data available in system</strong></div></div>';            
                }
            }
            else 
            {
                $result['status'] = 'error';
                $result['errors'] = validation_errors('<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12" style="padding:0 5px;margin:10px 0px 0px 0px;"><div style="padding:5px;margin:0px;" role="alert" class="alert fresh-color alert-danger"><strong>','</strong></div></div>');
            }
        }
        echo json_encode($result);
    }
    
    public function getPastDue()
    {
        $result = array();
        if($this->input->server('REQUEST_METHOD') === 'POST')
    	{  
            $this->form_validation->set_rules('no_of_days', 'No of days', 'trim|required|is_natural_no_zero');
            
            if($this->form_validation->run() == TRUE)
	    {
                $post = $this->input->post();
                $nfd = $post['no_of_days'];
                $nfd_date = date('Y-m-d',strtotime("-$nfd days"));
                $this->data['pds'] = $this->patient->getPastDuePatients($nfd_date);
                
                $result['status'] = 'success';
                $this->data['title'] = "Patients No show after ".date("m/d/Y",strtotime($nfd_date));
                $result['html'] = $this->load->view('reports/_pd',$this->data,TRUE);
            }
            else 
            {
                $result['status'] = 'error';
                $result['errors'] = validation_errors('<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12" style="padding:0 5px;margin:10px 0px 0px 0px;"><div style="padding:5px;margin:0px;" role="alert" class="alert fresh-color alert-danger"><strong>','</strong></div></div>');
            }
        }
        echo json_encode($result);
    }
    
    public function pastDuePDF($nfd)
    {
        $nfd = date('Y-m-d',strtotime("-$nfd days"));
        $this->data['pds'] = $this->patient->getPastDuePatients($nfd);

        $this->data['title'] = "Patients No show after ".date("m/d/Y",strtotime($nfd));
        
        $html = $this->load->view('reports/_pd_pdf',$this->data,true);
        
        create_mp_ticket($html);
    }
    
    public function createCustomList()
    {
        $result = array();
        if($this->input->server('REQUEST_METHOD') === 'POST')
    	{  
            $this->form_validation->set_rules('name', 'List Name', 'trim|required');
            
            if($this->form_validation->run() == TRUE)
	    {
                $post = $this->input->post();
                $from = $post['from'];
                 $fromd = date('Y-m-d',strtotime("-$from days"));
                $pds = $this->patient->getPastDuePatients($fromd);
                
                $list = array();
                $list['created'] = date('Y-m-d');
                $list['name'] = $post['name'];
                $cl_id = $this->marketing->addCL($list);
                
                if(!empty($pds))
                {                    
                    $data = array();

                    foreach($pds as $pd)
                    {
                        $temp = array();
                        $temp['cl_id'] = $cl_id;
                        $temp['patient_id'] = $pd->patient_id;
                        array_push($data, $temp);
                    }
                    $this->marketing->addCLMemBatch($data);
                    
                    $result['status'] = 'success';
                    $result['msg'] ='<div class="col-xs-12" style="padding:0 5px;margin:10px 0px 0px 0px;"><div style="padding:5px;margin:0px;" role="alert" class="alert fresh-color alert-success"><strong>Custom list created successfully.</strong></div></div>';                            
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
    
        
    public function salesActivity($pid,$start,$end)
    {
        $patient = $this->patient->getPatient($pid);
        $this->data['patient'] = $patient;
        $orders = $this->order->getPatientOrdersForPeriod($pid,$start,$end);
        
        foreach($orders as &$order)
        {
            $order->items = $this->order->getOrderItemsWithNames($order->id);
        }
        
        $this->data['orders'] = $orders;
        
        $html = $this->load->view('reports/sales_activity_pdf',  $this->data,TRUE);
        
        create_mp_ticket($html);
    }
}
