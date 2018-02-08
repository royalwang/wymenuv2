	<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/jquery-1.10.2.min.js');?>

	<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/wechat_css/mobiscroll.min.css');?>
    <?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/wechat_js/mobiscroll.min.js');?>
    
    <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js');?>
	
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js');?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js');?>
    
    <!-- BEGIN PAGE -->
    <style>
    .modal-seach{vertical-align: middle;text-align: center;padding: 10px 10px;}
        .btn-group{
    padding: 4px 2px;
    }
    .float-right{
    float: right;
    }
    </style>
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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','报表中心'),'subhead'=>yii::t('app','营业数据'),'breadcrumbs'=>array(array('word'=>yii::t('app','营业数据'),'url'=>$this->createUrl('statements/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','单品销售详情报表'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('statements/list' , array('companyId' => $this->companyId,'type'=>0)))));?>
	<div class="row">
	<div class="col-md-12">
	<div style="border: 1px solid silver;padding: 10px;margin-bottom: 25px;">
		<div class="actions ">
			<?php if(Yii::app()->user->role <=5):?>
			<div id="dpidchoose" class="btn-group "><input class="btn blue" value="选择店铺"/></div>
			<?php endif;?>
			<input type="hidden" id="dpids" name="dpids" value="<?php if($str)echo $str;?>" />
			<div class="btn-group">
				<?php echo CHtml::dropDownList('selectCategory', $categoryId, $categories , array('class'=>'form-control'));?>
			</div>
			<div class="btn-group" >
				<select class="form-control" id="pdname">
				<option cate = "0" value="0"><?php echo '--请选择单品--';?></option>
				<?php if($products):?>
				<?php foreach ($products as $m):?>
				<option class="proname <?php if($categoryId){if($categoryId!=$m->chs_code){echo 'hide';}}?>" cate = "<?php echo $m->chs_code;?>" value="<?php echo $m->phs_code;?>"<?php if($pdname == $m->phs_code) echo 'selected';?> ><?php echo $m->product_name;?></option>
				<?php endforeach;endif;?>
				<?php ?>
				</select>
			</div>
			<select id="ordertype" class="btn yellow" >
				<option value="-1" <?php if ($ordertype==-1){?> selected="selected" <?php }?> ><?php echo yii::t('app','全部');?></option>
				<option value="0" <?php if ($ordertype==0){?> selected="selected" <?php }?> ><?php echo yii::t('app','堂食');?></option>
				<option value="1" <?php if ($ordertype==1){?> selected="selected" <?php }?> ><?php echo yii::t('app','微信堂食');?></option>
				<option value="2" <?php if ($ordertype==2){?> selected="selected" <?php }?> ><?php echo yii::t('app','微信外卖');?></option>
				<option value="3" <?php if ($ordertype==3){?> selected="selected" <?php }?> ><?php echo yii::t('app','微信预约');?></option>
				<option value="4" <?php if ($ordertype==4){?> selected="selected" <?php }?> ><?php echo yii::t('app','后台外卖');?></option>
				<option value="5" <?php if ($ordertype==5){?> selected="selected" <?php }?> ><?php echo yii::t('app','自助');?></option>
				<option value="6" <?php if ($ordertype==6){?> selected="selected" <?php }?> ><?php echo yii::t('app','微信点单');?></option>
				<option value="7" <?php if ($ordertype==7){?> selected="selected" <?php }?> ><?php echo yii::t('app','美团');?></option>
				<option value="8" <?php if ($ordertype==8){?> selected="selected" <?php }?> ><?php echo yii::t('app','饿了么');?></option>
			</select>
			<select style="display: none;" id="text" class="btn yellow" >
				<option value="1" <?php if ($text==1){?> selected="selected" <?php }?> ><?php echo yii::t('app','年');?></option>
				<option value="2" <?php if ($text==2){?> selected="selected" <?php }?> ><?php echo yii::t('app','月');?></option>
				<option value="3" <?php if ($text==3){?> selected="selected" <?php }?> ><?php echo yii::t('app','日');?></option>
			</select>
			<select id="setid" class="btn green" >
				<option value="1" <?php if ($setid==1){?> selected="selected" <?php }?> ><?php echo yii::t('app','综合');?></option>
				<option value="0" <?php if ($setid==0){?> selected="selected" <?php }?> ><?php echo yii::t('app','单品');?></option>
				<option value="2" <?php if ($setid==2){?> selected="selected" <?php }?> ><?php echo yii::t('app','套餐单品');?></option>
			</select>
			<div class="btn-group">
			   <div class="input-group input-large date-picker input-daterange" data-date="10/11/2017" data-date-format="mm/dd/yyyy">
					<input type="text" class="form-control" name="begtime" id="begin_time" placeholder="<?php echo yii::t('app','起始时间');?>" value="<?php echo $begin_time; ?>">  
					<span class="input-group-addon">~</span>
				    <input type="text" class="form-control" name="endtime" id="end_time" placeholder="<?php echo yii::t('app','终止时间');?>"  value="<?php echo $end_time;?>">           
			  </div>  
		    </div>
		    <div class="btn-group ">时段：<input id="checktime" <?php if($cks)echo 'checked';?> type="checkbox" /></div>
		    <div class="btn-group times <?php if(!$cks)echo 'hide';?>">
	            <div class="input-group input-large  input-daterange" data-date="10:10" data-date-format="h:i">
	            <input type="text" class="form-control" name="begtime" id="day_begin" placeholder="<?php echo yii::t('app','起始时段');?>" value="<?php echo $day_begin; ?>">  
	            <span class="input-group-addon"> ~ </span>
	            <input type="text" class="form-control" name="endtime" id="day_end" placeholder="<?php echo yii::t('app','终止时段');?>"  value="<?php echo $day_end;?>"> 
	            </div> 
            </div>	
			<div class="btn-group float-right">
					<button type="submit" id="btn_time_query" class="btn red" ><i class="fa fa-pencial"></i><?php echo yii::t('app','查 询');?></button>
					<button type="submit" id="excel"  class="btn green" ><i class="fa fa-pencial"></i><?php echo yii::t('app','导出Excel');?></button>		
			</div>
	    </div>
	</div>
	</div>
	</div>
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','单品销售报表');?></div>
			 </div> 
				<div class="portlet-body" id="table-manage">
				<div class="dataTables_wrapper form-inline">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th><?php echo yii::t('app','下单时间');?></th>
								<th>
								</th>
								<th><?php echo yii::t('app','类别');?></th>
                                <th><?php echo yii::t('app','单品名称(旧名称)');?></th>
                                <th><?php echo yii::t('app','销量');?></th>
                                <th><?php echo yii::t('app','销售金额');?></th>                                                        
                                <th><?php echo yii::t('app','折扣金额');?></th>
								<th><?php echo yii::t('app','实收金额');?></th>
								<th><?php echo yii::t('app','备注');?></th>
								
							</tr>
						</thead>
						<tbody>
						<?php if( $models) :?>
						<!--foreach-->
						<?php $a=1;?>
						<?php foreach ($models as $model):?>
						<?php if($model['all_total'] == 0 || $model['all_total'] == null): $model['all_total'] = 1;endif;?>
								<tr class="odd gradeX">
								<td><?php echo $model['create_at'];?></td>
								<td><?php echo $model['company_name'];?></td>
								<td><?php if($model['category_name']) echo $model['category_name'];else '其他';?></td>
								<td><?php if($model['product_type'] !=2) echo $model['new_name'].'('.$model['product_name'].')';else echo '打包费';?></td>
								<td><?php echo $model['all_total'];?></td>
								<td><?php echo sprintf("%.2f",$model['all_jiage']);?></td>
								<td><?php echo sprintf("%.2f",$model['all_jiage']-$model['all_price']);?></td>
								<td><?php echo sprintf("%.2f",$model['all_price']);?></td>
								<td></td>
							</tr>
						<?php $a++;?>
						<?php endforeach;?>	
						<!-- end foreach-->
						<?php endif;?>
						</tbody>
					</table>
					</div>
					<?php if($pages->getItemCount()):?>
					<div class="row">
						<div class="col-md-5 col-sm-12">
							<div class="dataTables_info">
								<?php echo yii::t('app','共 ');?> <?php echo $pages->getPageCount();?> <?php echo yii::t('app','页');?>  , <?php echo $pages->getItemCount();?> <?php echo yii::t('app','条数据');?> ,  <?php echo yii::t('app','当前是第');?> <?php echo $pages->getCurrentPage()+1;?> <?php echo yii::t('app','页');?>
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
	<!-- END PAGE CONTENT-->
