<?php
$email = $_POST['email'];

$token = bin2hex(random_bytes(16));
$token_hash = hash("sha256", $token);

$expire = date("Y-m-d H:i:s", time() + 60 * 5);

require_once connect();
?>