<?php

App::uses('UsersAppController', 'Users.Controller');

/**
 * Users Controller
 *
 * @package       Users
 * @subpackage    Users.Controller
 *
 * @property User               $User
 * @property Address            $Address
 * @property PaginatorComponent $Paginator
 */
class UsersController extends UsersAppController
{

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array(
		'Session',
		'Paginator',
		'RequestHandler',
		'Auth' => array(
			'loginAction'    => array(
				'plugin'     => 'users',
				'controller' => 'users',
				'action'     => 'login'
			),
			'loginRedirect'  => array(
				'plugin'     => 'users',
				'controller' => 'users',
				'action'     => 'index'
			),
			'logoutRedirect' => array(
				'plugin'       => false,
				'solutionscms' => false,
				'controller'   => 'pages',
				'action'       => 'display',
				'home'
			),
			'authError'      => 'Você não tem permissão para acessar esta página!',
			'authenticate'   => array(
				'Form' => array(
					'userModel' => 'User',
					'fields'    => array(
						'username' => 'email'
					)
				),
			),
			'flash'          => array(
				'params'  => array(
					'class' => 'alert alert-warning'
				),
				'key'     => 'auth',
				'element' => 'default'
			)
		),
	);

	public $uses = ['Users.User', 'Users.Address'];

	protected function _pluginDot()
	{
		if (is_string($this->plugin)) {
			return $this->plugin . '.';
		}

		return $this->plugin;
	}


	public function beforeRender()
	{
		parent::beforeRender();
	}

	public function beforeFilter()
	{
		parent::beforeFilter();
		// $this->set($this->User->enumValues());

		$this->set('model', $this->modelClass);

		$this->_setupAuth();
	}

	protected function _setupAuth()
	{

		if (Configure::read('Users.disableDefaultAuth') === true) {
			return;
		}

		$this->Auth->allow('add', 'reset', 'verify', 'logout', 'view', 'reset_password', 'login', 'resend_verification');

		if (!is_null(Configure::read('Users.allowRegistration')) && !Configure::read('Users.allowRegistration')) {
			$this->Auth->deny('add');
		}

		if ($this->request->action == 'register') {
			$this->Components->disable('Auth');
		}

		if (Configure::read('debug') == 2) {
			$this->Auth->allow('letmein');
		}

		$this->Auth->authenticate = array(
			'Form' => array(
				'fields'    => array(
					'username' => 'email',
					'password' => 'password'
				),
				'userModel' => $this->_pluginDot() . $this->modelClass,
				'scope'     => array(
					$this->modelClass . '.active'         => 1,
					$this->modelClass . '.email_verified' => 1
				)
			)
		);

		$this->Auth->loginRedirect  = '/';
		$this->Auth->logoutRedirect = array(
			'plugin'     => Inflector::underscore($this->plugin),
			'controller' => 'users',
			'action'     => 'login'
		);
		$this->Auth->loginAction    = array(
			'admin'      => false,
			'plugin'     => Inflector::underscore($this->plugin),
			'controller' => 'users',
			'action'     => 'login'
		);

	}

	public function resend_verification() {
		if ($this->request->is('post')) {
			try {
				if ($this->{$this->modelClass}->checkEmailVerification($this->request->data)) {
					//$this->_sendVerifationEmail($this->{$this->modelClass}->data);
					$this->Session->setFlash(__d('users', 'The email was resent. Please check your inbox.'));
					$this->redirect('login');
				} else {
					$this->Session->setFlash(__d('users', 'The email could not be sent. Please check errors.'));
				}
			} catch (Exception $e) {
				$this->Session->setFlash($e->getMessage());
			}
		}
	}

	/**
	 * index method
	 *
	 * @return void
	 */
	public function index()
	{
		$user = $this->User->findById($this->Auth->user('id'));
		unset($user['User']['password']);

		$this->request->data = $user;
		$this->set(compact('user'));
	}

