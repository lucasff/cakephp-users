
<?php



/**

 * @var $this View

 */


$this->Html->addCrumb(__d('SolutionsCMS', 'Users'));

$this->Html->addCrumb(__d('SolutionsCMS', 'Add %s User', $this->Form->value('User.id')));

?>
<div class="row">
    <div class="col-md-12">
        <h2 class="page-title"><?php echo __d('SolutionsCMS', 'Add User'); ?></h2>
                <?php echo $this->Html->getCrumbList(
            array(
                'class' => 'breadcrumb',
                'separator' => '<i class="icon-angle-right"></i>',
                'escape' => false
            ),
            '<i class="icon-home"></i> Home'
        );
        ?>    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <?php echo $this->Session->flash(); ?>
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    <?php echo __d('SolutionsCMS', 'Add the User information'); ?>62                </div>
            </div>

            <div class="portlet-body form users">
                <div class="form-body">
                    <?php echo $this->Form->create(

                        'User',

                        		array(

                            			'inputDefaults' => array(

                                				'div' => 'form-group',

                                				'wrapInput' => 'col-md-4',

                                				'label' => array(

                                    					'class' => 'col col-md-3 control-label'

                                				),

                                				'class' => 'form-control',

                                				'novalidate' => 'novalidate'

                            			),

                            			'class' => 'form-horizontal',

                        		)

                    ); ?>

                    	<?php
		echo $this->Form->input('first_name');
		echo $this->Form->input('last_name');
		echo $this->Form->input('birthday');
		echo $this->Form->input('gender');
		echo $this->Form->input('email');
		echo $this->Form->input('username');
		echo $this->Form->input('password');
		echo $this->Form->input('last_login');
	?>

                    <div class="form-actions">

                        <?php echo $this->Form->submit(__d('SolutionsCMS', 'Submit'), array(
                            'div' => false,
                            'class' => 'btn blue',
                            'escape' => false,
                        )); ?>
                        <?php echo $this->Form->postLink(
                            __d('SolutionsCMS', 'Delete %s', '<i class="fa fa-times"></i>'),
                            array(
                                'action' => 'delete',
                                $this->Form->value('User.id')
                            ),
                            array('class' => 'btn red', 'escape' => false),
                            __d('SolutionsCMS', 'Are you sure you want to delete %s # %s?', __d('SolutionsCMS', 'System User'), $this->Form->value('User.id'))
                        );

                    </div>

                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
