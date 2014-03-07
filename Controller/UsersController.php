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
        'RequestHandler'
    );

    /**
     * index method
     *
     * @return void
     */
    public function index()
    {
        // $this->User->recursive = 0;
        // $this->set('users', $this->Paginator->paginate());
        $users = $this->User->find('all');
        $this->set('users', $users);
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
        $this->DataTable->paginate = array('User');
        // $this->User->recursive = 0;
        // $this->set('users', $this->Paginator->paginate());
        $users = $this->User->find('all');
        $this->set('users', $users);
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
