<?php
  define("IMAGE", 110);
  define("IMAGE_INDEX", 6);
  define("RULE", 113);
  define("SLA", 114);
  define("REP", 115);
  define("FEE", 116);
  define("CONTACTS", 117);
?>
<div class="row hide-me" id="client">
	<div class="col l12 m12 s12 offset-l1">	
    <?php if(isset($p)) { ?>    
        <div class="divider"></div>
        <?php
        $i = 0;
        while ($i < count($_SESSION['clients'])) {
          $elemid = $p->select([
              'table' => 'b_iblock_element',
              'where' => ['key' => 'code', 'value' => $_SESSION['clients'][$i]['Code'] ]
              ])->fetching('ID');
          if(isset($elemid)) {
          ?>
  			<ul class="inner-menu-desc collapsible hide-me" id="<?=$_SESSION['clients'][$i]['Code']?>" data-collapsible="expandable">
    		<li class="active clearfix">
      			<div class="collapsible-header active"><i class="material-icons">info</i>Справочная информация о клиенте</div>
      			<div class="collapsible-body first" style="display: inline-block;">
                <?php
                  $j = 0;  
                  $value_nums = $p->select([
                    "table" => "b_iblock_element_property",
                    "where" =>  [
                                  "key" => "iblock_element_id",
                                  "value" => "{$elemid} AND iblock_property_id=".IMAGE
                                ]
                  ])->fetchingAll(IMAGE_INDEX);
                  
                  while($j < count($value_nums)) {
                    $v  = intval($value_nums[$j]);
                    $fn = $p->select([
                            "table" => "b_file",
                            "where" => [
                                          "key" => "ID", 
                                          "value" => "{$v}"
                                       ]
                          ])->fetching("FILE_NAME");

                    $subdir = $p->select([
                            "table" => "b_file",
                            "where" => [
                                          "key" => "ID",
                                          "value" => "{$v}"
                                       ]
                          ])->fetching("SUBDIR");
                    $v = "upload/{$subdir}/{$fn}";
                    $j++;
                      if($fn) {
                ?>
                <img src="<?=$v?>" alt="image" class="z-depth-5 materialboxed">
                <?php } ?>
              <?php } ?>
              <div>
                  <?=$p->select([
                'table' => 'b_iblock_element',
                'where' => 
                  ['key' => 'id', 'value' => $elemid]
                ])->fetching('PREVIEW_TEXT');?>
              </div>
            </div>
    		</li>
    		<li>
      			<div class="collapsible-header"><i class="material-icons">description</i>Принципы и стандарты работы с клиентом</div>
      			<div class="collapsible-body"> 
                <?=unserialize($p->select(['table' => 'b_iblock_element_property',
                                           'where' => 
                                                        [ 'key' => 'iblock_element_id', 'value' => "{$elemid} AND iblock_property_id=".RULE ]
                                          ])->fetching('VALUE'))['TEXT'];?>  
            </div>
    		</li>
    		<li>
    			<div class="collapsible-header"><i class="material-icons">report_problem</i>SLA, критерии оценки клиентом проводимых работ</div>
    			<div class="collapsible-body">
                <?=unserialize($p->select(['table' => 'b_iblock_element_property',
                            'where' => 
                                         [ 'key' => 'iblock_element_id', 'value' => "{$elemid} AND iblock_property_id=".SLA ]
                           ])->fetching('VALUE'))['TEXT'];?>       
          </div>
    		</li>
        <li>
          <div class="collapsible-header"><i class="material-icons">library_books</i>Техническая документация</div>
          <div class="collapsible-body">
              <?php
                $code = $_SESSION['clients'][$i]['Code'];
                if(null !== $d[$code]) {
                  $idx = 0;
                  while($idx < count($d[$code]['Files'])) {
              ?>  
              <p class="docline" style="background-image: url(<?=$o->get_ext_for_out($d[$code]['Files'][$idx]['ext']);?>)">
                <?php $b64 = base64_encode(urlencode($d[$code]['Dir'].'/'.$d[$code]['Files'][$idx]['fname'].'.'.$d[$code]['Files'][$idx]['ext'])); ?>
                <a href="controller.php?d=<?=$b64;?>"><?=$d[$code]['Files'][$idx]['fname']?></a>
              </p>  
               <div class="divider"></div>
                    <?php $idx++ ?>
                  <?php } ?>
              <?php } ?>
          </div>
        </li>
    		<li>
    			<div class="collapsible-header"><i class="material-icons">work</i>Отчетные документы для клиента</div>
    			 <div class="collapsible-body">
                <?=unserialize($p->select(['table' => 'b_iblock_element_property',
                           'where' => 
                                        [ 'key' => 'iblock_element_id', 'value' => "{$elemid} AND iblock_property_id=".REP ]
                          ])->fetching('VALUE'))['TEXT'];?>  
          </div>
    		</li>
    		<li>
    			<div class="collapsible-header"><i class="material-icons">payment</i>Стоимость и оплата работ</div>
    			 <div class="collapsible-body">
                <?=unserialize($p->select(['table' => 'b_iblock_element_property',
                           'where' => 
                                        [ 'key' => 'iblock_element_id', 'value' => "{$elemid} AND iblock_property_id=".FEE ]
                          ])->fetching('VALUE'))['TEXT'];?>   
          		</div>
    		</li>
    		<li>
    			<div class="collapsible-header"><i class="material-icons">contact_phone</i>Контактные лица</div>
    			 <div class="collapsible-body">
                <?=unserialize($p->select(['table' => 'b_iblock_element_property',
                           'where' => 
                                        [ 'key' => 'iblock_element_id', 'value' => "{$elemid} AND iblock_property_id=".CONTACTS ]
                          ])->fetching('VALUE'))['TEXT'];?>       
          </div>
    		</li>
		  </ul>
      <?php } ?>
      <?php $i++; ?>
      <?php } ?>
    <?php } ?>
	</div>
</div>