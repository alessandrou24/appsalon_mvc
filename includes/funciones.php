<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}


function esAuth(){
    if(!isset($_SESSION['login'])){
        header('Location: /');
    }
}

function isAdmin(){
    if(!isset($_SESSION['admin'])){
        header('Location: /');
    }
}

function esUltimo( $actual, $proximo): bool {   
    if($actual != $proximo) return true; 
    else return false; 
}