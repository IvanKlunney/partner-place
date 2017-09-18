<?php define("BLOCK_ID", 186) ?>
<div class="row hide-me" id="home">
	<div class="col l12 m12 s12 offset-l1">
  <?php if(isset($p)) {?>
		<div class="divider"></div>	
		  <ul class="collapsible" data-collapsible="expandable">
    		<li class="active">
      			<div class="collapsible-header active"><i class="material-icons">info</i>О нас</div>
      			<div class="collapsible-body">
      						<?=$p->select([
									'table' => 'b_iblock_section',
									'where' => 
  									['key' => 'id', 'value' => BLOCK_ID]
									])->fetching('DESCRIPTION');?>
      			</div>
    		</li>
    		<li>
      			<div class="collapsible-header"><i class="material-icons">description</i>Принципы работы компании</div>
      			<div class="collapsible-body"> 
                <?=$p->select([
                  'table' => 'b_uts_iblock_46_section',
                  'where' => 
                    ['key' => 'value_id', 'value' => BLOCK_ID]
                ])->fetching('UF_W_COMPANY');?>  
            </div>
    		</li>
    		<li>
    			<div class="collapsible-header"><i class="material-icons">description</i>Принципы работы с ITSRB</div>
    			<div class="collapsible-body">
                  <?=$p->select([
                  'table' => 'b_uts_iblock_46_section',
                  'where' => 
                    ['key' => 'value_id', 'value' => BLOCK_ID]
                  ])->fetching('UF_WK_WITH_ITSRB');?>     
          </div>
    		</li>
    		<li>
    			<div class="collapsible-header"><i class="material-icons">label</i>Используемые инструменты для работы</div>
    			 <div class="collapsible-body">
                  <?=$p->select([
                  'table' => 'b_uts_iblock_46_section',
                  'where' => 
                    ['key' => 'value_id', 'value' => BLOCK_ID]
                  ])->fetching('UF_TOOL_F_J');?>     
          </div>
    		</li>
    		<li>
    			<div class="collapsible-header"><i class="material-icons">contact_phone</i>Контактные лица</div>
    			 <div class="collapsible-body">
                  <?=$p->select([
                  'table' => 'b_uts_iblock_46_section',
                  'where' => 
                    ['key' => 'value_id', 'value' => BLOCK_ID]
                  ])->fetching('UF_W_CONTACTS');?>     
          </div>
    		</li>
		  </ul>
  <?php } ?>
	</div>
</div>