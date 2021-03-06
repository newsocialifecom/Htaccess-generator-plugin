<?php
include "../../common.php";
if(!defined("BASE_PATH"))
    include "../../config.php";
checkSession();
#$_GET["projectpath"] = realpath($_GET["projectpath"]);
if(substr($_GET["projectpath"], 0, 1) != "/")
    $_GET["projectpath"] = WORKSPACE.$_GET["projectpath"];
if(defined("WHITEPATHS")) {
    $white = explode(",", WHITEPATHS);
    $found = false;
    foreach($white as $allowed) {
        if(substr($_GET["projectpath"], 0, strlen($allowed)) == $allowed)
            $found = true;
    }
    if(!$found) 
        die("Allowed: ".WHITEPATHS."<br />Requested: ".$_GET["projectpath"]);
}
if(!file_exists($_GET["projectpath"]."/.htaccess")) {
    $htaccess = fopen($_GET["projectpath"]."/.htaccess", "wb");
    fwrite($htaccess, "############### Codiad .htaccess Generator ###############\n");
    fclose($htaccess);  
}
function writecode($file = "htaccess", $toWrite = "") {
    if(!in_array($file, array("htaccess", "htpasswd")))
        $file = "htaccess";
    $code = file_get_contents($_GET["projectpath"]."/.{$file}");
    $code .= $toWrite.PHP_EOL;
    unlink($_GET["projectpath"]."/.{$file}");
    file_put_contents($_GET["projectpath"]."/.{$file}", $code);
}
switch($_GET["action"]) {
    case "view":
        echo file_get_contents("view.html");
    break;
    case "delete":
        unlink($_GET["projectpath"]."/.htaccess");
        file_put_contents($_GET["projectpath"]."/.htaccess", "");
        echo '<span style="color: red">.htaccess deleted succefully</span>';
        echo file_get_contents("view.html");
    break;
    case "errorpages":
        echo file_get_contents("errorpages.html");
    break;
    case "fileindex":
        echo file_get_contents("fileindex.html");
    break;
    case "homepage":
        echo file_get_contents("homepage.html");
    break;
    case "protector":
        echo file_get_contents("protector.html");
    break;
    case "write":
        writecode(isset($_GET["file"]) ? $_GET["file"] : "htaccess", $_GET["code"]);
    break;
    case "password":
        $username = $_POST["username"];
        $password = crypt($_POST["password"], base64_encode($_POST["password"]));
        writecode("htpasswd", "{$username}:{$password}");
    break;
}
