<?php
/**
 * Description of Mail
 *
 * @author Udana
 */
class Mail extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model("Mail_model", "mail");
    }
    public function welcomeMail()
    {
	$this->email->from('info@drsweightloss.center');
	$this->email->to('udanaudayanga@gmail.com');

	$this->email->subject('Welcome to DWL!');
	$message = $this->load->view('emails/welcome',array(),true);
	$this->email->message($message);	

	$this->email->send();
	
	die('Mail Sent');
    }
    
    public function wc()
    {
	$this->load->view("emails/welcome");
    }
    
    public function validate()
    {
        $this->load->model('Patient_model','patient');
        $get = $this->input->get();	
	$this->load->library('encrypt');
        $id = $get['i'];
        $emailEncrypt = $this->encrypt->decode(base64_decode($get['e']));
        
        
        $patient = $this->patient->getPatient($id);
        $result = ($patient->email == $emailEncrypt)? TRUE :FALSE;
        
        if($result) $this->patient->updatePatient($id,array('email_validated'=>1));
        
	$this->load->view("mail/validate",array('result'=>$result));
    }
    
    public function review_alert($id,$email_encrypt)
    {
	$this->load->library('encrypt');
	$this->load->model("Patient_model", "patient");
	$this->load->model("Mail_model", "mail");
	
	$emailEncrypt = $this->encrypt->decode(base64_decode($email_encrypt));
	$patient = $this->patient->getPatient($id);
	
	if($emailEncrypt == $patient->email)
	{
	    $data = array();
	    $data['fname'] = $patient->fname;
	    $message = $this->load->view('emails/review_alert',$data,TRUE);
	    $subject = "A Quick Reminder …";
	    
	    $mail = array('patient_id'=>$patient->id,'subject'=>$subject,'content'=>$message,'created'=>time());
	    $this->mail->add_mail($mail);
	    
	    
	    $this->load->view("mail/review_alert",array('t1'=>'Thank you!','t2'=>'Reminder added successfully.'));
	}
	else
	{
	    $this->load->view("mail/review_alert",array('t1'=>'Oops!','t2'=>'Something wrong with the link'));
	}
    }
    
    
}
