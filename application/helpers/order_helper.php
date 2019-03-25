<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function addPrepaidItem($data)
{
    $CI =& get_instance();
    $CI->load->model("Order_model", "order");
    
    $result = array();
    
    $prepaid_item = $CI->order->getPrepaidItem($data['patient_id'],$data['pro_id']);
    if($prepaid_item)
    {
        $update = array();
        if($data['type'] == 'add')
        {
            $update['remaining'] = $prepaid_item->remaining + $data['quantity'];
            $update['updated'] = date('Y-m-d H:i:s');
            $CI->order->updatePrepaidItem($prepaid_item->id,$update);
            
            $data['prepaid_id'] = $prepaid_item->id;
            unset($data['patient_id'],$data['pro_id']);
            $CI->order->addPPBrkdwn($data);
            
            $result['status'] = 'success';
        }
        elseif($data['type'] == 'subtract')
        {
            if($prepaid_item->remaining >= $data['quantity'])
            {
//                $ex_pp_brkdown = $CI->order->getTodayExPPBrkdwnSub($prepaid_item->id);
//                $new_qnty = ($ex_pp_brkdown) ? abs($ex_pp_brkdown->quantity - $data['quantity']): $data['quantity'];
                $ex_pp_brkdown = false;
                
                $update['remaining'] = $prepaid_item->remaining - $data['quantity'];
                $update['updated'] = date('Y-m-d H:i:s');
                $CI->order->updatePrepaidItem($prepaid_item->id,$update);
                
                
                if($ex_pp_brkdown)
                {
                    $update_brkdwn = array();
                    $update_brkdwn['quantity'] = $data['quantity'];
                    $CI->order->updatePPBrkdwn($ex_pp_brkdown->id,$update_brkdwn);
                }
                else
                {
                    $data['prepaid_id'] = $prepaid_item->id;
                    unset($data['patient_id'],$data['pro_id']);
                    $CI->order->addPPBrkdwn($data);
                }
                
                $result['msg'] = "PP Item: $prepaid_item->name,    &nbsp;&nbsp;&nbsp;&nbsp;  Qty: ".$data['quantity']." &nbsp;&nbsp;&nbsp;&nbsp;   Redeemed Successfully.";
                $result['status'] = 'success';
            }
            else
            {
                $result['status'] = 'error';
                $result['error'] = 'No sufficiant remaining.';
            }            
        }        
    }
    else
    {
         $prepaid_item = array();
         $prepaid_item['patient_id'] = $data['patient_id'];
         $prepaid_item['pro_id'] = $data['pro_id'];
         if($data['type'] == 'add')
         {
            $prepaid_item['remaining'] = $data['quantity'];
            $prepaid_item['updated'] = date('Y-m-d H:i:s');
            
            $prepaid_id = $CI->order->addPrepaidItem($prepaid_item);
            
            $data['prepaid_id'] = $prepaid_id;
            unset($data['patient_id'],$data['pro_id']);
            $CI->order->addPPBrkdwn($data);
            
            $result['status'] = 'success';
         }
         else
         {
            $result['status'] = 'error';
            $result['error'] = 'No prepaid.';
         }
    } 
    
    return $result;
    
}

function getCartDicount()
{
    $CI =& get_instance();
    $patient_category = $CI->session->userdata('patient_category');
    $total = $CI->cart->total();
    $discount = 0;
    if($patient_category == 'staff')
    {
        $discount = $total * 0.2;
    }
    elseif($patient_category == 'family')
    {
        $discount = $total;
    }

    return $discount;
}

function getViewCartDicount()
{
    $CI =& get_instance();
    $patient_category = $CI->session->userdata('patient_category');
    $total = $CI->view_cart->total();
    $discount = 0;
    if($patient_category == 'staff')
    {
        $discount = $total * 0.2;
    }
    elseif($patient_category == 'family')
    {
        $discount = $total;
    }

    return $discount;
}

function removePrepaid($order_id,$patient_id)
{
    $CI =& get_instance();
    $CI->load->model("Order_model", "order");
    
    $pps = $CI->order->getOrderAddedPP($order_id,$patient_id);
   
    foreach($pps as $pp)
    {
        $remaining = $pp->remaining - $pp->quantity;
        $CI->order->updatePrepaidItem($pp->pp_id,array('remaining'=>$remaining));
        $CI->order->removePPBrkdwn($pp->ppb_id);
    }
}

