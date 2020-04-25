<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin([
        'action' => ['send'],
    ]); ?>
    <?= $form->field($form_model, 'message')->textInput(['value' => '']) ?>
    <?= $form->field($form_model, 'phone')->textInput(['placeholder' => '375296937575']) ?>
   <?= Html::submitButton('Send', ['class' => 'btn btn-success']) ?> 
	
<?php ActiveForm::end() ?>

<br>

<?php $form = ActiveForm::begin([
    'action' => ['send'],
]); ?>
<?= $form->field($form_model, 'message')->textInput(['value' => 'Здравствуйте, трек код вашей посылки: ,ссылка для отслеживания:https://webservices.belpost.by/searchRu/']) ?>
<?= $form->field($form_model, 'phone')->textInput(['placeholder' => '375296937575']) ?>
<?= Html::submitButton('Send', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end() ?>
<br>

<?php $form = ActiveForm::begin([
    'action' => ['send'],
]); ?>
<?= $form->field($form_model, 'message')->textInput(['value' => 'пр. Независимости 85б офис Roger.by, график пн-пт 9-19, сб-вс 10-16']) ?>
<?= $form->field($form_model, 'phone')->textInput(['placeholder' => '375296937575']) ?>
<?= Html::submitButton('Send', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end() ?>
<br>
<br>
<?= Html::a('Рассылка смс', ['create'], ['class' => 'btn btn-success']) ?>
