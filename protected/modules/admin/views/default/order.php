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
			
                        <!-- BEGIN PAGE CONTENT-->
			<div class="row">
                                <div class="col-md-4">
                                    <h3 class="page-title">收银台（"<?php switch($model->order_status) {case 1:{echo '未下单';break;} case 2:{echo '下单未支付';break;} case 3:{echo '已支付';break;} }?>"）</h3>
                                </div>
                                <div class="col-md-8">
                                    <h4>
                                       下单时间：<?php echo $model->create_at;?> 
                                       &nbsp;&nbsp;&nbsp;&nbsp; 应付金额（元）：<?php echo number_format($total['total'], 2);?>
                                       &nbsp;&nbsp;&nbsp;&nbsp; 实付金额（元）：<?php echo $model->reality_total;?>
                                    </h4>    
                                </div>
				<div class="col-md-12">
					<div class="portlet box blue">
						<div class="portlet-title">
							<div class="caption"><i class="fa fa-reorder"></i>
                                                        </div>
                                                        <div class="actions">
                                                            <a class="btn purple" id="btn_account"><i class="fa fa-pencil"></i> 结单</a>
                                                            <a id="kitchen-btn" class="btn purple"><i class="fa fa-cogs"></i> 下单&厨打</a>
                                                            <a id="print-btn" class="btn purple"><i class="fa fa-print"></i> 打印清单</a>
                                                            <a href="<?php echo $this->createUrl('default/producttaste' , array('companyId' => $this->companyId,'typeId'=>$typeId,'isall'=>'1'));?>" class="btn purple"><i class="fa fa-pencil"></i> 全单口味</a>
                                                            <a href="<?php echo $this->createUrl('default/index' , array('companyId' => $model->dpid,'typeId'=>$typeId));?>" class="btn red"><i class="fa fa-times"></i> 返回</a>
                                                        </div>
						</div>
						<div class="portlet-body form">
							<!-- BEGIN FORM-->
							<?php echo $this->renderPartial('_form', array('model'=>$model,'orderProducts' => $orderProducts,'productTotal' => $productTotal,'total' => $total,'typeId'=>$typeId)); ?>
							<!-- END FORM--> 
						</div>
					</div>
				</div>
			</div>
			<!-- END PAGE CONTENT-->    
		</div>
		<!-- END PAGE -->  
                
                    <script type="text/javascript">
                        $(document).ready(function(){
                                $('body').addClass('page-sidebar-closed');
                                
                        });
                         
                        $('#submit-btn').click(function(){
                                 bootbox.confirm('你确定要结单吗？', function(result) {
                                        if(result){
                                                $('#order-form').submit();
                                        }
                                 });
                        });
                        $('#print-btn').click(function(){
                                $.get('<?php echo $this->createUrl('default/printList',array('companyId'=>$this->companyId,'id'=>$model->lid));?>',function(data){
                                        if(data.status) {
                                                alert('操作成功');
                                                //alert(data.msg);
                                        } else {
                                                alert(data.msg);
                                        }
                                },'json');
                        });
                        $('#kitchen-btn').click(function(){
                                location.href="<?php echo $this->createUrl('default/printKitchen',array('companyId'=>$this->companyId,'typeId'=>$typeId,'id'=>$model->lid));?>";
                        });
                        
                    </script>