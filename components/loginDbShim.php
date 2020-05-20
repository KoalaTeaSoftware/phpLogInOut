<?php
require_once "handlerDatabase.php";

function loginDbShim_getMemberData($errorLocation, $cleanedEmailAddress)
{
    $conn = getNewConnection();
    if (is_null($conn)) {
        header("Location: " . $errorLocation . "?error=internal-noConnection");
        exit();
    }
    $smt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($smt, "SELECT * FROM `members` WHERE emailAddress = ?; ")) {
        header("Location: " . $errorLocation . "?error=internal");
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

function loginHandler_addMember($errorLocation, $validEmailAddress, $validPassword)
{
    $hashedPassword = password_hash($validPassword, PASSWORD_DEFAULT);

    $connection = getNewConnection();
    if (is_null($connection)) {
        header("Location: " . $errorLocation . "?error=internal-noConnection");
        exit();
    }
    $statement = mysqli_stmt_init($connection);
    if (!mysqli_stmt_prepare($statement, "INSERT INTO members (emailAddress, passwordHash) VALUES (? ,?); ")) {
        header("Location: " . $errorLocation . "?error=internal");
        exit();
    }
    mysqli_stmt_bind_param($statement, "ss", $validEmailAddress, $hashedPassword);
    mysqli_stmt_execute($statement);

    if (mysqli_affected_rows($connection) != 1) {
        header("Location: " . $errorLocation . "?error=internal");
        exit();
    }
    closeStatement($statement);
    closeConnection($connection);
}
