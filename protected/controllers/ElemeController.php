<?php
header("Content-type: text/html; charset=utf-8"); 
class ElemeController extends Controller
{
	/*
	*ElemeToken 授权
	*CreateCategory  菜品分类
	*ShopId 店铺对应
	*CreateItem 菜品对应
	*/
	public function actionElemeToken(){ 
		if(!empty($_GET['code'])){
			$code = $_GET['code'];
			$dpid = $_GET['state'];
			$res = Elm::eleMetoken($code,$dpid);
			echo $res;
		 }else{
			 echo "授权失败";
		}
	}
	public function actionCreateCategory(){
		$dpid = $_GET['companyId'];
		$ids = $_POST['ids'];
		$resultid = Elm::ElemeId($dpid);
		$obj = json_decode($resultid);
		$auth = $obj->result->authorizedShops;
		$shopid = $auth[0]->id;
		foreach ($ids as $value) {
			$lid = $value;
			$sql = "select lid,category_name from nb_product_category where lid=$lid";
			 $res = Yii::app()->db->createCommand($sql)->queryRow();
			$fen_lei_id = $res['lid'];
			$name = $res['category_name'];
			$result = Elm::productCategory($dpid,$fen_lei_id,$name,$shopid);
			$obj = json_decode($result);
	        if(!empty($obj->result)){
	        	$se=new Sequence("eleme_cpdy");
				$lid = $se->nextval();
				$creat_at = date("Y-m-d H:i:s");
				$update_at = date("Y-m-d H:i:s");
				$inserData = array(
							'lid'=>	$lid,
							'dpid'=> $dpid,
							'create_at'=>$creat_at,
							'update_at'=>$update_at,
							'elemeID'=>	$obj->result->id,
							'fen_lei_id'=>$fen_lei_id
					);
				$res = Yii::app()->db->createCommand()->insert('nb_eleme_cpdy',$inserData);
				echo "对应成功";
	        }else{
	        	echo $obj->error->message;
	        }
		}
	}
	public function actionShopId(){
		$dpid = $_GET['companyId'];
		$result = Elm::elemeUpdateId($dpid);
		echo $result;
        
	}
	public function actionCreateItem(){
		$dpid = $_GET['companyId'];
		$ids = $_POST['ids'];
		foreach ($ids as $key => $value) {
		 	$product_id = $value;
		 	$res = Elm::selectProduct($product_id);
		 	$product_id = $res['lid'];
		 	$name = $res['product_name'];
		 	$phs_code = $res['phs_code'];
		 	$original_price = $res['original_price'];
		 	$category_id = $res['category_id'];
		 	$category = Elm::selectCategory($category_id);
		 	$fen_lei_id = $category['pid'];
		 	$cpdy = Elm::getProduct($fen_lei_id);
		 	$id = $cpdy['elemeID'];
		 	if(!isset($id)){
		 		$this->redirect(array('/admin/eleme/index','companyId'=>$dpid));
		 	}
		 	$result = Elm::batchCreateItems($dpid,$id,$product_id,$name,$phs_code,$original_price);
		 	$obj = json_decode($result);
		 	if(!empty($obj->result)){
		 		$se=new Sequence("eleme_cpdy");
				$lid = $se->nextval();
				$creat_at = date("Y-m-d H:i:s");
				$update_at = date("Y-m-d H:i:s");
				$inserData = array(
							'lid'=>	$lid,
							'dpid'=> $dpid,
							'create_at'=>$creat_at,
							'update_at'=>$update_at,
							'elemeID'=>	$obj->result->id,
							'categoryId'=>$obj->result->categoryId,
							'fen_lei_id'=>$product_id
					);
				$res = Yii::app()->db->createCommand()->insert('nb_eleme_cpdy',$inserData);
		 		echo "对应成功";
		 	}else{
		 		echo $obj->error->message;
		 	}
		}
	}
	public function actionUpdateProduct(){
		if(isset($_POST['ids'])){
			$ids = $_POST['ids'];
			foreach ($ids as $value) {
				$lid = $value;
				$ree = Elm::selectProduct($lid);
				$name = $ree['product_name'];
				$phs_code = $ree['phs_code'];
				$original_price = $ree['original_price'];
				$res = Elm::productUpdate($lid);
				$dpid = $res['dpid'];
				$itemid = $res['elemeID'];
				$categoryid =$res['categoryId'];
				$item = Elm::updateItem($itemid,$dpid,$categoryid,$name,$original_price,$phs_code);
				$obj = json_decode($item);
				if(!empty($obj->result)){
					echo "更新成功";
				}else{
					echo $obj->error->message;
				}
			}
		}else{
			$lid = $_GET['product_id'];
			$ree = Elm::selectProduct($lid);
			$name = $ree['product_name'];
			$phs_code = $ree['phs_code'];
			$original_price = $ree['original_price'];
			$res = Elm::productUpdate($lid);
			$dpid = $res['dpid'];
			$itemid = $res['elemeID'];
			$categoryid =$res['categoryId'];
			$item = Elm::updateItem($itemid,$dpid,$categoryid,$name,$original_price,$phs_code);
			$obj = json_decode($item);
			if(!empty($obj->result)){
				echo "更新成功";
			}else{
				echo $obj->error->message;
			}
		}
	}
	public function actionDeleteProduct(){
		if(isset($_POST['ids'])){
			$ids = $_POST['ids'];
			foreach ($ids as $value) {
				$lid = $value;
				$lid = $_GET['product_id'];
				$res = Elm::productUpdate($lid);
				$dpid = $res['dpid'];
				$itemid = $res['elemeID'];
				$result = Elm::deleteItem($itemid,$dpid);
				$obj = json_decode($result);
				if(!empty($obj->result)){
					$sql = "update nb_eleme_cpdy set delete_flag=1 where elemeID=".$obj->result->id;
					$res = Yii::app()->db->createCommand($sql)->execute();
					echo "删除成功";
				}else{
					echo $obj->error->message;
				}
			}
		}else{
			$lid = $_GET['product_id'];
			$res = Elm::productUpdate($lid);
			$dpid = $res['dpid'];
			$itemid = $res['elemeID'];
			$result = Elm::deleteItem($itemid,$dpid);
			$obj = json_decode($result);
			if(!empty($obj->result)){
				$sql = "update nb_eleme_cpdy set delete_flag=1 where elemeID=".$obj->result->id;
				$res = Yii::app()->db->createCommand($sql)->execute();
				echo "删除成功";
			}else{
				echo $obj->error->message;
			}
		}
	}
	public function actionUpdateCategory(){
		if(isset($_POST['ids'])){
			$ids = $_POST['ids'];
			foreach ($ids as $value) {
				$lid = $value;
				$res = Elm::productUpdate($lid);
				$dpid = $res['dpid'];
				$elemeID = $res['elemeID'];
				$category_id = $res['fen_lei_id'];
				$sql = "select category_name from nb_product_category where lid=$category_id and delete_flag=0";
				$result = Yii::app()->db->createCommand($sql)->queryRow();
				$name = $result['category_name'];
				$ress = Elm::updateCategory($elemeID,$dpid,$name);
				$obj = json_decode($ress);
				if(!empty($obj->result)){
					echo "更新成功";
				}else{
					echo $obj->error->message;
				}
			}
		}else{
			$lid = $_GET['category_id'];
			$res = Elm::productUpdate($lid);
			$dpid = $res['dpid'];
			$elemeID = $res['elemeID'];
			$category_id = $res['fen_lei_id'];
			$sql = "select category_name from nb_product_category where lid=$category_id and delete_flag=0";
			$result = Yii::app()->db->createCommand($sql)->queryRow();
			$name = $result['category_name'];
			$ress = Elm::updateCategory($elemeID,$dpid,$name);
			$obj = json_decode($ress);
			if(!empty($obj->result)){
				echo "更新成功";
			}else{
				echo $obj->error->message;
			}
		}
	}
	public function actionDeleteCategory(){
		if(isset($_POST['ids'])){
			$ids = $_POST['ids'];
			foreach ($ids as $value) {
				$lid = $value;
				$res = Elm::productUpdate($lid);
				$dpid = $res['dpid'];
				$elemeID = $res['elemeID'];
				$result = Elm::removeCategory($elemeID,$dpid);
				$obj = json_decode($result);
				if(!empty($obj->result)){
					$sql = "update nb_eleme_cpdy set delete_flag=1 where elemeID=".$obj->result->id;
					$res = Yii::app()->db->createCommand($sql)->execute();
					echo "删除成功";
				}else{
					echo $obj->error->message;
				}
			}
		}else{
			$lid = $_GET['category_id'];
			$res = Elm::productUpdate($lid);
			$dpid = $res['dpid'];
			$elemeID = $res['elemeID'];
			$result = Elm::removeCategory($elemeID,$dpid);
			$obj = json_decode($result);
			if(!empty($obj->result)){
				$sql = "update nb_eleme_cpdy set delete_flag=1 where elemeID=".$obj->result->id;
				$res = Yii::app()->db->createCommand($sql)->execute();
				echo "删除成功";
			}else{
				echo $obj->error->message;
			}
		}
	}
	public function actionElemeOrder(){
		ob_end_flush();
		ob_start();
		echo '{"message":"ok"}';
		
		header("Content-Type: text/html;charset=utf-8");
		header("Connection: close");
		header('Content-Length: '. ob_get_length());
		
		ob_flush();
		flush();
		$data = file_get_contents('php://input');
		if(!empty($data)){
			$data = urldecode($data);
			$obj = json_decode($data);
			$type = $obj->type;
			$shopId = $obj->shopId;
			$message = $obj->message;
			$elemeDy = Elm::getErpDpid($shopId);
			if($elemeDy){
				$dpid = $elemeDy['dpid'];
				if($type==10){
					$result = Elm::order($message,$dpid);
				}elseif($type==12){
					$result = Elm::orderStatus($message,$dpid);
				}elseif($type==20){
					$result = Elm::orderCancel($message,$dpid);
				}elseif($type==30){
					$result = Elm::refundOrder($message,$dpid);
				}else {
					$result = true;
				}
				if($result){
					echo '{"message":"ok"}';
				}else{
					echo '{"message":"error"}';
				}
			}else{
				echo '{"message":"ok"}';
			}
		}
		exit;
	}
}