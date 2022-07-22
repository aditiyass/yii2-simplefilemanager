<?php

use app\modules\easy_file_manager\models\Easyfilemanager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\easy_file_manager\models\EasyfilemanagerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('easy_file_manager', 'Easyfilemanagers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="easyfilemanager-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('easy_file_manager', 'Create Easyfilemanager'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'key',
            'name',
            'extension',
            'category',
            'description:ntext',
            //'roles:ntext',
            //'created_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Easyfilemanager $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'key' => $model->key]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
