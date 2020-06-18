<?php 
  $today=date("Y-m-d");//今日の日付を取得
  $options='';
  $file=fopen('data/task.txt','r');
  flock($file,LOCK_EX);
  if($file){
    while($line=fgets($file)){
      $newline=str_replace(array("\r\n", "\r", "\n"),'',$line);  //改行を削除
      $options.="<option value={$newline}>{$newline}</option>";
    }
  }
  flock($file,LOCK_UN);
  fclose($file);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="ie=edge"> 
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
  <title>作業時間管理アプリ</title>
</head>

<html>
  <body>
    <div class="wrapper">
      <div class="inner">
        <h1 class="app-title">作業時間管理アプリ</h1>
        <form action="create_record.php" method="post">
          <div class="user_and_date">
            <input type="date" class="date" name="date[]" value='<?=$today?>'>
            <input type="user" class="user" name="user[]" value="ユーザ１">
          </div>
          <table class="todays-work">
            <tr><th>案件</th><th>時間</th></tr>
            <tr class="input">
              <td>
                <i class="fas fa-plus-circle"></i>
                <select class="task" name="task[]">
                  <?=$options?>
                </select>
              </td>
              <td>
                <select class="time" name="time[]">
                  <option value=0.5>0.5</option><option value=1>1</option><option value=2>2</option><option value=3>3</option><option value=4>4</option><option value=5>5</option><option value=6>6</option>
                  <option value=7>7</option><option value=8>8</option><option value=9>9</option><option value=10>10</option><option value=11>11</option><option value=12>12</option>
                </select>
              </td>
            </tr>
            <tr class="submit-row">
              <td colspan="2"><button class="submit-button">送信</button></td>
            </tr>
          </table>
        </form>
        <hr>
        <div class="new-task-area">
          <p>案件追加</p>
          <form action="add_task.php" method="post">
            <input class="new-task" name="newtask">
            <input type="submit" class="add-btn" value="追加">
          </form>
        </div>
        <hr>
        <button class="move-btn" onclick="location.href='./read_record.php'">作業時間の合計を見る</button>
      </div>
    </div>
    <script>
      $(document).on('click','.fa-plus-circle',function(){
        let newEle=$('.input').clone(true)
        $('.submit-row').before(newEle[0])
      })
    </script>
  </body>
</html>