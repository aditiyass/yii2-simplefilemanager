<?php

namespace aditiya\simplefilemanager\controllers;

use app\modules\simple_file_manager\models\Simplefilemanager;
use Exception;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\Url;

/**
 * DefaultController example of uploading file to server.
 */
class DefaultController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Upload file form
     *
     * @return string
     */
    public function actionIndex()
    {
        $module = Yii::$app->getModule('sfm');
        if(!$module->usedemo){
            throw new Exception('Not Allowed');
        }
        $model = new Simplefilemanager();

        if ($this->request->isPost) {
            $model->load(Yii::$app->request->post());
            // $model->rolelist = ['@'];
            // $model->category = 'screenshoot';
            // $model->description = 'some description';
            $key = $model->uploadByInstance($model,'file');
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if($key){
                return [
                    'url' => $model->getFileUrl(),
                    'data' => $model->toArray(),
                ];
            }
            return ['warning'=>'something wrong'];
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
}
