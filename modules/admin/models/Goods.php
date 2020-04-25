<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "goods".
 *
 * @property int $id
 * @property int $idCategory
 * @property string $name
 * @property string $photo
 * @property int $price
 * @property int $idProperty
 * @property string $valueProperty
 *
 * @property Category $category
 * @property Property $property
 * @property Category[] $ids
 */
class Goods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idCategory', 'price', 'idProperty'], 'integer'],
            [['name', 'photo', 'valueProperty'], 'string', 'max' => 255],
            [['idCategory'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['idCategory' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idCategory' => 'Id Category',
            'name' => 'Name',
            'photo' => 'Photo',
            'price' => 'Price',
            'idProperty' => 'Id Property',
            'valueProperty' => 'Value Property',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'idCategory']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProperty()
    {
        return $this->hasOne(Property::className(), ['id' => 'idProperty']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIds()
    {
        return $this->hasMany(Category::className(), ['idProperty' => 'id'])->viaTable('property', ['id' => 'idProperty']);
    }
}
