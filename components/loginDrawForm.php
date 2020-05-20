<?php
/**
 * this is a fragment of HTML
 * include it to have the form drawn exactly at that point of insertion
 */
if (isset($_GET['email'])) {
    $emailPlaceholder = $_GET['email'];
    $emailValue = @$emailPlaceholder;
    $emailAutofocusAttribute = "";
    $passwordAutoFocusAttribute = "autofocus";
} else {
    $emailPlaceholder = "Enter email";
    $emailValue = "";
    $emailAutofocusAttribute = "autofocus";
    $passwordAutoFocusAttribute = "";
}
if (isset($_GET['error'])) {
    $errorFlag = $_GET['error'];
} else {
    $errorFlag = "";
}
if (!isset($_SESSION['memberEmail'])) {
    ?>
    <!--suppress HtmlUnknownTarget -->
    <form action="components/loginAction.php" method="post" id="membershipForm">
        <input type="hidden" id="errorFlag" value="<?= $errorFlag ?>">
        <div class="form-group">
            <label for="emailAddress">Email address</label>
            <input type="email" class="form-control" aria-describedby="emailHelp"
                   id="emailAddress" name="emailAddress"
                   placeholder="<?= $emailPlaceholder ?>"
                   value="<?= $emailValue ?>"
                <?= $emailAutofocusAttribute ?>>
            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
        </div>
        <div class="form-group">
            <label for="pwd">Password</label>
            <input type="password" class="form-control"
                   id="pwd" name="pwd"
                   placeholder="Password"
                <?= $passwordAutoFocusAttribute ?>>
        </div>
        <button type="submit" class="btn btn-primary" name="logIn">Log In</button>
        <button type="submit" class="btn btn-light" name="signUp">Sign Up</button>
    </form>
    <?php
} else {
    ?>
    <form action="components/loginAction.php" method="post" id="logOutForm">
        <button type="submit" class="btn btn-primary" name="logout">Log Out</button>
    </form>
    <?php
}
