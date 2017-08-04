
<? //debug($photos);?>
<?php 
$foo = $this->Session->read("Auth.User.email");
$bar = $this->Session->read("Auth.User.username");
if(empty($foo)): ?>
<div id="splash" class="splash-item"></div>
<div id="splash-text" class="splash-item">Float</div>

  <div id="login-signup" class="splash-item">
    <div class="row">
      <div class="columns small-12">
        <form id="splashProfile" action="/profile" method="post">
          <p>Set a username and email</p>
          <input type="hidden" name="data[User][id]" value="<?php echo $this->Session->read("Auth.User.id"); ?>" />
          <input type="text" placeholder="username" required name="data[User][username]" value="<?php echo $bar; ?>" />
          <input id="signup-email" type="email" autocomplete="off" placeholder="Enter your email address" name="data[User][email]" />
          <input type="submit" style="position: absolute; left: -9999px" value="Update" />
        </form>
      </div>
      <div class="columns small-12">
        <a onClick="closeSplash();">Do it later</a>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<div id="best" class="home-index-photos">
  <div class="row profile-header">
  <div class="columns small-12 medium-8 medium-offset-2">
    <h2>Best</h2>
    <h5>outta <?=$photoCount?> Photos<br>(according to <a target="_blank" href="http://eyeem.com">EyeEm</a>'s algorithm)</h5>
    </div>
  </div>
<?php $photoCounter = 0; ?>
<?php foreach($photos as $photo): ?>
  <?php if ($photoCounter++ == 5): ?></div><div id="worst-wave"></div><div id="worst" data-totalphotos="<?=$photoCount?>"><?php endif; ?>
  <?php $this->App->printPhoto($photo, $photoCount);?>
<?php endforeach; ?>
<div class="row profile-header white flipped">
  <div class="columns small-12 medium-8 medium-offset-2">
    <h2>Worst</h2>
    <h5>outta <?=$photoCount?> Photos</h5>
    </div>
  </div>
</div> <!-- end #worst, #worst-wave -->
