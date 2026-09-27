<?php
$password = "";

if (isset($_POST['generate'])) {

    $length = $_POST['length'];

    // Strong character sets
    $lower = 'abcdefghijklmnopqrstuvwxyz';
    $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numbers = '0123456789';
    $symbols = '!@#$%^&*()-_=+[]{}<>?/';

    // Combine all
    $all = $lower . $upper . $numbers . $symbols;

    $password = "";

    // Ensure at least 1 from each type (strong password rule)
    $password .= $lower[random_int(0, strlen($lower) - 1)];
    $password .= $upper[random_int(0, strlen($upper) - 1)];
    $password .= $numbers[random_int(0, strlen($numbers) - 1)];
    $password .= $symbols[random_int(0, strlen($symbols) - 1)];

    // Fill remaining length
    for ($i = 4; $i < $length; $i++) {
        $password .= $all[random_int(0, strlen($all) - 1)];
    }

    // Shuffle final password for randomness
    $password = str_shuffle($password);
}
?>