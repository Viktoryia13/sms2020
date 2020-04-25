<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "modelPhone".
 *
 * @property int $id
 * @property string $model
 * @property int $idGoods
 *
 * @property Goods $goods
 */
class ModelPhone extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'modelPhone';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idGoods'], 'integer'],
            [['model'], 'string', 'max' => 255],
            [['idGoods'], 'exist', 'skipOnError' => true, 'targetClass' => Goods::className(), 'targetAttribute' => ['idGoods' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model' => 'Model',
            'idGoods' => 'Id Goods',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['id' => 'idGoods']);
    }
}
