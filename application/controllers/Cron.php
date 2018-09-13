<?php
/**
 * Description of Lock
 *
 * @author Udana
 */
class Cron extends CI_Controller
{
    public function gainloss()
    {
	$this->load->model("Patient_model", "patient");
	$this->load->model("Mail_model", "mail");
	
	$yesterday = date('Y-m-d',strtotime("-1 days"));
	
	$yesterdayVisiters = $this->patient->getVisitedOnDay($yesterday);
	
	foreach($yesterdayVisiters as $visit)
	{
	    $previousVisit = $this->patient->getPatientVisit($visit->patient_id,$visit->visit - 1);
	    if($previousVisit)
	    {
		$diff = abs($previousVisit->weight - $visit->weight);
		if($diff <= 2) continue;
		
		$patient = $this->patient->getPatient($visit->patient_id);
		$data = array();
		$data['diff'] = $diff;
		$data['d1'] = date('M j',strtotime($previousVisit->visit_date));
		$data['d2'] = date('M j',strtotime($visit->visit_date));
		$data['fname'] = $patient->fname;
		if($previousVisit->weight > $visit->weight)
		{  
		    $data['loss'] = TRUE;
		}
		else
		{
		    $data['loss'] = FALSE;
		}
		$subject = "Your weekly weigh in";
		$message =  $this->load->view('emails/gainloss',$data,true);
		$mail = array('patient_id'=>$visit->patient_id,'subject'=>$subject,'content'=>$message,'created'=>time());
		$data['d2w'] = round($visit->weight);
		$data['d1w'] = round($previousVisit->weight);
		$data['vn'] = $visit->visit;
		$fileName = $patient->fname."_gain_loss_".date('Y-m-d',strtotime($visit->visit_date)).".pdf";
//		if($this->gainlossPdf($data,$fileName))$mail['attach'] = $fileName;
		
		$this->mail->add_mail($mail);
		
	    }
	}
	
	die('complete.');
    }
    
    public function average()
    {
	$this->load->model("Patient_model", "patient");
	$this->load->model("Mail_model", "mail");
	$monday = strtotime("last week monday");
	$monday = date('W', $monday)==date('W') ? $monday-7*86400 : $monday;

	$sunday = strtotime(date("Y-m-d",$monday)." +6 days");
	$start = date("Y-m-d",$monday);
	$end = date("Y-m-d",$sunday);
	
	$visitAverages = array();

	$visits = $this->patient->getVisitorsOnPeriod($start." 00:00:00",$end." 23:59:59");
	
	foreach($visits as $visit)
	{
	    $visi = $visit->visit;
	    
	    $previousVisit = $this->patient->getPatientVisit($visit->patient_id,$visi - 1);
	    if($previousVisit)
	    {
		$average = isset($visitAverages[$visi])? $visitAverages[$visi] : $this->getVisitAverage($visi);
		$visitAverages[$visi] = $average;
		$loss = abs($previousVisit->weight - $visit->weight);
		$patient = $this->patient->getPatient($visit->patient_id);
		$data = array();
		$data['diff'] = $loss;
		$data['d1'] = date('M j, Y',strtotime($previousVisit->visit_date));
		$data['d2'] = date('M j, Y',strtotime($visit->visit_date));
		$data['fname'] = $patient->fname;
		$subject = $data['subject'] =  "You got this…";
		$data['average'] = $average;
		
		if($loss > $average)
		{  
		    $data['loss'] = TRUE;
		    $subject =  "You got this";
		    $data['subject'] = "You got this…";
		}
		else
		{
		    $data['loss'] = FALSE;
		    $subject = "You’re getting there";
		    $data['subject'] = "You’re getting there…";
		}
		
		$data['subject'] = $subject;
		
		$message =  $this->load->view('emails/average',$data,true);
		$mail = array('patient_id'=>$visit->patient_id,'subject'=>$subject,'content'=>$message,'created'=>time());
		$this->mail->add_mail($mail);
		
	    }
	    
	}

	die('Complete');
    }
    
    public function deliver()
    {	
	$this->load->model("Mail_model", "mail");
	
	$mails = $this->mail->getMailsToDeliver();
        
	foreach($mails as $mail)
	{
	    $attach = NULL;
	    if(!empty($mail->attach))
	    {
		$attach = "./assets/attachments/".$mail->attach;
	    }
	    $this->mail->send_mail($mail->email,$mail->subject,$mail->content,$attach);	    
//            $this->mail->send_mail('udanaudayanga@gmail.com',$mail->subject,$mail->content,$attach);	  
	    $this->mail->update_mail($mail->id,array('delivered'=>1));
	}
	
	die('complete');
    }
    
