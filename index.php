<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Поля таблиц</title>
</head>
<body>
<form method="post">
        <p><input type="text" name="table_name" value="" pattern="[A-Za-z_]+$" /> Название таблицы латиницей</p>
        <p><input type="submit" name="create_table" value="Создать таблицу" /></p>
</form>
</body>
</html>
<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$pdo = new PDO("mysql:host=localhost;dbname=netology01; charset=utf8","admin","1qa2ws3ed");
if (isset($_POST['create_table'])){
    $data = [
        'table_name' => $_POST["table_name"]
    ];
    $new_table = $_POST["table_name"];
    $sql = "CREATE TABLE ".$new_table." (
        id int(11) NOT NULL AUTO_INCREMENT,
        PersonID int,
        LastName varchar(255),
        FirstName varchar(255),
        Address varchar(255),
        City varchar(255),
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    $stmt= $pdo->prepare($sql);
    $stmt->execute();
}
$sql = "SHOW TABLES";
$stmt= $pdo->prepare($sql);
$stmt->execute();
$tables_names = $stmt->fetchAll();
?>
<form name="new" method="post">
        <?php foreach ($tables_names as $tables_name){ ?>
        <p><input type="radio" name="table_name_from_base" id="<?php echo $tables_name[0]?>"  value="<?php echo $tables_name[0]?>"  /> <label for="<?php echo $tables_name[0]?>"> <?php echo $tables_name[0]?></label></p>
        <?php } ?>
        <p><input type="submit" name="show_table" value="Показать таблицу" /></p>
</form>
<?php
if (isset($_POST['show_table'])){
    $sql = "DESCRIBE ".$_POST['table_name_from_base'];
    $stmt= $pdo->prepare($sql);
    $stmt->execute();
    $tables_fields = $stmt->fetchAll();
    ?>
    <form name="new1" method="post">
        <table width="60%" border="1" >
    <tr>
        <th width='30'>удалить</th>
        <th>Название поля</th>
   	    <th>Тип поля</th>
    </tr>
    <input type='text' hidden name="table_name" value="<?php echo $_POST['table_name_from_base'] ?>" />
    <?php
    foreach($tables_fields as $tables_field){
        echo "<tr>

                <td><input type='radio' name='delete_row' value=".$tables_field['Field']." /></td>
                <td>".$tables_field['Field']."  <input type='text' name='name[".$tables_field['Field']."]' value=".$tables_field['Field']." /></td>
                <td>".$tables_field['Type']."  <input type='text' name='type[".$tables_field['Field']."]' value=".$tables_field['Type']." /></td>
                <td><input type='text' name='test_of_filed' value='' /></td>
                </tr>";
    }
    echo '</table>
    <p><input type="submit" name="change_fields" value="Изменить поле" /></p>
    </form>';
}
if (isset($_POST['change_fields'])){
    foreach ($_POST['name'] as $key => $value){
        $name = $key;
        $type = $_POST['type'][$key];
        if ($value and $key and $type) {
        $sql = "ALTER TABLE ".$_POST['table_name']." CHANGE ".$name." ".$value." ".$type." ";
        $stmt= $pdo->prepare($sql);
        $stmt->execute();
    }
    }
    if (isset($_POST['delete_row'])){
        $sql = "ALTER TABLE ".$_POST['table_name']." DROP ".$_POST['delete_row'];
        $stmt= $pdo->prepare($sql);
        $stmt->execute();
    }
}
?>
</body>
</html>