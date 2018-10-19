<?php
/**
 * Description of Injmeds
 *
 * @author Udana
 */
class Injmeds extends Admin_Controller
{
    public function __construct() {
        parent::__construct();
        
        $this->load->model('Schedule_model','schedule');
        $this->load->model('Location_model','location');
        $this->load->model('User_model','user');
        $this->load->model('Injmeds_model','injmeds');
    }
    
    public function index()
    {
        
        $this->data['bc1'] = 'Inj / Meds';
	$this->data['bc2'] = 'Reports';
        $add = FALSE;
        
        $this->data['locations'] = $this->location->getAll(TRUE);
        $injmedsarr = $this->config->item('injmeds');
        $this->data['imarr'] = $injmedsarr;
        
        $injmedspro = $this->config->item('injmeds_pro');
        $this->data['impro'] = $injmedspro;
        
        if($id = $this->input->get('id'))
        {
            $locWeek = $this->injmeds->getById($id);
            $this->data["week"] = $locWeek->week;
            $this->data["loc_id"] = $locWeek->location_id;
            $this->data["loc_week"] = $locWeek;
            $weekdays = getWeekDatys($locWeek->week);
            $this->data['weekdays'] = $weekdays;
        }
        
        if($this->input->server('REQUEST_METHOD') === 'POST')
    	{
            $user = $this->session->userdata('user');
            
            if($user->type == 1)
            $this->form_validation->set_rules('week', 'Select Monday', 'trim|required');
            
            $this->form_validation->set_rules('location', 'Location', 'trim');
            
            $post = $this->input->post();
            
            if($this->form_validation->run() == TRUE)
	    {
                if($user->type != 1)$post['week'] = getWeekStartDate ();
                
                $locWeek = $this->injmeds->getLocationWeek($post['location'],$post['week']);
                $this->data["week"] = $post['week'];
                $this->data["loc_id"] = $post['location'];
                $this->data["loc_week"] = $locWeek;
                
                
                if(!$locWeek)
                {
                    $add = TRUE;
                }
                else
                {
                    $weekdays = getWeekDatys($locWeek->week);
                    $this->data['weekdays'] = $weekdays;
                }
                
            }
        }
        
        $this->data['add'] = $add;
        
        $this->load->view('injmeds/index',$this->data);
    }
    
    public function view()
    {
        $this->data['bc1'] = 'Inj / Meds';
	$this->data['bc2'] = 'Reports';
        $add = FALSE;
        
        $this->data['locations'] = $this->location->getAll(TRUE);
        $injmedsarr = $this->config->item('injmeds');
        $this->data['imarr'] = $injmedsarr;
        $this->data['staff'] = $this->user->getUserByType(array(2,4));
        
        $injmedspro = $this->config->item('injmeds_pro');
        $this->data['impro'] = $injmedspro;
        
        if($this->input->server('REQUEST_METHOD') === 'POST')
    	{
            $user = $this->session->userdata('user');
            
            if($user->type == 1)
            $this->form_validation->set_rules('week', 'Select Monday', 'trim|required');
            
            $this->form_validation->set_rules('loc_id', 'Location', 'trim');
            
            $post = $this->input->post();
            
            if($this->form_validation->run() == TRUE)
	    {
                $miData = $this->getMIdata($post['loc_id'], $post['week']);
                $this->data['mis'] = $miData;
                $this->data += $post;
            }
        }
        
        $this->load->view('injmeds/view',$this->data);
    }
    
    private function getMIdata($loc_id,$start)
    {
        $injmedsarr = $this->config->item('injmeds');
        $this->data['imarr'] = $injmedsarr;
        
        $days = getWeekDatys($start);
        $ims = $pros = $meds = $injs = array();
        $res = $this->injmeds->getInjMeds($loc_id,$days[1],$days[6]);       
        
        foreach($res as $r)
        {
            $pros[$r->date] = $r;
        }
        
        foreach($injmedsarr as $key=>$val)
        {
            foreach($days as $in=>$day)
            {
                $ou = 0;
                if(substr($key,0,3) == 'med')
                {
                    $me = array();
                    if(isset($meds[$day]))
                    {
                        $me = $meds[$day];
                    }
                    else 
                    {
                        $me = getMedUsage($loc_id,$day);
                        $meds[$day] = $me;
                    }
                    
                    $ou = isset($me[$key])? round($me[$key]/7):0;
                }
                
                if(substr($key,0,3) == 'inj')
                {
                    $in = array();
                    if(isset($injs[$day]))
                    {
                        $in = $injs[$day];
                    }
                    else 
                    {
                        $in = getInjUsage($loc_id,$day);
                        $injs[$day] = $in;
                    }
                    
                    $ou = isset($in[$key])? round($in[$key]/7):0;
                }
                
                
                if(isset($pros[$day]))
                {
                    $pro = $pros[$day];
                    $ims[$key][$day] = array('given'=>$pro->{$key."_g"},'used'=>$pro->{$key."_u"},'id'=>$pro->id,'ou'=>$ou,'staff'=>$pro->lname." ".$pro->fname,'staff_id'=>$pro->staff_id);
                }
                else 
                {
                    $ims[$key][$day] = array('ou'=>$ou);
                }
            }
        }
        
        return $ims;
    }
    
