<?php

class IssueController extends CController
{

    public function indexAction()
    {
        $issues = new Issue('search');
        $issues->unsetAttributes();

        if (isset($_GET['Issue']))
            $issues->attributes = $_GET['Issue'];

        $issues->status_id = '<5';
        $issues->customer_id = Yii::app()->user->id;

        $this->render('index', array(
            'issues' => $issues
        ));
    }

}