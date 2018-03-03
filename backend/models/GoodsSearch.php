<?php
namespace backend\models;
use yii\base\Model;

class GoodsSearch extends Model{
    public $g_name;
    public $g_sn;
    public $min_price;
    public $max_price;
    public function rules()
    {
        return [
            [['g_name'],'string'],
            [['g_sn','min_price','max_price'],'integer'],
        ];
    }
}