    public function getVisitAverage($visit)
    {
	$prevVisit = $visit - 1;
	$this->load->model("Patient_model", "patient");
	$visitors = $this->patient->getWeightLossPatients($visit,'loss');
	$prevVisitor = $this->patient->getWeightLossPatients($prevVisit);
	
	$allVisits = array();
	foreach($visitors as $visitor)
	{
	    $allVisits[$visitor->patient_id][$visit] = $visitor->weight;
	}
	foreach($prevVisitor as $visitor)
	{
	    if(isset($allVisits[$visitor->patient_id]))
	    $allVisits[$visitor->patient_id][$prevVisit] = $visitor->weight;
	}
	
	$totalWeighLost = $totalPatients = 0;
	
	foreach($allVisits as $key=>$val)
	{
	    if(count($val)== 2 && ($val[$visit] < $val[$prevVisit]))
	    {
		$totalPatients++;
		$totalWeighLost += $val[$prevVisit] - $val[$visit];
	    }
	}
	
	return $totalWeighLost/$totalPatients;
    }
    
    public function streak()
    {
	$this->load->model("Patient_model", "patient");
	$this->load->model("Mail_model", "mail");
	$monday = strtotime("last week monday");
	$monday = date('W', $monday)==date('W') ? $monday-7*86400 : $monday;

	$sunday = strtotime(date("Y-m-d",$monday)." +6 days");
	$start = date("Y-m-d",$monday);
	$end = date("Y-m-d",$sunday);
	
	$visits = $this->patient->getVisitorsForStreak($start." 00:00:00",$end." 23:59:59");
	
	foreach($visits as $visit)
	{
	    if($visit->visit == 3)
	    {
		$prevVisits = $this->patient->getVisitsByVisit($visit->patient_id,1,$visit->visit,'loss');
		if(count($prevVisits) == $visit->visit)
		{
		    $patient = $this->patient->getPatient($visit->patient_id);
		    $firstVisit = $this->patient->getPatientVisit($visit->patient_id,1);
		    $data = array();
		    $data['fname'] = $patient->fname;
		    $data['d1'] = date('M j, Y',strtotime($firstVisit->visit_date));
		    $data['d2'] = date('M j, Y',strtotime($visit->visit_date));
		    $data['d1w'] = round($firstVisit->weight);
		    $data['d2w'] = round($visit->weight);
		    $data['diff'] = $data['d1w'] - $data['d2w'];
		    
		    $message =  $this->load->view('emails/streak_one',$data,true);
		    $subject = "You’re on a roll";
		    $mail = array('patient_id'=>$visit->patient_id,'subject'=>$subject,'content'=>$message,'created'=>time());
		    $this->mail->add_mail($mail);
		}
	    }
	    elseif($visit->visit == 4)
	    {
		$this->load->library('encrypt');
		$prevVisits = $this->patient->getVisitsByVisit($visit->patient_id,1,$visit->visit,'loss');
		if((count($prevVisits) == $visit->visit))
		{
		    $patient = $this->patient->getPatient($visit->patient_id);
		    $firstVisit = $this->patient->getPatientVisit($visit->patient_id,1);
		    $data = array();
		    $data['fname'] = $patient->fname;
		    $data['d1'] = date('M j, Y',strtotime($firstVisit->visit_date));		    
		    $data['d1w'] = round($firstVisit->weight);
		    $data['d2w'] = round($visit->weight);
		    $data['diff'] = $data['d1w'] - $data['d2w'];
		    $emailEncrypt = base64_encode($this->encrypt->encode($patient->email));
		    $data['reminder_url'] = site_url("mail/review_alert/$patient->id/$emailEncrypt");
		    $message =  $this->load->view('emails/streak_two',$data,true);
		    $subject = "Keep on rolling";
		    $mail = array('patient_id'=>$visit->patient_id,'subject'=>$subject,'content'=>$message,'created'=>time());
		    $mail['deliver_after'] = date('Y-m-d',strtotime("+1 days"))." 08:00:00";
		    $this->mail->add_mail($mail);
		    
		    //Get Rewarded mail
		    $message =  $this->load->view('emails/streak_two_reward',$data,true);
		    $subject = "Get rewarded";
		    $mail = array('patient_id'=>$visit->patient_id,'subject'=>$subject,'content'=>$message,'created'=>time());
		    $mail['deliver_after'] = date('Y-m-d',strtotime("+3 days"))." 08:00:00";
		    $this->mail->add_mail($mail);
		}
	    }
	    elseif($visit->visit == 5)
	    {
		$prevVisits = $this->patient->getVisitsByVisit($visit->patient_id,1,$visit->visit,'loss');
		if((count($prevVisits) == $visit->visit))
		{
		    $patient = $this->patient->getPatient($visit->patient_id);
		    $firstVisit = $this->patient->getPatientVisit($visit->patient_id,1);
		    $data = array();
		    $data['fname'] = $patient->fname;
		    $data['d1'] = date('M j, Y',strtotime($firstVisit->visit_date));		    
		    $data['d1w'] = round($firstVisit->weight);
		    $data['d2w'] = round($visit->weight);
		    $data['diff'] = $data['d1w'] - $data['d2w'];
		    
		    $message =  $this->load->view('emails/streak_three',$data,true);
		    $subject = "You're at the head of the class";
		    $mail = array('patient_id'=>$visit->patient_id,'subject'=>$subject,'content'=>$message,'created'=>time());
		    $this->mail->add_mail($mail);
		}
	    }
	}
	
	die('Complete');
    }
    
