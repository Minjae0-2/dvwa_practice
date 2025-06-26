<?php

$html = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $_SESSION['last_session_id'] = bin2hex(random_bytes(16)); //💡16바이트 난수 생성 -> bin2hex()로 16진수 문자열로 변환 
        //$_SESSION['last_session_id']++;
        $cookie_value = $_SESSION['last_session_id'];
        setcookie("dvwaSession", $cookie_value);
}
?>


