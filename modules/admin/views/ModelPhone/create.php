<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\ModelPhone */

$this->title = 'Create Model Phone';
$this->params['breadcrumbs'][] = ['label' => 'Model Phones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="model-phone-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'goods' => $goods
    ]) ?>

</div>
