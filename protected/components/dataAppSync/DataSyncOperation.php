<?php
/**
 * 
 * 
 * 云端和本地营业数据实时同步！！！！！！！！！！
 * 本地获得云端新的订单数据，只取完成的，
 * 本地将自己的新的订单数据及会员和优惠数据发送给云端，只发送完成的
 * ///////////////////////////
 * 根据具体的需求添加
 * 目前有：
 * 1获得云端订单，2更改云端订单（状态、支付及口味等信息）
 * 2发送本地订单、支付、结单到云端、
 * 3发送本地交接班、日结、等信息到云端
 * 4发送本地活动信息到云端。
 * 5发送本地会员信息到云端。
 * 
 * 
 */
class DataSyncOperation {
	/**
	 * 
	 * 
	 * 获取基础数据表
	 * 
	 * 
	 */
	public static function getDataSyncBaseTables() {
		$dataBase = new DataSyncTables ();
		$baseTables = $dataBase->getBaseTableList ();
		return $baseTables;
	}
	/**
	 * 
	 * 
	 * 获取所有的表 和 结构
	 * 
	 * 
	 */
	public static function getDataSyncAllTables() {
		$dataBase = new DataSyncTables ();
		$allTables = $dataBase->getAllTableList ();
		
		foreach ( $allTables as $k => $table ) {
			$tableStruct = $dataBase->getTableStructure ( $table ['table'] );
			$allTables [$k] ['struct'] = $tableStruct;
		}
		return array (
				'status' => true,
				'msg' => $allTables 
		);
	}
	/**
	 * 
	 * 
	 * 获取初始化数据
	 * 
	 * 
	 */
	public static function getDataSyncData($dpid, $tableName) {
		$dataBase = new DataSyncTableData ( $dpid, $tableName );
		$tableData = $dataBase->getInitData ();
		return array ('status' => true,'msg' => $tableData);
	}
	/**
	 * 
	 * 
	 * 获取需要到本地执行的sql，每次仅限1000条
	 * 
	 * 
	 */
	public static function getCloudSqlData1000($dpid) {
		$allflag = substr ( "11111111111111111111111111111111111111111111111111111", 0, Yii::app ()->params ['sync_maxlocal'] );
		return substr_replace ( $allflag, "0", Yii::app ()->params ['sync_localnum'] - 1, 1 );
	}
	public static function execLocalSql($dpid, $sqldata) {
	}
	
	/**
	 * 
	 * 
	 * 有些更新必须先同步到云端，如产品的库存数量，必须各个客户端都更新云端一个地方，然后同步下来
	 * 采用的策略是用sql语句更新云端，然后从云端同步到本地，
	 * 所以更新时限更新云端，然后更新本地，如果云端失败，保存sql，同步前先调用这个更新sql，再同步云端数据
	 * 具体功能是：
	 * 先操作云端，如果成功则返回，如果失败则存储到nb_sqlcmd_sync
	 *
	 * 这些数据，必须云端一致！！！
	 *
	 * @param type $sql  
	 * 
	 *       	
	 */
	public static function cloudFirt($dpid, $sql) {
		try {
			$dbcloud = Yii::app ()->dbcloud;
			$dbcloud->createCommand ( $sql )->execute ();
			return true;
		} catch ( exception $e ) {
			$dblocal = Yii::app ()->dblocal;
			$se = new Sequence ( "sqlcmd_sync" );
			$lid = $se->nextval ();
			$data = array (
					'lid' => $lid,
					'dpid' => $dpid,
					'create_at' => date ( 'Y-m-d H:i:s', time () ),
					'sqlcmd' => $sql,
					'is_sync' => '10000' 
			); // 10000表示需要先在服务器端更新！

			$dblocal->createCommand ()->insert ( 'nb_sqlcmd_sync', $data );
			return false;
		}
	}
	
