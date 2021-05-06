<?php

/**
 * @author Udana Udayanga <udana@udana.lk>
 */
class Pdmp extends CI_Controller
{
    public function gen($loc,$date)
    {
        
        $this->load->model("Cron_model", "cron");
        $this->load->helper('file');
        $this->load->library('zip');
        
        $start = $end = $date;
        //$end = '2018-11-18';
        $loc_id = $loc;
        echo "Generating: ".$start." - ".$end." - ".$loc_id."<br>";
        
        $dea = array(2=>'FN4356514',3=>'FN4321080',4=>'FN5029005');
        $pres = $this->cron->getForPDMP($loc_id,$start,$end);
        
        
        $TH01 = "4.2A";
        $TH02 = strtoupper(date("ndYDHi"));
        $TH05 = date("Ymd");
        $TH06 = date("Hi");
        $TH07 = "P"; //T=Test, P= Production
        
        $IS01 = "7274128208";
        $IS02 = "DOCTORS WEIGHT LOSS CENTER";
        $IS03 = empty($pres)? date("#Ymd#",strtotime($start))."-".date("#Ymd#", strtotime($end)): "*";
        
        $PHA01 = "1699760843";
        $PHA03 = $dea[$loc_id];
        $PHA08 = "FL";
        $PHA13 = "ME44026";
        
        $PAT02 = "03";
        $PAT03 = "";
        
        
        $ds = "TH*$TH01*$TH02*01**$TH05*$TH06*$TH07**~~";
        $ds .= "IS*$IS01*$IS02*$IS03~";
        
        $ds .= "PHA*$PHA01**$PHA03*****$PHA08*****$PHA13~";
        
        $tpc = 1;
        
        $ndc = array(19=>'61939081010',20=>'00527131010',21=>'00527174210',22=>'10702004401');
        
                
        foreach($pres as $pr)
        {
            $PAT03 = $pr->patient_id;
            $PAT07 = strtoupper($pr->lname);
            $PAT08 = strtoupper($pr->fname);
            $PAT12 = strtoupper($pr->address);
            $PAT14 = strtoupper($pr->city);
            $PAT15 = strtoupper($pr->abbr);
            $PAT16 = $pr->zip;
            $PAT17 = $pr->phone;
            $PAT18 = date("Ymd",strtotime($pr->dob));
            $PAT19 = $pr->gender == 1 ? "M":"F";
            
            
            $DSP01 = "00";
            $DSP02 = $pr->prescription_no;
            $DSP03 = date("Ymd",strtotime($pr->ori_pres_date));
            $DSP04 = 4;
            $DSP05 = date("Ymd",strtotime($pr->visit_date));
            $DSP06 = $pr->refill > 0 ? $pr->refill - 1: 0;
            $DSP07 = "01";
            $DSP11 = "03";
            $DSP16 = "01";
            
            $PRE02 = $dea[$loc_id];
            
            $ds .= "PAT**$PAT02*$PAT03****$PAT07*$PAT08****$PAT12**$PAT14*$PAT15*$PAT16*$PAT17*$PAT18*$PAT19~";
            $tpc += 1;
            
            if($pr->med1 > 0)
            {
                $DSP08 = $ndc[$pr->med1];
                $DSP09 = $pr->med_days * $pr->meds_per_day;
                $DSP10 = $pr->med_days;
                
                $ds .= "DSP*$DSP01*$DSP02*$DSP03*$DSP04*$DSP05*$DSP06*$DSP07*$DSP08*$DSP09*$DSP10*$DSP11*****$DSP16~";
                $ds .= "PRE**$PRE02~";
                
                $tpc += 2;
            }
            
            if($pr->med2 > 0)
            {
                $DSP08 = $ndc[$pr->med2];
                $DSP09 = $pr->med_days * $pr->meds_per_day;
                $DSP10 = $pr->med_days;
                
                $ds .= "DSP*$DSP01*$DSP02*$DSP03*$DSP04*$DSP05*$DSP06*$DSP07*$DSP08*$DSP09*$DSP10*$DSP11*****$DSP16~";
                $ds .= "PRE**$PRE02~";
                
                $tpc += 2;
            }
            
            if($pr->med3 > 0)
            {
                $DSP08 = $ndc[$pr->med3];
                $DSP09 = $pr->med_days * $pr->meds_per_day;
                $DSP10 = $pr->med_days;
               
                $ds .= "DSP*$DSP01*$DSP02*$DSP03*$DSP04*$DSP05*$DSP06*$DSP07*$DSP08*$DSP09*$DSP10*$DSP11*****$DSP16~";
                $ds .= "PRE**$PRE02~";
                
                $tpc += 2;
            }        
            
        }
        if(empty($pres))
        {
            $ds .= "PAT*******Report*Zero************~";
            $DSP05 = date('Ymd', strtotime($start));
            $ds .= "DSP*****$DSP05************~";
            $ds .= "PRE***~";
            $ds .= "CDI*****~";
            $tpc += 4;
        }
        
        $tpc += 3;
        
        $ds .= "TP*$tpc~";
        
        $tpc += 1;
        
        $ds .= "TT*$TH02*$tpc~";
        
        $file_name = date("Ymd", strtotime($start))."_".$loc_id.".dat";
        $this->zip->add_data($file_name, $ds);
        $zip_file_name = date("Y-m-d", strtotime($start))."_".$loc_id.".zip";
        $this->zip->download($zip_file_name);


//         $file_name = "./assets/upload/pdmp/$loc_id/".date("Ymd", strtotime($start))."_".$loc_id.".dat";
//         if ( !write_file($file_name, $ds))
//         {
//            die('Unable to write the file');
//         }
//         else
//         {
//             die("done");
// //            return $file_name;
//         }
    }
}