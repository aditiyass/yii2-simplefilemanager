<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\easy_file_manager\models\Easyfilemanager */

$this->title = 'Upload File';
?>
<div class="uploadfile-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
