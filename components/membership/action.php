<?php
/**
 * Every (normal) exit from this adds a status GET parameter
 * EXCEPT for the logout (which provides no parameters)
 * If the given credentials are bad, then the status indicates (within limits) the nature of the badness
 */
require_once "../logging/handlerErrors.php";
// this files is called by a form, so needs to re-start its session capability
session_start();

//error_log("Processing login form submission");
//error_log("Get:" . print_r($_GET, true));
//error_log("Post:" . print_r($_POST, true));
//error_log("Sess:" . print_r($_SESSION, true));
require_once "handlerSession.php";
require_once "loginDbShim.php";

// where the user will be redirected if there is some problem
const LOGIN_ACTION_DESTINATION = "/index.php";
const VALID_PWD_REGEX = "/^[a-zA-Z0-9_]{4,12}$/";

// get this out of the way, as the simplest demand
if ((isset($_POST['logout']))) {
    session_destroy();
    header("Location: " . LOGIN_ACTION_DESTINATION);
    exit();
}
// It is a bit more complicated than a mere log out, so, initially, see that the parameters look OK
if (empty($_POST['emailAddress'])) {
    header("Location: " . LOGIN_ACTION_DESTINATION . "?status=mtEmail");
    exit();
}
if (!filter_var($_POST['emailAddress'], FILTER_VALIDATE_EMAIL)) {
    header("Location: " . LOGIN_ACTION_DESTINATION . "?status=badEmail");
    exit();
}
$validEmailAddress = $_POST['emailAddress'];

if (empty($_POST['pwd'])) {
    header("Location: " . LOGIN_ACTION_DESTINATION . "?status=mtPwd&email=" . $validEmailAddress);
    exit();
}
if (!preg_match(VALID_PWD_REGEX, $_POST['pwd'])) {
    header("Location: " . LOGIN_ACTION_DESTINATION . "?status=badPwd&email=" . $validEmailAddress);
    exit();
}
$validPassword = $_POST['pwd'];

// Input data appears well-formed. Look for a member with this email address
$memberData = loginDbShim_getMemberData(LOGIN_ACTION_DESTINATION, $validEmailAddress);

if (isset($_POST['signUp'])) {
    if (is_null($memberData)) {
        // we have been given unique credentials so we can sign them up
        loginHandler_addMember(LOGIN_ACTION_DESTINATION, $validEmailAddress, $validPassword);
        // ToDo: send welcome email
        session_storeMemberData($validEmailAddress);
        header("Location: " . LOGIN_ACTION_DESTINATION . "?status=signedUp");
        exit();
    } else {
//        error_log("User is already known");
        header("Location: " . LOGIN_ACTION_DESTINATION . "?status=emailTaken&email=" . $validEmailAddress);
        exit();
    }
} elseif (isset($_POST['logIn'])) {
    if (is_null($memberData)) {
        // can't login because there is no matching email address
        header("Location: " . LOGIN_ACTION_DESTINATION . "?status=unmatchableCreds&email=" . $validEmailAddress);
        exit();
    } else {
        // try to sign in
        if (password_verify($validPassword, $memberData['passwordHash'])) {
            session_storeMemberData($validEmailAddress);
            header("Location: " . LOGIN_ACTION_DESTINATION . "?status=loggedIn");
//            error_log("Session in the action file:" . print_r($_SESSION, true));
            exit();
        } else {
            // can't login because the passwords don't match
            header("Location: " . LOGIN_ACTION_DESTINATION . "?status=unmatchableCreds&email=" . $validEmailAddress);
            exit();
        }
    }
}
// We have arrived at this action by an unexpected route
header("Location: " . LOGIN_ACTION_DESTINATION . "?status=whereAmI");
exit();
