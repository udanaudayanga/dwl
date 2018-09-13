<?php
/**
 * Description of Medication_model
 *
 * @author Udana
 */
class Category_model extends CI_Model
{
    public function getAll()
    {
        return $this->db->get('categories')->result();
    }
    
    public function get($id)
    {
        return $this->db->where('id',$id)->get('categories')->row();
    }
    
    public function update($id,$data)
    {
        $this->db->where('id',$id)->update('categories',$data);
    }
    
    public function delete($id)
    {
        $this->db->where('id',$id)->delete('categories');
    }
    
    public function add($data)
    {
        $this->db->insert('categories',$data);
    }
}
