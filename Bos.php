<?php
/**
 * @package   yii2-bos
 * @author    Buzz Zhang <buzz.zhang@gmail.com>
 */

namespace fengerzh\bos;

use Yii;
use yii\web\UploadedFile;
use BaiduBce\Services\Bos\BosClient;

class Bos
{
    public static function saveToBos($model, $bucket, $remote_path, $save_file_name = '')
    {
        $image = UploadedFile::getInstance($model, 'logo_file');
        if (isset($image) && $image != null) {
            $img_arr = explode('.', $image->name);
            $ext = end($img_arr);
            if (empty($save_file_name)) {
                $filename = $remote_path . '/' . $image->name;
            } else {
                $filename = $remote_path . '/' . $save_file_name . '.' . $ext;
            }
            $filepath = '/tmp/' . $filename;
            if (!$image->saveAs($filepath)) {
                //保存图片失败
                if ($image->error == UPLOAD_ERR_INI_SIZE) {
                    echo '文件尺寸太大！';
                } else {
                    echo $image->error;
                }
                exit;
            } else {
                //保存照片成功，上传百度
                //上传照片到百度
                $client = new BosClient(
                    [
                        'credentials' => [
                            'ak' => Yii::$app->params['baidu.bos.ak'],
                            'sk' => Yii::$app->params['baidu.bos.sk']
                        ],
                        'endpoint' => Yii::$app->params['baidu.bos.endpoint']
                    ]
                );
                $client->putObjectFromFile($bucket, $filename, $filepath);
                //删除本地图片
                unlink($filepath);
            }
        }
        return $filename;
    }
}
