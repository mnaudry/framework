<?php
 $name = $name ?? "no name";
 $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8')
?>
hello <?php echo $name ?>