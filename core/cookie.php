<?php
    if (!isset($_COOKIE['CodeShup'])){
        setcookie('CodeShup', 'patobeur', time() + 365*24*3600, null, null, false, true); // On écrit un cookie tout chaud
    }
?>