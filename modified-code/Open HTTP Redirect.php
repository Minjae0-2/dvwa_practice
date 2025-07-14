<?php
//💡허용할 내부 페이지 목록 - whitelist
$whitelist = array("info.php?id=1","info.php?id=2","index.php");

//💡whitelist에 있는 URL인 경우에만 리다이렉트 수행
if (array_key_exists ("redirect", $_GET) && $_GET['redirect'] != "" && in_array($_GET['redirect'],$whitelist)) {
        header ("location: " . $_GET['redirect']);
        exit;
} else {
        echo "error";
        exit;
}

http_response_code (500);
?>
<p>Missing redirect target.</p>
<?php
exit;
?>
