<?php
class UserController extends CController
{

    /**
     * AccessRules, only authenticated users can access this page
     * @return array
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('search', 'view'),
                'users'=>array('@'),
                'expression' => 'Yii::app()->user->role>=2'
            ),
            array('allow',
                'actions' => array('index', 'save', 'delete'),
                'users'=>array('@'),
                'expression' => 'Yii::app()->user->role==3'
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

}