function getPrePaids($patient_id)
{
    $CI =& get_instance();
    $CI->load->model("Order_model", "order");
    
    return $CI->order->getPatientPP($patient_id);
}

function checkStock($pro_id,$qnt)
{
    $CI =& get_instance();
    $CI->load->model("Product_model", "product");
    $product = $CI->product->get($pro_id);
    $location = $CI->session->userdata('location');
    
    if($product->measure_in == 'Days') return TRUE;
    
    if($product->is_stock)
    {
        $pro_stock = $CI->product->getProlocStock($pro_id,$location->id);
        return ($pro_stock && $pro_stock->quantity >= $qnt)? TRUE:FALSE;
    }
    elseif($product->is_combo)
    {
        $stock_item = $CI->product->getProlocStock($product->stock_item,$location->id);
        $combo_item = $CI->product->getProlocStock($product->combo_item,$location->id);
        
        return ($stock_item && $stock_item->quantity >= $qnt && $combo_item && $combo_item->quantity >= $qnt)? TRUE:FALSE;
    }
    elseif($product->stock_item)
    {
        $stock_item = $CI->product->getProlocStock($product->stock_item,$location->id);
        return ($stock_item && $stock_item->quantity >= $qnt) ? TRUE:FALSE;
    }
    
    return FALSE;
}

function reduceStock($pro_id,$qty)
{
    $CI =& get_instance();
    $CI->load->model("Product_model", "product");
    $product = $CI->product->get($pro_id);
    $location = $CI->session->userdata('location');
    
    if($product->measure_in == 'Days') return TRUE;
    
    if($product->is_stock)
    {
        $pro_stock = $CI->product->getProlocStock($pro_id,$location->id);
        if($pro_stock)
        {
            $stock = $pro_stock->quantity - ($qty * $product->quantity);
            $CI->product->updateProLocStock($pro_id,$location->id,$stock);
        }
        else 
        {
            $stock = 0-($qty * $product->quantity);
            $proLocStock = array('product_id'=>$pro_id,'location_id'=>$location->id,'quantity'=> $stock);
            $CI->product->addProLocStock($proLocStock);
        }
        
    }
    elseif($product->is_combo)
    {
        $stock_item = $CI->product->getProlocStock($product->stock_item,$location->id);
        $combo_item = $CI->product->getProlocStock($product->combo_item,$location->id);
        
        $stock = $stock_item->quantity - $qty;
        $CI->product->updateProLocStock($product->stock_item,$location->id,$stock);
        
        $combostock = $combo_item->quantity - $qty;
        $CI->product->updateProLocStock($product->combo_item,$location->id,$combostock);
        
    }
    elseif($product->stock_item)
    {
        $stock_item = $CI->product->getProlocStock($product->stock_item,$location->id);
        $stock = $stock_item->quantity - ($qty * $product->quantity);
        $CI->product->updateProLocStock($product->stock_item,$location->id,$stock);
    }
}

function restoreStock($pro_id,$qty)
{
    $CI =& get_instance();
    $CI->load->model("Product_model", "product");
    $product = $CI->product->get($pro_id);
    $location = $CI->session->userdata('location');
    
    if($product->measure_in == 'Days') return TRUE;
    
    if($product->is_stock)
    {
        $pro_stock = $CI->product->getProlocStock($pro_id,$location->id);
        if($pro_stock)
        {
            $stock = $pro_stock->quantity + ($qty * $product->quantity);
            $CI->product->updateProLocStock($pro_id,$location->id,$stock);
        }
        
    }
}

function reducePPStock($pro_id,$qty)
{
    $CI =& get_instance();
    $CI->load->model("Product_model", "product");
    $product = $CI->product->get($pro_id);
    $location = $CI->session->userdata('location');
    
    if($product->measure_in == 'Days') return TRUE;
    
    if($product->is_stock)
    {
        $pro_stock = $CI->product->getProlocStock($pro_id,$location->id);
        $stock = $pro_stock->quantity - $qty;
        $CI->product->updateProLocStock($pro_id,$location->id,$stock);
    }
    elseif($product->is_combo)
    {
        $stock_item = $CI->product->getProlocStock($product->stock_item,$location->id);
        $combo_item = $CI->product->getProlocStock($product->combo_item,$location->id);
        
        $stock = $stock_item->quantity - $qty;
        $CI->product->updateProLocStock($product->stock_item,$location->id,$stock);
        
        $combostock = $combo_item->quantity - $qty;
        $CI->product->updateProLocStock($product->combo_item,$location->id,$combostock);
        
    }
    elseif($product->stock_item)
    {
        $stock_item = $CI->product->getProlocStock($product->stock_item,$location->id);
        $stock = $stock_item->quantity - ($qty * $product->quantity);
        $CI->product->updateProLocStock($product->stock_item,$location->id,$stock);
    }
}

