<?php

if( isset( $_REQUEST[ 'Submit' ] ) ) {
        // Get input
         /*💡입력값을 SQL쿼리 삽입 전에 mysqli_real_escape_string을 이용하여 escape 처리를 함
         ex. '는 \' 로. 
         $GLOBALS["___mysqli_ston"]은 현재 사용중인 데이터베이스 연결 객체 참조  */
        $id = mysqli_real_escape_string($GLOBALS["___mysqli_ston"],$_REQUEST[ 'id' ]);

        switch ($_DVWA['SQLI_DB']) {
                case MYSQL:
                        // Check database
                        $query  = "SELECT first_name, last_name FROM users WHERE user_id = '$id';";
                        $result = mysqli_query($GLOBALS["___mysqli_ston"],  $query ) or die( '<pre>' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) . '</pre>' );

                        // Get results
                        while( $row = mysqli_fetch_assoc( $result ) ) {
                                // Get values
                                $first = $row["first_name"];
                                $last  = $row["last_name"];

                                // Feedback for end user
                                $html .= "<pre>ID: {$id}<br />First name: {$first}<br />Surname: {$last}</pre>";
                        }

                        mysqli_close($GLOBALS["___mysqli_ston"]);
                        break;
                case SQLITE:
                        global $sqlite_db_connection;

                        #$sqlite_db_connection = new SQLite3($_DVWA['SQLITE_DB']);
                        #$sqlite_db_connection->enableExceptions(true);

                        $query  = "SELECT first_name, last_name FROM users WHERE user_id = '$id';";
                        #print $query;
                        try {
                                $results = $sqlite_db_connection->query($query);
                        } catch (Exception $e) {
                                echo 'Caught exception: ' . $e->getMessage();
                                exit();
                        }

                        if ($results) {
                                while ($row = $results->fetchArray()) {
                                        // Get values
                                        $first = $row["first_name"];
                                        $last  = $row["last_name"];

                                        // Feedback for end user
                                        $html .= "<pre>ID: {$id}<br />First name: {$first}<br />Surname: {$last}</pre>";
                                }
                        } else {
                                echo "Error in fetch ".$sqlite_db->lastErrorMsg();
                        }
                        break;
        } 
}

?>
