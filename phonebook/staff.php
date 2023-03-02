<?php
require_once 'functions.php';
session_start();

if (isset($_SESSION['user']))
{
    $user     = $_SESSION['user'];
    $user_id = $_SESSION['user_id'];
    $loggedin = TRUE;
    $userstr  = "Logged in as: $user";
}
else $loggedin = FALSE;

if (!$loggedin) die("please <a href='signup.php'>sign up</a> or <a href='login.php'>log in</a>");
if ($loggedin) echo "$user, you are logged in";
echo "<div class='center'>for logout, <a href='logout.php'>click here</a>";



$number_type = 'Без ярлыка';
$action = 'view';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(empty($_POST['staff_id']))
        $action = 'create';
    else $action = 'edit';
//    if (!empty($_GET['edit'])) {
////        $action = 'edit';
////    } else if (!empty($_GET['del'])) {
////        $action = 'delete';
////    } else if (!empty($_GET['create'])) {
////        $action = 'create';
////    }
}
elseif (!empty($_GET['del']))
        $action = 'delete';

if(isset($_POST['q'])){
    $action = 'view';
    $update = '';
    if (empty($_POST['q'])) {
        $q = '';
    } else {
        $q = $_POST['q'];
    $q = '%' . $_POST['q'] . '%';
    $_SESSION['search'] = $q;
    ;}
    echo $q;
}



$last_name = $first_name = $phone_number = '';
if ($action === 'create') {

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone_number = sanitizeString($_POST['phone_number']);
    $number_type = $_POST['select'];
    $user_id = $_SESSION['user_id'];


    if ($first_name == "" || $last_name == "" || $phone_number == "")
        $error = 'Not all fields were entered<br><br>';
    else {
        $record = queryMysql("SELECT staff_id FROM `staff` WHERE first_name = '$first_name' AND last_name = '$last_name' AND user_id = '$user_id'");

        if ($record->rowCount()) {
            $record2 = queryMysql("SELECT staff.staff_id, first_name, last_name, number FROM `staff` JOIN `phone` ON staff.staff_id = phone.staff_id WHERE first_name = '$first_name' AND last_name = '$last_name' AND `number` = '$phone_number' AND `user_id` = '$user_id'");
            if (!$record2->rowCount())
            {
            $temp = $record->fetch();
            $temp_id = $temp['staff_id'];
            queryMysql("INSERT INTO phone VALUES(NULL, '$temp_id', '$phone_number', '$number_type')");
            header('Location: index.php', true, 303);
            }
        } else {
            $db = $pdo->prepare("INSERT INTO `staff` SET `first_name` = :first_name, `last_name` = :last_name, `user_id` = :user_id");
            $db->execute(array('first_name' => $first_name, 'last_name' => $last_name, 'user_id' => $user_id));
            $last_id = $pdo->lastInsertId();
            queryMysql("INSERT INTO phone VALUES(NULL, '$last_id', '$phone_number' , '$number_type' )");
            header('Location: index.php', true, 303);
            //die('<h4>Account created</h4>Please Log in.</div></body></html>');
        }
    }
}

if ($action === 'delete')
{
    $remove_id = $_GET['del'];
    $remove_number = $_GET['num'];
    $user_id = $_SESSION['user_id'];
    $record =  queryMysql("SELECT staff.staff_id FROM `staff` JOIN `phone` ON staff.staff_id = phone.staff_id WHERE staff.staff_id = '$remove_id'");
    $num = $record->rowCount();
    if ($num == '1') {
        queryMysql("DELETE FROM phone WHERE staff_id='$remove_id' AND number = '$remove_number'");
        queryMysql("DELETE FROM staff WHERE staff_id='$remove_id' AND user_id = '$user_id'");
    } else queryMysql("DELETE FROM phone WHERE staff_id='$remove_id' AND number = '$remove_number'");

    header( 'Location: index.php', true, 303 );
}

if ($action === 'edit') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone_number = $_POST['phone_number'];
    $staff_id = $_POST['staff_id'];
    $number_type = $_POST['select'];
    $old_num = sanitizeString($_GET['num']);
    $user_id = $_SESSION['user_id'];



    if ($first_name == "" || $last_name == "" || $phone_number == "")
        $error = 'Not all fields were entered<br><br>';
    else
    {
        $db = $pdo->prepare("UPDATE `staff` SET `first_name` = :first_name, `last_name` = :last_name WHERE `staff_id` = :staff_id AND `user_id` = :user_id");
        $db->execute(array('first_name' => $first_name, 'last_name' => $last_name, 'staff_id' => $staff_id, 'user_id' => $user_id));
        $db = $pdo->prepare("UPDATE `phone` SET `number` = :number, `number_type` = :number_type WHERE `staff_id` = :staff_id AND `number` = :old_num");
        $db->execute(array('number' => $phone_number, 'number_type' => $number_type , 'staff_id' => $staff_id, 'old_num' => $old_num));
        if ($_SESSION['search'] == '') header( 'Location: index.php', true, 303 );
        else header( 'Location: search.php', true, 303 );
        //die('<h4>Account created</h4>Please Log in.</div></body></html>');
    }
}

if (!empty($_GET['edit'])) {
    $id = $_GET['edit'];
    $old_num = sanitizeString($_GET['num']);
    $update = true;
    $record = queryMysql("SELECT staff.staff_id, first_name, last_name, number, number_type FROM `staff` JOIN `phone` ON staff.staff_id = phone.staff_id WHERE staff.staff_id = '$id' AND `number` = '$old_num'");
    while ($row = $record->fetch()) {
        $staff_id = $row['staff_id'];
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $number = $row['number'];
        $number_type = $row['number_type'];
    }
}
