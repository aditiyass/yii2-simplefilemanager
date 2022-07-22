<?php

namespace aditiya\simplefilemanager\models;

use Exception;
use thamtech\uuid\helpers\UuidHelper;
use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\UploadedFile;

/**
 * This is the model class of Simplefilemanager.
 *
 * @property string $key file key, to access the file and it's data
 * @property string $name file name
 * @property string $extension file extension
 * @property string $mimetype it's mime type
 * @property string|null $category file category. please assign manualy before upload
 * @property string|null $description file description. please assign manualy before upload
 * @property array|null $rolelist array of user rbac permission. please assign manualy before upload
 * @property int|null $size file size on bytes
 * @property string|null $file placeholder used to upload data.
 * @property string $modulename name of module used to initial data and setting
 * @property string $baseurl base url without the key to get it. use pretty url.
 * @property string|null $created_at record of when data uploaded
 */
class Simplefilemanager extends Model
{
    use yii\base\ArrayableTrait;
    
    public $key; //diisi waktu upload
    public $name; //diisi waktu upload
    public $extension; //diisi waktu upload
    public $category = "*"; //harus diisi manual
    public $description = ""; //harus diisi manual
    public $mimetype; //harus diisi manual
    public $rolelist = ['@','?']; //harus diisi manual
    public $size; //diisi waktu upload
    public $created_at; //diiisi waktu upload
    public $file; //placeholder kalau mau masukkan file sendiri
    public $modulename = 'sfm'; //placeholder kalau mau masukkan file sendiri
    public $baseurl; //placeholder kalau mau masukkan file sendiri

    //private variables
    private $folderpath; //init saat awal
    private $metadatapath; //init saat awal

    
    public function init()
    {
        $module = $this->getModule();
        $this->folderpath = $module->uploadfilepath;
        $this->metadatapath = $module->metadatapath;
        $this->baseurl = $module->defaultUrl;
    }

    /**
     * Get list of categories or keys with category.
     *
     * @param string|null $category Category name. null to get category list
     * @return boolean|array
     */
    public function getCategories($category = null)
    {
        if(!file_exists($this->fullMetaDataPath().'/categories.json')){
            $categories = fopen($this->fullMetaDataPath().'/categories.json','w+');
            fwrite($categories,Json::encode(['*'=>[]]));
            fclose($categories);
        }
        $categories = file_get_contents($this->fullMetaDataPath().'/categories.json');
        if($category){
            $decoded = json_decode($categories);
            if(isset($decoded)){
                return $decoded[$category];
            }
            else return false;
        }
        return (array)json_decode($categories);
    }

    /**
     * Get list of keys.
     *
     * @param string|null $category Category name. null to get category list
     * @return boolean|array
     */
    public function getKeyList()
    {
        if(!file_exists($this->fullMetaDataPath().'/keylist.json')){
            $this->initData();
        }
        $keylist = file_get_contents($this->fullMetaDataPath().'/keylist.json');
        return (array)json_decode($keylist);
    }

    /**
     * url to request file.
     *
     * @return string|boolean
     */
    public function getFileUrl($param = 'id')
    {
        return Url::to([$this->baseurl,$param=>$this->key],true);
    }

    /**
     * Get path where file is or will be saved.
     *
     * @return string|boolean
     */
    public function fullFolderPath()
    {
        return Yii::getAlias($this->folderpath);
    }

    /**
     * Full file path.
     *
     * @return string|boolean
     */
    public function fullFilePath()
    {
        return $this->fullFolderPath().'/'.$this->key;
    }

    /**
     * Folder path where meta data is stored.
     *
     * @return string|boolean
     */
    public function fullMetaDataPath()
    {
        return Yii::getAlias($this->metadatapath);
    }

    /**
     * The file should be uploaded using [[\yii\widgets\ActiveField::fileInput()]].
     * 
     * @param \yii\base\Model $model — the data model
     * @param string $attribute 
     * the attribute name. The attribute name may contain array indexes. For example, '[1]file' for tabular file uploading; and 'file[1]' for an element in a file array.
     * @return string uploaded file key
     * @throws Exception if something wrong happened
     */
    public function uploadByInstance($model,$attribute)
    {
        $this->initDir();
        $this->initData();
        $uploadedfile = UploadedFile::getInstance($model,$attribute);
        if($uploadedfile){
            if($uploadedfile->hasError){
                throw new Exception($this->phpFileUploadErrors($uploadedfile->error));
            }
            $this->key = UuidHelper::uuid();
            $this->size = $uploadedfile->size;
            $this->name = $uploadedfile->name;
            $this->extension = $uploadedfile->extension;
            $this->created_at = date("Y-m-d H:i:s");
            $is_uploaded = $uploadedfile->saveAs($this->fullFilePath());
            if($is_uploaded){
                $this->mimetype = FileHelper::getMimeType($this->fullFilePath());
                $this->setCategories($this->category,$this->key);
                $this->addToKeyList($this->key);
                $this->saveMetaData();
                return $this->key;
            }
        }
        throw new Exception('File not uploaded');
    }

