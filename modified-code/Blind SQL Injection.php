<?php

if( isset( $_GET[ 'Submit' ] ) ) {
        // Get input
        $id = $_GET[ 'id' ];
        $exists = false;

        switch ($_DVWA['SQLI_DB']) {
                case MYSQL:
                        // Check database
                        //💡prepare() 사용해서 SQL 쿼리 구조를 먼저 보내고 `user_id=?`의 ?는 나중에 실제 데이터가 들어가는 자리이다,
                        //💡 즉 쿼리 구조와 사용자 입력 데이터를 분리하여 SQL Injection 막음
                        $stmt  = $GLOBALS["___mysqli_ston"]->prepare("SELECT first_name, last_name FROM users WHERE user_id = ?");

                        //💡 bind_param() 이용하여 사용자 입력값을 `?`에 바인딩
                        //💡 바인딩 할때 타입= s (string) => sql 명령어로 해석하지 않음 그래서 Blind SQL Injection 막음
                        $stmt->bind_param('s',$id);

                        //💡 바인딩된 쿼리 실행
                        $stmt->execute();

                         //💡실행 결과 가져옴 
                        $result=$stmt->get_result();

                        $exists = false;

                         //💡결과 존재하고 사용자 id 발견되면 true
                        if ($result && $result->num_rows>0) {
                                $exists = true;
                        }

                         //💡구문 닫음
                        $stmt->close();

                        ((is_null($___mysqli_res = mysqli_close($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res);
                        break;
        }

        if ($exists) {
                // Feedback for end user
                $html .= '<pre>User ID exists in the database.</pre>';
        } else {
                // User wasn't found, so the page wasn't!
                header( $_SERVER[ 'SERVER_PROTOCOL' ] . ' 404 Not Found' );

                // Feedback for end user
                $html .= '<pre>User ID is MISSING from the database.</pre>';
        }

}

?>
