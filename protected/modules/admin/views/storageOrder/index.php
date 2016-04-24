<div class="page-content">
	<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
	<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">Modal title</h4>
				</div>
				<div class="modal-body">
					Widget settings form goes here
				</div>
				<div class="modal-footer">
					<button type="button" class="btn blue">Save changes</button>
					<button type="button" class="btn default" data-dismiss="modal">Close</button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- BEGIN PAGE HEADER-->
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','入库单'),'subhead'=>yii::t('app','入库单列表'),'breadcrumbs'=>array(array('word'=>yii::t('app','入库单'),'url'=>''))));?>
	
	<!-- END PAGE HEADER-->
	<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/jquery-ui-1.8.17.custom.css');?>
	<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/jquery-ui-timepicker-addon.css');?>
	<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-1.7.1.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-1.8.17.custom.min.js');?>
	<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-timepicker-addon.js');?>
	<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-timepicker-zh-CN.js');?>

	<div class="">
		<form action="" method="get" class="">
			<input type="text" class="col-md-2" placeholder="厂商名称" value="" />&nbsp;&nbsp;<input type="text" class="col-md-2" placeholder="组织名称" />&nbsp;&nbsp;<input type="text" class="ui_timepicker col-md-2" placeholder="起始日期" />&nbsp;&nbsp;<input type="text" class="ui_timepicker col-md-2" placeholder="结束日期" />&nbsp;&nbsp;<input type="submit" class="btn blue" value="搜索" />
		</form>
	</div>
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'material-form',
				'action' => $this->createUrl('storageOrder/delete' , array('companyId' => $this->companyId)),
				'errorMessageCssClass' => 'help-block',
				'htmlOptions' => array(
					'class' => 'form-horizontal',
					'enctype' => 'multipart/form-data'
				),
		)); ?>
	<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','入库单列表');?></div>
					<div class="actions">
						<a href="<?php echo $this->createUrl('storageOrder/create' , array('companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-pencil"></i> <?php echo yii::t('app','添加');?></a>
						<div class="btn-group">
							<button type="submit"  class="btn red" ><i class="fa fa-ban"></i> <?php echo yii::t('app','删除');?></button>
						</div>
						<a href="<?php echo $this->createUrl('bom/bom' , array('companyId' => $this->companyId));?>" class="btn blue"> <?php echo yii::t('app','返回');?></a>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
								<th style="width:16%"><?php echo yii::t('app','厂商名称');?></th>
								<th><?php echo yii::t('app','组织名称');?></th>
								<th><?php echo yii::t('app','入库单号');?></th>
								<th><?php echo yii::t('app','管理员');?></th>
								<th><?php echo yii::t('app','订货单号');?></th>
								<th><?php echo yii::t('app','入库日期');?></th>
								<th><?php echo yii::t('app','备注');?></th>
								<th>&nbsp;</th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" name="ids[]" /></td>
								<td style="width:16%"><?php echo Common::getmfrName($model->manufacturer_id);?></td>
								<td ><?php echo Common::getorgName($model->organization_id);?></td>
								<td><?php echo $model->storage_account_no;?></td>
								<td><?php echo Common::getuserName($model->admin_id);?></td>
								<td><?php echo $model->purchase_account_no;?></td>
								<td><?php echo $model->storage_date;?></td>
								<td><?php echo $model->remark;?></td>
								<td class="center">
									<a href="<?php echo $this->createUrl('/admin/storageOrderDetail/index',array('id' => $model->lid , 'companyId' => $model->dpid));?>"><?php echo yii::t('app','订单详情');?></a>
								</td>
								<td class="center">
								<a href="<?php echo $this->createUrl('storageOrder/update',array('id' => $model->lid , 'companyId' => $model->dpid));?>"><?php echo yii::t('app','编辑');?></a>
								</td>
							</tr>
						<?php endforeach;?>
						<?php endif;?>
						</tbody>
					</table>
					<?php if($pages->getItemCount()):?>
						<div class="row">
							<div class="col-md-5 col-sm-12">
								<div class="dataTables_info">
									<?php echo yii::t('app','共');?> <?php echo $pages->getPageCount();?> <?php echo yii::t('app','页');?> , <?php echo $pages->getItemCount();?> <?php echo yii::t('app','条数据');?> , <?php echo yii::t('app','当前是第');?> <?php echo $pages->getCurrentPage()+1;?> <?php echo yii::t('app','页');?>
								</div>
							</div>
							<div class="col-md-7 col-sm-12">
								<div class="dataTables_paginate paging_bootstrap">
								<?php $this->widget('CLinkPager', array(
									'pages' => $pages,
									'header'=>'',
									'firstPageLabel' => '<<',
									'lastPageLabel' => '>>',
									'firstPageCssClass' => '',
									'lastPageCssClass' => '',
									'maxButtonCount' => 8,
									'nextPageCssClass' => '',
									'previousPageCssClass' => '',
									'prevPageLabel' => '<',
									'nextPageLabel' => '>',
									'selectedPageCssClass' => 'active',
									'internalPageCssClass' => '',
									'hiddenPageCssClass' => 'disabled',
									'htmlOptions'=>array('class'=>'pagination pull-right')
								));
								?>
								</div>
							</div>
						</div>
						<?php endif;?>					
					
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
		<?php $this->endWidget(); ?>
	</div>
	<!-- END PAGE CONTENT-->
	<script type="text/javascript">
	$(document).ready(function(){
		$('#material-form').submit(function(){
			if(!$('.checkboxes:checked').length){
				alert("<?php echo yii::t('app','请选择要删除的项');?>");
				return false;
			}
			return true;
		});
		$('.s-btn').on('switch-change', function () {
			var id = $(this).find('input').attr('pid');
		    $.get('<?php echo $this->createUrl('storageOrder/status',array('companyId'=>$this->companyId));?>/id/'+id);
		});
		$('.r-btn').on('switch-change', function () {
			var id = $(this).find('input').attr('pid');
		    $.get('<?php echo $this->createUrl('storageOrder/recommend',array('companyId'=>$this->companyId));?>/id/'+id);
		});
		$('#selectCategory').change(function(){
			var cid = $(this).val();
			location.href="<?php echo $this->createUrl('storageOrder/index' , array('companyId'=>$this->companyId));?>/cid/"+cid;
		});
	});
	$(function () {
		$(".ui_timepicker").datetimepicker({
			showSecond: true,
			timeFormat: 'hh:mm:ss',
			stepHour: 1,
			stepMinute: 1,
			stepSecond: 1
		})
	});
	</script>	