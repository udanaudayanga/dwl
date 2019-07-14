<?php
/**
 * Description of Util_model
 *
 * @author Udana
 */
class Util_model extends CI_Model
{
    public function getVisits($date,$loc_id)
    {
        $sql = "SELECT v.*
                FROM visits v
                LEFT JOIN orders o ON v.order_id = o.id
                where (CAST(v.visit_date AS DATE) = '$date')";
        
        if(!empty($loc_id))
        {
            $sql .= " AND o.location_id = $loc_id";
        }
        
        $query = $this->db->query($sql);
        return $query->result();
    }  
    
    public function addBlock($data)
    {
        $this->db->insert_batch('appoint_block', $data);
    }

    public function addActivityLog($data)
    {
        $this->db->insert('activity_log',$data);
    }

    //get activities
    function getActivities()
    {
        $query = $this->db->query('SELECT al.*,u.fname,u.lname
                                FROM activity_log al
                                LEFT JOIN users u ON al.uid = u.id
                                ORDER BY al.created DESC
                                '); 

        return $query->result();
    }

}
