<?php
require_once 'config.php';

$new_password = '12345';
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// Update/Insert into new table structure
$email = 'admin@jewepe.com';
$name = 'Administrator JeWePe';

$check = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");

if (mysqli_num_rows($check) > 0) {
    $update = mysqli_query($conn, "UPDATE users SET password = '$hashed_password', name = '$name' WHERE email = '$email'");
} else {
    $update = mysqli_query($conn, "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashed_password')");
}

if ($update) {
    echo "<div style='font-family: sans-serif; padding: 20px; border: 2px solid green; border-radius: 10px; max-width: 500px; margin: 50px auto; text-align: center;'>";
    echo "<h2 style='color: green;'>Database Diperbarui ke Skema Baru!</h2>";
    echo "<p>Gunakan kredensial berikut untuk login:</p>";
    echo "<div style='background: #f0f0f0; padding: 10px; border-radius: 5px; margin: 15px 0;'>";
    echo "Email: <b style='color: blue;'>admin@jewepe.com</b><br>";
    echo "Password: <b style='color: blue;'>12345</b>";
    echo "</div>";
    echo "<a href='login.php' style='display: inline-block; padding: 10px 20px; background: blue; color: white; text-decoration: none; border-radius: 5px;'>Kembali ke Login</a>";
    echo "</div>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
