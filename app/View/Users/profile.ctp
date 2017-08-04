<?
 //debug($this->data);

?>

<div class="row profile-header">
  <div class="columns small-12">
    <h2 id="profile-username"><?=$this->data['User']['username']?></h2>

    <?php //********TODO @billy -- best/worst possibilities ?>
    <?
        $best = false;
        $worst = false;
    if (isset($this->data["Photo"])):
    foreach($this->data["Photo"] as $i=>$photo){

      if($i>4 && $i < count($this->data["Photo"]) -5){
        continue;
      }else{
              //debug($i."-".$photo["Rank"]);
      

        if($photo["rank"] < 6){
          $best = true;
          }
        if($photo["rank"] > $photoCount-5){
          $worst = true;
          }

      }
    }
    endif;
    if($best){
      ?><h4>Congratulations.<br/>
             You are still in the lead with one of the best! <span class="hel">💥</span></h4><?
    }

    if($worst){
      echo "<h4>";
      if(!$best){
        ?>Congratulations.<?
      }else{
        echo "&";
      }
     ?> <br/>
             You are still in last with one of the worst! <span class="hel">💩</span></h4><?
    }

    ?>
     
    <div class="actions">
    <? if($this->Session->read("Auth.User.id") == $this->data["User"]["id"]):?>
    <a id="show-profile-editor" href="#edit-profile">Edit Profile</a><br/>
    <?endif;?>
    Not you<span class="hell">?</span> <a href="/users/session">Click here to log in</a>
    </div>
  </div>
</div>

<div id="profile-editor" class="row">
  <div class="columns small-12 medium-8 medium-offset-2 large-6 large-offset-3">
    <div class="users form">
    <?php echo $this->Form->create('User'); ?>
      <fieldset>
        <legend><?php echo __('Validate'); ?></legend>
      <?php
        echo $this->Form->input('id');
        echo $this->Form->input('username');
        echo $this->Form->input('email');
        // echo $this->Form->input('validated');
        // echo $this->Form->input('validationCode');
        // echo $this->Form->input('group');
        // echo $this->Form->input('photos_count');
      ?>
      </fieldset>
    <?php echo $this->Form->end(__('Save')); ?>
    </div>
  </div>
</div>

<div id="profile-photos">
<?
if (isset($this->data["Photo"])):
foreach($this->data["Photo"] as $photo):
  $photo = array("Photo"=>$photo, "User"=>$this->data["User"], "Tag"=>$photo["Tag"]);
 $this->App->printPhoto($photo, $photoCount);

endforeach;
endif;
?>
</div>