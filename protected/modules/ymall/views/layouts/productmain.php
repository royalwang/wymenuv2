<?php


/* @var $this \yii\web\View */
/* @var $content string */
if(isset($_GET['wuyimenusysosyoyhmac']))
{
	$_SESSION['smac']=$_GET['wuyimenusysosyoyhmac'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>壹点吃商城</title>
    <link rel="stylesheet" type="text/css" href="../../../../css/ymall/ymall.css"/>
    <script type="text/javascript" src="../../../../plugins/jquery-1.10.2.min.js"></script>
</head>
<body>
    <div class="ymall">
    <?php echo $content; ?>
    </div>
</body>
</html>