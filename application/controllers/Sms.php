<?php

/**
 * Description of Sms
 *
 * @author Udana
 */
class Sms extends CI_Controller
{
    public function request()
    {
        error_reporting(-1);
		ini_set('display_errors', 1);
        $this->load->model('Appoint_model','appoint');
        $post = $this->input->post();
        
        
        // $this->email->from($this->config->item('app_email'), $this->config->item('app_email_name'));
        // $this->email->to("udanaudayanga@gmail.com");

        
        // $this->email->subject('SMS request from TWILIO');
        
        // $message = implode('<br>', array_map(
        //     function ($v, $k) { return sprintf("%s='%s'", $k, $v); },
        //     $post,
        //     array_keys($post)
        // ));
       
        // $this->email->message($message);	

        // $this->email->send();
        
        $from = $post['From'];
        $bodyori = $post['Body'];
        $msg = "";
        
        $body =  trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $bodyori)));
        $body = strtolower($body);
        $query = explode(' ', $body);
        $timeq = false;
        
        $local = substr($from, -10);
        $patient = $this->patient->getPatientByPhone($local);
        
        if($patient)
        {
            $data = array();
            $data['patient_id'] = $patient->id;
            $data['msg'] = $bodyori;
            $data['status'] = 2;
            $data['phone'] = $from;
            $data['created'] = date('Y-m-d H:i:s');
            $this->patient->addSmsLog($data);
        }
        
        if(!empty($query))
        {
            if($query[0] == 'largotime')
            {
                $timeq = true;
                if(count($query) == 2)
                {
                   if($query[1]=='mon') 
                   {
                       $msg = "Largo \nMonday: 7 - 11am | 3pm - 6.30pm";
                   }
                   elseif($query[1]=='tue')
                   {
                       $msg = "Largo \nTuesday: 8 - 11am | 3 - 6.30pm";
                   }
                   elseif($query[1]=='wed')
                   {
                       $msg = "Largo \nWednesday: 12 - 5pm";
                   }
                   elseif($query[1]=='thu')
                   {
                       $msg = "Largo \nThursday: 7 - 11am | 3 - 6.30pm";
                   }
                   elseif($query[1]=='fri')
                   {
                       $msg = "Largo \nFriday: 7am - 12.30pm";
                   }
                   elseif($query[1]=='sat')
                   {
                       $msg = "Largo \nSaturday: 9am - 1.15pm";
                   }
                }
                else
                {
                    $msg = "Largo \nMon: 7 - 11am | 3pm - 6.30pm \nTue: 8 - 11am | 3 - 6.30pm \nWed: 12-5pm \nThu: 7 - 11am | 3 - 6.30pm \nFri: 7am - 12.30pm \nSat: 9am - 1.15pm";
                }
            }
            elseif($query[0] == '4thtime')
            {
                $timeq = true;
                if(count($query) == 2)
                {
                   if($query[1]=='mon') 
                   {
                       $msg = "St Petersburg \nMonday:  7 - 10.45am";
                   }
                   elseif($query[1]=='tue')
                   {
                       $msg = "St Petersburg \nTuesday: Closed";
                   }
                   elseif($query[1]=='wed')
                   {
                       $msg = "St Petersburg \nWednesday: Closed";
                   }
                   elseif($query[1]=='thu')
                   {
                       $msg = "St Petersburg/Thursday: Closed";
                   }
                   elseif($query[1]=='fri')
                   {
                       $msg = "St Petersburg \nFriday: 7am - 1.45pm";
                   }
                   elseif($query[1]=='sat')
                   {
                       $msg =  "St Petersburg \nSaturday: 9am - 12.45pm";
                   }
                }
                else
                {
                    $msg = "St Petersburg \nMon: 7-10.45am \nFri: 7am-1.45pm \nSat: 9am-12.45pm";
                }
            }
            elseif($query[0] == 'pinetime')
            {
                $timeq = true;
                $msg = "Closed: Renovations";
            }
            elseif($query[0] == 'palmtime')
            {
                $timeq = true;
                if(count($query) == 2)
                {
                   if($query[1]=='mon') 
                   {
                       $msg = "Palm Harbor \nMonday: Closed";
                   }
                   elseif($query[1]=='tue')
                   {
                       $msg = "Palm Harbor \nTuesday: Closed";
                   }
                   elseif($query[1]=='wed')
                   {
                       $msg = "Palm Harbor \nWednesday: Closed";
                   }
                   elseif($query[1]=='thu')
                   {
                       $msg = "Palm Harbor \nThursday: 7 - 1.00pm | 3.30 - 6.15pm";
                   }
                   elseif($query[1]=='fri')
                   {
                       $msg = "Palm Harbor \nFriday: Closed";
                   }
                   elseif($query[1]=='sat')
                   {
                       $msg = "Palm Harbor \nSaturday: 9am - 12.30pm";
                   }
                }
                else
                {
                    $msg = "Palm Harbor \nThu: 7 - 1pm | 3.30 - 6.15pm \nSat: 9am - 12.30pm";
                }
            }
            elseif($query[0] == 'tamtime')
            {
                $timeq = true;
                $msg = "Will Start Serving you from 2017 January.";
            }
            elseif($query[0]=='time')
            {
                $timeq = true;
                $msg = "Largo: \nMon: 7 - 11am | 3pm - 6.30pm \nTue: 8 - 11am | 3 - 6.30pm \nWed: 12-5pm \nThu: 7 - 11am | 3 - 6.30pm \nFri: 7am - 12.30pm \nSat: 9am - 1.15pm\n\n";
                $msg .= "St Petersburg: \nMon: 7-10.45am \nFri: 7am-1.45pm \nSat: 9am-12.45pm\n\n";
                $msg .= "Palm Harbor: \nThu: 7 - 1pm | 3.30 - 6.15pm \nSat: 9am - 12.30pm\n";
            }
            elseif($query[0] == 'mystatus')
            {
                if($patient)
                {
                    $name = $patient->fname;
                    
                    $visit = $this->patient->getPatientLastVisit($patient->id);
                    if($visit)
                    {
                        $med_days = $visit->is_med == 1 ? $visit->med_days : $visit->no_med_days;                        
                        $date = date('M d, Y',  strtotime($visit->visit_date . " + $med_days days"));
                        $msg = "Hi $name,";
                        $msg .=  $patient->status == 1? "\n To-date you are a current patient and can continue your wkly visits.\nCall 727-412-8208 to schedule a wkly appt.":"After $date you would have to restart the program. (> 6 mo of absentee)\nCall 727-412-8208 to schedule an appt.";
                    }
                    else
                    {
                        if($patient->last_status_date)
                        {
                            $last_status = $this->patient->getLastStatus($patient->id);
                            $location = '';
                            if($last_status)
                            {
                                $location = $last_status->location_id ? '@'.getLocation($last_status->location_id)->name:'';
                            }
                            $date = date('M d, Y',  strtotime($patient->last_status_date));
                            $msg = "Hi $name,";
                            $msg .=  $patient->status == 1? "\n To-date you are a current patient and can continue your wkly visits.\nCall 727-412-8208 to schedule a wkly appt.":"After $date you would have to restart the program. (> 6 mo of absentee)\nCall 727-412-8208 to schedule an appt.";
                        }
                        else
                        {
                            $msg = "Sorry, we cannot find any record about you at the moment. \nPlease call our hotline: \n727-412-8208";
                        }
                    }
                }
                else
                {
                    $msg = "Unable to obtain your info.\nPlease call 727-412-8208 \nDoctors’ Weight Loss Center";
                }
            }
            elseif($query[0] == 'help' || $query[0] == '?')
            {
                //$msg = "lartime (for Largo)\npintime (for Pinellas Park)\npaltime (for Palm Harbor)\n4thtime (for St Pete)\nlartime mon\n(gives you the Monday timings for Largo)\n\nmystatus\n(gives your current status todate)";
                $msg = "largotime \n4thtime \npalmtime \n\nmystatus \n\nmyappt";
            }
            elseif($query[0] == 'myappt')
            {
                
                if($patient)
                {
                    $name = $patient->fname;
                    $appoint = $this->appoint->getAppForPatient($patient->id);
                    if($appoint)
                    {   
                        $nv = date('D d M', strtotime($appoint->date));
                        $nvt = date('g.ia',strtotime($appoint->time));  
                        if($appoint->date >= date('Y-m-d') && $appoint->no_show == 0)
                        {
                            $allapp = $this->appoint->getFutureAppForPatients($patient->id);
                            if(count($allapp)>1)
                            {
                                $msg ="Hi $name, \nYour next appts are";
                                foreach($allapp as $app)
                                {
                                    $nvv = date('D d M', strtotime($app->date));
                                    $nvtt = date('g.ia',strtotime($app->time));  
                                    
                                    $msg .= " \n$nvv $app->name $nvtt";
                                }
                                
                                $msg .="\nTo reschedule please call DWLC 727-412-8208. \nFor HELP type: ?"; 
                            }
                            else
                            {
                               $msg ="Hi $name, \nYour next appt is at $nv @$appoint->name $nvt \nTo reschedule please call DWLC 727-412-8208. \nFor HELP type: ?"; 
                            }
                            
                        }
                        elseif($appoint->no_show == 1)
                        {
                            $msg = "Hi $name, \nWe missed you on $nv @$appoint->name. \nTo reschedule please call DWLC 727-412-8208. \nFor HELP type: ?";
                        }
                        else
                        {
                            $msg = "No records found.\nPlease call 727-412-8208 \nDoctors’ Weight Loss Center \nFor HELP type: ?";
                        }                        
                    }
                    else
                    {
                        $msg = "No records found.\nPlease call 727-412-8208 \nDoctors’ Weight Loss Center \nFor HELP type: ?";
                    }
                }
                else
                {
                    $msg = "No records found.\nPlease call 727-412-8208 \nDoctors’ Weight Loss Center \nFor HELP type: ?";
                }
            }
            elseif($query[0] == 'stop' || $query[0] == 'st') 
            {   
                if($patient)
                {
                   $this->patient->updatePatient($patient->id,array('sms' => 0));
                }                
            }  
            elseif($query[0] == 'start') 
            {   
                if($patient)
                {
                   $this->patient->updatePatient($patient->id,array('sms' => 1));
                }                
            }  
            else
            {
                $msgg = "";
                
                if($patient) 
                {
                    $appo = $this->appoint->getAppForPatient($patient->id);
                    
                    $msgg .= "N: ".$patient->lname." ".$patient->fname."\n";
                
                    $msgg .= "P: ".$from."\n";
                    $msgg .= "T: ".$bodyori;   
                    
                    if($appo)
                    {
                        $msgg .= "\nA: ".date('D m/d',strtotime($appo->date))." | ".date('H:i',strtotime($appo->time))." | ".$appo->name;
                    }

                    SendSMSnew('+17272497853', $msgg);
                }
                
//                $msg = "Doctor's Weight Loss Center \n727-412-8208";
            }
        }
        
        if(!empty($msg))
        {
            if($timeq)$msg .="\nDWLC. 727-412-8208";
            SendSMSnew($from, $msg);
            
            if($patient)
            {
                $data = array();
                $data['patient_id'] = $patient->id;
                $data['msg'] = $msg;
                $data['status'] = 1;
                $data['phone'] = $from;
                $data['created'] = date('Y-m-d H:i:s');
                $this->patient->addSmsLog($data);
            }
        }
        
        
    }
    
    public function voice()
    {
        header("content-type: text/xml");
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        echo "<Response>
                <Say voice='alice'>
                Welcome to Doctor's Weight Loss Center
                </Say>
                <Pause length='1'/>
                <Gather numDigits=\"1\" action=\"http://drsweightloss.center/sms/voiceforward/\" method=\"POST\">
		<Say voice='alice'>
			Please press 1 to forward this call to our hotline. 
	  		Press any other key to start over.
	  	</Say>
                </Gather>
                <Say voice='alice'>
                    Or Please call our hotline 7274128208
                </Say>
                
                </Response>";
    }
    
    public function voiceforward()
    {
        $post = $this->input->post();
        if($post['Digits'] != '1') {
		header("Location: http://drsweightloss.center/sms/voice/");
		die;
	}
        
        header("content-type: text/xml");
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        
        echo "<Response>";
        if ($post['Digits'] == '1') {
               echo "<Dial>+17274128208</Dial>
               <Say voice='alice'>The call failed Please try our hotline 7274128208.</Say>";
               } 
        echo "</Response>";

    }
    
    public function voicecall()
    {
        header("content-type: text/xml");
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        echo "<Response>
                <Say voice='alice'>
                This is an automates call from software system.
                </Say>                  
                </Response>";
    }
    
    public function testsms()
    {
        $media = array('mediaUrl'=>'http://beta.drsweightloss.center/assets/img/B12_test.png');
        $msg = site_url("/assets/upload/queue_alert/test.jpg");
        $msg = "this is a test";
        $res = SendSMSnew('+94777367567', $msg);
        print_r($res);
        die('test');
    }
    
    public function test()
    {
        sendErrorEmail('This is a test');
        die('done');
    }
    
    
    public function createCall()
    {
        $url = site_url('sms/voicecall');
        $res = CreateCall($url);
        print_r($res);
        die('test');
    }
    
}
