<?php
/**
 * Description of Patient_model
 *
 * @author Udana
 */
class Patient_model extends CI_Model
{
    /**
     * Get users for weekly mail ( get users visited yesterday who has visited more than once)
     */
    public function getVisitedOnDay($day)
    {
	$next_day = date('Y-m-d', strtotime($day . ' +1 day'));
	$query = $this->db->query("SELECT * 
				    from visits v 
				    WHERE visit_date between '$day 00:00:00' and '$next_day 00:00:00'
				    AND visit > 1 ");
	
	return $query->result();
    }
    
    public function getVisitorsOnPeriod($start,$end)
    {
	$query = $this->db->query("SELECT * 
				    from visits v 
				    WHERE visit_date between '$start' and '$end'
				    AND status = 'loss'
				    AND visit > 1 ");

	return $query->result();
    }
    
    public function getVisitorsForStreak($start,$end)
    {
	$query = $this->db->query("SELECT * 
				    from visits v 
				    WHERE visit_date between '$start' and '$end'
				    AND status = 'loss'
				    AND visit > 2 ");

	return $query->result();
    }
    
    public function getPatientVisit($user_id,$visit)
    {
        $query = $this->db->select('v.*,o.location_id')
                ->from('visits v')
                ->join('orders o','v.order_id = o.id','left')
                ->where('visit',$visit)
                ->where('v.patient_id',$user_id)
                ->get();
        
	return $query->row();
    }
    
    public function getPatient($id)
    {
	$query = $this->db->where('id',$id)->get('patients');
	return $query->row();
    }
    
    public function getWeightLossPatients($visit,$status = null)
    {
	$this->db->where('visit',$visit);
	if($status)$this->db->where('status',$status);
	$query = $this->db->get('visits');
	return $query->result();
    }
    
    public function getVisitsByVisit($patient_id,$from,$to,$status)
    {
	$query = $this->db->query("SELECT * 
			    FROM visits 
			    WHERE patient_id = $patient_id 
			    AND visit <= $to 
			    AND visit >= $from 
			    AND (diff < -2 || status = 'start')");
	
	return $query->result_array();
    }
    
    public function getDefectorPatients($lastDate)
    {
	$today = date('Y-m-d');
	$next_day = date('Y-m-d', strtotime($lastDate . ' +1 day'));
	$query = $this->db->query("SELECT v.*,p.fname
				  FROM visits v
				  LEFT JOIN patients p ON v.patient_id = p.id
				  WHERE v.patient_id not in (SELECT patient_id from visits where date(visit_date) BETWEEN '$next_day' and '$today')
				  AND date(v.visit_date) = '$lastDate'");
	return $query->result();
    }
    
    public function add($data)
    {
	$this->db->insert('patients',$data);
         return $this->db->insert_id();
    }
    
    public function getPatientAC($name,$active=false)
    {
	$this->db->distinct()
		->select('id,CONCAT(lname," ",fname) as value')
		->like('fname', $name, 'both')
		->or_like('lname', $name, 'both');
        if($active)$this->db->where('status',1);
        $query = $this->db->get('patients');
	
	return $query->result_array();
    }
    
    public function getPrevMedAC($term)
    {
	$query = $this->db->distinct()
		->select('id,med as value')
		->like('med', $term, 'both')
		->get('prev_meds');
	
	return $query->result_array();
    }
    
    public function getAllPatients()
    {
        $sql = "SELECT p.id,p.fname,p.lname,p.phone,p.last_status_date,p.dob,p.status,count(v.id) as vcount,pls.patient_id as pls
                FROM patients p
                LEFT JOIN visits v ON p.id = v.patient_id
                LEFT JOIN patient_last_status pls ON p.id = pls.patient_id
                GROUP by p.id";
	$query = $this->db->query($sql);
	return $query->result();	
    }
    
    public function getPatients()
    {
        $query = $this->db->get('patients');
        return $query->result();
    }
    
    public function getLastVisit($patient_id)
    {
        $query = $this->db->select('v.*,o.location_id')
                ->from('visits v')
                ->join('orders o','v.order_id = o.id','left')
                ->where('v.patient_id',$patient_id)
                ->where('DATE(v.visit_date) <',date('Y-m-d'))
                ->order_by('v.visit_date','DESC')
                ->get();
        return $query->row();
    }
    
    public function getLastVisitNew($patient_id,$order_date)
    {
        $query = $this->db->select('v.*,o.location_id')
                ->from('visits v')
                ->join('orders o','v.order_id = o.id','left')
                ->where('v.patient_id',$patient_id)
                ->where('DATE(v.visit_date) <',$order_date)
                ->order_by('v.visit_date','DESC')
                ->get();
        return $query->row();
    }
    
    public function getLastMedVisit($patient_id)
    {
        $query = $this->db->select('v.*,o.location_id')
                ->from('visits v')
                ->join('orders o','v.order_id = o.id','left')
                ->where('DATE(visit_date) !=',date('Y-m-d'))
                ->where('v.patient_id',$patient_id)
                ->where('v.is_med',1)
                ->order_by('v.visit_date','DESC')
                ->get();
        return $query->row();
    }
    
    public function getTodayVisit($patient_id)
    {
        $query = $this->db->where('patient_id',$patient_id)
                ->where('DATE(visit_date)',date('Y-m-d'))
                ->get('visits');
        return $query->row();
    }
    
    public function add_visit($data)
    {
//        $this->db->trans_start();
//        if($data['is_med']==1)
//        {
//            $query = $this->db->select('prescription_no')->order_by('prescription_no','DESC')->get('visits');
//            $prescription = $query->row();
//            
//            $data['prescription_no'] = ($prescription && $prescription->prescription_no > 0)? ($prescription->prescription_no + 1) : 100000;
//        }
//        else
//        {
//            $data['prescription_no'] = NULL;
//        }
            
        $this->db->insert('visits',$data);
//        $this->db->trans_complete();
    }
    
    public function updateVisit($id,$data)
    {
//        $this->db->trans_start();
//        if($data['is_med']==1 && $data['prescription_no']=== NULL)
//        {
//            $query = $this->db->select('prescription_no')->order_by('prescription_no','DESC')->get('visits');
//            $prescription = $query->row();
//            
//            $data['prescription_no'] = ($prescription && $prescription->prescription_no > 0)? ($prescription->prescription_no + 1) : 100000;
//        }
//        else
//        {
//            $data['prescription_no'] = NULL;
//        }
        $this->db->where('id',$id)->update('visits',$data);
//        $this->db->trans_complete();
    }
    
    public function getLastPresNo()
    {
        $query = $this->db->select('prescription_no')->order_by('prescription_no','DESC')->get('visits');
        return $query->row();
    }
    
    public function updateDrNote($id,$data)
    {       
        $this->db->where('id',$id)->update('visits',$data);
    }
    
    public function updatePatient($id,$data)
    {
        $this->db->where('id',$id)->update('patients',$data);
    }
    
    public function getPatientVisits($patient_id)
    {
        $sql = "SELECT v.*,m1.name as med1n,m2.name as med2n,m3.name as med3n
                FROM visits v
                LEFT JOIN products m1 ON v.med1 = m1.id
                LEFT JOIN products m2 ON v.med2 = m2.id
                LEFT JOIN products m3 ON v.med3 = m3.id
                WHERE v.patient_id = $patient_id
                ORDER BY v.visit ASC";
        
        $query = $this->db->query($sql);
        
//        $query = $this->db->where('patient_id',$patient_id)
//                            ->order_by('visit_date','DESC')
//                            ->get('visits');
        return $query->result();
    }
    
    public function getStates()
    {
        return $this->db->get('states')->result();
    }
    
    public function getState($id)
    {
        return $this->db->where('id',$id)->get('states')->row();
    }
    
    public function addECG($data)
    {
        $this->db->insert('ecg',$data);
        return $this->db->insert_id();
    }
    
    public function updateECG($id,$data)
    {
        $this->db->where('id',$id)->update('ecg',$data);
    }
    
    public function getAllECG($id)
    {
        $query = $this->db->where('patient_id',$id)->order_by('created','DESC')->get('ecg');
        return $query->result();
    }
    
    public function getECG($id)
    {
        $query = $this->db->where('id',$id)->get('ecg');
        return $query->row();
    }
    
    public function delECG($id)
    {
        $this->db->where('id',$id)->delete('ecg');
    }
    
    public function addBW($data)
    {
        $this->db->insert('bw',$data);
        return $this->db->insert_id();
    }
    
    public function updateBW($id,$data)
    {
        $this->db->where('id',$id)->update('bw',$data);
    }
    
    public function getAllBW($id)
    {
        $query = $this->db->where('patient_id',$id)->order_by('created','DESC')->get('bw');
        return $query->result();
    }
    
    public function getBW($id)
    {
        $query = $this->db->where('id',$id)->get('bw');
        return $query->row();
    }
    
    public function delBW($id)
    {
        $this->db->where('id',$id)->delete('bw');
    }
    
    public function getLatestNoOfVisits($patient_id,$no_of_visits,$till_date)
    {
        $query = $this->db->query("SELECT v.*,l.abbr
                                   FROM visits v
                                   LEFT JOIN orders o ON v.order_id = o.id
                                   LEFT JOIN locations l ON o.location_id = l.id
                                   where v.patient_id = $patient_id
                                   AND (CAST(v.visit_date AS DATE) <= '$till_date')
                                   ORDER BY v.visit DESC
                                   limit $no_of_visits");
        
        return $query->result();
    }
    
    public function getVisitsForVisitPage($patient_id,$till_date,$lastRestart)
    {
        $sql = "SELECT v.*,l.abbr
                FROM visits v
                LEFT JOIN orders o ON v.order_id = o.id
                LEFT JOIN locations l ON o.location_id = l.id
                where v.patient_id = $patient_id
                AND (CAST(v.visit_date AS DATE) <= '$till_date')";
        
        if($lastRestart)
        {
            $sql .= " AND (CAST(v.visit_date AS DATE) >= '$lastRestart->date')";
        }
        
        $sql .= " ORDER BY v.visit DESC
                 limit 6";
        
        $query = $this->db->query($sql);
        return $query->result();        
    }
    
        
    public function getVisitCountSinceLastRestart($patient_id,$till_date,$lastRestart)
    {
        $sql = "SELECT v.*
                FROM visits v
                where v.patient_id = $patient_id
                AND (CAST(v.visit_date AS DATE) >= '$lastRestart->date')
                AND (CAST(v.visit_date AS DATE) <= '$till_date')";
        $query = $this->db->query($sql);
        return $query->num_rows();
    }
    
    
    
    public function getLatestVisit($patient_id)
    {
        $query = $this->db->query("SELECT v.*,l.name as location
                                   FROM visits v
                                   LEFT JOIN orders o ON v.order_id = o.id
                                   LEFT JOIN locations l ON o.location_id = l.id
                                   where v.patient_id = $patient_id
                                   ORDER BY visit_date DESC
                                   limit 1");
        return $query->row();
    }
    
    public function getLastOrder($patient_id)
    {
        $sql = "SELECT * 
                FROM orders o
                WHERE o.patient_id = $patient_id
                ORDER BY o.id DESC
                LIMIT 1";
        
        $query = $this->db->query($sql);
        
        return $query->row();
    }


    public function search($query)
    {
        $sql = "SELECT DISTINCT p.* FROM patients p WHERE ";
        $i = 0;
        foreach($query as $q)
        {
            if($i == 0)
            {
                $sql .= "p.fname LIKE '%$q%' OR p.lname LIKE '%$q%' ";
            }
            else
            {
                $sql .= "OR p.fname LIKE '%$q%' OR p.lname LIKE '%$q%' ";
            }
            $i++;
        }
        
        $query = $this->db->query($sql);
        
        return $query->result();
    }
    
    public function getVisitById($id)
    {
        $query = $this->db->where('id',$id)->get('visits');
        return $query->row();
    }
    
    public function getLocDateLog($location_id,$start,$end)
    {
        $query = $this->db->query("SELECT v.*,p.fname,p.lname,p.address,p.city,p.zip,s.abbr,p.dob,p.phone,o.payment_type,o.net_total,l.dea,l.address as caddress
                            FROM visits v
                            LEFT JOIN orders o ON v.order_id = o.id 
                            LEFT JOIN patients p ON v.patient_id = p.id 
                            LEFT JOIN states s ON p.state = s.id 
                            LEFT JOIN locations l ON o.location_id = l.id
                            WHERE v.prescription_no > 0 
                            AND (CAST(v.visit_date AS DATE) BETWEEN '$start' AND '$end')
                            AND o.location_id = $location_id");
        return $query->result();       
        
    }
    
    public function getPatientPresLog($patient_id,$start = null,$end = null)
    {
        $sql = "SELECT v.*,p.fname,p.lname,p.address,p.city,p.zip,s.abbr,p.dob,o.payment_type,o.net_total,l.dea,l.address as caddress,l.name as loc
                            FROM visits v
                            LEFT JOIN orders o ON v.order_id = o.id 
                            LEFT JOIN patients p ON v.patient_id = p.id 
                            LEFT JOIN states s ON p.state = s.id 
                            LEFT JOIN locations l ON o.location_id = l.id
                            WHERE v.prescription_no > 0                             
                            AND v.patient_id = $patient_id";
        
        if($start && $end)$sql .= " AND (CAST(v.visit_date AS DATE) BETWEEN '$start' AND '$end')";
        
        $query = $this->db->query($sql);
        return $query->result();       
        
    }
    
    public function getLocDateLogPres($location_id,$start,$end)
    {
        $sql = "SELECT v.*,p.fname,p.lname,p.address,p.city,p.zip,s.abbr,p.dob,o.payment_type,o.net_total,l.dea,l.address as caddress
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
    
    public function getPresByNumber($pres)
    {
        $sql = "SELECT v.*,p.fname,p.lname,p.address,p.city,p.zip,s.abbr,p.dob,o.payment_type,o.net_total,l.dea,l.address as caddress
                            FROM visits v
                            LEFT JOIN orders o ON v.order_id = o.id 
                            LEFT JOIN patients p ON v.patient_id = p.id 
                            LEFT JOIN states s ON p.state = s.id 
                            LEFT JOIN locations l ON o.location_id = l.id
                            WHERE v.prescription_no = $pres"; 
        
        $query = $this->db->query($sql);
        return $query->result();       
    }
    
    public function getStatsForDashboardSR($start,$end)
    {
        $sql = "SELECT o.*,p.last_status_date,o.credit_amount,v.visit,v.is_med,v.med1,v.med2,v.med3,v.med_days,v.meds_per_day
                FROM orders o
                LEFT JOIN visits v ON o.id = v.order_id
                LEFT JOIN patients p ON v.patient_id = p.id
                WHERE (CAST(o.created AS DATE) BETWEEN '$start' AND '$end')
                AND o.status NOT IN(2,5)";
        
        $query = $this->db->query($sql);
        return $query->result();
    }
    
    public function getStatsForDashboard($start,$end,$location = null)
    {        
         $sql = "SELECT o.*,p.last_status_date,o.credit_amount,v.visit,v.is_med,v.med1,v.med2,v.med3,v.med_days,v.meds_per_day,v.visit_date
                FROM visits v
                LEFT JOIN orders o ON v.order_id = o.id
                LEFT JOIN patients p ON v.patient_id = p.id
                WHERE (CAST(v.visit_date AS DATE) BETWEEN '$start' AND '$end')
                AND o.status NOT IN(2,5) AND o.location_id IN ($location)
                UNION
                SELECT o.*,p.last_status_date,o.credit_amount,v.visit,v.is_med,v.med1,v.med2,v.med3,v.med_days,v.meds_per_day,v.visit_date
                FROM orders o
                LEFT JOIN visits v ON o.id = v.order_id
                LEFT JOIN patients p ON v.patient_id = p.id
                WHERE (CAST(o.created AS DATE) BETWEEN '$start' AND '$end')
                AND o.status IN (3,4)
                AND o.location_id IN ($location)";
         
        
        
        $query = $this->db->query($sql);
        return $query->result();
    }
    
    public function getInjSaleForDuration($start,$end,$injIds)
    {
        $sql = "SELECT oi.product_id,oi.quantity,p.name,p.stock_item,o.location_id,p.is_combo,p.combo_item,o.patient_id,o.created
                FROM orders o
                LEFT JOIN order_items oi ON o.id = oi.order_id 
                LEFT JOIN products p ON oi.product_id = p.id 
                WHERE p.prepaid = 0 AND (CAST(o.created AS DATE) BETWEEN '$start' AND '$end')
                AND ( oi.product_id IN ($injIds) OR p.stock_item IN ($injIds) OR p.combo_item IN ($injIds))
                    
                UNION ALL

                SELECT pp.pro_id AS product_id,ppbr.quantity,p.name,p.stock_item,ppbr.location_id,p.is_combo,p.combo_item,pp.patient_id,ppbr.created
                FROM prepaid_brkdwn ppbr
                LEFT JOIN prepaid pp ON ppbr.prepaid_id = pp.id
                LEFT JOIN products p ON pp.pro_id = p.id 
                WHERE ppbr.type = 'subtract' AND (CAST(ppbr.created AS DATE) BETWEEN '$start' AND '$end')
                AND ( pp.pro_id IN ($injIds) OR p.stock_item IN ($injIds) OR p.combo_item IN ($injIds))";
        
        $query = $this->db->query($sql);
        return $query->result();
    }
    
    public function getPatientInjSaleForDuration($start,$end,$injIds,$patient_id,$location_id)
    {
        $sql = "SELECT oi.product_id,oi.quantity,p.name,p.stock_item,o.location_id,p.is_combo,p.combo_item,o.patient_id,o.created
                FROM orders o
                LEFT JOIN order_items oi ON o.id = oi.order_id 
                LEFT JOIN products p ON oi.product_id = p.id 
                WHERE o.patient_id = $patient_id AND o.location_id = $location_id AND p.prepaid = 0 AND (CAST(o.created AS DATE) BETWEEN '$start' AND '$end')
                AND ( oi.product_id IN ($injIds) OR p.stock_item IN ($injIds) OR p.combo_item IN ($injIds))
                    
                UNION ALL

                SELECT pp.pro_id AS product_id,ppbr.quantity,p.name,p.stock_item,ppbr.location_id,p.is_combo,p.combo_item,pp.patient_id,ppbr.created
                FROM prepaid_brkdwn ppbr
                LEFT JOIN prepaid pp ON ppbr.prepaid_id = pp.id
                LEFT JOIN products p ON pp.pro_id = p.id 
                WHERE pp.patient_id = $patient_id AND ppbr.location_id = $location_id AND ppbr.type = 'subtract' AND (CAST(ppbr.created AS DATE) BETWEEN '$start' AND '$end')
                AND ( pp.pro_id IN ($injIds) OR p.stock_item IN ($injIds) OR p.combo_item IN ($injIds))";
        
        $query = $this->db->query($sql);
        return $query->result();
    }
    
    public function getStatsForDashboardStaff($date,$location = null)
    {
        $sql = "SELECT o.*,p.last_status_date,o.credit_amount,v.visit,v.is_med,v.med1,v.med2,v.med3,v.med_days,v.meds_per_day,v.visit_date
                FROM visits v
                LEFT JOIN orders o ON v.order_id = o.id
                LEFT JOIN patients p ON v.patient_id = p.id
                WHERE (CAST(v.visit_date AS DATE) = '$date')
                AND o.status NOT IN(2,5) AND o.location_id IN ($location)
                UNION
                SELECT o.*,p.last_status_date,o.credit_amount,v.visit,v.is_med,v.med1,v.med2,v.med3,v.med_days,v.meds_per_day,v.visit_date
                FROM orders o
                LEFT JOIN visits v ON o.id = v.order_id
                LEFT JOIN patients p ON v.patient_id = p.id
                WHERE (CAST(o.created AS DATE) = '$date')
                AND o.status IN (3,4)
                AND o.location_id IN ($location)";
        
        if($location) $sql .= " AND o.location_id IN ($location)";
        
        $query = $this->db->query($sql);
        return $query->result();
    }
    
    public function getShotsOnlyCountForDashboard($start,$end,$location_id)
    {
        $sql = "SELECT o.id 
                FROM orders o 
                WHERE o.status = 3
                AND o.location_id = $location_id
                AND (CAST(o.created AS DATE) BETWEEN '$start' AND '$end')";
        
        $query = $this->db->query($sql);
        return $query->num_rows();
    }
    
    public function getShotsOnlyCountForDashboardStaff($date,$location_id)
    {
        $sql = "SELECT o.id 
                FROM orders o 
                WHERE o.status = 3
                AND o.location_id = $location_id
                AND (CAST(o.created AS DATE) = '$date')";
        
        $query = $this->db->query($sql);
        return $query->num_rows();
    }
    
    public function getForStatPDF($location_id,$start,$end)
    {
        $sql = "SELECT o.id,o.patient_id,o.location_id,o.payment_type,o.created,o.credit_amount,o.net_total,p.last_status_date,o.credit_amount,o.status,p.fname,p.lname,p.address,p.city,p.zip,s.abbr,p.dob,p.last_status_date,v.visit,v.is_med,v.med1,v.med2,v.med3,v.med_days,v.meds_per_day,v.no_med_days,v.visit_date
                FROM visits v
                LEFT JOIN orders o ON v.order_id = o.id
                LEFT JOIN patients p ON o.patient_id = p.id 
                LEFT JOIN states s ON p.state = s.id 
                WHERE (CAST(v.visit_date AS DATE) BETWEEN '$start' AND '$end')
                AND o.status NOT IN(2,5)
                AND o.location_id = $location_id
                    
                UNION 
                
                SELECT o.id,o.patient_id,o.location_id,o.payment_type,o.created,o.credit_amount,o.net_total,p.last_status_date,o.credit_amount,o.status,p.fname,p.lname,p.address,p.city,p.zip,s.abbr,p.dob,p.last_status_date,v.visit,v.is_med,v.med1,v.med2,v.med3,v.med_days,v.meds_per_day,v.no_med_days,v.visit_date
                FROM orders o
                LEFT JOIN visits v ON o.id = v.order_id
                LEFT JOIN patients p ON o.patient_id = p.id 
                LEFT JOIN states s ON p.state = s.id 
                WHERE (CAST(o.created AS DATE) BETWEEN '$start' AND '$end')
                AND o.status IN (3,4)
                AND o.location_id = $location_id
                    
                UNION
                
                SELECT o.id,o.patient_id,o.location_id,o.payment_type,o.created,o.credit_amount,o.net_total,p.last_status_date,o.credit_amount,4 as status,p.fname,p.lname,p.address,p.city,p.zip,s.abbr,p.dob,p.last_status_date,v.visit,v.is_med,v.med1,v.med2,v.med3,v.med_days,v.meds_per_day,v.no_med_days,v.visit_date
                FROM orders o
                LEFT JOIN visits v ON o.id = v.order_id
                LEFT JOIN patients p ON v.patient_id = p.id
                LEFT JOIN states s ON p.state = s.id 
                WHERE (CAST(o.created AS DATE) BETWEEN '$start' AND '$end')
                AND CAST(o.created AS DATE) != CAST(v.visit_date AS DATE)
                AND o.status IN (1)
                AND o.location_id = $location_id";
        
        
        $query = $this->db->query($sql);
        return $query->result();  
    }
    
    public function getForStatPDFStaff($location_id,$date)
    {
        $sql = "SELECT o.id,o.patient_id,o.location_id,o.payment_type,o.created,o.credit_amount,o.net_total,p.last_status_date,o.credit_amount,o.status,p.fname,p.lname,p.address,p.city,p.zip,s.abbr,p.dob,p.last_status_date,v.visit,v.is_med,v.med1,v.med2,v.med3,v.med_days,v.meds_per_day,v.no_med_days,v.visit_date
                FROM visits v
                LEFT JOIN orders o ON v.order_id = o.id
                LEFT JOIN patients p ON o.patient_id = p.id 
                LEFT JOIN states s ON p.state = s.id 
                WHERE (CAST(v.visit_date AS DATE) = '$date')
                AND o.status NOT IN(2,5)
                AND o.location_id = $location_id

                UNION

                SELECT o.id,o.patient_id,o.location_id,o.payment_type,o.created,o.credit_amount,o.net_total,p.last_status_date,o.credit_amount,o.status,p.fname,p.lname,p.address,p.city,p.zip,s.abbr,p.dob,p.last_status_date,v.visit,v.is_med,v.med1,v.med2,v.med3,v.med_days,v.meds_per_day,v.no_med_days,v.visit_date
                FROM orders o
                LEFT JOIN visits v ON o.id = v.order_id
                LEFT JOIN patients p ON o.patient_id = p.id 
                LEFT JOIN states s ON p.state = s.id 
                WHERE (CAST(o.created AS DATE) = '$date')
                AND o.status IN (3,4)
                AND o.location_id = $location_id
                    
                UNION
                
                SELECT o.id,o.patient_id,o.location_id,o.payment_type,o.created,o.credit_amount,o.net_total,p.last_status_date,o.credit_amount,4 as status,p.fname,p.lname,p.address,p.city,p.zip,s.abbr,p.dob,p.last_status_date,v.visit,v.is_med,v.med1,v.med2,v.med3,v.med_days,v.meds_per_day,v.no_med_days,v.visit_date
                FROM orders o
                LEFT JOIN visits v ON o.id = v.order_id
                LEFT JOIN patients p ON v.patient_id = p.id
                LEFT JOIN states s ON p.state = s.id 
                WHERE (CAST(o.created AS DATE) = '$date')
                AND CAST(o.created AS DATE) != CAST(v.visit_date AS DATE)
                AND o.status IN (1)
                AND o.location_id = $location_id";
                
        
        $query = $this->db->query($sql);
        return $query->result();       
        
    }
    
    
    public function getPatientVisitsInPeriod($patient_id,$start,$end)
    {
        $query = $this->db->query("SELECT v.*
                            FROM visits v
                            WHERE DATE(v.visit_date) between '$start' AND '$end'
                            AND v.patient_id = $patient_id
                            ORDER BY v.visit_date ASC");
       
        return $query->result(); 
    }
    
    public function getPatientsForStatusCron($date)
    {
        $query = $this->db->query("SELECT w.*
                                    FROM (SELECT p.*,(SELECT v.visit_date FROM visits v WHERE v.patient_id = p.id ORDER BY visit DESC LIMIT 1) AS visit_date
                                            FROM patients p) w
                                    WHERE w.visit_date < '$date'");
        
        return $query->result();
    }
    
    public function getStateId($name)
    {
        $query = $this->db->select('id')->where('abbr',$name)->get('states');
        
        return $query->row();
    }
    
    public function importPatients($data)
    {
        $this->db->insert_batch('patients',$data);
    }
    
    public function removePatient($id)
    {
        $this->db->where('id',$id)->delete('patients');
    }
    
    public function getVisitByOrderId($order_id)
    {
        $query = $this->db->where('order_id',$order_id)->get('visits');
        return $query->row();
    }
    
    public function isPatientHavVisits($patient_id)
    {
        $query = $this->db->where('patient_id',$patient_id)->get('visits');
        return $query->num_rows()> 0? TRUE:FALSE;
    }
    
    public function getLastStatus($patient_id)
    {
        $query = $this->db->where('patient_id',$patient_id)->get('patient_last_status');
        return $query->row();
    }
    
    public function addLastStatus($data)
    {
        $this->db->insert('patient_last_status',$data);
    }
    
    public function updateLastStatus($patient_id,$data)
    {
        $this->db->where('patient_id',$patient_id)->update('patient_last_status',$data);
    }
    
    public function addAlert($data)
    {
        $this->db->insert('alerts',$data);
    }
    
    public function getAlerts($patient_id,$status = 0)
    {
        $query = $this->db->where('patient_id',$patient_id)->where('doctor',$status)->get('alerts');
        return $query->result();
    }
    
    public function removeAlert($id)
    {
        $this->db->where('id',$id)->delete('alerts');
    }
    
    public function getVisitsOnLocOnDay($loc_id,$date)
    {
        $sql = "SELECT v.*
                FROM visits v
                LEFT JOIN orders o ON v.order_id = o.id
                WHERE o.location_id = $loc_id
                AND v.is_med = 1
                AND CAST(v.visit_date AS DATE) = '$date'";
        
        $query = $this->db->query($sql);
        return $query->result();
    }
    
    public function getPatientByPhone($phone)
    {
        $query = $this->db->where('phone',$phone)->get('patients');
        return $query->row();
    }
    
    public function getPatientLastVisit($patient_id)
    {
        $query = $this->db->select('v.*,l.name')
                ->from('visits v')
                ->join('orders o','v.order_id = o.id','left')
                ->join('locations l','o.location_id = l.id','left')
                ->where('v.patient_id',$patient_id)
                ->order_by('v.visit_date','DESC')
                ->get();
        return $query->row();
    }
    
    public function getPresMedDays($pres_no)
    {
        $query = $this->db->query("SELECT SUM(med_days) AS total_med_days FROM visits WHERE prescription_no = $pres_no AND is_med = 1 GROUP BY prescription_no");
        
        return $query->row();
    }
    
    public function patientSearch($phase)
    {
        $pa = explode(" ", $phase);
        
        $sql = "SELECT p.id,p.fname,p.lname,p.phone,p.last_status_date,p.dob,p.status,count(v.id) as vcount,pls.patient_id as pls
                FROM patients p
                LEFT JOIN visits v ON p.id = v.patient_id
                LEFT JOIN patient_last_status pls ON p.id = pls.patient_id";
        if(isset($pa[0])) $sql .= " WHERE p.fname LIKE '$pa[0]%' OR p.lname LIKE '$pa[0]%' OR p.phone LIKE '%$pa[0]%'";
        if(isset($pa[1])) $sql .= " OR p.fname LIKE '$pa[1]%' OR p.lname LIKE '$pa[1]%' OR p.phone LIKE '%$pa[1]%'";
        
        $sql .= " GROUP by p.id";
       
	$query = $this->db->query($sql);
	return $query->result();	
    }
    
    public function getTodayBday()
    {
        $date = date("m-d");
        $query = $this->db->query("SELECT * FROM patients WHERE DATE_FORMAT(dob, '%m-%d') = '$date' AND sms=1 AND status = 1");
        return $query->result();
    }
    
    
    public function getSixStatusAlert($date)
    {
        $query = $this->db->query("SELECT p.*
                                    FROM patients p
                                    WHERE p.last_status_date = '$date'
                                    AND (SELECT COUNT(v.id) FROM visits v WHERE CAST(visit_date AS DATE) > '$date' AND v.patient_id = p.id) = 0
                                    AND p.sms = 1

                                    UNION

                                    SELECT p.*
                                    FROM visits v
                                    LEFT JOIN patients p ON v.patient_id = p.id 
                                    WHERE CAST(v.visit_date AS DATE) = '$date'
                                    AND (SELECT COUNT(v.id) FROM visits vv WHERE CAST(vv.visit_date AS DATE) > '$date' AND vv.patient_id = v.patient_id)=0 AND p.sms = 1");
        return $query->result();
    }
    
    public function getPastDuePatients($date)
    {
        
        $sql = "SELECT v.*,p.fname,p.lname,p.phone
                FROM visits v
                LEFT JOIN patients p ON v.patient_id = p.id 
                WHERE ADDDATE(CAST(v.visit_date AS DATE), INTERVAL v.med_days DAY) < '$date'
                AND v.patient_id NOT IN (SELECT vv.patient_id FROM visits vv WHERE vv.visit_date > v.visit_date)
                and v.is_med = 1";
        
        $query = $this->db->query($sql);
        return $query->result();
    }
    
    public function getByLname($lname)
    {
        $query = $this->db->where('lname',$lname)->get('patients');
        return $query->result();
    }
    
    public function getDTPatients()
    {
        $order_column = array(null,"fname","lname",null,null,null,null);
        
        $this->db->select('p.id,p.fname,p.lname,p.phone,p.dob,p.status,p.last_status_date,count(v.id) as vcount,pls.patient_id as pls')
                ->from('patients p')
                ->join('visits v','p.id = v.patient_id','left')
                ->join('patient_last_status pls','p.id = pls.patient_id','left');
        
        if(isset($_POST["search"]["value"]))
        {
            $term = trim($_POST["search"]["value"]);
            $term = preg_replace('!\s+!', ' ', $term);
            $terms = explode(' ', $term);
            
            foreach($terms as $term)
            {
                $this->db->or_like("fname",$term)
                         ->or_like("lname",$term)
                         ->or_like("phone",$term);
            }
        }
        
        if(isset($_POST["order"]))
        {
            $this->db->order_by($order_column[$_POST['order']['0']['column']],$_POST['order']['0']['dir']);
        }
        else
        {
            $this->db->order_by("id", "DESC");
        }
        
        $this->db->group_by('p.id');
    }
    
    public function makeDT()
    {
        $this->getDTPatients();
        if($_POST["length"] != -1)
        {
            $this->db->limit($_POST["length"], $_POST["start"]);
        }
        $query = $this->db->get();
        return $query->result();
    }
    
    function getFilteredDT()
    {
        $this->getDTPatients();
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    function getAllPatientCount()
    {
        $this->db->select("*")->from('patients');
        return $this->db->count_all_results();
    }
    
    function getMCPatients($start = NULL,$limit = NULL)
    {
        $this->db->where('email !=','')->order_by('id','ASC');
        
        if($start !== NULL && $limit !== NULL)$this->db->limit($limit,$start);
        
        $query = $this->db->get('patients');
        return $query->result();
    }
    
    function getMCActivePatients($start = NULL,$limit = NULL)
    {
        $this->db->where('email !=','')->where('status',1)->order_by('id','ASC');
        
        if($start !== NULL && $limit !== NULL)$this->db->limit($limit,$start);
        
        $query = $this->db->get('patients');
        return $query->result();
    }
    
    function getSmsLog($patient_id)
    {
        $query = $this->db->select("sl.*,p.fname,p.lname,p.photo,p.gender")
                ->from('sms_log sl')
                ->join('patients p','sl.patient_id = p.id','left')
                ->where('sl.patient_id',$patient_id)
                ->order_by('sl.created','ASC')
                ->get();
        return $query->result();
    }
    
    function addSmsLog($data)
    {
        $this->db->insert('sms_log',$data);
    }
    
    function updateB12Val($data)
    {
        $b = $this->db->where('date',$data['date'])->where('loc_id',$data['loc_id'])->get('b12_used')->row();
        
        if($b)
        {
            $this->db->where('id',$b->id)->update('b12_used',$data);
        }
        else
        {
            $this->db->insert('b12_used',$data);
        }
    }
    
    function getB12Used($start,$end)
    {
        $sql = "SELECT SUM(value) as val FROM b12_used WHERE date BETWEEN '$start' and '$end'";
        $query = $this->db->query($sql);
        return $query->row();
    }
    
    public function getAllFreezed()
    {
	$query = $this->db->where('freezed',1)->get('patients');
	return $query->result();
    }
    
    public function getRefGivenDate($patient)
    {
       $added = '';
       if($patient->new_patient > 0)
       {
           $query = $this->db->select('pb.created')
                ->from('prepaid p')
                ->join('prepaid_brkdwn pb','p.id = pb.prepaid_id','left')
                ->where('pb.referred_by',$patient->patient_refferral_id)
                ->where('p.patient_id',$patient->id)
                ->get();
           $result = $query->row();
           $added = $result->created;
       }
       elseif($patient->old_patient > 0)
       {           
           $query = $this->db->select('pb.created')
                ->from('prepaid p')
                ->join('prepaid_brkdwn pb','p.id = pb.prepaid_id','left')
                ->where('pb.referrer',$patient->id)
                ->where('p.patient_id',$patient->patient_refferral_id)
                ->get();
           $result = $query->row();
           $added = $result->created;
       }
       return $added;
    }
}
