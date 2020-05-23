<?php
function getNewConnection()
{
    $host = "localhost";
    $port = "3306"; // on bluehost
    $dbUserName = "koalate1_grnadmi";
    $dbPassword = "o?kAJmB~Az&w";
    $dbName = "koalate1_grnusers";

    $mysqli = null;

    // cause it to take notice of the exceptions
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        $mysqli = new mysqli($host, $dbUserName, $dbPassword, $dbName, $port);
    } catch (Exception $e) {
        switch ($e->getCode()) {
            case 2054:
                /* The text associated with this code is:
                 * Warning: mysqli::mysqli(): The server requested authentication method unknown to the client [mysql_old_password]
                 * Connect Error (2054) The server requested authentication method unknown to the client
                 * However, this does not prevent the system from working
                 */
                return $mysqli;
            default:
                error_log("Database Error:" . $e->getMessage());
                return null;
        }
    }
    if ($mysqli->connect_errno) {
        error_log("Database Error:" . $mysqli->connect_error);
        return null;
    }

    return $mysqli;
}

function closeConnection($mysqli)
{
    mysqli_close($mysqli);
}

function closeStatement($stmt)
{
    mysqli_stmt_close($stmt);
}
