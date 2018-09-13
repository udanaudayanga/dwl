<?php
/**
 * Description of Ext_model
 *
 * @author Udana
 */
class Ext_model extends CI_Model
{
    public function getReminders()
    {
        $query = $this->db->get('reminders');
        return $query->result();
    }
    
    public function isReminderExist($patient_id,$reminder_id)
    {
        $query = $this->db->where('patient_id',$patient_id)->where('reminder_id',$reminder_id)->where('hide',0)->get('patient_reminders');
        return $query->num_rows()>0 ? TRUE:FALSE;
    }
    
    public function insert($data)
    {
        $this->db->insert_batch('patient_reminders',$data);
    }
    
    public function getPatientReminders($patient_id)
    {
        $query = $this->db->query("SELECT pr.*,r.text
                                  FROM patient_reminders pr
                                  LEFT JOIN reminders r ON pr.reminder_id = r.id
                                  WHERE pr.patient_id = $patient_id");
        
        return $query->result();
    }
    
    public function isOrderExist($patient_id,$amount)
    {
        $query = $this->db->select('o.*')
                ->from('orders o')
                ->join('patients p','o.patient_id = p.id','left')
                ->where('o.patient_id',$patient_id)
                ->where('o.net_total >=',$amount)
                ->where('p.status',1)
                ->get();
        
        return $query->num_rows()>0?TRUE:FALSE;
    }
    
    public function saveScratchPromo($patient_id,$promo_id)
    {
        $query = $this->db->where('promo_id',$promo_id)->where('patient_id',$patient_id)->get('scratch_promo');
        if($query->num_rows() == 0)
        {
            $this->db->insert('scratch_promo',array('patient_id'=>$patient_id,'promo_id'=>$promo_id,'created'=>date('Y-m-d H:i:s')));
        }
    }
    
    public function updateScratch($patient_id,$promo_id)
    {
        $this->db->where('patient_id',$patient_id)->where('promo_id',$promo_id)->update('scratch_promo',array('scratched'=>1));
    }
    
    public function updateClaimed($id)
    {
        $this->db->where('id',$id)->update('scratch_promo',array('claimed'=>1));
    }
}
