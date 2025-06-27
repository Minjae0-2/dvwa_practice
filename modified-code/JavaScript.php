/*💡low.php*/
<script>
window.onload = function() {
        //💡세션에서 생성된 CSRF 토큰 값을 token에 저장->hidden input에 넘겨줌
    const token = '<?php echo $_SESSION["token"]; ?>'; 
    document.getElementById("token").value = token;
};
</script>

/*💡index.php */
<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated' ) );

$page = dvwaPageNewGrab();
$page[ 'title' ]   = 'Vulnerability: JavaScript Attacks' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'javascript';
$page[ 'help_button' ]   = 'javascript';
$page[ 'source_button' ] = 'javascript';

dvwaDatabaseConnect();

$vulnerabilityFile = '';
switch( dvwaSecurityLevelGet() ) {
        case 'low':
                $vulnerabilityFile = 'low.php';
                break;
        case 'medium':
                $vulnerabilityFile = 'medium.php';
                break;
        case 'high':
                $vulnerabilityFile = 'high.php';
                break;
        default:
                $vulnerabilityFile = 'impossible.php';
                break;
}

session_start();

if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(16)); //💡랜덤 16바이트로 16진수 문자열로 변환한 값 token에 저장
}

$message = "";
// Check what was sent in to see if it was what was expected
if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (array_key_exists ("phrase", $_POST) && array_key_exists ("token", $_POST)) {

                $phrase = $_POST['phrase'];
                $token = $_POST['token'];

                if ($phrase == "success") {
                        switch( dvwaSecurityLevelGet() ) {
                                case 'low':
                                         //💡Post로 token phrase 다 전달되었는지 확인
                                         if (isset($_POST['token'], $_POST['phrase'])) {
                                                 $user_token = $_POST['token'];
                                                 $phrase = $_POST['phrase'];

                                        //💡hash_equals로 세션에 저장된 토큰과 클라이언트가 보낸 토큰 일치하는지 확인
                                        if (isset($_SESSION['token']) && hash_equals($_SESSION['token'], $user_token)) {
                                                $message = "<p style='color:red'>Well done!</p>";
                                        } else {
                                                $message = "<p>Invalid token.</p>";
                                        }
                                        //💡토큰 한번 사용 후 새로운 랜덤 토큰 생성
                                        $_SESSION['token']= bin2hex(random_bytes(16));}
                                        break;
                                case 'medium':
                                        if ($token == strrev("XXsuccessXX")) {
                                                $message = "<p style='color:red'>Well done!</p>";
                                        } else {
                                                $message = "<p>Invalid token.</p>";
                                        }
                                        break;
                                case 'high':
                                        if ($token == hash("sha256", hash("sha256", "XX" . strrev("success")) . "ZZ")) {
                                                $message = "<p style='color:red'>Well done!</p>";
                                        } else {
                                                $message = "<p>Invalid token.</p>";
                                        }
                                        break;
                                default:
                                        $vulnerabilityFile = 'impossible.php';
                                        break;
                        }
                } else {
                        $message = "<p>You got the phrase wrong.</p>";
                }
        } else {
                $message = "<p>Missing phrase or token.</p>";
        }
}

if ( dvwaSecurityLevelGet() == "impossible" ) {
$page[ 'body' ] = <<<EOF
<div class="body_padded">
        <h1>Vulnerability: JavaScript Attacks</h1>

        <div class="vulnerable_code_area">
        <p>
                You can never trust anything that comes from the user or prevent them from messing with it and so there is no impossible level.
        </p>
EOF;
} else {
$page[ 'body' ] = <<<EOF
<div class="body_padded">
        <h1>Vulnerability: JavaScript Attacks</h1>

        <div class="vulnerable_code_area">
        <p>
                Submit the word "success" to win.
        </p>

        $message

        <form name="low_js" method="post">
                <input type="hidden" name="token" value="" id="token" />
                <label for="phrase">Phrase</label> <input type="text" name="phrase" value="ChangeMe" id="phrase" />
                <input type="submit" id="send" name="send" value="Submit" />
        </form>
EOF;
}

require_once DVWA_WEB_PAGE_TO_ROOT . "vulnerabilities/javascript/source/{$vulnerabilityFile}";

$page[ 'body' ] .= <<<EOF
        </div>
EOF;

$page[ 'body' ] .= "
        <h2>More Information</h2>
        <ul>
                <li>" . dvwaExternalLinkUrlGet( 'https://www.w3schools.com/js/' ) . "</li>
                <li>" . dvwaExternalLinkUrlGet( 'https://www.youtube.com/watch?v=cs7EQdWO5o0&index=17&list=WL' ) . "</li>
                <li>" . dvwaExternalLinkUrlGet( 'https://ponyfoo.com/articles/es6-proxies-in-depth' ) . "</li>
        </ul>
        <p><i>Module developed by <a href='https://twitter.com/digininja'>Digininja</a>.</i></p>
</div>\n";

dvwaHtmlEcho( $page );

?>
     