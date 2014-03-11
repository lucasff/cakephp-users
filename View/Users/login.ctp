<?php /** @var $this View */ ?>

<div class="row">
    <div class="col-md-2 col-md-offset-5">
        <?php echo $this->Form->create(
            'Users.User',
            [
                'inputDefaults' => array(
                    'div'       => 'form-group',
                    'wrapInput' => false,
                    'class'     => 'form-control'
                ),
                'class'         => 'well'
            ]
        ); ?>

        <?php echo $this->Form->input('User.username'); ?>
        <?php echo $this->Form->input('User.password'); ?>

        <?php echo $this->Form->submit(__('Submit'), ['class' => 'btn btn-primary']); ?>

        <?php echo $this->Form->end(); ?>


    </div>
</div>
