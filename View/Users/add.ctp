<?php

/**
 * @var $this View
 */

?>
<div class="row">
	<div class="col-md-12">
		<h2 class="page-title"><?php echo __d('users', 'Add User'); ?></h2>
	</div>
</div>

<div class="row">

	<?php echo $this->Session->flash(); ?>

	<?php echo $this->Form->create(
		'User',
		array(
			'inputDefaults' => array(
				'div'        => 'form-group',
				'wrapInput'  => 'col-md-6',
				'label'      => array(
					'class' => 'col-md-3 control-label'
				),
				'class'      => 'form-control',
				'novalidate' => 'novalidate'
			),
			'class'         => 'form-horizontal',
		)
	); ?>

	<div class="col-md-6">

		<?php

		echo $this->Form->input('first_name');
		echo $this->Form->input('last_name');
		echo $this->Form->input('birthday',
		[
			'dateFormat' => 'DMY',
		    'class' => '',
		]);
		echo $this->Form->input('gender');
		echo $this->Form->input('email');

		?>

	</div>

	<div class="col-md-6">

		<?php

		echo $this->Form->input('username');
		echo $this->Form->input('password');

		?>

	</div>
</div>

<div class="row">

	<div class="form-actions">

		<?php echo $this->Form->submit(__d('users', 'Submit'), array(
			'div'    => false,
			'class'  => 'btn blue',
			'escape' => false,
		)); ?>

	</div>

	<?php echo $this->Form->end(); ?>

</div>
