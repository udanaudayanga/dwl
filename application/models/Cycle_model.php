<?php
/**
 * Description of Cycle_model
 *
 * @author Udana
 */
class Cycle_model extends CI_Model
{
    public function getCycle($id)
    {
        $query = $this->db->where('id',$id)->get('cycle');
        return $query->row();
    }
    
    public function getLastPhase($patient_id)
    {
        $query = $this->db->where('patient_id',$patient_id)->order_by('created','DESC')->get('cycle');
        return $query->row();
    }
    
    public function updatePhase($phase_id, $data)
    {
        $this->db->where('id',$phase_id)->update('cycle',$data);
    }
    
    public function addPhase($data)
    {
        $this->db->insert('cycle',$data);
        return $this->db->insert_id();
    }
}
