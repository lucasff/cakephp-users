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


    public $virtualFields = array(
        'full_name' => 'CONCAT(User.first_name, " ", User.last_name)'
    );

    //The Associations below have been created with all possible keys, those that are not needed can be removed

    public function beforeSave($options = array())
    {
        if (isset($this->data[$this->alias]['password'])) {
            $passwordHasher = new SimplePasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash($this->data[$this->alias]['password']);
        }
        return true;
    }

}
