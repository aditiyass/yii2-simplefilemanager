<?php

namespace aditiya\simplefilemanager\simple_file_manager;

/**
 * simple_file_manager module definition class
 */
class SimpleFileManager extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'aditiya\simplefilemanager\simple_file_manager\controllers';
    public $uploadfilepath = '@app/uploads/sfm';
    public $metadatapath = '@app/uploads/sfm/fileinfo';
    public $usedemo = false;
    public $defaultUrl = '/sfm/file/get';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
