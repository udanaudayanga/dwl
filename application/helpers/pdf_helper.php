<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//function create_pdf($html, $filename = '', $stream = TRUE)
//{
//    $CI = get_instance();
//
//    $pdf_view = 'portrait';
//    define("DOMPDF_DPI", 150);
//    require_once(APPPATH . 'libraries/dompdf/dompdf_config.inc.php');
//    
//
//    $dompdf = new DOMPDF();
//    $dompdf->load_html($html);
//    $dompdf->set_paper('a4', $pdf_view);
//    $dompdf->render();
//
//    if ($stream)
//    {
//	$dompdf->stream($filename . ".pdf");
//    }
//    else
//    {
//	return $dompdf->output();
//    }
//}

function create_pdf($html, $filename = '', $stream = TRUE)
{
    $CI = get_instance();

    $pdf_view = 'portrait';
    define("DOMPDF_DPI", 150);
    require_once(APPPATH . 'libraries/dompdf/dompdf_config.inc.php');
    

    $dompdf = new DOMPDF();
    $dompdf->load_html($html);
    $dompdf->set_paper('a4', $pdf_view);
    $dompdf->render();

    if ($stream)
    {
	$dompdf->stream($filename . ".pdf");
    }
    else
    {
	return $dompdf->output();
    }
}

function create_dp_ticket($html)
{
    $CI = get_instance();
    $filename = 'test';

    $pdf_view = 'portrait';
    define("DOMPDF_DPI", 150);
    require_once(APPPATH . 'libraries/dompdf/dompdf_config.inc.php');
    

    $dompdf = new DOMPDF();
    $dompdf->load_html($html);
    $dompdf->set_paper('letter', $pdf_view);
    $dompdf->render();
    
    if (true)
    {
	$dompdf->stream($filename . ".pdf",array('Attachment'=>0));
    }
    else
    {
	return $dompdf->output();
    }
}

function create_mp_ticket($html)
{
    $CI = get_instance();
    $filename = 'test';
    require_once(APPPATH . 'libraries/mpdf61/mpdf.php');
    $pdf_view = 'portrait';

    $mpdf = new mPDF('utf-8',array(216,279));
//    $mpdf->debug = true; 
//    $mpdf->showImageErrors = true;
//    $stylesheet = file_get_contents('./assets/css/ticket.css'); // external css
//    $mpdf->WriteHTML($stylesheet,1);
    $mpdf->WriteHTML($html);
    $mpdf->Output();
    exit;
    
    
}

function create_mp_ticket_edit($html)
{
    $CI = get_instance();
    $filename = 'test';
    require_once(APPPATH . 'libraries/mpdf61/mpdf.php');
    $pdf_view = 'portrait';

    $mpdf = new mPDF('utf-8',array(216,279));
    $mpdf->useActiveForms = true;
//    $mpdf->debug = true; 
//    $mpdf->showImageErrors = true;
//    $stylesheet = file_get_contents('./assets/css/ticket.css'); // external css
//    $mpdf->WriteHTML($stylesheet,1);
    $mpdf->WriteHTML($html);
    $mpdf->Output('Edit_ex.pdf','D');
    exit;
    
}


function create_mp_label($html)
{
    $CI = get_instance();
    $filename = 'test';
    require_once(APPPATH . 'libraries/mpdf61/mpdf.php');
    $pdf_view = 'landscape';

    $mpdf = new mPDF('utf-8',array(89,26));
//    $stylesheet = file_get_contents('./assets/css/ticket.css'); // external css
//    $mpdf->WriteHTML($stylesheet,1);
    $mpdf->WriteHTML($html);
    $mpdf->Output();
    exit;
}

function create_mp_logs($html)
{
    $CI = get_instance();
    $filename = 'test';
    require_once(APPPATH . 'libraries/mpdf61/mpdf.php');
    $pdf_view = 'landscape';

    $mpdf = new mPDF('utf-8',array(279,216));
//    $stylesheet = file_get_contents('./assets/css/ticket.css'); // external css
//    $mpdf->WriteHTML($stylesheet,1);
    $mpdf->SetAuthor('Udana Udayanga');
    $mpdf->SetCreator('DWL CENTER');
    $mpdf->WriteHTML($html);
    $mpdf->Output();
    exit;
}