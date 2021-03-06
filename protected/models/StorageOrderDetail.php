<?php

/**
 * This is the model class for table "nb_storage_order_detail".
 *
 * The followings are the available columns in table 'nb_storage_order_detail':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $material_id
 * @property string $price
 * @property string $stock
 * @property string $free_stock
 * @property string $is_sync
 */
class StorageOrderDetail extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_storage_order_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('update_at,', 'required'),
			array('delete_flag', 'numerical', 'integerOnly'=>true),
			array('lid, dpid, material_id, price, stock, free_stock', 'length', 'max'=>10),
			array('is_sync', 'length', 'max'=>50),
			array('create_at', 'safe'),
			array('mphs_code', 'length', 'max'=>12),
			array('stock_day', 'length', 'max'=>4),
			array('batch_code', 'length', 'max'=>20),
			array('material_id', 'compare', 'compareValue'=>'0','operator'=>'>','message'=>yii::t('app','请选择品项信息')),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, material_id, mphs_code, batch_code, stock_day, price, stock, free_stock, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
			'material_id' => '品项名称',
			'mphs_code' => '品项编码',
			'batch_code' => '批次号',
			'stock_day' => '库存天数',
			'price' => '入库进价',
			'stock' => '入库库存(含赠品)',
			'free_stock' => '赠品数',
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
		$criteria->compare('material_id',$this->material_id,true);
		$criteria->compare('mphs_code',$this->mphs_code,true);
		$criteria->compare('batch_code',$this->batch_code,true);
		$criteria->compare('stock_day',$this->stock_day,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('stock',$this->stock,true);
		$criteria->compare('free_stock',$this->free_stock,true);
		$criteria->compare('delete_flag',$this->delete_flag,true);
		$criteria->compare('is_sync',$this->is_sync,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StorageOrderDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
