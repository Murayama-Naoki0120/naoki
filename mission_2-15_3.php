<!DOCTYPE html>
<html lang="ja">
<html>
	<head>
	<meta charset="UTF-8">
	</head><!-- ファイルの中身がわからない -->
	
	<body>
		<?php
			//データベースへの接続
			///mysql:dbname=データベースの名前;host=localhost(DBのホスト)//ユーザー名//パスワード
			$dsn = 'データベース名';
			$user = 'ユーザー名';
			$password = 'パスワード';

			//PDOでDBに接続する。
			$pdo = new PDO($dsn,$user,$password);
			$convert=$pdo -> query("SET NAMES utf8");

			
		?>
		<!-- 投稿ボタン -->
		<form action ="mission_2-15-2.php" method="post">
		名前：<input type ="text" name="name"value="<?php echo $username; ?>"/><br>
		コメント:<input type="text"name="comment"value="<?php echo $text; ?>"/><br>
		パスワード：<input type="password"size="8"name="pass" value="<?php echo $userpass;?>"/><br>
		<button type ="submit"name="toukou"value="send1">投稿</button><br>
		<hr>
		<br>
		<!-- 編集ボタン -->
		編集番号：<input type="text" name="editnum">
		パスワード<input type="text"name="editpass"><br>
		<button type="submit" name="editbutton"value="send2">編集</button><br><!--  -->
		<hr>
		<!-- 削除ボタン -->
		削除番号：<input type="text"name="deletenum"><br>
		パスワード：<input type="text" name="deletepass"><br>
		<button type="submit"name="deletebutton"value="send3">削除</button><br>
		</form>
		<hr>
		
		<?php
			header('Content-Type:text/html;charset=UTF-8');//UTFー8を使うことを宣言する

			//データベースへの接続
			//mysql:dbname=データベースの名前;host=localhost(DBのホスト)//ユーザー名//パスワード
			$dsn = 'データベース名';
			$user = 'ユーザー名';
			$password = 'パスワード';
			
			//PDOでdbtextに接続する。
			$pdo = new PDO($dsn,$user,$password);
			$convert=$pdo -> query("SET NAMES utf8");

			$name1=$_POST['name'];
			$text=$_POST['comment'];
			$edit=$_POST['editnum'];

			$ps=$_POST['pass'];
			$edpass=$_POST['editpass'];

			$deletenumber=$_POST['deletenum'];
			$deletepass=$_POST['deletepass'];

			$onoff=$_POST['onoff'];
			$editbutton=$_POST['editbutton'];
			$deleltebutton=$_POST['deletebutton'];

			if( ((isset($_POST['onoff'])) && ($_POST['onoff']!=="")) ){
					
					$pscheck='SELECT*FROM dbtext;';
					$checkresult=$pdo ->query($pscheck);

					
					foreach($checkresult as $checkrow){
						if( $checkrow['number']== $editnum){
							
							$username=$checkrow['name'];
							$text=$checkrow['comment'];

						}
					}

			}else{
			//投稿処理
				if($_POST['toukou']=='send1'){
						if( ((isset($name1))&&($name1!="")) && ((isset($text))&&($text!="")) && ((isset($ps))&&($ps)) ){
	
									$int=$pdo -> prepare("INSERT INTO dbtext(number,name,comment,time,pass)VALUES(:number,:name,:comment,:time,:pass)");
									$int -> bindParam(':number',$numbermax,PDO::PARAM_STR);
									$int -> bindParam(':name',$name1,PDO::PARAM_STR);
									$int -> bindParam(':comment',$comment,PDO::PARAM_STR);
									$int -> bindParam(':time',$time,PDO::PARAM_STR);
									$int -> bindParam(':pass',$pass,PDO::PARAM_STR);
									
									//投稿番号をつけるための処理
									$nbmax='SELECT max(number) FROM dbtext ;';
									$nbmax1=$pdo->query($nbmax);
									
									foreach($nbmax1 as $row){
										$numbermax = $row[0]+1;
									
										echo'<hr>';
									}
								
	
									//挿入する値-----------------------
	
									$name=$name1;
									$comment=$text;
	
									$timestamp=time();//UNIX TIMESTAMPを会得
									$time=date('Y/m/d H:i:s');//時間の会得
									$pass= $ps;
									//-------------------------------
									
									$int -> execute();

						}else{
									echo'投稿内容に不足があります。';
						}
				}
			}
			//編集処理
			if($_POST['editbutton']=='send2'){

				$pscheck='SELECT*FROM dbtext;';
				$checkresult=$pdo ->query($pscheck);

				foreach($checkresult as $checkrow){
					if( ($checkrow['number']== $edit) && ($checkrow['pass']==$edpass)){
						
						$username=$checkrow['name'];
						$text=$checkrow['comment'];

						//テキストフィールドに表示するための前処理（代入）
						$pscheck='SELECT*FROM dbtext;';
						$checkresult=$pdo ->query($pscheck);
						//------------------------------------------

						echo "$edit".'番を編集します。<br>';
						echo'編集内容を入力してください。<br>';
						
						echo'<form action ="mission_2-15-2.php"method="post">';
						echo'名前：<input type ="text" name="hensyuname" value="'.$username.'"/><br>';
						echo'コメント:<input type="text"name="hensyucomment" value="'.$text.'"/><br>';
						echo'<input type="hidden" name="onoff"value="'.$editnum=$edit.'"/>';
						echo'<button type ="submit" name="hensyukakunin"value="edit_on">この内容で編集する</button><br>';
						echo'</form>';
						}else if( ($checkrow['number']== $edit) && ($checkrow['pass']!==$edpass) ){
							echo'投稿番号またはパスワードが違います。';
						}
				}
			}
			

			if( (isset($onoff)) && ($onoff!=="")){
				$hensyuname=$_POST['hensyuname'];
				$hensyutext=$_POST['hensyucomment'];

				$hensyu="update dbtext set name='$hensyuname', comment='$hensyutext' where number='$onoff';";
				$hensyuresult=$pdo->query($hensyu);
				
				if(isset($hensyuresult)){

					echo'編集完了';
				}else{
					echo'編集失敗';
				}

			}	


			//削除処理-----------------------------------
			if($_POST['deletebutton']=='send3'){
			//確認ボタンをつける。hiddenで値を飛ばす。

					$sakujoserch='SELECT*FROM dbtext;';
					$serchresult=$pdo -> query($sakujoserch);
					
					if(!$serchresult){
						echo'失敗';
					}else{
					
						foreach($serchresult as $row){
							if( ($row['number']==$deletenumber) &&($row['pass']==$deletepass) ){
	
								$delete="delete from dbtext where number='$deletenumber';";
								$deleteresult=$pdo -> query($delete);
								
								echo $deletenumber .'番を削除しました。<br>';
	
								
							}else if( ($row['number']==$deletenumber) &&($row['pass']!==$deletepass) ){
								echo'投稿番号またはパスワードが違います。<br>';
								
							}
						}
							
				
		echo'<hr>';
		
		//順番を入れ替える----------------
		$junban='SELECT number,name,comment,time,pass FROM dbtext  where number ORDER BY number ASC;';
		$seiretu=$pdo -> query($junban);
		//------------------------------

		//表示処理------------------------------------
		foreach($seiretu as $line){
					echo '投稿番号'.$line['number'].',';
					echo '名前'.$line['name'].',';
					echo 'コメント'.$line['comment'].',';
					echo '投稿時間'.$line['time'];
					echo'<br>';
				}
				echo "<hr>";
		//------------------------------------------

		?>
	</body>
</html>
