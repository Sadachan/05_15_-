<?php
  //var_dump($_POST['time']);
  //POSTデータ受け取り
  $date=$_POST['date'];
  $user=$_POST['user'];
  $task=array();
  $time=array();
  foreach($_POST['task'] as $eachtask){array_push($task,$eachtask);}
  foreach($_POST['time'] as $eachtime){array_push($time,$eachtime);}

  //ファイルに書き込む
  for($i=0; $i<count($task); $i++){
    $array = array($date[0],$user[0],$task[$i],$time[$i]);
    var_dump($array);
    $file=fopen('data/record.csv','a');     //ファイルを開くまたは作成
    flock($file,LOCK_EX);                  //ファイルをロック
    fputcsv($file, $array);               //csvに書き込み
    flock($file,LOCK_UN);                //ファイルのロックを解除
    fclose($file);                      //ファイルを閉じる
  }
  header('Location:index.php');      //入力画面に戻る
?>

