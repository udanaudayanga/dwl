<?php
/**
 * Description of Mail_model
 *
 * @author Udana
 */
class Mail_model extends CI_Model
{
    public function getMailsToDeliver()
    {        
	$query = $this->db->select('mq.*,p.email')
		->from('mail_queue mq')
		->join('patients p','mq.patient_id = p.id','left')
		->where('delivered',0)
                ->where('deliver_after <','now()')
		->order_by('created','ASC')
		->get();
	
	return $query->result();
    }
    
    public function send_mail($to,$subject,$message,$attach = null)
    {
	$this->email->from($this->config->item('app_email'), $this->config->item('app_email_name'));
	$this->email->to($to);

	$this->email->subject($subject);
	$this->email->message($message);
	if($attach)
	{
	    $buffer = file_get_contents($attach);
	    $this->email->attach($buffer, 'attachment',  basename($attach), 'application/pdf');
	}

	$this->email->send();
    }
    
    public function update_mail($id,$data)
    {
	$this->db->where('id',$id)->update('mail_queue',$data);
    }
    
    public function add_mail($data)
    {
	$this->db->insert('mail_queue',$data);
    }
    
    public function getTemplates()
    {
        $query = $this->db->get('mail_templates');
        return $query->result();
    }
    
    public function getTemplate($id)
    {
        $query = $this->db->where('id',$id)->get('mail_templates');
        return $query->row();
    }
    
    public function getMailQueue()
    {
        $query = $this->db->select('mq.*,p.fname,p.lname')
                ->from('mail_queue mq')
                ->join('patients p','mq.patient_id = p.id','left')
                ->order_by('created','DESC')
                ->get();
        return $query->result();
    }
}
