<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property int $numberOrder
 * @property string $delivery
 * @property int $data
 * @property string $description
 * @property int $phone
 * @property string $trek
 * @property string $message
 * @property string $belpost
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['numberOrder', 'data', 'phone'], 'integer'],
            [['delivery', 'description', 'trek', 'message', 'belpost'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'numberOrder' => 'Number Order',
            'delivery' => 'Delivery',
            'data' => 'Data',
            'description' => 'Description',
            'phone' => 'Phone',
            'trek' => 'Trek',
            'message' => 'Message',
            'belpost' => 'Belpost',
        ];
    }
}