	/**
	 * view method
	 *
	 * @throws NotFoundException
	 *
	 * @param string $id
	 *
	 * @return void
	 */
	public function view($id = null)
	{
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__d('admin', 'User not found.'));
		}
		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
		$this->set('user', $this->User->find('first', $options));
	}

	/**
	 * add method
	 *
	 * @return void
	 */
	public function add()
	{

		if ($this->Auth->loggedIn()) {
			$this->Session->setFlash(__d('users', 'You are already registered and logged in!'), 'alert', array(
				'plugin' => 'BoostCake',
				'class'  => 'alert-info'
			));
			$this->redirect('/');
		}

		if ($this->request->is('post')) {
			$this->User->create();

			$data = $this->request->data;
			$this->User->set($data);

			$this->set('invalidFields', $this->User->invalidFields());

			if ($this->User->save($data)) {

				if (isset($data['Address'])) {
					$this->Address->create();
					$data['Address']['user_id'] = $this->User->getInsertID();

					if ($this->Address->save($data)) {
						$this->Auth->login($data['User']);
						$this->Session->setFlash(__('Your account has been created successfully and now you\'re logged in.'), 'alert', array(
							'plugin' => 'BoostCake',
							'class'  => 'alert-success'
						));
						$this->redirect($this->Auth->redirectUrl());
					} else {
						$this->Session->setFlash(__('The address could not be saved. Please, try again.'), 'alert', array(
							'plugin' => 'BoostCake',
							'class'  => 'alert-danger'
						));
					}

				} else {
					$this->Auth->login($data['User']);
					$this->Session->setFlash(__('Your account has been created successfully and now you\'re logged in.'), 'alert', array(
						'plugin' => 'BoostCake',
						'class'  => 'alert-success'
					));
					$this->redirect($this->Auth->redirectUrl());
				}
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'), 'alert', array(
					'plugin' => 'BoostCake',
					'class'  => 'alert-danger'
				));
			}
		}
	}

	public function login()
	{

		$this->layout = $this->plugin . '.login';

		// Dispatch the event before everything.
		$Event = new CakeEvent(
			'Users.Controller.Users.beforeLogin',
			$this,
			array('data' => $this->request->data)
		);

		$this->getEventManager()->dispatch($Event);

		if ($Event->isStopped()) {
			return;
		}

		if ($this->request->is('post')) {

			if (strpos($this->request->data['User']['username'], '@') !== false) {
				// $this->Auth->authenticate['Form']['fields']['username'] = 'email';
			}

			if ($this->Auth->login()) {
				$Event = new CakeEvent(
					'Users.Controller.Users.afterLogin',
					$this,
					array(
						'data'         => $this->request->data,
						'isFirstLogin' => !$this->Auth->user('last_login')
					)
				);
				$this->getEventManager()->dispatch($Event);

				$this->{$this->modelClass}->id = $this->Auth->user('id');
				$this->{$this->modelClass}->saveField('last_login', date('Y-m-d H:i:s'));

				if ($this->here == $this->Auth->loginRedirect) {
					$this->Auth->loginRedirect = '/';
				}
				$this->Session->setFlash(
					sprintf(__d('users', '%s you have successfully logged in'), $this->Auth->user('first_name'))
				);
				$this->redirect('/users');

			} else {
				$this->Session->setFlash(__d('users', "We couldn't identify you. Please, try again."), 'alert', array(
					'plugin' => 'BoostCake',
					'class'  => 'alert-warning'
				));
			}
		}
	}

	public function letmein()
	{

		$username = array_merge(range('a', 'z'), range('A', 'Z'));
		shuffle($username);
		$username = substr(implode($username), 0, 8);

		$password = uniqid();

		$this->User->create();
		$data['User'] = [
			'id'       => null,
			'name'     => 'Auto Generated User',
			'username' => $username,
			'password' => $password,
			'email'    => sprintf('system%s@software.com', mt_rand())
		];

		if ($this->User->save($data)) {
			$data['User']['id'] = $this->User->getInsertID();
			$this->Auth->login($data);
			$this->Session->setFlash(
				__d('admin', 'The User %s has been created with the following password %s', $username, $password),
				'alert', array(
					'plugin' => 'BoostCake',
					'class'  => 'alert-warning'
				)
			);
			$this->redirect(array('action' => 'index'));
		} else {
			throw new RuntimeException('The Let Me In function has been already used.');
		}

	}


	/**
	 * Common logout action
	 *
	 * @return void
	 */
	public function logout()
	{
		$user = $this->Auth->user();
		$this->Session->destroy();
		/*if (isset($_COOKIE[$this->Cookie->name])) {
			$this->Cookie->destroy();
		}*/
		//$this->RememberMe->destroyCookie();
		$this->Session->setFlash(
			sprintf(__d('users', '%s you have successfully logged out'), $user[$this->{$this->modelClass}->displayField])
		);
		$this->redirect($this->Auth->logout());
	}

	/**
	 * edit method
	 *
	 * @throws NotFoundException
	 *
	 * @param string $id
	 *
	 * @return void
	 */
	public function edit($id = null)
	{
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__d('admin', 'User not found.'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__d('admin', 'The user has been updated successfully.'), 'alert', array(
					'plugin' => 'BoostCake',
					'class'  => 'alert-success'
				));

				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__d('admin', 'The user could not be updated. Please, correct any errors and try again.'), 'alert', array(
					'plugin' => 'BoostCake',
					'class'  => 'alert-danger'
				));
			}
		} else {
			$options             = array('conditions' => array('User.' . $this->User->primaryKey => $id));
			$this->request->data = $this->User->find('first', $options);
			unset($this->request->data['User']['password']);
		}
	}

	/**
	 * delete method
	 *
	 * @throws NotFoundException
	 *
	 * @param string $id
	 *
	 * @return void
	 */
	public function delete($id = null)
	{
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__d('admin', 'User not found.'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->User->delete()) {
			$this->Session->setFlash(__d('admin', 'The user has been deleted successfully.'), 'alert', array(
				'plugin' => 'BoostCake',
				'class'  => 'alert-success'
			));
		} else {
			$this->Session->setFlash(__d('admin', 'The user could not be deleted. Please, try again.'), 'alert', array(
				'plugin' => 'BoostCake',
				'class'  => 'alert-danger'
			));
		}

		return $this->redirect(array('action' => 'index'));
	}


	/**
	 * admin_index method
	 *
	 * @return void
	 */
	public function admin_index()
	{
		$this->User->recursive = 0;
		$this->set('users', $this->Paginator->paginate());
	}

	/**
	 * admin_view method
	 *
	 * @throws NotFoundException
	 *
	 * @param string $id
	 *
	 * @return void
	 */
	public function admin_view($id = null)
	{
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__d('admin', 'User not found.'));
		}
		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
		$this->set('user', $this->User->find('first', $options));
	}

	/**
	 * admin_add method
	 *
	 * @return void
	 */
	public function admin_add()
	{
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__d('admin', 'The user has been saved successfully.'), 'alert', array(
					'plugin' => 'BoostCake',
					'class'  => 'alert-success'
				));

				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__d('admin', 'The user could not be saved. Please, try again.'), 'alert', array(
					'plugin' => 'BoostCake',
					'class'  => 'alert-danger'
				));
			}
		}
	}

	/**
	 * admin_edit method
	 *
	 * @throws NotFoundException
	 *
	 * @param string $id
	 *
	 * @return void
	 */
	public function admin_edit($id = null)
	{
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__d('admin', 'User not found.'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__d('admin', 'The user has been updated successfully.'), 'alert', array(
					'plugin' => 'BoostCake',
					'class'  => 'alert-success'
				));

				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__d('admin', 'The user could not be updated. Please, correct any errors and try again.'), 'alert', array(
					'plugin' => 'BoostCake',
					'class'  => 'alert-danger'
				));
			}
		} else {
			$options             = array('conditions' => array('User.' . $this->User->primaryKey => $id));
			$this->request->data = $this->User->find('first', $options);
			unset($this->request->data['User']['password']);
		}
	}

	/**
	 * admin_delete method
	 *
	 * @throws NotFoundException
	 *
	 * @param string $id
	 *
	 * @return void
	 */
	public function admin_delete($id = null)
	{
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__d('admin', 'User not found.'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->User->delete()) {
			$this->Session->setFlash(__d('admin', 'The user has been deleted successfully.'), 'alert', array(
				'plugin' => 'BoostCake',
				'class'  => 'alert-success'
			));
		} else {
			$this->Session->setFlash(__d('admin', 'The user could not be deleted. Please, try again.'), 'alert', array(
				'plugin' => 'BoostCake',
				'class'  => 'alert-danger'
			));
		}

		return $this->redirect(array('action' => 'index'));
	}
}
