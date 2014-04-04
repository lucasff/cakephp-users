<?php

App::uses('UsersAppModel', 'Users.Model');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

/**
 * User Model
 *
 * @property Subscription $Subscription
 */
class User extends UsersAppModel
{

	public $actsAs = array(/*'Admin.Enum' => array(
            'gender'   => array('M' => 'Masculino', 'F' => 'Feminino'),
        )*/
	);

	public $virtualFields = array(
		'full_name' => 'CONCAT(User.first_name, " ", User.last_name)'
	);

	public $hasMany = array(
		'Address' => array(
			'className'   => 'Address',
			'foreignKey'  => 'user_id',
			'limit'       => '',
			'order'       => '',
			'finderQuery' => '',
			'dependent'   => true
		)
	);

	public function beforeSave($options = array())
	{
		if (isset($this->data[$this->alias]['password'])) {
			$passwordHasher                       = new SimplePasswordHasher();
			$this->data[$this->alias]['password'] = $passwordHasher->hash($this->data[$this->alias]['password']);
		}

		return true;
	}

	/**
	 * Custom validation method to ensure that the two entered passwords match
	 *
	 * @param string $password Password
	 *
	 * @return boolean Success
	 */
	public function confirmPassword($password = null)
	{
		if ((isset($this->data[$this->alias]['password']) && isset($password['temppassword']))
		    && !empty($password['temppassword'])
		    && ($this->data[$this->alias]['password'] === $password['temppassword'])
		) {
			return true;
		}

		return false;
	}

	/**
	 * Compares the email confirmation
	 *
	 * @param array $email Email data
	 *
	 * @return boolean
	 */
	public function confirmEmail($email = null)
	{
		if ((isset($this->data[$this->alias]['email']) && isset($email['confirm_email']))
		    && !empty($email['confirm_email'])
		    && (strtolower($this->data[$this->alias]['email']) === strtolower($email['confirm_email']))
		) {
			return true;
		}

		return false;
	}

	/**
	 * Checks the token for email verification
	 *
	 * @param string $token
	 *
	 * @return array
	 */
	public function checkEmailVerfificationToken($token = null)
	{
		$result = $this->find('first', array(
			'contain'    => array(),
			'conditions' => array(
				$this->alias . '.email_verified' => 0,
				$this->alias . '.email_token'    => $token
			),
			'fields'     => array(
				'id',
				'email',
				'email_token_expires',
				'role'
			)
		));

		if (empty($result)) {
			return false;
		}

		return $result;
	}

	/**
	 * Registers a new user
	 *
	 * Options:
	 * - bool emailVerification : Default is true, generates the token for email verification
	 * - bool removeExpiredRegistrations : Default is true, removes expired registrations to do cleanup when no cron is configured for that
	 * - bool returnData : Default is true, if false the method returns true/false the data is always available through $this->User->data
	 *
	 *
	 * @param array $postData Post data from controller
	 * @param array $options should be an array
	 *
	 * @return mixed
	 */
	public function register($postData = array(), $options = array()) {
		/**
		 * @var boolean $emailVerification
		 * @var boolean $removeExpiredRegistrations
		 * @var boolean $returnData
		 */

		$Event = new CakeEvent(
			'Users.Model.User.beforeRegister',
			$this,
			array(
				'data' => $postData,
				'options' => $options
			)
		);

		$this->getEventManager()->dispatch($Event);
		if ($Event->isStopped()) {
			return $Event->result;
		}

		if (is_bool($options)) {
			$options = array('emailVerification' => $options);
		}

		$defaults = array(
			'emailVerification' => true,
			'removeExpiredRegistrations' => true,
			'returnData' => true
		);
		extract(array_merge($defaults, $options));

		$postData = $this->_beforeRegistration($postData, $emailVerification);

		if ($removeExpiredRegistrations) {
			$this->_removeExpiredRegistrations();
		}

		$this->set($postData);
		if ($this->validates()) {
			$postData[$this->alias]['password'] = Security::hash($postData[$this->alias]['password'], null, true);
			$this->create();
			$this->data = $this->save($postData, false);
			$this->data[$this->alias]['id'] = $this->id;

			$Event = new CakeEvent(
				'Users.Model.User.afterRegister',
				$this,
				array(
					'data' => $this->data,
					'options' => $options
				)
			);

			$this->getEventManager()->dispatch($Event);

			if ($Event->isStopped()) {
				return $Event->result;
			}

			if ($returnData) {
				return $this->data;
			}
			return true;
		}
		return false;
	}


}
