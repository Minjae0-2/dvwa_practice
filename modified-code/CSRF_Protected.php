<?php
session_start(); //💡세션 시작 

if(empty($_SESSION['csrf_token'])){ //💡CSRF토큰이 세션에 없으면 생성 
        $_SESSION['csrf_token']=bin2hex(random_bytes(32)); //💡32바이트 난수 생성 -> bin2hex()로 16진수 문자열로 변환
}
$csrf_token=$_SESSION['csrf_token'];  //💡CSRF방어를 위해 폼에 포함시킬 토큰

/*💡index.php에서 폼에 토큰을 hidden필드로 포함했다. 아래 주석은 해당 코드이다. */
/*
if($vulnerabilityFile=='low.php'){
        $page['body'] .= '<input type="hidden" name="csrf_token" value="'. htmlspecialchars($csrf_token) . '" />';
} 
 */

if( isset( $_GET[ 'Change' ] ) ) {

        /*💡csrf 토큰 검증  */
        /*💡empty()로 get요청에서 토큰이 비어있거나 hash_equals()로 session에 저장된 토큰과 get요청으로 들어온 토큰 비교  */
        if(empty($_GET['csrf_token'])||!hash_equals($_SESSION['csrf_token'],$_GET['csrf_token'])){
                die('CSRF token validation failed');
        }

        // GET input
        $pass_new  = $_GET[ 'password_new' ];
        $pass_conf = $_GET[ 'password_conf' ];

        // Do the passwords match?
        if( $pass_new == $pass_conf ) {
                // They do!
                $pass_new = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"],  $pass_new ) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
                $pass_new = md5( $pass_new );

                // Update the database
                $current_user = dvwaCurrentUser();
                $insert = "UPDATE `users` SET password = '$pass_new' WHERE user = '" . $current_user . "';";
                $result = mysqli_query($GLOBALS["___mysqli_ston"],  $insert ) or die( '<pre>' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) . '</pre>' );

                // Feedback for the user
                $html .= "<pre>Password Changed.</pre>";
        }
        else {
                // Issue with passwords matching
                $html .= "<pre>Passwords did not match.</pre>";
        }

        ((is_null($___mysqli_res = mysqli_close($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
}

?>
