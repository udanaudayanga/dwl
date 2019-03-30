<?php

/**
 * Description of Order
 *
 * @author Udana
 */
class Order extends Admin_Controller
{
    public function __construct() {
        parent::__construct();
        
        $this->load->library('cart');
        $this->load->model('Evaluate_model','evaluate');   
        $this->load->model('User_model','user');  
        $this->load->model('marketing_model','marketing');
        $this->load->model('Promos_model','promos');
    }
    
    public function add($patient_id)
    {
        
        $this->data['bc1'] = 'Order';
	$this->data['bc2'] = 'Add';

        
        //check whether there's a order edit
        if($this->session->userdata('edit_order_id'))
        {
            $this->cart->destroy();
            $this->session->unset_userdata('patient_category');
            $this->session->unset_userdata('edit_order_id');
            
            $this->session->set_flashdata('error', "Shopping cart has modified by an edit order, Please always use one tab/window for shopping cart.Order resetted.");
            redirect("order/add/$patient_id");
        }
        
        
        $this->data['categories'] = $this->product->getCategories();
        $patient = $this->patient->getPatient($patient_id);
        $this->data['patient'] = $patient;
        $this->data['patient_id'] = $patient->id;
        $this->data['patient_category'] = $patient->patient_category;
        $this->session->set_userdata('patient_category',$patient->patient_category);
        $this->data['staff'] = $this->user->getUserByType(array(2,4));
        $this->data['coupon'] = $this->session->userdata('coupon_'.$patient_id);

        
        if($this->input->server('REQUEST_METHOD') === 'POST')
    	{
            $post = $this->input->post();
            $order = $order_items = array();
            
            $dayProducts = FALSE;
            $pp_redim = $this->order->getPPDaysRedim($patient_id,date('Y-m-d'));
            if($pp_redim) $dayProducts = TRUE;
            $visit_cats = $this->config->item('visit_cat');
            
            
            //Check stock before saving
            foreach ($this->cart->contents() as $item)
            {
                $stock = true;
                $error = '';
                $product = $item['product'];
                
                if(in_array($product->cat_id,$visit_cats))$dayProducts = TRUE;
                
                if(!checkStock($item['id'], $item['qty']))
                {   
                    $this->session->set_flashdata('error', "Product: $product->name, Doesn't have enough stock. Order cannot save with an out of stock item.");
                    redirect("order/add/$patient_id");
                }
            }
            
            
            if(!$dayProducts && $post['status'] == 0)
            {
                $this->session->set_flashdata('error', "No Visits redeemed or bought");
                redirect("order/add/$patient_id");
            }
            
            $order['patient_id'] = $patient_id;
            $order['location_id'] = $this->data['location']->id;
//            $order['patient_category'] = $this->data['patient_category'];
            $order['payment_type'] = $post['payment_type'];
            if(!empty($post['credit_amount']))$order['credit_amount'] = $post['credit_amount'];
            
            $discount = getCartDicount($patient_id);
	    $cartTotal = $this->cart->total();
            
            $order['gross_total'] = number_format($cartTotal,2,'.','');
            $order['discount'] = number_format($discount,2,'.','');
            $order['net_total'] = number_format(($cartTotal - $discount),2,'.','');
            $order['created'] = date('Y-m-d H:i:s');
            $order['staff_id'] = $post['staff_id'];
            $order['status'] = $post['status'];
            
            if($coupon = $this->session->userdata('coupon_'.$patient_id))
            {
                $order['coupon'] = $coupon;
            }
            
            if($post['payment_type'] == 'mix' && (empty($post['credit_amount']) ||  $post['credit_amount'] > $order['net_total']))
            {
                $this->session->set_flashdata('error', "Credit Amount is required");
                redirect("order/add/$patient_id");
            }
                      
            $order_id = $this->order->addOrder($order);
            
            
            foreach ($this->cart->contents() as $item)
            {
                $temp = array();
                $temp['order_id'] = $order_id;
                $temp['product_id'] = $item['id'];
                $temp['quantity'] = $item['qty'];
                $temp['price'] = number_format($item['price'],2,'.','');
                array_push($order_items,$temp);
            }
            
            $this->order->addOrderItems($order_items);
            
            if($post['status'] == 0 && ($patient->status == 2 || $patient->status == 3))
            {
                $restart = array();
                $restart['patient_id'] = $patient->id;
                $restart['date'] = date('Y-m-d');
                $restart['type'] = $patient->status == 2 ? 1:2;
                $restart['order_id'] = $order_id;
                $this->order->addRestart($restart);
            }
            
            $hvPP = false;
            
            foreach ($this->cart->contents() as $item)
            {
                $pro = $item['product'];
                if($pro->prepaid == 1)
                {
                    $new_p_pp = array();
                    $new_p_pp['patient_id'] = $patient_id;
                    $new_p_pp['location_id'] = $this->data['location']->id;
                    $new_p_pp['pro_id'] = $pro->id;
                    $new_p_pp['type'] = 'add';       
                    
                    $new_p_pp['quantity'] = $item['qty'] * $pro->quantity;
                    $new_p_pp['add_type'] = 'order';
                    $new_p_pp['order_id'] = $order_id;
                    $new_p_pp['created'] = date('Y-m-d');
                    addPrepaidItem($new_p_pp);
                    $hvPP = TRUE;
                }
                
                // reduce stock
                if($pro->prepaid==0) reduceStock($pro->id,$item['qty']);
            }
            
            if($coupon = $this->session->userdata('coupon_'.$patient_id))
            {
                $this->marketing->updateCoupon($coupon,array('status'=>1));
                $this->session->unset_userdata('coupon_'.$patient_id);
                $this->session->unset_userdata('cd_'.$patient_id);
            }
            
            markQueue($patient);
            
            sendQueueMsg($patient_id,2);                
            
            $this->cart->destroy();
            $this->session->unset_userdata('patient_category');
            
            $this->session->set_flashdata('message','Order added successfully.');
            $redirect  = $hvPP? "order/pending?pid=$patient_id":"order/pending";
            redirect($redirect);
        }
        
        $this->load->view('order/add',$this->data);
    }
    
