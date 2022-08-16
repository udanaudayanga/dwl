<?php
/**
 * Description of Product
 *
 * @author Udana
 */
class Product extends Admin_Controller
{
    public function __construct()
    {
	parent::__construct();
        
        $this->load->model('Location_model','location');
    }
    
    public function index()
    {
	$this->data['bc1'] = 'Products';
	$this->data['bc2'] = 'View';
        $this->data['locations'] = $this->location->getAll(TRUE);
        $products = $this->product->getAll();
        $user = $this->session->userdata('user');
        
        foreach($products as &$pro)
        {
            if($user->type == 2)
            {
                $location = $this->session->userdata('location');
                $pro->stock_count = $this->product->getProlocStock($pro->id,$location->id);                
            }
            else 
            {
                $pro->stock_count = $this->product->getProAllInvSum($pro->id);
                $pro->all_stock = $this->product->getProAllLocInv($pro->id);
            }
        }
	$this->data['products'] = $products;
	$this->load->view('product/index',$this->data);
    }
    
    public function add()
    {
	$this->data['bc1'] = 'Product';
	$this->data['bc2'] = 'Add';
	$this->data['errors'] ='';
	$this->data['categories'] = $this->product->getCategories();
        $this->data['stockPros'] = getStockCategoryArray();
        $this->data['allPros'] = getAllProWithCats();
	
	if($this->input->server('REQUEST_METHOD') === 'POST')
    	{  
            $post = $this->input->post();
	    $this->form_validation->set_rules('cat_id', 'Category', 'trim|required');
	    $this->form_validation->set_rules('name', 'Name', 'trim|required');
	    $this->form_validation->set_rules('price', 'Price', 'trim|numeric|required');
	    $this->form_validation->set_rules('lsa', 'Low Stock Alert', 'trim|integer');
            $this->form_validation->set_rules('quantity', 'Product Quantity', 'trim|is_natural_no_zero|required');
            $this->form_validation->set_rules('measure_in', 'Measure In', 'trim');
            $this->form_validation->set_rules('prepaid', 'Pre Paid', 'trim');
            $this->form_validation->set_rules('is_stock', 'Is Stock Item', 'trim');
            $this->form_validation->set_rules('friendly_name', 'Friendly Name', 'trim');
            
            
            if(!isset($post['is_stock']))
            {
                $this->form_validation->set_rules('stock_item', 'Stock Item', 'trim|is_natural_no_zero|required');
                
                if(isset($post['is_combo']))
                    $this->form_validation->set_rules('combo_item', 'Combo Item', 'trim|is_natural_no_zero|required');
            }
            
            if(isset($post['free_product']) && empty($post['free_pro']) && empty($post['free_quantity']))
            {
                $this->form_validation->set_rules('free_pro_id', 'Free product', 'trim|required');
                $this->form_validation->set_rules('free_quantity', 'Free Quantity', 'trim|is_natural_no_zero|required');
            }
	    
	    if($this->form_validation->run() == TRUE)
	    {
                
		$uploaded = $this->upload();
		
		if(!isset($uploaded['error']))
		{                    
		    $post['created'] = date('Y-m-d H:i:s');
		    $post['photo'] = $uploaded['img'];
                    $free_pros  = $post['free_pro'];
                    $free_qty   = $post['free_quantity'];
                    unset($post['free_pro'],$post['free_quantity']);
                   
                    
                    
                    if(isset($post['is_stock'])) unset($post['stock_item']);
                    if(!isset($post['is_combo'])) unset($post['combo_item']);
                    if(!isset($post['free_product'])) unset($post['free_pro_id'],$post['free_quantity']);
                    
		    $new_pro_id = $this->product->add($post);
                    
                    
                    $freepros = array();
                    for($i=0;$i<4;$i++)
                    {
                        if(empty($free_pros[$i])) continue;
                        $temp = array();
                        $temp['free_pro_id'] = $free_pros[$i];
                        $temp['quantity'] = empty($free_qty[$i])? 1 : $free_qty[$i];
                        $temp['pro_id'] = $new_pro_id;
                        
                        array_push($freepros,$temp);
                    } 
                    
                    if(!empty($freepros))$this->product->addFreeProduct($freepros);
                   
		    $this->session->set_flashdata('message','Product Added Successfully');
		    redirect('product');
		}
		else
		{
		    $this->data['errors'] = $uploaded['error'];
		}
	    }
	    else
	    {
		$this->data['errors'] = validation_errors('<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12" style="padding:0 5px;"><div role="alert" class="alert fresh-color alert-danger"><strong>','</strong></div></div>');
	    }
	}
	
	$this->load->view('product/add',$this->data);
    }
    
