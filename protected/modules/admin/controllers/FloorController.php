<?php
class FloorController extends BackendController
{
	public function beforeAction($action){
		parent::beforeAction($action);
		if(!$this->companyId) {
			Yii::app()->user->setFlash('error' , '请选择公司');
			$this->redirect(array('company/index')) ;
		}
		return true;
	}
	public function actionIndex() {
		$criteria = new CDbCriteria;
		$criteria->with = 'company';
		$criteria->condition = 't.dpid='.$this->companyId .' and t.delete_flag=0';
		$pages = new CPagination(Floor::model()->count($criteria));
		//	    $pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = Floor::model()->findAll($criteria);
		//var_dump($models);
                //exit;
		$this->render('index',array(
				'models'=>$models,
				'pages'=>$pages,
		));
	}
	public function actionCreate() {
		$model = new Floor() ;
		$model->dpid = $this->companyId ;
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Floor');
                        $se=new Sequence("floor");
                        $model->lid = $se->nextval();
                        $model->create_at = date('Y-m-d H:i:s',time());
                        $model->delete_flag = '0';
                        //var_dump($model);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success' , '添加成功');
				$this->redirect(array('floor/index' , 'companyId' => $this->companyId));
			}
		}
		$this->render('create' , array(
			'model' => $model,
		));
	}
	public function actionUpdate() {
		$lid = Yii::app()->request->getParam('lid');
                $dpid = Yii::app()->request->getParam('companyId');
		$model = floor::model()->find('t.lid=:lid and t.dpid=:dpid', array(':lid' => $lid,':dpid'=>$dpid));
		//var_dump($model);
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Floor');
                        //var_dump($model);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success' , '修改成功');
				$this->redirect(array('floor/index' , 'companyId' => $this->companyId));
			}
		}
		$this->render('update' , array(
			'model' => $model
		));
	}
	public function actionDelete() {
		$ids = $_POST['floor_id'] ;
		//var_dump(implode(',' , $ids),$this->companyId);exit;
                //$sql='update nb_site_type set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId';
                //$command=Yii::app()->db->createCommand($sql);
                //$command->bindValue(":ids" , implode(',' , $ids));
                //$command->bindValue(":dpid" , $this->companyId);
                //var_dump($command);exit;
		if(!empty($ids)) {
			Yii::app()->db->createCommand('update nb_floor set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
			
			Yii::app()->db->createCommand('update nb_site set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
			->execute(array( ':companyId' => $this->companyId));
		}
		$this->redirect(array('floor/index' , 'companyId' => $this->companyId));
	}
}