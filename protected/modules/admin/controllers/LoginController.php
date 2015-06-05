<?php
class LoginController extends BackendController
{
	public $layout = '/layouts/loginLayout';
	public function actionIndex()
	{
                $language=Yii::app()->request->getParam('language','0');
                //echo Yii::app()->language;
                if($language!='0')
                {
                    //echo $language;
                    Yii::app()->session['language']=$language;
                    Yii::app()->language=$language;
                    //Yii::$app->language=isset(Yii::$app->session['language'])?Yii::$app->session['language']:'zh_cn';
                }
                
		$model = new LoginForm();
		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		
		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			//var_dump($model);exit;
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login()) {
				$this->redirect(array('default/index/companyId/'.Yii::app()->user->companyId));
			}
		}
		$this->render('index',array('model' => $model));
	}
	public function actionLogout()
	{
                //$language=Yii::app()->session['language'];
		Yii::app()->user->logout();
                //Yii::app()->user->
		//$this->redirect(array('index','language'=>$language));
                $this->redirect('index');
	}
	
	
	
	
	
	
}