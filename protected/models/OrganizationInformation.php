<?php

/**
 * This is the model class for table "nb_organization_information".
 *
 * The followings are the available columns in table 'nb_organization_information':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $classification_id
 * @property string $organization_code
 * @property string $organization_name
 * @property string $contact_name
 * @property string $contact_tel
 * @property string $contact_fax
 * @property string $post_code
 * @property string $email
 * @property string $bank
 * @property string $bank_account
 * @property string $tax_account
 * @property string $address
 * @property string $remark
 * @property integer $delete_flag
 * @property string $is_sync
 */
class OrganizationInformation extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_organization_information';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('update_at, organization_code, organization_name, contact_name, contact_tel, address,', 'required'),
			array('delete_flag', 'numerical', 'integerOnly'=>true),
			array('lid, dpid, classification_id', 'length', 'max'=>10),
			array('organization_code, organization_name, contact_name, email, address, remark', 'length', 'max'=>255),
			array('contact_tel, contact_fax, post_code, bank', 'length', 'max'=>20),
			array('bank_account, tax_account', 'length', 'max'=>64),
			array('is_sync', 'length', 'max'=>50),
			array('create_at', 'safe'),
			array('classification_id','compare','compareValue'=>'0','operator'=>'>','message'=>yii::t('app','必须选择组织类别')),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, classification_id, organization_code, organization_name, contact_name, contact_tel, contact_fax, post_code, email, bank, bank_account, tax_account, address, remark, delete_flag, is_sync', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		'company' => array(self::BELONGS_TO , 'Company' , 'dpid'),
		'orgclass' => array(self::BELONGS_TO , 'OrganizationClassification' ,'','on'=> 't.classification_id=orgclass.lid and orgclass.dpid=t.dpid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'lid' => '自身id，统一dpid下递增',
			'dpid' => '店铺id',
			'create_at' => 'Create At',
			'update_at' => '更新时间',
			'classification_id' => '组织类别',
			'organization_code' => '组织编号',
			'organization_name' => '组织名称',
			'contact_name' => '联系人',
			'contact_tel' => '联系电话',
			'contact_fax' => '传真',
			'post_code' => '邮编',
			'email' => '电子邮箱',
			'bank' => '开户银行',
			'bank_account' => '开会账号',
			'tax_account' => '纳税账号',
			'address' => '地址',
			'remark' => '备注',
			'delete_flag' => '删除 0未删除 1删除',
			'is_sync' => 'Is Sync',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('lid',$this->lid,true);
		$criteria->compare('dpid',$this->dpid,true);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('update_at',$this->update_at,true);
		$criteria->compare('classification_id',$this->classification_id,true);
		$criteria->compare('organization_code',$this->organization_code,true);
		$criteria->compare('organization_name',$this->organization_name,true);
		$criteria->compare('contact_name',$this->contact_name,true);
		$criteria->compare('contact_tel',$this->contact_tel,true);
		$criteria->compare('contact_fax',$this->contact_fax,true);
		$criteria->compare('post_code',$this->post_code,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('bank',$this->bank,true);
		$criteria->compare('bank_account',$this->bank_account,true);
		$criteria->compare('tax_account',$this->tax_account,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('delete_flag',$this->delete_flag);
		$criteria->compare('is_sync',$this->is_sync,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OrganizationInformation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
