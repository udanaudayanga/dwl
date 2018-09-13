<?php
/**
 * Description of Adz_model
 *
 * @author Udana
 */
class Adz_model extends CI_Model
{
    public function getAll()
    {
        $query = $this->db->order_by('updated','DESC')->get('mail_ads');
        return $query->result();
    }
    
    public function add($data)
    {
        $this->db->insert('mail_ads',$data);
    }
    
    public function getActiveAd()
    {
        $query = $this->db->where('status',1)->get('mail_ads');
        return $query->row();
    }
    
    public function get($id)
    {
        $query = $this->db->where('id',$id)->get('mail_ads');
        return $query->row();
    }
    
    public function delete($id)
    {
        $this->db->where('id',$id)->delete('mail_ads');
    }
    
    public function update($id,$data)
    {
        $this->db->where('id',$id)->update('mail_ads',$data);
    }
}
