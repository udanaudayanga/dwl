<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function assets($path) {
    return site_url() . 'assets/' . $path;
}

function getStockCategoryArray() {
    $CI = & get_instance();
    $CI->load->model('Product_model', 'product');

    $products = $CI->product->getStockCatPros();

    $pros = array();
    foreach ($products as $pro) {
        $pros[$pro->cid]['name'] = $pro->cname;
        $pros[$pro->cid]['pros'][] = array('id' => $pro->id, 'name' => $pro->name);
    }

    return $pros;
}

function getAllProWithCats() {
    $CI = & get_instance();
    $CI->load->model('Product_model', 'product');

    $products = $CI->product->getAllProWithCats();

    $pros = array();
    foreach ($products as $pro) {
        $pros[$pro->cid]['name'] = $pro->cname;
        $pros[$pro->cid]['pros'][] = array('id' => $pro->id, 'name' => $pro->name);
    }

    return $pros;
}

function getLocation($location_id) {
    $CI = & get_instance();
    $CI->load->model('Location_model', 'location');

    return $CI->location->get($location_id);
}

function getStateAbbr($state_id) {
    $CI = & get_instance();
    $CI->load->model('Patient_model', 'patient');

    $state = $CI->patient->getState($state_id);

    return $state ? $state->abbr : '';
}

function getProduct($id) {
    $CI = & get_instance();
    $CI->load->model('Product_model', 'product');
    return $CI->product->get($id);
}

function addProStock($pro_id, $location_id, $qnty) {
    $CI = & get_instance();
    $CI->load->model('Product_model', 'product');
    $product = $CI->product->get($pro_id);
    if ($product->is_stock) {
        $ex_stock = $CI->product->getProlocStock($pro_id, $location_id);
        if ($ex_stock) {
            $new_stock = $ex_stock->quantity + $qnty;
            $CI->product->updateProLocStock($pro_id, $location_id, $new_stock);
        } else {
            $newStock = array('product_id' => $pro_id, 'location_id' => $location_id, 'quantity' => $qnty);
            $CI->product->addProLocStock($newStock);
        }
    }
}

