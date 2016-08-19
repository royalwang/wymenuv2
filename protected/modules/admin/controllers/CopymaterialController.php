<?php
class CopymaterialController extends BackendController
{
	public function actions() {
		return array(
				'upload'=>array(
						'class'=>'application.extensions.swfupload.SWFUploadAction',
						//注意这里是绝对路径,.EXT是文件后缀名替代符号
						'filepath'=>Helper::genFileName().'.EXT',
						//'onAfterUpload'=>array($this,'saveFile'),
				)
		);
	}
	public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId && $this->getAction()->getId() != 'upload') {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionIndex(){
		$categoryId = Yii::app()->request->getParam('cid',0);
		$criteria = new CDbCriteria;
		$criteria->with = array('company','category');
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId;
		if($categoryId){
			$criteria->condition.=' and t.category_id = '.$categoryId;
		}
		
		//$pages = new CPagination(Product::model()->count($criteria));
		//	    $pages->setPageSize(1);
		//$pages->applyLimit($criteria);
		$models = Product::model()->findAll($criteria);
		
		$db = Yii::app()->db;
		$sql = 'select t.dpid,t.company_name from nb_company t where t.delete_flag = 0 and t.comp_dpid = '.$this->companyId;
		$command = $db->createCommand($sql);
		$dpids = $command->queryAll();
		//var_dump($dpids);exit;
		$categories = $this->getCategories();
//                var_dump($categories);exit;
		$this->render('index',array(
				'models'=>$models,
				'dpids'=>$dpids,
				'categories'=>$categories,
				'categoryId'=>$categoryId
		));
	}
	public function actionSetMealList() {
		
	}
	public function actionCreate(){
		$model = new Product();
		$model->dpid = $this->companyId ;
		//$model->create_time = time();
		
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Product');
                        $se=new Sequence("product");
                        $model->lid = $se->nextval();
                        $model->create_at = date('Y-m-d H:i:s',time());
                        $model->update_at = date('Y-m-d H:i:s',time());
                        $model->delete_flag = '0';
                        $py=new Pinyin();
                        $model->simple_code = $py->py($model->product_name);
                        //var_dump($model);exit;
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','添加成功！'));
				$this->redirect(array('product/index' , 'companyId' => $this->companyId ));
			}
		}
		$categories = $this->getCategoryList();
		//$departments = $this->getDepartments();
                //echo 'ss';exit;
		$this->render('create' , array(
			'model' => $model ,
			'categories' => $categories
		));
	}
	
	public function actionUpdate(){
		$id = Yii::app()->request->getParam('id');
		$model = Product::model()->find('lid=:productId and dpid=:dpid' , array(':productId' => $id,':dpid'=>  $this->companyId));
		$model->dpid = $this->companyId;
		Until::isUpdateValid(array($id),$this->companyId,$this);//0,表示企业任何时候都在云端更新。
		if(Yii::app()->request->isPostRequest) {
			$model->attributes = Yii::app()->request->getPost('Product');
                        $py=new Pinyin();
                        $model->simple_code = $py->py($model->product_name);
			$model->update_at=date('Y-m-d H:i:s',time());
			if($model->save()){
				Yii::app()->user->setFlash('success',yii::t('app','修改成功！'));
				$this->redirect(array('product/index' , 'companyId' => $this->companyId ));
			}
		}
		$categories = $this->getCategoryList();
		//$departments = $this->getDepartments();
		$this->render('update' , array(
				'model' => $model ,
				'categories' => $categories
		));
	}
	public function actionStorProduct(){
		$companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
		$is_sync = DataSync::getInitSync();
		//var_dump($companyId);exit;
		$ids = Yii::app()->request->getPost('ids');
		$chscode = Yii::app()->request->getParam('chscode');
		$phscode = Yii::app()->request->getParam('phscode');
		$dpid = Yii::app()->request->getParam('dpids');
		$chscodes = array();
		$chscodes = explode(',',$chscode);
		$phscodes = array();
		$phscodes = explode(',',$phscode);
		$dpids = array();
		$dpids = explode(',',$dpid);
		//var_dump($ids,$chscodes,$dpids,$phscodes);exit;
		
		//****查询公司的产品分类。。。****
		$db = Yii::app()->db;
		$sql = 'select t.* from nb_product_category t where t.delete_flag = 0 and t.pid = 0 and t.dpid = '.$this->companyId;
		$command = $db->createCommand($sql);
		$catep1 = $command->queryAll();
		$db = Yii::app()->db;
		$sql = 'select t.* from nb_product_category t where t.delete_flag = 0 and t.pid != 0 and t.dpid = '.$this->companyId;
		$command = $db->createCommand($sql);
		$catep2 = $command->queryAll();
		$db = Yii::app()->db;
		$sql = 'select t.* from nb_product t where t.delete_flag = 0 and t.dpid = '.$this->companyId;
		$command = $db->createCommand($sql);
		$products = $command->queryAll();
		//var_dump($catep1,$catep2,$products);exit;
                Until::isUpdateValid($ids,$companyId,$this);//0,表示企业任何时候都在云端更新。
        if((!empty($dpids))&&(Yii::app()->user->role < User::WAITER)){
        	foreach ($dpids as $dpid){
        		if(!empty($catep1)){
        			foreach($catep1 as $category){
        				$catep = ProductCategory::model()->find('chs_code=:ccode and dpid=:companyId and delete_flag =0' , array(':ccode'=>$category['chs_code'],':companyId'=>$dpid));
        				if($catep){
//         					Yii::app()->user->setFlash('success' ,yii::t('app', '菜单下发成功'));
// 	                        $this->redirect(array('copyproduct/index' , 'companyId' => $this->companyId));
        				}else{//var_dump($catep);exit;
        					
	                        $se = new Sequence("product_category");
	                        $id = $se->nextval();
	                        $data = array(
	                        		'lid'=>$id,
	                        		'dpid'=>$dpid,
	                        		'create_at'=>date('Y-m-d H:i:s',time()),
	                        		'update_at'=>date('Y-m-d H:i:s',time()),
	                        		'category_name'=>$category['category_name'],
	                        		'pid'=>"0",
	                        		'type'=>$category['type'],
	                        		'chs_code'=> $category['chs_code'],
	                        		'main_picture'=>$category['main_picture'],
	                        		'order_num'=>$category['order_num'],
	                        		'delete_flag'=>'0',
	                        		'is_sync'=>$is_sync,
	                        );
	                        $command = $db->createCommand()->insert('nb_product_category',$data);
	                      
	                        	//var_dump(mysql_query($command));exit;
	                        	//var_dump($model);exit;
 	                        	$self = ProductCategory::model()->find('lid=:pid and dpid=:dpid and delete_flag=0' , array(':pid'=>$id,':dpid'=>$dpid ));
 	                        	//var_dump($self);exit;
	                        	if($self->pid!='0'){
	                        		$parent = ProductCategory::model()->find('lid=:pid and dpid=:dpid and delete_flag=0' , array(':pid'=>$model->pid,':dpid'=> $dpid));
	                        		$self->tree = $parent->tree.','.$self->lid;
	                        	} else {
	                        		$self->tree = '0,'.$self->lid;
	                        	}
	                        	$self->update();
// 	                        	//var_dump($model);exit;
// 	                        	
	                        	//Yii::app()->user->setFlash('success' ,yii::t('app', '菜单下发成功'));
	                        	//$this->redirect(array('copyproduct/index' , 'companyId' => $this->companyId));
	                        //}else{var_dump(mysql_query($command));exit;
	                        //	Yii::app()->user->setFlash('error' ,yii::t('app', '添加失败'));
	                        	//$this->redirect(array('copyproduct/index' ,'companyId' => $this->companyId));
	                        
        				}
        			}
        			if($catep2){
        				foreach ($catep2 as $category){
        					$sql = 'select t.chs_code,t.tree from nb_product_category t where t.delete_flag =0 and t.lid='.$category['pid'].' and t.dpid='.$this->companyId;
        					$command = $db->createCommand($sql);
        					$sqltree = $command->queryRow();
        					$chscode = $sqltree['chs_code'];
        					//$chscodetree = $sqltree['tree'];
        					$catep = ProductCategory::model()->find('chs_code=:ccode and dpid=:companyId and delete_flag=0' , array(':ccode'=>$category['chs_code'],':companyId'=>$dpid));
        					$cateptree = ProductCategory::model()->find('chs_code=:ccode and dpid=:companyId and delete_flag=0' , array(':ccode'=>$chscode,':companyId'=>$dpid));
        					
        					
        					//var_dump($cateptree,$sqltree);exit;
        					if($catep){
        						//         					Yii::app()->user->setFlash('success' ,yii::t('app', '菜单下发成功'));
        						// 	                        $this->redirect(array('copyproduct/index' , 'companyId' => $this->companyId));
        					}else{//var_dump($catep);exit;
        				
        						$se = new Sequence("product_category");
        						$id = $se->nextval();
        						$datacate = array(
        								'lid'=>$id,
        								'dpid'=>$dpid,
        								'create_at'=>date('Y-m-d H:i:s',time()),
        								'update_at'=>date('Y-m-d H:i:s',time()),
        								'category_name'=>$category['category_name'],
        								'pid'=>$cateptree['lid'],
        								'type'=>$category['type'],
        								'chs_code'=> $category['chs_code'],
        								'main_picture'=>$category['main_picture'],
        								'order_num'=>$category['order_num'],
        								'delete_flag'=>'0',
        								'is_sync'=>$is_sync,
        						);
        						$command = $db->createCommand()->insert('nb_product_category',$datacate);
        				
        						//var_dump(mysql_query($command));exit;
        						//var_dump($model);exit;
        						$self = ProductCategory::model()->find('lid=:pid and dpid=:dpid and delete_flag=0' , array(':pid'=>$id,':dpid'=>$dpid ));
        						//var_dump($self);exit;
        						if($self->pid!='0'){
        							//$parent = ProductCategory::model()->find('lid=:pid and dpid=:dpid' , array(':pid'=>$model->pid,':dpid'=> $dpid));
        							$self->tree = $cateptree['tree'].','.$self->lid;
        						} else {
        							$self->tree = '0,'.$self->lid;
        						}
        						$self->update();
        						// 	                        	//var_dump($model);exit;
        						//
        						//Yii::app()->user->setFlash('success' ,yii::t('app', '菜单下发成功'));
        						//$this->redirect(array('copyproduct/index' , 'companyId' => $this->companyId));
        						//}else{var_dump(mysql_query($command));exit;
        						//	Yii::app()->user->setFlash('error' ,yii::t('app', '添加失败'));
        						//$this->redirect(array('copyproduct/index' ,'companyId' => $this->companyId));
        				
        					}
        				}
        			}
        			
        		}
        		
        		if($products){
        			foreach ($phscodes as $prodhscode){
        				$producto = Product::model()->find('phs_code=:pcode and dpid=:companyId and delete_flag=0' , array(':pcode'=>$prodhscode,':companyId'=>$dpid));
        				$product =  Product::model()->find('phs_code=:pcode and dpid=:companyId and delete_flag=0' , array(':pcode'=>$prodhscode,':companyId'=>$this->companyId));
        				$categoryId = ProductCategory::model()->find('chs_code=:ccode and dpid=:companyId and delete_flag=0' , array(':ccode'=>$product['chs_code'],':companyId'=>$dpid));
        				//var_dump($product,$producto,$categoryId);exit;
        				//var_dump(!empty($producto));exit;
        				if((!empty($product))&&(empty($producto))&&(!empty($categoryId))){
        					//var_dump($catep);exit;
        						
        					$se = new Sequence("product");
        					$id = $se->nextval();
        					$dataprod = array(
        							'lid'=>$id,
        							'dpid'=>$dpid,
        							'create_at'=>date('Y-m-d H:i:s',time()),
        							'update_at'=>date('Y-m-d H:i:s',time()),
        							'category_id'=>$categoryId['lid'],
        							'phs_code'=>$product['phs_code'],
        							'chs_code'=>$product['chs_code'],
        							'product_name'=>$product['product_name'],
        							'simple_code'=>$product['simple_code'],
        							'main_picture'=>$product['main_picture'],
        							'description'=>$product['description'],
        							'rank'=>$product['rank'],
        							'spicy'=>$product['spicy'],
        							'is_temp_price'=>$product['is_temp_price'],
        							'is_member_discount'=>$product['is_member_discount'],
        							'is_special'=>$product['is_special'],
        							'status'=>$product['status'],
        							'dabao_fee'=>$product['dabao_fee'],
        							'is_discount'=>$product['is_discount'],
        							'original_price'=>$product['original_price'],
        							'product_unit'=>$product['product_unit'],
        							'weight_unit'=>$product['weight_unit'],
        							'is_weight_confirm'=>$product['is_weight_confirm'],
        							'store_number'=>$product['store_number'],
        							'order_number'=>$product['order_number'],
        							'favourite_number'=>$product['favourite_number'],
        							//'printer_way_id'=>,
        							'is_show'=>$product['is_show'],
        							'delete_flag'=>'0',
        							'is_sync'=>$is_sync,
        					);
        					//var_dump($dataprod);exit;
        					$command = $db->createCommand()->insert('nb_product',$dataprod);
        					
        				}else{
        					//var_dump($product);exit;
        				}
        			}
        		}
        	}
        	Yii::app()->user->setFlash('success' , yii::t('app','菜品下发成功！！！'));
        	$this->redirect(array('copyproduct/index' , 'companyId' => $companyId)) ;
        	
        }else{
        	Yii::app()->user->setFlash('error' , yii::t('app','无权限进行此项操作！！！'));
        	$this->redirect(array('copyproduct/index' , 'companyId' => $companyId)) ;
        }        
                
                
// 		if((!empty($ids))&&(Yii::app()->user->role < User::WAITER)) {
			
// 			//Yii::app()->db->createCommand('update nb_product set delete_flag=1 where lid in ('.implode(',' , $ids).') and dpid = :companyId')
// 			//->execute(array( ':companyId' => $this->companyId));
// 			$this->redirect(array('copyproduct/index' , 'companyId' => $companyId)) ;
// 		} else {
// 			Yii::app()->user->setFlash('error' , yii::t('app','无权限进行此项操作！！！'));
// 			$this->redirect(array('copyproduct/index' , 'companyId' => $companyId)) ;
// 		}
	}
	public function actionStatus(){
		$id = Yii::app()->request->getParam('id');
		$product = Product::model()->find('lid=:id and dpid=:companyId' , array(':id'=>$id,':companyId'=>$this->companyId));
		//var_dump($product->status);
		if($product){
			$product->saveAttributes(array('status'=>$product->status?0:1,'update_at'=>date('Y-m-d H:i:s',time())));
		}
		exit;
	}
	public function actionRecommend(){
		$id = Yii::app()->request->getParam('id');
		$product = Product::model()->find('lid=:id and dpid=:companyId' , array(':id'=>$id,':companyId'=>$this->companyId));
		
		if($product){
			$product->saveAttributes(array('recommend'=>$product->recommend==0?1:0,'update_at'=>date('Y-m-d H:i:s',time())));
		}
		exit;
	}
	private function getCategoryList(){
		$categories = ProductCategory::model()->findAll('delete_flag=0 and dpid=:companyId' , array(':companyId' => $this->companyId)) ;
		//var_dump($categories);exit;
		return CHtml::listData($categories, 'lid', 'category_name');
	}
	public function actionGetChildren(){
		$pid = Yii::app()->request->getParam('pid',0);
		if(!$pid){
			Yii::app()->end(json_encode(array('data'=>array(),'delay'=>400)));
		}
		$treeDataSource = array('data'=>array(),'delay'=>400);
		$categories = Helper::getCategories($this->companyId,$pid);
	
		foreach($categories as $c){
			$tmp['name'] = $c['category_name'];
			$tmp['id'] = $c['lid'];
			$treeDataSource['data'][] = $tmp;
		}
		Yii::app()->end(json_encode($treeDataSource));
	}
	private function getCategories(){
		$criteria = new CDbCriteria;
		$criteria->with = 'company';
		$criteria->condition =  't.delete_flag=0 and t.dpid='.$this->companyId ;
		$criteria->order = ' tree,t.lid asc ';
		
		$models = ProductCategory::model()->findAll($criteria);
                
		//return CHtml::listData($models, 'lid', 'category_name','pid');
		$options = array();
		$optionsReturn = array(yii::t('app','--请选择分类--'));
		if($models) {
			foreach ($models as $model) {
				if($model->pid == '0') {
					$options[$model->lid] = array();
				} else {
					$options[$model->pid][$model->lid] = $model->category_name;
				}
			}
                        //var_dump($options);exit;
		}
		foreach ($options as $k=>$v) {
                    //var_dump($k,$v);exit;
			$model = ProductCategory::model()->find('t.lid = :lid and dpid=:dpid',array(':lid'=>$k,':dpid'=>  $this->companyId));
			$optionsReturn[$model->category_name] = $v;
		}
		return $optionsReturn;
	}
	private function getDepartments(){
		$departments = Department::model()->findAll('company_id=:companyId',array(':companyId'=>$this->companyId)) ;
		return CHtml::listData($departments, 'department_id', 'name');
	}
	
}