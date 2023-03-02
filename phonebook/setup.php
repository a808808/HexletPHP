<!DOCTYPE html>
<head>
    <title>Setting up database</title>
</head>
<body>
<h3>Setting up...</h3>

<?php
require_once 'functions.php';

createTable('phone',
    'phone_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              staff_id INT UNSIGNED,
              number VARCHAR(400),
              number_type VARCHAR(400)');

createTable('users',
    'user_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              user_name VARCHAR(400),
              pass VARCHAR(200)');

createTable('staff',
    'staff_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              user_id INT UNSIGNED,
              first_name VARCHAR(400),
              last_name VARCHAR(400)');

?>

<br>...done.
</body>
</html>