    public function getCatProDropDown($cat)
    {
        $products = $this->product->getCatPros($cat);
        $free_pros = $this->config->item('allow_free_pros');
        
        $select = '<select class="form-control" name="pro_id" id="pro_id" tabindex="-1" aria-hidden="true" style="width: 100%;">';
        foreach($products as $pro)
        {
            if($pro->price == 0 && !in_array($pro->id,$free_pros)) continue;
            $select .= '<option value="'.$pro->id.'">'.$pro->name.'</option>';
        }
        $select .= '</select>';
        
        echo $select;
    }
    
    public function addProCart()
    {
        $post = $this->input->post();
        $result = array();
        $stock = true;
        $fps = array();
        $error = '';
        $patient_id = $post['patient_id'];
        $this->data['patient_id'] = $patient_id;
        
        $product = $this->product->getProForCart($post['pro_id']);
        
        
        //check stock
        if(checkStock($product->id, $post['qty']))
        {   
            $stock = true;
            if($product->free_product)
            {
                $fps = $this->product->getFreeProsWithNames($product->id);
                foreach($fps as $fp)
                {
                    $free_qty = $fp->quantity * $post['qty'];
                    if(checkStock($fp->free_pro_id, $free_qty))
                    {
                        $stock = TRUE;
                    }
                    else
                    {
                        $stock = false;
                        $error = "Free Product: $fp->name, Doesn't have enough stock.";
                        break;
                    }
                }
            }
        }
        else
        {
            $stock = false;
            $error = "Product: $product->name, Doesn't have enough stock.";
        }
        
        if($stock)
        {
            $free_combine = 0;
            foreach($fps as $fp)
            {
                $free_combine .= $free_combine==0?$fp->free_pro_id:",".$fp->free_pro_id;
            }
            
            $ppbulk_cats = $this->config->item('bulk_visit_pp');
            $free_pro_for_visit = $this->config->item('free_pro_for_visit');
            if($ppbulk_cats == $product->cat_id)
            {
                $free_combine .= $free_combine==0? $free_pro_for_visit:",".$free_pro_for_visit;
            }
            
                $data = array(
                    'id'      => $product->id,
                    'qty'     => $post['qty'],
                    'price'   => number_format($product->price,2,'.',''),
                    'name'    => $product->name,
                    'product' => $product,
                    'combined_id'=> $free_combine
                );
                
                $this->cart->product_name_rules = '\d\D';
                $row_id = $this->cart->insert($data);
                
                //check and add free product
                if($fps)
                {
                    foreach($fps as $fp)
                    {
                        $pro = $this->product->getProForCart($fp->free_pro_id);
                        $free = array(
                            'id'      => $fp->free_pro_id,
                            'qty'     => $fp->quantity * $post['qty'],
                            'price'   => number_format(0,2),
                            'name'    => $fp->name,
                            'product' => $pro,
                            'combined_id' => 0
                        );

                        $this->cart->product_name_rules = '\d\D';
                        $free_rawid = $this->cart->insert($free);
                    }
                }
                
//                if($ppbulk_cats == $product->cat_id)
//                {
//                    $cynofree = $this->product->getProForCart($free_pro_for_visit);
//                    $weeks = $product->quantity/7;
//                    
//                     $free = array(
//                            'id'      => $free_pro_for_visit,
//                            'qty'     => $weeks,
//                            'price'   => number_format(0,2),
//                            'name'    => $cynofree->name,
//                            'product' => $cynofree,
//                            'combined_id' => 0
//                        );
//
//                    $this->cart->product_name_rules = '\d\D';
//                    $free_rawid = $this->cart->insert($free);
//                }
                
                if($coupon = $this->session->userdata('coupon_'.$patient_id))
                {
                    updateCoupon($coupon,$patient_id);
                }

                $result['status'] = 'success';
                $result['cart'] = $this->load->view('order/_cart',$this->data,TRUE);
        }
        else
        {
            $result['status'] = 'error';
            $result['msg'] = $error;
        }
        
        
        echo json_encode($result);
    }
    
