<?php
$edit_val ="";
$name_val ="";
$comment_val ="";
$pass_val ="";
$name = $_POST["name"];
$comment = $_POST["comment"];

//�f�[�^�x�[�X�ւ̐ڑ�(3-1)try�Ƃ͂Ȃɂ��A25,26�H�H
try {
    $dsn = '�f�[�^�x�[�X��;host=localhost';
    $user = '���[�U�[��';
    $pass = '�p�X���[�h';
    $pdo = new PDO(
        $dsn,$user,$pass,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,//�G���[������
            PDO::ATTR_EMULATE_PREPARES => false,//�ÓI�v���[�X�z���_�[
        )
    );
    //�e�[�u���폜 �Ȃ�//�g���̂�
    //$sql = "DROP TABLE IF EXISTS table";
    //$pdo -> exec($sql);
    
    //�e�[�u���쐬 35�s��'�H not exists, not null??(32)?
    $DB_table_name = "table1";
    $create_query = '
    CREATE TABLE IF NOT EXISTS '.$DB_table_name.'(
    id INT NOT NULL AUTO_INCREMENT primary key,
    name CHAR(32) NOT NULL,
    comment TEXT NOT NULL,
    date DATETIME NOT NULL,
    password TEXT NOT NULL
    )';
    $create_table = $pdo -> prepare($create_query);
    $create_table -> execute();
    
    //�쐬�ł������m�F �Ȃ�/*
    /*
     $is_table = $pdo -> query('SHOW TABLES');
    foreach($is_table as $rows){
        print_r($rows) ."<br>";
}
    //create table�̒��g�\��
    $showcre_sql ='SHOW CREATE TABLE table1';
    $showcre_result = $pdo-> query($showcre_sql);
    foreach ($showcre_result as $row_1){
        print_r($row_1);
    }
    echo"<hr>";
    */
    //���ݎ����擾 ����̕K�v���H
    $date = date(Y."-".m."-".d."-".H."-".i."-".s);
    
    //�폜�p�X����@�폜����p�X�Ƃ́HFETCH�QNUM�H
    if(!empty($_POST["delete_pass"]) && !empty($_POST["delete_num"]) && ctype_digit($_POST["delete_num"])){
        //�폜�s�̏��擾
        $delete_pass = $_POST["delete_pass"];
        $delete_num = $_POST["delete_num"];
        $select_sql ="SELECT * FROM table1 where id=$delete_num";
        $select_result = $pdo->query($select_sql);
        $sel_result = $select_result->fetch(PDO::FETCH_NUM);
        
        //�p�X����v���Ă���폜���s$sql_result[4]�H
        if($sel_result[4] == $delete_pass){
            $delete_sql = "delete from table1 where id=$delete_num";
            $delete_result = $pdo->query($delete_sql);
        }elseif($sel_result[4] != $delete_pass){
            echo "�폜�̃p�X���[�h���Ⴂ�܂�";
        }
    }
    //�ҏW�p�X����
    if(!empty($_POST["edit_pass"]) && !empty($_POST["edit_num"]) && ctype_digit($_POST["edit_num"])){
        $edit_pass = $_POST["edit_pass"];
        $edit_num = $_POST["edit_num"];
        unset($select_sql);
        unset($select_result);
        unset($sel_result);
        $select_sql ="SELECT * FROM table1 where id=$edit_num";
        $select_result = $pdo->query($select_sql);
        $sel_result =$select_result->fetch(PDO::FETCH_NUM);
 
        //�p�X����v���Ă�����ҏW���e���t�H�[���ɕ\��
        if($sel_result[4] == $edit_pass){
            $edit_val = $sel_result[0];
            $name_val = $sel_result[1];
            $comment_val = $sel_result[2];
            $pass_val = $sel_result[4];
        }elseif($sel_result[4] != $edit_pass){
            echo "�ҏW�̃p�X���[�h���Ⴂ�܂�";
        }
    }
    
    //�ҏW�@�\
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $password = $_POST["password"];
    $edit = $_POST["edit"];
    if(!empty($_POST["edit"]) && !empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["password"])){
    
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $password = $_POST["password"];
        $edit = $_POST["edit"];
        
        $update_sql = "update table1 set name='$name' , comment='$comment' , date='$date' , password='$password' where id=$edit";
        $update_result = $pdo->query($update_sql);
    }elseif(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["password"])){
        //���e�@�\
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $password = $_POST["password"];
        
        //DB�ɒǉ� PDO::PARAM_STR�Ƃ́H
        $add_sql = $pdo->prepare("INSERT INTO table1(name,comment,date,password)VALUES(:name,:comment,:date,:password)");
        $add_sql->bindParam(':name',$name,PDO::PARAM_STR);
        $add_sql->bindParam(':comment',$comment,PDO::PARAM_STR);
        $add_sql->bindParam(':date',$date,PDO::PARAM_STR);
        $add_sql->bindParam(':password',$password,PDO::PARAM_STR);
        $add_sql->execute();
    }

    //DB���e�擾
        unset($select_sql);
        unset($select_result);
        unset($sel_reslt);
        $select_sql ='SELECT * FROM table1 ORDER BY id';
        $select_result = $pdo->query($select_sql);
        /*���@1
        foreach($select_result as $row){
            echo $row['id'].',';
            echo $row['name'].',';
            echo $row['comment'].'<br>';
        }*/
        //���@2
        $sel_result = $select_result->fetchAll(PDO::FETCH_NUM);
        //print_r($sel_result);

} catch (PDOException $e) {
    echo $e->getMessage()." - ".$e->getLine().PHP_EOL;

}
?>

<html lang="ja">
     <head>
     <meta charset="utf-8">
     <title>4-1</title>
     </head>
     <body>
        <form action="mission_4-1_(nakadai).php" method="post">
            <input type="text" name="edit" value="<?=$edit_val;?>" placeholder="�ҏW�Ώ۔ԍ�"><br>
            <input type="text" name="name" value="<?=$name_val;?>" placeholder="���O"><br>
            <input type="text" name="comment" value="<?=$comment_val;?>" placeholder="�R�����g"><br>
            <input type="text" name="password" value="<?=$pass_val;?>" placeholder="�p�X���[�h"><br>
            <input type="submit" value="���M">
        </form>
        <form action="mission_4-1_(nakadai).php" method="post">
            <input type="text" name="delete_num" placeholder="�폜�ԍ�"><br>
            <input type="text" name="delete_pass" placeholder="�p�X���[�h"><br>
            <input type="submit" value="�폜">
        </form>
        <form action="mission_4-1_(nakadai).php" method="post">
            <input type="text" name="edit_num" placeholder="�ҏW�ԍ�"><br>
            <input type="text" name="edit_pass" placeholder="�p�X���[�h"><br>
            <input type="submit" value="�ҏW">
        </form>
        <?php
        //�u���E�U�\���p
        foreach((array)$sel_result as $key1 => $val1){
            foreach( $val1 as $key2 => $val2 ){
                echo $val2." ";
            }
            echo "<br>";
        }
        ?>
     </body>
</html>
