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

    public $actsAs = array(
        'Admin.Enum' => array(
            'gender'   => array('M' => 'Masculino', 'F' => 'Feminino'),
        )
    );

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

    public $hasMany = array(
        'Address' => array(
            'className' => 'Address',
            'foreignKey' => 'user_id',
            'limit' => '',
            'order' => '',
            'finderQuery' => '',
            'dependent' => true
        )
    );

}