function getMedAmount($visit,$med)
{
    $CI =& get_instance();
    $medsById = $CI->config->item('meds_for_id');
    
    if(!$visit) return '';
    if($visit->is_med == 0) return '';
    
    $med_count = '';
    
    if($visit->med1 && $medsById[$visit->med1] == $med) $med_count = $visit->med_days;
    if($visit->med2 && $medsById[$visit->med2] == $med) $med_count = $visit->med_days;
    if($visit->med3 && $medsById[$visit->med3] == $med) $med_count = $visit->med_days;
    
    return $med_count;
}

function getMedsMsg($m,$e,$mpd)
{
    $CI =& get_instance();
    $medsById = $CI->config->item('meds_for_id');
    
    $morning = $m? $medsById[$m]:NULL;
    $evening = $e? $medsById[$e]:NULL;
    
    if($morning && $morning == 15 && $mpd ==1)
    {
        return "TAKE ONE CAP (15 MG) A DAY AROUND 9-10 AM -- ABOUT 10-15 MINS AFTER CONSUMING FOOD";
    }
    
    if($morning && $morning == 15 && $mpd == 0.5)
    {
        return "ADAPTING PHASE: TAKE ONE CAP (15 MG) EVERY OTHER DAY AROUND 9-10AM -- ABOUT 10-15 MINS AFTER CONSUMING FOOD";
    }
    
    if($morning && $morning == 30 && $mpd ==1)
    {
        return "TAKE ONE CAP (30 MG) A DAY AROUND 9-10 AM -- ABOUT 10-15 MINS AFTER CONSUMING FOOD";
    }
    
    if($morning && $morning == 37 && $mpd ==1)
    {
        return "TAKE ONE TAB (37.5 MG) A DAY AROUND 9-10 AM -- ABOUT 10-15 MINS AFTER CONSUMING FOOD";
    }
    
    if($morning && $morning == 'DI' && $mpd ==1)
    {
        return "TAKE ONE TAB (25 MG) A DAY AROUND 9-10 AM -- ABOUT 10-15 MINS AFTER CONSUMING FOOD";
    }
    
    if($morning && $morning == 'DI' && $mpd ==2)
    {
        return "TAKE ONE TAB (25 mg) TWICE A DAY [(9-10 AM) & (1-2PM)]. ABOUT 10-15 MINS AFTER CONSUMING FOOD";
    }
    
    if($evening && $evening == '15' && $mpd ==1)
    {
        return "TAKE ONE CAP (15 MG) A DAY BETWEEN 1 AND 2 PM. (NOT LATER THAN 3 PM).";
    }
    
    return "";
}

function avblPPbeforeOrder($order_id)
{
    $CI =& get_instance();
    $CI->load->model("Order_model", "order");
    
    $order = $CI->order->getOrder($order_id);
    $allPP = $CI->order->getPatientAllPP($order->patient_id);
    $final = $orderAddedPP = $redeemPP = array();
    $orderPP = $CI->order->getOrderAddedPP($order_id,$order->patient_id);
    foreach($orderPP as $opp)
    {
        $orderAddedPP[$opp->pp_id] = $opp->quantity;
    }
    
    $redeemedPP = $CI->order->getRedeemedItems($order->patient_id,date('Y-m-d',strtotime($order->created)));
    foreach($redeemedPP as $rpp)
    {
        $redeemPP[$rpp->id] = isset($redeemPP[$rpp->id])? $redeemPP[$rpp->id] + $rpp->quantity : $rpp->quantity;
    }
    
        
    foreach($allPP as $pp)
    {
        $remaining = $pp->remaining;
        $added = $subtracted = 0;
        
        $init_rem = $remaining > 0 ? TRUE:FALSE;
        if(isset($orderAddedPP[$pp->id])) 
        {
            $added = $orderAddedPP[$pp->id];
            $remaining = $remaining - $added;
        }
        
        if(isset($redeemPP[$pp->id]))
        {
            $subtracted = $redeemPP[$pp->id];
            $remaining = $remaining + $subtracted;          
        }
        
        if($remaining > 0 || ($added < $subtracted))
        {
            $temp = array();
            $temp['name'] = $pp->name;
            $temp['available'] = $remaining;
            $temp['measure_in'] = $pp->measure_in;
            $orderAdded = $CI->order->getPPLatestAddedOrderPrior($pp->id,date('Y-m-d',strtotime($order->created)));
            if($orderAdded)
            {
                $temp['order'] = str_pad($orderAdded->order_id, 5, '0', STR_PAD_LEFT);
                $temp['date'] = date('m/d/Y',strtotime($orderAdded->created));
            }
            else
            {
                $temp['order'] = 'EXISTING';
                $temp['date'] = '';
            }
            
            array_push($final,$temp);
        }
           
    }
    
    return $final;
}

