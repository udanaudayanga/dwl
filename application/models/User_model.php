<?php
/**
 * Description of User_model
 *
 * @author Udana
 */
class User_model extends CI_Model
{
    public function add($data)
    {
	$this->db->insert('users',$data);
    }
    
    public function getAll()
    {
	$query = $this->db->get('users');
	return $query->result();
    }
    
    public function getUsersByType($type)
    {
        $query = $this->db->where('type',$type)->get('users');
	return $query->result();
    }
    
    public function get($id)
    {
	return $this->db->where('id',$id)->get('users')->row();
    }
    
    public function remove($id)
    {
	$this->db->where('id',$id)->delete('users');
    }
    
    public function update($id,$data)
    {
	$this->db->where('id',$id)->update('users',$data);
    }
    
    public function isUsernameExist($username)
    {
	$query = $this->db->where('username',$username)->get('users');
	
	return $query->num_rows()>0 ? TRUE:FALSE;
    }
    
    public function getUserByUsername($username)
    {
	$query = $this->db->where('username',$username)->get('users');
	
	return $query->row();
    }
    
    public function getUserByType($types)
    {
        $query = $this->db->where_in('type',$types)->get('users');
        
        return $query->result();
    }

}