    public function delPro()
    {
        $post = $this->input->post();
        $patient_id = $post['pid'];
        $this->data['patient_id'] = $patient_id;
        
        $data = array(
        'rowid' => $post['raw_id'],
        'qty'   => 0
        );

        $this->cart->update($data);
        
        if($post['combine_id'] != 0)
        {
            $combine_id = explode(',',$post['combine_id']);
            
           foreach ($this->cart->contents() as $item)
           { 
               if(in_array($item['id'],$combine_id))
               {
                   $removeCombined = array();
                   $removeCombined['rowid'] = $item['rowid'];
                   $removeCombined['qty'] = 0;
                   $this->cart->update($removeCombined);
               }
           }
        }
        if($coupon = $this->session->userdata('coupon_'.$patient_id))
        {
            updateCoupon($coupon,$patient_id);
        }
        
        echo $this->load->view('order/_cart',$this->data,TRUE);
    }
    
    public function setPatientCat()
    {
        $post = $this->input->post();
        $this->session->set_userdata('patient_category',$post['pcat']);
        echo $this->load->view('order/_cart',$this->data,TRUE);
    }
    
    public function pending()
    {
        $this->data['bc1'] = 'Prescription';
	$this->data['bc2'] = 'Pending';
        $location = $this->data['location'];
        $this->data['orders'] = $this->order->getPendingOrders($location->id);
        $this->load->view('order/pending',$this->data);
    }
    
    public function updateDone()
    {
        $post = $this->input->post();
        
        if($post['done'] == 4)
        {
            $location_id = $this->data['location']->id;
            $this->order->updateOrder($post['id'],array('status'=> 0,'location_id'=>$location_id));            
        }
        else
        {
            $this->order->updateOrder($post['id'],array('status'=>$post['done']==0?1:0));
        }
        
        echo 'success';
    }
    
