<?php
/**
 * Description of Appoint_model
 *
 * @author Udana
 */
class Appoint_model extends CI_Model
{
    public function getAll($loc = null,$date= null)
    {
        $this->db->select('a.*,p.fname,p.lname,p.phone,p.email,l.abbr')
            ->from('appointments a')
            ->join('patients p','a.patient_id = p.id','left')
            ->join('locations l','a.location_id = l.id','left');
        
        if($date)$this->db->where('date',$date);
        if($loc)$this->db->where('a.location_id',$loc);
        
        $query = $this->db->order_by('date ASC, time ASC')->get();
        
        return $query->result();
    }
    
    
    public function addAppoint($data)
    {
        $this->db->insert('appointments',$data);
    }
    
    public function delAppoint($id)
    {
        $this->db->where('id',$id)->delete('appointments');
    }
    
    public function getAppForPatient($id)
    {        
        $query = $this->db->select('a.*,l.name')
                ->from('appointments a')
                ->join('locations l','a.location_id = l.id','left')
                ->where('patient_id',$id)
                ->order_by('date','DESC')
                ->get();
        return $query->row();
    }
    
    public function getFutureAppForPatients($id)
    {
        $date = date('Y-m-d');
        $query = $this->db->select('a.*,l.name')
                ->from('appointments a')
                ->join('locations l','a.location_id = l.id','left')
                ->where('patient_id',$id)
                ->where('date >=',$date)
                ->order_by('date','ASC')
                ->get();
        return $query->result();
    }
    
    public function addBlock($data)
    {
        $this->db->insert('appoint_block',$data);
    }
    
    public function getBlocks($loc = null,$date= null)
    {
        $this->db->select('*');
        if($date)$this->db->where('date',$date);
        if($loc)$this->db->where('location_id',$loc);
        
        $query = $this->db->get("appoint_block");
        
        return $query->result();
    }
    
    public function hasAppoints($date,$loc_id,$start,$end,$type = 1)
    {
//        $start = $start.":00";
//        $end = $end.":00";
        $this->db->where("date",$date)
                ->where("location_id",$loc_id)
                ->where("time >=",$start)
                ->where("time <",$end);
        
        if($type == 2) $this->db->where('type',1);
        
        $query = $this->db->get('appointments');
  
        return $query->num_rows()>0? TRUE:FALSE;
    }
    
    public function isBlocked($date,$loc_id,$time,$new = FALSE)
    {
        $time = $time.":00";
        
        $sql = "SELECT * 
                FROM appoint_block 
                WHERE location_id = $loc_id 
                AND date = '$date'
                AND start <= '$time' AND end >= '$time'";
        
        if(!$new)$sql .= " AND type = 1";
        
        $query = $this->db->query($sql);
        return $query->num_rows()>0? TRUE:FALSE;
    }
    
    public function hasBlock($date,$loc_id,$start,$end)
    {
//        $start = $start.":00";
//        $end = $end.":00";
        $sql = "SELECT * 
                FROM appoint_block 
                WHERE location_id = $loc_id 
                AND date = '$date'
                AND ((start > '$start' AND start < '$end') OR (end > '$start' AND end < '$end') OR (start <= '$start' AND end >= '$end'))";
        
        $query = $this->db->query($sql);
//        echo $this->db->last_query();
//        die();
        return $query->num_rows()>0? TRUE:FALSE;
    }
    
    public function delBlock($id)
    {
        $this->db->where('id',$id)->delete('appoint_block');
    }
    
    public function update($id,$data)
    {
        $this->db->where('id',$id)->update('appointments',$data);
    }
    
    public function getHistory($patient_id)
    {
        $query = $this->db->select('a.*,l.name,concat(s.lname," ",s.fname) as sname')
                ->from('appointments a')
                ->join('locations l','a.location_id = l.id','left')
                ->join('users s','a.staff_id = s.id','left')
                ->where('a.patient_id',$patient_id)
                ->order_by('a.date','desc')
                ->get();
        
        return $query->result();
    }
    
    public function getAppointsForDay($date)
    {
        $query = $this->db->select('a.*,p.fname,p.lname,p.phone,p.email,l.name as loc_name')
                    ->from('appointments a')
                    ->join('patients p','a.patient_id = p.id AND p.sms = 1','left')
                    ->join('locations l','a.location_id = l.id','left')
                    ->where('date',$date)
                    ->get();
       return $query->result();
    }
    
    public function getByNames($fname,$lname,$phone)
    {
        $this->db->where('fname',$fname)->where('lname',$lname);
        
        if(!empty($phone)) $this->db->or_where('phone',$phone);
        
        $query = $this->db->get('patients');
        return $query->row();
    }
}