    public function add($loc_id,$date)
    {
        $this->data['bc1'] = 'Inj / Meds';
	$this->data['bc2'] = 'Add/Update';
        
        $given = $this->injmeds->get($loc_id,$date);
        $this->data['given'] = $given;
        
        $weekdays = getWeekDatys($date);
        $this->data['weekdays'] = $weekdays;
        $this->data['loc_id'] = $loc_id;
        
        if($this->input->server('REQUEST_METHOD') === 'POST')
    	{
            $this->form_validation->set_rules('med1', '37', 'trim|is_natural|required');
            $this->form_validation->set_rules('med2', '30', 'trim|is_natural|required');
            $this->form_validation->set_rules('med3', '15', 'trim|is_natural|required');
            $this->form_validation->set_rules('med4', 'DI', 'trim|is_natural|required');
            
            $this->form_validation->set_rules('inj1', '37', 'trim|is_natural|required');
            $this->form_validation->set_rules('inj2', '30', 'trim|is_natural|required');
            $this->form_validation->set_rules('inj3', '15', 'trim|is_natural|required');
            $this->form_validation->set_rules('inj4', 'DI', 'trim|is_natural|required');
            
            if($this->form_validation->run() == TRUE)
	    {
                $post = $this->input->post();
                if($given)
                {
                    $this->injmeds->updateGiven($given->id,$post);
                }
                else
                {
                    $post['week'] = $date;
                    $post['location_id'] = $loc_id;
                    $post['created'] = date('Y-m-d H:i:s');
                    $this->injmeds->addGiven($post);
                    $given = $this->injmeds->getLocationWeek($loc_id,$date);
                }
                
                redirect("injmeds?id=$given->id");
            }
        }
        
        $this->load->view('injmeds/add',$this->data);
    }
    
    public function getViewUsage($id,$med)
    {
        $locWeek = $this->injmeds->getById($id);
        $weekdays = getWeekDatys($locWeek->week);
        $imUsage = $this->injmeds->getIMUsage($id,$weekdays[1],$weekdays[6]);
        $imuarr = array();
        foreach($imUsage as $imu)
        {
            $imuarr[$imu->date] = $imu;
        }
        $this->data['staff'] = $this->user->getUserByType(array(2,4));
        $this->data['weekdays'] = $weekdays;
        $this->data['imu'] = $imuarr;
        $this->data['locweek'] = $locWeek;
        $this->data['med'] = $med;
        
        echo $this->load->view('injmeds/_view_injmeds',$this->data,TRUE);
    }
    
    public function updateUsage()
    {
        $post = $this->input->post();
        $im_id = $post['im_id'];
        $med = $post['med'];
        foreach($post['usage'] as $day => $val)
        {
            $imu = $this->injmeds->getIMU($im_id,$day);
            if($imu)
            {
                $this->injmeds->updateIMU($imu->id,array($med=>$val['balance'],'staff_id'=>$val['staff_id']));
            }
            else 
            {
                $this->injmeds->addIMU(array('im_id'=>$im_id,'date'=>$day,$med=>$val['balance'],'staff_id'=>$val['staff_id']));
            }
        }
        
        $this->getViewUsage($im_id, $med);
    }
    
    public function copy()
    {
        $post = $this->input->post();
        
        $loc_id = $post['loc_id'];
        $start = $post['start'];
        
        $days = getWeekDatys($start);
        
        $res = $this->injmeds->getInjMedsArr($loc_id,$days[1],$days[6]);    
        
        foreach ($res as $r)
        {
            $new_date = date('Y-m-d', strtotime('+1 week',strtotime($r['date'])));
            
            if(!$this->injmeds->getIM($loc_id,$new_date))
            {
                $r['date'] = $new_date;
                $this->injmeds->addIM($r);
            }
        }
        
        echo 'success';
        
    }
    
    public function printWeek($week,$loc_id)
    {
        $medInfo = $this->config->item('injmeds');
        $weekdays = getWeekDatys($week);
        $imUsage = $this->injmeds->getIMUsage($loc_id,$weekdays[1],$weekdays[6]);
        
        $this->data['staff'] = $this->user->getUserByType(array(2,4));
        $this->data['weekdays'] = $weekdays;
        
        $this->data['loc_id'] = $loc_id;
        $this->data['meds'] = $medInfo;
        
        $this->data['mis'] = $this->getMIdata($loc_id, $week);
        
        
       $html = $this->load->view("injmeds/_print",$this->data,TRUE); 
       
       create_mp_ticket($html);
    }
    
    public function updateIM()
    {
        $post = $this->input->post();
        $im = $this->injmeds->getIM($post['loc'],$post['date']);
        
        $imm = $post['im'];
        $data = array('loc_id'=>$post['loc'],'date'=>$post['date'],'staff_id'=>$post['staff'],$imm."_g"=>$post['given'],$imm."_u"=>$post['used']);
        if($im)
        {
            $this->injmeds->updateIM($im->id,$data);
        }
        else 
        {
            $this->injmeds->addIM($data);
        }
        
        echo 'success';
    }
}
