<?php
App::uses('Helper', 'View');
class AppHelper extends Helper {
  function printPhoto($photo = array(), $photoCount = null){

    // debug($photo);

   $now = new DateTime("now");
   $datePhoto = new DateTime($photo['Photo']['created']);
   $interval = $datePhoto->diff($now);
   if ($interval->y > 0) {
    if ($interval->y == 1) {
      $timeHere = $interval->y." year";  
    } else {
      $timeHere = $interval->y." years";
    }
   } elseif ($interval->m > 0) {
    if ($interval->m == 1) {
      $timeHere = $interval->m." month";  
    } else {
      $timeHere = $interval->m." monthes";
    }
   } elseif ($interval->d > 0) {
    if ($interval->d == 1) {
      $timeHere = $interval->d." day";  
    } else {
      $timeHere = $interval->d." days";
    }
   } elseif ($interval->h > 0) {
   if ($interval->h == 1) {
      $timeHere = $interval->h." hour";  
    } else {
      $timeHere = $interval->h." hours";
    }
   } else {
    if ($interval->i == 1) {
      $timeHere = $interval->i." minute"; 
    } else {
      $timeHere = $interval->i." minutes"; 
    }
   }
  ?>
  <div class="photo-row row" data-rating="<?=$photo['Photo']['rating']?>">
    <div class="columns small-12 medium-8 medium-offset-2">
      <div class="photo-panel">
        <div class="panel">
          <div class="row photo-image">
            <div class="columns small-12">
              <?php if ($photo['Photo']['amazonUrl']): ?>
                <img class="unveil" src="/img/blank.gif" data-src="<?=$photo['Photo']['amazonUrl']?>" />
              <?php else: ?>
                <img src="http://thumbs.dreamstime.com/z/cloud-face-blowing-wind-character-cartoon-36049178.jpg" />
              <?php endif; ?>
            </div>
          </div>
          <div class="row photo-meta">
            <div class="columns small-6">
              <h5><span class="img-user">
                <a href="/users/profile/<?php echo $photo['User']['id']; ?>"><?=$photo['User']['username'];?></a>
              </span></h5>
              <?php // ************ TODO @BILLY RANK ?>
              <h6><span class="rank"><?
              if(isset($photo["Photo"]["rank"])):
              	// echo "Ranked #".$photo["Photo"]["rank"]." / ".$photoCount." ";

              	endif;
              ?></span><span class="secondary">for <?=$timeHere?></span></h6>
            </div>
            <div class="columns small-6 text-right">
              <h4 class="score"> 
                <?php
                $score = round($photo['Photo']['rating']*100, 2);
                if ($score > 0): ?>
                <span class="hel">💥</span>&nbsp;<span class="img-score"><?=$score?></span>
              <?php else: ?>
                <span class="hel">💩</span>&nbsp;<span class="red img-score"><?=$score?></span>
              <?php endif; ?>
            </h4>
          </div>
        </div>
        <?php if(isset($photo["Tag"])): ?>
          <a href="#tags" class="show-tags">Show Concepts</a>
        <?php endif; ?>
        <div class="row tags">
          <div class="columns small-12">
          <?
          if(isset($photo["Tag"])){
            foreach($photo["Tag"] as $i=>$tag){
              echo "<a href='".$this->webroot."tags/view/".$tag["id"]."'>";
              echo $tag["name"]."(".$tag["photo_count"].")";
              echo "</a>";
              echo $i == count($photo["Tag"]) - 1 ? "" : " ";
              
            }
          }
          ?>
          </div>
          </div>
      </div>
    </div>
  </div>
</div><?
}
}
?>