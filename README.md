Simple file manager to manage file data without db and little config
====================================================================
Simple file manager to manage file data without db and little config. using uuid as key to acccessing the file.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist aditiya/yii2-simplefilemanager "*"
```

or add

```
"aditiya/yii2-simplefilemanager": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply add to module configuration :

```php
    'modules' => [
        ...
        'sfm' => [
            'class' => 'aditiya\simplefilemanager\SimpleFileManager',
            'usedemo' => true,
            // 'uploadfilepath' => '@app/uploads/files',
            // 'metadatapath' => '@app/uploads/files/fileinfo',
            // 'defaultUrl' => '/sfm/file/get',
        ],
        ...
    ]
```