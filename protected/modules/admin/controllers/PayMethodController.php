<?php
class PayMethodController extends BackendController {
	public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId && $this->getAction()->getId() != 'upload') {
			Yii::app()->user->setFlash('error' , '请选择公司');
			$this->redirect(array('company/index'));
		}
		return true;
	}
	
	public function actionIndex() {
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$criteria = new CDbCriteria;
		//$criteria->with = 'company' ;
		$criteria->condition = Yii::app()->user->role == User::POWER_ADMIN ? '' : 't.company_id='.Yii::app()->user->companyId ;
		
		$pages = new CPagination(PaymentMethod::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = PaymentMethod::model()->findAll($criteria);
		$this->render('index',array(
				'models'=>$models,
				'pages'=>$pages,
				'companyId' => $companyId
		));
		
	}
	public function actionCreate(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		
		$model = new PaymentMethod() ;
		$model->dpid = $companyId ;
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('PaymentMethod');
			$se=new Sequence("payment_method");
            $model->lid = $se->nextval();
			$model->create_at = date('Y-m-d H:i:s');
//			var_dump($model->attributes);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success' , '添加成功');
				$this->redirect(array('payMethod/index' , 'companyId' => $companyId));
			}
		}
		$this->render('create' , array('model' => $model));
	}
	public function actionUpdate(){
		$id = Yii::app()->request->getParam('id');
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$model = PaymentMethod::model()->find('lid=:id and dpid=:companyId' , array(':id' => $id , ':companyId' => $companyId));
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('PaymentMethod');
			if($model->save()){
				Yii::app()->user->setFlash('success' , '修改成功');
				$this->redirect(array('payMethod/index' , 'companyId' => $companyId));
			}
		}
		$this->render('update' , array('model' => $model ));
	}
	public function actionDelete(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$ids = Yii::app()->request->getPost('ids');
		if(!empty($ids)) {
				foreach ($ids as $id) {
					$model = PaymentMethod::model()->find('lid=:id and dpid=:companyId' , array(':id' => $id , ':companyId' => $companyId)) ;
					if($model) {
						$model->delete();
					}
				}
				$this->redirect(array('payMethod/index' , 'companyId' => $companyId)) ;
		} else {
			Yii::app()->user->setFlash('error' , '请选择要删除的项目');
			$this->redirect(array('payMethod/index' , 'companyId' => $companyId)) ;
		}
	}
}
