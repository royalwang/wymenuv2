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
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'product-form',
				'action' => $this->createUrl('/eleme/createitem' , array('companyId' => $this->companyId)),
				'errorMessageCssClass' => 'help-block',
				'htmlOptions' => array(
					'class' => 'form-horizontal',
					'enctype' => 'multipart/form-data'
				),
		)); ?>
	<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
				<div class="portlet-body" id="table-manage">
					<h5 style="color: red;">*更新菜品必须要在基础设置的菜品录入先修改菜品再更新饿了么上的菜品</h5>
						<table class="table table-striped table-bordered table-hover" id="sample_1">
							<thead>
								<tr>
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
	                            <th><?php echo yii::t('app','菜品ID');?></th>
	                            <th><?php echo yii::t('app','菜品名称');?></th> 
	                            <th><?php echo yii::t('app','操作');?></th>   
								</tr>
							</thead>
							<tbody>
								<?php foreach($model as $value):?>
									<tr class="odd gradeX">
									<td><input type="checkbox" class="checkboxes" value="<?php echo $value['lid'];?>" name="ids[]" /></td>
									<td><?php echo $value['lid'];?></td>
	                                <td><?php echo $value['product_name'];?></td>
	                                <td><a href="<?php echo $this->createUrl('/eleme/updateproduct',array('product_id'=>$value['lid']));?>">更新</a>　　　　<a href="<?php echo $this->createUrl('/eleme/deleteproduct',array('product_id'=>$value['lid']));?>">删除</a></td>
								</tr>
							<?php endforeach;?>
							</tbody>
							<tr style="text-align: center;">
								<td colspan="4" style="width: 300px;"><input type="submit" value="菜品提交">　　　　<a href="<?php echo $this->createUrl('eleme/cpgx',array('companyId'=>$this->companyId));?>">批量更新</a>　　　　<a href="<?php echo $this->createUrl('eleme/sccp',array('companyId'=>$this->companyId));?>">批量删除</a></td>
							</tr>
						</table>
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
		<?php $this->endWidget(); ?>
	</div>
	<!-- END PAGE CONTENT-->	