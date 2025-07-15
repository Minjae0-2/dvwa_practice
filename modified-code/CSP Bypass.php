<?php
$headerCSP = "Content-Security-Policy: script-src 'self' https://pastebin.com hastebin.com www.toptal.com example.com code.jquery.com https://ssl.google-analytics.com https://digi.ninja ;"; // allows js from self, pastebin.com, hastebin.com, jquery, digi.ninja, and google analytics.
header($headerCSP);

# These might work if you can't create your own for some reason
# https://pastebin.com/raw/R570EE00
# https://www.toptal.com/developers/hastebin/raw/cezaruzeka

?>
<?php
if (isset ($_POST['include'])) {
    //💡허용할 URL 스크립트 화이트리스트 만들기
    $allowed_scripts = array( 
        "https://www.google.com"
    );
//💡사용자의 입력이 화이트리스트 안에 포함되어 있는지 확인
if (in_array($_POST['include'], $allowed_scripts)) {
        $page['body'] .= "
            <script src='" . $_POST['include'] . "'></script>
        ";
    } else { //💡허용되지 않은 URL일 시 에러메시지 출력
        $page['body'] .= "
            <p>Error:Script is not allowed.</p>
        ";
    }
}
$page[ 'body' ] .= '
<form name="csp" method="POST">
        <p>You can include scripts from external sources, examine the Content Security Policy and enter a URL to include here:</p>
        <input size="50" type="text" name="include" value="" id="include" />
        <input type="submit" value="Include" />
</form>
<p>
        As Pastebin and Hastebin have stopped working, here are some scripts that may, or may not help.
</p>
<ul>
        <li>https://digi.ninja/dvwa/alert.js</li>
        <li>https://digi.ninja/dvwa/alert.txt</li>
        <li>https://digi.ninja/dvwa/cookie.js</li>
        <li>https://digi.ninja/dvwa/forced_download.js</li>
        <li>https://digi.ninja/dvwa/wrong_content_type.js</li>
</ul>
<p>
        Pretend these are on a server like Pastebin and try to work out why some work and some do not work. Check the help for an explanation if you get stuck.
</p>
';
