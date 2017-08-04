<div class="row profile-header">
  <div class="columns small-12 medium-8 medium-offset-2 large-6 large-offset-3">
    <h2 id="profile-username"><?=$tag["Tag"]["name"]?></h2>
    <div class="tag-count-circle"><?=$tag["Tag"]["photo_count"]?></div>
    </div>
  </div>

<?
foreach($tag["Photo"] as $photo):
  $photo = array("Photo"=>$photo, "User"=>$photo["User"]);
  $this->App->printPhoto($photo, $photoCount);
endforeach;
?>
