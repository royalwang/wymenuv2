<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js');?>"></script>
<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js');?>"></script>

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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','库存管理'),'url'=>$this->createUrl('bom/bom' , array('companyId'=>$this->companyId,'type'=>2,))),array('word'=>yii::t('app','盘损日志'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('bom/bom' , array('companyId' => $this->companyId,'type' => '2',)))));?>
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">

	<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','盘点日志');?></div>
					<div class="actions">
						<div class="btn-group">
							<select id="stype" class="btn yellow" >
								<option value="0" <?php if ($stype==0){?> selected="selected" <?php }?> ><?php echo yii::t('app','选择类型');?></option>
								<option value="1" <?php if ($stype==1){?> selected="selected" <?php }?> ><?php echo yii::t('app','日盘');?></option>
								<option value="2" <?php if ($stype==2){?> selected="selected" <?php }?> ><?php echo yii::t('app','周盘');?></option>
								<option value="3" <?php if ($stype==3){?> selected="selected" <?php }?> ><?php echo yii::t('app','月盘');?></option>
							</select>
						</div>
						<div class="btn-group">
							
						   <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
								<input type="text" class="form-control ui_timepicker" name="begtime" id="begin_time" placeholder="<?php echo yii::t('app','起始时间');?>" value="<?php echo $begin_time; ?>">  
								<span class="input-group-addon">~</span>
							    <input type="text" class="form-control ui_timepicker" name="endtime" id="end_time" placeholder="<?php echo yii::t('app','终止时间');?>"  value="<?php echo $end_time;?>">           
						  </div>  
						</div>	
					
						<div class="btn-group">
							<button type="button" id="btn_time_query" class="btn red" ><i class="fa fa-pencial"></i><?php echo yii::t('app','查 询');?></button>
						</div>	
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
				<div class="dataTables_wrapper form-inline">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<!--<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>-->
								<th style="width:16%"><?php echo yii::t('app','盘点日期');?></th>
								<th><?php echo yii::t('app','盘点类型');?></th>
								<th><?php echo yii::t('app','盘点人');?></th>
								<th><?php echo yii::t('app','记录');?></th>
								<th><?php echo yii::t('app','备注');?></th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><?php echo $model->create_at;?></td>
								<td><?php if($model->type==1){ echo '日盘';}elseif($model->type==2){ echo '周盘';}else{ echo '月盘';}?></td>
								<td><?php echo $model->username;?></td>
								<td><?php echo $model->title;?></td>
								<td class="center">
								<a href="<?php echo $this->createUrl('stocktakinglog/detailindex',array('id' => $model->lid , 'companyId' => $model->dpid , 'begin_time'=>$begin_time , 'end_time'=>$end_time));?>"><?php echo yii::t('app','查看详情');?></a>
								</td>
							</tr>
						<?php endforeach;?>
						<?php endif;?>
						</tbody>
					</table>
					</div>
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
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>

	</div>
	<!-- END PAGE CONTENT-->
	<script type="text/javascript">
	$(document).ready(function(){

		if (jQuery().datepicker) {
			$('.date-picker').datepicker({
				format: 'yyyy-mm-dd',
				language: 'zh-CN',
				rtl: App.isRTL(),
				autoclose: true
			});
			$('body').removeClass("modal-open");
		}
		  
		   $('#btn_time_query').click(function time() {
			   var stype = 0;
			   if($('#stype').length > 0){
				   stype = $('#stype').val();
			   }  
			   var begin_time = $('#begin_time').val();
			   var end_time = $('#end_time').val();
			   location.href="<?php echo $this->createUrl('stocktakinglog/index' , array('companyId'=>$this->companyId));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/stype/"+stype   
			  
	        });
	});
	</script>	