function redeemedExisToday($order_id)
{
     $CI =& get_instance();
    $CI->load->model("Order_model", "order");
    
    $order = $CI->order->getOrder($order_id);
    $exredeem = array();
    
    $redeemedPP = $CI->order->getRedeemedItems($order->patient_id,date('Y-m-d',strtotime($order->created)));
    
    foreach($redeemedPP as $item)
    {
        $orderAdded = $CI->order->getPPLatestAddedOrder($item->id);
        if($orderAdded && $orderAdded->order_id == $order_id) continue;
        
        if(isset($exredeem[$item->id]))
        {
            $tem = $exredeem[$item->id];
            $tem['available']  += $item->quantity;
            $exredeem[$item->id] = $tem;
        }
        else
        {
            $temp = array();
            $temp['name'] = $item->name;
            $temp['fname'] = $item->friendly_name;           
            $temp['available'] = $item->quantity;
            $temp['measure_in'] = $item->measure_in;

            if($orderAdded)
            {
                $temp['order'] = str_pad($orderAdded->order_id, 5, '0', STR_PAD_LEFT);
                $temp['date'] = date('m/d/Y',strtotime($orderAdded->created));
            }
            else
            {
                $temp['order'] = 'EXISTING';
                $temp['date'] = '-';
            }        
            $exredeem[$item->id] = $temp;
        }
    }
    
    return $exredeem;
}

function redeemedNewToday($order_id)
{
     $CI =& get_instance();
    $CI->load->model("Order_model", "order");
    
    $order = $CI->order->getOrder($order_id);
    $exredeem = array();
    
    $redeemedPP = $CI->order->getRedeemedItems($order->patient_id,date('Y-m-d',strtotime($order->created)));
    
    foreach($redeemedPP as $item)
    {
        $temp = array();
        $temp['name'] = $item->name;
        $temp['available'] = $item->quantity;
        $temp['measure_in'] = $item->measure_in;
        
        $orderAdded = $CI->order->getPPLatestAddedOrder($item->id);
        
        
        if($orderAdded && $orderAdded->order_id != $order_id) continue;
        
        if($orderAdded)
        {
            $temp['order'] = str_pad($orderAdded->order_id, 5, '0', STR_PAD_LEFT);
            $temp['date'] = date('m/d/Y',strtotime($orderAdded->created));
            
            array_push($exredeem,$temp);
        }
    }
    
    return $exredeem;
}

function boughtToday($order_id)
{
    $CI =& get_instance();
    $CI->load->model("Order_model", "order");
    
    $order = $CI->order->getOrder($order_id);
    
    $today_bought = array();
    $order_items = $CI->order->getOrderItemsWithNames($order_id);
    
    foreach($order_items as $item)
    {    
        $temp = array();
        $temp['name'] = $item->name;
        $temp['available'] = $item->quantity;
        $temp['order'] = str_pad($order_id, 5, '0', STR_PAD_LEFT);
        $temp['date'] = date('m/d/Y',strtotime($order->created));
        $temp['price'] = number_format($item->price,2);
        $temp['subtotal'] = number_format($item->price * $item->quantity,2);
        
        array_push($today_bought,$temp);
    }

    
    return $today_bought;
}

