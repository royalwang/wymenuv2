<?php
class ProductPrinterController extends BackendController
{
	public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId) {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionindex(){
		$criteria = new CDbCriteria;
		//$criteria->with = 'printerWay';
		$criteria->addCondition('t.dpid=:dpid and t.delete_flag=0 ');
                $criteria->with='productPrinterway';
		$criteria->order = ' t.lid desc ';
		$criteria->params[':dpid']=$this->companyId;
		
		$pages = new CPagination(Product::model()->count($criteria));
		//$pages->setPageSize(1);
		$pages->applyLimit($criteria);
		$models = Product::model()->findAll($criteria);
		//var_dump($models[0]);exit;
		$this->render('productPrinter',array(
				'models'=>$models,
				'pages' => $pages,
		));
	}
	public function actionUpdate(){
                $printerway=array();
		$lid = Yii::app()->request->getParam('lid');
		$model = Product::model()->find('lid=:lid and dpid=:dpid', array(':lid' => $lid,':dpid'=>  $this->companyId));
		
		if(Yii::app()->request->isPostRequest) {
			$postData = Yii::app()->request->getPost('ProductPrinterway');
			//$model->printer_way_id = $postData;
			if(ProductPrinterway::saveProductPrinterway($this->companyId, $lid, $postData)){
				Yii::app()->user->setFlash('success' ,yii::t('app', '修改成功'));
				$this->redirect(array('productPrinter/index' , 'companyId' => $this->companyId));
			}
		}
		$printerWays = PrinterWay::getPrinterWay($this->companyId);
                
                $productPrinterway=  ProductPrinterway::getProductPrinterWay($lid,$this->companyId);
		foreach($productPrinterway as $ppw){
			array_push($printerway,$ppw['printer_way_id']);
		}
		$this->render('updateProductPrinter' , array(
			'model'=>$model,
			'printerWays'=>$printerWays,
                        'printerway'=>$printerway,
		));
	}
}