    public function gainlossPdf($data,$file_name)
    {
	$this->load->helper('pdf');
	$this->load->helper('file');
	$html = $this->load->view('pdf/gainloss',$data,true);
//	echo $html;
//	die();
	$pdf = create_pdf($html,$file_name,FALSE);
	file_put_contents('./assets/attachments/'.$file_name, $pdf);
	        
	return TRUE;
    }
    
    public function defector()
    {
	$this->load->model("Patient_model", "patient");
	$this->load->model("Mail_model", "mail");
	$this->load->model("Order_model", "order");
	
	$pro_id = 1;
	$quantity = 2;
	
	$lastDay = date('Y-m-d',strtotime("-11 days"));
	$patients = $this->patient->getDefectorPatients($lastDay);
	foreach($patients as $patient)
	{
	    $pro_puchase = $this->order->checkProPurchase($patient->patient_id,$pro_id,date('Y-m-d',strtotime($patient->visit_date)));
	    if($pro_puchase && $pro_puchase->quantity >= $quantity)continue;
	    
	    $data = array();
	    $data['fname'] = $patient->fname;
	    
	    $message =  $this->load->view('emails/defector',$data,true);
	    $subject = "Defector Mail";
	    $mail = array('patient_id'=>$patient->patient_id,'subject'=>$subject,'content'=>$message,'created'=>time());
	    $this->mail->add_mail($mail);
	}
	
	//Booster mail
	$lastDay = date('Y-m-d',strtotime("-21 days"));
	$patients = $this->patient->getDefectorPatients($lastDay);
	foreach($patients as $patient)
	{
	    $pro_puchase = $this->order->checkProPurchase($patient->patient_id,$pro_id,date('Y-m-d',strtotime($patient->visit_date)));
	    if($pro_puchase && $pro_puchase->quantity >= $quantity)continue;
	    
	    $data = array();
	    $data['fname'] = $patient->fname;
	    
	    $message =  $this->load->view('emails/boost',$data,true);
	    $subject = "Here’s a boost";
	    $mail = array('patient_id'=>$patient->patient_id,'subject'=>$subject,'content'=>$message,'created'=>time());
	    $mail['deliver_after'] = date('Y-m-d 08:00:00');
	    $this->mail->add_mail($mail);
	}
	
	die('Complete');
    }
    
    public function patientstatus()
    {
        $this->load->model("Patient_model", "patient");
        $sixMonthAgo = date('Y-m-d',strtotime("-6 months"));
        $oneYearAgo = date('Y-m-d',strtotime("-1 year"));
        
        $patients = $this->patient->getPatients();
        
        foreach($patients as $p)
        {
            if($p->status == 0) continue;
            
            $visit = $this->patient->getPatientLastVisit($p->id);
            $last_med_days = NULL;
            if($visit)
            {
                $med_days = $visit->is_med == 1 ? $visit->med_days : $visit->no_med_days;
                $last_med_days = date('Y-m-d',  strtotime($visit->visit_date . " + $med_days days"));                
            }
            elseif($p->last_status_date)
            {
                $last_med_days = $p->last_status_date;
            }
            
            if($last_med_days)
            {
                if($last_med_days > $sixMonthAgo)
                {
                    if($p->status != 1) $this->patient->updatePatient($p->id,array('status'=>1));
                }
                elseif($last_med_days > $oneYearAgo)
                {
                    if($p->status != 2) $this->patient->updatePatient($p->id,array('status'=>2));
                }
                else
                {
                    if($p->status != 3) $this->patient->updatePatient($p->id,array('status'=>3));
                }
            }
            else
            {
                if($p->status != 3) $this->patient->updatePatient($p->id,array('status'=>3));
            }
        }
        
        die('complete');
    }
    
    public function updatePhaseStatus()
    {
        $this->load->model('Evaluate_model','evaluate');        
        $this->evaluate->UpdatePhaseToComplete(date('Y-m-d'));
        die('complete');
    }
    
    public function weekly()
    {
        $this->load->model("Patient_model", "patient");
	$this->load->model("Mail_model", "mail");
        
        
        
        $subject = "Weekly Mail Subject";
        $message =  $this->load->view('emails/weekly',$data,true);
        $mail = array('patient_id'=>$visit->patient_id,'subject'=>$subject,'content'=>$message,'created'=>time());
        $this->mail->add_mail($mail);
    }
   
        
    function readCSV($csvFile)
    {
	$file_handle = fopen($csvFile, 'r');
	while (!feof($file_handle) ) {
		$line_of_text[] = fgetcsv($file_handle, 1024);
	}
	fclose($file_handle);
	return $line_of_text;
    }
    
