<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('视频播放');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/screen.css">
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl;?>/player/cyberplayer.js"></script>

<div class="section">
	<h2><?php echo $screen['title'];?></h2>
	<div id="playercontainer"></div>
</div>
		
<script type="text/javascript">
		var player = cyberplayer("playercontainer").setup({
			flashplayer : "<?php echo $baseUrl;?>/player/cyberplayer.flash.swf",
			width : 680,
			height : 400,
			backcolor : "#FFFFFF",
			stretching : "uniform",
			file : "<?php echo $screen['vedio_url'];?>",
			// image : "images/bipbop.jpg",
			autoStart : true,
			// playlist: "bottom",
			// playlistfile:"playlist.xml",
			repeat : "always",
			volume : 100,
			controls : "over",
			//ak 和 sk（sk 只需前 16 位）参数值需要开发者进行申请
			ak : "85518b85842c4cc0809523322c8c05c3",
			sk : "4373f8e3941d4a19"
		});
		function init_barrage(obj){
			var _top = 20;
			$('.top').find('.'+obj).show().each(function(){
				var topW = $('.top').width();
				var thisW = $(this).width();
				var _left = topW + thisW;

				$(this).css({left:_left,top:_top,color:getRandomColor()});
				
				_top +=75;
				if(_top >= 90){
					_top = 20;
				}


				var time = 15000;
				if($(this).index() % 2 == 0){
					time = 10000;
				}

				$(this).animate({left:"-"+_left+"px"},time,function(){
					$(this).remove();
				});
			});
		}
		//获取随机颜色
		function getRandomColor(){
			return '#' + (function(h){
				return new Array(7 - h.length).join("0") + h
			})((Math.random() * 0x1000000 << 0).toString(16))
		}
		$(document).ready(function(){
			$("#playercontainer").append('<div class="top"></div>');
			var i = 0;
			 setInterval(function(){
				$.ajax({
					url:'<?php echo $this->createUrl('/screen/ajaxGetDiscuss',array('companyId'=>$this->companyId));?>',
					dataType:'json',
					success:function(msg){
						for(var p in msg){
							$(".top").append('<div class="message ms'+i+'">'+msg[p]['content']+'</div>');
						}
						init_barrage('ms'+i);
						i++;
						if(i > 100000){
							i = 0;
						}
					},

				});
			 },3000);
		});
</script>