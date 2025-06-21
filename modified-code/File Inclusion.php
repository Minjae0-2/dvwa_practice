<?php

// The page we wish to display
$file = $_GET[ 'page' ];
$file = basename($file); //💡 basename() 사용하여 디렉토리 경로 제거하고 파일 이름만 추출

$allowed_files = array( //💡 허용된 파일만 include하기 위해
        'file1.php',
        'file2.php',
        'file3.php',
        'include.php'
);

if(!in_array($file,$allowed_files)){ //💡요청한 파일이 배열에 없다면 에러메시지 출력
echo 'Error: Invalid file requested.';
exit;
}

?>
