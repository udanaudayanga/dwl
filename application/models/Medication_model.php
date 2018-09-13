<?php
/**
 * Description of Medication_model
 *
 * @author Udana
 */
class Medication_model extends CI_Model
{
    public function getAll()
    {
        return $this->db->get('prev_meds')->result();
    }
    
    public function get($id)
    {
        return $this->db->where('id',$id)->get('prev_meds')->row();
    }
    
    public function update($id,$data)
    {
        $this->db->where('id',$id)->update('prev_meds',$data);
    }
    
    public function delete($id)
    {
        $this->db->where('id',$id)->delete('prev_meds');
    }
    
    public function add($data)
    {
        $this->db->insert('prev_meds',$data);
    }
}
