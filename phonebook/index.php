<?php include('staff.php');
$_SESSION['search'] = '';
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
<body>
        <form action="search.php?" method="POST" autocomplete="on">
            <div>
                <input
                        type="search"
                        id="mySearch"
                        name="q"
                        placeholder="Поиск" />
                <button>Search</button>
            </div>
        </form>
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
<div>
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
        <tbody>
        <?php
        $query = "SELECT staff.staff_id, first_name, last_name, number, number_type FROM `staff` JOIN `phone` ON staff.staff_id = phone.staff_id WHERE user_id = '$user_id' ORDER BY staff.staff_id";
        $result = queryMysql($query);
        $prestuffid = '';
        //        $num    = $result->rowCount();

        while ($row = $result->fetch()) {
            $temp = $row['staff_id'];
            $tempnum = $row['number'];
            $rfirst_name = $row['first_name'];
            $rlast_name = $row['last_name'];
            if ($prestuffid == $temp)
            {
                $rfirst_name = '';
                $rlast_name = '';
            }

            echo "<tr>
                    <td>" . $temp . "</td>
                    <td>" . $rfirst_name . " " . $rlast_name . "</td>
                    <td>" . "+" . $row['number'] . "</td>
                    <td>" . $row['number_type'] . "</td>
                    <td><a href='index.php?del=" . $temp . "&num=" . $tempnum . "'>Удалить</a>
                    <a href='index.php?edit=" . $temp . "&num=" . $tempnum . "'>Изменить</a></td>
                    </tr>";
            $prestuffid = $row['staff_id'];
        }
        ?>
        </tbody>
    </table>
</div>
</body>
</html>