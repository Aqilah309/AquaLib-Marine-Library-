<?php
    //overwrite PHP setting for error_reporting
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(E_ALL);//e.g. (E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT); display all errors except notice and deprecated and strict
    
    //load version information
    include 'sw_includes/build.php';

    //load default config file; this file must present in order sWADAH to function properly.
    if (file_exists(stream_resolve_include_path('config.default.php'))) {include "config.default.php";} else {echo "Missing configuration file.";exit;}

    //load config.user.php value. will overwrite any value assigned on config.php or config.default.php
    //if upgraded from previous version, please rename config.php to config.user.php -- all default will be loaded in config.default.php and user configuration will be load on config.user.php
    //existing config.php will be ignored.
    if (file_exists(stream_resolve_include_path('config.user.php'))) {include "config.user.php";}

    if ($system_mode == 'maintenance' && basename($_SERVER["SCRIPT_FILENAME"], '.php') != 'in' && !isset($_SESSION['username'])) 
    {
        echo "<html lang='en'><body style='text-align:center;'><h1>$system_title</h1><h2 style='color:red;'>System is currently under maintenance. Will be right back !</h2></body></html>";
        exit;
    }

    //for user that upgrading from 2021X/2021Y, $system_path will be assigned = $system_identifier
    //might be remove in future version. below line is a fail-safe.
    if ($system_path == 'https://mydomain.institution.edu/') {$system_path = $system_identifier;}

    //header policy
    $hd_Strict_Transport_Security = "max-age=31536000";
    $hd_X_Frame_Options = "SAMEORIGIN";
    $hd_Referrer_Policy = "same-origin";
    $hd_Content_Security_Policy = "default-src $system_path $system_ip $ezproxy_appended_domain localhost ajax.googleapis.com 'unsafe-inline' 'unsafe-eval' img-src * data:; frame-ancestors default-src $system_path $system_ip $ezproxy_appended_domain localhost ajax.googleapis.com 'unsafe-inline' 'unsafe-eval' img-src * data:;";
    $hd_X_Content_Type_Options = "nosniff";
    $hd_Permissions_Policy = "accelerometer=(), camera=(), geolocation=(), gyroscope=(), magnetometer=(), microphone=(), payment=(), usb=()";

    //for mysqli database connection engine
    try {
        $conn = @mysqli_connect ($dbhost,$dbuser,$dbpass,$dbname) or die("Unable to connect to $dbname at $dbhost.");
        @mysqli_query($conn,"SET CHARACTER SET 'utf8'");
        @mysqli_query($conn,"SET SESSION collation_connection ='utf8_swedish_ci'");    
    }
    catch (mysqli_sql_exception $e) {
        exit("Unable to connect to database. Check your configuration file.");
    }

    //new database connection for in use with prepared statement
    try {
        $new_conn = new mysqli($dbhost,$dbuser,$dbpass,$dbname);
        $new_conn->set_charset("utf8mb4");
    }
    catch (mysqli_sql_exception $e) {
        exit("Unable to connect to database. Check your configuration file.");
    }
    $new_conn->set_charset("utf8mb4");

?>