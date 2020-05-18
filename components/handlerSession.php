<?php
function session_storeMemberData($address)
{
    error_log("Storing session data. Address :" . $address . ":");
    $_SESSION['memberEmail'] = $address;
}

/*function logSession()
{
    error_log("sessionsID:" . session_id() . ":");
    error_log("ini_get:" . ini_get('session.cookie_domain') . ":");
    error_log("Session contains:" . print_r($_SESSION, true));

}*/
