<?php

if( isset( $_POST[ 'Upload' ] ) ) {

        //💡허용할 파일 확장자 배열
        $allowed_extensions= ['jpg','jpeg','png'];

        //💡업로드 된 파일 확장자(소문자로)
        $file_extension= strtolower(pathinfo($_FILES['uploaded']['name'],PATHINFO_EXTENSION));

        /*💡업로드 된 파일 확장자가 위 허용된 배열에 존재하는지 확인 */
        if(!in_array($file_extension,$allowed_extensions)){
                $html .= '<pre>Not Allowed Extension.</pre>';
        }else{
        // Where are we going to be writing to?
        $target_path  = DVWA_WEB_PAGE_TO_ROOT . "hackable/uploads/";
        $target_path .= basename( $_FILES[ 'uploaded' ][ 'name' ] );

        // Can we move the file to the upload folder?
        if( !move_uploaded_file( $_FILES[ 'uploaded' ][ 'tmp_name' ], $target_path ) ) {
                // No
                $html .= '<pre>Your image was not uploaded.</pre>';
        }
        else {
                // Yes!
                $html .= "<pre>{$target_path} succesfully uploaded!</pre>";
        }
}
}
?>
