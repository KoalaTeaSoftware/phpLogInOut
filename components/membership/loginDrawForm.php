<?php
/**
 * this is a fragment of HTML
 * include it to have the form drawn at the point of insertion
 */
// note the assumption inherent in this statement
const LOGIN_FORM_DESTINATION = "/components/membership/action.php";

// the following 2 variables are set by the focusOn.. functions
$emailAutofocusAttribute = "";
$passwordAutoFocusAttribute = "";

// these are given values if we are coming through a second time (because of invalid credentials)
$statusMessageClass = "info";
$emailValue = "";
$emailPlaceholder = "Enter email";

// set this to the required attribute if you want client-side enforcement, in addition to the server side enforcement
// here, it is MT to make verification of server-side enforcement easier
$required = "";
//$required = "required";

$msg = "Please provide both username and password";

if (isset($_GET['status'])) {
    // control is returning to us because the server-side validation is unhappy
//    error_log("Back from the server-side verification of the form");
    $statusMessageClass = "text-warning";
    switch ($_GET['status']) {
        case "signedUp":
            // do nothing here, lett the stuff a bit lower down handle what to draw
            error_log("Status param says we are logged on");
            break;
        case "internal":
            //we can not log anyone in so don't even try
            echo '<p id="status" class="bg-warning">We are sorry, but we can not sign you in at the moment.</p>';
            exit;
        case "mtEmail":
        case "badEmail": // i.e. the email has failed the server-side **format** verification
            $msg = "Please provide a valid email address";
            $emailAutofocusAttribute = "autofocus";
            break;
        case "mtPwd":
        case "badPwd": // i.e. the password has failed the server-side **format** verification
            $msg = "A valid password contains only letters, numbers, or _ and is from 4 to 12 characters long";
            $passwordAutoFocusAttribute = "autofocus";
            break;
        case "emailTaken": // ie. trying to sign in but the email is already taken
            $msg = "That email address is already in use. Log in, or use a different email address";
            $emailAutofocusAttribute = "autofocus";
            break;
        case "unmatchableCreds": // i.e. trying to log in with creds that do not match anyone in the DB
            $msg = "Those credentials do not match any established members";
            $emailAutofocusAttribute = "autofocus";
            break;
        case "loggedIn":
    }
    if (isset($_GET['email'])) {
        $emailPlaceholder = $_GET['email'];
        $emailValue = $emailPlaceholder;
    }
}

// irrespective of whether this our first time through, or we are back again because of server-side rejection,
// decide what we are going to draw (based on session data) and do the drawing
if (!isset($_SESSION['memberEmail'])) {
//    error_log("Session says this user is NOT logged in");
    ?>
    <p id="status" class="<?= $statusMessageClass ?>"><?= $msg ?></p>
    <!--suppress HtmlUnknownTarget -->
    <form action="<?= LOGIN_FORM_DESTINATION ?>" method="post" id="membershipForm">
        <div class="form-group">
            <label for="emailAddress">Email address</label>
            <input type="email" class="form-control" aria-describedby="emailHelp"
                   id="emailAddress" name="emailAddress"
                   placeholder="<?= $emailPlaceholder ?>"
                   value="<?= $emailValue ?>"
                   size="20"
                <?= $required ?>
                <?= $emailAutofocusAttribute ?>>
        </div>
        <div class="form-group">
            <label for="pwd">Password</label>
            <input type="password" class="form-control"
                   id="pwd" name="pwd"
                   placeholder="Password"
                   size="12"
                <?= $required ?>
                <?= $passwordAutoFocusAttribute ?>>
        </div>
        <button type="submit" class="btn btn-primary" name="logIn">Log In</button>
        <button type="submit" class="btn btn-light" name="signUp">Sign Up</button>
    </form>
    <?php
} else {
//    error_log("Session says this user is logged in as " . $_SESSION['memberEmail']);
    ?>
    <form action="<?= LOGIN_FORM_DESTINATION ?>" method="post" id="logOutForm">
        <button type="submit" class="btn btn-primary" name="logout">Log Out</button>
    </form>
    <?php
}