    public function import()
    {die('test');
        $file = "./assets/csv/NewC5.csv";
        $csv = $this->readCSV($file);
        
        $patients = $states = array();
        $i = 0;
        
        foreach($csv as $key=>$val)
        {
            if($key == 0) continue;
            if(empty($val[0])) continue;
            
            $temp = array();
            $temp['lname'] = $val[0];
            $temp['fname'] = $val[1];
            $temp['address'] = $val[2];
            $temp['city'] = $val[3];
            $state = 0;
            
            if(isset($states[$val[4]]))
            {
                $state = $states[$val[4]];
            }
            else 
            {
                $st = $this->patient->getStateId($val[4]);
                
                $state = $st->id;
                $states[$val[4]] = $state;
            }
            
            $temp['state'] = $state;
            $temp['zip'] = $val[5];
            $temp['phone'] = $val[6];
            $temp['dob'] = date('Y-m-d',strtotime($val[7]));
            $temp['gender'] = $val[8]== 'F'? 2:1;
            $temp['goal_weight'] = $val[9];
            $temp['email'] = $val[10];
            $temp['patient_category'] = strtolower($val[11]);
            
            $height = 0;
            if(!empty($val[12]))
            {                
                $ha = explode('.', $val[12]);      
//                if(count($ha)==1) die($val[0]);
                $height = (count($ha)==2)?$ha[0]*12 + $ha[1] : $ha[0]*12;
            }
            $temp['height'] = $height;
            $temp['allergies'] = $val[14];
            
            array_push($patients,$temp);
            $i++;
            
            if($i == 10)
            {
                $this->patient->importPatients($patients);
                $patients = array();
                $i = 0;
            }
            
        }
        
//        print_r($patients);
        $this->patient->importPatients($patients);
        die("Complete");
    }
    
    public function staffShiftAlert()
    {
        $next_day = date('Y-m-d',strtotime('+1 day'));
        $this->load->model("Schedule_model", "schedule");
                
        $shifts = $this->schedule->getShiftsForDay($next_day);
        $emps = array();
        
        foreach($shifts as $shift)
        {
            if($shift->user_id == 14) continue;
            if(isset($emps[$shift->user_id]))
            {
                $user = $emps[$shift->user_id];
                $user['name'] = $shift->fname;
                $user['phone'] = $shift->phone;
                $msg = "$shift->abbr:".dateFormat($shift->start)."-".dateFormat($shift->end);
                $user['msg'] .= "\n".$msg;
                $emps[$shift->user_id] = $user;
            }
            else
            {
                $user = array();
                $user['name'] = $shift->fname;
                $user['phone'] = $shift->phone;
                $msg = "$shift->abbr:".dateFormat($shift->start)."-".dateFormat($shift->end);
                $user['msg'] = $msg;
                
                $emps[$shift->user_id] = $user;
            }
        }
        
        
                
        foreach($emps as $key=>$val)
        {
            $msg = $val['name'].", here is your schedule for ".date('l d',strtotime($next_day)).": \n".$val['msg'];
            SendSMS($val['phone'], $msg);
        }
        
        die('DONE');
    }
    
    public function appointAlert()
    {
        $next_day = date('Y-m-d',strtotime('+1 day'));
        $this->load->model("Appoint_model", "appoint");
        $appoints = $this->appoint->getAppointsForDay($next_day);
        
        $type = array(1=>'new patient visit',2=>'weekly visit',3=>'doctor consult visit',4=>'shots only visit');
        foreach($appoints as $app)
        {
            if($app->patient_id && $app->phone)
            {
                $msg = $app->fname.", a reminder from Doctor's Weight Loss for your ".$type[$app->type]." on ".date("l, F d, Y",strtotime($app->date))." ".date("g:ia",strtotime($app->time))." EDT @($app->loc_name). To reschedule (727-412-8208) \nType C to confirm or P to cancel.\nType STOP to stop these Msgs.\nMsg & Data rates may apply.";
                SendSMS($app->phone, $msg);
                
                $data = array();
                $data['patient_id'] = $app->patient_id;
                $data['msg'] = $msg;
                $data['status'] = 1;
                $data['phone'] = $app->phone;
                $data['created'] = date('Y-m-d H:i:s');
                $this->patient->addSmsLog($data);
            }
            elseif($app->phn)
            {
                $msg = $app->first_name.", a reminder from Doctor's Weight Loss for your ".$type[$app->type]." on ".date("l, F d, Y",strtotime($app->date))." ".date("g:ia",strtotime($app->time))." EDT @($app->loc_name). Please note: New Patient Initial Visit takes about 2 hrs. Take all your prescription meds prior to visit. Do not apply any body oils or lotions as that might hinder the EKG process. To reschedule (727-412-8208) \nType C to confirm or P to cancel.\nType STOP to stop these Msgs.\nMsg & Data rates may apply.";
                SendSMS($app->phn, $msg);               
            }
        }
        
        die('DONE');
    }
    
    
    public function printtime()
    {
        echo date('Y-m-d H:i:s');
        echo "<br>";
        echo date_default_timezone_get() . ' => ' . date('e') . ' => ' . date('T');
        die();
    }
    
    public function saveVP($date = null)
    {
        $this->load->model('User_model','user'); 
        
        $gen_date = $date? $date : date('Y-m-d');
        
        $orders = $this->order->getOrdersByDate($gen_date);
        foreach($orders as $order)
        {
            $order_id = $order->id;        
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
            $latest_visits = $this->patient->getLatestNoOfVisits($patient->id,6,date('Y-m-d',strtotime($order->created)));
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

            $html = $this->load->view('order/visitpage',$this->data,true);
            
            $this->order->updateOrder($order_id,array('vp'=>gzcompress($html)));
        }
        
        die('done');
    }
    
