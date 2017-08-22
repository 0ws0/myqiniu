<?php
namespace myqiniu;

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;

class qiniuUtil
{
    // 需要填写你的 Access Key 和 Secret Key
    private $accessKey;
    private $secretKey;

    private $bucketName;
    private $auth;

    /**
     * 初使化存储配置
     * qiniuUtil constructor.
     * @param $accessKey Access Key
     * @param $secretKey Secret Key
     * @param $bucketName 空间名称
     */
    function __construct($accessKey,$secretKey,$bucketName)
    {
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
        $this->bucketName = $bucketName;
        $this->auth = new Auth($this->accessKey,$this->secretKey);
    }

    /**
     * 上传新增文件
     * @param $bucketName             存储空间名称
     * @param $filePath               存储文件路径
     * @param string $saveFilename    保存文件名
     * @return array
     */
    function uploadAddFile($filePath,$saveFilename = '')
    {
        $uploadMgr = new UploadManager();
        //生成上传 Token
        $token = $this->auth->uploadToken($this->bucketName);
        //上传到七牛后保存的文件名
        if(!empty($saveFilename)) {
            $key = $saveFilename;
        }else{
            $pathInfo = pathinfo($filePath);
            $key = $pathInfo['basename'];
        }

        //调用UploadManager的putFile 方法进行文件的上传。
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);

        return array('ret' => $ret,'err' => $err);
    }

    /**
     * 上传重复文件
     * @param $bucketName  存储空间名称
     * @param $filePath    存储文件路径
     * @param string $key  已存在文件名
     * @return array
     */
    function uploadRepeatFile($filePath,$key = '')
    {
        $uploadMgr = new UploadManager();
        // 上传到七牛后保存的文件名
        if(empty($key)){
            $pathInfo = pathinfo($filePath);
            $key = $pathInfo['basename'];
        }

        $token = $this->auth->uploadToken($this->bucketName,$key);
        //调用UploadManager的putFile 方法进行文件的上传。
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);

        return array('ret' => $ret,'err' => $err);
    }

    /**
     * 删除文件
     * @param $key 文件KEY
     * @return mixed
     */
    function delFile($key){
        //初始化BucketManager
        $result = false;

        $bucketMgr = new BucketManager($this->auth);

        //删除$bucket 中的文件 $key
        $err = $bucketMgr->delete($this->bucketName,$key);

        if($err == null){
            $result = array('ret' =>true,'err'=>$err);
        }else{
            $result = array('ret' =>false,'err'=>$err);
        }

        return $result;
    }
}
