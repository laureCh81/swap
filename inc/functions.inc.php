<?php


function user_connected(){
    if (empty($_SESSION['membre'])) {
        return false;
    } else {
        return true;
    }
}

function user_admin() {
    if(user_connected() && $_SESSION['membre']['statut'] == 2) {
        return true;
    }
    return false; 
}
