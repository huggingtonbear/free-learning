<?php
 
// Email address verification
function isEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}
 
if($_POST) {
 
    $mailchimp_api_key = '051ccfe3278d03c4c24a9719c07e298e-us11'; // enter your MailChimp API Key
    // ****
    $mailchimp_list_id = '285a1cd2fc'; // enter your MailChimp List ID
    // ****
 
    $subscriber_email = addslashes(trim($_POST['email']));
 
    if(!isEmail($subscriber_email)) {
        $array = array();
        $array['valid'] = 0;
        $array['message'] = 'Please use a valid email address!';
        echo json_encode($array);
    }
    else {
        $array = array();
        $merge_vars = array();
         
        require_once 'MailChimp.php';
         
        $MailChimp = new \Drewm\MailChimp($mailchimp_api_key);
        $result = $MailChimp->call('lists/subscribe', array(
                'id'                => $mailchimp_list_id,
                'email'             => array('email' => $subscriber_email),
                'merge_vars'        => $merge_vars,
                'double_optin'      => false,
                'update_existing'   => true,
                'replace_interests' => false,
                'send_welcome'      => false,
        ));
         
        if($result == false) {
            $array['valid'] = 0;
            $array['message'] = 'An error occurred! Please try again later.';
        }
        else {
            $array['valid'] = 1;
            $array['message'] = 'Thanks! We have sent you an email containing a link to download the Course';
        }
 
            echo json_encode($array);
 
    }
 
}
 
?>