    public function saveVPOrder($order_id)
    {
        $this->load->model('User_model','user'); 
        
        
        $order  = $this->order->getOrder($order_id);
        
        $order_id = $order->id;        
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
        $latest_visits = $this->patient->getLatestNoOfVisits($patient->id,6,date('Y-m-d',strtotime($order->created)));
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

        $html = $this->load->view('order/visitpage',$this->data,true);

        $this->order->updateOrder($order_id,array('vp'=>gzcompress($html)));
        die('done');
    }
    
    public function showVP($order_id)
    {
        $order  = $this->order->getOrder($order_id);
        
        $html = gzuncompress($order->vp);
        
        create_mp_ticket($html);
    }
    
    public function bday()
    {
        $this->load->model("Patient_model", "patient");
        
        $bdays = $this->patient->getTodayBday();
        
        foreach($bdays as $bd)
        {            
            $msg = "Hi $bd->fname, \nHappy Birthday from everyone at our clinic! We love having you as one of our patients and hope you have a great day! You also get a free B-12 on your next Wkly Visit. \nSincerely, \nDWLC Staff";            
            
            SendSMS($bd->phone, $msg);
            
            $data = array();
            $data['patient_id'] = $bd->id;
            $data['msg'] = $msg;
            $data['status'] = 1;
            $data['phone'] = $bd->phone;
            $data['created'] = date('Y-m-d H:i:s');
            $this->patient->addSmsLog($data);
        }
        
        die('done');
    }
    
    public function sixStatus()
    {
        $this->load->model("Patient_model", "patient");
        $tw = date("Y-m-d",strtotime("+2 weeks"));
        $sixago = date('Y-m-d',strtotime("-6 months",strtotime($tw)));
        $sixago = '2016-07-07';
        $patients = $this->patient->getSixStatusAlert($sixago);
        
        foreach($patients as $p)
        {
            $msg = "Hi $p->fname, \nJust a reminder to inform you that your 6 months status of “Current Patient” is about to expire on ".date('j F', strtotime($tw)).". Call 727 412 8208 Doctor's Weight Loss Cntr to schedule an appointment so as to extend the Current Patient Status.";
            
            SendSMS($p->phone, $msg);
        }
        
        die('done');
    }
    
    public function testMail()
    {
        $this->load->library('email');
        $this->email->from('noreply@drsweightloss.center','DWLC');
        $this->email->to('udanaudayanga@gmail.com');		
        $this->email->subject("Test Subject");
        $this->email->message("This is a test");		
        $this->email->send(); 
        die('test');
    }
    
    
    public function testmc()
    {
        $this->load->helper('mc');
        
        $list = testGetLists();
        print_r($list);
    }
    
    public function createmc()
    {
        $this->load->helper('mc');
        
        $data = array();
        
        $data['name'] = "Create List by API - Udana";
        
        $contact = array();
        $contact['company'] = "Helatech";
        $contact['address1'] = '16,Surigama';
        $contact['city'] = 'Kadawatha';
        $contact['state'] = 'Western';
        $contact['zip'] = '11850';
        $contact['country'] = 'Sri Lanka';
        
        $data['contact'] = $contact;
        $data['permission_reminder'] = "You're receiving this email because you signed up for updates about Freddie's newest hats.";
        $data['email_type_option'] = true;
        
        $cd = array();
        $cd['from_name'] = "DWLC";
        $cd['from_email'] = "doctorsweightloss@gmail.com";
        $cd['subject'] = "";
        $cd['language'] = "EN";
        
        
        $data['campaign_defaults'] = $cd;
        
        $list = createList($data);
        print_r($list);
    }
    
    public function addMem()
    {
        $this->load->helper('mc');
        
        $data = $temp = array();
        $list_id = "927f5f8ccf";
        
        $temp['email_address'] = "udanaudayanga@gmail.com";
        $temp['status'] = 'subscribed';
//        array_push($temp,$data);
//        $temp['email_address'] = "udana@udana.lk";
//        $temp['status'] = 'subscribed';
//        array_push($temp,$data);
        
        $result = addMemToList($list_id, $temp);
        print_r($result);
    }
    
    public function addMems()
    {
        $this->load->helper('mc');
        $this->load->library('encrypt');
        $batch  = getMCBatchObj();
        $temp = $tempp = array();
        $temp['email_address'] = "udanaudayanga@gmail.com";
        $temp['status_if_new'] = 'subscribed';
        $temp['merge_fields'] = array('FNAME'=>'Udana','LNAME'=>'Udayanga','PID'=> urlencode($this->encrypt->encode(1)));
        
        $tempp['email_address'] = "udana@udana.lk";
        $tempp['merge_fields'] = array('FNAME'=>'Udana','LNAME'=>'Dotlk','PID'=> urlencode($this->encrypt->encode(2)));
        $tempp['status_if_new'] = 'subscribed';
        
        
        $batch->put("1",'lists/55dfce721b/members/'. md5(strtolower($temp['email_address'])),$temp);
        $batch->put("2",'lists/55dfce721b/members/'. md5(strtolower($tempp['email_address'])),$tempp);
        $result = $batch->execute(30);
        print_r($result);
    }
    
    public function checkBatch($batch)
    {
        $this->load->helper('mc');
        $data = array();
        
        $get = getMC("batches/$batch", $data);
        print_r($get);
    }
    
