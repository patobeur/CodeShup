<?php
if (!isset($_COOKIE['name'])){
    setcookie('name', 'patobeur', time() + 365*24*3600, null, null, false, true); // On écrit un cookie
}
?>