<?php
/**
 * Created by PhpStorm.
 * User: Matt
 * Date: 2016-05-03
 * Time: 2:23 PM
 */
error_reporting(E_ALL);
ini_set("display_errors",1 );
include_once("db.php");

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password, array(PDO::ATTR_PERSISTENT => false));

$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
ini_set("display_errors", 1 );

include_once('GenerateXlsx.php');


$excelmaker = new GenerateXlsx($conn, 1);
$excelmaker->run();



