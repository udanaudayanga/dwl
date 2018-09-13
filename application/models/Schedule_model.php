<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Schedule_model
 *
 * @author Udana
 */
class Schedule_model extends CI_Model
{
    public function getshifts($location_id,$start,$end,$user = 'all')
    {
        $sql = "SELECT es.*,u.fname,u.lname,l.abbr,u.id as user_id
                                    FROM emp_shifts es
                                    LEFT JOIN users u ON es.user_id = u.id 
                                    LEFT JOIN locations l on es.location_id = l.id
                                    WHERE es.date BETWEEN '$start' AND '$end'";
        
        if($location_id != 'all') $sql .= " AND es.location_id = $location_id";
        if($user != 'all') $sql .= " AND es.user_id = $user";
        
        $sql .= " ORDER BY shift ASC";
        
        $query = $this->db->query($sql);
        
        return $query->result();
    }
    
    public function addShift($data)
    {
        $this->db->insert('emp_shifts',$data);
    }
    
    public function removeShift($id)
    {
        $this->db->where('id',$id)->delete('emp_shifts');
    }
    
    public function getDailyShifts($date,$location)
    {
        $sql = "SELECT es.*,u.fname,u.lname,l.abbr
                FROM emp_shifts es
                LEFT JOIN users u ON es.user_id = u.id 
                LEFT JOIN locations l on es.location_id = l.id
                WHERE es.date = '$date'";
        
        if($location != 'all') $sql .= " AND es.location_id = $location";
        
        $sql .= " ORDER BY shift ASC";
        
        $query = $this->db->query($sql);
        
        return $query->result();
    }
    
    public function updateShift($id,$data)
    {
        $this->db->where('id',$id)->update('emp_shifts',$data);
    }
    
    public function addShiftBatch($data)
    {
        $this->db->insert_batch('emp_shifts',$data);
    }
    
    public function isShiftExist($data)
    {
        $start = $data['start'].":00";
        $end = $data['end'].":00";
        $date = $data['date'];
        $user_id = $data['user_id'];
        $sql = "SELECT * 
                FROM emp_shifts 
                WHERE user_id = $user_id 
                AND DATE = '$date'
                AND ((start > '$start' AND start < '$end') OR (end > '$start' AND end < '$end') OR (start <= '$start' AND end >= '$end'))";
        
        $query = $this->db->query($sql);
        return $query->num_rows()>0? TRUE:FALSE;
    }
    
    public function isShiftTypeExist($data)
    {
        $date = $data['date'];
        $user_id = $data['user_id'];
        $shift = $data['shift'];
        $sql = "SELECT * 
                FROM emp_shifts 
                WHERE user_id = $user_id 
                AND DATE = '$date'
                AND shift = $shift";
        
        $query = $this->db->query($sql);
        return $query->num_rows()>0? TRUE:FALSE;
    }
    
    public function getShiftsForDay($date)
    {
        $sql = "SELECT es.*,u.fname,u.lname,l.abbr,u.phone
                FROM emp_shifts es
                LEFT JOIN users u ON es.user_id = u.id 
                LEFT JOIN locations l on es.location_id = l.id
                WHERE es.date = '$date'";
        
        $query = $this->db->query($sql);
        return $query->result();
    }
}
