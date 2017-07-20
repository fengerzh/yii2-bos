# 安装

在`composer.json`中添加

```
"fengerzh/yii2-bos": "*"
```

然后，执行

```
composer update
```

# 用法

## 设置

将以下键值存入配置文件`common\config\main.php`或`common\config\params.php`：

```
'baidu.bos.ak' => '*** key ***',
'baidu.bos.sk' => '*** secret ***',
```

## 存入百度云

```
use fengerzh\bos\Bos;

$pic_name = Bos::saveToBos($model, $field_name, $bucket, $path, $filename);
```

参数说明：

* $model, 数据库记录
* $field_name, 字符串，存储图片文件的字段
* $bucket, 字符串，BOS的库名
* $path, 字符串，远端路径名，不包括文件名
* $filename, 字符串，文件名

## 从百度云删除

```
use fengerzh\bos\Bos;

Bos::deleteFromBos($bucket, $objectKey);
```

参数说明：

* $bucket, 字符串，BOS的库名
* $objectKey, 字符串，文件路径，包括文件名