</div>
<!-- END PAGE -->
	
		<div id="printRsultListdetail" style="margin:0;padding:0;display:none;width:96%;height:96%;">
		<div class="modal-header">
			<h4 class="modal-title">选择店铺</h4>
		</div>
		<div class="modal-body">
			<div class="portlet-body" id="table-manage">
				<div id="reportlistdiv" style="display:inline-block;width:100%;font-size:1.5em;">
					<div class="form-group last">
						<div class="col-md-9">
							<select name="country" class="multi-select" multiple="" id="my_multi_select3" >
							<?php if($dpids):?>
			            	<?php foreach($dpids as $dpid):?>
								<option value="<?php echo $dpid['dpid'];?>"><?php echo $dpid['dpid']%1000;?><?php echo $dpid['company_name']?></option>
							<?php endforeach;?>
					    	<?php endif;?>
							</select>
						</div>
					</div>
         	        <div>
         		        <button id="printall" type="button" class="btn blue">确认</button>
         	        </div>
		        </div>
	        </div>
		</div>
	</div>

	
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
	            
           };
        //App.init();
	    FormComponents.init();
		});
        var bgin_instance = mobiscroll.time('#day_begin', {
            theme: 'mobiscroll',
            lang: 'zh',
            display: 'center',
            headerText: false,
            maxWidth: 90
        });
        
         var end_instace = mobiscroll.time('#day_end', {
            theme: 'mobiscroll',
            lang: 'zh',
            display: 'center',
            headerText: false,
            maxWidth: 90
        });
		$("#dpidchoose").on('click',function() {
			layer_index_printreportlist=layer.open({
	            type: 1,
	            shade: false,
	            title: false, //不显示标题
	            area: ['60%', '60%'],
	            content: $('#printRsultListdetail'),//$('#productInfo'), //捕获的元素
	            cancel: function(index){
	                layer.close(index);
	                layer_index_printreportlist=0;
	            }
	        });
		});
		$('#checktime').on('change',function(){
			if($('.times').hasClass('hide')){
				$('.times').removeClass('hide');
				}else{
					$('.times').addClass('hide');
					}
		});
		$('#selectCategory').on('change',function(){
			var cate = $('#selectCategory').val();
			//layer.msg(cate);
			$(".proname").addClass('hide');
			var s = "#pdname option[cate='"+cate+"']";
			$(s).removeClass('hide');
			if(cate == 0){
				$(".proname").removeClass('hide');
				}
		});
		   $('#btn_time_query').click(function() {
			   var ordertype = $('#ordertype').val();
			   var begin_time = $('#begin_time').val();
			   var end_time = $('#end_time').val();
			   var day_begin = $('#day_begin').val();
			   var day_end = $('#day_end').val();
			   var text = $('#text').val();
			   var setid = $('#setid').val();
			   var cid = $('#selectCategory').val();
			   var pdname = $('#pdname').val();
			   var str = $('#dpids').val();
			   if($('#checktime').attr('checked')){
				   var cks = 1;
				   }else{var cks =0;
				   }
			   
			   location.href="<?php echo $this->createUrl('statements/productdetailReport' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time +"/day_begin/"+day_begin+"/day_end/"+day_end+"/text/"+text+"/ordertype/"+ordertype+"/setid/"+setid+"/cid/"+cid+"/pdname/"+pdname+"/str/"+str+"/cks/"+cks;
			  
	        });
	        $('#printall').on('click',function(){
		        var dpids = '';
	        	$('.ms-elem-selectable.ms-selected').each(function(){
	                dpids += $(this).val()+',';
	                
	            });
	        	if(dpids!=''){
	            	dpids = dpids.substr(0,dpids.length-1);//除去最后一个“，”
	            }else{
					alert("请选择具体店铺,再确认！");return;
		            }
	        	$("#dpids").val(dpids);
	        	layer.closeAll();
		        });
			
			  $('#excel').click(function excel(){
				  var ordertype = $('#ordertype').val();
				   var begin_time = $('#begin_time').val();
				   var end_time = $('#end_time').val();
				   var day_begin = $('#day_begin').val();
				   var day_end = $('#day_end').val();
				   var text = $('#text').val();
				   var setid = $('#setid').val();
				   var cid = $('#selectCategory').val();
				   var pdname = $('#pdname').val();
				   var str = $('#dpids').val();
				   if($('#checktime').attr('checked')){
					   var cks = 1;
					   }else{var cks =0;
					   }
				   
			       if(confirm('确认导出并且下载Excel文件吗？')){
			    	   location.href="<?php echo $this->createUrl('statements/productdetailReportExport' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time +"/day_begin/"+day_begin+"/day_end/"+day_end+"/text/"+text+"/ordertype/"+ordertype+"/setid/"+setid+"/cid/"+cid+"/pdname/"+pdname+"/str/"+str+"/cks/"+cks;
						   }
			      
			   });

</script> 