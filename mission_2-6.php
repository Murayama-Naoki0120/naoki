
<!DOCTYPE HTML>
<html lang="ja">
<html>
<head>
	<meta charset="UTF-8">
	<title>旅の写真集</title>
	<style type="text/css">
	<!--
		body{
			background-color:#CBFFD3;
		}
		h1{
			font-size:15pt
		}
	-->
	</style>
</head>
<body>

<div style="text-align:light">

	"旅の写真集"<br>
	<br>
<?php
	$filename='textfile4.txt';
	$edit=$_POST['edit'];
	$editpass=$_POST['editpass'];
	$editarray=file($filename);
	mb_convert_variables('UTF-8','SJIS',$editarray);//配列の文字コードを変換
	//編集が入力された時にループ回してパスワード判定をする。その後に「名前」「コメント」「パス」を出力するためにそれぞれ値を入れる。
	foreach($editarray as $exp_array){
		$data=explode("<>",$exp_array);
		if($edit==$data[0]){
			if($editpass==$data[4]){
				$edit_num=$data[0];
				$user=$data[1];
				$text=$data[2];
				$userpass=$data[4];
				$onoff=$data[0];
			}else{
				echo'パスワードが違います。';
				echo'再度入力してください。';
			}
		}
	}
?>
<h1>
<p><form action ="mission_2-6.php" method="post">
名前：<input type ="text" name="name" value="<?php echo $user; ?>"/><br>
コメント:<input type="text"name="coment" value="<?php echo $text; ?>"/><br>
	<!-- パスワードを入力 -->
パスワード：<input type="password"size="8"name="pass" value="<?php echo $userpass; ?>"><br></p>
	<input type="button" onClick="location.href='http://co-581.it.99sv-coco.com/textfile4.txt'" value="textfile4.txtへ"> <br>
	<hr>

編集対象番号：<input type="text" name="edit">
<input type ="hidden" name="onoff" value="<?php echo $onoff;?>"/><br>
パスワード：<input type="text" name="editpass"><br>

<!-- 編集の時にボタンの記述内容を変える -->
<?php
if(!isset($onoff)){
	echo'<button type="submit"name="editbutton">送信</button><br>';
	echo'</form>';
}else{	
			echo '内容を編集してください。<br>';
			echo'<form action ="mission_2-6.php" method="post"/><br>';
			echo'<input type ="hidden" name="onoff" value="'.$onoff.'"/>';
			echo'<button type ="submit" name="hensyu" value="'.$a=$onoff.'" >この内容で編集する</button><br>';
			echo'</form>';
			
	}

?>


<?php
header('Content-Type:text/html;charset=UTF-8');//UTFー8を使うことを宣言する
	$filename='textfile4.txt';
	$name=$_POST['name'];
	$coment=$_POST['coment'];
	$ed=$_POST['edit'];
	
	$ps=$_POST['pass'];
	$edpass=$_POST['editpass'];
	
	$name1= mb_convert_encoding($name,"sjis","utf-8");//文字コードを変換
	$coment1 = mb_convert_encoding($coment,"sjis","utf-8");//文字コードを変換
	$editpass = mb_convert_encoding($edpass,"sjis","utf-8");//文字コードを変換
	$edit = mb_convert_encoding($ed,"sjis","utf-8");//文字コードを変換
	$pass = mb_convert_encoding($ps,"sjis","utf-8");//文字コードを変換
	$onoff=$_POST['onoff'];
	$a=$_POST['hensyu'];

