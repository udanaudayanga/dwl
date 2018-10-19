<?php

/**
 * Description of Injmeds_model
 *
 * @author Udana
 */
class Injmeds_model extends CI_Model
{
    public function getLocationWeek($loc_id,$week)
    {
        $query = $this->db->where('location_id',$loc_id)->where('week',$week)->get('injmeds');
        
        return $query->row();
    }
    
    public function get($loc_id,$date)
    {
        $query = $this->db->where('week',$date)->where('location_id',$loc_id)->get('injmeds');
        return $query->row();
    }
    
    public function addGiven($data)
    {
        $this->db->insert('injmeds',$data); 
        return $this->db->insert_id();
    }
    
    public function updateGiven($id,$data)
    {
        $this->db->where('id',$id)->update('injmeds',$data);
    }
    
    public function getById($id,$array = false)
    {
        $query = $this->db->where('id',$id)->get('injmeds');
        return $array? $query->row_array():$query->row();
    }
    
    public function getIMUsage($loc_id,$start,$end)
    {
        $sql = "SELECT im.*,u.fname,u.lname
                FROM injmed im
                LEFT JOIN users u ON im.staff_id = u.id
                WHERE im.loc_id = $loc_id
                AND date BETWEEN '$start' AND '$end'";
        $query = $this->db->query($sql);
        return $query->result();
    }
    
    public function getInjMeds($locId,$start,$end)
    {
        $sql = "SELECT im.*,u.fname,u.lname
                FROM injmed im
                LEFT JOIN users u ON im.staff_id = u.id
                WHERE loc_id = $locId
                AND date BETWEEN '$start' AND '$end'";
        $query = $this->db->query($sql);
        return $query->result();
    }
    
    public function getInjMedsArr($locId,$start,$end)
    {
        $sql = "SELECT loc_id,date,med1_g,med2_g,med3_g,med4_g,inj1_g,inj2_g,inj3_g,inj4_g 
                FROM injmed
                WHERE loc_id = $locId
                AND date BETWEEN '$start' AND '$end'";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    public function getIMU($im_id,$day)
    {
        $query = $this->db->where('im_id',$im_id)->where('date',$day)->get('injmeds_usage');        
        return $query->row();
    }
    
    public function addIMU($data)
    {
        $this->db->insert('injmeds_usage',$data);
    }
    
    public function updateIMU($id,$data)
    {
        $this->db->where('id',$id)->update('injmeds_usage',$data);
    }
    
    public function getIM($loc_id,$date)
    {
        $query = $this->db->where('loc_id',$loc_id)->where('date',$date)->get('injmed');
        
        return $query->row();
    }
    
    public function addIM($data)
    {
        $this->db->insert('injmed',$data);
    }
    
    public function updateIM($id,$data)
    {
        $this->db->where('id',$id)->update('injmed',$data);
    }
}
