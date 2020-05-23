<?php
function session_storeMemberData($address)
{
//    error_log("Storing session data. Address :" . $address . ":");
    $_SESSION['memberEmail'] = $address;
}
