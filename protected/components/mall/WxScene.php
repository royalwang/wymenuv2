<?php 
/**
 * 
 * 
 * 微信端是否扫描二维码类
 * 
 */
class WxScene
{
	public static function get($dpid,$scenelid,$scenedpid,$type){
		$sql = 'select * from nb_scene where dpid='.$dpid.' and scene_lid='.$scenelid.' and scene_dpid='.$scenedpid.' and type='.$type;
		$scene = Yii::app()->db->createCommand($sql)->queryRow();
		return $scene;
	}
	public static function insert($data){
		Yii::app()->db->createCommand()->insert('nb_scene',$data);
	}
	public static function update($lid,$dpid,$expireTime){
		$sql = 'update nb_scene set expire_time='.$expireTime.' where lid='.$lid.' and dpid='.$dpid;
		$result = Yii::app()->db->createCommand($sql)->execute();
		return $result;
	}
}