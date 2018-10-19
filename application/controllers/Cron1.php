<?php

/**
 * Description of Cron
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
        
        $patients = $this->patient->getPatientsForStatusCron($sixMonthAgo);
        
        foreach($patients as $p)
        {
            if(date('Y-m-d',strtotime($p->visit_date)) < $oneYearAgo)
            {
                if($p->status != 3) $this->patient->updatePatient($p->id,array('status'=>3));
            }
            else 
            {
                if($p->status != 2) $this->patient->updatePatient($p->id,array('status'=>2));
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
    
    public function import()
    {
        die('test');
        $file = "./assets/csv/New.csv";
        $csv = $this->readCSV($file);
        print_r($csv);
        die();
        $patients = array();
        foreach($csv as $key => $data)
        {
            if($key==0)continue;
            
            $temp = array();
            $temp['fname'] = $data[];
            
        }
        die();
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
    
    function link()
    {
        die('test');
    }

}
