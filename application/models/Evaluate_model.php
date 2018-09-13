<?php
/**
 * Description of Evaluate_model
 *
 * @author Udana
 */
class Evaluate_model extends CI_Model
{
    public function getLatestPhase($patient_id)
    {
        $query = $this->db->query("SELECT * FROM evaluate where patient_id = $patient_id ORDER BY created DESC");
        return $query->row();
    }
    
    public function add($data)
    {
        $this->db->insert('evaluate',$data);
    }
    
    public function update($id,$data)
    {
        $this->db->where('id',$id)->update('evaluate',$data);
    }
    
    public function getPhases($patient_id)
    {
        $query = $this->db->query("SELECT e.*,p.fname,p.lname 
                                   FROM evaluate e 
                                   LEFT JOIN patients p ON e.patient_id = p.id
                                   where e.patient_id = $patient_id
                                    ORDER BY e.created ASC");
        
        return $query->result();
    }
    
    public function getPhase($id)
    {
        $query = $this->db->query("SELECT e.*,p.fname,p.lname 
                                   FROM evaluate e 
                                   LEFT JOIN patients p ON e.patient_id = p.id
                                   where e.id = $id
                                   ORDER BY e.created ASC");
        
        return $query->row();
    }
    
    public function remAll($patient_id)
    {
        $this->db->where('patient_id',$patient_id)->delete('evaluate');
    }
    
    public function remove($id)
    {
        $this->db->where('id',$id)->delete('evaluate');
    }
    
    public function UpdatePhaseToComplete($date)
    {
        $this->db->query("UPDATE evaluate
                        SET status = 'completed'
                        WHERE END < '$date' AND STATUS = 'inprogress'");
    }
    
        
    public function testUpdate($id,$data)
    {
        $this->db->where('id',$id)->update('test',$data);
    }
    
    public function getTest($id)
    {
        $query = $this->db->where('id',$id)->get('test');
        
        return $query->row();
    }
    
        public function getCycle($id)
    {
        $query = $this->db->where('id',$id)->get('evaluate');
        return $query->row();
    }
    
    public function getLastPhase($patient_id)
    {
        $query = $this->db->where('patient_id',$patient_id)->order_by('created','DESC')->get('evaluate');
        return $query->row();
    }
    
    public function getPhaseById($phase_id)
    {
        $query = $this->db->where('id',$phase_id)->get('evaluate');
        return $query->row();
    }
    
    public function getPhasesByPhaseInfo($patient_id,$cycle,$phase)
    {
        $query = $this->db->where('phase',$phase)->where('cycle',$cycle)->where('patient_id',$patient_id)->order_by('created','DESC')->get('evaluate');
        return $query->row();
    }
    
    public function updatePhase($phase_id, $data)
    {
        $this->db->where('id',$phase_id)->update('evaluate',$data);
    }
    
    public function addPhase($data)
    {
        $this->db->insert('evaluate',$data);
        return $this->db->insert_id();
    }
    
    public function getPhaseByVistDate($date,$patient_id)
    {
        $query = $this->db->query("SELECT * 
                                FROM evaluate 
                                WHERE patient_id = $patient_id 
                                AND START <= '$date' AND (END >= '$date' OR END IS NULL)
                                ORDER BY created DESC");
        return $query->row();
    }
    
    public function getEvalById($eval_id)
    {
        $query = $this->db->where('id',$eval_id)->get("evaluate");
        return $query->row();
    }
}
