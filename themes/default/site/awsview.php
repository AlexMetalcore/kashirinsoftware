<?php
$region_one = 'us-east-1';
$region_two = 'eu-west-2';
$region_three = 'eu-west-3';
$filename_one = 'Ресурс 23@2x.png';
$filename_two = 'Ресурс 66.png';
$filename_three = 'Ресурс 25.png';

$upload_path_one = '/home/alex/testtask/photo1.jpg';
$upload_path_two = '/home/alex/testtask/photo2.jpg';
$upload_path_three = '/home/alex/testtask/photo3.jpg';

$file_upload_one = 'photo1.jpg';
$file_upload_two = 'photo2.jpg';
$file_upload_three = 'photo3.jpg';

$one = Yii::$app->aws->getContent(Yii::$app->aws::bucket_one, $filename_one , $region_one);
$two = Yii::$app->aws->getContent(Yii::$app->aws::bucket_two, $filename_two ,  $region_two);
$three = Yii::$app->aws->getContent(Yii::$app->aws::bucket_three ,$filename_three ,  $region_three);
?>
<a href="<?= $one;?>"><?= $one;?></a><br>
<a href="<?= $two;?>"><?= $two;?></a><br>
<a href="<?= $three;?>"><?= $three;?></a><br>
<?php
Yii::$app->aws->setContent($file_upload_one , Yii::$app->aws::bucket_one, $upload_path_one , $region_one);
Yii::$app->aws->setContent($file_upload_two , Yii::$app->aws::bucket_two , $upload_path_two , $region_two);
Yii::$app->aws->setContent($file_upload_three, Yii::$app->aws::bucket_three, $upload_path_three , $region_three);

?>