function avblPPRemaining($patient_id,$order_id)
{
    $CI =& get_instance();
    $CI->load->model("Order_model", "order");
    
    $pps = $CI->order->getPatientPP($patient_id);
    
    $items = array();
    foreach($pps as $pp)
    {
        $temp = array();
        $temp['name'] = $pp->name;
        $temp['available'] = $pp->remaining;
        $temp['measure_in'] = $pp->measure_in;
        
        $orderAdded = $CI->order->getPPLatestAddedOrder($pp->id);
        if($orderAdded)
        {
            $temp['order'] = str_pad($orderAdded->order_id, 5, '0', STR_PAD_LEFT);
            $temp['date'] = date('m/d/Y',strtotime($orderAdded->created));
            $temp['order_text'] = ($orderAdded->order_id != $order_id)? "LAST Order":"TODAY'S order";
        }
        else
        {
            $temp['order'] = 'EXISTING';
            $temp['date'] = '-';
            $temp['order_text'] = "LAST order";
        }        
        array_push($items,$temp);
    }
    
    return $items;
}

function getTodayInjections($order_id)
{
    $CI =& get_instance();
    $CI->load->model("Order_model", "order");
    
    $order = $CI->order->getOrder($order_id);
    
    $injections = array();
    $injNames = array();
    $injNames[5] = "B-12 Cyno 0.4cc";
    $injNames[18] = "LipoGen: Inj USP - SC / IM";
    $injNames[41] = "UltraBurn: Inj - IM";

    $order_items = $CI->order->getOrderItemsWithNames($order_id);
    foreach($order_items as $item)
    {
        if($item->prepaid == 1) continue;
        
        if($sls = is_injecton($item))
        {
            foreach($sls as $key => $sl)
            {   
                $temp = array();
                $temp['name'] = isset($injNames[$sl->stock])?$injNames[$sl->stock]:$item->name;
                $temp['lot'] = $sl->lot_no;
                $temp['exp'] = $sl->exp_date;
                $temp['stock'] = $sl->stock;
                $temp['qty'] = $item->quantity;

                if(isset($injections[$sl->stock]))
                {
                    $injections[$sl->stock]['qty'] += $item->quantity;
                }
                else
                {
                    $injections[$sl->stock] = $temp;
                }
            }
                
        }
    }
    
    $redeemedPP = $CI->order->getRedeemedItems($order->patient_id,date('Y-m-d',strtotime($order->created)));
        
    foreach($redeemedPP as $item)
    {
        if($sls = is_injecton($item))
        {
            foreach($sls as $key => $sl)
            {  
                $temp = array();
                $temp['name'] = isset($injNames[$sl->stock])?$injNames[$sl->stock]:$item->name;
                $temp['lot'] = $sl->lot_no;
                $temp['exp'] = $sl->exp_date;
                $temp['stock'] = $sl->stock;
                $temp['qty'] = $item->quantity;

                if(isset($injections[$sl->stock]))
                {
                    $injections[$sl->stock]['qty'] += $item->quantity;
                }
                else
                {
                    $injections[$sl->stock] = $temp;
                }
            }
        }
    }
    
    return $injections;
}

function is_injecton($item)
{
    $CI =& get_instance();
    $CI->load->model("Product_model", "product");
    
    $injs = array();
    
    $si_cat = $CI->config->item('injection_cats');
    if($item->is_stock)
    {
        if(in_array($item->cat_id,$si_cat))
        {
            $last_stock = $CI->product->getProLastStockLot($item->product_id);
            if($last_stock) 
            {
                $last_stock->stock = $item->product_id;
                $last_stock->stock_name = $item->name;
                $injs[$item->product_id] = $last_stock;
                return $injs;
            }
        }
    }
    elseif($item->stock_item)
    {
        $stockItem = $CI->product->get($item->stock_item);

        if($stockItem && in_array($stockItem->cat_id,$si_cat))
        {
            $last_stock = $CI->product->getProLastStockLot($stockItem->id);
            if($last_stock) 
            {
                $last_stock->stock = $item->stock_item;
                $last_stock->stock_name = $stockItem->name;
                $injs[$item->stock_item] = $last_stock;
            }
            
            if($item->is_combo)
            {
                $comboItem = $CI->product->get($item->combo_item);
                if($comboItem && in_array($comboItem->cat_id,$si_cat))
                {
                    $last_combo_stock = $CI->product->getProLastStockLot($comboItem->id);
                    if($last_combo_stock) 
                    {
                        $last_combo_stock->stock = $item->combo_item;
                        $last_combo_stock->stock_name = $comboItem->name;                        
                        $injs[$item->combo_item] = $last_combo_stock;
                    }
                }
            }
            
            return $injs;
        }
    }

    return false;
}

