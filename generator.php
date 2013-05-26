<?php
include "../../common.php";
checkSession();
if(!isset($_GET["projectpath"]))
    die();
$_GET["projectpath"] = realpath($_GET["projectpath"]);
$white = explode(",", WHITEPATHS);
$found = false;
foreach($white as $allowed) {
    if(substr($_GET["projectpath"], 0, strlen($allowed)) == $allowed)
        $found = true;
}
if(!$found) 
    die();
if(!file_exists($_GET["projectpath"]."/.htaccess"))
    file_put_contents($_GET["projectpath"]."/.htaccess", "");
function writecode($file = "htaccess", $toWrite = "") {
    if(!in_array($file, array("htaccess", "htpasswd")))
        $file = "htaccess";
    $code = file_get_contents($_GET["projectpath"]."/.{$file}");
    $code .= $toWrite.PHP_EOL;
    unlink($_GET["projectpath"]."/.{$file}");
    file_put_contents($_GET["projectpath"]."/.{$file}", $code);
}
switch(isset($_GET["action"]) ? $_GET["action"] : "view") {
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