    public function upload()
    {	
	// Upload photo
	$config['upload_path'] = './assets/upload/products/';;
	$config['allowed_types'] = 'gif|jpg|png|jpeg';
	$config['encrypt_name'] = TRUE;
	$data = array();
	$this->load->library('upload', $config);

	if (!$this->upload->do_upload('photo'))
	{
	    $data['error'] = $this->upload->display_errors('<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12" style="padding:0 5px;"><div role="alert" class="alert fresh-color alert-danger"><strong>','</strong></div></div>');
	}
	else
	{
	    $upload_data = $this->upload->data();
	    $data['img'] = $upload_data['file_name'];
	}

	return $data;
    }
    
    public function edit($pro_id)
    {
	$this->data['bc1'] = 'Product';
	$this->data['bc2'] = 'Edit';
	$this->data['errors'] ='';
	$this->data['categories'] = $this->product->getCategories();
        $this->data['stockPros'] = getStockCategoryArray();
        $this->data['allPros'] = getAllProWithCats();
        
	$product = $this->product->get($pro_id);
	$this->data['product'] = $product;
        $free_pros = $this->product->getFreeProducts($pro_id);
        $this->data['free_pros'] = $free_pros;
        
       
	if($this->input->server('REQUEST_METHOD') === 'POST')
    	{  
	   $post = $this->input->post();
	    $this->form_validation->set_rules('cat_id', 'Category', 'trim|required');
	    $this->form_validation->set_rules('name', 'Name', 'trim|required');
	    $this->form_validation->set_rules('price', 'Price', 'trim|numeric|required');
	    $this->form_validation->set_rules('lsa', 'Low Stock Alert', 'trim|integer');
            $this->form_validation->set_rules('quantity', 'Product Quantity', 'trim|is_natural_no_zero|required');
            $this->form_validation->set_rules('measure_in', 'Measure In', 'trim');
            $this->form_validation->set_rules('prepaid', 'Pre Paid', 'trim');
            $this->form_validation->set_rules('is_stock', 'Is Stock Item', 'trim');
            $this->form_validation->set_rules('friendly_name', 'Friendly Name', 'trim');
            
            if(!isset($post['is_stock']))
            {
                $this->form_validation->set_rules('stock_item', 'Stock Item', 'trim|is_natural_no_zero|required');
                
                if(isset($post['is_combo']))
                    $this->form_validation->set_rules('combo_item', 'Combo Item', 'trim|is_natural_no_zero|required');
            }
            
            if(isset($post['free_product']) && empty($post['free_pro']) && empty($post['free_quantity']))
            {
                $this->form_validation->set_rules('free_pro_id', 'Free product', 'trim|required');
                $this->form_validation->set_rules('free_quantity', 'Free Quantity', 'trim|is_natural_no_zero|required');
            }
	    
	    if($this->form_validation->run() == TRUE)
	    {
		$post = $this->input->post();
                
		$uploaded = array();
		
		if (!empty($_FILES['photo']['name'])) {
		    $uploaded = $this->upload();
		}
		
		if(!isset($uploaded['error']))
		{
		    $post['created'] = date('Y-m-d H:i:s');
		    if(isset($uploaded['img']))
		    {
			$post['photo'] = $uploaded['img'];
                        if(file_exists("./assets/upload/products/$product->photo"))unlink("./assets/upload/products/$product->photo");
		    }
                    
                    if(!isset($post['is_stock'])) $post['is_stock'] = 0;
                    if(!isset($post['prepaid'])) $post['prepaid'] = 0;
                    if(!isset($post['is_combo'])) 
                    {
                        $post['is_combo'] = 0;
                        unset($post['combo_item']);
                    }
                    if(!isset($post['free_product']))$this->product->delFreePro($pro_id);   
                    
                    $free_pros  = $post['free_pro'];
                    $free_qty   = $post['free_quantity'];
                    unset($post['free_pro'],$post['free_quantity']);
		    
		    $this->product->update($pro_id,$post);
                    
                    if(isset($post['free_product']))
                    {
                        $freepros = array();
                        for($i=0;$i<4;$i++)
                        {
                            if(empty($free_pros[$i])) continue;
                            $temp = array();
                            $temp['free_pro_id'] = $free_pros[$i];
                            $temp['quantity'] = empty($free_qty[$i])? 1 : $free_qty[$i];
                            $temp['pro_id'] = $pro_id;

                            array_push($freepros,$temp);
                        } 
                        $this->product->delFreePro($pro_id); 
                        if(!empty($freepros))$this->product->addFreeProduct($freepros);
                    }
                                       
		    $this->session->set_flashdata('message','Product Updated Successfully');
		    redirect('product');
		}
		else
		{
		    $this->data['errors'] = $uploaded['error'];
		}	
	    }
	    else
	    {
		$this->data['errors'] = validation_errors('<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12" style="padding:0 5px;"><div role="alert" class="alert fresh-color alert-danger"><strong>','</strong></div></div>');
	    }
	}
	
	$this->load->view('product/edit',$this->data);
    }
    
