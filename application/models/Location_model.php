<?php

/**
 * Description of Location_model
 *
 * @author Udana
 */
class Location_model extends CI_Model
{
    public function add($data)
    {
	$this->db->insert('locations',$data);
    }
    
    public function get($id)
    {
	return $this->db->where('id',$id)->get('locations')->row();
    }
    
    public function getAll($status = FALSE)
    {
        if($status)$this->db->where('status',1);
        
	$query = $this->db->get('locations');
	return $query->result();
    }
    
    public function update($id,$data)
    {
	$this->db->where('id',$id)->update('locations',$data);
    }
    
    public function delete($id)
    {
        $this->db->where('id',$id)->delete('locations');
    }
}
