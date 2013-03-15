<?php
shell_exec('echo 0 > /var/www/PHP/frontend/pages/var.txt');
header("Location: http://localhost/PHP/frontend/index.php?page=monitors&expand=none");
?>
