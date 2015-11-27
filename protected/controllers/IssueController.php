<?php

class IssueController extends CController
{
    /**
     * AccessControl filter
     * @return array
     */
    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    /**
     * AccessRules, only authenticated users can access this page
     * @return array
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('index', 'create', 'update'),
                'users'=>array('@'),
            ),
            array('allow',
                'actions' => array( 'search'),
                'users'=>array('@'),
                'expression' => 'Yii::app()->user->role>=2'
            ),
            array('allow',
                'actions' => array('emailUpdate'),
                'users'=>array('*'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    /**
     * Displays issues belonging to the current user and issues belonging to all users if they are a supporter
     */
    public function actionIndex()
    {
        // Retrieve the issues belonging to the currently logged in user
        $issues = new Issue('search');
        $issues->unsetAttributes();

        if(isset($_GET['Issue']))
            $issues->attributes = $_GET['Issue'];

        // Don't search resolved issues
        $issues->status_id = '<5';
        $issues->customer_id = Yii::app()->user->id;

        // Render the View
        $this->render('index', array(
            'issues' => $issues
        ));
    }

    /**
     * Allows the supporter or admin to search for issues
     */
    public function actionSearch()
    {
        $issues = new Issue('search');
        $issues->status_id = '<5';

        if (isset($_GET['query']))
        {
            // Search by ID
            if (is_numeric($_GET['query']))
            {
                $issue = Issue::model()->findByPk($_GET['query']);
                if ($issue != NULL)
                    $this->redirect($this->createUrl('issue/update', array('id' => $issue->id)));
            }

            $issues->title = $_GET['query'];
            $issues->description = $_GET['query'];
        }

        $this->render('search', array(
            'issues' => $issues
        ));
    }

}