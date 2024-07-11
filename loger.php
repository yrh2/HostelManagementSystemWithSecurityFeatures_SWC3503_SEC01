<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<title>Docs</title>
</head>

<body>
<?php
error_reporting(E_ALL);
ini_set("display_errors", 0);

function customErrorHandler($errno, $errstr, $errfile, $errline){
    {
        $message = "Error: [$errno] $errstr - $errfile:$errline";
        error_log($message, 3, "error_log.txt");
    }
}
set_error_handler("customnErrorHandler");

echo $undefinedVariable;
?>
</body>

</html>

