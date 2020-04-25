<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\ModelPhone */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="model-phone-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'model')->textInput(['maxlength' => true]) ?>

    <? foreach ($category as $cat) {?>

        <?= $form->field($cat, 'model')->textInput(['maxlength' => true]) ?>

    <?}?>
    <?= $form->field($model, 'model')->textInput(['maxlength' => true]) ?>
    <?= $form->field($goods, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'idGoods')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
