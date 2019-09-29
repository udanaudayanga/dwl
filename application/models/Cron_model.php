<?php
/**
 * Description of Cron_model
 *
 * @author Udana
 */
class Cron_model extends CI_Model
{
    public function getPetersburgActive()
    {
        $sql = "SELECT p.*
                FROM patients p
                INNER JOIN (
                    SELECT  DISTINCT o.patient_id 
                    FROM    orders o
                    WHERE   o.location_id = 4		
                ) o ON p.id = o.patient_id
                WHERE p.status = 1";
        
        $query = $this->db->query($sql);
        return $query->result();
    }
    
    public function getPastDuePatients($frd,$tod)
    {
        $from = date('Y-m-d',strtotime("-$frd days"));
        $to = date('Y-m-d',strtotime("-$tod days"));
        
        $sql = "SELECT v.*,p.fname,p.lname,p.phone
                FROM visits v
                LEFT JOIN patients p ON v.patient_id = p.id 
                WHERE ADDDATE(CAST(v.visit_date AS DATE), INTERVAL v.med_days DAY) BETWEEN '$to' AND '$from'
                AND v.patient_id NOT IN (SELECT vv.patient_id FROM visits vv WHERE vv.visit_date > v.visit_date)
                and v.is_med = 1 AND p.sms = 1 AND p.freezed = 0";
        
        $query = $this->db->query($sql);
        return $query->result();
    }
    
    public function getPatientsBasedOnOrderTotal($net_total)
    {
        $sql = "SELECT o.patient_id,p.fname,p.lname,p.phone
                FROM orders o
                LEFT JOIN patients p ON o.patient_id = p.id 
                WHERE o.net_total >= $net_total
                AND p.status = 1
                AND p.sms = 1
                GROUP BY o.patient_id ";
        
        $query = $this->db->query($sql);
        return $query->result();
    }
   
    public function getForPDMP($location_id,$start,$end)
    {
        $sql = "SELECT v.*,p.fname,p.lname,p.address,p.city,p.zip,s.abbr,p.dob,p.gender,p.phone,o.payment_type,o.net_total,l.dea,l.address as caddress
                            FROM visits v
                            LEFT JOIN orders o ON v.order_id = o.id 
                            LEFT JOIN patients p ON v.patient_id = p.id 
                            LEFT JOIN states s ON p.state = s.id 
                            LEFT JOIN locations l ON o.location_id = l.id
                            WHERE v.prescription_no > 0 
                            AND (CAST(v.visit_date AS DATE) BETWEEN '$start' AND '$end')
                            AND o.location_id = $location_id";
        
        $query = $this->db->query($sql);
        return $query->result();       
        
    }
}
