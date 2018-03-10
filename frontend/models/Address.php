<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property int $id
 * @property string $name 收货人
 * @property int $member_id 用户id
 * @property string $cmbProvince 省
 * @property string $cmbCity 市
 * @property string $cmbArea 区
 * @property string $address 详细地址
 * @property string $tel 电话
 * @property int $status 状态（1默认，0不默认）
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'member_id', 'cmbProvince', 'cmbCity', 'cmbArea', 'address', 'tel'], 'required'],
            [['member_id', ], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['cmbProvince', 'cmbCity', 'cmbArea'], 'string', 'max' => 25],
            [['address'], 'string', 'max' => 255],
            [['tel'], 'string', 'max' => 11],
            ['status','safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '收货人',
            'member_id' => '用户id',
            'cmbProvince' => '省',
            'cmbCity' => '市',
            'cmbArea' => '区',
            'address' => '详细地址',
            'tel' => '电话',
            'status' => '状态（1默认，0不默认）',
        ];
    }
}
