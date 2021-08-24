<?php

/**
 * Patient model
 *
 * @author Udana Udayanga <udana@udana.lk>
 */
class Patient_model extends CI_Model
{
    /**
     * Get users for weekly mail ( get users visited yesterday who has visited more than once)
     *
     * @param  mixed $day
     * @return results
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
    
    /**
     * get visitors for period
     *
     * @param  mixed $start
     * @param  mixed $end
     * @return void
     */
    public function getVisitorsOnPeriod($start, $end)
    {
        $query = $this->db->query("SELECT * 
				    from visits v 
				    WHERE visit_date between '$start' and '$end'
				    AND status = 'loss'
				    AND visit > 1 ");

        return $query->result();
    }
    
    /**
     * Get visitors for streak
     *
     * @param  mixed $start
     * @param  mixed $end
     * @return void
     */
    public function getVisitorsForStreak($start, $end)
    {
        $query = $this->db->query("SELECT * 
				    from visits v 
				    WHERE visit_date between '$start' and '$end'
				    AND status = 'loss'
				    AND visit > 2 ");

        return $query->result();
    }
    
    /**
     * get Patient Visit
     *
     * @param  mixed $user_id
     * @param  mixed $visit
     * @return void
     */
    public function getPatientVisit($user_id, $visit)
    {
        $query = $this->db->select('v.*,o.location_id')
            ->from('visits v')
            ->join('orders o', 'v.order_id = o.id', 'left')
            ->where('visit', $visit)
            ->where('v.patient_id', $user_id)
            ->get();

        return $query->row();
    }
    
    /**
     * get Patient
     *
     * @param  mixed $id
     * @return void
     */
    public function getPatient($id)
    {
        $query = $this->db->where('id', $id)->get('patients');
        return $query->row();
    }
    
    /**
     * get WeightLoss Patients
     *
     * @param  mixed $visit
     * @param  mixed $status
     * @return void
     */
    public function getWeightLossPatients($visit, $status = null)
    {
        $this->db->where('visit', $visit);
        if ($status) $this->db->where('status', $status);
        $query = $this->db->get('visits');
        return $query->result();
    }
    
    /**
     * get Visits By Visit
     *
     * @param  mixed $patient_id
     * @param  mixed $from
     * @param  mixed $to
     * @param  mixed $status
     * @return void
     */
    public function getVisitsByVisit($patient_id, $from, $to, $status)
    {
        $query = $this->db->query("SELECT * 
			    FROM visits 
			    WHERE patient_id = $patient_id 
			    AND visit <= $to 
			    AND visit >= $from 
			    AND (diff < -2 || status = 'start')");

        return $query->result_array();
    }
    
    /**
     * get Defector Patients
     *
     * @param  mixed $lastDate
     * @return void
     */
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
    
    /**
     * add patient
     *
     * @param  mixed $data
     * @return void
     */
    public function add($data)
    {
        $this->db->insert('patients', $data);
        return $this->db->insert_id();
    }
    
    /**
     * get Patient Active
     *
     * @param  mixed $name
     * @param  mixed $active
     * @return void
     */
    public function getPatientAC($name, $active = false)
    {
        $this->db->distinct()
            ->select('id,CONCAT(lname," ",fname) as value')
            ->like('fname', $name, 'both')
            ->or_like('lname', $name, 'both');
        if ($active) $this->db->where('status', 1);
        $query = $this->db->get('patients');

        return $query->result_array();
    }
    
    /**
     * get Prev Med Active
     *
     * @param  mixed $term
     * @return void
     */
    public function getPrevMedAC($term)
    {
        $query = $this->db->distinct()
            ->select('id,med as value')
            ->like('med', $term, 'both')
            ->get('prev_meds');

        return $query->result_array();
    }
    
    /**
     * get All Patients
     *
     * @return void
     */
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
    
    /**
     * get Patients
     *
     * @return void
     */
    public function getPatients()
    {
        $query = $this->db->get('patients');
        return $query->result();
    }
    
    /**
     * get Last Visit
     *
     * @param  mixed $patient_id
     * @return void
     */
    public function getLastVisit($patient_id)
    {
        $query = $this->db->select('v.*,o.location_id')
            ->from('visits v')
            ->join('orders o', 'v.order_id = o.id', 'left')
            ->where('v.patient_id', $patient_id)
            ->where('DATE(v.visit_date) <', date('Y-m-d'))
            ->order_by('v.visit_date', 'DESC')
            ->get();
        return $query->row();
    }
    
    /**
     * get Las tVisit New
     *
     * @param  mixed $patient_id
     * @param  mixed $order_date
     * @return void
     */
    public function getLastVisitNew($patient_id, $order_date)
    {
        $query = $this->db->select('v.*,o.location_id')
            ->from('visits v')
            ->join('orders o', 'v.order_id = o.id', 'left')
            ->where('v.patient_id', $patient_id)
            ->where('DATE(v.visit_date) <', $order_date)
            ->order_by('v.visit_date', 'DESC')
            ->get();
        return $query->row();
    }
    
    /**
     * get Last Med Visit
     *
     * @param  mixed $patient_id
     * @return void
     */
    public function getLastMedVisit($patient_id)
    {
        $query = $this->db->select('v.*,o.location_id')
            ->from('visits v')
            ->join('orders o', 'v.order_id = o.id', 'left')
            ->where('DATE(visit_date) !=', date('Y-m-d'))
            ->where('v.patient_id', $patient_id)
            ->where('v.is_med', 1)
            ->order_by('v.visit_date', 'DESC')
            ->get();
        return $query->row();
    }
    
    /**
     * get Today Visit
     *
     * @param  mixed $patient_id
     * @return void
     */
    public function getTodayVisit($patient_id)
    {
        $query = $this->db->where('patient_id', $patient_id)
            ->where('DATE(visit_date)', date('Y-m-d'))
            ->get('visits');
        return $query->row();
    }
    
    /**
     * add_visit
     *
     * @param  mixed $data
     * @return void
     */
    public function add_visit($data)
    {       
        $this->db->insert('visits', $data);
    }
    
    /**
     * updateVisit
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return void
     */
    public function updateVisit($id, $data)
    {        
        $this->db->where('id', $id)->update('visits', $data);
    }
    
    /**
     * getLastPresNo
     *
     * @return void
     */
    public function getLastPresNo()
    {
        $query = $this->db->select('prescription_no')->order_by('prescription_no', 'DESC')->get('visits');
        return $query->row();
    }
    
    /**
     * updateDrNote
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return void
     */
    public function updateDrNote($id, $data)
    {
        $this->db->where('id', $id)->update('visits', $data);
    }
    
    /**
     * update Patient
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return void
     */
    public function updatePatient($id, $data)
    {
        $this->db->where('id', $id)->update('patients', $data);
    }
    
    /**
     * get Patient Visits
     *
     * @param  mixed $patient_id
     * @return void
     */
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
        
        return $query->result();
    }
    
    /**
     * getStates
     *
     * @return void
     */
    public function getStates()
    {
        return $this->db->get('states')->result();
    }
    
    /**
     * getState
     *
     * @param  mixed $id
     * @return void
     */
    public function getState($id)
    {
        return $this->db->where('id', $id)->get('states')->row();
    }
    
    /**
     * addECG
     *
     * @param  mixed $data
     * @return void
     */
    public function addECG($data)
    {
        $this->db->insert('ecg', $data);
        return $this->db->insert_id();
    }

    public function updateECG($id, $data)
    {
        $this->db->where('id', $id)->update('ecg', $data);
    }
    
    /**
     * getAllECG
     *
     * @param  mixed $id
     * @return void
     */
    public function getAllECG($id)
    {
        $query = $this->db->where('patient_id', $id)->order_by('created', 'DESC')->get('ecg');
        return $query->result();
    }
    
    /**
     * getECG
     *
     * @param  mixed $id
     * @return void
     */
    public function getECG($id)
    {
        $query = $this->db->where('id', $id)->get('ecg');
        return $query->row();
    }
    
    /**
     * delECG
     *
     * @param  mixed $id
     * @return void
     */
    public function delECG($id)
    {
        $this->db->where('id', $id)->delete('ecg');
    }
    
    /**
     * addBW
     *
     * @param  mixed $data
     * @return void
     */
    public function addBW($data)
    {
        $this->db->insert('bw', $data);
        return $this->db->insert_id();
    }
    
    /**
     * updateBW
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return void
     */
    public function updateBW($id, $data)
    {
        $this->db->where('id', $id)->update('bw', $data);
    }
    
    /**
     * getAllBW
     *
     * @param  mixed $id
     * @return void
     */
    public function getAllBW($id)
    {
        $query = $this->db->where('patient_id', $id)->order_by('created', 'DESC')->get('bw');
        return $query->result();
    }
    
    /**
     * getBW
     *
     * @param  mixed $id
     * @return void
     */
    public function getBW($id)
    {
        $query = $this->db->where('id', $id)->get('bw');
        return $query->row();
    }
    
    /**
     * delBW
     *
     * @param  mixed $id
     * @return void
     */
    public function delBW($id)
    {
        $this->db->where('id', $id)->delete('bw');
    }
    
    /**
     * getLatestNoOfVisits
     *
     * @param  mixed $patient_id
     * @param  mixed $no_of_visits
     * @param  mixed $till_date
     * @return void
     */
    public function getLatestNoOfVisits($patient_id, $no_of_visits, $till_date)
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
    
    /**
     * getVisitsForVisitPage
     *
     * @param  mixed $patient_id
     * @param  mixed $till_date
     * @param  mixed $lastRestart
     * @return void
     */
    public function getVisitsForVisitPage($patient_id, $till_date, $lastRestart)
    {
        $sql = "SELECT v.*,l.abbr
                FROM visits v
                LEFT JOIN orders o ON v.order_id = o.id
                LEFT JOIN locations l ON o.location_id = l.id
                where v.patient_id = $patient_id
                AND (CAST(v.visit_date AS DATE) <= '$till_date')";

        if ($lastRestart) {
            $sql .= " AND (CAST(v.visit_date AS DATE) >= '$lastRestart->date')";
        }

        $sql .= " ORDER BY v.visit DESC
                 limit 6";

        $query = $this->db->query($sql);
        return $query->result();
    }

    
    /**
     * getVisitCountSinceLastRestart
     *
     * @param  mixed $patient_id
     * @param  mixed $till_date
     * @param  mixed $lastRestart
     * @return void
     */
    public function getVisitCountSinceLastRestart($patient_id, $till_date, $lastRestart)
    {
        $sql = "SELECT v.*
                FROM visits v
                where v.patient_id = $patient_id
                AND (CAST(v.visit_date AS DATE) >= '$lastRestart->date')
                AND (CAST(v.visit_date AS DATE) <= '$till_date')";
        $query = $this->db->query($sql);
        return $query->num_rows();
    }


    
    /**
     * getLatestVisit
     *
     * @param  mixed $patient_id
     * @return void
     */
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
    
    /**
     * getLastOrder
     *
     * @param  mixed $patient_id
     * @return void
     */
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

    
    /**
     * search
     *
     * @param  mixed $query
     * @return void
     */
    public function search($query)
    {
        $sql = "SELECT DISTINCT p.* FROM patients p WHERE ";
        $i = 0;
        foreach ($query as $q) {
            if ($i == 0) {
                $sql .= "p.fname LIKE '%$q%' OR p.lname LIKE '%$q%' ";
            } else {
                $sql .= "OR p.fname LIKE '%$q%' OR p.lname LIKE '%$q%' ";
            }
            $i++;
        }

        $query = $this->db->query($sql);

        return $query->result();
    }
    
    /**
     * getVisitById
     *
     * @param  mixed $id
     * @return void
     */
    public function getVisitById($id)
    {
        $query = $this->db->where('id', $id)->get('visits');
        return $query->row();
    }
    
    /**
     * getLocDateLog
     *
     * @param  mixed $location_id
     * @param  mixed $start
     * @param  mixed $end
     * @return void
     */
    public function getLocDateLog($location_id, $start, $end)
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
    
    /**
     * getPatientPresLog
     *
     * @param  mixed $patient_id
     * @param  mixed $start
     * @param  mixed $end
     * @return void
     */
    public function getPatientPresLog($patient_id, $start = null, $end = null)
    {
        $sql = "SELECT v.*,p.fname,p.lname,p.address,p.city,p.zip,s.abbr,p.dob,o.payment_type,o.net_total,l.dea,l.address as caddress,l.name as loc
                            FROM visits v
                            LEFT JOIN orders o ON v.order_id = o.id 
                            LEFT JOIN patients p ON v.patient_id = p.id 
                            LEFT JOIN states s ON p.state = s.id 
                            LEFT JOIN locations l ON o.location_id = l.id
                            WHERE v.prescription_no > 0                             
                            AND v.patient_id = $patient_id";

        if ($start && $end) $sql .= " AND (CAST(v.visit_date AS DATE) BETWEEN '$start' AND '$end')";

        $query = $this->db->query($sql);
        return $query->result();
    }
    
    /**
     * getLocDateLogPres
     *
     * @param  mixed $location_id
     * @param  mixed $start
     * @param  mixed $end
     * @return void
     */
    public function getLocDateLogPres($location_id, $start, $end)
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
    
    /**
     * getPresByNumber
     *
     * @param  mixed $pres
     * @return void
     */
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
    
    /**
     * getStatsForDashboardSR
     *
     * @param  mixed $start
     * @param  mixed $end
     * @return void
     */
    public function getStatsForDashboardSR($start, $end)
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
    
    /**
     * getStatsForDashboard
     *
     * @param  mixed $start
     * @param  mixed $end
     * @param  mixed $location
     * @return void
     */
    public function getStatsForDashboard($start, $end, $location = null)
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
    
    /**
     * getInjSaleForDuration
     *
     * @param  mixed $start
     * @param  mixed $end
     * @param  mixed $injIds
     * @return void
     */
    public function getInjSaleForDuration($start, $end, $injIds)
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
    
    /**
     * getPatientInjSaleForDuration
     *
     * @param  mixed $start
     * @param  mixed $end
     * @param  mixed $injIds
     * @param  mixed $patient_id
     * @param  mixed $location_id
     * @return void
     */
    public function getPatientInjSaleForDuration($start, $end, $injIds, $patient_id, $location_id)
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
    
    /**
     * getStatsForDashboardStaff
     *
     * @param  mixed $date
     * @param  mixed $location
     * @return void
     */
    public function getStatsForDashboardStaff($date, $location = null)
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

        if ($location) $sql .= " AND o.location_id IN ($location)";

        $query = $this->db->query($sql);
        return $query->result();
    }
    
    /**
     * getShotsOnlyCountForDashboard
     *
     * @param  mixed $start
     * @param  mixed $end
     * @param  mixed $location_id
     * @return void
     */
    public function getShotsOnlyCountForDashboard($start, $end, $location_id)
    {
        $sql = "SELECT o.id 
                FROM orders o 
                WHERE o.status = 3
                AND o.location_id = $location_id
                AND (CAST(o.created AS DATE) BETWEEN '$start' AND '$end')";

        $query = $this->db->query($sql);
        return $query->num_rows();
    }
    
    /**
     * getShotsOnlyCountForDashboardStaff
     *
     * @param  mixed $date
     * @param  mixed $location_id
     * @return void
     */
    public function getShotsOnlyCountForDashboardStaff($date, $location_id)
    {
        $sql = "SELECT o.id 
                FROM orders o 
                WHERE o.status = 3
                AND o.location_id = $location_id
                AND (CAST(o.created AS DATE) = '$date')";

        $query = $this->db->query($sql);
        return $query->num_rows();
    }
    
    /**
     * getForStatPDF
     *
     * @param  mixed $location_id
     * @param  mixed $start
     * @param  mixed $end
     * @return void
     */
    public function getForStatPDF($location_id, $start, $end)
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
    
    /**
     * getForStatPDFStaff
     *
     * @param  mixed $location_id
     * @param  mixed $date
     * @return void
     */
    public function getForStatPDFStaff($location_id, $date)
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

    
    /**
     * getPatientVisitsInPeriod
     *
     * @param  mixed $patient_id
     * @param  mixed $start
     * @param  mixed $end
     * @return void
     */
    public function getPatientVisitsInPeriod($patient_id, $start, $end)
    {
        $query = $this->db->query("SELECT v.*
                            FROM visits v
                            WHERE DATE(v.visit_date) between '$start' AND '$end'
                            AND v.patient_id = $patient_id
                            ORDER BY v.visit_date ASC");

        return $query->result();
    }
    
    /**
     * getPatientsForStatusCron
     *
     * @param  mixed $date
     * @return void
     */
    public function getPatientsForStatusCron($date)
    {
        $query = $this->db->query("SELECT w.*
                                    FROM (SELECT p.*,(SELECT v.visit_date FROM visits v WHERE v.patient_id = p.id ORDER BY visit DESC LIMIT 1) AS visit_date
                                            FROM patients p) w
                                    WHERE w.visit_date < '$date'");

        return $query->result();
    }
    
    /**
     * getStateId
     *
     * @param  mixed $name
     * @return void
     */
    public function getStateId($name)
    {
        $query = $this->db->select('id')->where('abbr', $name)->get('states');

        return $query->row();
    }
    
    /**
     * importPatients
     *
     * @param  mixed $data
     * @return void
     */
    public function importPatients($data)
    {
        $this->db->insert_batch('patients', $data);
    }
    
    /**
     * removePatient
     *
     * @param  mixed $id
     * @return void
     */
    public function removePatient($id)
    {
        $this->db->where('id', $id)->delete('patients');
    }
    
    /**
     * getVisitByOrderId
     *
     * @param  mixed $order_id
     * @return void
     */
    public function getVisitByOrderId($order_id)
    {
        $query = $this->db->where('order_id', $order_id)->get('visits');
        return $query->row();
    }
    
    /**
     * isPatientHavVisits
     *
     * @param  mixed $patient_id
     * @return void
     */
    public function isPatientHavVisits($patient_id)
    {
        $query = $this->db->where('patient_id', $patient_id)->get('visits');
        return $query->num_rows() > 0 ? TRUE : FALSE;
    }
    
    /**
     * getLastStatus
     *
     * @param  mixed $patient_id
     * @return void
     */
    public function getLastStatus($patient_id)
    {
        $query = $this->db->where('patient_id', $patient_id)->get('patient_last_status');
        return $query->row();
    }
    
    /**
     * addLastStatus
     *
     * @param  mixed $data
     * @return void
     */
    public function addLastStatus($data)
    {
        $this->db->insert('patient_last_status', $data);
    }
    
    /**
     * updateLastStatus
     *
     * @param  mixed $patient_id
     * @param  mixed $data
     * @return void
     */
    public function updateLastStatus($patient_id, $data)
    {
        $this->db->where('patient_id', $patient_id)->update('patient_last_status', $data);
    }
    
    /**
     * addAlert
     *
     * @param  mixed $data
     * @return void
     */
    public function addAlert($data)
    {
        $this->db->insert('alerts', $data);
    }
    
    /**
     * getAlerts
     *
     * @param  mixed $patient_id
     * @param  mixed $status
     * @return void
     */
    public function getAlerts($patient_id, $status = 0)
    {
        $query = $this->db->where('patient_id', $patient_id)->where('doctor', $status)->get('alerts');
        return $query->result();
    }
    
    /**
     * removeAlert
     *
     * @param  mixed $id
     * @return void
     */
    public function removeAlert($id)
    {
        $this->db->where('id', $id)->delete('alerts');
    }
    
    /**
     * getVisitsOnLocOnDay
     *
     * @param  mixed $loc_id
     * @param  mixed $date
     * @return void
     */
    public function getVisitsOnLocOnDay($loc_id, $date)
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
    
    /**
     * getPatientByPhone
     *
     * @param  mixed $phone
     * @return void
     */
    public function getPatientByPhone($phone)
    {
        $query = $this->db->where('phone', $phone)->get('patients');
        return $query->row();
    }
    
    /**
     * getPatientLastVisit
     *
     * @param  mixed $patient_id
     * @return void
     */
    public function getPatientLastVisit($patient_id)
    {
        $query = $this->db->select('v.*,l.name')
            ->from('visits v')
            ->join('orders o', 'v.order_id = o.id', 'left')
            ->join('locations l', 'o.location_id = l.id', 'left')
            ->where('v.patient_id', $patient_id)
            ->order_by('v.visit_date', 'DESC')
            ->get();
        return $query->row();
    }
    
    /**
     * getPresMedDays
     *
     * @param  mixed $pres_no
     * @return void
     */
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
        if (isset($pa[0])) $sql .= " WHERE p.fname LIKE '$pa[0]%' OR p.lname LIKE '$pa[0]%' OR p.phone LIKE '%$pa[0]%'";
        if (isset($pa[1])) $sql .= " OR p.fname LIKE '$pa[1]%' OR p.lname LIKE '$pa[1]%' OR p.phone LIKE '%$pa[1]%'";

        $sql .= " GROUP by p.id";

        $query = $this->db->query($sql);
        return $query->result();
    }
    
    /**
     * getTodayBday
     *
     * @return void
     */
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
    
    /**
     * getPastDuePatients
     *
     * @param  mixed $date
     * @return void
     */
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
    
    /**
     * getByLname
     *
     * @param  mixed $lname
     * @return void
     */
    public function getByLname($lname)
    {
        $query = $this->db->where('lname', $lname)->get('patients');
        return $query->result();
    }
    
    /**
     * getDTPatients
     *
     * @return void
     */
    public function getDTPatients()
    {
        $order_column = array(null, "fname", "lname", null, null, null, null);

        $this->db->select('p.id,p.fname,p.lname,p.phone,p.dob,p.status,p.last_status_date,count(v.id) as vcount,pls.patient_id as pls')
            ->from('patients p')
            ->join('visits v', 'p.id = v.patient_id', 'left')
            ->join('patient_last_status pls', 'p.id = pls.patient_id', 'left');

        if (isset($_POST["search"]["value"])) {
            $term = trim($_POST["search"]["value"]);
            $term = preg_replace('!\s+!', ' ', $term);
            $terms = explode(' ', $term);

            foreach ($terms as $term) {
                $this->db->or_like("fname", $term)
                    ->or_like("lname", $term)
                    ->or_like("phone", $term);
            }
        }

        if (isset($_POST["order"])) {
            $this->db->order_by($order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by("id", "DESC");
        }

        $this->db->group_by('p.id');
    }
    
    /**
     * makeDT
     *
     * @return void
     */
    public function makeDT()
    {
        $this->getDTPatients();
        if ($_POST["length"] != -1) {
            $this->db->limit($_POST["length"], $_POST["start"]);
        }
        $query = $this->db->get();
        return $query->result();
    }
    
    /**
     * getFilteredDT
     *
     * @return void
     */
    function getFilteredDT()
    {
        $this->getDTPatients();
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    /**
     * getAllPatientCount
     *
     * @return void
     */
    function getAllPatientCount()
    {
        $this->db->select("*")->from('patients');
        return $this->db->count_all_results();
    }
    
    /**
     * getMCPatients
     *
     * @param  mixed $start
     * @param  mixed $limit
     * @return void
     */
    function getMCPatients($start = NULL, $limit = NULL)
    {
        $this->db->where('email !=', '')->order_by('id', 'ASC');

        if ($start !== NULL && $limit !== NULL) $this->db->limit($limit, $start);

        $query = $this->db->get('patients');
        return $query->result();
    }
    
    /**
     * getMCActivePatients
     *
     * @param  mixed $start
     * @param  mixed $limit
     * @return void
     */
    function getMCActivePatients($start = NULL, $limit = NULL)
    {
        $this->db->where('email !=', '')->where('status', 1)->order_by('id', 'ASC');

        if ($start !== NULL && $limit !== NULL) $this->db->limit($limit, $start);

        $query = $this->db->get('patients');
        return $query->result();
    }
    
    /**
     * getSmsLog
     *
     * @param  mixed $patient_id
     * @return void
     */
    function getSmsLog($patient_id)
    {
        $query = $this->db->select("sl.*,p.fname,p.lname,p.photo,p.gender")
            ->from('sms_log sl')
            ->join('patients p', 'sl.patient_id = p.id', 'left')
            ->where('sl.patient_id', $patient_id)
            ->order_by('sl.created', 'ASC')
            ->get();
        return $query->result();
    }
    
    /**
     * addSmsLog
     *
     * @param  mixed $data
     * @return void
     */
    function addSmsLog($data)
    {
        $this->db->insert('sms_log', $data);
    }
    
    /**
     * updateB12Val
     *
     * @param  mixed $data
     * @return void
     */
    function updateB12Val($data)
    {
        $b = $this->db->where('date', $data['date'])->where('loc_id', $data['loc_id'])->get('b12_used')->row();

        if ($b) {
            $this->db->where('id', $b->id)->update('b12_used', $data);
        } else {
            $this->db->insert('b12_used', $data);
        }
    }
    
    /**
     * getB12Used
     *
     * @param  mixed $start
     * @param  mixed $end
     * @return void
     */
    function getB12Used($start, $end)
    {
        $sql = "SELECT SUM(value) as val FROM b12_used WHERE date BETWEEN '$start' and '$end'";
        $query = $this->db->query($sql);
        return $query->row();
    }
    
    /**
     * getAllFreezed
     *
     * @return void
     */
    public function getAllFreezed()
    {
        $query = $this->db->where('freezed', 1)->get('patients');
        return $query->result();
    }
    
    /**
     * getRefGivenDate
     *
     * @param  mixed $patient
     * @return void
     */
    public function getRefGivenDate($patient)
    {
        $added = '';
        if ($patient->new_patient > 0) {
            $query = $this->db->select('pb.created')
                ->from('prepaid p')
                ->join('prepaid_brkdwn pb', 'p.id = pb.prepaid_id', 'left')
                ->where('pb.referred_by', $patient->patient_refferral_id)
                ->where('p.patient_id', $patient->id)
                ->get();
            $result = $query->row();
            $added = $result->created;
        } elseif ($patient->old_patient > 0) {
            $query = $this->db->select('pb.created')
                ->from('prepaid p')
                ->join('prepaid_brkdwn pb', 'p.id = pb.prepaid_id', 'left')
                ->where('pb.referrer', $patient->id)
                ->where('p.patient_id', $patient->patient_refferral_id)
                ->get();
            $result = $query->row();
            $added = $result->created;
        }
        return $added;
    }
    
    /**
     * getFirstVisitForYear
     *
     * @param  mixed $patient_id
     * @param  mixed $year
     * @return void
     */
    public function getFirstVisitForYear($patient_id, $year)
    {
        $sql = "SELECT v.*
                FROM visits v
                WHERE v.patient_id = $patient_id
                AND (CAST(v.visit_date AS DATE) >= '$year')
                ORDER BY v.visit_date ASC";

        $query = $this->db->query($sql);
        return $query->row();
    }
    
    /**
     * getMedDaysForYear
     *
     * @param  mixed $patient_id
     * @param  mixed $year
     * @param  mixed $this_visit
     * @return void
     */
    public function getMedDaysForYear($patient_id, $year, $this_visit)
    {
        $sql = "SELECT SUM(v.med_days) AS med_days
                FROM visits v
                WHERE v.patient_id = $patient_id
                AND (CAST(v.visit_date AS DATE) >= '$year')
                AND v.visit_date <= '$this_visit'";

        $query = $this->db->query($sql);
        return $query->row()->med_days;
    }
    
    /**
     * getPatientsByStatus
     *
     * @param  mixed $status
     * @return void
     */
    public function getPatientsByStatus($status = 1)
    {
        $query = $this->db->where('status', $status)->get('patients');
        return $query->result();
    }
}
