<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property string $goodsName
 * @property string $goodsUrl
 * @property int $goodsPrice
 * @property int $phoneClient
 * @property string $comment
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goodsPrice', 'phoneClient'], 'integer'],
            [['goodsName', 'goodsUrl', 'comment'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goodsName' => 'Goods Name',
            'goodsUrl' => 'Goods Url',
            'goodsPrice' => 'Goods Price',
            'phoneClient' => 'Phone Client',
            'comment' => 'Comment',
        ];
    }
}
