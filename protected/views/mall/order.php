<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('确认订单');
	$isCupon = false;
	if(!empty($cupons)){
		$isCupon = true;
	}
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/order.css">
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl.'/js/layer/layer.js';?>"></script>


<form action="<?php echo $this->createUrl('/mall/payOrder',array('companyId'=>$this->companyId,'orderId'=>$order['lid']));?>" method="post">
<div class="order-title">我的订单</div>
<?php if($this->type==1):?>
<div class="order-site">桌号:<?php if($siteType){echo $siteType['name'];}?><?php echo $site['serial'];?></div>
<?php else:?>

<?php endif;?>
<input type="hidden" name="cupon" value="0" />
<input type="hidden" name="paytype" value="2" />
<div class="order-info">
	<?php foreach($orderProducts as $product):?>
	<div class="item">
		<div class="lt"><?php echo $product['product_name'];?></div><div class="rt">X<?php echo $product['amount'];?> ￥<?php echo $product['price'];?></div>
		<div class="clear"></div>
	</div>
	<?php endforeach;?>
	<div class="ht1"></div>
	<div class="item">
		<div class="lt">合计:</div><div class="rt">￥<?php echo $order['reality_total'];?></div>
		<div class="clear"></div>
	</div>
	<?php if($order['reality_total'] - $order['should_total']):?>
	<div class="item">
		<div class="lt">优惠金额:</div><div class="rt">￥<?php echo number_format($order['reality_total'] - $order['should_total'],2);?></div>
		<div class="clear"></div>
	</div>
	<?php endif;?>
</div>

<div class="order-copun arrowright cupon <?php if(!$isCupon) echo 'disabled';?>">
	<div class="copun-lt">代金券</div><div class="copun-rt"><?php if($isCupon):?>选择代金券<?php else:?>无可用代金券<?php endif;?></div><div class="clear"></div>
</div>
<div class="order-paytype">
	<div class="select-type">选择支付方式</div>
	<div class="paytype">
		<div class="item on" paytype="2">线上支付</div>
		<div class="item" paytype="1">饭后支付</div>
	</div>
</div>
<div class="bottom"></div>

<footer>
    <div class="ft-lt">
        <p>￥<span id="total" class="total"><?php echo $order['should_total'];?></span></p>
    </div>
    <div class="ft-rt">
        <p><a id="payorder" href="javascript:;">去付款</a></p>
    </div>
    <div class="clear"></div>
</footer>
<div class="user-cupon" id="cuponList">
<?php if($isCupon):?>
<?php foreach($cupons as $coupon):?>
	<div class="item useCupon" user-cupon-id="<?php echo $coupon['lid'];?>" min-money="<?php echo $coupon['min_consumer'];?>" cupon-money="<?php echo $coupon['cupon_money'];?>"><?php echo $coupon['cupon_title'];?></div>
<?php endforeach;?>
<div class="item noCupon" user-cupon-id="0" min-money="0" cupon-money="0">不使用代金券</div>
<?php endif;?>
</div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	$('.paytype .item').click(function(){
		var paytype = $(this).attr('paytype');
		$('.paytype .item').removeClass('on');
		
		$('input[name="paytype"]').val(paytype);
		$(this).addClass('on');
		if(parseInt(paytype)==1){
			$('.noCupon').click();
		}
	});
	$('.user-cupon .item.useCupon').click(function(){
		var userCuponId = $(this).attr('user-cupon-id');
		var cuponMoney = $(this).attr('cupon-money');
		var noCuponMoney = $('.noCupon').attr('cupon-money');
		var minMoney = $(this).attr('min-money');
		var total = $('#total').html();
		var money = 0;
		
		$('.user-cupon .item').removeClass('on');
		$(this).addClass('on');
		$('#cuponList').css('display','none');
		$('input[name="cupon"]').val(userCuponId);
		$('.noCupon').attr('min-money',minMoney);
		$('.noCupon').attr('cupon-money',cuponMoney);
		
		money = parseFloat(total) + parseFloat(noCuponMoney) - parseFloat(cuponMoney);
		if(money > 0){
			money = money;
		}else{
			money = 0;
			$('.noCupon').attr('cupon-money',total);
		}
		money = money.toFixed(2);
		$('#total').html(money);
		$('.cupon').find('.copun-rt').html('满'+minMoney+'减'+cuponMoney);
	});
	$('.user-cupon .item.noCupon').click(function(){
		var userCuponId = $(this).attr('user-cupon-id');
		var cuponMoney = $(this).attr('cupon-money');
		var minMoney = $(this).attr('min-money');
		var total = $('#total').html();
		var money = 0;
		
		$('.user-cupon .item').removeClass('on');
		$(this).addClass('on');
		$('#cuponList').css('display','none');
		$('input[name="cupon"]').val(userCuponId);
		
		$(this).attr('min-money',0);
		$(this).attr('cupon-money',0);
		
		money = parseFloat(total) + parseFloat(cuponMoney);
		if(money > 0){
			money = money;
		}else{
			money = 0;
		}
		money = money.toFixed(2);
		$('#total').html(money);
		$('.cupon').find('.copun-rt').html('请选择代金券');
	});
	$('.cupon').click(function(){
		if($(this).hasClass('disabled')){
			layer.msg('无可用代金券');
			return;
		}
		$('#cuponList').css('display','block');
	});
	$('#payorder').click(function(){
		var paytype = $('.on').attr('paytype');
		var cupon =   $('input[name="cupon"]').val();
		
		var timestamp=new Date().getTime()
        var random = ''+timestamp + parseInt(Math.random()*899+100)+'';
		
		if(parseInt(paytype)==2){
			$.get('<?php echo $this->createUrl('/mall/getOrderStatus',array('companyId'=>$this->companyId,'orderId'=>$order['lid']))?>',{random:random},function(msg){
				if(parseInt(msg) < 2){
					layer.msg('服务员确认后才能付款!');
				}else{
					$('form').submit();
				}
			});
		}else{
			$('form').submit();
		}
	});
});
</script>
