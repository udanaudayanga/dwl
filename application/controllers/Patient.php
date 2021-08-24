<?php

/**
 * Patient Controller
 *
 * @author Udana Udayanga <udana@udana.lk>
 */
class Patient extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Location_model', 'location');
        $this->load->model('Ext_model', 'ext');
        $this->load->model('User_model', 'user');
        $this->load->model('Evaluate_model', 'evaluate');
    }

    public function index()
    {
        $this->data['bc1'] = 'Patients';
        $this->data['bc2'] = 'View';

        $this->data['locations'] = $this->location->getAll(TRUE);
        $this->data['staff'] = $this->user->getUserByType(array(2, 4));

        $this->load->view('patient/index', $this->data);
    }

    /**
     * get Patients for datatable
     *
     * @return JSON
     */
    public function getPatients()
    {
        $fetch_data = $this->patient->makeDT();
        $data = array();
        $user = $this->data['user'];
        foreach ($fetch_data as $row) {
            $sub_array = array();
            $sub_array[] = $row->lname . " " . $row->fname;
            $sub_array[] = "(" . substr($row->phone, 0, 3) . ") " . substr($row->phone, 3, 3) . "-" . substr($row->phone, 6);
            $sub_array[] = ($row->dob) ? date('m/d/Y', strtotime($row->dob)) : '';
            $sub_array[] = getPatientStatus($row->status);
            $action = '<a  data-status="weekly" class="add_visit" href="' . site_url("patient/addVisit/$row->id") . '" style="color: red;" >1 - Add Visit</a>&nbsp;&nbsp;|&nbsp;&nbsp;';
            if ($user->type == 1) {
                $action .= '<a onclick="return confirm(\'Are you sure?\')" href="' . site_url("patient/remove/$row->id") . '" style="color: #31b0d5;">Del</a> &nbsp;&nbsp;|&nbsp;&nbsp;';
            }
            $action .= '<a href="' . site_url("patient/view/$row->id") . '" style="color: #31b0d5;">View</a>';
            if ($user->type == '1' && FALSE) {
                $action .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a href="#" data-name="' . $row->lname . ' ' . $row->fname . '" data-patientid="' . $row->id . '" class="add_pp" style="color: #31b0d5;">Add PP</a>';
            }
            if ($row->vcount > 0) {
                $action .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a href="' . site_url("evaluate/view/$row->id") . '"  style="color: #31b0d5;">Re-eval</a>';
            } else if (!empty($row->pls)) {
                $action .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a target="_blank" href="' . site_url("patient/lspdf/$row->id") . '"  style="color: #31b0d5;">Last Status</a>';
            }

            $action .= '&nbsp;&nbsp;|&nbsp;&nbsp;<a title="Create an Appointment" data-lsd="' . date('m/d/Y', strtotime($row->last_status_date)) . '" data-status="' . getPatientStatus($row->status) . '" data-id="' . $row->id . '" data-name="' . $row->lname . ' ' . $row->fname . '" class="appt" href=""  style="color: #31b0d5;">Appt</a>';

            $sub_array[] = $action;

            $data[] = $sub_array;
        }

        $output = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $this->patient->getAllPatientCount(),
            "recordsFiltered" => $this->patient->getFilteredDT(),
            "data" => $data
        );

        echo json_encode($output);
    }
    
    /**
     * Patient search page
     *
     * @return void
     */
    public function search()
    {
        $this->data['bc1'] = 'Patients';
        $this->data['bc2'] = 'Search';
        $this->data['locations'] = $this->location->getAll(TRUE);
        $this->data['staff'] = $this->user->getUserByType(array(2, 4));
        $this->data['pps'] = $this->product->getPpPros();
        $this->load->view('patient/search', $this->data);
    }

    /**
     * search AJAX request
     *
     * @return void
     */
    public function dosearch()
    {
        $post = $this->input->post();

        $phase = $post['phase'];
        $phase = preg_replace('/\s+/S', " ", $phase);
        $phase = trim($phase);

        $result = array();
        if (empty($phase)) {
            $result['status'] = 'error';
            $result['msg'] = "Please insert a search phase";
        } else {
            $this->data['patients'] = $this->patient->patientSearch($phase);

            $result['table'] = $this->load->view('patient/_search', $this->data, TRUE);
        }

        echo json_encode($result);
    }

    /**
     * Patient add page
     *
     * @return void
     */
    public function add()
    {
        $this->data['bc1'] = 'Patient';
        $this->data['bc2'] = 'Add';
        $this->data['errors'] = '';
        $this->data['states'] = $this->patient->getStates();
        $this->data['staff'] = $this->user->getUserByType(array(2, 4));

        if ($this->input->server('REQUEST_METHOD') === 'POST') {

            $this->form_validation->set_rules('fname', 'First Name', 'trim|required');
            $this->form_validation->set_rules('lname', 'Last Name', 'trim|required');
            $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|is_unique[patients.email]', array('is_unique' => 'This email already exist.'));
            $this->form_validation->set_rules('phone', 'Phone', 'trim');
            $this->form_validation->set_rules('gender', 'Gender', 'trim|required');
            $this->form_validation->set_rules('goal_weight', 'Goal Weight', 'trim|numeric|greater_than[80]|less_than[400]|required');
            $this->form_validation->set_rules('height', 'Height', 'trim|required');
            $this->form_validation->set_rules('address', 'Address', 'trim|required');
            $this->form_validation->set_rules('city', 'City', 'trim|required');
            $this->form_validation->set_rules('zip', 'Zip', 'trim|is_natural|exact_length[5]|required');
            $this->form_validation->set_rules('state', 'State', 'trim|required');

            $this->form_validation->set_rules('new_patient', 'New Patient Share', 'trim|is_natural');
            $this->form_validation->set_rules('old_patient', 'Old Patient Share', 'trim|is_natural');
            $this->form_validation->set_rules('patient_refferral_name', 'Patient referral name', 'trim');
            $this->form_validation->set_rules('patient_refferral_id', 'Patient refferal id', 'trim');

            $this->form_validation->set_rules('previous_medication', 'Current medication', 'trim|required');
            $this->form_validation->set_rules('allergies', 'Allergies', 'trim');
            $this->form_validation->set_rules('staff', 'Staff', 'trim|required');

            $post = $this->input->post();
            if (!empty($post['patient_refferral_id']) && $post['new_patient'] == 0 && $post['old_patient'] == 0) {
                $this->form_validation->set_rules('new_patient', 'Patient Referral Split', 'trim|is_natural_no_zero|required', array('is_natural_no_zero' => 'Patient Referral Split is required'));
                $this->form_validation->set_rules('old_patient', 'Old Patient Referral Split', 'trim|is_natural_no_zero|required', array('is_natural_no_zero' => 'Patient Referral Split is required'));
            }

            if ($this->form_validation->run() == TRUE) {


                $uploaded = array();

                if (!empty($_FILES['photo']['name'])) {
                    $uploaded = $this->upload();
                }

                if (!isset($uploaded['error'])) {
                    //Add patient
                    $post['created'] = date('Y-m-d H:i:s');
                    if (isset($uploaded['img'])) $post['photo'] = $uploaded['img'];
                    unset($post['patient_refferral_name']);
                    $post['phone'] = str_replace(array(' ', '(', ')', '-'), '', $post['phone']);
                    $post['dob'] = date('Y-m-d', strtotime($post['dob']));


                    if (empty($post['patient_refferral_id'])) {
                        unset($post['patient_refferral_id'], $post['new_patient'], $post['old_patient']);
                    } else {
                        $post['new_patient'] = empty($post['new_patient']) ? 0 : $post['new_patient'];
                        $post['old_patient'] = empty($post['old_patient']) ? 0 : $post['old_patient'];
                    }


                    $post['referral_given'] = 0;
                    $new_patient_id = $this->patient->add($post);

                    //add initial phase
                    $phase = array('patient_id' => $new_patient_id, 'phase' => 1, 'start' => date('Y-m-d'), 'end' => date('Y-m-d', strtotime("+12 weeks")), 'created' => date("Y-m-d H:i:s"));
                    $this->evaluate->add($phase);

                    $this->session->set_flashdata('message', 'Patient Added Successfully');
                    redirect('patient');
                } else {
                    $this->data['errors'] = '<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12" style="padding:0 5px;"><div role="alert" class="alert fresh-color alert-danger"><strong>' . $uploaded['error'] . '</strong></div></div>';
                }
            } else {
                $this->data['errors'] = validation_errors('<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12" style="padding:0 5px;"><div style="padding:5px 10px;" role="alert" class="alert fresh-color alert-danger"><strong>', '</strong></div></div>');
            }
        }

        $this->load->view("patient/add", $this->data);
    }

    /**
     * patient edit page
     *
     * @param [type] $id
     * @return void
     */
    public function edit($id)
    {
        $this->data['bc1'] = 'Patient';
        $this->data['bc2'] = 'Update';
        $this->data['errors'] = '';
        $this->data['states'] = $this->patient->getStates();
        $patient = $this->patient->getPatient($id);
        $this->data['patient'] = $patient;
        $this->data['lv'] = $this->patient->getLatestVisit($id);
        $this->data['staff'] = $this->user->getUserByType(array(2, 4));

        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $post = $this->input->post();
            $this->form_validation->set_rules('fname', 'First Name', 'trim|required');
            $this->form_validation->set_rules('lname', 'Last Name', 'trim|required');

            if ($post['email'] != $patient->email)
                $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|is_unique[patients.email]', array('is_unique' => 'This email already exist.'));

            $this->form_validation->set_rules('phone', 'Phone', 'trim');
            $this->form_validation->set_rules('gender', 'Gender', 'trim|required');
            $this->form_validation->set_rules('goal_weight', 'Goal Weight', 'trim|numeric|greater_than[80]|less_than[400]|required');
            $this->form_validation->set_rules('height', 'Height', 'trim|required');
            $this->form_validation->set_rules('address', 'Address', 'trim|required');
            $this->form_validation->set_rules('city', 'City', 'trim|required');
            $this->form_validation->set_rules('zip', 'Zip', 'trim|is_natural|exact_length[5]|required');
            $this->form_validation->set_rules('state', 'State', 'trim|required');

            $this->form_validation->set_rules('new_patient', 'New Patient Share', 'trim|is_natural');
            $this->form_validation->set_rules('old_patient', 'Old Patient Share', 'trim|is_natural');
            $this->form_validation->set_rules('patient_refferral_name', 'Patient referral name', 'trim');
            $this->form_validation->set_rules('patient_refferral_id', 'Patient refferal id', 'trim');

            $this->form_validation->set_rules('previous_medication', 'Current medication', 'trim|required');
            $this->form_validation->set_rules('allergies', 'Allergies', 'trim');

            if (!$patient->dob) {
                $this->form_validation->set_rules('staff', 'Staff', 'trim|required');

                if (!empty($post['patient_refferral_id']) && $post['new_patient'] == 0 && $post['old_patient'] == 0) {
                    $this->form_validation->set_rules('new_patient', 'Patient Referral Split', 'trim|is_natural_no_zero|required', array('is_natural_no_zero' => 'Patient Referral Split is required'));
                    $this->form_validation->set_rules('old_patient', 'Old Patient Referral Split', 'trim|is_natural_no_zero|required', array('is_natural_no_zero' => 'Patient Referral Split is required'));
                }
            }

            if ($this->form_validation->run() == TRUE) {


                $uploaded = array();

                if (!empty($_FILES['photo']['name'])) {
                    $uploaded = $this->upload();
                }

                if (!isset($uploaded['error'])) {
                    //Add patient
                    $post['created'] = date('Y-m-d H:i:s');
                    if (isset($uploaded['img'])) {
                        $post['photo'] = $uploaded['img'];
                        if ($patient->photo && file_exists("./assets/upload/patients/$patient->photo")) unlink("./assets/upload/patients/$patient->photo");
                    }

                    $post['phone'] = str_replace(array(' ', '(', ')', '-'), '', $post['phone']);
                    $post['dob'] = date('Y-m-d', strtotime($post['dob']));

                    if (!$patient->dob) {
                        if (empty($post['patient_refferral_id'])) {
                            unset($post['patient_refferral_id'], $post['new_patient'], $post['old_patient']);
                        } else {
                            $post['new_patient'] = empty($post['new_patient']) ? 0 : $post['new_patient'];
                            $post['old_patient'] = empty($post['old_patient']) ? 0 : $post['old_patient'];
                        }

                        $post['referral_given'] = 0;
                    }
                    unset($post['patient_refferral_name']);
                    $this->patient->updatePatient($id, $post);

                    $this->session->set_flashdata('message', 'Patient Updated Successfully');
                    redirect("patient/view/$id");
                } else {
                    $this->data['errors'] = '<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12" style="padding:0 5px;"><div role="alert" class="alert fresh-color alert-danger"><strong>' . $uploaded['error'] . '</strong></div></div>';
                }
            } else {
                $this->data['errors'] = validation_errors('<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12" style="padding:0 5px;"><div role="alert" class="alert fresh-color alert-danger"><strong>', '</strong></div></div>');
            }
        }

        $this->load->view("patient/edit", $this->data);
    }

    /**
     * get referal patient
     *
     * @return JSON
     */
    public function getReferalPatient()
    {
        $request = $this->input->get();
        $patients = $this->patient->getPatientAC($request['term']);

        echo json_encode($patients);
    }

    /**
     * get list of active patients
     *
     * @return JSON
     */
    public function getActivePatients()
    {
        $request = $this->input->get();
        $patients = $this->patient->getPatientAC($request['term'], TRUE);
        echo json_encode($patients);
    }

    /**
     * get previous meds
     *
     * @return JSON
     */
    public function getPrevMeds()
    {
        $request = $this->input->get();
        $patients = $this->patient->getPrevMedAC($request['term']);

        echo json_encode($patients);
    }

    /**
     * Upload patient image
     *
     * @return image data
     */
    public function upload()
    {
        // Upload photo
        $config['upload_path'] = './assets/upload/patients/';;
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['encrypt_name'] = TRUE;
        $data = array();
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('photo')) {
            $data['error'] = $this->upload->display_errors();
        } else {
            $upload_data = $this->upload->data();
            $data['img'] = $upload_data['file_name'];
        }

        return $data;
    }

    /**
     * add patient visit
     *
     * @param [type] $id
     * @return void
     */
    public function addVisit($id)
    {
        $this->data['bc1'] = 'Patient';
        $this->data['bc2'] = 'Add Visit';

        $patient = $this->patient->getPatient($id);
        $this->data['patient'] = $patient;
        $this->data['patient_id'] = $patient->id;
        $this->data['prepaids'] = $this->order->getPatientPP($id);
        $this->data['reminders'] = $this->ext->getPatientReminders($id);
        $this->data['alerts'] = $this->patient->getAlerts($id);
        $this->data['dralerts'] = $this->patient->getAlerts($id, 1);

        $last_visit = $this->patient->getLastVisit($id);
        $this->data['last_visit'] = $last_visit;


        $this->data['today_order'] = $this->order->getTodayOrder($id);
        manageCycle($id);

        $this->load->view('patient/add_visit', $this->data);
    }

    /**
     * get prepaid history page
     *
     * @param [type] $id
     * @return void
     */
    public function getPPHistory($id)
    {
        $ppItem = $this->order->getPPItem($id);
        $product = $this->product->get($ppItem->pro_id);
        $this->data['product'] = $product;
        $this->data['pph'] = $this->order->getPPHistory($id);

        echo $this->load->view('patient/prepaid_history', $this->data, TRUE);
    }

    /**
     * patient info view page
     *
     * @param [type] $id
     * @return void
     */
    public function view($id)
    {

        $this->data['bc1'] = 'Patient';
        $this->data['bc2'] = 'View';

        $this->data['patient'] = $this->patient->getPatient($id);
        $this->data['visits'] = $this->patient->getPatientVisits($id);
        $this->data['orders'] = $this->order->getPatientOrders($id);
        $this->data['ecgs'] = $this->patient->getAllECG($id);
        $this->data['bws'] = $this->patient->getAllBW($id);
        $this->data['alerts'] = $this->patient->getAlerts($id);
        $this->data['fv'] = $this->patient->getPatientVisit($id, 1);
        $restarts = array();
        $rss = $this->order->getRestarts($id);
        foreach ($rss as $rs) {
            $restarts[$rs->order_id] = $rs->date;
        }

        $lastOrder = $this->patient->getLastOrder($id);
        $this->data['lo'] = $lastOrder;
        if ($lastOrder) $this->data['loi'] = $this->order->getOrderItemsWithNames($lastOrder->id);

        $this->data['restarts'] = $rss;
        $this->data['rss'] = $restarts;
        $this->data['ppPros'] = getPPCategoryArray();

        $this->load->view('patient/view', $this->data);
    }

    /**
     * get prescription for order
     *
     * @param [type] $order_id
     * @return void
     */
    public function prescription($order_id)
    {
        $this->data['bc1'] = 'Patient';
        $this->data['bc2'] = 'Prescription';

        $order = $this->order->getOrder($order_id);
        $this->data['order'] = $order;
        $patient = $this->patient->getPatient($order->patient_id);
        $this->data['patient'] = $patient;

        $today_visit = $this->patient->getVisitByOrderId($order->id);
        $last_visit = null;
        if ($today_visit && $today_visit->visit > 1) {
            $lvn = $today_visit->visit - 1;
            $last_visit = $this->patient->getPatientVisit($order->patient_id, $lvn);
        } else {
            $last_visit = $this->patient->getLastVisitNew($order->patient_id, date('Y-m-d', strtotime($order->created)));
        }
        $this->data['last_visit'] = $last_visit;
        $this->data['tv'] = $today_visit;

        $medication_cat_id = $this->config->item('medication_cat');
        $this->data['med_pros'] = $this->product->getCatPros($medication_cat_id);

        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $post = $this->input->post();

            $this->form_validation->set_rules('is_med', 'No Meds or Meds', 'trim|required');
            $this->form_validation->set_rules('weight', 'Weight', 'trim|decimal|greater_than_equal_to[60.0]|less_than_equal_to[420.0]|required');
            $this->form_validation->set_rules('bmi', 'BMI', 'trim|decimal|greater_than_equal_to[15.0]|less_than_equal_to[60.0]|required');
            if ($post['bfi'] != 0)
                $this->form_validation->set_rules('bfi', 'BFI', 'trim|decimal|greater_than_equal_to[15.0]|less_than_equal_to[60.0]|required');

            $this->form_validation->set_rules('bp', 'BP', 'trim|required');

            if (isset($post['is_med'])) {
                if ($post['is_med'] == 0) {
                    $this->form_validation->set_rules('no_med_days', 'No od days', 'trim|required');
                }
                if ($post['is_med'] == 1) {
                    $this->form_validation->set_rules('med_days', 'No of days', 'trim|required');
                    $this->form_validation->set_rules('meds_per_day', 'Meds per day', 'trim|required');

                    if ($post['med1'] == 0 && $post['med2'] == 0 && $post['med3'] == 0) {
                        $this->form_validation->set_rules('med1', 'Medications', 'trim|is_natural_no_zero|required', array('is_natural_no_zero' => "Medications field is required"));
                        //                        $this->form_validation->set_rules('med2', 'Medications', 'trim|is_natural_no_zero',array('is_natural_no_zero'=> "Medications field is required"));
                        //                        $this->form_validation->set_rules('med3', 'Medications', 'trim|is_natural_no_zero',array('is_natural_no_zero'=> "Medications field is required"));
                    }
                }
            }

            if ($this->form_validation->run() == TRUE) {
                $proceed = true;
                if ($post['is_med'] == 1) {
                    $no_of_meds = $post['med_days'] * $post['meds_per_day'];
                    if (!empty($post['med1']) && !checkStock($post['med1'], $no_of_meds)) $proceed = FALSE;
                    if (!empty($post['med2']) && !checkStock($post['med2'], $no_of_meds)) $proceed = FALSE;
                    if (!empty($post['med3']) && !checkStock($post['med3'], $no_of_meds)) $proceed = FALSE;

                    if (!$proceed) $this->data['errors'] = '<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12" style="padding:0 5px;"><div role="alert" class="alert fresh-color alert-danger">Meds are out of stock.</div></div>';
                }
                if ($proceed) {

                    $post['order_id'] = $order_id;
                    if (!$today_visit) $post['visit'] = ($last_visit) ? $last_visit->visit + 1 : 1;

                    if (!$last_visit) {
                        $post['status'] = 'start';
                        $post['diff'] = 0;
                    } else {
                        if ($last_visit->weight > $post['weight']) $post['status'] = 'loss';
                        if ($last_visit->weight < $post['weight']) $post['status'] = 'gain';
                        if ($last_visit->weight == $post['weight']) $post['status'] = 'same';

                        $post['diff'] = $post['weight'] - $last_visit->weight;
                    }

                    if ($post['is_med'] == 0) unset($post['med_days']);
                    if ($post['is_med'] == 1) unset($post['no_med_days']);

                    if ($today_visit) {

                        if ($post['is_med'] == 1) {
                            $post += getPresNo($today_visit, $last_visit, $post);
                        } elseif ($post['is_med'] == 0) {
                            $post['prescription_no'] = NULL;
                            $post['refill'] = NULL;
                            $post['ori_pres_date'] = NULL;
                        }

                        $this->patient->updateVisit($today_visit->id, $post);

                        if ($today_visit->is_med == 1) {
                            $ex_no_of_days = $today_visit->med_days;
                            if ($today_visit->med1 > 0) restoreStock($today_visit->med1, $ex_no_of_days);
                            if ($today_visit->med2 > 0) restoreStock($today_visit->med2, $ex_no_of_days);
                            if ($today_visit->med3 > 0) restoreStock($today_visit->med3, $ex_no_of_days);

                            $ex_packs = getSupPacksForDays($ex_no_of_days);

                            foreach ($ex_packs as $key => $val) {
                                restoreStock($key, $val);
                            }
                        }
                        if ($post['is_med'] == 1) {
                            $no_of_days = $post['med_days'];
                            if ($post['med1'] > 0) reduceStock($post['med1'], $no_of_days);
                            if ($post['med2'] > 0) reduceStock($post['med2'], $no_of_days);
                            if ($post['med3'] > 0) reduceStock($post['med3'], $no_of_days);

                            $packs = getSupPacksForDays($no_of_days);

                            foreach ($packs as $key => $val) {
                                reduceStock($key, $val);
                            }
                        }
                    } else {
                        $post['visit_date'] = date('Y-m-d H:i:s');
                        if ($post['is_med'] == 1) {
                            $post += getPresNo($today_visit, $last_visit, $post);
                        }

                        $this->patient->add_visit($post);

                        if ($post['is_med'] == 1) {
                            $no_of_days = $post['med_days'];
                            if ($post['med1'] > 0) reduceStock($post['med1'], $no_of_days);
                            if ($post['med2'] > 0) reduceStock($post['med2'], $no_of_days);
                            if ($post['med3'] > 0) reduceStock($post['med3'], $no_of_days);

                            $packs = getSupPacksForDays($no_of_days);
                            foreach ($packs as $key => $val) {
                                reduceStock($key, $val);
                            }
                        }
                    }

                    if ($patient->referral_given == 0) {
                        $turns  = getNoOfTurnsForPeriod($patient->id, date('Y-m-d', strtotime($patient->created)));

                        if (!empty($patient->patient_refferral_id) && $turns >= 4) {
                            $referral_pro_id = $this->config->item('referral_pro_id');

                            if ($patient->new_patient > 0) {
                                $new_p_pp = array();
                                $new_p_pp['patient_id'] = $patient->id;
                                $new_p_pp['location_id'] = $this->data['location']->id;
                                $new_p_pp['pro_id'] = $referral_pro_id;
                                $new_p_pp['type'] = 'add';
                                $new_p_pp['quantity'] = $patient->new_patient;
                                $new_p_pp['add_type'] = 'referral';
                                $new_p_pp['created'] = date('Y-m-d');
                                $new_p_pp['referred_by'] = $patient->patient_refferral_id;

                                addPrepaidItem($new_p_pp);
                            }

                            if ($patient->old_patient > 0) {
                                $new_p_pp = array();
                                $new_p_pp['patient_id'] = $patient->patient_refferral_id;
                                $new_p_pp['location_id'] = $this->data['location']->id;
                                $new_p_pp['pro_id'] = $referral_pro_id;
                                $new_p_pp['type'] = 'add';
                                $new_p_pp['quantity'] = $patient->old_patient;
                                $new_p_pp['add_type'] = 'referral';
                                $new_p_pp['created'] = date('Y-m-d');
                                $new_p_pp['referrer'] = $patient->id;

                                addPrepaidItem($new_p_pp);
                            }

                            $this->patient->updatePatient($patient->id, array('referral_given' => 1));
                        }
                    }

                    $this->patient->updatePatient($patient->id, array('status' => 1));

                    if (date('Y-m-d', strtotime($order->created)) == date('Y-m-d'))
                        $this->order->updateOrder($order_id, array('status' => 1));


                    manageCycle($order->patient_id);

                    $this->session->set_flashdata('message', 'Visit Information added successfully');
                    redirect('order/pending');
                }
            } else {
                $this->data['errors'] = validation_errors('<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12" style="padding:0 5px;"><div role="alert" style="padding:5px 10px;" class="alert fresh-color alert-danger">', '</div></div>');
            }
        }


        $this->load->view('patient/prescription', $this->data);
    }

    /**
     * Update prepaid quantities
     *
     * @return void
     */
    public function update_prepaid()
    {
        $post = $this->input->post();

        $pp_item = $this->order->getPPItem($post['pp_id']);

        $product = $this->product->get($pp_item->pro_id);
        $result = array();
        $proceed = TRUE;


        if ($pp_item->remaining < $post['qnty']) {
            $proceed = FALSE;
            $result['status'] = 'error';
            $result['error'] = 'Not enough remaining to redeem';
        }

        if ($proceed && !checkStock($pp_item->pro_id, $post['qnty'])) {
            $proceed = FALSE;
            $result['status'] = 'error';
            $result['error'] = 'No Days/Qty were recorded or Product out of stock';
        }

        if ($proceed) {
            $new_p_pp = array();
            $new_p_pp['patient_id'] = $pp_item->patient_id;
            $new_p_pp['location_id'] = $this->data['location']->id;
            $new_p_pp['pro_id'] = $pp_item->pro_id;
            $new_p_pp['type'] = 'subtract';
            $new_p_pp['quantity'] = $post['qnty'];
            $new_p_pp['created'] = isset($post['date']) ? $post['date'] : date('Y-m-d');
            $result = addPrepaidItem($new_p_pp);
            $result['sql'] = $this->db->last_query();
            if ($result['status'] == 'success') {
                reducePPStock($pp_item->pro_id, $post['qnty']);
            }
        }

        echo json_encode($result);
    }

    /**
     * Ticket page
     *
     * @return void
     */
    public function ticket()
    {
        $this->load->view('patient/ticket', $this->data);
    }

    /**
     * Create ticket PDF
     *
     * @return void
     */
    public function ticketdpdf()
    {
        $html = $this->load->view('patient/ticketm', $this->data, true);
        create_dp_ticket($html);
    }

    /**
     * Create PDF alternate
     *
     * @return void
     */
    public function ticketmpdf()
    {
        $html = $this->load->view('patient/ticketm', $this->data, true);
        create_mp_ticket($html);
    }

    /**
     * Add ECG info
     *
     * @return void
     */
    public function ecg()
    {
        $post = $this->input->post();
        $patient = $this->patient->getPatient($post['patient_id']);
        $result = $this->uploadOther("ecg/");
        if (isset($result['error'])) {
            $this->session->set_flashdata('error', $result['error']);
            redirect("patient/view/$patient->id");
        } else {
            $data = array();
            $data['patient_id'] = $patient->id;
            $data['file'] = $result['file'];
            $data['created'] = date('Y-m-d H:i:s');
            $ecg_id = $this->patient->addECG($data);
            $this->load->library('ftp');
            $newname = $ecg_id . "_" . $result['file'];
            rename('./assets/upload/ecg/' . $result['file'], './assets/upload/ecg/' . $newname);
            $this->patient->updateECG($ecg_id, array('file' => $newname));

            $this->session->set_flashdata('message', 'ECG uploaded successfully.');
            redirect("patient/view/$patient->id");
        }
    }

    /**
     * Add BW info
     *
     * @return void
     */
    public function bw()
    {
        $post = $this->input->post();
        $patient = $this->patient->getPatient($post['patient_id']);
        $result = $this->uploadOther("bw/");
        if (isset($result['error'])) {
            $this->session->set_flashdata('error', $result['error']);
            redirect("patient/view/$patient->id");
        } else {
            $data = array();
            $data['patient_id'] = $patient->id;
            $data['file'] = $result['file'];
            $data['created'] = date('Y-m-d H:i:s');
            $bw_id = $this->patient->addBW($data);

            $this->load->library('ftp');
            $newname = $bw_id . "_" . $result['file'];
            rename('./assets/upload/bw/' . $result['file'], './assets/upload/bw/' . $newname);
            $this->patient->updateBW($bw_id, array('file' => $newname));

            $this->session->set_flashdata('message', 'BW uploaded successfully.');
            redirect("patient/view/$patient->id");
        }
    }

    /**
     * Upload patient reports
     *
     * @param [type] $path
     * @return upload data
     */
    public function uploadOther($path)
    {
        // Upload photo
        $config['upload_path'] = './assets/upload/' . $path;;
        $config['allowed_types'] = 'pdf';
        $config['encrypt_name'] = TRUE;
        $data = array();
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('userfile')) {
            $data['error'] = $this->upload->display_errors();
        } else {
            $upload_data = $this->upload->data();
            $data['file'] = $upload_data['file_name'];
        }

        return $data;
    }

    /**
     * Delete ECG record
     *
     * @param [type] $id
     * @return void
     */
    public function del_ecg($id)
    {
        $ecg = $this->patient->getECG($id);
        $this->patient->delECG($id);

        if ($ecg && file_exists("./assets/upload/ecg/$ecg->file")) unlink("./assets/upload/ecg/$ecg->file");

        $this->session->set_flashdata('message', 'ECG removed successfully.');
        redirect("patient/view/$ecg->patient_id");
    }

    /**
     * Delete BW record
     *
     * @param [type] $id
     * @return void
     */
    public function del_bw($id)
    {
        $bw = $this->patient->getBW($id);
        $this->patient->delBW($id);

        if ($bw && file_exists("./assets/upload/bw/$bw->file")) unlink("./assets/upload/bw/$bw->file");

        $this->session->set_flashdata('message', 'BW removed successfully.');
        redirect("patient/view/$bw->patient_id");
    }

    /**
     * upload patient avatar
     *
     * @return void
     */
    public function upload_patient_photo()
    {
        $uploaded = $this->upload();
        if (!isset($uploaded['error'])) {
            $post = $this->input->post();
            $patient = $this->patient->getPatient($post['pro_id']);

            $data = array();
            $data['photo'] = $uploaded['img'];
            if (file_exists("./assets/upload/patients/$patient->photo")) unlink("./assets/upload/patients/$patient->photo");

            $this->patient->updatePatient($post['pro_id'], $data);

            $this->session->set_flashdata('message', 'Patient Photo Added Successfully');
        } else {
            $this->session->set_flashdata('error', $uploaded['error']);
        }
        redirect('/');
    }

    /**
     * Remove patient
     *
     * @param [type] $id
     * @return void
     */
    public function remove($id)
    {
        if ($this->patient->getLastVisit($id)) {
            $this->session->set_flashdata('error', 'Patient cannot removed.There are recorded visits for this patient.');
        } else {
            $this->patient->removePatient($id);
            $this->session->set_flashdata('message', 'Patient Removed Successfully');
        }

        redirect("patient");
    }

    /**
     * Assign intitial prepaid
     *
     * @return JSON
     */
    public function assignInitPP()
    {
        $this->form_validation->set_rules('pro_id', 'PP Pro', 'trim|required');
        $this->form_validation->set_rules('remaining', 'Remaining', 'trim|required');

        $location = $this->session->userdata('location');

        $result = array();
        if ($this->form_validation->run() == TRUE) {
            $post = $this->input->post();

            $expp = $this->order->getPrepaidItem($post['patient_id'], $post['pro_id']);
            if ($expp) {
                $remaining = $expp->remaining + $post['remaining'];
                $this->order->updatePrepaidItem($expp->id, array('remaining' => $remaining, 'updated' => date('Y-m-d H:i:s')));
                $ppbb = array('prepaid_id' => $expp->id, 'type' => 'add', 'add_type' => 'free', 'location_id' => $location->id, 'quantity' => $post['remaining'], 'created' => date('Y-m-d'));
                $this->order->addPPBrkdwn($ppbb);
            } else {
                $pp_id = $this->order->addPrepaidItem(array('patient_id' => $post['patient_id'], 'pro_id' => $post['pro_id'], 'remaining' => $post['remaining'], 'updated' => date('Y-m-d H:i:s')));
                $ppbb = array('prepaid_id' => $pp_id, 'type' => 'add', 'add_type' => 'free', 'location_id' => $location->id, 'quantity' => $post['remaining'], 'created' => date('Y-m-d'));
                $this->order->addPPBrkdwn($ppbb);
            }

            $result['status'] = 'success';
            $result['msg'] = '<div role="alert" class="alert fresh-color alert-success"><strong>Prepaid remaining Assigned Successfully.</strong></div>';
        } else {
            $result['status'] = 'error';
            $result['errors'] = validation_errors('<div class="col-xs-12" style="padding:0 5px;"><div style="padding:5px;" role="alert" class="alert fresh-color alert-danger"><strong>', '</strong></div></div>');
        }

        echo json_encode($result);
    }

    /**
     * Visit summery page
     *
     * @param [type] $visit_id
     * @return void
     */
    public function visitSummery($visit_id)
    {
        $this->data['bc1'] = "Patient";
        $this->data['bc2'] = "Visit Summery";

        $this->load->view('patient/visitsummery', $this->data);
    }

    /**
     * add last status to patient
     *
     * @param [type] $patient_id
     * @return void
     */
    public function addLastStatus($patient_id)
    {
        $this->data['bc1'] = 'Patient';
        $this->data['bc2'] = 'Add Last Status';

        $patient = $this->patient->getPatient($patient_id);
        $this->data['patient'] = $patient;
        $this->data['patient_category'] = $patient->patient_category;
        $this->data['locations'] = $this->location->getAll(TRUE);

        $pls = $this->patient->getLastStatus($patient_id);


        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $post = $this->input->post();

            $last_status_date = $post['last_status_date'];
            unset($post['last_status_date']);
            if (empty($post['weight'])) unset($post['weight']);
            if (empty($post['bmi'])) unset($post['bmi']);
            if (empty($post['bfi'])) unset($post['bfi']);

            if ($pls) {
                $this->patient->updateLastStatus($patient_id, $post);
            } else {
                $post['patient_id'] = $patient_id;
                $this->patient->addLastStatus($post);
            }

            if (!empty($last_status_date)) {
                $last_status_date = date('Y-m-d', strtotime($last_status_date));
                $this->patient->updatePatient($patient_id, array('last_status_date' => $last_status_date));
            }
            $this->data['success'] = TRUE;
            $pls = $this->patient->getLastStatus($patient_id);
        }

        $this->data['pls'] = $pls;

        $this->load->view('patient/addlaststatus', $this->data);
    }

    /**
     * create last status PDF
     *
     * @param [type] $patient_id
     * @return void
     */
    public function lspdf($patient_id)
    {
        $patient = $this->patient->getPatient($patient_id);
        $this->data['patient'] = $patient;

        $pls = $this->patient->getLastStatus($patient_id);
        $this->data['pls'] = $pls;

        $html = $this->load->view('patient/lspdf', $this->data, true);

        create_mp_ticket($html);
    }

    /**
     * Add patient alerts
     *
     * @return void
     */
    public function addAlert()
    {
        $post = $this->input->post();
        $post['expire'] = date('Y-m-d',  strtotime($post['expire']));
        $post['created'] = date('Y-m-d');
        $this->patient->addAlert($post);
    }

    /**
     * Remove alert
     *
     * @param [type] $alert_id
     * @param [type] $patient_id
     * @return void
     */
    public function del_alert($alert_id, $patient_id)
    {
        $this->patient->removeAlert($alert_id);

        $this->session->set_flashdata('message', 'Alert Removed Successfully');
        redirect("patient/view/$patient_id");
    }

    /**
     * Remove alert and go to visit
     *
     * @param [type] $alert_id
     * @param [type] $patient_id
     * @return void
     */
    public function delAlert($alert_id, $patient_id)
    {
        $this->patient->removeAlert($alert_id);

        $this->session->set_flashdata('message', 'Alert Removed Successfully');
        redirect("patient/addvisit/$patient_id");
    }

    /**
     * Add today prepaid redeems
     *
     * @param [type] $patient_id
     * @return void
     */
    public function todayRedeem($patient_id)
    {
        $date = date('Y-m-d');
        $reedims = $this->order->getRedeemedItems($patient_id, $date);

        $pros = array();
        foreach ($reedims as $r) {
            if (isset($pros[$r->id])) {
                $pros[$r->id]['qty'] += $r->quantity;
            } else {
                $pros[$r->id]['qty'] = $r->quantity;
                $pros[$r->id]['name'] = $r->name;
                $pros[$r->id]['measure_in'] = $r->measure_in;
            }
        }

        $this->data['pros'] = $pros;

        echo $this->load->view("patient/_todayRedeem", $this->data, TRUE);
    }

    /**
     * Show last redeems
     *
     * @param [type] $patient_id
     * @param [type] $date
     * @return void
     */
    public function lastRedeem($patient_id, $date)
    {
        $reedims = $this->order->getRedeemedItems($patient_id, $date);

        $pros = array();
        foreach ($reedims as $r) {
            if (isset($pros[$r->id])) {
                $pros[$r->id]['qty'] += $r->quantity;
            } else {
                $pros[$r->id]['qty'] = $r->quantity;
                $pros[$r->id]['name'] = $r->name;
                $pros[$r->id]['measure_in'] = $r->measure_in;
            }
        }

        $this->data['pros'] = $pros;

        echo $this->load->view("patient/_todayRedeem", $this->data, TRUE);
    }

    /**
     * Update weight
     *
     * @return void
     */
    public function updateIniWght()
    {
        $post = $this->input->post();
        $this->patient->updateVisit($post['id'], array('weight' => $post['weight']));
    }

    /**
     * Send sms alert for chat system
     *
     * @return void
     */
    public function sendSMS()
    {
        $post = $this->input->post();
        $res = SendSMSnew($post['phn'], $post['msg']);
        if ($res) {
            $data = array();
            $data['patient_id'] = $post['id'];
            $data['msg'] = $post['msg'];
            $data['status'] = 1;
            $data['phone'] = $post['phn'];
            $data['created'] = date('Y-m-d H:i:s');
            $this->patient->addSmsLog($data);

            echo 'success';
        } else {
            echo 'error';
        }
    }

    /**
     * Alter prepaid balance
     *
     * @return JSON
     */
    public function adjustPP()
    {
        $post = $this->input->post();
        $pp_item = $this->order->getPPItem($post['id']);
        $adjust = $post['ori'] - $post['val'];

        $result = array();

        if ($pp_item && $adjust > 0) {
            $remaining = $pp_item->remaining + $adjust;
            $location_id = $this->data['location']->id;
            $this->order->updatePrepaidItem($pp_item->id, array('remaining' => $remaining));

            $date = isset($post['date']) ? $post['date'] : date('Y-m-d');
            $ppbs = $this->order->getPPRedeemBrkDwn($pp_item->id, $date);
            $restore = $adjust;

            foreach ($ppbs as $ppb) {
                if ($restore > 0) {
                    if ($restore >= $ppb->quantity) {
                        $this->order->removePPBrkdwn($ppb->id);
                        $restore = $restore - $ppb->quantity;
                    } else {
                        $new_qnty = $ppb->quantity - $restore;
                        $this->order->updatePPBrkdwn($ppb->id, array('quantity' => $new_qnty));
                        $restore = 0;
                    }
                }
            }

            restoreStock($pp_item->pro_id, $adjust);
            $result['status'] = 'success';
            $this->data['patient_id'] = $pp_item->patient_id;
            $result['pp_tbl'] = $this->load->view('patient/_pp_redeem_tbl', $this->data, TRUE);
        } else {
            $result['status'] = 'error';
        }

        echo json_encode($result);
    }

    /**
     * Search patients by first name
     *
     * @return void
     */
    public function getByFname()
    {
        $post = $this->input->post();
        $result = array();

        $patients = $this->patient->getByLname($post['lname']);
        if ($patients) {
            $result['status'] = 'exist';
            $msg = "<h3 style='margin-top:0px;'>Existing Patients with Last Name: " . $post['lname'] . "</h3>";
            foreach ($patients as $patient) {
                if ($patient->dob) {
                    $msg .= "<p>" . $patient->lname . " " . $patient->fname . " (" . date('m/d/Y', strtotime($patient->dob)) . ")  &nbsp;&nbsp;<a href='" . site_url("patient/view/$patient->id") . "' style='color:blue;' target='_blank'>View </a></p>";
                } else {
                    $msg .= "<p>" . $patient->lname . " " . $patient->fname . "  &nbsp;&nbsp;<a href='" . site_url("patient/view/$patient->id") . "' style='color:blue;' target='_blank'>View </a></p>";
                }
            }
            $result['msg'] = $msg;
        } else {
            $result['status'] = 'no';
        }
        echo json_encode($result);
    }

    /**
     * Get SMS log for chat
     *
     * @param [type] $patient_id
     * @return void
     */
    public function getSmsLog($patient_id)
    {
        $logs = $this->patient->getSmsLog($patient_id);
        $html = "";
        if (!empty($logs)) {
            $html = $this->load->view('patient/_sms_chat', array('logs' => $logs), TRUE);
        }

        echo $html;
    }

    /**
     * Remove order item
     *
     * @return void
     */
    public function removeOrderItem()
    {
        $post = $this->input->post();
        $pp = $this->order->getOrderAddedPPItem($post['patient_id'], $post['pro_id'], $post['order_id']);

        if ($pp) {
            $remaining = $pp->remaining - $pp->quantity;
            if ($remaining < 0) $remaining = 0;
            $this->order->updatePrepaidItem($pp->pp_id, array('remaining' => $remaining));

            $this->order->removePPBrkdwn($pp->ppb_id);
        }

        $this->order->removeOrderItem($post['order_id'], $post['pro_id']);

        $result = array();
        $result['status'] = 'success';
        $result['msg'] = 'Item removed from order Successfully';
        $this->data['loi'] = $this->order->getOrderItemsWithNames($post['order_id']);

        $result['oi_tbl'] = $this->load->view('patient/_pp_order_items', $this->data, TRUE);

        echo json_encode($result);
    }

    /**
     * Add order Item
     *
     * @return void
     */
    public function addOrderItem()
    {
        $post = $this->input->post();
        $oi = $this->order->getOrderItm($post['order_id'], $post['pp_item']);
        $pro = $this->product->get($post['pp_item']);

        if ($oi) {
            $data = array();
            $data['quantity'] = $oi->quantity + $post['quantity'];
            $price = ($pro->price * $post['quantity']) + $oi->price;
            $temp['price'] = number_format($price, 2, '.', '');
            $this->order->updateOrderItem($post['order_id'], $post['pp_item'], $data);
        } else {
            $temp = array();
            $temp['order_id'] = $post['order_id'];
            $temp['product_id'] = $post['pp_item'];
            $temp['quantity'] = $post['quantity'];
            $price = $pro->price * $post['quantity'];
            $temp['price'] = number_format($price, 2, '.', '');
            $this->order->addOrderItem($temp);
        }

        $new_p_pp = array();
        $new_p_pp['patient_id'] = $post['patient_id'];
        $new_p_pp['location_id'] = $this->data['location']->id;
        $new_p_pp['pro_id'] = $post['pp_item'];
        $new_p_pp['type'] = 'add';

        $new_p_pp['quantity'] = $post['quantity'] * $pro->quantity;
        $new_p_pp['add_type'] = 'order';
        $new_p_pp['order_id'] = $post['order_id'];
        $new_p_pp['created'] = $post['order_date'];
        addPrepaidItem($new_p_pp);

        $result = array();
        $result['status'] = 'success';
        $result['msg'] = 'Item added to order Successfully';
        $this->data['loi'] = $this->order->getOrderItemsWithNames($post['order_id']);

        $result['oi_tbl'] = $this->load->view('patient/_pp_order_items', $this->data, TRUE);

        echo json_encode($result);
    }

    /**
     * Get freezed patients
     *
     * @return void
     */
    public function freeze()
    {
        $this->data['bc1'] = 'Freeze';
        $this->data['bc2'] = 'Patients';
        $this->data['patients'] = $this->patient->getAllFreezed();
        $this->load->view("patient/freeze", $this->data);
    }

    /**
     * Add patient to freeze
     *
     * @return void
     */
    public function addFreeze()
    {
        $post = $this->input->post();
        $id = $post['id'];
        $fr = $post['fr'];
        $this->patient->updatePatient($id, array('freezed' => 1, 'freezed_reason' => $fr, 'sms' => 0));
        $result = array();
        $this->data['patients'] = $this->patient->getAllFreezed();
        $result['table'] = $this->load->view("patient/_freeze_table", $this->data, TRUE);

        echo json_encode($result);
    }

    /**
     * Remove patient from freeze
     *
     * @return void
     */
    public function removeFreeze()
    {
        $post = $this->input->post();
        $id = $post['id'];
        $this->patient->updatePatient($id, array('freezed' => 0, 'freezed_reason' => NULL, 'sms' => 1));
        $result = array();
        $this->data['patients'] = $this->patient->getAllFreezed();
        $result['table'] = $this->load->view("patient/_freeze_table", $this->data, TRUE);

        echo json_encode($result);
    }

    /**
     * Download past six weeks visits
     *
     * @param [type] $patient_id
     * @return void
     */
    public function pastSixWeek($patient_id)
    {

        $lastOrder = $this->order->geLastOrder($patient_id);

        $lastRestart = $this->order->getLastRestart($lastOrder->patient_id);
        if ($lastRestart && $lastRestart->date > date('Y-m-d', strtotime($lastOrder->created))) $lastRestart = NULL;

        //get last 6 visits
        $latest_visits = $this->patient->getVisitsForVisitPage($lastOrder->patient_id, date('Y-m-d', strtotime($lastOrder->created)), $lastRestart);
        $visits = array_reverse($latest_visits);

        if ($lastRestart) {
            $count = $this->patient->getVisitCountSinceLastRestart($lastOrder->patient_id, date('Y-m-d', strtotime($lastOrder->created)), $lastRestart);
            $lastRestart->count = $count > 6 ? $count - 5 : 1;
        }
        $this->data['lastRestart'] = $lastRestart;

        $this->data['visits'] = $latest_visits;


        $headers = ['Visit #', 'Phase', '# of Wks', 'Visit Date', 'Loc', 'Weight', 'BFI', 'BMI', 'BP', 'Last 2 Visit', 'Total Wt', 'Days Over Schld Visit'];

        $rows = array();
        $last_weight = 0;
        $tr = 0;
        $total_weight_diff = 0;
        $pvdate = FALSE;
        $vn = 0;

        for ($i = 0; $i < 6; $i++) {
            if (isset($visits[$i])) {
                $v = $visits[$i];
                $med_days = $v->is_med == 1 ? $v->med_days : $v->no_med_days;
                $coming_after = "-";
                if ($pvdate) {
                    $d1 = new DateTime(date('Y-m-d', strtotime($pvdate->visit_date)));
                    $d2 = new DateTime(date('Y-m-d', strtotime($v->visit_date)));
                    $gap = $d1->diff($d2)->format("%a");
                    $prev_med_days = $pvdate->is_med == 1 ? $pvdate->med_days : $pvdate->no_med_days;
                    $gap = $gap - $prev_med_days;
                    $coming_after = $gap . " days";
                }
                $pvdate = $v;
                $vt = getTurnsForDays($med_days);

                if ($i == 0) {
                    $vn = ($lastRestart) ? $lastRestart->count : $v->visit;
                }

                $temp = [];
                $temp['vn'] = $vn;
                $temp['phase'] = getPhaseByVisit(date('Y-m-d', strtotime($v->visit_date)), $patient_id);
                $temp['now'] = $vt;
                $temp['vd'] = date('m/d/Y', strtotime($v->visit_date));
                $temp['loc'] = $v->abbr;
                $temp['weight'] = $v->weight;
                $temp['bfi'] = $v->bfi;
                $temp['bmi'] = $v->bmi;
                $temp['bp'] = $v->bp;

                $diff = floatval($v->weight - $last_weight);
                $diff = round($diff, 1);

                $temp['l2v'] =  $i == 0 ? 0 : $diff;

                if ($i == 0) {
                    if ($v->visit > 1 && isset($first_visit)) {
                        $total_weight_diff = floatval($v->weight - $first_visit->weight);
                    } else {
                        $total_weight_diff = 0;
                    }
                } else {
                    $total_weight_diff = $total_weight_diff + $diff;
                }

                $temp['tw'] = round($total_weight_diff, 1);
                $temp['dosv'] = $coming_after;

                array_push($rows, $temp);

                $last_weight = $v->weight;
                $vn++;
            }
        }

        $fp = fopen('php://output', 'w');
        if ($fp && $rows && !empty($rows)) {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="past_6_visits_"' . $patient_id . '".csv"');
            header('Pragma: no-cache');
            header('Expires: 0');
            fputcsv($fp, $headers);
            foreach ($rows as $r) {
                fputcsv($fp, array_values($r));
            }
            exit();
        } else {
            header("Location: " . site_url("patient/view/$patient_id"));
        }


        die($patient_id);
    }
}
