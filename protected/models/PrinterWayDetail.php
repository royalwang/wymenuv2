<?php

/**
 * This is the model class for table "nb_printer_way_detail".
 *
 * The followings are the available columns in table 'nb_printer_way_detail':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $print_way_id
 * @property string $floor_id
 * @property string $printer_id
 * @property integer $list_no
 * @property string $delete_flag
 * @property string $is_sync
 */
class PrinterWayDetail extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_printer_way_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lid', 'required'),
			array('list_no', 'numerical', 'integerOnly'=>true),
			array('lid, dpid, print_way_id, floor_id, printer_id', 'length', 'max'=>10),
			array('delete_flag', 'length', 'max'=>1),
				array('is_sync','length','max'=>50),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, print_way_id, floor_id, printer_id, list_no, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
                        'floor' => array(self::BELONGS_TO , 'Floor' , '' , 'on' => 't.floor_id=floor.lid and t.dpid=floor.dpid'),
			'printer' => array(self::BELONGS_TO , 'Printer' ,'','on' =>'t.printer_id=printer.lid and t.dpid=printer.dpid')
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
			'print_way_id' => yii::t('app','打印方式'),
			'floor_id' => yii::t('app','楼层/区域'),
			'printer_id' => yii::t('app','打印机'),
			'list_no' =>yii::t('app', '打印份数'),
			'delete_flag' => 'Delete Flag',
				'is_sync' => yii::t('app','是否同步'),
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
		$criteria->compare('print_way_id',$this->print_way_id,true);
		$criteria->compare('floor_id',$this->floor_id,true);
		$criteria->compare('printer_id',$this->printer_id,true);
		$criteria->compare('list_no',$this->list_no);
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
	 * @return PrinterWayDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