if(  ((!empty($onoff)) or (isset($a))) ){
			$editarray=file($filename);
			
			//変換を入れたら文字化けが出た。
			//mb_convert_variables('UTF-8','SJIS',$editarray);//配列の文字コードを変換
		$number=$onoff;
	if(((isset($name1))&&($name1!==""))&&((isset($coment1))&&(coment1!==""))){
			//file読み込み(forech)
			//ファイルリセット
			$open=fopen($filename,'w');
			fclose($open);
			$henkou = mb_convert_encoding($onoff,"sjis","utf-8");//文字コードを変換
	
			$fp=fopen($filename,'a');
			foreach($editarray as $editdata){
			//explodeで投稿番号と編集番号の比較
				$exp_editarray=explode("<>",$editdata);
					if($exp_editarray[0]==$onoff){
					//編集番号なら名前とコメントを変更する。
						fwrite($fp,"$exp_editarray[0]<>$name1<>$coment1<>$exp_editarray[3]<>$exp_editarray[4]<>\n");
					echo'編集完了';
					$onoff=NULL;
					}else{
					fwrite($fp,"$exp_editarray[0]<>$exp_editarray[1]<>$exp_editarray[2]<>$exp_editarray[3]<>$exp_editarray[4]<>\n");

					}
			}
			fclose($fp);
		}else{
		
		}
}else{
		if( ( (isset($_POST["name"]) )&&($_POST["name"] !=="") )&&((isset($_POST["coment"]))&&($_POST["coment"] !=""&&(isset($_POST['pass'])&&($_POST['pass']!=="")))) ){

				/*ファイルにデータがある場合のみ処理する。*/
				if(file_exists($filename)){
						
					$timestamp=time();//UNIX TIMESTAMPを会得
					$uptime=date('Y/m/d H:i:s');//時間の会得
					$cnt=count(file($filename))+1;//データのカウント
					$fp=fopen($filename,'a');//ファイルを開く
					fwrite($fp,"$cnt<>$name1<>$coment1<>$uptime<>$pass<>");//データの表示
					fputs($fp,"\n");//改行
					fclose($fp);//ファイルを閉じる
				}else{
					touch($filename);//ファイル作成	
					$timestamp=time();//UNIX TIMESTAMPを会得
					$uptime=date('Y/m/d H:i:s');//時間の会得
					$fp=fopen($filename,'a');
					fwrite($fp,"1<>$name1<>$coment1<>$uptime<>$pass<>");
					fputs($fp,"\n");
					fclose($fp);
				}
		}
}
?>


<form action ="mission_2-6.php"method="post"><br>
削除したい投稿番号：<input type="text" name="delete_num"><br>
パスワード：<input type="text" name="deletepass"><br>
<button type="submit"name="deletebutton">送信</button>
</form>
</h1>
<?php
 header('Content-Type:text/html;charset=UTF-8');//UTFー8を使うことを宣言する
//削除機能
	$filename='textfile4.txt';
	$delete_num=$_POST['delete_num'];
	$deletepass=$_POST['deletepass'];
	$passarray=file($filename);
	mb_convert_variables('UTF-8','SJIS',$passarray);//配列の文字コードを変換

	$delete = mb_convert_encoding($delete_num,"sjis","utf-8");//文字コードを変換
	$deletebutton=$_POST['deletebutton'];

	if(isset($deletebutton)){
		foreach($passarray as $passline){
			//mb_convert_variables('UTF-8','SJIS',$passline);//配列の文字コードを変換
			$exp_pass=explode("<>",$passline);

			if($exp_pass[0]==$delete){

					if($exp_pass[4]==$deletepass){
						
						$array=file($filename);
						
						//textfile4.txtの初期化
						$syokika_filename=fopen($filename,'w');
						fclose($syokika_filename);
					
						$i=1;
						foreach($array as $deleteline){
					
							$delete_data=explode("<>",$deleteline);
							if($delete_data[0]!= $delete){
								$fp=fopen($filename,'a');
										fwrite($fp,"$i<>$delete_data[1]<>$delete_data[2]<>$delete_data[3]<>$delete_data[4]<>\n");
								fclose($fp);
								$i++;
							}
						}
					}else{
						echo'パスワードが違います。';
					}
			}
		}
	}

?>
<hr>
<?php
//表示機能
		$filename='textfile4.txt';
		$dispray_array=file($filename);//配列を読み込んで配列に格納
		mb_convert_variables('UTF-8','SJIS',$dispray_array);//配列の文字コードを変換
		
		foreach($dispray_array as $line){//$lineに$dispray_arrayを1行ずつ入れる。それをファイルの全ての行で行う。
			
			$dispray_data=explode("<>",$line);//配列の内容を<>で分解
			
			echo "投稿番号：$dispray_data[0]\t\t";
			echo "名前：$dispray_data[1]\t\t";
			echo "コメント：$dispray_data[2]\t\t";
			echo "日時：$dispray_data[3]";
			echo'<br/>';
			}	
?>
<hr>
</div>
</body>
</html>