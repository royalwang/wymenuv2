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
 <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','外卖订单'),'url'=>$this->createUrl('waimai/list' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','真实手机号查询'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('waimai/list' , array('companyId' => $this->companyId,'type'=>0)))));?>
<div class="row">   
    <div class="col-md-12">
  <div class="row">
        <div class="col-md-12 col-sm-12">
           <?php $form=$this->beginWidget('CActiveForm', array(
                    'id'=>'Promote',
                    'clientOptions'=>array(
                            'validateOnSubmit'=>true,
                    ),
                    'htmlOptions'=>array(
                            'class'=>'form-inline'
                    ),
            )); ?>
            <div class="input-group" style="float:left;width:700px;margin-bottom:15px;">　
                  <a class="btn green" href="<?php echo $this->createUrl('waimai/real',array('companyId'=>$this->companyId,'type'=>1));?>">
                         <i class="fa fa-search">查询 &nbsp;</i>
                  </a>
              </div>
             <?php $this->endWidget(); ?>
         </div>
    <div class="portlet purple box">
      <div class="portlet-title">
             <div class="caption"><i class="fa fa-group"></i>订单的真实号码信息</div>
             <div class="actions"></div>
        </div>
        <div class="portlet-body">
              <?php echo $re;?>
        </div>
        </div> 
    </div>
  </div>
</div>