<?php
/*
 * Created on 2013-12-10
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class MaterialCategorySelecter extends CWidget {
	public $categoryId;
	public $companyId;
	public function init(){

	}
	public function run(){
		$selecter = '';
                
		$rootCategoties = Helper::getCategory($this->companyId);
            //var_dump($this->categoryId,$rootCategoties,$this->companyId);exit;
		if($this->categoryId!=0 && $category = MaterialCategory::model()->find('t.lid = :cid and t.dpid=:dpid',array(':cid'=>$this->categoryId,':dpid'=>$this->companyId))){
			//var_dump($category);exit;
                        $categoryTree = explode(',',$category['tree']);
                        echo $this->getSelecter($categoryTree);
		}else{
                   // var_dump($rootCategoties);exit;
			$selecter = '<select class="form-control category_selecter " tabindex="-1" name="category_id_selecter" >';
			$selecter .=yii::t('app', '<option value="0">--请选择--</option>');
			foreach($rootCategoties as $c1){
				$selecter .= '<option value="'.$c1['lid'].'">'.$c1['category_name'].'</option>';
			}
			$selecter .= '</select>';
		}
		echo $selecter;
	}
	
	public function getSelecter($categoryTree){
		$selecter = '';
		for($i=0, $count = count($categoryTree); $i<$count-1; $i++){
			//var_dump($categoryTree[$i]);
			$categoties = Helper::getCategory($this->companyId,$categoryTree[$i]);
			if($i == 0){
				$ms = '';
			}else{
				$ms = 'material_select';
			}
			$selecter .= '<select class="form-control category_selecter '.$ms.'" tabindex="-1" name="category_id_selecter" >';
			$selecter .= yii::t('app','<option value="0">--请选择--</option>');
			foreach($categoties as $c){
				$selecter .= '<option value="'.$c['lid'].'" '.(in_array($c['lid'],$categoryTree)?'selected':'').'>'.$c['category_name'].'</option>';
			}
			$selecter .= '</select>';
		}
		return $selecter;
	}
}
?>