    public function edit($order_id)
    {
     
        $this->data['bc1'] = 'Order';
	$this->data['bc2'] = 'Edit';
        
        $exorder = $this->order->getOrder($order_id);
        $user = $this->session->userdata('user');
        
        if($user->type !=1 && date('Y-m-d',strtotime($exorder->created)) != date('Y-m-d'))
        {
            $this->session->set_flashdata('error','Sorry! cannot edit that order.');
            redirect('order/pending');
        }
        
        $this->data['order'] = $exorder;
        
        $this->data['categories'] = $this->product->getCategories();
        $patient = $this->patient->getPatient($exorder->patient_id);
        $this->data['patient'] = $patient;
        $this->data['staff'] = $this->user->getUserByType(array(2,4));
//        $this->data['prepaids'] = $this->order->getPatientPP($exorder->patient_id);
//        $redeemToday = $this->order->getRedeemedItems($exorder->patient_id,date('Y-m-d'));
//        
//        foreach($redeemToday as &$redeem)
//        {
//            $add = $this->order->getPPLatestAddedOrder($redeem->id);
//            if($add) $redeem->order = $add->order_id;
//        }
//        
//        $this->data['rt'] = $redeemToday;
        
        $reload = FALSE;
        
        if($ex_order_id = $this->session->userdata('edit_order_id'))
        {
            if($ex_order_id != $order_id)
            {
                $this->cart->destroy();
                $this->session->unset_userdata('patient_category');
                $reload = TRUE;
            }            
        }
        else
        {
            $this->cart->destroy();
            $this->session->unset_userdata('patient_category');
            $reload = TRUE;
        }
        
        if($reload)
        {
            $this->populateOrder($order_id);
            $this->session->set_userdata('patient_category',$exorder->patient_category);            
        }
        $this->session->set_userdata('patient_category',$patient->patient_category);
        
        
        if($this->input->server('REQUEST_METHOD') === 'POST')
    	{
            $post = $this->input->post();
            $order = $order_items = array();
            
            $order['patient_id'] = $exorder->patient_id;
            $order['location_id'] = $this->data['location']->id;
//            $order['patient_category'] = $this->data['patient_category'];
            $order['payment_type'] = $post['payment_type'];
            
            $discount = getCartDicount($exorder->patient_id);
	    $cartTotal = $this->cart->total();
            
            $order['gross_total'] = number_format($cartTotal,2,'.','');
            $order['discount'] = number_format($discount,2,'.','');
            $order['net_total'] = number_format(($cartTotal - $discount),2,'.','');
            
            $order['created'] = date('Y-m-d H:i:s');
            $order['staff_id'] = $post['staff_id'];
            if(!empty($post['credit_amount']))$order['credit_amount'] = $post['credit_amount'];
            
            $order['status'] = $post['status'];
            
            $this->order->updateOrder($order_id,$order);
            $this->restore_stock($order_id);
            $this->order->removeOrderItems($order_id);
            removePrepaid($order_id,$exorder->patient_id);
            
            
            foreach ($this->cart->contents() as $item)
            {
                $temp = array();
                $temp['order_id'] = $order_id;
                $temp['product_id'] = $item['id'];
                $temp['quantity'] = $item['qty'];
                $temp['price'] = number_format($item['price'],2,'.','');
                array_push($order_items,$temp);                
            }
            
            $this->order->addOrderItems($order_items);
            
            foreach ($this->cart->contents() as $item)
            {
                $pro = $item['product'];
                if($pro->prepaid == 1)
                {
                    $new_p_pp = array();
                    $new_p_pp['patient_id'] = $exorder->patient_id;
                    $new_p_pp['location_id'] = $this->data['location']->id;
                    $new_p_pp['pro_id'] = $pro->id;
                    $new_p_pp['type'] = 'add';  
                    $pro_qty = $pro->measure_in == 'Days' ? $item['qty'] * $pro->quantity : $item['qty'];
                    $new_p_pp['quantity'] = $item['qty'] * $pro->quantity;
                    $new_p_pp['add_type'] = 'order';
                    $new_p_pp['order_id'] = $order_id;
                    $new_p_pp['created'] = date('Y-m-d');
                    addPrepaidItem($new_p_pp);
                }
                
                // reduce stock
                if($pro->prepaid==0) reduceStock($pro->id,$item['qty']);
            }
            
            $this->cart->destroy();
            $this->session->unset_userdata('patient_category');
            $this->session->unset_userdata('edit_order_id');
            
            $this->session->set_flashdata('message','Order added successfully.');
            redirect('order/pending');
        }
        
        $this->load->view('order/edit',$this->data);
    }
    
    public function restore_stock($order_id)
    {
        $order_items = $this->order->getOrderItem($order_id);
        $exorder = $this->order->getOrder($order_id);
        
        foreach($order_items as $item)
        {
            $product = $this->product->get($item->product_id);
            
            if($product->measure_in == 'Days') continue;
            if($product->prepaid == 1) continue;
    
            if($product->is_stock)
            {
                $pro_stock = $this->product->getProlocStock($item->product_id,$exorder->location_id);
                $stock = $pro_stock->quantity + ($item->quantity * $product->quantity);
                $this->product->updateProLocStock($item->product_id,$exorder->location_id,$stock);
            }
            else
            {
                if($product->is_combo)
                {
                    $stock_item = $this->product->getProlocStock($product->stock_item,$exorder->location_id);
                    $combo_item = $this->product->getProlocStock($product->combo_item,$exorder->location_id);

                    $stock = $stock_item->quantity + $item->quantity;
                    $this->product->updateProLocStock($product->stock_item,$exorder->location_id,$stock);

                    $combostock = $combo_item->quantity + $item->quantity;
                    $this->product->updateProLocStock($product->combo_item,$exorder->location_id,$combostock);

                }
                elseif($product->is_stock == 0 && $product->stock_item)
                {
                    $stock_item = $this->product->getProlocStock($product->stock_item,$exorder->location_id);
                    $stock = $stock_item->quantity + ($item->quantity * $product->quantity);
                    $this->product->updateProLocStock($product->stock_item,$exorder->location_id,$stock);
                }
            }
        }
    }
    
