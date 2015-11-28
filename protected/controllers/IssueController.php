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
            $issues->attributes=$_GET['Issue'];

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

        if (isset($_GET['issue']))
        {
            if (is_numeric($_GET['issue']))
            {
                $issue = Issue::model()->findByPk($_GET['issue']);
                if ($issue != NULL)
                    $this->redirect($this->createUrl('issue/update', array('id' => $issue->id)));
            }

            $issues->title = $_GET['issue'];
            $issues->description = $_GET['issue'];
        }

        $this->render('search', array(
            'issues' => $issues
        ));
    }


    /**
     * Handles the creation of new issues by the customer
     */
    public function actionCreate()
    {
        $issue = new Issue;

        // If the POST attributes are set
        if (isset($_POST['Issue']))
        {
            $issue->attributes = $_POST['Issue'];

            if ($issue->save())
            {
                Yii::app()->user->setFlash('success', "Issue #{$issue->id} has successfully been created");
                $this->redirect($this->createUrl('issue/update', array('id' => $issue->id)));
            }
        }

        $this->render('create', array(
            'model' => $issue
        ));
    }

    public function actionUpdate($id = null)
    {
        // Load the necessary models
        $issue = $this->loadModel($id);
        $update = new Update();
        $update->update = null;
        $customer_id = $issue->customer_id;

        // Check perms
        if (Yii::app()->user->role == 1)
        {
            if (Yii::app()->user->role != $customer_id)
            {
                throw new CHttpException(403, 'You do not have permissions to view this issue');
            }
        }

        if (Yii::app()->user->id >= 2)
        {
            if (isset($_POST['Issue']))
            {
                $issue->attributes = $_POST['Issue'];
                if ($issue->save())
                    Yii::app()->setFlash('success', "Issue {$issue->id} has successfully been updated");
            }
        }

        if (isset($_POST['Update']))
        {
            $update->issue_id   = $issue->id;
            $update->update     = $_POST['Update']['update'];

            if ($update->save())
            {
                Yii::app()->user->setFlash('success', "Issue {$issue->id} has been successfully been updated");
                $this->redirect($this->createUrl('/issue/update', array('id' => $issue->id)));
            }
        }

        $this->render('update', array(
            'issue'     => $issue,
            'update'    => $update,
            'md'        => new CMarkdownParser
        ));
    }


    /**
     * Loads the issue model
     * @param  int $id
     * @return Issue model
     */
    private function loadModel($id = NULL)
    {
        if ($id == NULL)
            throw new CHttpException(400, 'Missing ID');

        $model = Issue::model()->findByPk($id);

        if ($model == NULL)
            throw new CHttpException(404, 'No issue with that ID was found');

        return $model;
    }
}