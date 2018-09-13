<?php
/**
 * Description of Promos_model
 *
 * @author Udana
 */
class Promos_model extends CI_Model
{
    public function getAll()
    {
        $query = $this->db->get('promos');        
        return $query->result();
    }
    
    public function getPromoTypes()
    {
        $query = $this->db->get('promo_types');        
        return $query->result();
    }
    
    public function add($data)
    {
        $this->db->insert('promos',$data);
    }
    
    public function delete($id)
    {
        $this->db->where('id',$id)->delete('promos');
    }
    
    public function getPromo($id)
    {
        $query = $this->db->where('id',$id)->get('promos');
        return $query->row();
    }
    
    public function getAllActive()
    {
        $query = $this->db->where('end >',date('Y-m-d'))->get('promos');
        return $query->result();
    }
    
    public function addGeneral($data)
    {
        $this->db->insert('general_promos',$data);
    }
    
    public function getAllGeneral()
    {
        $query = $this->db->select("g.id as gen_id,g.name as gen_name,g.code,g.created as gen_created,p.*")
                ->from('general_promos g')
                ->join('promos p','g.promo_id = p.id','left')
                ->get();
        
        return $query->result();
    }
    
    public function deleteGen($id)
    {
        $this->db->where('id',$id)->delete('general_promos');
    }
    
    public function getGenCoupon($coupon)
    {
        $query = $this->db->where('code',$coupon)->get('general_promos');
        return $query->row();
    }
    
    public function getPromoWinList($promo_id)
    {
        $query = $this->db->select("wl.*,p.fname,p.lname,p.email,p.id as pid")
                ->from('scratch_promo wl')
                ->join('patients p','wl.patient_id = p.id','left')
                ->where('wl.promo_id',$promo_id)
                ->get();
        
        return $query->result();
    }
}
