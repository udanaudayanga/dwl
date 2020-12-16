<?php
/**
 * Description of Order_model
 *
 * @author Udana
 */
class Order_model extends CI_Model
{
    public function checkProPurchase($patient_id,$product_id,$date)
    {
	$query = $this->db->query("SELECT oi.*
				   FROM order_items oi
				   LEFT JOIN orders o ON oi.order_id = o.id
				   WHERE date(o.created) = '$date'
				   AND o.patient_id = $patient_id AND oi.product_id = $product_id ");
	
	return $query->row();
    }
    
    public function getPrepaidItem($patient_id,$pro_id)
    {
        $query = $this->db->select('pp.*,p.name')
                ->from('prepaid pp')
                ->join('products p','pp.pro_id = p.id','left')
                ->where('pp.patient_id',$patient_id)
                ->where('pp.pro_id',$pro_id)
                ->get();
        return $query->row();
    }
    
    public function getPPItem($id)
    {
        $query = $this->db->where('id',$id)->get('prepaid');
        return $query->row();
    }
    
    public function addPrepaidItem($data)
    {
        $this->db->insert('prepaid',$data);        
        return $this->db->insert_id();
    }
    
    public function updatePrepaidItem($id,$data)
    {
        $this->db->where('id',$id)->update('prepaid',$data);
    }
    
    public function addPPBrkdwn($data)
    {
        $this->db->insert('prepaid_brkdwn',$data);
        return $this->db->insert_id();
    }
    
    public function updatePPBrkdwn($id,$data)
    {
        $this->db->where('id',$id)->update('prepaid_brkdwn',$data);
    }
    
    public function removePPBrkdwn($ppbid)
    {
        $this->db->where('id',$ppbid)->delete('prepaid_brkdwn');
    }
    
    public function getTodayExPPBrkdwnSub($prepaid_id)
    {
        $query = $this->db->where('prepaid_id',$prepaid_id)
                ->where('created',date('Y-m-d'))
                ->where('type','subtract')
                ->get('prepaid_brkdwn');
        return $query->row();
    }
    
    public function getRedeemedItems($patient_id,$date)
    {
        $query = $this->db->query("SELECT p.name,p.friendly_name,ppb.quantity,pp.id,p.cat_id,p.is_stock,p.stock_item,p.id as product_id,p.is_combo,p.combo_item,p.measure_in
                                    FROM prepaid pp
                                    LEFT JOIN prepaid_brkdwn ppb ON pp.id = ppb.prepaid_id 
                                    LEFT JOIN products p ON pp.pro_id = p.id 
                                    WHERE pp.patient_id = $patient_id
                                    AND ppb.type = 'subtract'
                                    AND ppb.created = '$date'");
        
        return $query->result();
    }
    
    public function getPPLatestAddedOrder($prepaid_id)
    {
        $query = $this->db->where('prepaid_id',$prepaid_id)
                          ->where('type','add')
                          ->where('add_type','order')
                          ->order_by('created','DESC')
                          ->limit(1)
                          ->get('prepaid_brkdwn');
        return $query->row();
    }
    
    public function getPPLatestAddedOrderPrior($prepaid_id,$date)
    {
        $query = $this->db->where('prepaid_id',$prepaid_id)
                          ->where('type','add')
                          ->where('add_type','order')
                          ->where('created <',$date)
                          ->order_by('created','DESC')
                          ->limit(1)
                          ->get('prepaid_brkdwn');
        return $query->row();
    }
    
    public function getPatientPP($patient_id)
    {
        $query = $this->db->query("SELECT pp.*,p.name,p.cat_id,p.measure_in
                                    FROM prepaid pp
                                    LEFT JOIN products p ON pp.pro_id = p.id
                                    WHERE pp.patient_id = $patient_id AND pp.remaining > 0");
        return $query->result();
    }
    
    public function getPatientAllPP($patient_id)
    {
        $query = $this->db->query("SELECT pp.*,p.name,p.cat_id,p.measure_in
                                    FROM prepaid pp
                                    LEFT JOIN products p ON pp.pro_id = p.id
                                    WHERE pp.patient_id = $patient_id");
        return $query->result();
    }
    
    public function getPPHistory($id)
    {
        $query = $this->db->query("SELECT pp.*,l.name,l.abbr,CONCAT(r.lname,' ',r.fname) as referrer_name,CONCAT(rb.lname,' ',rb.fname) as referred_by_name  
                                    FROM prepaid_brkdwn pp
                                    LEFT JOIN locations l ON pp.location_id = l.id
                                    LEFT JOIN patients r ON pp.referrer = r.id
                                    LEFT JOIN patients rb ON pp.referred_by = rb.id
                                    WHERE pp.prepaid_id = $id
                                    ORDER BY pp.created DESC");
        return $query->result();
    
    }
    
    public function addOrder($data)
    {
        $this->db->insert('orders',$data);
        return $this->db->insert_id();
    }
    
        
    public function addOrderItems($items)
    {
        $this->db->insert_batch('order_items',$items);
    }
    
    public function removeOrderItems($order_id)
    {
        $this->db->where('order_id',$order_id)->delete('order_items');
    }
    
    public function removeOrderItem($order_id,$pro_id)
    {
        $this->db->where('order_id',$order_id)->where('product_id',$pro_id)->delete('order_items');
    }
    
    public function getPendingOrders($location_id)
    {
        $today = date('Y-m-d');
        $query = $this->db->query("SELECT o.*, CONCAT(p.lname,' ',p.fname) as patient_name,v.prescription_no,v.id as visit_id,p.photo
                                    FROM orders o
                                    LEFT JOIN patients p ON o.patient_id = p.id
                                    LEFT JOIN visits v ON o.id = v.order_id
                                    WHERE (((o.status = 0 OR (CAST(o.created AS DATE)='$today' && o.status != 2)) AND o.location_id = $location_id) OR o.status = 4)
                                    ORDER BY created DESC");    
        return $query->result();
    }
    
    public function getOrder($id)
    {
        $query = $this->db->where('id',$id)->get('orders');
        return $query->row();
    }
    
    public function getOrdersByDate($date)
    {
        $sql = "SELECT * FROM orders WHERE (CAST(created AS DATE)) = '$date'";
        
        $query = $this->db->query($sql);
        return $query->result();
    }
    
    public function updateOrder($id,$data)
    {
        $this->db->where('id',$id)->update('orders',$data);
    }
    
    public function getOrderItem($order_id)
    {
        $query = $this->db->where('order_id',$order_id)->get('order_items');
        return $query->result();
    }
    
    public function getOrderAddedPP($order_id,$patient_id)
    {
        $query = $this->db->query("SELECT pp.id AS pp_id,remaining,quantity,ppb.id AS ppb_id
                                FROM prepaid pp
                                LEFT JOIN prepaid_brkdwn ppb ON pp.id = ppb.prepaid_id 
                                WHERE pp.patient_id = $patient_id AND ppb.order_id = $order_id");
        return $query->result();
    }
    
    public function getOrderAddedPPItem($patient_id,$pro_id,$order_id)
    {
        $query = $this->db->query("SELECT pp.id AS pp_id,remaining,quantity,ppb.id AS ppb_id
                                FROM prepaid pp
                                LEFT JOIN prepaid_brkdwn ppb ON pp.id = ppb.prepaid_id 
                                WHERE pp.patient_id = $patient_id AND ppb.order_id = $order_id AND pp.pro_id = $pro_id AND ppb.type='add'");
        return $query->row();
    }
    
    public function getPatientOrders($patient_id)
    {
        $query = $this->db->query("SELECT o.*,l.name as location,v.id as vid
                                FROM orders o
                                LEFT JOIN locations l ON o.location_id = l.id 
                                LEFT JOIN visits v ON o.id = v.order_id
                                WHERE o.patient_id = $patient_id
                                AND o.status != 2
                                ORDER BY o.created DESC");
        return $query->result();
    }
    
    public function hasProductOrders($proId)
    {
        $query = $this->db->where('product_id',$proId)->get('order_items');
        return $query->num_rows()? TRUE:FALSE;
    }
    
    public function getOrderItemsWithNames($order_id)
    {
        $query = $this->db->query("SELECT o.*, p.name,p.is_stock,p.stock_item,p.cat_id,p.prepaid,p.measure_in,p.quantity as pro_qty,p.is_combo,p.combo_item,p.friendly_name
                                    FROM order_items o
                                    LEFT JOIN products p ON o.product_id = p.id
                                    WHERE o.order_id = $order_id
                                    ");
        
        return $query->result();
    }
    
    public function getPPDaysRedim($patient_id,$date)
    {
        $query = $this->db->query("SELECT ppb.* 
                                FROM prepaid pp
                                LEFT JOIN products p ON pp.pro_id = p.id 
                                LEFT JOIN prepaid_brkdwn ppb ON pp.id = ppb.prepaid_id 
                                WHERE pp.patient_id = $patient_id
                                AND p.measure_in = 'Days' 
                                AND ppb.created = '$date'
                                AND ppb.type = 'subtract'");
        
        return $query->row();
    }
    
    public function getPPRedeemBrkDwn($pp_id,$date = null)
    {
        $created = ($date)?$date:date('Y-m-d');
        $query = $this->db->where('prepaid_id',$pp_id)->where('type','subtract')->where('created',$created)->order_by('id','DESC')->get('prepaid_brkdwn');
        
        return $query->result();
    }
    
    public function removeOrder($order_id)
    {
        $this->db->where('id',$order_id)->delete('orders');
    }
    
    public function getRemovedOrders()
    {
        $query = $this->db->select('o.*,p.fname,p.lname,l.name as loc_name')
                        ->from('orders o')
                        ->join('patients p','o.patient_id = p.id','left')
                        ->join('locations l','o.location_id = l.id','left')
                        ->where('o.status',2)
                        ->get();
        
        return $query->result();
    }
    
    public function getTodayOrder($patient_id)
    {
        $query = $this->db->where('patient_id',$patient_id)
                ->where('DATE(created)',date('Y-m-d'))
                ->where('status !=',2)
                ->get('orders');
        return $query->row();
    }
    
    public function getOrderItemsOnLocOnDay($loc_id,$day)
    {
        $sql = "SELECT oi.*,p.cat_id,p.is_stock,p.stock_item,p.is_combo,p.combo_item,p.prepaid
               FROM order_items oi
               LEFT JOIN orders o ON oi.order_id = o.id
               LEFT JOIN products p ON oi.product_id = p.id
               WHERE o.location_id = $loc_id
               AND CAST(o.created AS DATE) = '$day'";
        
        $query = $this->db->query($sql);
        return $query->result();
    }
    
    public function getPPRedeemOnLocOnDay($loc_id,$day)
    {
        $sql = "SELECT ppb.quantity,p.cat_id,p.is_stock,p.stock_item,p.is_combo,p.combo_item,p.prepaid
                FROM prepaid_brkdwn ppb
                LEFT JOIN prepaid pp ON ppb.prepaid_id = pp.id
                LEFT JOIN products p ON pp.pro_id = p.id
                WHERE ppb.type = 'subtract'
                AND ppb.created = '$day'
                AND ppb.location_id = $loc_id";
        
        $query = $this->db->query($sql);
        
        return $query->result();
    }
    
    public function getPatientOrdersForPeriod($patient_id,$start,$end)
    {
        $query = $this->db->query("SELECT o.*
                                   FROM orders o
                                   WHERE CAST(o.created as DATE) BETWEEN '$start' AND '$end'
                                   AND o.net_total > 0
                                   AND o.status != 2
                                   AND o.patient_id = $patient_id");
        return $query->result();
    }
    
    public function addRestart($data)
    {
        $this->db->insert('restarts',$data);
    }
    
    public function getRestarts($patientId)
    {
        $query = $this->db->where('patient_id',$patientId)->order_by('date','DESC')->get('restarts');
        return $query->result();
    }
    
    public function getLastRestart($patient_id)
    {
        $query = $this->db->where('patient_id',$patient_id)->order_by('date','DESC')->get('restarts');
        return $query->row();
    }
    
    public function addOrderItem($item)
    {
        $this->db->insert('order_items',$item);
    }
    
    public function getOrderItm($order_id,$product_id)
    {
        $query = $this->db->where('order_id',$order_id)->where('product_id',$product_id)->get('order_items');
        return $query->row();
    }
    
    public function updateOrderItem($order_id,$product_id,$data)
    {
        $this->db->where('order_id',$order_id)->where('product_id',$product_id)->update('order_items',$data);
    }

    public function geLastOrder($patient_id)
    {
        $query = $this->db->where('patient_id',$patient_id)->order_by('created','DESC')->get('orders');
        return $query->row();
    }
}
