<?php

function vm_handleAttachment($file_handler,$post_id,$set_thu=false) {
    // check to make sure its a successful upload
    if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) __return_false();
  
    require_once(ABSPATH . "wp-admin" . '/includes/image.php');
    require_once(ABSPATH . "wp-admin" . '/includes/file.php');
    require_once(ABSPATH . "wp-admin" . '/includes/media.php');
    require_once(ABSPATH . "wp-admin" . '/includes/post.php');
    
    $attach_id = media_handle_upload( $file_handler, $post_id );

    // If you want to set a featured image frmo your uploads. 
    //if ($set_thu) set_post_thumbnail($post_id, $attach_id);

    $filePath = get_attached_file($attach_id);
    //url adresa  $filePath = wp_get_attachment_url($attach_id);
    return $filePath;
  }