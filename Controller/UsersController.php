<?php

App::uses('UsersAppController', 'Users.Controller');

/**
 * Users Controller
 *
 * @property User $User
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
                    'fields' => array(
                        'username' => 'email'
                    )
                ),
            ),
            'flash' => array(
                'params' => array(
                    'class'  => 'alert alert-warning'
                ),
                'key' => 'auth',
                'element' => 'default'
            )
        ),
    );

    public $uses = ['Users.User', 'Users.Address'];

    public function beforeRender() {
        parent::beforeRender();
    }

    public function beforeFilter() {
        parent::beforeFilter();
        // $this->set($this->User->enumValues());
        $this->Auth->deny();
        $this->Auth->allow('login');
        $this->Auth->allow('add');
        $this->Auth->allow('letmein');
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
     * @param string $id
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
        if ($this->request->is('post')) {
            $this->User->create();

            $data = $this->request->data;
            $this->User->set($data);

            $this->set('invalidFields', $this->User->invalidFields());

            if ($this->User->save($data)) {

                $this->Address->create();

                $data['Address']['user_id'] = $this->User->getInsertID();

                if ($this->Address->save($data)) {
                    $this->Auth->login($data['User']);
                    $this->Session->setFlash(__('The user has been saved successfully and now you\'re logged in.'), 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => 'alert-success'
                    ));
                    $this->redirect($this->Auth->redirectUrl());
                } else {
                    $this->Session->setFlash(__('The address could not be saved. Please, try again.'), 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => 'alert-danger'
                    ));
                }
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));
            }
        }
    }

    public function login() {

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
                        'data' => $this->request->data,
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
                    'class' => 'alert-warning'
                ));
            }
        }
    }

    public function letmein() {

        $username = array_merge(range('a', 'z'), range('A', 'Z'));
        shuffle($username);
        $username = substr(implode($username), 0, 8);

        $password = uniqid();

        $this->User->create();
        $data['User'] = [
            'id' => null,
            'name' => 'Auto Generated User',
            'username' => $username,
            'password' => $password,
            'email' => vsprintf('system%s@software.com', mt_rand())
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
    public function logout() {
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
     * @param string $id
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
                    'class' => 'alert-success'
                ));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__d('admin', 'The user could not be updated. Please, correct any errors and try again.'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));
            }
        } else {
            $options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
            $this->request->data = $this->User->find('first', $options);
            unset($this->request->data['User']['password']);
        }
    }

    /**
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
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
                'class' => 'alert-success'
            ));
        } else {
            $this->Session->setFlash(__d('admin', 'The user could not be deleted. Please, try again.'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
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
     * @param string $id
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
                    'class' => 'alert-success'
                ));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__d('admin', 'The user could not be saved. Please, try again.'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));
            }
        }
    }

    /**
     * admin_edit method
     *
     * @throws NotFoundException
     * @param string $id
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
                    'class' => 'alert-success'
                ));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__d('admin', 'The user could not be updated. Please, correct any errors and try again.'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));
            }
        } else {
            $options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
            $this->request->data = $this->User->find('first', $options);
            unset($this->request->data['User']['password']);
        }
    }

    /**
     * admin_delete method
     *
     * @throws NotFoundException
     * @param string $id
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
                'class' => 'alert-success'
            ));
        } else {
            $this->Session->setFlash(__d('admin', 'The user could not be deleted. Please, try again.'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
        }
        return $this->redirect(array('action' => 'index'));
    }
}