    public function checkSubscription()
    {
       $this->load->helper('mc');
       $md5 = md5('udanaudayanga@gmail.com');
       $get = getMC('lists/15ffaee1f9/members/'.$md5, array());
        print_r($get);
    }
    
    public function allMc()
    {
        $this->load->helper('mc');
        $this->load->model("Patient_model", "patient");
        $patients = $this->patient->getMCPatients(600,1000);
//        foreach($patients as $p)
//        {
//            echo $p->id." - ".$p->email."<br>";
//        }
        $batch = getMCBatchObj();
        $path = "lists/b7ba82a617/members";
                    
        foreach($patients as $pd)
        {
            if(empty($pd->email)) continue;

            $temp = array();
            $temp['email_address'] = $pd->email;
            $temp['status'] = 'subscribed';
            $temp['merge_fields'] = array('FNAME'=>$pd->fname,'LNAME'=>$pd->lname);
            
            $batch->post($pd->id,$path,$temp);
        }

        $res = $batch->execute(60);
        print_r($res);        
    }
    
    Public function allActive()
    {
        $this->load->helper('mc');
        $this->load->library('encrypt');
        $this->load->model("Patient_model", "patient");
        $patients = $this->patient->getMCActivePatients();

        $batch = getMCBatchObj();
        $path = "lists/b1761ecb8b/members";
               
                    
        foreach($patients as $pd)
        {
            if(empty($pd->email)) continue;
            
            $pid = urlencode(urlencode($this->encrypt->encode($pd->id)));

            $temp = array();
            $temp['email_address'] = $pd->email;
            $temp['status_if_new'] = 'subscribed';
//            $temp['status'] = 'subscribed';
            $temp['merge_fields'] = array('FNAME'=>$pd->fname,'LNAME'=>$pd->lname,'PID'=>$pid);
            $paths = $path."/".md5(strtolower($pd->email));
            
            $batch->put($pd->id,$paths,$temp);
        }

        $res = $batch->execute(60);
        print_r($res);        
    }
    
    public function checkAll()
    {
        @ini_set('max_execution_time', 0);
        @ini_set('zlib.output_compression', 0);
        @ini_set('implicit_flush', 1);
        @ob_end_clean();
        $this->load->helper('mc');
        $this->load->model("Patient_model", "patient");
        $patients = $this->patient->getMCPatients(1000,500);
        
        $list = array();
        foreach($patients as $p)
        {
            if(empty($p->email)) continue;
            $md5 = md5(strtolower($p->email));
            $get = getMC('lists/15ffaee1f9/members/'.$md5, array());
            if($get['status'] == 404) array_push($list, $p);
            
            echo $p->email." - ".$get['status']."<br>";
            flush();
        }
        if(!empty($list))
        {
            $batch = getMCBatchObj();
            $path = "lists/15ffaee1f9/members";

            foreach($list as $pd)
            {
                if(empty($pd->email)) continue;

                $temp = array();
                $temp['email_address'] = $pd->email;
                $temp['status'] = 'subscribed';
                $temp['merge_fields'] = array('FNAME'=>$pd->fname,'LNAME'=>$pd->lname);

                $batch->post($pd->id,$path,$temp);
            }

            $res = $batch->execute(60);
            print_r($res);       
        }
        print_r($list);
    }
    
    public function sendsp()
    {
        @ini_set('max_execution_time', 0);
        @ini_set('zlib.output_compression', 0);
        @ini_set('implicit_flush', 1);
        @ob_end_clean();
        $this->load->model("Cron_model", "cron");
        $patients = $this->cron->getPatientsBasedOnOrderTotal(300);
        
        foreach($patients as $p)
        {
            echo $p->fname." ".$p->lname;
            
            $msg = "Hi $p->fname, \nAs a valued patient of Doctors’ Weight Loss Cntr, we are offering one Tasty Spoon low-carb meal to you, absolutely FREE. This offer ends Sep 9. Please pick it up on your next visit by showing this msg. 727-4128208. \nMsg & Data rates may apply";
            
//            SendSMS($p->phone, $msg);
            
            echo " - SENT <br>";
            sleep(1);
            flush();
            $data = array();
            $data['patient_id'] = $p->patient_id;
            $data['msg'] = $msg;
            $data['status'] = 1;
            $data['phone'] = $p->phone;
            $data['created'] = date('Y-m-d H:i:s');
            $this->patient->addSmsLog($data);
        }
        
        die('done');
    }
    
