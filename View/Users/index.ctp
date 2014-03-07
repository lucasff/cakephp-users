

<?php



/**

 * @var $this View

 */


$this->Html->addCrumb(__d('SolutionsCMS', 'Users'));

?>

<div class="row">
    <div class="col-md-12">
        <h2 class="page-title"><?php echo __d('SolutionsCMS', 'Index User'); ?></h2>
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
                    <i class="fa fa-list">&nbsp;<?php echo __d('SolutionsCMS', 'Index the User information'); ?>64
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
                        sprintf('%s <i class="fa fa-plus"></i>', __d('SolutionsCMS', 'Add New')),
                        array('action' => 'add'),
                        array(
                            'escape' => false,
                            'class'  => 'btn green'
                        )
                    ); ?>
                </div>

                <table class="table table-hover table-bordered table-striped table-condensed flip-content"
                       data-model="User">
                    <thead>
                    <tr>
                                                <th><?php echo $this->Paginator->sort('id'); ?></th>
                                                <th><?php echo $this->Paginator->sort('first_name'); ?></th>
                                                <th><?php echo $this->Paginator->sort('last_name'); ?></th>
                                                <th><?php echo $this->Paginator->sort('birthday'); ?></th>
                                                <th><?php echo $this->Paginator->sort('gender'); ?></th>
                                                <th><?php echo $this->Paginator->sort('email'); ?></th>
                                                <th><?php echo $this->Paginator->sort('username'); ?></th>
                                                <th><?php echo $this->Paginator->sort('password'); ?></th>
                                                <th><?php echo $this->Paginator->sort('created'); ?></th>
                                                <th><?php echo $this->Paginator->sort('modified'); ?></th>
                                                <th><?php echo $this->Paginator->sort('last_login'); ?></th>
                                                <th class="actions"><?php echo __('Actions'); ?></th>
                    </tr>
                    </thead>

                    <?php foreach ($users as $user): ?>
	<tr>
		<td><?php echo h($user['User']['id']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['first_name']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['last_name']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['birthday']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['gender']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['email']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['username']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['password']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['created']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['modified']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['last_login']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(

                            				sprintf('<i class="fa fa-info"></i>&nbsp;&nbsp;%s</a>', __d('SolutionsCMS', 'View')),

                             				array('action' => 'view', $user['User']['id']),

                             				array(

                                    					'escape'         => false,

                                    					'data-id'        => $user['User']['id'],

                                    					'class'          => 'btn btn-sm blue btn-editable',

                                    					'original-title' => __d('SolutionsCMS', 'View')

                                				)
                             				); ?>
			<?php echo $this->Html->link(sprintf('<i class="fa fa-edit"></i>&nbsp;&nbsp;%s</a>', __d('SolutionsCMS', 'Edit')),

                        				array(

                            					'action' => 'edit',

                            					$user['User']['id']

                            				),

                            				array(

                                					'escape'         => false,

                                					'data-id'        => $user['User']['id'],

                                					'class'          => 'btn btn-sm green btn-editable',

                                					'original-title' => __d('SolutionsCMS', 'Edit')

                            				)

                            ); ?>
			<?php echo $this->Form->postLink(
                        				sprintf('<i class="fa fa-times"></i>&nbsp;&nbsp;%s</a>', __d('SolutionsCMS', 'Delete')),

                        				array(

                            					'action' => 'delete',

                            					$user['User']['id']),

                            					null,

                            					__('Are you sure you want to delete # %s?', $user['User']['id'])
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