    public function addStock()
    {
	$this->form_validation->set_rules('date', 'Batch Date', 'trim|required');
        $this->form_validation->set_rules('location_id', 'location', 'trim|required');
	$this->form_validation->set_rules('quantity', 'Quantity', 'trim|is_natural_no_zero|required');
	$this->form_validation->set_rules('lot_no', 'Lot #', 'trim|required');
	$this->form_validation->set_rules('exp_date', 'Expire Date', 'trim|required');
	
	$result = array();
	
	if($this->form_validation->run() == TRUE)
	{
	    $post = $this->input->post();
            $product = $this->product->get($post['pro_id']);
            
            if($product->stock > $post['quantity'])
            {
                $post['date'] .= "-01";
                $post['exp_date'] .= "-01";
                $post['created'] = date('Y-m-d');
                $this->product->addStock($post);

                addProStock($post['pro_id'],$post['location_id'], $post['quantity']);
                
                $new_stock = $product->stock - $post['quantity'];
                $this->product->update($product->id,array('stock'=>$new_stock));

                $result['status'] = 'success';
                $result['msg'] = '<div role="alert" class="alert fresh-color alert-success"><strong>Stock Added Successfully.</strong></div>';
            }
            else
            {
                $result['status'] = 'error';
                $result['errors'] = '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="padding:0 5px;"><div role="alert" class="alert fresh-color alert-danger"><strong>No enough main stock to allocate.</strong></div></div>';
	    
            }
	}
	else
	{
	    $result['status'] = 'error';
	    $result['errors'] = validation_errors('<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="padding:0 5px;"><div role="alert" class="alert fresh-color alert-danger"><strong>','</strong></div></div>');
	    
	}
	
	echo json_encode($result);
    }
    
    public function addMainStock()
    {
	$this->form_validation->set_rules('date', 'Batch Date', 'trim|required');
	$this->form_validation->set_rules('quantity', 'Quantity', 'trim|is_natural_no_zero|required');
	$this->form_validation->set_rules('lot_no', 'Lot #', 'trim|required');
	$this->form_validation->set_rules('exp_date', 'Expire Date', 'trim|required');
	
	$result = array();
	
	if($this->form_validation->run() == TRUE)
	{
	    $post = $this->input->post();
            $post['date'] .= "-01";
            $post['exp_date'] .= "-01";
            $post['created'] = date('Y-m-d');
	    
            $product = $this->product->get($post['pro_id']);   
            //Update stock figure
            $newQty = $product->stock + $post['quantity'];
            $this->product->update($product->id,array('stock'=>$newQty));
            
            //Add main stock
            $this->product->addMainStock($post);
            
	    $result['status'] = 'success';
	    $result['msg'] = '<div role="alert" class="alert fresh-color alert-success"><strong>Stock Added Successfully.</strong></div>';
	}
	else
	{
	    $result['status'] = 'error';
	    $result['errors'] = validation_errors('<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="padding:0 5px;"><div role="alert" class="alert fresh-color alert-danger"><strong>','</strong></div></div>');
	    
	}
	
	echo json_encode($result);
    }
    
    public function viewStock($id)
    {
        $location = $this->session->userdata('location');
        $user = $this->session->userdata('user');
        $location_id = in_array($user->type, array(1,4))? NULL:$location->id;
        $this->data['stocks'] = $this->product->getProStock($id,$location_id);
        echo $this->load->view('product/pro_stock_partial',$this->data,TRUE);
    }
    
    public function viewMainStock($id)
    {        
        $user = $this->session->userdata('user');        
        $this->data['stocks'] = $this->product->getMainProStock($id);
        echo $this->load->view('product/pro_main_stock_partial',$this->data,TRUE);
    }
    
    public function remStock()
    {
        $post = $this->input->post();
        $this->product->removeStock($post['stk_id']);
        echo json_encode(array('status'=>'success'));
    }
    
    public function remMainStock()
    {
        $post = $this->input->post();
        $stklog = $this->product->getMainStockLog($post['stk_id']);
        $product = $this->product->get($stklog->pro_id);
        
        $result = array();
        
        if($stklog->quantity < $product->stock)
        {
             $this->product->removeMainStock($post['stk_id']);
             $newMainStock = $product->stock - $stklog->quantity;
             
             $this->product->update($product->id,array('stock'=>$newMainStock));
             $result['status'] = 'success';
        }
        else
        {
            $result['status'] = 'error';
            $result['msg'] = 'Stock has utilized, cannot remove!';
        }
        
        echo json_encode($result);
    }
    
    public function delete($id)
    {
        if($this->order->hasProductOrders($id))
        {
            $this->session->set_flashdata('error','Product has used in orders. Not removed.');
            redirect('product');
        }
        else 
        {
            $product = $this->product->get($id);
            if($product->photo)
                if(file_exists("./assets/upload/products/$product->photo"))unlink("./assets/upload/products/$product->photo");
                
            $this->product->delete($id);
            $this->session->set_flashdata('message','Product removed successfully.');
            redirect('product');
        }
    }
    
    public function getLastMainStockLog($pro_id)
    {
        $log = $this->product->getLastMainStockLog($pro_id,true);
        echo json_encode($log);
    }
    
    
}
