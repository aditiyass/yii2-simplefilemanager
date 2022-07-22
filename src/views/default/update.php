<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\easy_file_manager\models\Easyfilemanager */

$this->title = Yii::t('easy_file_manager', 'Update Easyfilemanager: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('easy_file_manager', 'Easyfilemanagers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'key' => $model->key]];
$this->params['breadcrumbs'][] = Yii::t('easy_file_manager', 'Update');
?>
<div class="easyfilemanager-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
