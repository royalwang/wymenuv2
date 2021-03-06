<?php $form=$this->beginWidget('CActiveForm', array(
		'id' => 'material-form',
		'errorMessageCssClass' => 'help-block',
		'htmlOptions' => array(
			'class' => 'form-horizontal',
			'enctype' => 'multipart/form-data'
		),
)); ?>
	
	<div class="form-body">
		<div class="form-group <?php if($model->hasErrors('classid')) echo 'has-error';?>">
			<?php echo $form->label($model, 'classid',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->dropDownList($model, 'classid', array('0' => yii::t('app','门店') , '1' => yii::t('app','仓库'), '2'=>yii::t('app','其他')),array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('classid')));?>
				<?php echo $form->error($model, 'classid' )?>
			</div>
		</div>
        <div class="form-group <?php if($model->hasErrors('classification_name')) echo 'has-error';?>">
			<?php echo $form->label($model, 'classification_name',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-4">
				<?php echo $form->textField($model, 'classification_name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('classification_name')));?>
				<?php echo $form->error($model, 'classification_name' )?>
			</div>
		</div>
		<div class="form-group <?php if($model->hasErrors('remark')) echo 'has-error';?>">
			<?php echo $form->label($model, 'remark',array('class' => 'col-md-3 control-label'));?>
			<div class="col-md-5">
				<?php echo $form->textArea($model, 'remark',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('remark')));?>
				<?php echo $form->error($model, 'remark' )?>
			</div>
		</div>
		<div class="form-actions fluid">
			<div class="col-md-offset-3 col-md-9">
				<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
				<a href="<?php echo $this->createUrl('orgClassification/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
			</div>
		</div>
<?php $this->endWidget(); ?>
<?php $this->widget('ext.kindeditor.KindEditorWidget',array(
	'id'=>'',	//Textarea id
	'language'=>'zh_CN',
	// Additional Parameters (Check http://www.kindsoft.net/docs/option.html)
	'items' => array(
		'height'=>'200px',
		'width'=>'100%',
		'themeType'=>'simple',
		'resizeType'=>1,
		'allowImageUpload'=>true,
		'allowFileManager'=>true,
	),
)); ?>