    public function sendPD()
    {
        @ini_set('max_execution_time', 0);
        @ini_set('zlib.output_compression', 0);
        @ini_set('implicit_flush', 1);
        @ob_end_clean();
        $this->load->model("Cron_model", "cron");
        //$patients = $this->cron->getPastDuePatients(60,180);
        $patients = $this->cron->getPastDuePatients(365,700);
        die();
        foreach($patients as $p)
        {
            echo $p->fname." ".$p->lname;
            
            $msg = "Dear $p->fname, \nWe would love to have you back and help you get ready for the festive season by taking advantage of our '$45/wk Visit' Seasonal Special Offer. Re-join fee $10 for Nov. Check out our other Seasonal Specials at doctorswl.com. Hurry, join before offer ends on Nov 30. Doctors Weight Loss Center 727 412 8208. \nMsg & Data rates may apply. \nType stop to Stop.";
//             SendSMS($p->phone, $msg);
             
             echo " - SENT <br>";
            sleep(1);
            flush();
        }
        die('done');
    }
    
    
    public function getSms()
    {
        $client = getSMSClient();
        
        
        try 
        {
//            $responce = $client->account->calls->create($twilio_from,$hotline,$url);
            
            foreach ($client->account->messages->getIterator(0, 50, array(
                'DateSent>' => '2017-02-04',
                'DateSent<' => '2017-08-04'
            )) as $message) {
                
                if($message->status == 'delivered')
                {
                    $local = substr($message->to, -10);
                    if($local == '7272497853') continue;
                    $patient = $this->patient->getPatientByPhone($local);

                    if($patient)
                    {
                        $data = array();
                        $data['patient_id'] = $patient->id;
                        $data['msg'] = $message->body;
                        $data['status'] = 1;
                        $data['phone'] = $message->to;
                        $data['created'] = date('Y-m-d H:i:s',strtotime($message->date_created));
                        $this->patient->addSmsLog($data);
                    }
                }
                else 
                {
                    $local = substr($message->from, -10);
                    $patient = $this->patient->getPatientByPhone($local);

                    if($patient)
                    {
                        $data = array();
                        $data['patient_id'] = $patient->id;
                        $data['msg'] = $message->body;
                        $data['status'] = 2;
                        $data['phone'] = $message->from;
                        $data['created'] = date('Y-m-d H:i:s',strtotime($message->date_created));
                        $this->patient->addSmsLog($data);
                    }
                }
                
                
//                print_r($message);
                echo "<br><br>From: {$message->from}<br>To: {$message->to}<br>Body: " . $message->body." <br>Status: ".$message->status." <br>Created:".$message->date_created." <br>formatted: ".date('Y-m-d H:i:s',strtotime($message->date_created));
            }
            
        }
        catch(Services_Twilio_RestException $e) 
        {
            $msg = "TO: $to <br> Msg: $message <br> Error: ".$e->getMessage()."  ".$e->getInfo();       
            echo $msg;
        }  
    }
    
    public function getSmsNew()
    {
        $client = getSMSClientNew();
        $i = 0;
        foreach ($client->messages->read() as $message) {
            print_r($message);
            
            //echo $message->from." - ".$message->to." - ".$message->body.'<br>';
            
            $i++;
            if($i == 1) die();
        }
    }
    
    public function sendtest()
    {
        $fname = "Sunil";
        $msg = "Dear $fname, \nWe would love to have you back and help you get ready for the festive season by taking advantage of our '$45/wk Visit' Seasonal Special Offer. Re-join fee $10 for Nov. Check out our other Seasonal Specials at doctorswl.com. Hurry, join before offer ends on Nov 30. Doctors Weight Loss Center 727 412 8208. \nMsg & Data rates may apply. \nType stop to Stop.";
            
//        SendSMS('+17272497853', $msg);
        die('sent');
    }
    
    public function uploadPDMP()
    {
        $this->load->helper('sftp_helper');
        $locs = array(2=>2,4=>4,3=>3);
        $start = '2017-08-28';
        $end = '2017-08-28';
        $auths = array(
            2 => array('u'=>'FN4356514','p'=>'84841'),
            3 => array('u'=>'FN4321080','p'=>'88690'),
            4 => array('u'=>'FN5029005','p'=>'87907')
        );
        
        foreach($locs as $key=>$loc_id)
        {            
            $local_file = $this->genPDMP($start, $end, $loc_id);
            $sftp = getSFTPCon($auths[$loc_id]);
            $remote_file = "new/".date("Ymd").".dat";
            $sftp->put($remote_file, $local_file, NET_SFTP_LOCAL_FILE);
            
        }
        
        die('done');
    }
    
    public function batchPDMP()
    {
        $begin = new DateTime('2018-04-23');
        $end = new DateTime('2018-04-29');

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);
        

