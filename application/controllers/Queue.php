<?php
/**
 * Description of Queue
 *
 * @author Udana
 */
class Queue extends CI_Controller
{
    
    public $data;
    
    public function __construct() {
        parent::__construct();
        $this->data = array();
        $this->load->model('Queue_model','queue');
    }
    
    public function index()
    {
        $this->data['categories'] = $this->product->getCategories();
        $this->load->view('queue/index',$this->data);
    }

    public function addQueue()
    {
        $post = $this->input->post();

        $post['created'] = date('Y-m-d H:i:s');

        $qid = $this->queue->add($post);
        if($qid)
        {
            echo json_encode(['status'=> 'success']);
        }
        else
        {
            echo json_encode(['status'=> 'error']);
        }
    }

    public function markAsDone()
    {
        $post = $this->input->post();
        $id = $post['id'];
        $this->queue->update($id,array('status'=>1));
    }
    
    // public function checkUser()
    // {
    //     $post = $this->input->post();
    //     $patients = $this->queue->getPatient($post);
        
    //     $result = array();
        
    //     if($patients)
    //     {
    //         $patient = null;
    //         if(count($patients)>1)
    //         {
    //             $result['status'] = 'sn';
    //             $ids = '';
    //             foreach($patients as $patient)
    //             {
    //                 $ids .= $ids==''? $patient->id : ','.$patient->id;
    //             }
    //             $result['ids'] = $ids;
    //         }
    //         else
    //         {
    //             $result['status'] = 'success';
    //             $patient = $patients[0];
    //             $result['ui'] = $this->load->view('queue/_user_info',array('patient'=>$patient),TRUE);
    //             $result['pid'] = $patient->id;
    //         }
            
    //     }
    //     else
    //     {
    //         $result['status'] = 'error';
    //     }
        
    //     echo json_encode($result);
    // }
    
    // public function checkWithSN()
    // {
    //     $post = $this->input->post();
    //     $sn = trim($post['sn']);
    //     $ids = $post['ids'];
    //     $idArr = explode(',', $ids);
    //     $patient = null;
    //     $result = array();
        
    //     foreach($idArr as $id)
    //     {
    //         $p = $this->patient->getPatient($id);
            
    //         $addArr = explode(' ', $p->address);
    //         if(trim($addArr[0]) == $sn) 
    //         {
    //             $patient = $p;
    //             break;
    //         }
    //     }
        
    //     if($patient)
    //     {
    //         $result['status'] = 'success';
    //         $result['pid'] = $patient->id;
    //         $result['ui'] = $this->load->view('queue/_user_info',array('patient'=>$patient),TRUE);
    //     }
    //     else 
    //     {
    //         $result['status'] = 'error';
    //     }
        
    //     echo json_encode($result);
    // }
    
    
    
    // public function addItem()
    // {
    //     $post = $this->input->post();
    //     $pid = $post['pid'];
    //     $ois = array();
        
    //     if($this->session->userdata('qi_'.$pid))
    //     {
    //         $ois = $this->session->userdata('qi_'.$pid);
    //     }
        
    //     $product = $this->product->getProForCart($post['pro_id']);
    //     $ois[$product->id]['pro'] = $product;
    //     $ois[$product->id]['qty'] = $post['qty'];
        
    //     $this->session->set_userdata('qi_'.$pid, $ois);
        
    //     $result = array();
    //     $result['status'] = 'success';
    //     $result['cart'] = $this->load->view('queue/_cart',array('ois'=>$ois),TRUE);
        
    //     echo json_encode($result);
    // }
    
    // public function getQueue($pid)
    // {
    //     $queue = $this->queue->getQueue($pid);
    //     $type = 1;
    //     if($queue)
    //     {
    //         $type = $queue->type;
    //     }
        
    //     echo $type;
    // }
    
    // public function getItems($pid)
    // {
    //     $ois = array();
    //     $queue = $this->queue->getQueue($pid);
    //     if($queue)
    //     {
    //         $items = $this->queue->getQueueItems($queue->id); 
    //         foreach($items as $item)
    //         {
    //             $product = $this->product->getProForCart($item->pro_id);
    //             $ois[$product->id]['pro'] = $product;
    //             $ois[$product->id]['qty'] = $item->qty;
    //         }
        
    //         $this->session->set_userdata('qi_'.$pid, $ois);
    //     }
        
    //     echo $this->load->view('queue/_cart',array('ois'=>$ois),TRUE);
    // }
    
    // public function viewItems($pid)
    // {        
    //     $items = $this->queue->getQueueItems($pid); 
        
    //     $ois = array();
        
    //     foreach($items as $item)
    //     {
    //         $product = $this->product->getProForCart($item->pro_id);
    //         $ois[$product->id]['pro'] = $product;
    //         $ois[$product->id]['qty'] = $item->qty;
    //     }
        
    //     echo $this->load->view('queue/_cart',array('ois'=>$ois),TRUE);
    // }
    
    // public function addToQueue()
    // {
    //     $post = $this->input->post();
    //     $pid = $post['pid'];
    //     $qid = 0;
    //     $type = $post['type'];
    //     if($queue = $this->queue->getQueue($pid))
    //     {
    //         $this->queue->update($queue->id,array('type'=>$type));
    //     }
    //     else
    //     {
    //         $qid = $this->queue->add(array('patient_id'=>$pid,'type'=>$type,'created'=>date('Y-m-d H:i:s')));
    //         sendQueueMsg($pid,1);
    //     }
       
        
    //     $result = array();
    //     $result['status'] = 'success';
    //     echo json_encode($result);
    // }
    
    
    
    // public function getCatProDropDown($cat)
    // {
    //     $products = $this->product->getCatPros($cat);
    //     $free_pros = $this->config->item('allow_free_pros');
        
    //     $select = '<select class="form-control" name="pro_id" id="pro_id" tabindex="-1" aria-hidden="true" style="width: 100%;">';
    //     foreach($products as $pro)
    //     {
    //         if($pro->price == 0 && !in_array($pro->id,$free_pros)) continue;
    //         $select .= '<option value="'.$pro->id.'">'.$pro->name.'</option>';
    //     }
    //     $select .= '</select>';
        
    //     echo $select;
    // }
    
    // public function addNewToQueue()
    // {
    //     $post = $this->input->post();
        
    //     $post['created'] = date('Y-m-d H:i:s');
    //     $qid = $this->queue->add($post);
        
    // }
    
    // public function removeQueue()
    // {
    //     $post = $this->input->post();
    //     $this->queue->removeQueue($post['id']);
    //     echo 'success';
    // }
    
    
}