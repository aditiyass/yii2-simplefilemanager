<?php

namespace aditiya\simplefilemanager\controllers;

use app\modules\simple_file_manager\models\Simplefilemanager;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FileController for default file access.
 */
class FileController extends Controller
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
     * Get your file.
     *
     * @return mixed file
     * @throws NotFoundHttpException if file not found or not allowed to access.
     */
    public function actionIndex($id)
    {
        $response = Yii::$app->response;
        $sfm = new Simplefilemanager();
        if($sfm->getMetaData($id) != false){
            return $response->sendFile($sfm->fullFilePath(),$sfm->name,['mimeType'=>$sfm->mimetype,'inline'=>true]);
        }
        throw new NotFoundHttpException('file not found');
    }

    public function actionGet($id){
        return $this->actionIndex($id);
    }
}
