<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Queue_model
 *
 * @author Udana
 */
class Queue_model extends CI_Model
{
    public function getPatient($data)
    {
        $first = trim($data['first']);
        $last = trim($data['last']);
        
        $dob = date('Y-m-d', strtotime($data['dob']));
        $sql = "SELECT *
                FROM patients
                WHERE SUBSTR(lname,1,3) = '$last' 
                AND SUBSTR(fname,1,3) = '$first'
                AND dob = '$dob'";
        
        $query =  $this->db->query($sql);        
        return $query->result();
    }
    
    public function add($data)
    {
        $this->db->insert('queue',$data);
        return $this->db->insert_id();
    }
    
    public function addItems($data)
    {
        $this->db->insert_batch('queue_items',$data);
    }
    
    public function getQueue($pid)
    {
        $date = date('Y-m-d');
        $sql = "SELECT *
                FROM queue 
                where patient_id = $pid
                AND CAST(created as DATE) = '$date'";
        
        $query = $this->db->query($sql);
        return $query->row();
    }
    
    public function removeItems($qid)
    {
        $this->db->where('qid',$qid)->delete('queue_items');
    }
    
    public function getQueueItems($qid)
    {
        $queue = $this->db->where('qid',$qid)->get('queue_items');
        return $queue->result();
    }
    
    public function getTodayQueue()
    {
        $date = date('Y-m-d');
        $sql = "SELECT q.*,p.fname,p.lname
                FROM queue q 
                LEFT JOIN patients p ON q.patient_id = p.id
                WHERE CAST(q.created as DATE) = '$date'
                ORDER BY q.created ASC";
        
        $query = $this->db->query($sql);
        return $query->result();
    }
    
    public function update($id,$data)
    {
        $this->db->where('id',$id)->update('queue',$data);
    }
    
    public function getQueByNames($patient)
    {
        $date = date('Y-m-d');
        $first = $patient->fname;
        $last = $patient->lname;
        $sql = "SELECT *
                FROM queue 
                where first = '$first'
                AND last = '$last'
                AND CAST(created as DATE) = '$date'";
        
        $query = $this->db->query($sql);
        return $query->row();
    }
    
    public function removeQueue($id)
    {
        $this->db->where('id',$id)->delete('queue');
    }
    
    public function getQueueMsgs()
    {
        $queue = $this->db->order_by('id','DESC')->get('queue_msg');
        return $queue->result();
    }
    
    public function addQueueMsg($data)
    {
        $this->db->insert('queue_msg',$data);
    }
    
    public function deleteQm($id)
    {
        $this->db->where('id',$id)->delete('queue_msg');
    }
    
    public function updateQueueMsg($id,$data)
    {
        $this->db->where('id',$id)->update('queue_msg',$data);
    }
    
    public function getQMByDate($date,$type)
    {
        $sql = "SELECT * 
                FROM queue_msg 
                WHERE '$date' BETWEEN START AND END 
                AND TYPE = $type";
        $query = $this->db->query($sql);
        return $query->result();
    }
    
    public function getQueueMsg($id)
    {
        $query = $this->db->where('id',$id)->get('queue_msg');
        return $query->row();
    }
}
