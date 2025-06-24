<?php
session_start(); //💡세션 시작

if(empty($_SESSION['csrf_token'])){ //💡CSRF토큰이 세션에 없으면 생성
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); //💡32바이트 난수 생성 -> bin2hex()로 16진수 문자열로 변환
}

$csrf_token= $_SESSION['csrf_token']; //💡CSRF방어를 위해 폼에 포함시킬 토큰

/*💡index.php에서 폼에 토큰을 hidden필드로 포함했다. 아래 주석은 해당 코드이다. */
/*
if($vulnerabilityFile=='low.php'){
        $page['body'] .= '<input type="hidden" name="csrf_token" value="'. htmlspecialchars($csrf_token) . '" />';
} 
 */

  //💡 로그인 시도 횟수가 세션에 없으면 0으로 초기화
if(!isset($_SESSION['login_attempts'])){ 
        $_SESSION['login_attempts']=0;
}

if( isset( $_GET[ 'Login' ] ) ) {

         /*💡csrf 토큰 검증  */
        /*💡empty()로 get요청에서 토큰이 비어있거나 hash_equals()로 session에 저장된 토큰과 get요청으로 들어온 토큰 비교  */
        if(empty($_GET['csrf_token'])||!hash_equals($_SESSION['csrf_token'],$_GET['csrf_token'])){
                die('<pre>CSRF token validation failed</pre>');
        }

          /*💡로그인 시도 횟수가 5회 이상인지 확인 (계정 잠금) */
        if ($_SESSION['login_attempts'] >= 5) {
                echo "<pre>You failed login 5 attempts. Account locked.</pre>";
         } else {
        // Get username
        $user = $_GET[ 'username' ];

        // Get password
        $pass = $_GET[ 'password' ];
        $pass = md5( $pass );

        // Check the database
        $query  = "SELECT * FROM `users` WHERE user = '$user' AND password = '$pass';";
        $result = mysqli_query($GLOBALS["___mysqli_ston"],  $query ) or die( '<pre>' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) . '</pre>' );

        if( $result && mysqli_num_rows( $result ) == 1 ) {
                // Get users details
                $row    = mysqli_fetch_assoc( $result );
                $avatar = $row["avatar"];

                // Login successful
                $html .= "<p>Welcome to the password protected area {$user}</p>";
                $html .= "<img src=\"{$avatar}\" />";

                $_SESSION['login_attempts'] = 0;   //💡로그인 성공시 횟수 초기화
        }
        else {
                // Login failed
                $html .= "<pre><br />Username and/or password incorrect.</pre>";

                $_SESSION['login_attempts']++; //💡로그인 실패시 횟수 증가
}

        ((is_null($___mysqli_res = mysqli_close($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
}
}
?>
    