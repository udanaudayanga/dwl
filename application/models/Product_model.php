<?php
/**
 * Description of Product_model
 *
 * @author Udana
 */
class Product_model extends CI_Model
{
    public function add($data)
    {
	$this->db->insert('products',$data);
        return $this->db->insert_id();
    }
    
    public function getCategories()
    {
	return $this->db->get('categories')->result();
    }
    
    public function getCat($id)
    {
	$query =  $this->db->where('id',$id)->get('categories');
        return $query->row();
    }
    
    public function getAll()
    {
	$query = $this->db->query("SELECT p.*,c.name as cat_name
				   FROM products p 
				   LEFT JOIN categories c ON p.cat_id = c.id
                                   WHERE p.deleted = 0
				   ORDER BY p.created DESC");
	return $query->result();	
    }
    
    public function get($id)
    {
	$query = $this->db->where('id',$id)->get('products');
	return $query->row();
    }
    
    public function update($id,$data)
    {
	$this->db->where('id',$id)->update('products',$data);
    }
    
    public function addStock($data)
    {
	$this->db->insert('product_stock',$data);
    }
    
    public function addMainStock($data)
    {
	$this->db->insert('product_main_stock',$data);
    }
    
    public function getProStock($id,$loc_id = NULL)
    {
        if($loc_id)
        {
            $query = $this->db->select('ps.*,l.name')
                    ->from('product_stock ps')
                    ->JOIN('locations l','ps.location_id = l.id','left')
                    ->where('location_id',$loc_id)
                    ->where('pro_id',$id)
                    ->order_by('created','DESC')
                    ->get();
        }
        else
        {
                $query = $this->db->select('ps.*,l.name')
                    ->from('product_stock ps')
                    ->JOIN('locations l','ps.location_id = l.id','left')
                    ->where('pro_id',$id)
                    ->order_by('created','DESC')
                    ->get();
        }
        return $query->result();
    }
    
    public function getMainProStock($id,$loc_id = NULL)
    {
        $query = $this->db->select('ps.*')
                    ->from('product_main_stock ps')
                    ->where('pro_id',$id)
                    ->order_by('created','DESC')
                    ->get();
        
        return $query->result();
    }
    
    public function removeMainStock($id)
    {
        $this->db->where('id',$id)->delete('product_stock');
    }
    
    public function removeStock($id)
    {
        $this->db->where('id',$id)->delete('product_stock');
    }
    
    public function getCatPros($catId)
    {
        $query = $this->db->where('cat_id',$catId)->where('deleted',0)->get('products');
        return $query->result();
    }
    
    public function getStockCatPros()
    {
        $query = $this->db->query('SELECT c.id as cid,c.name as cname,p.*
                                    FROM categories c
                                    LEFT JOIN products p ON c.id = p.cat_id 
                                    WHERE p.is_stock = 1
                                    ORDER BY c.id ASC,p.name ASC');
        
        return $query->result();
    }
    
    public function getAllProWithCats()
    {
        $query = $this->db->query('SELECT c.id as cid,c.name as cname,p.*
                                    FROM categories c
                                    LEFT JOIN products p ON c.id = p.cat_id 
                                    ORDER BY c.id ASC,p.name ASC');
        
        return $query->result();
    }
    
    public function addFreeProduct($data)
    {
        $this->db->insert_batch('free_products',$data);
    }
    
    public function getFreeProducts($pro_id)
    {
        $query = $this->db->where('pro_id',$pro_id)->get('free_products');
        return $query->result_array();
    }
    
    public function getFreeProsWithNames($pro_id)
    {
        $query = $this->db->select('fp.*,p.name,c.name as cat_name,p.measure_in,p.prepaid,p.id')
                ->from('free_products fp')
                ->join('products p','fp.free_pro_id = p.id','left')    
                ->join('categories c','p.cat_id = c.id','left')
                ->where('fp.pro_id',$pro_id)->get();
        return $query->result();
    }
    
    public function delFreePro($pro_id)
    {
        $this->db->where('pro_id',$pro_id)->delete('free_products');
    }
    
    public function getProForCart($pro_id)
    {
        $query = $this->db->query("SELECT p.*,c.name as cat_name
                                    FROM products p
                                    LEFT JOIN categories c ON p.cat_id = c.id
                                    WHERE p.id = $pro_id");
        
        return $query->row();
    }
    
    public function getFreeProForCart($pro_id)
    {
        $query = $this->db->query("SELECT p.*,c.name as cat_name,fr.quantity as free_qnt
                                    FROM free_products fr
                                    LEFT JOIN products p ON fr.free_pro_id = p.id
                                    LEFT JOIN categories c ON p.cat_id = c.id
                                    WHERE fr.pro_id = $pro_id");
        
        return $query->result();
    }
    
    public function delete($id)
    {
        $this->db->where('id',$id)->delete('products');
    }
    
    public function getProLastStockLot($pro_id)
    {
        $query = $this->db->where('pro_id',$pro_id)
                        ->order_by('date','DESC')
                        ->get('product_stock');
        
        return $query->row();
    }
    
    public function getProlocStock($pro_id,$location_id)
    {
        $this->db->where('product_id',$pro_id);
        $this->db->where('location_id',$location_id);
        $query = $this->db->get('product_inventory');
        return $query->row();
    }
    
    public function getProAllInvSum($pro_id)
    {
        $query = $this->db->query("SELECT sum(quantity) as quantity FROM product_inventory WHERE product_id = $pro_id GROUP BY product_Id");
        return $query->row();
    }
    
    public function getProAllLocInv($pro_id)
    {
        $query = $this->db->query("SELECT pi.*,l.name
                                    FROM product_inventory pi
                                    LEFT JOIN locations l ON pi.location_id = l.id
                                    WHERE product_id = $pro_id");
        return $query->result();
    }
    
    public function updateProLocStock($pro_id,$location_id,$qnty)
    {
        $this->db->where('product_id',$pro_id)->where('location_id',$location_id)->update('product_inventory',array('quantity'=>$qnty));
    }
    public function updateProLocMinStock($pro_id,$location_id,$qnty)
    {
        $this->db->where('product_id',$pro_id)->where('location_id',$location_id)->update('product_inventory',array('min_stock_lvl'=>$qnty));
    }
    
    public function addProLocStock($data)
    {
        $this->db->insert('product_inventory',$data);
    }
    
    public function getProInv($pro_id)
    {
        $query = $this->db->where('product_id',$pro_id)
                ->get('product_inventory');
        return $query->result();
    }
    
    public function getAllStockPros()
    {
        $query = $this->db->where('is_stock',1)
                ->where_not_in('cat_id',array(4,10,22))
                ->get('products');
        
        return $query->result();
    }
    
    public function getPpPros()
    {
        $query = $this->db->where('prepaid',1)->get('products');
        return $query->result();
    }
    
    public function getLastMainStockLog($pro_id,$array = false)
    {
        $query = $this->db->where('pro_id',$pro_id)->order_by('created','DESC')->limit(1)->get('product_main_stock');
        return $array? $query->row_array():$query->row();
    }
    
    public function getMainStockLog($stk_log_id)
    {
        $query = $this->db->where('id',$stk_log_id)->get('product_main_stock');
        return $query->row();
    }
    
    public function getPPCatPros()
    {
        $query = $this->db->query('SELECT c.id as cid,c.name as cname,p.*
                                    FROM categories c
                                    LEFT JOIN products p ON c.id = p.cat_id 
                                    WHERE p.prepaid = 1
                                    ORDER BY c.id ASC,p.name ASC');
        
        return $query->result();
    }
}
 