    public function populateOrder($order_id)
    {        
        $order_items = $this->order->getOrderItem($order_id);
                        
        foreach($order_items as $item)
        {
            $product = $this->product->getProForCart($item->product_id);
            $fp = FALSE;
            $free_combine = 0;
            //check and add free product
            if($product->free_product)
            {
                $fps = $this->product->getFreeProsWithNames($product->id);
                
                foreach($fps as $fp)
                {
                    $free_combine .= $free_combine==0?$fp->free_pro_id:",".$fp->free_pro_id;
                }
            }

            $price = $item->price == 0? 0 : $product->price;
            $data = array(
                'id'      => $product->id,
                'qty'     => $item->quantity,
                'price'   => number_format($price,2,'.',''),
                'name'    => $product->name,
                'product' => $product,
                'combined_id'=> $free_combine
            );


            $this->cart->product_name_rules = '\d\D';
            $row_id = $this->cart->insert($data);
        }
        
        $this->session->set_userdata('edit_order_id',$order_id);
    }
    
    public function view($id)
    {
        $order = $this->order->getOrder($id);
        $this->data['order'] = $order;
        $orderItems = $this->order->getOrderItem($id);
        $this->data['order_items'] = $orderItems;
        $this->data['bc1'] = 'Order';
	$this->data['bc2'] = 'View';
        
        $this->data['patient'] = $this->patient->getPatient($order->patient_id);
        
        $this->session->set_userdata('view_patient_category',$order->patient_category);  
        $this->data['patient_category'] = ($this->session->userdata('view_patient_category'))? $this->session->userdata('view_patient_category'): 'regular';
        
        
        
        $order_items = array();
        
        foreach($orderItems as $item)
        {
            $product = $this->product->getProForCart($item->product_id);
        
            
            $data = array(
                'id'      => $product->id,
                'qty'     => $item->quantity,
                'price'   => number_format($item->price,2),
                'name'    => $product->name,
                'product' => $product,
                'subtotal'=> number_format($item->price,2) * $item->quantity
            );

            $order_items[$product->id] = $data;
        }
        
        $this->data['order_items'] = $order_items;
        
        $this->load->view('order/view',$this->data);
    }
    