function convert_number_to_words($number) {

    $hyphen = '-';
    $conjunction = ' and ';
    $separator = ', ';
    $negative = 'negative ';
    $decimal = ' point ';
    $dictionary = array(
        0 => 'zero',
        1 => 'one',
        2 => 'two',
        3 => 'three',
        4 => 'four',
        5 => 'five',
        6 => 'six',
        7 => 'seven',
        8 => 'eight',
        9 => 'nine',
        10 => 'ten',
        11 => 'eleven',
        12 => 'twelve',
        13 => 'thirteen',
        14 => 'fourteen',
        15 => 'fifteen',
        16 => 'sixteen',
        17 => 'seventeen',
        18 => 'eighteen',
        19 => 'nineteen',
        20 => 'twenty',
        30 => 'thirty',
        40 => 'fourty',
        50 => 'fifty',
        60 => 'sixty',
        70 => 'seventy',
        80 => 'eighty',
        90 => 'ninety',
        100 => 'hundred',
        1000 => 'thousand',
        1000000 => 'million',
        1000000000 => 'billion',
        1000000000000 => 'trillion',
        1000000000000000 => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens = ((int) ($number / 10)) * 10;
            $units = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}

function dateIsInBetween(DateTime $from, DateTime $to, DateTime $subject) {
    return $subject->getTimestamp() >= $from->getTimestamp() && $subject->getTimestamp() < $to->getTimestamp() ? true : false;
}

function getWeekStartDate($date = null) {
    if ($date) {
        $day = date('w', strtotime($date));
        $day = $day - 1;
        $week_start = date('Y-m-d', strtotime($date . ' -' . $day . ' days'));
        return $week_start;
    } else {
        return date('Y-m-d', strtotime('monday this week'));
    }
}

function getWeekDatys($startDate) {
    $dates = array();
    $dates[1] = $startDate;
    for ($i = 1; $i < 6; $i++) {
        $dates[$i + 1] = date('Y-m-d', strtotime("+" . $i . " days", strtotime($startDate)));
    }

    return $dates;
}

function dateFormat($date) {
    $new_date = "";
    $date = strtotime($date);
    if (date('i', $date) == 00) {
        $new_date = date('ga', $date);
    } else {
        $new_date = date('g.ia', $date);
    }

    return substr($new_date, 0, -1);
}

function manageCycle($patient_id) {
    $CI = & get_instance();
    $CI->load->model('Evaluate_model', 'evaluate');
    $latest_phase = $CI->evaluate->getLastPhase($patient_id);


    if ($latest_phase) {
        if ($latest_phase->end && $latest_phase->end < date('Y-m-d')) {
            addCyclePhase($patient_id, $latest_phase, $latest_phase->end);
        } else {
            $last_visit = $CI->patient->getLatestVisit($patient_id);
            $turns = getNoOfTurns($latest_phase);

            if ($turns >= 12) {
                $days = $last_visit->is_med == 1 ? $last_visit->med_days : $last_visit->no_med_days;
                $endDate = date('Y-m-d', strtotime("+$days days", strtotime($last_visit->visit_date)));
                $CI->evaluate->updatePhase($latest_phase->id, array('end' => $endDate));
                addCyclePhase($patient_id, $latest_phase, $endDate);
            }
        }
    } else {
        addCyclePhase($patient_id, NULL, NULL);
    }
}

function addCyclePhase($patient_id, $lastPhase = null, $start = null) {
    $CI = & get_instance();
    $CI->load->model('Evaluate_model', 'evaluate');

    $nc = array();
    $nc['patient_id'] = $patient_id;
    $nc['start'] = $start ? $start : date('Y-m-d');

    $last = $lastPhase ? $lastPhase : $CI->evaluate->getLastPhase($patient_id);


    if ($last) {
        $nc['start'] = $last->end > $nc['start'] ? $last->end : $nc['start'];
        $nc['phase'] = $last->phase + 1;
    } else {
        $nc['phase'] = 1;
    }

    $nc['created'] = date('Y-m-d H:i:s');
    $phase_id = $CI->evaluate->addPhase($nc);
    return $phase_id;
}

function getNoOfTurnsForPeriod($patient_id, $start, $end = null) {
    $CI = & get_instance();
    $CI->load->model('Patient_model', 'patient');
    $end = $end ? $end : date('Y-m-d');
    $visits = $CI->patient->getPatientVisitsInPeriod($patient_id, $start, $end);
    $turns = 0;

    foreach ($visits as $visit) {
        $days = $visit->is_med == 1 ? $visit->med_days : $visit->no_med_days;

        $turns += getTurnsForDays($days);
    }

    return $turns;
}

function getNoOfTurns($phase) {
    $CI = & get_instance();
    $CI->load->model('Patient_model', 'patient');
    $end = $phase->end ? $phase->end : date('Y-m-d');
    $visits = $CI->patient->getPatientVisitsInPeriod($phase->patient_id, $phase->start, $end);
    $turns = 0;

    foreach ($visits as $visit) {
        $days = $visit->is_med == 1 ? $visit->med_days : $visit->no_med_days;

        $turns += getTurnsForDays($days);
    }

    return $turns;
}

function getWkForDays($days) {
    return ($days % 7 == 0) ? $days / 7 : 0;
}

function getTurnsForDays($days) {
    $turns = 0;
    if ($days > 0 && $days < 14) {
        $turns = 1;
    } elseif (14 <= $days && $days < 21) {
        $turns = 2;
    } elseif (21 <= $days && $days < 28) {
        $turns = 3;
    } elseif (28 <= $days && $days < 35) {
        $turns = 4;
    }

    return $turns;
}

function getSupPacksForDays($days) {
    $CI = & get_instance();
    $spacks = $CI->config->item('spacks');

    $packs = array();
    if ($days > 0 && $days < 14) {
        $packs[$spacks[1]] = 1;
    } elseif (14 <= $days && $days < 21) {
        $packs[$spacks[2]] = 1;
    } elseif (21 <= $days && $days < 28) {
        $packs[$spacks[1]] = 1;
        $packs[$spacks[2]] = 1;
    } elseif (28 <= $days && $days < 35) {
        $packs[$spacks[4]] = 1;
    } elseif (35 <= $days && $days < 42) {
        $packs[$spacks[1]] = 1;
        $packs[$spacks[4]] = 1;
    }

    return $packs;
}

function secToHM($secs) {
    $hours = floor($secs / 3600);
    $minutes = floor(($secs / 60) % 60);

    return "$hours.$minutes";
}

function isPPRedeemAllow($patient_id, $days) {
    $CI = & get_instance();
    $CI->load->model('Evaluate_model', 'evaluate');
    $latest_phase = $CI->evaluate->getLastPhase($patient_id);
    $result = array();

    if ($latest_phase && $latest_phase->phase == 2) {
        $existing = getNoOfTurns($latest_phase);
        $new_turns = getTurnsForDays($days);

        if (($existing + $new_turns) <= 12) {
            $result['status'] = TRUE;
        } elseif ($existing == 12) {
            $result['status'] = FALSE;
            $result['msg'] = 'Patient moving to adaptive phase.';
        } elseif (($existing + $new_turns) > 12) {
            $allow = 12 - $existing;
            $result['status'] = FALSE;
            $adays = $allow * 7;
            $result['msg'] = "only $adays days allowed to end second 12wk phase.";
        }
    } else {
        $result['status'] = TRUE;
    }

    return $result;
}

function getPhaseByVisit($date, $patient_id) {
    $CI = & get_instance();
    $CI->load->model('Evaluate_model', 'evaluate');

    $phase = $CI->evaluate->getPhaseByVistDate($date, $patient_id);
    $text = '';
    if ($phase) {
        $text = "12." . $phase->phase;
    } else {
        $text = "-";
    }

    return $text;
}

function getPhaseObjByVisit($date, $patient_id) {
    $CI = & get_instance();
    $CI->load->model('Evaluate_model', 'evaluate');

    $phase = $CI->evaluate->getPhaseByVistDate($date, $patient_id);

    return $phase;
}

function getMedName($id) {
    $CI = & get_instance();
    $ids = $CI->config->item('meds_for_id');
    return isset($ids[$id]) ? $ids[$id] : '-';
}

function getUser($id) {
    $CI = & get_instance();
    $CI->load->model('User_model', 'user');
    return $CI->user->get($id);
}

function crypto_rand_secure($min, $max) {
    $range = $max - $min;
    if ($range < 1)
        return $min; // not so random...
    $log = ceil(log($range, 2));
    $bytes = (int) ($log / 8) + 1; // length in bytes
    $bits = (int) $log + 1; // length in bits
    $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
    do {
        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
        $rnd = $rnd & $filter; // discard irrelevant bits
    } while ($rnd > $range);
    return $min + $rnd;
}

function getToken($length) {
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet .= "0123456789";
    $max = strlen($codeAlphabet); // edited

    for ($i = 0; $i < $length; $i++) {
        $token .= $codeAlphabet[crypto_rand_secure(0, $max - 1)];
    }

    return $token;
}

function getPromoCoupon() {
    $CI = & get_instance();
    $CI->load->model('marketing_model', 'marketing');
    $coupon = getToken(6);
    if ($CI->marketing->isCouponExist($coupon)) {
        return getPromoCoupon();
    } else {
        return $coupon;
    }
}

function updateCoupon($coupon, $pid) {
    $CI = & get_instance();
    $CI->load->model('Promos_model', 'promos');
    $CI->load->model('marketing_model', 'marketing');
    $CI->load->model('Product_model', 'product');

    $proCou = $CI->marketing->getCoupon($coupon);
    if (!$proCou)
        $proCou = $CI->promos->getGenCoupon($coupon);

    if (!$proCou)
        exit;

    $promo = $CI->promos->getPromo($proCou->promo_id);

    if ($promo) {
        $CI->session->unset_userdata("cd_" . $pid);
        $discount = array();
        if ($promo->type == 1) {
            $promoXML = new SimpleXMLElement($promo->logic);

            $value = (float) $promoXML->offer['value'];

            if ($promoXML->offer['type'] == 'flat') {
                $discount['discount'] = $value;
            } elseif ($promoXML->offer['type'] == 'percentage') {
                $total = $CI->cart->total();
                $discount['discount'] = $total * ($value / 100);
            }
            $CI->session->set_userdata("cd_" . $pid, $discount);
        } elseif ($promo->type == 2) {
            $promoXML = new SimpleXMLElement($promo->logic);
            $pro_id = (int) $promoXML->condition['pro_id'];
            $type = (string) $promoXML->offer['type'];
            $value = (float) $promoXML->offer['value'];

            foreach ($CI->cart->contents() as $item) {
                if ($item['id'] == $pro_id) {
                    if ($promoXML->offer['type'] == 'flat') {
                        $discount['discount'] = $value * $item['qty'];
                    } elseif ($promoXML->offer['type'] == 'percentage') {
                        $product = $CI->product->get($pro_id);
                        $dis = ($product->price) * ($value / 100);
                        $discount['discount'] = $dis * $item['qty'];
                    }
                }
            }

            $CI->session->set_userdata("cd_" . $pid, $discount);
        } elseif ($promo->type == 3) {
            $promoXML = new SimpleXMLElement($promo->logic);
            $pro_id = (int) $promoXML->condition['pro_id'];
            $offer = (float) $promoXML->offer['value'];

            foreach ($CI->cart->contents() as $item) {
                if ($item['id'] == $pro_id && $item['qty'] > 1) {
                    $product = $CI->product->get($pro_id);
                    $discount['discount'] = $product->price;
                }
            }

            $CI->session->set_userdata("cd_" . $pid, $discount);
        } elseif ($promo->type == 4) {
            $promoXML = new SimpleXMLElement($promo->logic);
            $pro_id = (int) $promoXML->condition['pro_id'];
            $qty = (int) $promoXML->condition['qty'];
            $value = (float) $promoXML->offer['value'];

            foreach ($CI->cart->contents() as $item) {
                if ($item['id'] == $pro_id && $item['qty'] > $qty) {
                    $product = $CI->product->get($pro_id);
                    $dis = ($product->price) * ($value / 100);
                    $discount['discount'] = $dis;
                }
            }

            $CI->session->set_userdata("cd_" . $pid, $discount);
        } elseif ($promo->type == 5) {
            $promoXML = new SimpleXMLElement($promo->logic);
            $pro1 = (int) $promoXML->condition['pro1'];
            $pro2 = (int) $promoXML->condition['pro2'];
            $dis1 = (float) $promoXML->offer['pro1'];
            $dis2 = (float) $promoXML->offer['pro2'];

            $com1 = $com2 = FALSE;
            $discount['discount'] = 0;

            foreach ($CI->cart->contents() as $item) {
                if ($item['id'] == $pro1) {
                    $prod1 = $CI->product->get($pro1);
                    $discount['discount'] += $prod1->price * ($dis1 / 100);
                    $com1 = TRUE;
                }

                if ($item['id'] == $pro2) {
                    $prod2 = $CI->product->get($pro2);
                    $discount['discount'] += $prod2->price * ($dis2 / 100);
                    $com2 = TRUE;
                }
            }

            if ($com1 && $com2) {
                $CI->session->set_userdata("cd_" . $pid, $discount);
            }
        } elseif ($promo->type == 6) {
            $promoXML = new SimpleXMLElement($promo->logic);
            $pro1 = (int) $promoXML->condition['pro1'];
            $pro2 = (int) $promoXML->condition['pro2'];
            $pro3 = (int) $promoXML->condition['pro3'];
            $dis1 = (int) $promoXML->offer['pro1'];
            $dis2 = (int) $promoXML->offer['pro2'];
            $dis3 = (int) $promoXML->offer['pro3'];

            $com1 = $com2 = $com3 = FALSE;
            $discount['discount'] = 0;

            foreach ($CI->cart->contents() as $item) {
                if ($item['id'] == $pro1) {
                    $prod1 = $CI->product->get($pro1);
                    $discount['discount'] += $prod1->price * ($dis1 / 100);
                    $com1 = TRUE;
                }

                if ($item['id'] == $pro2) {
                    $prod2 = $CI->product->get($pro2);
                    $discount['discount'] += $prod2->price * ($dis2 / 100);
                    $com2 = TRUE;
                }

                if ($item['id'] == $pro3) {
                    $prod3 = $CI->product->get($pro3);
                    $discount['discount'] += $prod3->price * ($dis3 / 100);
                    $com3 = TRUE;
                }
            }

            if ($com1 && $com2 && $com3) {
                $CI->session->set_userdata("cd_" . $pid, $discount);
            }
        }
    }
}

function applyCoupon($proCou) {
    $CI = & get_instance();
    $CI->load->model('Promos_model', 'promos');
    $CI->load->model('Product_model', 'product');
    $promo = $CI->promos->getPromo($proCou->promo_id);

    $result = array();

    if ($promo) {
        if ($promo->start > date('Y-m-d')) {
            $result['status'] = 'error';
            $result['error'] = "Promotion has not started Yet.";
        } elseif ($promo->end < date('Y-m-d')) {
            $result['status'] = 'error';
            $result['error'] = "Promotion has expired.";
        } else {
            $discount = array();
            if ($promo->type == 1) {
                $promoXML = new SimpleXMLElement($promo->logic);
                $result['status'] = 'success';
                $value = (float) $promoXML->offer['value'];

                if ($promoXML->offer['type'] == 'flat') {
                    $discount['discount'] = $value;
                } elseif ($promoXML->offer['type'] == 'percentage') {
                    $total = $CI->cart->total();
                    $discount['discount'] = $total * ($value / 100);
                }

                $CI->session->set_userdata("cd_" . $proCou->patient_id, $discount);
            } elseif ($promo->type == 2) {
                $promoXML = new SimpleXMLElement($promo->logic);
                $pro_id = (int) $promoXML->condition['pro_id'];
                $type = (string) $promoXML->offer['type'];
                $value = (float) $promoXML->offer['value'];

                foreach ($CI->cart->contents() as $item) {
                    if ($item['id'] == $pro_id) {
                        if ($promoXML->offer['type'] == 'flat') {
                            $discount['discount'] = $value * $item['qty'];
                        } elseif ($promoXML->offer['type'] == 'percentage') {
                            $product = $CI->product->get($pro_id);
                            $dis = ($product->price) * ($value / 100);
                            $discount['discount'] = $dis * $item['qty'];
                        }
                    }
                }
                $result['status'] = 'success';
                $CI->session->set_userdata("cd_" . $proCou->patient_id, $discount);
            } elseif ($promo->type == 3) {
                $promoXML = new SimpleXMLElement($promo->logic);
                $pro_id = (int) $promoXML->condition['pro_id'];
                $offer = (float) $promoXML->offer['value'];

                foreach ($CI->cart->contents() as $item) {
                    if ($item['id'] == $pro_id && $item['qty'] > 1) {
                        $product = $CI->product->get($pro_id);
                        $discount['discount'] = $product->price;
                    }
                }
                $result['status'] = 'success';
                $CI->session->set_userdata("cd_" . $proCou->patient_id, $discount);
            } elseif ($promo->type == 4) {
                $promoXML = new SimpleXMLElement($promo->logic);
                $pro_id = (int) $promoXML->condition['pro_id'];
                $qty = (int) $promoXML->condition['qty'];
                $value = (float) $promoXML->offer['value'];

                foreach ($CI->cart->contents() as $item) {
                    if ($item['id'] == $pro_id && $item['qty'] > $qty) {
                        $product = $CI->product->get($pro_id);
                        $dis = ($product->price) * ($value / 100);
                        $discount['discount'] = $dis;
                    }
                }
                $result['status'] = 'success';
                $CI->session->set_userdata("cd_" . $proCou->patient_id, $discount);
            } elseif ($promo->type == 5) {
                $promoXML = new SimpleXMLElement($promo->logic);
                $pro1 = (int) $promoXML->condition['pro1'];
                $pro2 = (int) $promoXML->condition['pro2'];
                $dis1 = (float) $promoXML->offer['pro1'];
                $dis2 = (float) $promoXML->offer['pro2'];

                $com1 = $com2 = FALSE;
                $discount['discount'] = 0;

                foreach ($CI->cart->contents() as $item) {
                    if ($item['id'] == $pro1) {
                        $prod1 = $CI->product->get($pro1);
                        $discount['discount'] += $prod1->price * ($dis1 / 100);
                        $com1 = TRUE;
                    }

                    if ($item['id'] == $pro2) {
                        $prod2 = $CI->product->get($pro2);
                        $discount['discount'] += $prod2->price * ($dis2 / 100);
                        $com2 = TRUE;
                    }
                }

                if ($com1 && $com2) {
                    $CI->session->set_userdata("cd_" . $proCou->patient_id, $discount);
                }

                $result['status'] = 'success';
            } elseif ($promo->type == 6) {
                $promoXML = new SimpleXMLElement($promo->logic);
                $pro1 = (int) $promoXML->condition['pro1'];
                $pro2 = (int) $promoXML->condition['pro2'];
                $pro3 = (int) $promoXML->condition['pro3'];
                $dis1 = (float) $promoXML->offer['pro1'];
                $dis2 = (float) $promoXML->offer['pro2'];
                $dis3 = (float) $promoXML->offer['pro3'];

                $com1 = $com2 = $com3 = FALSE;
                $discount['discount'] = 0;

                foreach ($CI->cart->contents() as $item) {
                    if ($item['id'] == $pro1) {
                        $prod1 = $CI->product->get($pro1);
                        $discount['discount'] += $prod1->price * ($dis1 / 100);
                        $com1 = TRUE;
                    }

                    if ($item['id'] == $pro2) {
                        $prod2 = $CI->product->get($pro2);
                        $discount['discount'] += $prod2->price * ($dis2 / 100);
                        $com2 = TRUE;
                    }

                    if ($item['id'] == $pro3) {
                        $prod3 = $CI->product->get($pro3);
                        $discount['discount'] += $prod3->price * ($dis3 / 100);
                        $com3 = TRUE;
                    }
                }

                if ($com1 && $com2 && $com3) {
                    $CI->session->set_userdata("cd_" . $proCou->patient_id, $discount);
                }

                $result['status'] = 'success';
            }
        }
    } else {
        $result['status'] = 'error';
        $result['error'] = "No Promotion Found";
    }
    return $result;
}

function markQueue($patient) {
    $CI = & get_instance();
    $CI->load->model('Queue_model', 'queue');
    $queue = $CI->queue->getQueue($patient->id);

    if (!$queue) {
        $queue = $CI->queue->getQueByNames($patient);
    }

    if ($queue)
        $CI->queue->update($queue->id, array('done' => 1));
}

function getPatientStatus($status) {
    $ps = '';
    switch ($status) {
        case 0:
            $ps = 'NEW';
            break;
        case 1:
            $ps = '6';
            break;
        case 2:
            $ps = '>6';
            break;
        case 3:
            $ps = '>12';
            break;

        default:
            break;
    }
    return $ps;
}

function getPhnFormat($phn) {
    return "(" . substr($phn, 0, 3) . ") " . substr($phn, 3, 3) . "-" . substr($phn, 6);
}

function humanizeDateDiffference($otherDate = null, $offset = null) {
    $now = strtotime(date('Y-m-d H:i:s'));
    $otherDate = strtotime($otherDate);
    if ($otherDate != null) {
        $offset = $now - $otherDate;
    }
    if ($offset != null) {
        $deltaS = $offset % 60;
        $offset /= 60;
        $deltaM = $offset % 60;
        $offset /= 60;
        $deltaH = $offset % 24;
        $offset /= 24;
        $deltaD = ($offset > 1) ? ceil($offset) : $offset;
    } else {
        throw new Exception("Must supply otherdate or offset (from now)");
    }
    if ($deltaD > 1) {
        if ($deltaD > 365) {
            $years = ceil($deltaD / 365);
            if ($years == 1) {
                return "last year";
            } else {
                return "<br>$years years ago";
            }
        }
        if ($deltaD > 6) {
            return date('d-M', strtotime("$deltaD days ago"));
        }
        return "$deltaD days ago";
    }
    if ($deltaD == 1) {
        return "Yesterday";
    }
    if ($deltaH == 1) {
        return "last hour";
    }
    if ($deltaM == 1) {
        return "last minute";
    }
    if ($deltaH > 0) {
        return $deltaH . " hours ago";
    }
    if ($deltaM > 0) {
        return $deltaM . " minutes ago";
    } else {
        return "few seconds ago";
    }
}

 function facebook_time_ago($timestamp)  
 {  
      $time_ago = strtotime($timestamp);  
      $current_time = time();  
      $time_difference = $current_time - $time_ago;  
      $seconds = $time_difference;  
      $minutes      = round($seconds / 60 );           // value 60 is seconds  
      $hours           = round($seconds / 3600);           //value 3600 is 60 minutes * 60 sec  
      $days          = round($seconds / 86400);          //86400 = 24 * 60 * 60;  
      $weeks          = round($seconds / 604800);          // 7*24*60*60;  
      $months          = round($seconds / 2629440);     //((365+365+365+365+366)/5/12)*24*60*60  
      $years          = round($seconds / 31553280);     //(365+365+365+365+366)/5 * 24 * 60 * 60  
      if($seconds <= 60)  
      {  
     return "Just Now";  
   }  
      else if($minutes <=60)  
      {  
     if($minutes==1)  
           {  
       return "one minute ago";  
     }  
     else  
           {  
       return "$minutes minutes ago";  
     }  
   }  
      else if($hours <=24)  
      {  
     if($hours==1)  
           {  
       return "an hour ago";  
     }  
           else  
           {  
       return "$hours hrs ago";  
     }  
   }  
      else if($days <= 7)  
      {  
     if($days==1)  
           {  
       return "yesterday";  
     }  
           else  
           {  
       return "$days days ago";  
     }  
   }  
      else if($weeks <= 4.3) //4.3 == 52/12  
      {  
     if($weeks==1)  
           {  
       return "a week ago";  
     }  
           else  
           {  
       return "$weeks weeks ago";  
     }  
   }  
       else if($months <=12)  
      {  
     if($months==1)  
           {  
       return "a month ago";  
     }  
           else  
           {  
       return "$months months ago";  
     }  
   }  
      else  
      {  
     if($years==1)  
           {  
       return "one year ago";  
     }  
           else  
           {  
       return "$years years ago";  
     }  
   }  
 }  
 
 function sendQueueMsg($patient_id,$type)
 {
     $CI = & get_instance();
     $CI->load->model('Patient_model', 'patient');
     $CI->load->model('Queue_model', 'queue');
     
     $patient = $CI->patient->getPatient($patient_id);
     
     if($patient && !empty($patient->phone))
     {
        $qms = $CI->queue->getQMByDate(date('Y-m-d'),$type);
        if($qms && !empty($qms))
        {
            foreach($qms as $qm)
            {
                if($qm->type==1)
                {
                    $media = null;
                    $msg = "Hi ".$patient->fname.",\n".$qm->msg;
                    if(!empty($qm->photo))$media = array('mediaUrl'=> site_url("/assets/upload/queue_alerts/$qm->photo"));
                    SendSMSnew($patient->phone, $msg,$media);
                    
                    $data = array();
                    $data['patient_id'] = $patient->id;
                    $data['msg'] = $msg;
                    $data['status'] = 1;
                    $data['phone'] = $patient->phone;
                    $data['created'] = date('Y-m-d H:i:s');
                    $CI->patient->addSmsLog($data);
                }
                else 
                {
                    $visit = $CI->patient->getLastVisit($patient_id);
                    if($visit->visit == 1)
                    {
                        $media = null;
                        $msg = "Hi ".$patient->fname.",\n".$qm->msg;
                        if(!empty($qm->photo))$media = array('mediaUrl'=> site_url("/assets/upload/queue_alerts/$qm->photo"));
                        SendSMSnew($patient->phone, $msg,$media);
                        
                        $data = array();
                        $data['patient_id'] = $patient->id;
                        $data['msg'] = $msg;
                        $data['status'] = 1;
                        $data['phone'] = $patient->phone;
                        $data['created'] = date('Y-m-d H:i:s');
                        $CI->patient->addSmsLog($data);
                    }
                }
            }
        }
     }
 }
 
 function getPPCategoryArray() {
    $CI = & get_instance();
    $CI->load->model('Product_model', 'product');

    $products = $CI->product->getPPCatPros();

    $pros = array();
    foreach ($products as $pro) {
        $pros[$pro->cid]['name'] = $pro->cname;
        $pros[$pro->cid]['pros'][] = array('id' => $pro->id, 'name' => $pro->name);
    }

    return $pros;
}
 
//Add activity log
function addActivity($uid,$event)
{
    $CI = & get_instance();
    $CI->load->model('Util_model', 'util');

    $data = array();
    $data['uid'] = $uid;
    $data['event'] = $event;
    $data['remote_ip'] = $_SERVER['REMOTE_ADDR'];

    $CI->util->addActivityLog($data);
}

function getNoOfTurnsForVisits($visits)
{
    $turns = 0;

    foreach ($visits as $visit) {
        $days = $visit->is_med == 1 ? $visit->med_days : $visit->no_med_days;

        $turns += getTurnsForDays($days);
    }

    return $turns;
}

 