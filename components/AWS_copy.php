<?php
namespace app\components;

use yii\base\Component;
use app\models\AWSSource;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use function GuzzleHttp\Psr7\mimetype_from_filename;

class AWS extends Component {

    private $accessKey = 'accesskey';
    private $secretKey = 'secterkey';

    /*Имена бакетов бакетов */
    const bucket_one = 'bucket-kashirin-one';
    const bucket_two = 'bucket-kashirin-two';
    const bucket_three = 'bucket-kashirin-three';

    /*Функция получения контента файла*/
    public function getContent($bucket , $filename,  $region) {

        $s3 = new S3Client(
            ['version' => 'latest',  'region'  => $region,
            'credentials' => ['key' => $this->accessKey,
            'secret' => $this->secretKey, ]
            ]);

        try {
            $command = $s3->getCommand('GetObject', array(
                'Bucket'      => $bucket,
                'Key'         => $filename,
                'ContentType'  => mimetype_from_filename(basename($filename)),
                'ResponseContentDisposition' => 'attachment; filename="'.basename($filename).'"'
            ));
            $request = $s3->createPresignedRequest($command , "+20 minutes");
            $signedUrl = (string) $request->getUri();
            $signedUrl = explode('?' , $signedUrl);
            $this->insertDataBucket($bucket , $signedUrl[0], $region);
            return $signedUrl[0];
        } catch (S3Exception $e) {
            return $e->getMessage() . PHP_EOL;
        }
    }
    /*Функция записи файла на амазон*/
    public function setContent($filename, $bucket,  $content , $region){
        try {
            $s3 = new S3Client(
                ['version' => 'latest',  'region'  => $region,
                    'credentials' => ['key' => $this->accessKey,
                        'secret' => $this->secretKey, ]
                ]);
            $s3->putObject(array(
                'Bucket'     => $bucket,
                'SourceFile' => $content,
                'Key'        => basename($filename),
                'ACL' => 'public-read',
                'StorageClass' => 'Standart',
            ));
        } catch (S3Exception $e) {
            return $e->getMessage() . PHP_EOL;
        }
    }

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