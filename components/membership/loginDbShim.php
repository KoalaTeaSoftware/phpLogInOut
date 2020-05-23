<?php
require_once "../database/connectionHandler.php";

/**
 * @param $errorLocation - where to go if there is a problem
 * @param $cleanedEmailAddress - chekc that it is nice before you give it in, to reduce SQL injection possibilities
 * @return string[]|null -  an associative array od the member data (including the hashed password)
 */
function loginDbShim_getMemberData($errorLocation, $cleanedEmailAddress)
{
    $conn = getNewConnection();
    if (is_null($conn)) {
        header("Location: " . $errorLocation . "?status=internal");
        exit();
    }
    $smt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($smt, "SELECT * FROM `members` WHERE emailAddress = ?; ")) {
        header("Location: " . $errorLocation . "?status=internal");
        exit();
    }
    mysqli_stmt_bind_param($smt, "s", $cleanedEmailAddress);
    mysqli_stmt_execute($smt);
    $resultHandle = mysqli_stmt_get_result($smt);
    $rowData = mysqli_fetch_assoc($resultHandle);

    closeStatement($smt);
    closeConnection($conn);

    return $rowData;
}

/**
 * @param $errorLocation - where to go if there is a problem
 * @param $validEmailAddress - check that it is nice before you give it in, to reduce SQL injection possibilities
 * @param $validPassword - again check that it is a nice password - it will get hashed
 */
function loginHandler_addMember($errorLocation, $validEmailAddress, $validPassword)
{
    $hashedPassword = password_hash($validPassword, PASSWORD_DEFAULT);

    $connection = getNewConnection();
    if (is_null($connection)) {
        header("Location: " . $errorLocation . "?status=internal");
        exit();
    }
    $statement = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($statement, "INSERT INTO members (emailAddress, passwordHash) VALUES (? ,?); ")) {
        header("Location: " . $errorLocation . "?status=internal");
        exit();
    }
    mysqli_stmt_bind_param($statement, "ss", $validEmailAddress, $hashedPassword);
    mysqli_stmt_execute($statement);

    if (mysqli_affected_rows($connection) != 1) {
        header("Location: " . $errorLocation . "?status=internal");
        exit();
    }
    closeStatement($statement);
    closeConnection($connection);
}