    public function ticket($order_id)
    {
        $order  = $this->order->getOrder($order_id);
        $patient = $this->patient->getPatient($order->patient_id);
        $this->data['order'] = $order;
        $this->data['patient'] = $patient;
        $this->data['order_location'] = getLocation($order->location_id);
        
        //get first visit
        $first_visit = $this->patient->getPatientVisit($patient->id,1);
        $this->data['first_visit'] = $first_visit;
        
        //get last 6 visits
        $latest_visits = $this->patient->getLatestNoOfVisits($patient->id,6,date('Y-m-d',strtotime($order->created)));
        $latest_visits = array_reverse($latest_visits);
        
//        $this->data['this_visit'] = $this->patient->getVisitByOrderId($order_id);
        //order items
        $today_bought = array();
        $order_items = $this->order->getOrderItemsWithNames($order_id);
        $redeemedPP = $this->order->getRedeemedItems($patient->id,date('Y-m-d',strtotime($order->created)));
        foreach($order_items as $item)
        {
            if($item->prepaid) continue;            
            array_push($today_bought,$item);
        }
        foreach($redeemedPP as $item)
        {
            array_push($today_bought,$item);
        }
        $this->data['order_items'] = $today_bought;
        
        //Check order items for injections
        $injections = array();
        $no_of_days = 0;
        $visit_cat = $this->config->item('visit_cat');
        foreach($order_items as $item)
        {
            if($sl = $this->is_injecton($item))
            {
                $temp = array();
                $temp['name'] = $item->name;
                $temp['lot'] = $sl->lot_no;
                $temp['exp'] = $sl->exp_date;
                
                array_push($injections, $temp);
            }
        }
        $this->data['injections'] = getTodayInjections($order_id);
        
        $this->data['visits'] = $latest_visits;
        
        $this->data['thisvisit'] = $this->patient->getVisitByOrderId($order_id);
        
        //get prepaid reedimed days product today
        $pp_redim = $this->order->getPPDaysRedim($patient->id,date('Y-m-d',  strtotime($order->created)));
        if($pp_redim)
        {
            $days = $pp_redim->quantity;
            $next_visit = date('m/d/Y', strtotime($order->created. " + $days days"));
            $this->data['next_visit'] = $next_visit;
        }
        
        $html = $this->load->view('order/ticket',$this->data,true);
        
        create_mp_ticket($html);
    }
    

    
    public function print_label($visit_id)
    {
        $visit = $this->patient->getVisitById($visit_id);
        $patient = $this->patient->getPatient($visit->patient_id);
        $name = $patient->lname." ".$patient->fname;
        $addr = $patient->address.", ".$patient->city.", ".getStateAbbr($patient->state)." ".$patient->zip;
        
        $meds = array();
        if($visit->med3 > 0)
        {
            $temp = array();
            $temp['rx'] = $visit->prescription_no;
            $temp['date'] = date('m/d/Y',strtotime($visit->visit_date));
            $temp['name'] = $name;
            $temp['addr'] = $addr;
            $temp['qty'] = $visit->med_days * $visit->meds_per_day;
            $temp['msg'] = getMedsMsg($visit->med3,NULL,$visit->meds_per_day);
            array_push($meds, $temp);
        }
        else 
        {
            if($visit->med1 > 0)
            {
                $temp = array();
                $temp['rx'] = $visit->prescription_no;
                $temp['date'] = date('m/d/Y',strtotime($visit->visit_date));
                $temp['name'] = $name;
                $temp['addr'] = $addr;
                $temp['qty'] = $visit->med_days * $visit->meds_per_day;
                $temp['msg'] = getMedsMsg($visit->med1,NULL,$visit->meds_per_day);
                array_push($meds, $temp);
            }
            if($visit->med2 > 0)
            {
                $temp = array();
                $temp['rx'] = $visit->prescription_no;
                $temp['date'] = date('m/d/Y',strtotime($visit->visit_date));
                $temp['name'] = $name;
                $temp['addr'] = $addr;
                $temp['qty'] = $visit->med_days * $visit->meds_per_day;
                $temp['msg'] = getMedsMsg(NULL,$visit->med2,$visit->meds_per_day);
                array_push($meds, $temp);
            }
        }
        
        $this->data['meds'] = $meds;
        
        
        $html = $this->load->view('order/label',$this->data,TRUE);
        create_mp_label($html);
    }
    
    private function removeOrder($order_id)
    {
        $exorder = $this->order->getOrder($order_id);
        
        $proceed = true;
        $result = array();
        
        $orderPP = $this->order->getOrderAddedPP($order_id,$exorder->patient_id);
        if($orderPP)
        {
            foreach($orderPP as $pp)
            {
                $todaySUB = $this->order->getTodayExPPBrkdwnSub($pp->pp_id);
                if($todaySUB)
                {
                    if(($pp->remaining - $pp->quantity)< $todaySUB->quantity) 
                    {
                        $proceed = FALSE;
                        $result['status'] = FALSE;
                        $result['msg'] = "As prepaid items added through order has redeemed, order cannot remove.";
                        break;
                    }
                }
            }
        }
        
        if($proceed)
        {
            $this->restore_stock($order_id);
//            $this->order->removeOrderItems($order_id);
            removePrepaid($order_id,$exorder->patient_id);

            //Mark order as removed
            $this->order->updateOrder($order_id,array('status'=>2));
            
//            $this->order->removeOrder($order_id);
            $result['status'] = TRUE;
        }
        return $result;
    }
    
    public function viewRemoveOrder($order_id,$patient_id)
    {
        $status = $this->removeOrder($order_id);
        
        
        
        if($status['status'])
        {
            $this->session->set_flashdata('message','Order Removed successfully.');
        }
        else 
        {
            $this->session->set_flashdata('error',$status['msg']);
        }
        
        
        redirect("patient/view/$patient_id");
    }
    
    public function pendingRemoveOrder($order_id)
    {
        $status = $this->removeOrder($order_id);
        if($status['status'])
        {
            $this->session->set_flashdata('message','Order Removed successfully.');
        }
        else 
        {
            $this->session->set_flashdata('error',$status['msg']);
        }
        
        
        redirect("order/pending");
    }
    
    public function removed()
    {
        $this->data['bc1'] = 'Order';
	$this->data['bc2'] = 'Removed';
        
        $removes  = $this->order->getRemovedOrders();
        $this->data['orders'] = $removes;
        
        $this->load->view('order/removed',$this->data);
    }
    