        foreach ($period as $dt) {
            $date =  $dt->format("Y-m-d");
            $locs = array(2,3,4);
            foreach($locs as $loc)
            {
                $this->genPDMP($date, $date, $loc);
//                echo "Generating: ".$date." - ".$date." - ".$loc."<br>";
            }
        }
        die('done');
    }
    
    public function genPDMP($start=null,$end=null,$loc_id=null)
    {  
        $this->load->model("Cron_model", "cron");
        $this->load->helper('file');
        
        $start = '2018-08-25';
        $end = '2018-08-25';
        $loc_id = 2;
        echo "Generating: ".$start." - ".$end." - ".$loc_id."<br>";
        
        $dea = array(2=>'FN4356514',3=>'FN4321080',4=>'FN5029005');
        
        $pres = $this->cron->getForPDMP($loc_id,$start,$end);
        
        
        $TH01 = "4.2A";
        $TH02 = strtoupper(date("ndYDHi"));
        $TH05 = date("Ymd");
        $TH06 = date("Hi");
        $TH07 = "P"; //T=Test, P= Production
        
        $IS01 = "7274128208";
        $IS02 = "DOCTORS WEIGHT LOSS CENTER";
        $IS03 = empty($pres)? date("#Ymd#",strtotime($start))."-".date("#Ymd#", strtotime($end)): "*";
        
        $PHA01 = "1699760843";
        $PHA03 = $dea[$loc_id];
        $PHA08 = "FL";
        
        $PAT02 = "03";
        $PAT03 = "";
        
        
        $ds = "TH*$TH01*$TH02*01**$TH05*$TH06*$TH07**~~";
        $ds .= "IS*$IS01*$IS02*$IS03~";
        
        $ds .= "PHA*$PHA01**$PHA03*****$PHA08~";
        
        $tpc = 1;
        
        $ndc = array(19=>'61939081010',20=>'00527131010',21=>'00527174210',22=>'10702004401');
        
                
        foreach($pres as $pr)
        {
            $PAT03 = $pr->patient_id;
            $PAT07 = strtoupper($pr->lname);
            $PAT08 = strtoupper($pr->fname);
            $PAT12 = strtoupper($pr->address);
            $PAT14 = strtoupper($pr->city);
            $PAT15 = strtoupper($pr->abbr);
            $PAT16 = $pr->zip;
            $PAT17 = $pr->phone;
            $PAT18 = date("Ymd",strtotime($pr->dob));
            $PAT19 = $pr->gender == 1 ? "M":"F";
            
            
            $DSP01 = "00";
            $DSP02 = $pr->prescription_no;
            $DSP03 = date("Ymd",strtotime($pr->ori_pres_date));
            $DSP04 = 4;
            $DSP05 = date("Ymd",strtotime($pr->visit_date));
            $DSP06 = $pr->refill > 0 ? $pr->refill - 1: 0;
            $DSP07 = "01";
            $DSP16 = "01";
            
            $PRE02 = $dea[$loc_id];
            
            $ds .= "PAT**$PAT02*$PAT03****$PAT07*$PAT08****$PAT12**$PAT14*$PAT15*$PAT16*$PAT17*$PAT18*$PAT19~";
            $tpc += 1;
            
            if($pr->med1 > 0)
            {
                $DSP08 = $ndc[$pr->med1];
                $DSP09 = $pr->med_days * $pr->meds_per_day;
                $DSP10 = $pr->med_days;
                
                $ds .= "DSP*$DSP01*$DSP02*$DSP03*$DSP04*$DSP05*$DSP06*$DSP07*$DSP08*$DSP09*$DSP10******$DSP16~";
                $ds .= "PRE**$PRE02~";
                
                $tpc += 2;
            }
            
            if($pr->med2 > 0)
            {
                $DSP08 = $ndc[$pr->med2];
                $DSP09 = $pr->med_days * $pr->meds_per_day;
                $DSP10 = $pr->med_days;
                
                $ds .= "DSP*$DSP01*$DSP02*$DSP03*$DSP04*$DSP05*$DSP06*$DSP07*$DSP08*$DSP09*$DSP10******$DSP16~";
                $ds .= "PRE**$PRE02~";
                
                $tpc += 2;
            }
            
            if($pr->med3 > 0)
            {
                $DSP08 = $ndc[$pr->med3];
                $DSP09 = $pr->med_days * $pr->meds_per_day;
                $DSP10 = $pr->med_days;
               
                $ds .= "DSP*$DSP01*$DSP02*$DSP03*$DSP04*$DSP05*$DSP06*$DSP07*$DSP08*$DSP09*$DSP10******$DSP16~";
                $ds .= "PRE**$PRE02~";
                
                $tpc += 2;
            }        
            
        }
        if(empty($pres))
        {
            $ds .= "PAT*******Report*Zero************~";
            $DSP05 = date('Ymd', strtotime($start));
            $ds .= "DSP*****$DSP05************~";
            $ds .= "PRE***~";
            $ds .= "CDI*****~";
            $tpc += 4;
        }
        
        $tpc += 3;
        
        $ds .= "TP*$tpc~";
        
        $tpc += 1;
        
        $ds .= "TT*$TH02*$tpc~";
        $file_name = "./assets/upload/pdmp/$loc_id/".date("Ymd", strtotime($start))."_".$loc_id.".dat";
        if ( !write_file($file_name, $ds))
        {
           die('Unable to write the file');
        }
        else
        {
            die("done");
//            return $file_name;
        }
    }
    
    function generateGuid($include_braces = false) 
    {
        if (function_exists('com_create_guid')) {
            if ($include_braces === true) {                
                return com_create_guid();
            } else {
                return substr(com_create_guid(), 1, 36);
            }
        } else {
            mt_srand((double) microtime() * 10000);
            $charid = strtoupper(md5(uniqid(rand(), true)));

            $guid = substr($charid,  0, 8) . '-' .
                    substr($charid,  8, 4) . '-' .
                    substr($charid, 12, 4) . '-' .
                    substr($charid, 16, 4) . '-' .
                    substr($charid, 20, 12);

            if ($include_braces) {
                $guid = '{' . $guid . '}';
            }

            return $guid;
        }
    }

        
}

