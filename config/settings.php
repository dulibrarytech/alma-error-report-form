<?php

// V2 diglib01
$recaptcha_public_key = "6LfKuJoUAAAAAIbsZj2fZkIwCkIRq2Z1gLHthlHo";
$recaptcha_private_key = "6LfKuJoUAAAAANfYOuTmEBT50DH7s1qDdhMaKutW";

$email_destinations = array("research@du.libanswers.com");      // LIVE
//$email_destinations = array("jeff.rynhart@du.edu");           // DEBUG

$google_analytics_id = "";

//change to a prefix that fits your communication preferences
$email_subject_prefix = "Compass Problem: ";

// Openurl 
// $openurl_base_url = "http://du-primo.hosted.exlibrisgroup.com/primo_library/libweb/action/openurl?null&vid=&institution=01UODE_MAIN&url_ctx_val=&url_ctx_fmt=null&isSerivcesPage=true&"; 
// $openurl_base_url = "http://primo.library.du.edu/primo_library/libweb/action/openurl?null&vid=01UODE_MAIN&institution=01UODE_MAIN&url_ctx_val=&url_ctx_fmt=null&isServicesPage=true&";

// New primo UI
$openurl_base_url = "https://du-primo.hosted.exlibrisgroup.com/primo-explore/openurl?institution=01UODE&vid=01UODE_MAIN&lang=en_US&";
$permalink_base_url = "https://du-primo.hosted.exlibrisgroup.com/primo-explore/fulldisplay";

// Response when form is submitted successfully
$success_response_text = "Thank you for reporting this issue. We will respond to you as soon as possible.";
?>
