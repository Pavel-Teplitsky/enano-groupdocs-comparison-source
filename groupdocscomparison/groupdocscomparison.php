<?php
   
    $embedKey = $_POST['groupdocs_embed_key'];
    $fileGuId = $_POST['groupdocs_file_id'];
    $embed = preg_replace('/[^a-z0-9]+/', '', trim($embedKey));
    $fileId = preg_replace('/[^a-z0-9]+/', '', trim($fileGuId));
    $main = $_SERVER['HTTP_REFERER'];
    header('Location: ' . $main . '&embedKey=' . $embed . '&fileId=' . $fileId);

?>
