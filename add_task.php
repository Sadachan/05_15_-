<?php 

var_dump($_POST);
$date=$_POST["newtask"];
$write_data="{$date}\n";
$file=fopen('data/task.txt','a');       //ファイルを開くまたは作成
flock($file,LOCK_EX);                  //ファイルをロック
fwrite($file, $write_data);           //csvに書き込み
flock($file,LOCK_UN);                //ファイルのロックを解除
fclose($file);                      //ファイルを閉じる
header('Location:index.php');      //入力画面に戻る