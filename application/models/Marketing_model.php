<?php

/**
 * Description of Marketing_model
 *
 * @author Udana
 */
class Marketing_model extends CI_Model
{
    public function getCustomLists()
    {
        $query = $this->db->get('custom_lists');
        return $query->result();
    }
    
    public function addCL($data)
    {
        $this->db->insert('custom_lists',$data);        
        return $this->db->insert_id();
    }
    
    public function updateCL($id,$data)
    {
        $this->db->where('id',$id)->update('custom_lists',$data);
    }
    
    public function getCL($id)
    {
        $query = $this->db->where('id',$id)->get('custom_lists');
        return $query->row();
    }
    
    public function getCLMems($id)
    {
        $query = $this->db->select('cm.*,p.fname,p.lname,p.email,p.phone')
                ->from('cl_members cm')
                ->join('patients p','cm.patient_id = p.id','left')
                ->where('cl_id',$id)
                ->get();
        
        return $query->result();
    }
    
    public function isInCList($list_id,$patient_id)
    {
        $query = $this->db->where('cl_id',$list_id)->where('patient_id',$patient_id)->get('cl_members');
        return $query->num_rows() > 0 ? TRUE:FALSE;
    }
    
    public function addMemToCL($data)
    {
        $this->db->insert('cl_members',$data);
    }
    
    public function addCLMemBatch($data)
    {
        $this->db->insert_batch('cl_members',$data);
    }    
    
    public function remCLMem($id)
    {
        $this->db->where('id',$id)->delete('cl_members');
    }
    
    public function delCL($id)
    {
        $this->db->where('id',$id)->delete('custom_lists');
        $this->db->where('cl_id',$id)->delete('cl_members');
    }
    
    public function isCouponExist($coupon)
    {
        $query = $this->db->where('coupon',$coupon)->get('coupon');
        return $query->num_rows()>0? TRUE:FALSE;
    }
    
    public function addCoupon($coupon)
    {
        $this->db->insert('coupon',$coupon);
    }
    
    public function getCoupons()
    {
        $query = $this->db->select('c.*,p.fname,p.lname,pr.name as promo')
                ->from('coupon c')
                ->join('patients p','c.patient_id = p.id','left')
                ->join('promos pr','c.promo_id = pr.id','left')
                ->get();
        
        return $query->result();
    }
    
    public function getCoupon($coupon)
    {
        $query = $this->db->where('coupon',$coupon)->get('coupon');
        return $query->row();
    }
    
    public function updateCoupon($coupon,$data)
    {
        $this->db->where('coupon',$coupon)->update('coupon',$data);
    }
}
