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
    public static function saveToBos($model, $field, $bucket, $remote_path, $save_file_name = '')
    {
        $image = UploadedFile::getInstance($model, $field);
        $filename = '';
        if (isset($image) && $image != null) {
            $img_arr = explode('.', $image->name);
            $ext = end($img_arr);
            if (empty($save_file_name)) {
                $filename = $image->name;
            } else {
                $filename = $save_file_name . '.' . $ext;
            }
            $remote_filename = $remote_path . '/' . $filename;
            $local_filename = '/tmp/' . $filename;
            if (!$image->saveAs($local_filename)) {
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
                $client->putObjectFromFile($bucket, $remote_filename, $local_filename);
                //删除本地图片
                unlink($local_filename);
            }
        }
        return $filename;
    }

    public static function deleteFromBos($bucket, $objectKey)
    {
        $client = new BosClient(
            [
                'credentials' => [
                    'ak' => Yii::$app->params['baidu.bos.ak'],
                    'sk' => Yii::$app->params['baidu.bos.sk']
                ],
                'endpoint' => Yii::$app->params['baidu.bos.endpoint']
            ]
        );
        $client->deleteObject($bucket, $objectKey);
        return '';
    }
}
