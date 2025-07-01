<?php
define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaDatabaseConnect();

/*
On impossible only the admin is allowed to retrieve the data.
*/

if (dvwaSecurityLevelGet() == "impossible" && dvwaCurrentUser() != "admin") {
        print json_encode (array ("result" => "fail", "error" => "Access denied"));
        exit;
}

if ($_SERVER['REQUEST_METHOD'] != "POST") {
        $result = array (
                                                "result" => "fail",
                                                "error" => "Only POST requests are accepted"
                                        );
        echo json_encode($result);
        exit;
}

try {
        $json = file_get_contents('php://input');
        $data = json_decode($json);
        if (is_null ($data)) {
                $result = array (
                                                        "result" => "fail",
                                                        "error" => 'Invalid format, expecting "{id: {user ID}, first_name: "{first name}", surname: "{surname}"}'

                                                );
                echo json_encode($result);
                exit;
        }
} catch (Exception $e) {
        $result = array (
                                                "result" => "fail",
                                                "error" => 'Invalid format, expecting \"{id: {user ID}, first_name: "{first name}", surname: "{surname}\"}'

                                        );
        echo json_encode($result);
        exit;
}

//💡현재 로그인된 사용자 ID와 요청된 사용자 ID 가져오기
$login_id = $_SESSION['user_id'];
$target_id = $data->id;

//💡두 ID가 일치한다면 정보 수정 실행
if($login_id == $target_id){
$query = "UPDATE users SET first_name = '" . $data->first_name . "', last_name = '" .  $data->surname . "' where user_id = " . $data->id . "";
$result = mysqli_query($GLOBALS["___mysqli_ston"],  $query ) or die( '<pre>' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) . '</pre>' );

print json_encode (array ("result" => "ok"));
}else{ //💡두 ID 일치하지 않으면 에러
        print json_encode(array("result" => "fail"));
}

exit;
?>
        