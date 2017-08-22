<?php
namespace qiniusdk;

// 引入鉴权类
use Qiniu\Auth;

// 引入上传类
use Qiniu\Storage\UploadManager;

class qiniuUtil
{
    // 需要填写你的 Access Key 和 Secret Key
    const  accessKey = 'YZkj1kb9bXrGbXJjxLvRIpR5BFNypLDagGMu6w4x';
    const  secretKey = 's5lnUgmffgXHgqtZRXTegbq2mufBkO4DlupKybVn';
    private $auth;
    private $uploadMgr;
    function __construct()
    {
        $this->auth = new Auth(self::accessKey, self::secretKey);
        $this->uploadMgr = new UploadManager();
    }

    //上传文件
    function uploadFile($bucketName,$filePath,$saveFilename = ''){
        // 生成上传 Token
        //$token = $auth->uploadToken($bucket,'test.txt');
        $token = $this->auth->uploadToken($bucketName);

        // 上传到七牛后保存的文件名
        if(!empty($saveFilename)) {
            $key = 'test.txt';
        }else{
            $pathInfo = pathinfo($filePath);
            $key = $pathInfo['basename'];
        }

        // 调用 UploadManager 的 putFile 方法进行文件的上传。
        list($ret, $err) = $this->uploadMgr->putFile($token, $key, $filePath);

        return array('ret'=>$ret,'err'=>$err);
    }

    //删除文件
    function delFile(){

    }
}
