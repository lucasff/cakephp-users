<?php /** @var $this View */ ?>

<style type="text/css">
	body {
		padding-top: 40px;
		padding-bottom: 40px;
		background-color: #eee;
	}

	.form-signin {
		max-width: 330px;
		padding: 15px;
		margin: 0 auto;
	}
	.form-signin .form-signin-heading {
		text-align: center;
	}
	.form-signin .form-signin-heading,
	.form-signin .checkbox {
		margin-bottom: 10px;
	}
	.form-signin .checkbox {
		font-weight: normal;
	}
	.form-signin .form-control {
		position: relative;
		height: auto;
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
		padding: 10px;
		font-size: 16px;
	}
	.form-signin .form-control:focus {
		z-index: 2;
	}
	.form-signin input[type="email"] {
		margin-bottom: -1px;
		border-bottom-right-radius: 0;
		border-bottom-left-radius: 0;
	}
	.form-signin input[type="password"] {
		margin-bottom: 10px;
		border-top-left-radius: 0;
		border-top-right-radius: 0;
	}
</style>

<div class="container">
	<form class="form-signin" role="form">

		<h2 class="form-signin-heading">
			<?php echo __('Please sign in') ?>
		</h2>

		<?php echo $this->Session->flash('auth'); ?>
		<?php echo $this->Session->flash(); ?>

		<?php echo $this->Form->create('Users.User', [
			'inputDefaults' => array(
				'div' => 'form-group',
				'wrapInput' => false,
				'class' => 'form-control'
			),
		]) ?>

		<?php echo $this->Form->input('username', [
			'label'    => [
				'text' => __('Username'),
				'class' => 'control-label sr-only'
			],
			'placeholder' => __('Username'),
			'required' => 'required'
		]); ?>

		<?php echo $this->Form->input('password', [
			'label'    => [
				'text' => __('Password'),
				'class' => 'control-label sr-only'
			],
			'placeholder' => __('Password'),
			'required' => 'required'
		]); ?>

		<?php echo $this->Form->input('remember_me', [
			'type' => 'checkbox',
			'div' => 'checkbox',
			'label'    => __('Remember me'),
		    'class' => false
		]); ?>

		<?php echo $this->Form->submit(__('Sign in'), ['class' => 'btn btn-lg btn-primary btn-block']) ?>

		<?php echo $this->Form->end() ?>
	</form>
</div>