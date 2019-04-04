<?php

require_once 'operational.php';

if(! isset($_FILES['image'])) redandexit();

if($_FILES['image']['error'] != UPLOAD_ERR_OK) redandexit();

if($_FILES['image']['type'] != 'image/jpeg') redandexit();

$tmp = $_FILES['image']['tmp_name'];
$name = basename($_FILES['image']['name']);
move_uploaded_file($tmp, 'photos/'.$name);
redandexit();
