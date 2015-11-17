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

    /**
     * Displays a list of all the available users
     */
    public function actionIndex()
    {
        $users = new User('search');
        $users->unsetAttributes();

        if (isset($_GET['Users']))
            $users->attributes = $_GET['Users'];

        $this->render('index', array(
            'model' => $users
        ));
    }

    /**
     * Creates a new user or updates an existing user
     * @param  int $id
     */
    public function actionSave($id = null)
    {
        $user = ($id == null) ? new User() : $this->loadModel($id);

        if (isset($_POST['User']))
        {
            $user->attributes = $_POST['User'];

            try {
                if ($user->save())
                {
                    Yii::app()->user->setFlash('success', 'The user has sucessfully been updated');
                    $this->redirect($this->createUrl('user/save', array('id' => $user->id)));
                }
            } catch (Exception $e) {
                $user->addError('email', 'A user with that email address already exists');
            }
        }

        $this->render('save', array(
            'model' => $user
        ));
    }

    /**
     * Delets a particular user
     * @param  int $id
     */
    public function actionDelete($id = null)
    {
        if (Yii::app()->user->id == $id)
            throw new CHttpException(403, 'You can not delete yourself');

        $user = $this->loadModel($id);

        if ($user->delete())
            $this->redirect($this->createUrl('user/'));

        throw new CHttpException(400, 'Bad Request');
    }

    public function actionView($id = null)
    {
        if ($id == null)
            throw new CHttpException(400, 'Missing ID argument');

        $user = $this->loadModel($id);
        $issues = new

    }

    /**
     * Loads a model with a given ID, and throws the appropriate error
     * @param  int $id
     * @return User::model
     */
    private function loadModel($id = null)
    {
        if (id == null)
            throw new CHttpException(400, 'Missing ID argument');

        $model = User::model()->findByPk($id);

        if ($model == null)
            throw new CHttpException(404, 'No user with that ID could be found');

        return $model;
    }
}