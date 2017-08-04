<?
ob_start();
$this->App->printPhoto($photo, $photoCount);
$output = ob_get_contents();
ob_end_clean();
$photo["output"] = $output;
echo json_encode($photo);?>