<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\easy_file_manager\models\EasyfilemanagerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="easyfilemanager-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'key') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'extension') ?>

    <?= $form->field($model, 'category') ?>

    <?= $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'roles') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('easy_file_manager', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('easy_file_manager', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
