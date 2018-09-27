    <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js');?>"></script>
    <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js');?>"></script>
     <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/css/datepicker.css';?>" />
         <!-- BEGIN PAGE -->
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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','数据中心'),'subhead'=>yii::t('app','营业数据'),'breadcrumbs'=>array(array('word'=>yii::t('app','营业数据'),'url'=>$this->createUrl('statements/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','代金券使用情况报表'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('statements/list' , array('companyId' => $this->companyId,'type'=>0)))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','代金券使用情况报表');?></div>
					<div class="actions">
						<div class="btn-group">
						<select  class="form-control input-medium select2me" name="selectCupon" data-placeholder="请选择券名...">
							<option value=""></option>
							<?php foreach ($cupons as $cupon):?>
							<option value="<?php echo $cupon['lid'];?>" <?php if($cupon['lid']==$cuponId){ echo 'selected="selected"';}?>><?php echo $cupon['cupon_title'];?></option>
							<?php endforeach;?>
						</select>
						</div>
						<div class="btn-group">
								<button type="submit" id="btn_time_query" class="btn red" ><i class="fa fa-pencial"></i><?php echo yii::t('app','查 询');?></button>
								<button type="submit" id="excel"  class="btn green" ><i class="fa fa-pencial"></i><?php echo yii::t('app','导出Excel');?></button>				
						</div>			
				    </div>
				 </div> 
			
				<div class="portlet-body" id="table-manage">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th><?php echo yii::t('app','序号');?></th>
								<th><?php echo yii::t('app','店名');?></th>
								<th><?php echo yii::t('app','联系人');?></th>
								<th><?php echo yii::t('app','联系电话');?></th>
								<th><?php echo yii::t('app','联系地址');?></th>
								<th><?php echo yii::t('app','发券数量');?></th>
                                <th><?php echo yii::t('app','当前店铺会员使用数量');?></th>
                                <th><?php echo yii::t('app','其他店铺会员使用数量');?></th>
                                <th><?php echo yii::t('app','未使用数量');?></th>
                                <th><?php echo yii::t('app','过期数量');?></th>
							</tr>
						</thead>
						<tbody>
						<?php if($models):?>
							<?php $key=0; foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><?php echo $key+1;?></td>
								<td><?php echo $model['company_name'];?></td>
								<td><?php echo $model['contact_name'];?></td>
								<td><?php echo $model['mobile'];?></td>
								<td><?php echo $model['province'].$model['city'].$model['county_area'].$model['address'];?></td>
								<td><?php echo $model['cupon_sent'];?></td>
								<td><?php echo count($model['cupon_used_0']);?></td>
								<td><?php echo count($model['cupon_used_1']);?></td>
								<td><?php echo count($model['cupon_noused']);?></td>
								<td><?php echo count($model['cupon_expire']);?></td>
							</tr>
							<?php $key++; endforeach;?>
						<?php else:?>
						<tr><td colspan="10">未查询到数据</td></tr>
						<?php endif;?>
						</tbody>
					</table>
					</div>
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
	
	</div>
	<!-- END PAGE CONTENT-->
</div>
<!-- END PAGE -->

<script>
		jQuery(document).ready(function(){
		    if (jQuery().datepicker) {
	            $('.date-picker').datepicker({
	            	format: 'yyyy-mm-dd',
	            	language: 'zh-CN',
	                rtl: App.isRTL(),
	                autoclose: true
	            });
	            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
	            
           }
		});
		 
		       
		   $('#btn_time_query').click(function() {  
			   var cuponId = $('select[name="selectCupon"]').val();
			   location.href="<?php echo $this->createUrl('statements/cuponReportDetail' , array('companyId'=>$this->companyId ));?>/cuponId/"+cuponId;
			  
	        });
			 $('#excel').click(function excel(){

				   
		    	   var begin_time = $('#begin_time').val();
				   var end_time = $('#end_time').val();
				  
				   //alert(str);
			       if(confirm('确认导出并且下载Excel文件吗？')){

			    	   location.href="<?php echo $this->createUrl('statements/cuponReportExport' , array('companyId'=>$this->companyId,'d'=>1));?>/begin_time/"+begin_time+"/end_time/"+end_time;
			       }
			       else{
			    	  // location.href="<?php echo $this->createUrl('statements/diningNum' , array('companyId'=>$this->companyId,'d'=>1));?>/str/"+str+"/begin_time/"+begin_time+"/end_time/"+end_time;
			       }
			      
			   });

</script> 