    public function visitpage($order_id)
    {        
        
        $order  = $this->order->getOrder($order_id);
        
        $patient = $this->patient->getPatient($order->patient_id);
        $this->data['patient'] = $patient;
        $this->data['order'] = $order;
        $this->data['order_location'] = getLocation($order->location_id);
        
        $staff = $order->staff_id ? $this->user->get($order->staff_id):NULL;
        $this->data['staff'] = $staff? $staff->lname." ".$staff->fname:"-";
        
        //get first visit
        $first_visit = $this->patient->getPatientVisit($patient->id,1);
        $this->data['first_visit'] = $first_visit;
        
        $lastRestart = $this->order->getLastRestart($order->patient_id);
        if($lastRestart && $lastRestart->date > date('Y-m-d',strtotime($order->created))) $lastRestart = NULL;
        
        //get last 6 visits
        $latest_visits = $this->patient->getVisitsForVisitPage($patient->id,date('Y-m-d',strtotime($order->created)),$lastRestart);
        $latest_visits = array_reverse($latest_visits);
        
        if($lastRestart) 
        {
            $count = $this->patient->getVisitCountSinceLastRestart($order->patient_id,date('Y-m-d',strtotime($order->created)),$lastRestart);            
            $lastRestart->count = $count > 6 ? $count - 5 : 1;
        }
        $this->data['lastRestart'] = $lastRestart;
        
        $this->data['visits'] = $latest_visits;
        
        //Check order items for injections
       
        $this->data['injections'] = getTodayInjections($order_id);
        
        $this->data['avbl_prior'] = avblPPbeforeOrder($order_id);
        $this->data['redeem_exis'] = redeemedExisToday($order_id);
        
        $this->data['got_today'] = boughtToday($order_id);
        $this->data['redeem_new'] = redeemedNewToday($order_id);
        $this->data['pp_remaining'] = avblPPRemaining($order->patient_id, $order_id);
        $this->data['dralerts'] = $this->patient->getAlerts($order->patient_id,1); 
        
        $html = $this->load->view('order/visitpage',$this->data,true);
        
        create_mp_ticket($html);
    }
    
    public function productsOnly($order_id)
    {
        $this->order->updateOrder($order_id,array('status'=>3));
        $this->session->set_flashdata('message','Order Marked as Shots ONLY.');
        redirect('order/pending');
    }
    
    public function finalPage($order_id)
    {
         $order  = $this->order->getOrder($order_id);
        $patient = $this->patient->getPatient($order->patient_id);
        $this->data['patient'] = $patient;
        $this->data['order'] = $order; 
        $staff = $order->staff_id ? $this->user->get($order->staff_id):NULL;
        $this->data['staff'] = $staff? $staff->lname." ".$staff->fname:"-";
        $this->data['order_location'] = getLocation($order->location_id);
        
        //get first visit
        $first_visit = $this->patient->getPatientVisit($patient->id,1);
        $this->data['first_visit'] = $first_visit;
        
        $lastRestart = $this->order->getLastRestart($order->patient_id);
        if($lastRestart && $lastRestart->date > date('Y-m-d',strtotime($order->created))) $lastRestart = NULL;
        
        //get last 6 visits
        $latest_visits = $this->patient->getVisitsForVisitPage($order->patient_id,date('Y-m-d',strtotime($order->created)),$lastRestart);
        $latest_visits = array_reverse($latest_visits);
        
        if($lastRestart) 
        {
            $count = $this->patient->getVisitCountSinceLastRestart($order->patient_id,date('Y-m-d',strtotime($order->created)),$lastRestart);
            $lastRestart->count = $count > 6 ? $count - 5 : 1;
        }
        $this->data['lastRestart'] = $lastRestart;
        
        $order_items = $this->order->getOrderItemsWithNames($order_id);
        
        $this->data['visits'] = $latest_visits;
        
        $this_visit = $this->patient->getVisitByOrderId($order_id);
        $this->data['thisvisit'] = $this_visit;
        
        
        $this->data['injections'] = getTodayInjections($order_id);
       
        $res = getNextVisitDate($order->patient_id);
        $next_visit = "";
        if($res['status'] == 'success')
        {
            $next_visit = ($res['ed'] < 7)? "Next Visit Date ".date('m/d/Y',strtotime($res['rnv'])): "Based on last 4 visits, your next visit should be on: ".date('m/d/Y',strtotime($res['nvd']));
        }
        else
        {
            $next_visit =  $res['msg'];
        }
            $this->data['next_visit'] = $next_visit;
            
        $html = $this->load->view('order/finalpage',$this->data,true);
        
        create_mp_ticket($html);
    }
    
