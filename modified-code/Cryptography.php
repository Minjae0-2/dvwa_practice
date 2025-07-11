<?php

//💡키 노출 안하기 위해 따로 key.php에 관리
$key_file =__DIR__ .  '/key.php';
if (!file_exists($key_file)) {
    die("Can't find a key.");
}
$key = require($key_file);

//💡AES-256-GCM 암호화 함수
function encrypt($plaintext,$key){
    $iv_length = openssl_cipher_iv_length('aes-256-gcm');
    $iv = openssl_random_pseudo_bytes($iv_length); //💡암호화 초기화 벡터- 매 암호화 시 랜덤하게 생성
    $ciphertext = openssl_encrypt($plaintext, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag);
    //💡 iv+암호화+태그를 하나로 붙여 Base64로 인코딩하여 반환
    return base64_encode($iv . $ciphertext . $tag);

}

//💡AES-256-GCM 복호화 함수
function decrypt($base64_ciphertext, $key) {
    $decoded = base64_decode($base64_ciphertext);
    if ($decoded === false) {
        return false; 
    }

    $iv_length = openssl_cipher_iv_length('aes-256-gcm');
    $iv = substr($decoded, 0, $iv_length); //💡iv 추출 
    $tag_length = 16; 
    $tag = substr($decoded, -$tag_length); //💡tag 추출 - 이걸로 무결성 검증
    $ciphertext = substr($decoded, $iv_length, -$tag_length); //💡ciphertext 추출 - iv 다음부터, 끝에서 tag 제외한 나머지 부분이 ciphertext

    //💡iv,tag 모두 사용해 복호화, tag 맞지 않으면 false 반환 
    return openssl_decrypt($ciphertext, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag);
}


/* 원래 소스코드
function xor_this($cleartext, $key) {
    // Our output text
    $outText = '';

    // Iterate through each character
    for($i=0; $i<strlen($cleartext);) {
        for($j=0; ($j<strlen($key) && $i<strlen($cleartext)); $j++,$i++) {
            $outText .= $cleartext[$i] ^ $key[$j];
        }
    }
    return $outText;
}
*/

$errors = "";
$success = "";
$messages = "";
$encoded = null;
$encode_radio_selected = " checked='checked' ";
$decode_radio_selected = " ";
$message = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
        try {
                if (array_key_exists ('message', $_POST)) {
                        $message = $_POST['message'];
                        if (array_key_exists ('direction', $_POST) && $_POST['direction'] == "decode") {
                                $encoded = decrypt($message,$key); //💡복호화 실행
                                $encode_radio_selected = " ";
                                $decode_radio_selected = " checked='checked' ";
                        } else {
                                $encoded = encrypt($message,$key); //💡평문을 암호화
                        }
                }
                if (array_key_exists ('password', $_POST)) {
                        $password = $_POST['password'];
                        if ($password == "Olifant") {
                                $success = "Welcome back user";
                        } else {
                                $errors = "Login Failed";
                        }
                }
        } catch(Exception $e) {
                $errors = $e->getMessage();
        }
}

$html = "
                <p>
                This super secure system will allow you to exchange messages with your friends without anyone else being able to read them. Use the box below to encode and decode messages.
                </p>
                <form name=\"xor\" method='post' action=\"" . $_SERVER['PHP_SELF'] . "\">
                        <p>
                                <label for='message'>Message:</lable><br />
                                <textarea style='width: 600px; height: 56px' id='message' name='message'>" . htmlentities ($message) . "</textarea>
                        </p>
                        <p>
                                <input type='radio' value='encode' name='direction' id='direction_encode' " . $encode_radio_selected . "><label for='direction_encode'>Encode</label> or 
                                <input type='radio' value='decode' name='direction' id='direction_decode' " . $decode_radio_selected . "><label for='direction_decode'>Decode</label>
                        </p>
                        <p>
                                <input type=\"submit\" value=\"Submit\">
                        </p>
                </form>
";

if (!is_null ($encoded)) {
        $html .= "
                        <p>
                                <label for='encoded'>Message:</lable><br />
                                <textarea readonly='readonly' style='width: 600px; height: 56px' id='encoded' name='encoded'>" . htmlentities ($encoded) . "</textarea>
                        </p>";
}

$html .= "
                <hr>
                <p>
                You have intercepted the following message, decode it and log in below.
                </p>
                <p>
                <textarea readonly='readonly' style='width: 600px; height: 28px' id='encoded' name='encoded'>Lg4WGlQZChhSFBYSEB8bBQtPGxdNQSwEHREOAQY=</textarea>
                </p>
";

if ($errors != "") {
        $html .= '<div class="warning">' . $errors . '</div>';
}

if ($messages != "") {
        $html .= '<div class="nearly">' . $messages . '</div>';
}

if ($success != "") {
        $html .= '<div class="success">' . $success . '</div>';
}

$html .= "
                <form name=\"ecb\" method='post' action=\"" . $_SERVER['PHP_SELF'] . "\">
                        <p>
                                <label for='password'>Password:</lable><br />
<input type='password' id='password' name='password'>
                        </p>
                        <p>
                                <input type=\"submit\" value=\"Login\">
                        </p>
                </form>
";
?>
