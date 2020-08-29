<?php

function url($link) {
    if(empty(trim($link))) {
        return "campo URL vazio: Insira uma url valida";
    }
    if(!preg_match("/.xml$/", $link)) {
        return "O formato da url não suportado";
    }else {
        return "";
    }
}

    


