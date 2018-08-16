<?php
namespace app\components;

use Yii;
use yii\base\Component;
use app\models\AWSSource;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use function GuzzleHttp\Psr7\mimetype_from_filename;

class AWS extends Component {

    private $accessKey = 'AKIAICVFEAWSQXKMRLXA';
    private $secretKey = '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';

    /*Имена бакетов бакетов */
    const bucket_one = 'bucket-kashirin-one';
    const bucket_two = 'bucket-kashirin-two';
    const bucket_three = 'bucket-kashirin-three';

    /*Возвращение данных клиента*/
    protected function getDataClient ($accessKey , $secretKey, $region) {
        $s3 = new S3Client(
            ['version' => 'latest',  'region'  => $region,
                'credentials' => ['key' => $accessKey,
                    'secret' => $secretKey, ]
            ]);
        return $s3;
    }

    /*Функция получения контента/ссилки на файл*/
    public function getContent($bucket , $filename, $region) {

        $s3 = $this->getDataClient($this->accessKey , $this->secretKey, $region);
        try {
            $bucket = AWSSource::find($bucket)->where(['name' => $bucket])->asArray()->all()[0]['name'];
            $file_exists = $s3->doesObjectExist($bucket, $filename);
            if($file_exists){
                $result = $s3->getObject(
                    ['Bucket' => $bucket,
                        'Key' => $filename
                    ]);
                return (string)$result['@metadata']['effectiveUri'];
            }
        } catch (S3Exception $e) {
            return $e->getMessage() . PHP_EOL;
        }
    }
    /*Функция записи файла на амазон*/
    public function setContent($filename, $bucket,  $content , $region){
        try {
            $s3 = $this->getDataClient($this->accessKey , $this->secretKey, $region);
            $bucket = AWSSource::find($bucket)->where(['name' => $bucket])->asArray()->all()[0]['name'];
            $result_upload = $s3->putObject(array(
                'Bucket'     => $bucket,
                'SourceFile' => $content,
                'Key'        => basename($filename),
                'ACL'        => 'public-read',
                'StorageClass' => 'Standart',
            ));
            $file_upload = $result_upload->get('ObjectURL');
            if($file_upload){
                return true;
            }
        } catch (S3Exception $e) {
            return $e->getMessage() . PHP_EOL;
        }
    }

    /*Добавление данных в базу*/
    protected function insertDataBucket($name , $root , $region){

        $model = new AWSSource();
        try {
            $model->name = $name;
            $model->region = $region;
            $model->root =  $root;
            $arrs = $model::find()->where(['root' => $model->root])->exists();
            if($arrs){
                return false;
            }else {
                return $model->save();
            }
        }catch (S3Exception $e) {
            return $e->getMessage() . PHP_EOL;
        }
    }
}