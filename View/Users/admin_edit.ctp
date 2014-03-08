<?php

/**
 * @var $this View
 */
$this->Html->addCrumb(__d('admin', 'Users'));
$this->Html->addCrumb(__d('admin', 'Edit %s User', $this->Form->value('User.id')));
?>
<div class="row">
    <div class="col-md-12">
        <h2 class="page-title"><?php echo __d('admin', 'Admin Edit User'); ?></h2>
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
                    <?php echo __d('admin', 'Admin Edit the User information'); ?>
                </div>
            </div>

            <div class="portlet-body form users">
                <div class="form-body">
                    <?php echo $this->Form->create(
                        'User',
                        array(
                            'inputDefaults' => array(
                                'div' => 'form-group',
                                'wrapInput' => 'col col-md-9',
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
                    echo $this->Form->hidden('id');
                    echo $this->Form->input('first_name',
                        array(
                            'label' => array(
                                'text' => 'First Name'
                            )
                        )
                    );
                    echo $this->Form->input('last_name',
                        array(
                            'label' => array(
                                'text' => 'Last Name'
                            )
                        )
                    );
                    echo $this->Form->input('birthday',
                        array(
                            'label' => array(
                                'text' => 'Birthday'
                            ),
                            'class' => ''
                        )
                    );
                    echo $this->Form->input('gender',
                        array(
                            'label' => array(
                                'text' => 'Gender'
                            )
                        )
                    );
                    echo $this->Form->input('email',
                        array(
                            'label' => array(
                                'text' => 'Email'
                            )
                        )
                    );
                    echo $this->Form->input('username',
                        array(
                            'label' => array(
                                'text' => 'Username'
                            )
                        )
                    );
                    echo $this->Form->input('password',
                        array(
                            'placeholder' => __d('admin', 'Leave blank if you do not want to change'),
                            'beforeInput' => '<i class="fa fa-lock"></i>',
                            'wrapInput' => 'input-icon col-md-4'
                        )
                    );
                    ?>

                    <div class="form-actions">

                        <?php echo $this->Form->submit(__d('admin', 'Submit'), array(
                            'div' => false,
                            'class' => 'btn blue',
                            'escape' => false,
                        )); ?>
                        <?php echo $this->Form->postLink(
                            __d('admin', 'Delete %s', '<i class="fa fa-times"></i>'),
                            array(
                                'action' => 'delete',
                                $this->Form->value('User.id')
                            ),
                            array('class' => 'btn red', 'escape' => false),
                            __d('admin', 'Are you sure you want to delete %s # %s?', __d('admin', 'User'), $this->Form->value('User.id'))
                        ); ?>

                    </div>

                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