function getMedUsage($loc_id,$day)
{
    $CI =& get_instance();
    $CI->load->model("Patient_model", "patient");
    $visits = $CI->patient->getVisitsOnLocOnDay($loc_id,$day);
    
    $impro = $CI->config->item('meds_pro');
    
    $meds = array();
    $meds['med1'] = 0;
    $meds['med2'] = 0;
    $meds['med3'] = 0;
    $meds['med4'] = 0;
    
    foreach($visits as $visit)
    {
        if($visit->med1)
        {
            $meds[$impro[$visit->med1]] += ($visit->med_days * $visit->meds_per_day);
        }
        
        if($visit->med2)
        {
            $meds[$impro[$visit->med2]] += ($visit->med_days * $visit->meds_per_day);
        }
        
        if($visit->med3)
        {
            $meds[$impro[$visit->med3]] += ($visit->med_days * $visit->meds_per_day);
        }
    }
    
    return $meds;
}

function getInjUsage($loc_id,$day)
{
    $CI =& get_instance();
    $CI->load->model("Order_model", "order");
    
    $ois = $CI->order->getOrderItemsOnLocOnDay($loc_id,$day);
    $injs = array();
    $injs['inj1'] = 0;
    $injs['inj2'] = 0;
    $injs['inj3'] = 0;
    $injs['inj4'] = 0;
    $impro = $CI->config->item('inj_pro');
    $si_cat = $CI->config->item('injection_cats');
    
    foreach($ois as $oi)
    {
        if($oi->prepaid == 1) continue;
        
        if($oi->is_stock)
        {
            if(in_array($oi->cat_id,$si_cat))
            {
                $injs[$impro[$oi->product_id]] += $oi->quantity;
            }
        }
        elseif($oi->stock_item)
        {
            $stockItem = $CI->product->get($oi->stock_item);

            if($stockItem && in_array($stockItem->cat_id,$si_cat))
            {
                $injs[$impro[$stockItem->id]] += $oi->quantity;
                
                 if($oi->is_combo)
                {
                    $comboItem = $CI->product->get($oi->combo_item);
                    if($comboItem && in_array($comboItem->cat_id,$si_cat))
                    {
                        $injs[$impro[$comboItem->id]] += $oi->quantity;
                    }
                }
            }
        }
    }
    
    $ppis = $CI->order->getPPRedeemOnLocOnDay($loc_id,$day);
    
    foreach($ppis as $oi)
    {
        if($oi->is_stock)
        {
            if(in_array($oi->cat_id,$si_cat))
            {
                $injs[$impro[$oi->product_id]] += $oi->quantity;
            }
        }
        elseif($oi->stock_item)
        {
            $stockItem = $CI->product->get($oi->stock_item);

            if($stockItem && in_array($stockItem->cat_id,$si_cat))
            {
                $injs[$impro[$stockItem->id]] += $oi->quantity;
                
                 if($oi->is_combo)
                {
                    $comboItem = $CI->product->get($oi->combo_item);
                    if($comboItem && in_array($comboItem->cat_id,$si_cat))
                    {
                        $injs[$impro[$comboItem->id]] += $oi->quantity;
                    }
                }
            }
        }
    }
    
    return $injs;
}

function getNextPresNo()
{
    $CI =& get_instance();
    $CI->load->model("Patient_model", "patient");
    $prescription = $CI->patient->getLastPresNo();
    return $prescription->prescription_no + 1;
}

