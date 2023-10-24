<!DOCTYPY html>
<html lang="ja">
<head>
        <meta charset="UTF-8">
        <title>mission_5-1</title>
</head>
</html>
<body>
        ＜最近はまっていること＞<br>
        <br>
        ・書き込んでほしい内容<br>
        1.「名前」フォームに自分の名前を書き込んでください<br>
        2.「コメント」フォームに最近はまっていることを書き込んでください<br>
        3.「パスワード」フォームに設定したいパスワードを入力してください<br>
        　パスワードを入力しないと削除・編集ができないため、注意してください！<br>
        <br>
        →「送信」ボタンによりデータ送信<br>
        <br>
        ・その他試してほしい機能<br>
        1.削除機能<br>
        　・「削除対象番号」フォームに削除したい投稿の投稿番号を記入してください<br>
        　・その投稿のパスワードを「パスワード」フォームに記入してください<br>
        　・「削除」ボタンをクリック→削除したい投稿が消えます！<br>
        　<br>
        2.編集機能<br>
        　・「編集対象番号」フォームに編集したい投稿の投稿番号を記入してください<br>
        　・その投稿のパスワードを「パスワード」フォームに記入してください<br>
        　・「編集」ボタンをクリックすると編集したい投稿の内容が「名前」と「コメント」フォームに表示されるので<br>
        　　フォーム内で編集を行ってください<br>
        　・編集ができたら「送信」ボタンをクリック→投稿が編集されます！<br>
        　<br>
        ＜投稿フォーム＞<br>
        <?php
            //データベース接続
            $dsn = 'mysql:dbname=tb250381db;host=localhost';
            $user = 'tb-250381';
            $password = 'mraBF9nxuy';
            $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            
            //テーブル作成
            $sql = "CREATE TABLE IF NOT EXISTS tbtest"
                ." ("
                . "id INT AUTO_INCREMENT PRIMARY KEY,"
                . "name CHAR(32),"
                . "comment TEXT,"
                . "password TEXT"
                .");";
            $stmt = $pdo->query($sql);
            
            //指定した投稿番号の投稿を編集
            if(!empty($_POST["ed"])){
                $id = $_POST["ed"];
                $name = $_POST["name"];
                $comment = $_POST["str"];
                $sql = 'UPDATE tbtest SET name=:name,comment=:comment WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }
            
            //データベースに投稿内容を保存
            elseif(!empty($_POST["name"]) && !empty($_POST["str"])){
            $name = $_POST["name"];
            $comment = $_POST["str"];
            $password = $_POST["pass"];
            $sql = "INSERT INTO tbtest (name, comment, password) VALUES (:name, :comment, :password)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->execute();
            }
            
            //指定した投稿番号の投稿を削除
            if(!empty($_POST["delete"]) && !empty($_POST["dpass"])){
                $id = $_POST["delete"];
                $sql = 'SELECT * FROM tbtest WHERE id=:id ';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
                $stmt->execute();                             
                $results = $stmt->fetchAll(); 
                foreach ($results as $row){
                    if($_POST["dpass"] == $row['password']){
                        $id = $_POST["delete"];
                        $sql = 'delete from tbtest where id=:id';
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                        $stmt->execute();
            }
            }
            }
        ?>

        <form action =""method="post">
            <input type="text" name="name" placeholder="名前" 
            value=<?php
            if(!empty($_POST["edit"]) && !empty($_POST["epass"])){
                $id = $_POST["edit"];
                $sql = 'SELECT * FROM tbtest WHERE id=:id ';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
                $stmt->execute();                             
                $results = $stmt->fetchAll(); 
                foreach ($results as $row){
                    if($_POST["epass"] == $row['password']){
                        echo $row["name"];
                    }
                }
            }
            ?>>
            <input type="text" name="str" placeholder="コメント" 
            value="<?php
            if(!empty($_POST["edit"]) && !empty($_POST["epass"])){
                $id = $_POST["edit"];
                $sql = 'SELECT * FROM tbtest WHERE id=:id ';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
                $stmt->execute();                             
                $results = $stmt->fetchAll(); 
                foreach ($results as $row){
                    if($_POST["epass"] == $row['password']){
                        echo $row["comment"];
                    }
                }
            }
            ?>">
            <input type="hidden" name="ed" 
            value="<?php 
            if(!empty($_POST["edit"]) && !empty($_POST["epass"])){
                echo $_POST["edit"];
            }
            ?>">
            <input type="text" name="pass" placeholder="パスワード">
            <input type="submit" name="submit">
            <br>
            <br>
            <input type="number" name="delete" placeholder="削除対象番号">
            <input type="text" name="dpass" placeholder="パスワード">
            <input type="submit" name="submit" value="削除">
            <br>
            <br>
            <input type="number" name = "edit" placeholder="編集対象番号">
            <input tyoe="text" name="epass" placeholder="パスワード">
            <input type="submit" name="submit" value="編集">
        </form>

        <?php
            //保存内容を表示
            $sql = 'SELECT * FROM tbtest';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                echo $row['id'].',';
                echo $row['name'].',';
                echo $row['comment'].'<br>';
                echo "<hr>";
            }
        ?>
</body>