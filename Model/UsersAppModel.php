<?php

App::uses('AppModel', 'Model');

class UsersAppModel extends AppModel {

	/**
	 * Plugin name
	 *
	 * @var string $plugin
	 */
	public $plugin = 'Users';

	/**
	 * Recursive level for finds
	 *
	 * @var integer
	 */
	public $recursive = -1;

	/**
	 * Behaviors
	 *
	 * @var array
	 */
	public $actsAs = array(
		'Containable'
	);

}