function getNextVisitDate($patient_id,$nov = 4)
{
    $CI =& get_instance();
    $CI->load->model("Order_model", "order");
    $CI->load->model("Patient_model", "patient");
    $CI->load->model('Evaluate_model','evaluate');
    $lvs = $CI->patient->getLatestNoOfVisits($patient_id,$nov,date('Y-m-d'));
    $days = 0;$result = array();
    
    if($lvs)
    {
        $lv = $lvs[0];
        $lvmd = $lv->is_med==1?$lv->med_days:$lv->no_med_days;
        $regular_nv = date('Y-m-d', strtotime($lv->visit_date." + $lvmd days"));
        
        $vc = count($lvs);
        $lvs = array_reverse($lvs);
        
        $lvd = NULL;$lmd = 0;$ed = 0;
        
        for($i=0;$i<$vc;$i++)
        {
            $visit = $lvs[$i];
            if($lvd && $lmd > 0)
            {
                $tvd = date('Y-m-d', strtotime($visit->visit_date));
                $tmd = $visit->is_med==1?$visit->med_days:$visit->no_med_days;
                $tvdo = new DateTime($tvd);
                $lvd0 = new DateTime($lvd);
                $gap = $lvd0->diff($tvdo)->format("%a");
                
                if($gap < $lmd) $ed += $lmd - $gap;
                if($gap > $lmd) $ed -= $gap - $lmd;
                
                $lvd = $tvd;
                $lmd = $tmd;
            }
            else 
            {
                $lvd = date('Y-m-d', strtotime($visit->visit_date));
                $lmd = $visit->is_med==1?$visit->med_days:$visit->no_med_days;
            }
        }
        
                
        if($ed > 0) $lmd = $lmd + $ed;
        
        $result['status'] = 'success';
        $result['nvd'] = date('Y-m-d', strtotime($lvd." + $lmd days"));
        $result['vc'] = $vc;
        $result['ed'] = $ed > 0? $ed:0;
        $result['rnv'] = $regular_nv;
    }
    else 
    {
        $result['status'] = 'error';
        $result['msg'] = "No previous visits.";
    }
    return $result;
    }
    

