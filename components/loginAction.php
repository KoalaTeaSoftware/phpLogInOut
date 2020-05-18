<?php
require_once "handlerErrors.php";
// this files is called by a form, so needs to re-start its session capability
session_start();
require_once "handlerSession.php";
require_once "loginDbShim.php";

// where the user will be redirected if there is some problem
const LOGIN_ACTION_DESTINATION = "../index.php";

// eliminate unhealthy calls
if ((isset($_POST['logout']))) {
    session_destroy();
    header("Location: " . LOGIN_ACTION_DESTINATION);
    exit();
}
// review inputs
if (empty($_POST['emailAddress'])) {
    header("Location: " . LOGIN_ACTION_DESTINATION . "?error=mtEmail");
    exit();
}
if (!filter_var($_POST['emailAddress'], FILTER_VALIDATE_EMAIL)) {
    header("Location: " . LOGIN_ACTION_DESTINATION . "?error=badEmail");
    exit();
}
$validEmailAddress = $_POST['emailAddress'];

if (empty($_POST['pwd'])) {
    header("Location: " . LOGIN_ACTION_DESTINATION . "?error=mtPwd&email=" . $validEmailAddress);
    exit();
}
if (!preg_match("/^[a-zA-Z0-9]{4,8}$/", $_POST['pwd'])) {
    header("Location: " . LOGIN_ACTION_DESTINATION . "?error=badPwd&email=" . $validEmailAddress);
    exit();
}
$validPassword = $_POST['pwd'];

// find out what to do
$memberData = loginDbShim_getMemberData(LOGIN_ACTION_DESTINATION, $validEmailAddress);

if (isset($_POST['signUp'])) {
    if (is_null($memberData)) {
        // we have unique credentials so we can sign them up
        loginHandler_addMember(LOGIN_ACTION_DESTINATION, $validEmailAddress, $validPassword);
        // ToDo: send welcome email
        session_storeMemberData($validEmailAddress);
        header("Location: " . LOGIN_ACTION_DESTINATION . "?status=signedUp");
        exit();
    } else {
        error_log("User is already known");
        header("Location: " . LOGIN_ACTION_DESTINATION . "?error=emailTaken&email=" . $validEmailAddress);
        exit();
    }
} elseif (isset($_POST['logIn'])) {
    if (is_null($memberData)) {
        // can't login because there is no matching email address
        header("Location: " . LOGIN_ACTION_DESTINATION . "?error=unmatchableCreds&email=" . $validEmailAddress);
        exit();
    } else {
        // try to sign in
        if (password_verify($validPassword, $memberData['passwordHash'])) {
            session_storeMemberData($validEmailAddress);
            header("Location: " . LOGIN_ACTION_DESTINATION . "?status=loggedIn");
            error_log("Session in the action file:" . print_r($_SESSION, true));
            exit();
        } else {
            // can't login because the passwords don't match
            header("Location: " . LOGIN_ACTION_DESTINATION . "?error=unmatchedCreds&email=" . $validEmailAddress);
            exit();
        }
    }
}

header("Location: " . LOGIN_ACTION_DESTINATION . "?error=whereAmI");
exit();
