			<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'siteNo',
				'enableAjaxValidation'=>true,
				'enableClientValidation'=>true,
				'clientOptions'=>array(
					'validateOnSubmit'=>false,
				),
				'htmlOptions'=>array(
					'class'=>'form-horizontal'
				),
			)); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title">请点击相应操作</h4>
			</div>
			<div class="modal-body">
				<?php if($status=='1') :?>
                                <button type="button" sid="<?php echo $sid; ?>" istemp="<?php echo $istemp; ?>" class="btn grey orderaction">点 单</button>
                                <div class="pull-right">
                                    <button type="button" sid="<?php echo $sid; ?>" istemp="<?php echo $istemp; ?>" class="btn red-stripe unionsite">并  台</button>
                                    <button type="button" sid="<?php echo $sid; ?>" istemp="<?php echo $istemp; ?>" class="btn red-stripe closesite">撤  台</button>
                                    <button type="button" sid="<?php echo $sid; ?>" istemp="<?php echo $istemp; ?>" class="btn red-stripe switchsite">换  台</button>
                                </div>
                                <?php elseif($status=='2') :?>
                                    <button type="button" sid="<?php echo $sid; ?>" istemp="<?php echo $istemp; ?>" class="btn green" id="btn-print-btn">打印清单</button>
                                    <button type="button" sid="<?php echo $sid; ?>" istemp="<?php echo $istemp; ?>" class="btn yellow orderaction">订单详情</button>
                                <div class="pull-right">
                                    <button type="button" sid="<?php echo $sid; ?>" istemp="<?php echo $istemp; ?>" class="btn red-stripe unionsite">并  台</button>
                                    <button type="button" sid="<?php echo $sid; ?>" istemp="<?php echo $istemp; ?>" class="btn red-stripe closesite">撤  台</button>
                                    <button type="button" sid="<?php echo $sid; ?>" istemp="<?php echo $istemp; ?>" class="btn red-stripe switchsite">换  台</button>
                                </div>
                                <?php elseif($status=='3') :?>
                                    <button type="button" sid="<?php echo $sid; ?>" istemp="<?php echo $istemp; ?>" class="btn grey" id="btn-account-btn">结 单</button>
                                    <button type="button" sid="<?php echo $sid; ?>" istemp="<?php echo $istemp; ?>" class="btn yellow orderaction">订单详情</button>
                                <div class="pull-right">
                                    <button type="button" sid="<?php echo $sid; ?>" istemp="<?php echo $istemp; ?>" class="btn red-stripe switchsite">换  台</button>
                                </div>
                                <?php else :?>
                                <label class="col-md-3 control-label">请输入人数：</label>
                                <div class="col-md-4">
                                    <input class="form-control" placeholder="请输入人数" name="siteNumber" id="site_number" type="text" maxlength="2" value="1">
                                </div>
                                <div class="pull-right">
                                    <button id="site_open" type="button" istemp="<?php echo $istemp; ?>" sid="<?php echo $sid; ?>" class="btn green">开 台</button>
                                    <button id="open_order" type="button" istemp="<?php echo $istemp; ?>" sid="<?php echo $sid; ?>" class="btn green-stripe">开台并点单</button>
                                </div>
                                <?php endif; ?>
				<!--订单明细中 退菜、勾挑、优惠、重新厨打///厨打、结单、整单优惠-->
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn default">取 消</button>
				<!--<input type="submit" class="btn green" id="create_btn" value="确 定">-->
			</div>
                        
			<?php $this->endWidget(); ?>
                        <script type="text/javascript">
                            $(document).ready(function() {
                                //alert($('#site_number')[0]);
                                var sno=$("#site_number");
                                if(sno.length > 0)
                                {
                                    sno[0].focus();
                                }
                            });
                            
                            var opensitef=function(siteNumber,sid,istemp){
                                
                            }
                            
                           $('#site_open').click(function(){
                               var siteNumber=$('#site_number').val();                               
                               var sid = $(this).attr('sid');
                               var istemp = $(this).attr('istemp');
                               if(!isNaN(siteNumber) && siteNumber>0 && siteNumber < 99)
                               {
                                   //alert(!isNaN(siteNumber));
                                    $.ajax({
					'type':'POST',
					'dataType':'json',
					'data':{"sid":sid,"siteNumber":siteNumber,"companyId":'<?php echo $this->companyId; ?>',"istemp":'<?php echo $istemp; ?>'},
					'url':'<?php echo $this->createUrl('defaultSite/opensite',array());?>',
					'success':function(data){
						if(data.status == 0) {
							alert(data.message);
						} else {
							alert(data.message);
                                                        $('#portlet-button').modal('hide');
							$('#tabsiteindex').load('<?php echo $this->createUrl('defaultSite/showSite',array('companyId'=>$this->companyId,'typeId'=>$typeId));?>');
                                                        
						}
					}
                                    });
                                    return false;
                               }else{
                                   alert("输入合法人数");
                               }                               
                           });
                           
                           $('#open_order').click(function(){
                               var siteNumber=$('#site_number').val();                               
                               var sid = $(this).attr('sid');
                               var istemp = $(this).attr('istemp');
                               if(!isNaN(siteNumber) && siteNumber>0 && siteNumber < 99)
                               {
                                   //alert(!isNaN(siteNumber));
                                    $.ajax({
					'type':'POST',
					'dataType':'json',
					'data':{"sid":sid,"siteNumber":siteNumber,"companyId":'<?php echo $this->companyId; ?>',"istemp":'<?php echo $istemp; ?>'},
					'url':'<?php echo $this->createUrl('defaultSite/opensite',array());?>',
					'success':function(data){
						if(data.status == 0) {
							alert(data.message);
						} else {
							alert(data.message);
							location.href='<?php echo $this->createUrl('defaultOrder/order',array('companyId'=>$this->companyId,'typeId'=>$typeId));?>'+'/sid/'+data.siteid+'/istemp/'+istemp;
						}
					}
                                    });
                                    return false;
                               }else{
                                   alert("输入合法人数");
                               }                                                              
                           });
                           
                           $('.closesite').on('click',function(){
                               var sid = $(this).attr('sid');
                               $.ajax({
                                    'type':'POST',
                                    'dataType':'json',
                                    'data':{"sid":sid,"companyId":'<?php echo $this->companyId; ?>',"istemp":'<?php echo $istemp; ?>'},
                                    'url':'<?php echo $this->createUrl('defaultSite/closesite',array());?>',
                                    'success':function(data){
                                            if(data.status == 0) {
                                                    alert(data.message);
                                            } else {
                                                    alert(data.message);
                                                    $('#tabsiteindex').load('<?php echo $this->createUrl('defaultSite/showSite',array('companyId'=>$this->companyId,'typeId'=>$typeId));?>');
                                                    $('#portlet-button').modal('hide');
                                            }
                                    }
                                });
                                return false;                               
                           });
                           
                           $('.switchsite').on('click',function(){
                               //var sid = $(this).attr('sid');
                               var statu = confirm("确定换台吗？");
                                if(!statu){
                                    return false;
                                }  
                                $('#tabsiteindex').load('<?php echo $this->createUrl('defaultSite/showSite',array('companyId'=>$this->companyId,'typeId'=>$typeId,'op'=>'switch','sistemp'=>$istemp,'ssid'=>$sid,'stypeId'=>$typeId));?>');
                                $('#portlet-button').modal('hide');
                           });                           
                           
                           $('.unionsite').on('click',function(){
                               //var sid = $(this).attr('sid');
                               var statu = confirm("确定并台吗？");
                                if(!statu){
                                    return false;
                                }  
                                $('#tabsiteindex').load('<?php echo $this->createUrl('defaultSite/showSite',array('companyId'=>$this->companyId,'typeId'=>$typeId,'op'=>'union','sistemp'=>$istemp,'ssid'=>$sid,'stypeId'=>$typeId));?>');
                                $('#portlet-button').modal('hide');
                           });
                           
                           $('.orderaction').on('click',function(){
                               var sid = $(this).attr('sid');
                               var istemp = $(this).attr('istemp');
                               //alert(istemp);
                               location.href='<?php echo $this->createUrl('defaultOrder/order',array('companyId'=>$this->companyId,'typeId'=>$typeId));?>'+'/sid/'+sid+'/istemp/'+istemp;
                           });
                           
                           $('#btn-print-btn').click(function(){
                                var sid = $(this).attr('sid');
                                var istemp = $(this).attr('istemp');
                                $.get('<?php echo $this->createUrl('defaultOrder/printList',array('companyId'=>$this->companyId));?>'+'/sid/'+sid+'/istemp/'+istemp,function(data){
                                        if(data.status) {
                                                alert('操作成功');
                                                //alert(data.msg);
                                        } else {
                                                alert(data.msg);
                                        }
                                },'json');
                            });
                            
                            $('#btn-account-btn').click(function(){
                                var sid = $(this).attr('sid');
                                var istemp = $(this).attr('istemp');
                                var $modalconfig = $('#portlet-button');
                                //$modalconfig.modal('hide');
                                $modalconfig.find('.modal-content').load('<?php echo $this->createUrl('defaultOrder/account',array('companyId'=>$this->companyId,'typeId'=>$typeId));?>'+'/sid/'+sid+'/istemp/'+istemp, '', function(){
                                  
                                            $modalconfig.modal();                                            
                                         }); 
                                          
                            });
                           
                        </script>