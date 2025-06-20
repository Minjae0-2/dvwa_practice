<?php

header ("X-XSS-Protection: 0");

// Is there any input?
if( array_key_exists( "name", $_GET ) && $_GET[ 'name' ] != NULL ) {
        // Feedback for end user
        /* htmlspecialchars()를 이용해 <script></script> 같은 경우 '<' -> &lt, '>' -> &gt; 로 변환
        따라서 단순 텍스트로 변환한다. END_QUOTES-> ' " 도 변환 */
        $html .= '<pre>Hello ' .htmlspecialchars( $_GET[ 'name' ], ENT_QUOTES, 'UTF-8') . '</pre>';
}

?>

