<?php

/**
 * @var $this View
 */
$this->Html->addCrumb(__d('admin', 'Users'));

?>

<div class="row">
    <div class="col-md-12">
        <h2 class="page-title"><?php echo __d('admin', 'Admin Index User'); ?></h2>
        <?php echo $this->Html->getCrumbList(
            array(
                'class' => 'breadcrumb',
                'separator' => '<i class="icon-angle-right"></i>',
                'escape' => false
            ),
            '<i class="icon-home"></i> Home'
        );
        ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <?php echo $this->Session->flash(); ?>

        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-list"></i>&nbsp;<?php echo __d('admin', 'Admin Index the User information'); ?>
                    <div class="tools">
                        <a href="javascript:;" class="collapse"></a>
                        <a href="#portlet-config" data-toggle="modal" class="config"></a>
                        <a href="javascript:;" class="reload"></a>
                        <a href="javascript:;" class="remove"></a>
                    </div>
                </div>
            </div>

            <div class="portlet-body">

                <div class="table-toolbar">
                    <?php echo $this->Html->link(
                        sprintf('%s <i class="fa fa-plus"></i>', __d('admin', 'Add New')),
                        array('action' => 'add'),
                        array(
                            'escape' => false,
                            'class' => 'btn green'
                        )
                    ); ?>
                </div>

                <table class="table table-hover table-bordered table-striped table-condensed flip-content"
                       data-model="User">
                    <thead>
                    <tr>
                        <th><?php echo $this->Paginator->sort('full_name', __d('users', 'Full Name')); ?></th>
                        <th><?php echo $this->Paginator->sort('birthday', __d('users', 'Data de Nascimento')); ?></th>
                        <th><?php echo $this->Paginator->sort('gender', __d('users', 'Sexo')); ?></th>
                        <th><?php echo $this->Paginator->sort('email', __d('users', 'E-mail')); ?></th>
                        <th><?php echo $this->Paginator->sort('username', __d('users', 'Usuário')); ?></th>
                        <th><?php echo $this->Paginator->sort('created', __d('users', 'Criado')); ?></th>
                        <th><?php echo $this->Paginator->sort('last_login', __d('users', 'Último Login')); ?></th>
                        <th class="actions"><?php echo __d('admin', 'Actions'); ?></th>
                    </tr>
                    </thead>

                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo h($user['User']['full_name']); ?>&nbsp;</td>
                            <td><?php echo $this->Time->format('d/m/Y', $user['User']['birthday']); ?>&nbsp;</td>
                            <td><?php echo h($user['User']['gender']); ?>&nbsp;</td>
                            <td><?php echo h($user['User']['email']); ?>&nbsp;</td>
                            <td><?php echo h($user['User']['username']); ?>&nbsp;</td>
                            <td><?php echo $this->Time->format('d/m/Y H:i:s', $user['User']['created']); ?>&nbsp;</td>
                            <td><?php echo $user['User']['last_login'] ? $this->Time->format('d/m/Y H:i:s', $user['User']['last_login']) : 'Nunca logado' ?>&nbsp;</td>
                            <td class="actions">
                                <?php echo $this->Html->link(

                                    sprintf('<i class="fa fa-info"></i>&nbsp;&nbsp;%s</a>', __d('admin', 'View')),
                                    array('action' => 'view', $user['User']['id']),
                                    array(
                                        'escape' => false,
                                        'data-id' => $user['User']['id'],
                                        'class' => 'btn btn-sm blue btn-editable',
                                        'original-title' => __d('admin', 'View')
                                    )
                                ); ?>
                                <?php echo $this->Html->link(sprintf('<i class="fa fa-edit"></i>&nbsp;&nbsp;%s</a>', __d('admin', 'Edit')),
                                    array(
                                        'action' => 'edit',
                                        $user['User']['id']
                                    ),
                                    array(
                                        'escape' => false,
                                        'data-id' => $user['User']['id'],
                                        'class' => 'btn btn-sm green btn-editable',
                                        'original-title' => __d('admin', 'Edit')
                                    )
                                ); ?>
                                <?php echo $this->Form->postLink(
                                    sprintf('<i class="fa fa-times"></i>&nbsp;&nbsp;%s</a>', __d('admin', 'Delete')),
                                    array(
                                        'action' => 'delete',
                                        $user['User']['id']),
                                    array(
                                        'escape' => false,
                                        'class' => 'btn btn-sm red btn-removable',
                                    ),
                                    __d('admin', 'Are you sure you want to delete # %s?', $user['User']['id'])
                                );

                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                </table>

            </div>
        </div>
    </div>
</div>
