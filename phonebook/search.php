<?php include('staff.php');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <style>
        table,
        td {
            border: 1px solid #333;
        }

        thead,
        tfoot {
            background-color: #333;
            color: #fff;
        }
    </style>
    <title>Phone book</title>
</head>
<div data-role='page'>
    <div data-role='header'>
        <div id='logo' class='center'>Phone book</div>
        <div class='username'></div>
    </div>
    <div class='center'>Вернуться на <a href='index.php'>главную страницу</a></div>
    <body>
    <form action="search.php" method="POST" autocomplete="on">
        <div>
            <input
                type="search"
                id="mySearch"
                name="q"
                placeholder="Поиск" />
            <button>Search</button>
        </div>
    </form>

    <?php
    $q = $_SESSION['search'];
    if (isset($q)){
        $record = queryMysql("select pseudo_name.number as number, pseudo_name.number_type as number_type, pseudo_name.concat as concat, pseudo_name.staff_id as staff_id, pseudo_name.first_name as first_name, pseudo_name.last_name as last_name
FROM (SELECT staff.staff_id, number, number_type, CONCAT(first_name, ' ', last_name) as concat, first_name, last_name
FROM `staff` JOIN `phone` ON staff.staff_id = phone.staff_id WHERE user_id='$user_id') AS pseudo_name
WHERE pseudo_name.concat LIKE '". $q ."' OR pseudo_name.number LIKE '". $q ."' ORDER BY pseudo_name.staff_id");

        //$record = queryMysql("SELECT staff.staff_id, first_name, last_name, number, number_type FROM `staff` JOIN `phone` ON staff.staff_id = phone.staff_id WHERE user_id ='$user_id' AND number LIKE '$q' OR first_name LIKE '$q' OR last_name LIKE '$q' ORDER BY staff.staff_id");
        if ($record->rowCount())
        {
            echo "<div>
    <table>
        <thead>
        <tr>
            <th>Staff_id</th>
            <th>ФИ</th>
            <th>Номер</th>
            <th>Ярлык</th>
            <th>Действие</th>
        </tr>
        </thead>
        <tbody>";
            while ($row = $record->fetch()) {
                $temp = $row['staff_id'];
                $tempnum = $row['number'];

                echo "<tr>
                    <td>" . $temp . "</td>
                    <td>" . $row['first_name'] . " " . $row['last_name'] . "</td>
                    <td>" . "+" . $row['number'] . "</td>
                    <td>" . $row['number_type'] . "</td>
                    <td><a href='search.php?del=" . $temp . "&num=" . $tempnum . "'>Удалить</a>
                    <a href='search.php?edit=" . $temp . "&num=" . $tempnum . "'>Изменить</a></td>
                    </tr>";
            }
            echo "</tbody>
    </table>
</div>";
        } elseif (empty($q)) ;
        else echo "По запросу ничего не найдено.";
    }
    ?>

    <div data-role='content'>
        <form action="" method="POST" autocomplete="on">

            <div data-role='fieldcontain'>
                <label>Фамилия</label>
                <input type='text' maxlength='16' name='last_name' value='<?php echo empty($last_name)? "" : $last_name ; ?>'>
            </div>
            <div data-role='fieldcontain'>
                <label>Имя</label>
                <input type='text' maxlength='16' name='first_name' value='<?php echo empty($first_name)? "" : $first_name ; ?>'>
            </div>
            <div data-role='fieldcontain'>
                <label>Номер</label>
                <input type='text' maxlength='16' name='phone_number' value='<?php echo empty($number)? "" : $number ; ?>'>
                <label> Ярлык</label>
                <select name="select">
                    <!--Supplement an id here instead of using 'name'-->
                    <option value="Без ярлыка" <?php echo ($number_type == "Без ярлыка")?" selected":"";?>  >Без ярлыка</option>
                    <option value="Мобильный" <?php echo ($number_type == "Мобильный")?" selected":"";?>  >Мобильный</option>
                    <option value="Рабочий" <?php echo ($number_type == "Рабочий")?" selected":"";?>  >Рабочий</option>
                    <option value="Домашний" <?php echo ($number_type == "Домашний")?" selected":"";?>  >Домашний</option>
                    <option value="Основной" <?php echo ($number_type == "Основной")?" selected":"";?>  >Основной</option>
                    <option value="Рабочий факс" <?php echo ($number_type == "Рабочий факс")?" selected":"";?>  >Рабочий факс</option>
                    <option value="Домашний факс" <?php echo ($number_type == "Домашний факс")?" selected":"";?>  >Домашний факс</option>
                    <option value="Пейджер" <?php echo ($number_type == "Пейджер")?" selected":"";?>  >Пейджер</option>
                    <option value="Другой" <?php echo ($number_type == "Другой")?" selected":"";?>  >Другой</option>
                </select>
            </div>
            <?php if (empty($update)): ?>
                <input data-transition='slide' type='submit' value='Добавить'>
            <?php else: ?>
                <input data-transition='slide' type='submit' name="save" value='Обновить'>
                <input type="hidden" name="staff_id" value="<?php echo empty($staff_id)? "" : $staff_id ; ?>">
            <?php endif; ?>

        </form>
        <br>
    </div>
    <br>
</div>
</body>
</html>