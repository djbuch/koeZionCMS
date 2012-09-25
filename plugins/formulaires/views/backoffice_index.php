<div class="section">
	<div class="box">
		
		<?php $this->element(PLUGINS.'/formulaires/views/elements/backoffice/title', null, false); ?>		
		<div class="content">
			<?php 
			if($pager['totalElements'] > 0) { 
				
				$formOptions = array('action' => Router::url('backoffice/'.$params['controllerFileName'].'/massive_delete'), 'method' => 'post', 'id' => 'formDelete');
				echo $helpers['Form']->create($formOptions);
				?>
				<table cellspacing="0" cellpadding="0" border="0">					
					<?php $this->element(PLUGINS.DS.'formulaires/views/elements/backoffice/thead', null, false); ?>
					<?php $this->element(PLUGINS.DS.'formulaires/views/elements/backoffice/tbody', null, false); ?>
					<?php $this->element(PLUGINS.DS.'formulaires/views/elements/backoffice/tfoot', null, false); ?>					
				</table>
				<?php 
				echo $helpers['Form']->end(false); 
				$this->element('backoffice/pagination');
			} else { 
				
				?><p>Aucun élément</p><?php 
			}
			?>
		</div>
	</div>
</div>