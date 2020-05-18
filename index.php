<?php
error_log("\n==================================\nOnce More\n");
session_start();
?>
<head>
    <title>Hello</title>
</head>
<body>
<?php
require_once "components/handlerErrors.php";
require_once "components/handlerDatabase.php";
require_once "components/handlerSession.php";

require "components/loginDrawForm.php";
?>
</body>