    /**
     * Get and load meta data from key.
     * 
     * @param string $key the key used to get data.
     * @return false|array return all data as array or false if failed.
     */
    public function getMetaData($key)
    {
        if(!file_exists($this->fullMetaDataPath().'/'.$key)){
            return false;
        }
        $file = file_get_contents($this->fullMetaDataPath().'/'.$key);
        if($file){
            $decoded = json_decode($file);
            if(isset($decoded)){
                $this->rolelist = $decoded->rolelist; //harus diisi manual
                // check if user can get data
                if(!$this->checkCredential()){
                    return false;
                }
                $this->key = $decoded->key; //diisi waktu upload
                $this->name = $decoded->name; //diisi waktu upload
                $this->extension = $decoded->extension; //diisi waktu upload
                $this->category = $decoded->category; //harus diisi manual
                $this->description = $decoded->description; //harus diisi manual
                $this->mimetype = $decoded->mimetype; //harus diisi manual
                $this->size = $decoded->size; //diisi waktu upload
                $this->created_at = $decoded->created_at; //diiisi waktu upload
                $this->file = $decoded->file;
                return (array)$decoded;
            }
        }
        return false;
    }

     /**
     * Check whether user can access file data or not.
     *
     * @param boolean $throwErrors Whether throw errors or not
     * @return boolean if permitted to access file data or not
     * @throws ForbiddenHttpException if not permitted and $throwErrors is true.
     */
    public function checkCredential($throwErrors = false)
    {
        $access = false;
        if(Yii::$app->user->isGuest){
            if(array_search('?',$this->rolelist) != false){
                $access = true;
            }
        }
        else{
            foreach ($this->rolelist as $role) {
                if(Yii::$app->user->can($role)){
                    $access = true;
                }
            }
        }
        if(!$access && $throwErrors){
            throw new ForbiddenHttpException("Access Denied. You don't have credential to access file data.");
        }
        return $access;
    }

    //private functions
    private function initDir()
    {
        if(!file_exists($this->fullFolderPath())){
            FileHelper::createDirectory($this->fullFolderPath());
        }
        if(!file_exists($this->fullMetaDataPath())){
            FileHelper::createDirectory($this->fullMetaDataPath());
        }
    }

    private function initData()
    {
        if(!file_exists($this->fullMetaDataPath().'/categories.json')){
            $categories = fopen($this->fullMetaDataPath().'/categories.json','w+');
            fwrite($categories,Json::encode(['*'=>[]]));
            fclose($categories);
        }
        if(!file_exists($this->fullMetaDataPath().'/keylist.json')){
            $keylist = fopen($this->fullMetaDataPath().'/keylist.json','w+');
            fwrite($keylist,Json::encode([]));
            fclose($keylist);
        }
    }

    private function saveMetaData()
    {
        $thisarray = $this->toArray();
        $filearray = fopen($this->fullMetaDataPath().'/'.$thisarray['key'],'w+');
        $is_saved = fwrite($filearray,Json::encode($thisarray));
        fclose($filearray);
        if($is_saved != false){
            return true;
        }
        else{
            return false;
        }
    }

    private function setCategories($category,$key)
    {
        if(($category == '') || ($category == false)){
            $category = "*";
        }
        $categories = $this->getCategories();
        if(!isset($categories[$category])){
            $categories[$category] = [$key];
        }
        else{
            $categories[$category][] = $key;
        }

        $categoriesfile = fopen($this->fullMetaDataPath().'/categories.json','w+');
        $is_writen = fwrite($categoriesfile,Json::encode($categories));
        fclose($categoriesfile);
        if($is_writen != false){
            return true;
        }
        return $is_writen;
    }

    private function addToKeyList($key)
    {
        if(!$key){
            return false;
        }
        $keylist = $this->getKeyList();
        array_push($keylist,$key);
        $keylistfile = fopen($this->fullMetaDataPath().'/keylist.json','w+');
        $is_writen = fwrite($keylistfile,Json::encode($keylist));
        fclose($keylistfile);
        if($is_writen != false){
            return true;
        }
        return $is_writen;
    }

    /**
     * @return Module
     */
    private function getModule()
    {
        return \Yii::$app->getModule($this->modulename);
    }
}
