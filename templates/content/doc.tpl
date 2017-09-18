<div class="row hide-me" id="doc">
<div class="col l12 m12 s12 offset-l1">
    <div class="divider"></div>
        <?php
          $i = 0;
          $key = "common";
          while ($i < count($d[$key]['Files'])) {?>
                     <p class="docline" style="background-image: url(<?=$o->get_ext_for_out($d[$key]['Files'][$i]['ext']);?>)">
                      <?php $b64 = base64_encode(urlencode($d[$key]['Dir'].'/'.$d[$key]['Files'][$i]['fname'].'.'.$d[$key]['Files'][$i]['ext'])); ?>
                      <a href="controller.php?d=<?=$b64;?>"><?=$d[$key]['Files'][$i]['fname']?></a>
                    </p>
                    <div class="divider"></div>
            <?php $i++; ?>
        <?php } ?>
  </div>
</div>