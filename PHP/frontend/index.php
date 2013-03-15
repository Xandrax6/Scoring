<?php
    require_once(dirname(__FILE__) . '/../common.php');
    require_once(PW2_PATH . '/frontend/GuiHelpers.php');
    require_once(PW2_PATH . '/frontend/FormHelpers.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta http-equiv="content-language" content="en">
		<title>Scoring Engine</title>
		<link rel="stylesheet" type="text/css" href="screen.css">
	</head>
	<body>
		<div id="content">
			<div id="left-column">	
					<h1>Practice CCDC Scoring Engine</h1>			
                <?php require('./pages/' . GuiHelpers::getPage($_GET['page']) . '.php'); ?>
			</div>
            <div class="right-block">
                <div>
                    <p class="side-title">Total Log Entries:</p>
                    <p><?php p(GuiHelpers::getStatistic('log_count')); ?></p>
                    <p class="side-title">Last Offline:</p>
                    <p><?php p(GuiHelpers::formatDateLong(GuiHelpers::getStatistic('last_offline'))); ?></li>
                </div>
                <div class="menu">
                <ul>
                    <li><a href="?page=monitors">Monitors</a></li>
                    <li><a href="?page=contacts">Contacts</a></li>
                </ul>
                </div>
<div class="menu">
        Display: <a href="?page=monitors">Expand All</a> - <a href="?page=monitors&expand=none">Collapse All</a>
</div>
                <div>
                        <ul class="page-menu">
        
            <?php
                FormHelpers::startForm('GET', '?page=monitor');
                FormHelpers::createHidden('page', 'monitor');
            ?>
            <?php
                $options = array();
                foreach($GLOBALS['monitor_types'] as $type)
                {
                    $o = new $type();
                    $options[] = FormHelpers::getOption($o->getName(), $type);
                }
                FormHelpers::createSelect('type', $options);
                FormHelpers::createSubmit('New Monitor');
            ?>
            <?php FormHelpers::endForm(); ?>
        
    </ul>
                </div>
            </div>
<form action="/PHP/frontend/pages/initialize.php" method="get">
  <input type="submit" value="Initialize">
</form>
<form action="/PHP/frontend/pages/start.php" method="get">
  <input type="submit" value="Start">
</form>
<form action="/PHP/frontend/pages/stop.php" method="get">
  <input type="submit" value="Stop">
</form>
		</div>
	</body>
</html>
