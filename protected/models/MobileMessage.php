<?php

/**
 * This is the model class for table "nb_member_wxcard_style".
 *
 * The followings are the available columns in table 'nb_member_wxcard_style':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $bg_img
 * @property string $style_cardnum_style
 * @property string $delete_flag
 * @property string $is_sync
 */
class MobileMessage extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_mobile_message';
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'lid' => 'lid',
			'dpid' => 'dpid',
			'create_at' => 'Create At',
			'update_at' => 'Update At',
			'type' => '类型',
			'mobile' => '手机号',
                        'code' => '验证码',
			'status' => '状态',
			'is_sync' => 'Is Sync',
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MemberWxcardStyle the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}


