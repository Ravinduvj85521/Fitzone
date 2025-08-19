<?php
// generate_admin_password.php - RUN ONCE THEN DELETE

$password = '11111111Aa'; // Change this to your desired password
$hashed = password_hash($password, PASSWORD_BCRYPT);

echo "Password: " . $password . "\n";
echo "Hashed: " . $hashed . "\n";

// Copy this hash to your database INSERT query
?>