	/**
	 * 
	 * 
	 * 同步云端的数据之前，将本地需要先更新云端的数据查询出来，
	 * 然后更新云端，再同步，如果失败直接返回false
	 * 操作：
	 * 查出所有的sqlcmd_sync，然后执行
	 *
	 * @return boolean
	 * 
	 * 
	 */
	public static function cloudFirstSync($dpid) {
		$dbcloud = Yii::app ()->dbcloud;
		$dblocal = Yii::app ()->dblocal;
		$cloudexec = $dblocal->createCommand ( "select lid,sqlcmd from nb_sqlcmd_sync where is_sync='10000' and dpid=" . $dpid )->queryAll ();
		if (empty ( $cloudexec )) {
			return true;
		}
		$dellist = "(";
		$transactioncloud = $dbcloud->beginTransaction ();
		try {
			foreach ( $cloudexec as $row ) {
				$dbcloud->createCommand ( $row ['sqlcmd'] )->execute ();
				$dellist = $dellist . $row ['lid'] . ",";
			}
			$dellist = $dellist . "0000000000" . ")";
			$dblocal->createCommand ( "delete from nb_sqlcmd_sync where dpid=" . $dpid . " and lid in " . $dellist )->execute ();
			$transactioncloud->commit ();
			return true;
		} catch ( exception $ex ) {
			// osy//echo $ex->getMessage();
			$transactioncloud->rollback ();
			return false;
		}
	}
	/**
	 * 
	 * 
	 * 获取同步数据
	 * 
	 * 
	 */
	public static function getSyncData($dpid) {
		$data = array ();
		$data ['order'] = array ();
		$data ['member_card'] = array ();
		$transaction = Yii::app ()->db->beginTransaction ();
		try {
			//订单数据
			$sql = 'select * from nb_order where dpid=' . $dpid . ' and order_status=3 and 	is_sync<>0';
			$results = Yii::app ()->db->createCommand ( $sql )->queryAll ();
			foreach ( $results as $result ) {
				$order = array ();
				$order ['nb_order'] = $result;
				$sql = 'select * from nb_order_product where order_id=' . $result ['lid'];
				$orderProduct = Yii::app ()->db->createCommand ( $sql )->queryAll ();
				foreach ( $orderProduct as $k => $product ) {
					$sql = 'select * from nb_order_taste where order_id=' . $product ['lid'] . ' and is_order=0';
					$orderProductTaste = Yii::app ()->db->createCommand ( $sql )->queryAll ();
					$orderProduct [$k] ['product_taste'] = $orderProductTaste;
				}
				$order ['nb_order_product'] = $orderProduct;
				$sql = 'select * from nb_order_pay where order_id=' . $result ['lid'];
				$orderPay = Yii::app ()->db->createCommand ( $sql )->queryAll ();
				$order ['nb_order_pay'] = $orderPay;
				$sql = 'select * from nb_order_taste where order_id=' . $result ['lid'] . ' and is_order=1';
				$orderTaste = Yii::app ()->db->createCommand ( $sql )->queryAll ();
				$order ['nb_order_taste'] = $orderTaste;
				array_push ( $data ['order'], $order );
				$sql = 'update nb_order set is_sync=0 where dpid=' . $dpid . ' and lid=' . $result ['lid'];
				Yii::app ()->db->createCommand ( $sql )->execute ();
			}
			//会员数据
			$sql = 'select * from nb_member_card where dpid=' . $dpid . ' and delete_flag=0 and is_sync<>0';
			$memberCard = Yii::app ()->db->createCommand ( $sql )->queryAll ();
			foreach ( $memberCard as $card ) {
				array_push ( $data ['member_card'], $card );
				$sql = 'update nb_member_card set is_sync=0 where dpid=' . $dpid . ' and lid=' . $card ['lid'];
				Yii::app ()->db->createCommand ( $sql )->execute ();
			}
			$transaction->commit (); // 事物结束
		} catch ( Exception $e ) {
			$transaction->rollback (); // 回滚函数
			$data ['order'] = array ();
			$data ['member_card'] = array ();
		}
		return json_encode ( $data );
	}
	/**
	 * 
	 * 
	 * 用户名密码验证
	 * 
	 * 
	 */
	public static function validateUser($data) {
		$userName = $data ['user_name'];
		$passward = $data ['passward'];
		$sql = 'select * from nb_user where username="' . $userName . '" and password_hash="' . Helper::genPassword ( $passward ) . '" and delete_flag=0';
		$result = Yii::app ()->db->createCommand ( $sql )->queryRow ();
		if ($result) {
			return json_encode ( array (
					'status' => true,
					'user_id' => $result ['lid'] 
			) );
		} else {
			return json_encode ( array (
					'status' => false 
			) );
		}
	}
	/**
	 * 
	 * 
	 * 订单操作
	 * 
	 * 
	 */
	public static function operateOrder($data) {
		$dpid = $data ['dpid'];
		$orderData = $data ['data'];
		$obj = json_decode ( $orderData );
		if (isset ( $data ['is_pos'] ) && $data ['is_pos'] == 1) {
			$isSync = 0;
		} else {
			$isSync = DataSync::getInitSync ();
		}
		
		$orderInfo = $obj->order_info;
		$orderProduct = $obj->order_product;
		$orderPay = $obj->order_pay;
		
		if (isset ( $obj->order_taste )) {
			$orderTaste = $obj->order_taste;
		} else {
			$orderTaste = array ();
		}
		
		if (isset ( $obj->order_discount )) {
			$orderDiscount = $obj->order_discount;
		} else {
			$orderDiscount = array ();
		}
		
		$time = time ();
		$se = new Sequence ( "order" );
		$orderId = $se->nextval ();
		$accountNo = WxOrder::getAccountNo ( $dpid, $orderInfo->site_id, $orderInfo->is_temp, $orderId );
		
		$transaction = Yii::app ()->db->beginTransaction ();
		try {
			$insertOrderArr = array (
					'lid' => $orderId,
					'dpid' => $dpid,
					'create_at' => date ( 'Y-m-d H:i:s', $time ),
					'update_at' => date ( 'Y-m-d H:i:s', $time ),
					'account_no' => $accountNo,
					'user_id' => '0',
					'site_id' => $orderInfo->site_id,
					'is_temp' => $orderInfo->is_temp,
					'number' => $orderInfo->number,
					'order_status' => $orderInfo->order_status,
					'order_type' => $orderInfo->order_type,
					'should_total' => $orderInfo->should_total,
					'reality_total' => isset($orderInfo->reality_total) ? $orderInfo->reality_total : $orderInfo->should_total,
					'taste_memo' => isset ( $orderInfo->taste_memo ) ? $orderInfo->taste_memo : '',
					'is_sync' => $isSync 
			);
			$result = Yii::app ()->db->createCommand ()->insert ( 'nb_order', $insertOrderArr );
			
			foreach ( $orderProduct as $product ) {
				$se = new Sequence ( "order_product" );
				$orderProductId = $se->nextval ();
				$orderProductData = array (
						'lid' => $orderProductId,
						'dpid' => $dpid,
						'create_at' => date ( 'Y-m-d H:i:s', $time ),
						'update_at' => date ( 'Y-m-d H:i:s', $time ),
						'order_id' => $orderId,
						'set_id' => $product->set_id,
						'product_id' => $product->product_id,
						'product_name' => $product->product_name,
						'product_pic' => '',
						'price' => $product->price,
						'original_price' => $product->original_price,
						'amount' => $product->amount,
						'product_order_status' => 2,
						'is_sync' => DataSync::getInitSync () 
				);
				Yii::app ()->db->createCommand ()->insert ( 'nb_order_product', $orderProductData );
				
				//产品口味
				if(isset($product->product_taste)){
					$productTastes = $product->product_taste;
					foreach ($productTastes as $taste){
						$se = new Sequence ( "order_taste" );
						$orderTasteId = $se->nextval ();
						$orderTasteData = array (
								'lid' => $orderTasteId,
								'dpid' => $dpid,
								'create_at' => date ( 'Y-m-d H:i:s', $time ),
								'update_at' => date ( 'Y-m-d H:i:s', $time ),
								'taste_id' => $taste->taste_id,
								'order_id' => $orderProductId,
								'is_order' => 0,
								'is_sync' => DataSync::getInitSync ()
						);
						Yii::app ()->db->createCommand ()->insert ( 'nb_order_taste', $orderTasteData );
					}
				}
				//产品普通优惠
				if(isset($product->product_promotion)){
					$productPromotions = $product->product_promotion;
					foreach ($productPromotions as $promotion){
						$se = new Sequence ( "order_product_promotion" );
						$orderPromotionId = $se->nextval ();
						$orderPromotionData = array (
								'lid' => $orderPromotionId,
								'dpid' => $dpid,
								'create_at' => date ( 'Y-m-d H:i:s', $time ),
								'update_at' => date ( 'Y-m-d H:i:s', $time ),
								'order_id' => $orderId,
								'order_product_id' => $orderProductId,
								'account_no' => $accountNo,
								'promotion_type' => $promotion->promotion_type,
								'promotion_id' => $promotion->promotion_id,
								'promotion_money' => $promotion->promotion_money,
								'is_sync' => DataSync::getInitSync ()
						);
						Yii::app ()->db->createCommand ()->insert ( 'nb_order_product_promotion', $orderPromotionData );
					}
				}
				
				if($isSync==0){
					// 消耗原材料库存
					$productBoms = self::getBom($dpid, $product->product_id);
					if(!empty($productBoms)){
						foreach ($productBoms as $bom){
							$stock = $bom['number']/$bom['unit_ratio'];
							self::updateMaterialStock($dpid,$bom['material_id'],$stock);
						}
					}
				}
			}
			// 支付方式
			foreach ( $orderPay as $pay ) {
				$se = new Sequence ( "order_pay" );
				$orderPayId = $se->nextval ();
				$orderPayData = array (
						'lid' => $orderPayId,
						'dpid' => $dpid,
						'create_at' => date ( 'Y-m-d H:i:s', $time ),
						'update_at' => date ( 'Y-m-d H:i:s', $time ),
						'order_id' => $orderId,
						'account_no' => $accountNo,
						'pay_amount' => $pay->pay_amount,
						'paytype' => $pay->paytype,
						'payment_method_id' => $pay->payment_method_id,
						'paytype_id' => $pay->paytype_id,
						'is_sync' => DataSync::getInitSync () 
				);
				Yii::app ()->db->createCommand ()->insert ( 'nb_order_pay', $orderPayData );
			}
			// 订单口味
			foreach ( $orderTaste as $taste ) {
				$se = new Sequence ( "order_taste" );
				$orderTasteId = $se->nextval ();
				$orderTasteData = array (
						'lid' => $orderTasteId,
						'dpid' => $dpid,
						'create_at' => date ( 'Y-m-d H:i:s', $time ),
						'update_at' => date ( 'Y-m-d H:i:s', $time ),
						'taste_id' => $taste->taste_id,
						'order_id' => $orderId,
						'is_order' => 1,
						'is_sync' => DataSync::getInitSync () 
				);
				Yii::app ()->db->createCommand ()->insert ( 'nb_order_taste', $orderTasteData );
			}
			// 订单优惠
			foreach ( $orderDiscount as $discount ) {
				$se = new Sequence ( "order_account_discount" );
				$orderDiscountId = $se->nextval ();
				$orderDiscountData = array (
						'lid' => $orderDiscountId,
						'dpid' => $dpid,
						'create_at' => date ( 'Y-m-d H:i:s', $time ),
						'update_at' => date ( 'Y-m-d H:i:s', $time ),
						'order_id' => $orderId,
						'account_no' => $accountNo,
						'discount_type' => $discount->discount_type,
						'discount_id' => $discount->discount_id,
						'discount_money' => $discount->discount_money,
						'is_sync' => DataSync::getInitSync () 
				);
				Yii::app ()->db->createCommand ()->insert ( 'nb_order_account_discount', $orderDiscountData );
			}
			
			$transaction->commit ();
			$msg = json_encode ( array (
					'status' => true,
					'orderId' => $orderId 
			) );
		} catch ( exception $e ) {
			$transaction->rollback ();
			$msg = json_encode ( array (
					'status' => false,
					'orderId' => '' 
			) );
		}
		return $msg;
	}
	/**
	 * 
	 * 
	 * 
	 * 增加会员卡
	 * 
	 * 
	 * 
	 */
	public static function addMemberCard($data) {
		$dpid = $data ['dpid'];
		$orderData = $data ['data'];
		if (isset ( $data ['is_pos'] ) && $data ['is_pos'] == 1) {
			$isSync = 0;
		} else {
			$isSync = DataSync::getInitSync ();
		}
		$obj = json_decode ( $orderData );
		
		$time = time ();
		$se = new Sequence ( "member_card" );
		$memberCardId = $se->nextval ();
		$inserMemberCardrArr = array (
				'lid' => $memberCardId,
				'dpid' => $dpid,
				'create_at' => date ( 'Y-m-d H:i:s', $time ),
				'update_at' => date ( 'Y-m-d H:i:s', $time ),
				'selfcode' => $obj->selfcode,
				'rfid' => $obj->rfid,
				'level_id' => $obj->level_id,
				'name' => $obj->name,
				'mobile' => $obj->mobile,
				'sex' => $obj->sex,
				'ages' => $obj->ages,
				'is_sync' => $isSync 
		);
		$result = Yii::app ()->db->createCommand ()->insert ( 'nb_member_card', $inserMemberCardrArr );
		if ($result) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * 
	 * 
	 * 会员卡支付
	 * 
	 * 
	 */
	public static function payMemberCard($data) {
		$dpid = $data ['dpid'];
		$rfid = $data ['rfid'];
		$password = $data ['password'];
		$payPrice = $data ['pay_price'];
		$sql = 'select * from nb_member_card where dpid=' . $dpid . ' and rfid=' . $rfid . ' and delete_flag=0';
		$reslut = Yii::app ()->db->createCommand ( $sql )->queryRow ();
		if (! $reslut) {
			throw new Exception ( '不存在该会员信息！' );
		}
		if ($payPrice > $reslut ['all_money']) {
			throw new Exception ( '余额不足！' );
		}
		$sql = 'update nb_member_card set all_money=all_money-' . $payPrice . ' where dpid=' . $dpid . ' and lid=' . $reslut ['lid'];
		$reslut = Yii::app ()->db->createCommand ( $sql )->execute ();
		if ($reslut) {
			return json_encode ( array (
					'status' => true 
			) );
		} else {
			return json_encode ( array (
					'status' => false 
			) );
		}
	}
	/**
	 * 
	 * 
	 * 
	 * 获取订单
	 * 
	 * 
	 * 
	 */
	public static function getOrderStaus($dpid, $orderId) {
		$order = WxOrder::getOrder ( $orderId, $dpid );
		if ($order) {
			return json_encode ( array (
					'status' => true,
					'order_status' => $order ['order_status'] 
			) );
		} else {
			return json_encode ( array (
					'status' => false,
					'order_status' => '' 
			) );
		}
	}
	/**
	 * 
	 * 
	 * 
	 * 日结订单
	 * 
	 * 
	 * 
	 */
	public static function operateCloseAccount($dpid, $userId) {
		$time = time ();
		$sql = 'select lid from nb_order where dpid=' . $dpid . ' and order_status in(3,4)';
		$lids = Yii::app ()->db->createCommand ( $sql )->queryColumn ();
		$lidStr = join ( ',', $lids );
		
		$sql = 'select sum(pay_amount) as pay_amount,paytype,payment_method_id,paytype_id from nb_order_pay where order_id in (' . $lidStr . ') group by paytype';
		$results = Yii::app ()->db->createCommand ( $sql )->queryAll ();
		
		$totalMoney = 0;
		$transaction = Yii::app ()->db->beginTransaction ();
		try {
			$se = new Sequence ( "close_account" );
			$closeAccountId = $se->nextval ();
			
			foreach ( $results as $result ) {
				$se = new Sequence ( "close_account_detail" );
				$closeAccountDetailId = $se->nextval ();
				$closeAccountDetailArr = array (
						'lid' => $closeAccountDetailId,
						'dpid' => $dpid,
						'create_at' => date ( 'Y-m-d H:i:s', $time ),
						'update_at' => date ( 'Y-m-d H:i:s', $time ),
						'close_account_id' => $closeAccountId,
						'paytype' => $result ['paytype'],
						'payment_method_id' => $result ['payment_method_id'],
						'all_money' => $result ['pay_amount'],
						'is_sync' => DataSync::getInitSync () 
				);
				Yii::app ()->db->createCommand ()->insert ( 'nb_close_account_detail', $closeAccountDetailArr );
				$totalMoney += $result ['pay_amount'];
			}
			$closeAccountArr = array (
					'lid' => $closeAccountId,
					'dpid' => $dpid,
					'create_at' => date ( 'Y-m-d H:i:s', $time ),
					'update_at' => date ( 'Y-m-d H:i:s', $time ),
					'user_id' => $userId,
					'close_day' => date ( 'Y-m-d', $time ),
					'all_money' => $totalMoney,
					'is_sync' => DataSync::getInitSync () 
			);
			Yii::app ()->db->createCommand ()->insert ( 'nb_close_account', $closeAccountArr );
			
			$sql = 'update nb_order set order_status=8 where lid in(' . $lidStr . ')';
			Yii::app ()->db->createCommand ( $sql )->execute ();
			
			$transaction->commit ();
			$msg = json_encode ( array (
					'status' => true,
					'closeAccountId' => $closeAccountId 
			) );
		} catch ( exception $e ) {
			$transaction->rollback ();
			$msg = json_encode ( array (
					'status' => false,
					'closeAccountId' => '' 
			) );
		}
		return $msg;
	}
	/**
	 * 
	 * bom库存对应信息
	 * 
	 * 
	 */
	public static function getBom($dpid, $productId) {
		$sql = 'select m.*,n.unit_ratio from (select t.product_id, t.material_id, t.number, t1.stock_unit_id, t1.sales_unit_id from nb_product_bom t,nb_product_material t1 where t.material_id=t1.lid and t.dpid=t1.dpid and t.dpid=' . $dpid . ' and t.product_id=' . $productId . ' and t.delete_flag=0 and t1.delete_flag=0)m,nb_material_unit_ratio n where m.stock_unit_id=n.stock_unit_id and m.sales_unit_id=n.sales_unit_id and n.delete_flag=0';
		$results = Yii::app ()->db->createCommand ( $sql )->queryAll ();
		return $results;
	}
	/**
	 * 
	 * 
	 * 更新库存
	 * 
	 * 
	 * 
	 */
	public static function updateMaterialStock($dpid, $materialId, $stock) {
		$time = time ();
		$sql = 'update nb_product_material_stock set stock=stock-'.$stock.' where dpid='.$dpid.' and material_id='.$materialId.' and delete_flag=0';
		Yii::app ()->db->createCommand ( $sql )->execute ();
		
		$se = new Sequence ( "material_stock_log" );
		$materialStockLogId = $se->nextval ();
		$materialStockLog = array (
				'lid' => $materialStockLogId,
				'dpid' => $dpid,
				'create_at' => date ( 'Y-m-d H:i:s', $time ),
				'update_at' => date ( 'Y-m-d H:i:s', $time ),
				'material_id' => $materialId,
				'type' => 1,
				'stock_num' => $stock,
				'resean' => '正常消耗',
				'is_sync' => DataSync::getInitSync ()
		);
		Yii::app ()->db->createCommand ()->insert ( 'nb_material_stock_log', $materialStockLog );
		
	}
}