function getPresNo($this_visit,$last_visit,$post)
{
    $CI =& get_instance();
    $CI->load->model("Patient_model", "patient");
    $CI->load->model("Order_model", "order");
    $sixMonthAgo = date('Y-m-d',strtotime("-6 months"));
    
    $result = array();
    $result['prescription_no'] = NULL;
    $result['refill'] = NULL;
    $result['ori_pres_date'] = NULL;
    
    $next_press_no = ($this_visit && $this_visit->prescription_no && $last_visit->prescription_no != $this_visit->prescription_no)? $this_visit->prescription_no:getNextPresNo();
    $location_id = $CI->data['location']->id;
    
    if(!$this_visit && !$last_visit)
    {
        $result['prescription_no'] = getNextPresNo();
        $result['refill'] = ($post['med_days'] > 7)? NULL:1;
        $result['ori_pres_date'] = date('Y-m-d');
    }
    elseif($this_visit && !$last_visit)
    {
        if($this_visit->is_med == 0)
        {
            $result['prescription_no'] = $next_press_no;
            $result['refill'] = ($post['med_days'] > 7)? NULL:1;;
            $result['ori_pres_date'] = date('Y-m-d');
        }
        else
        {
            $result['prescription_no'] = $next_press_no;
            $result['refill'] = ($post['med_days'] > 7)? NULL:1;;
            $result['ori_pres_date'] = date('Y-m-d');
        }
    }
    elseif($last_visit && (date('Y',strtotime($last_visit->visit_date))== 2016))
    {
        $result['prescription_no'] = $next_press_no;
        $result['refill'] = ($post['med_days'] > 7)? NULL:1;;
        $result['ori_pres_date'] = date('Y-m-d');
    }
    elseif($last_visit && !$last_visit->prescription_no)
    {
        $result['prescription_no'] = $next_press_no;
        $result['refill'] = ($post['med_days'] > 7)? NULL:1;;
        $result['ori_pres_date'] = date('Y-m-d');
    }
    elseif($last_visit && $last_visit->prescription_no)
    {
        $d1 = new DateTime(date('Y-m-d',strtotime($last_visit->visit_date)));
        $d2 = new DateTime(date('Y-m-d'));
        $gap = $d1->diff($d2)->format("%a");
        $gap = $gap - $last_visit->med_days;
        
        
        if($post['med_days'] > 7)
        {
            $result['prescription_no'] = $next_press_no;
            $result['refill'] = NULL;
            $result['ori_pres_date'] = date('Y-m-d');
        }
        elseif($last_visit->location_id != $location_id)
        {
            $result['prescription_no'] = $next_press_no;
            $result['refill'] = ($post['med_days'] > 7)? NULL:1;
            $result['ori_pres_date'] = date('Y-m-d');
        }
        elseif($gap < -1)
        {
            $result['prescription_no'] = $next_press_no;
            $result['refill'] = 1;
            $result['ori_pres_date'] = date('Y-m-d');
        }
        elseif(($post['med_days']!=$last_visit->med_days)||($post['meds_per_day'] != $last_visit->meds_per_day)||($post['med1']!=$last_visit->med1)||($post['med2']!=$last_visit->med2)||($post['med3']!=$last_visit->med3))
        {
            $result['prescription_no'] = $next_press_no;
            $result['refill'] = 1;
            $result['ori_pres_date'] = date('Y-m-d');
        }
        elseif(empty($last_visit->refill)) 
        {
            if($sixMonthAgo > date('Y-m-d',strtotime($last_visit->visit_date)))
            {
                $result['prescription_no'] = getNextPresNo();
                $result['refill'] = 1;
                $result['ori_pres_date'] = date('Y-m-d');
            }
            else
            {
                //Update last visit with refill and ori press date
                $data = array('refill'=>1,'ori_pres_date'=> date('Y-m-d',strtotime($last_visit->visit_date)));
                $CI->patient->updateVisit($last_visit->id,$data);
                
                if(($last_visit->med_days + $post['med_days']) > 35)
                {
                    $result['prescription_no'] = getNextPresNo();
                    $result['refill'] = 1;
                    $result['ori_pres_date'] = date('Y-m-d');
                }
                else 
                {
                     $result['prescription_no'] = $last_visit->prescription_no;
                    $result['refill'] = 2;
                    $result['ori_pres_date'] = date('Y-m-d',strtotime($last_visit->visit_date));
                }            
                
            }            
        }
        elseif($last_visit->refill) 
        {
            if($last_visit->refill == 5)
            {
                $result['prescription_no'] = $next_press_no;
                $result['refill'] = 1;
                $result['ori_pres_date'] = date('Y-m-d');
            }
            elseif($sixMonthAgo < date('Y-m-d',strtotime($last_visit->ori_pres_date)))
            {
                $totol_med_days = $CI->patient->getPresMedDays($last_visit->prescription_no);
                if(($totol_med_days->total_med_days + $post['med_days']) > 35)
                {
                    $result['prescription_no'] = $next_press_no;
                    $result['refill'] = 1;
                    $result['ori_pres_date'] = date('Y-m-d');
                }
                else 
                {
                    $result['prescription_no'] = $last_visit->prescription_no;
                    $result['refill'] = $last_visit->refill + 1;
                    $result['ori_pres_date'] = $last_visit->ori_pres_date;
                }
            }
            elseif($sixMonthAgo > date('Y-m-d',strtotime($last_visit->ori_pres_date)))
            {
                $result['prescription_no'] = $next_press_no;
                $result['refill'] = 1;
                $result['ori_pres_date'] = date('Y-m-d');
            }
                
        }
    }
    

    return $result;
}

function getMedWeeks($order_id)
{
    $wk = 0;
    $CI =& get_instance();
    $CI->load->model("Order_model", "order");
    $order = $CI->order->getOrder($order_id);
    $ois = $CI->order->getOrderItemsWithNames($order_id);
    
    foreach($ois as $oi)
    {
        if($oi->cat_id == 10) $wk += $oi->quantity;
    }
    
    $order_date = date('Y-m-d',strtotime($order->created));
    $pp = $CI->order->getRedeemedItems($order->patient_id,$order_date);
    
    foreach($pp as $p)
    {
       if($p->cat_id == 4) $wk += $p->quantity/7;
    }
    
    return $wk;
}

function getTodayInfo($order_id)
{
    $result = array();
    $result['bought'] = 0;
    $result['redeem'] = 0;
    
    $CI =& get_instance();
    
    $CI->load->model("Order_model", "order");
    $order = $CI->order->getOrder($order_id);
    $ois = $CI->order->getOrderItemsWithNames($order_id);
    
    foreach($ois as $oi)
    {
        if($oi->cat_id == 10) $result['bought'] += $oi->quantity;
    }
    
    $order_date = date('Y-m-d',strtotime($order->created));
    $pp = $CI->order->getRedeemedItems($order->patient_id,$order_date);
    
    foreach($pp as $p)
    {
       if($p->cat_id == 4) $result['redeem'] += $p->quantity/7;
    }
    
    return $result;
}
