<?php
shell_exec('echo 9 > /var/www/PHP/frontend/pages/var.txt');
shell_exec('echo "truncate table statistics;" | mysql -u root -ptoor phpwatch');
shell_exec('echo "update monitors set status = 5;" | mysql -u root -ptoor phpwatch');
header("Location: http://localhost/PHP/frontend/index.php?page=monitors&expand=none");
?>
