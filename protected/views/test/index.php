<?php
	$dpid = 28;
	$str = '{"nb_site_no":[],"nb_order_platform":[],"nb_order":{"lid":"0010992574","dpid":"0000000042","create_at":"2018-08-07 15:44:51","update_at":"2018-08-07 15:44:52","user_id":"0000004968","account_no":"180807992574","classes":"0000000000","username":"","site_id":"0000110772","is_temp":"1","number":"1","order_status":3,"order_type":"6","takeout_typeid":"0","takeout_status":"0","appointment_time":"2018-08-07 15:44:52","lock_status":"0","should_total":"14.00","reality_total":"18.00","callno":"","paytype":"1","payment_method_id":"0000000000","pay_time":"0000-00-00 00:00:00","remark":"","taste_memo":"","cupon_branduser_lid":"0000000000","cupon_money":"0.00","is_sync":"11111","taste":[]},"nb_order_product":[{"lid":"30785584","dpid":"42","create_at":"2018-08-07 15:44:51","update_at":"2018-08-07 15:45:12","order_id":"10992574","set_id":"0","private_promotion_lid":"2610","main_id":"0","product_id":"134132","product_name":"\u96ea\u9876\u5496\u5561","product_pic":"\/wymenuv2\/.\/uploads\/company_0000000032\/84657AA4-E398-46F5-8101-C7E5E65D23A9.jpg","product_type":"0","is_retreat":"0","original_price":"9.00","price":"7.0000","offprice":"100%","amount":"2","zhiamount":"1","is_waiting":"0","weight":"0.00","taste_memo":"","is_giving":"0","is_print":"0","product_status":"0","delete_flag":"0","product_order_status":"8","is_sync":"11111","set_name":"","set_price":"0.00","product_taste":[],"product_promotion":[]}],"nb_order_pay":[{"lid":"0014428918","dpid":"0000000042","create_at":"2018-08-07 15:44:51","update_at":"2018-08-07 15:44:52","order_id":"0010992574","account_no":"180807992574","pay_amount":"14.00","paytype":"12","payment_method_id":"00000000000","paytype_id":"0","remark":"0010992574-0000000042-611","is_sync":"11111"}],"nb_order_address":[],"nb_order_taste":[],"nb_order_account_discount":[]}';
	$res = Yii::app()->redis->lPush('redis-third-platform-'.(int)$dpid,$str);
	var_dump($res);
?>

