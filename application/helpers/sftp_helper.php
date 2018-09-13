<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function getSFTPCon($auth)
{
    set_include_path(get_include_path() . PATH_SEPARATOR.APPPATH . 'libraries/phpseclib');
    require_once(APPPATH . 'libraries/phpseclib/Net/SFTP.php');
    $user = $auth['u'];
    $pass = $auth['p'];
    
    
    $sftp = new Net_SFTP('flpdmp-reporting.hidinc.com');
    if (!$sftp->login('flpdm'.$user, $pass)) {
        exit('Login Failed');
    }
    
    return $sftp;
}
