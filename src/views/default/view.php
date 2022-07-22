<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\easy_file_manager\models\Easyfilemanager */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('easy_file_manager', 'Easyfilemanagers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="easyfilemanager-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('easy_file_manager', 'Update'), ['update', 'key' => $model->key], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('easy_file_manager', 'Delete'), ['delete', 'key' => $model->key], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('easy_file_manager', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'key',
            'name',
            'extension',
            'category',
            'description:ntext',
            'roles:ntext',
            'created_at',
        ],
    ]) ?>

</div>
