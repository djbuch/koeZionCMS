<div class="box box-primary">
	<div class="box-body">
		<div class="col-md-12">
			<?php
			echo $helpers['Form']->input('layout', _('Layout'), array('compulsory' => true, 'type' => 'select', 'datas' => $layoutsList, 'firstElementList' => _('Sélectionnez un layout')));
			echo $helpers['Form']->input('name', _("Libellé"), array('compulsory' => true, 'tooltip' => _("Indiquez le libellé du style")));
			echo $helpers['Form']->input('element', _("Element HTML"), array('compulsory' => true, 'tooltip' => _("Indiquez l'élément HTML auquel se rapporte le style")));
			echo $helpers['Form']->input('class', _("Classe(s) CSS"), array('tooltip' => _("Indiquez la ou les classes CSS à appliquer")));
			echo $helpers['Form']->input('styles', _("Style(s) CSS"), array('tooltip' => _("Indiquez le ou les styles CSS à appliquer")));
			echo $helpers['Form']->input('online', _('En ligne'), array('type' => 'checkbox', 'tooltip' => _("Cochez cette case pour diffuser ce style")));
			?>
		</div>
	</div>
	<div class="box-footer">
		<div class="col-md-12"><?php echo $helpers['Form']->button(); ?></div>
	</div>
</div>