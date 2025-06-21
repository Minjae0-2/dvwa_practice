<?php

if( isset( $_POST[ 'Submit' ]  ) ) {
        // Get input
        $target = escapeshellcmd($_REQUEST[ 'ip' ]); //💡escapeshellcmd()로 특수문자를 이스케이프 처리 

        // Determine OS and execute the ping command.
        if( stristr( php_uname( 's' ), 'Windows NT' ) ) {
                // Windows
                $cmd = shell_exec( 'ping  ' . $target );
        }
        else {
                // *nix
                $cmd = shell_exec( 'ping  -c 4 ' . $target );
        }

        // Feedback for the end user
        $html .= "<pre>{$cmd}</pre>";
}

?>
