    <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js');?>"></script>
    <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js');?>"></script>
    <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/echarts.min.js');?>"></script>
    <!-- BEGIN PAGE -->
    <div class="page-content">
	<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               

	<div id="main2" style="width: 600px;height: 400px;display: none;" onMouseOver="this.style.background='#fff'" onmouseout="this.style.background=''"></div>
	
	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- BEGIN PAGE HEADER-->
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','报表中心'),'url'=>$this->createUrl('statementstock/list' , array('companyId'=>$this->companyId,'type'=>1,))),array('word'=>yii::t('app','进销存汇总报表 '),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('statementstock/list' , array('companyId' => $this->companyId,'type'=>1,)))));?>

	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
		<div class="col-md-12">
			<div class="btn-group">
				<?php $this->widget('application.modules.admin.components.widgets.CompanySelect2', array('companyType'=>$this->comptype,'companyId'=>$this->companyId,'selectCompanyId'=>$selectDpid));?>
			</div>
			<select id="text" class="btn yellow" >
				<option selected="selected"><?php echo yii::t('app','汇总');?></option>
			</select>
			<div class="btn-group">
				 <input style="width: 100px;" type="text" class="form-control" name="codename" id="codename" placeholder="<?php echo yii::t('app','原料编号');?>" value="<?php echo $codename;?>" > 
			</div>
			<div class="btn-group">
				 <input style="width: 100px;" type="text" class="form-control" name="matename" id="matename" placeholder="<?php echo yii::t('app','原料名称');?>" value="<?php echo $matename;?>" > 
			</div>
			<div class="btn-group">
				<div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
					<input type="text" class="form-control ui_timepicker" name="begtime" id="begin_time" placeholder="<?php echo yii::t('app','起始时间');?>" value="<?php echo $begin_time; ?>">  
					<span class="input-group-addon">~</span>
				    <input type="text" class="form-control ui_timepicker" name="endtime" id="end_time" placeholder="<?php echo yii::t('app','终止时间');?>"  value="<?php echo $end_time;?>">           
			  	</div>  
			</div>	
			<div class="btn-group">
				<?php echo CHtml::dropDownList('selectCategory', $categoryId, $categories , array('class'=>'form-control'));?>
			</div>
		</div>
		<br>
	</div>
	<div class="row">
	<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','进销存汇总报表');?></div>
					<div class="actions">
						<div class="btn-group">
								<button type="submit" id="btn_time_query" class="btn red" ><i class="fa fa-pencial"></i><?php echo yii::t('app','查 询');?></button>
								<button type="submit" id="excel"  class="btn green" ><i class="fa fa-pencial"></i><?php echo yii::t('app','导出Excel');?></button>				
						</div>			
					</div>
			 	</div> 
			<div style="overflow-x: auto;" class="portlet-body" class="portlet-body" >
				<div class="portlet-body" id="table-manage">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th><?php echo yii::t('app','时间');?></th>
								<th><?php echo yii::t('app','类型');?></th>
								<th><?php echo yii::t('app','原料编码');?></th>
								<th><?php echo yii::t('app','名称');?></th>                                                              
                                <th><?php echo yii::t('app','销售单位');?></th>
                                <th><?php echo yii::t('app','上期库存');?></th>
                                <th><?php echo yii::t('app','入库总量');?></th>
								<th><?php echo yii::t('app','进货总量');?></th>
								<th><?php echo yii::t('app','进货成本');?></th>
								<th><?php echo yii::t('app','配送量');?></th>
								<th><?php echo yii::t('app','调拨量');?></th>
								<th><?php echo yii::t('app','损耗总量');?></th>
								<th><?php echo yii::t('app','损耗成本');?></th>
								<th><?php echo yii::t('app','销售出库');?></th>
								<th><?php echo yii::t('app','销售成本');?></th>
								<th><?php echo yii::t('app','总消耗量');?></th>
								<th><?php echo yii::t('app','系统库存');?></th>
								<th><?php echo yii::t('app','盘点库存');?></th>
								<th><?php echo yii::t('app','损溢总量');?></th>
								<th><?php echo yii::t('app','损溢成本');?></th>
							</tr>
						</thead>
						<tbody>
							<?php if( $sqlmodels) :?>
							<!--foreach-->
							<?php foreach ($sqlmodels as $model):?>
							<tr class="odd gradeX">
								<td><?php echo $model['create_at']?></td>
								<td><?php if($model['type']==1){echo '日报';}elseif ($model['type']==2){echo '周报';}else{echo '月报';}?></td>
								<td><?php echo $model['material_identifier'];?></td>
								<td><?php echo $model['material_name'];?></td>
								<td><?php echo $model['sales_name'];?></td>
								<td><?php echo $model['prestock_taking_num'];?></td>
								<td><?php echo $model['stockin_num'];?></td>
								<td><?php echo $model['stockin_num'];?></td>
								<td><?php echo $model['stockin_price'];?></td>
								<td></td>
								<td></td>
								<td><?php echo $model['damage_num'];?></td>
								<td><?php echo $model['damage_price'];?></td>
								<td><?php echo $model['salse_num'];?></td>
								<td><?php echo $model['salse_price'];?></td>
								<td><?php echo $model['total_num'];?></td>
								<!-- 总消耗 = 销售出库+损耗-损益 -->
								<td><?php echo $model['system_num'];?></td>
								<td><?php echo $model['stock_taking_num'];?></td>
								<td><?php echo $model['stock_taking_difnum'];?></td>
								<td><?php echo $model['stock_taking_difprice'];?></td>
							</tr>
							<?php endforeach;?>	
							<!-- end foreach-->
							<?php else:?>
							<tr>
							<td colspan='20'>未查询到数据。</td>
							</tr>
							<?php endif;?>
						</tbody>
					</table>
					</div>
				</div></div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
	
	</div>
	<!-- END PAGE CONTENT-->
</div>
<!-- END PAGE -->

<script>

	//var str=new array();						
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

  
	$('#btn_time_query').click(function time() {
		var begin_time = $('#begin_time').val();
		var end_time = $('#end_time').val();
		var cid = $('#selectCategory').val();
		var codename = $('#codename').val();
		var matename = $('#matename').val();
		var selectDpid = $('select[name="selectDpid"]').val();
		location.href="<?php echo $this->createUrl('statementstock/stockallReport' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/codename/"+codename+"/cid/"+cid+"/matename/"+matename+'/selectDpid/'+selectDpid;    
	});
	$('#excel').click(function excel(){
		var begin_time = $('#begin_time').val();
		var end_time = $('#end_time').val();
		var cid = $('#selectCategory').val();
		var codename = $('#codename').val();
		var matename = $('#matename').val();
		var selectDpid = $('select[name="selectDpid"]').val();
		if(confirm('确认导出并且下载Excel文件吗？')){
			location.href="<?php echo $this->createUrl('statementstock/stockallReport' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/codename/"+codename+"/cid/"+cid+"/matename/"+matename+'/selectDpid/'+selectDpid+'/d/1'; 
		}
	});

</script> 
