<?php

function redandexit() { header('Location: index.php'); exit(); }
function debug($obj)
{
    echo '<pre>';
    var_dump($obj);
    echo '</pre>';
}