    public function pendingOrder($order_id)
    {
        $this->order->updateOrder($order_id,array('status'=>4));
        $this->session->set_flashdata('message','Order Marked as PENDING.');
        redirect('order/pending');
    }
    
    public function oldvp($order_id)
    {
        $order  = $this->order->getOrder($order_id);
        
        $html = gzuncompress($order->vp);
        
        create_mp_ticket($html);
    }
    
    public function receipt($order_id)
    {
        $order = $this->order->getOrder($order_id);
        $this->data['order'] = $order;
        $patient = $this->patient->getPatient($order->patient_id);
        $this->data['patient'] = $patient;
        $ois = $this->order->getOrderItemsWithNames($order_id);
        $this->data['ois'] = $ois;

        $this->data['redeem_exis'] = redeemedExisToday($order_id);

        $html = $this->load->view('order/receipt_pdf',$this->data,TRUE);
        
        create_mp_ticket($html);
    }
    
    public function applyCoup()
    {
        $result = array();
        $post = $this->input->post();
        $pid = $post['pid'];
        $coupon = $post['coupon'];
        $proCou = $this->marketing->getCoupon($coupon);
        $genCou = $this->promos->getGenCoupon($coupon);
        
        if(!$proCou && !$genCou)
        {
            $result['status'] = 'error';
            $result['error'] = 'Invalid Promotion Code.';
        }
        elseif($genCou)
        {
            $genCou->patient_id = $pid;
            $this->data['patient_id'] = $pid;
            $result = applyCoupon($genCou);
            if(isset($result['status']) && $result['status']=='success')
            {
                $result['html'] = $this->load->view("order/_cart",$this->data,TRUE);
            }
            $this->session->set_userdata("coupon_".$pid,$coupon);
        }
        elseif($proCou)
        {
            if($proCou->status == 1)
            {
                $result['status'] = 'error';
                $result['error'] = 'Promo code has already used.'; 
            }
            elseif($proCou->patient_id != $pid)
            {
                $result['status'] = 'error';
                $result['error'] = 'This coupon code not issued for this patient.'; 
            }
            else 
            {            
                $this->data['patient_id'] = $proCou->patient_id;
                $result = applyCoupon($proCou);
                if(isset($result['status']) && $result['status']=='success')
                {
                    $result['html'] = $this->load->view("order/_cart",$this->data,TRUE);
                }
                $this->session->set_userdata("coupon_".$pid,$coupon);
            }
        }
        echo json_encode($result);
    }
    
    public function removeCoupon()
    {
        $result = array();
        $post = $this->input->post();
        $pid = $post['pid'];
        $this->session->unset_userdata("coupon_".$pid);
        $this->session->unset_userdata("cd_".$pid);
        $this->data['patient_id'] = $pid;
        
        $result['status'] = 'success';
        $result['html'] = $this->load->view("order/_cart",$this->data,TRUE);
        
        echo json_encode($result);
    }
    
    public function updateGenStatus()
    {
        $post = $this->input->post();
        $column = $post['column'];
        $order_id = $post['oid'];
        
        $this->order->updateOrder($order_id,array($column=>1));
        echo 'success';
    }
    
    public function cash_receipt($order_id)
    {
        $order = $this->order->getOrder($order_id);
        $this->data['order'] = $order;
        $patient = $this->patient->getPatient($order->patient_id);
        $this->data['patient'] = $patient;
        $ois = $this->order->getOrderItemsWithNames($order_id);
        $this->data['ois'] = $ois;
        
        $html = $this->load->view("order/_cash_receipt",$this->data,TRUE);
        create_mp_ticket($html);
    }
    
    public function updateVp($order_id)
    {
        $this->data['bc1'] = 'Order';
	$this->data['bc2'] = 'Update Visit page';
        
        $order  = $this->order->getOrder($order_id);
        
        $this->data['html'] = gzuncompress($order->vp);
        
        if($this->input->server('REQUEST_METHOD') === 'POST')
    	{
            $new_html = $this->input->post('html');
            $this->order->updateOrder($order_id,array('vp'=>gzcompress($new_html)));
            die('UPDATED');
        }
        
        $this->load->view('order/update_vp',$this->data);
    }
}

