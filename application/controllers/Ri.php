<?php
/**
 * Description of Regulare Inventory
 *
 * @author Udana
 */
class Ri extends Admin_Controller
{
    public function index()
    {        
        $this->data['bc1'] = 'Regular Inventory';
	$this->data['bc2'] = 'Manage';
        
        $this->data['products'] = array();
        
        $this->load->view('ri/index',$this->data);
    }
}
