<?php
  //読み込み時にチャート用の一時ファイルを毎度削除する
  $file='data/temp.csv';
  unlink($file);

  //今ある案件を配列に格納（task.txtを参照）
  $taskfile=fopen('data/task.txt','r');
  $taskarray=array();
  if($taskfile){
    while($line=fgets($taskfile)){
      $line=str_replace(array("\r\n", "\r", "\n"),"",$line);
      array_push($taskarray,$line);
    }
  }
  flock($taskfile,LOCK_UN);
  fclose($taskfile);


  //行まとめになっているrecord.csvデータをカンマで区切ってテーブル形式にする
  $str='';
  $recordfile=fopen('data/record.csv','r');
  flock($recordfile,LOCK_EX);
  $table=array();
  if($recordfile){
    while($line=fgets($recordfile)){
      $row=explode(',',$line);  //行を配列で句切る
      array_push($table,$row);
    }
  }
  flock($recordfile,LOCK_UN);
  fclose($recordfile);

  //案件ごとの時間の合計を算出
  $sumForEachTask=array();
  $today=date("Y-m-d");
  $nowMonth=substr($today,5,2);
  if($_POST){
    $nowMonth=$_POST['month'];
  }
  //echo $nowMonth;
  for($i=0; $i<count($taskarray); $i++){
    $task=$taskarray[$i];
    $sum=0;
    for($j=0; $j<count($table); $j++){
      $date=$table[$j][0];
      $month=substr($date,5,2);
      if($month==$nowMonth){
        if($task==$table[$j][2]){
          $num=(float) $table[$j][3];
          $sum+=$num;
        }
      }
    }
    $sumForEachTask+=array($task=>$sum);
    echo $task.':'.$sumForEachTask[$task].'時間　';

    //チャート書き出し用の一時ファイルを作成
    $array = array($task,$sumForEachTask[$task]); //csvファイルに書き込むデータの準備
    $file=fopen('data/temp.csv','a');            //ファイルを開くまたは作成
    //一時ファイルに項目の追加
    if($i==0){
      $koumokuArray=array('task','time');
      fputcsv($file, $koumokuArray);
    }
    flock($file,LOCK_EX);                       //ファイルをロック
    fputcsv($file, $array);                    //csvに書き込み
    flock($file,LOCK_UN);                     //ファイルのロックを解除
    fclose($file);                           //ファイルを閉じる   
  }
?>

<style>
  /* .wrapper{
    width:375px;
    margin:0 auto;
  } */
  select{margin-left:100px;}
</style>
<br>
<br>
<br>
<form action="read_record.php" method="post">
  <select name="month">
    <?php for($i=1; $i<=12; $i++){?>
      <?php if($i<10){?>
        <?php if($i==$nowMonth){?>
          <option value="0<?=$i?>" selected><?=$i?></option>
        <?php }else{?>
          <option value="0<?=$i?>"><?=$i?></option>
        <?php }?>
      <?php }else if($i>10){?>
        <?php if($i==$nowMonth){?>
          <option value="<?=$i?>" selected><?=$i?></option>
        <?php }else{?>
          <option value="<?=$i?>"><?=$i?></option>
        <?php }?>
      <?php }?>
    <?php }?>
  </select>月の結果を<input type="submit" value="見る">
</form>
<!-- <div class="wrapper"> -->
  <div id="viz0"></div>
<!-- </div> -->

<script src="https://unpkg.com/rough-viz@1.0.5"></script>
<script>
  new roughViz.Bar({
    element:'#viz0',
    data:'data/temp.csv',
    labels: 'task',
    values: 'time'
  })
</script>