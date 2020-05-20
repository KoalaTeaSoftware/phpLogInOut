<?php
function getNewConnection()
{
    define("HOST", "localhost");
//    define("PORT", "3308");
    define("PORT", "3306"); // on bluehost
    define("DB_USER_NAME", "koalate1_grnadmi");
    define("DB_PASSWORD", "o?kAJmB~Az&w");
    define("DB_NAME", "koalate1_grnusers"); //koalate1_grnusers

    $mysqli = null;

    // cause it to take notice of the exceptions
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        $mysqli = new mysqli(HOST, DB_USER_NAME, DB_PASSWORD, DB_NAME, PORT);
    } catch (Exception $e) {
        switch ($e->getCode()) {
            case 2054:
                /*
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
