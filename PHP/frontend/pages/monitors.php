<head>
<script type="text/javascript">
function prog_bar(cur_val,min_val,max_val,width,height,border,fill,bar)
{
var str = "",res = 0;
if(cur_val>=min_val && cur_val<=max_val)
{
  if(min_val<max_val)
  {
    res = ((cur_val-min_val)/(max_val-min_val))*100;
    res = Math.floor(res);
  }
  else
  {
    res = 0;
  }
}
else
{
  res = 0;
}
str = str + "<div style=\"border:"+border+" solid thin; width:"+width+"px; height:"+height+"px;\">";
str = str + "<div style=\"background-color:"+fill+"; width:"+res+"%; height:"+height+"px;\">";
str = str + "Uptime:" + res + "%</div></div>";
document.getElementById(bar).innerHTML = str;
}
</script>
</head>
<?php
$count = file_get_contents('var.txt', FILE_USE_INCLUDE_PATH);
switch ($count)
{
case 0:
file_put_contents('var.txt',1,FILE_USE_INCLUDE_PATH);
header("Refresh: 10; url=http://localhost/PHP/frontend/index.php?page=monitors&expand=none&query=24");
break;
case 1:
file_put_contents('var.txt',2,FILE_USE_INCLUDE_PATH);
header("Refresh: 10; url=http://localhost/PHP/frontend/index.php?page=monitors&expand=none&query=23");
break;
case 2:
file_put_contents('var.txt',3,FILE_USE_INCLUDE_PATH);
header("Refresh: 10; url=http://localhost/PHP/frontend/index.php?page=monitors&expand=none&query=21");
break;
case 3:
file_put_contents('var.txt',4,FILE_USE_INCLUDE_PATH);
header("Refresh: 10; url=http://localhost/PHP/frontend/index.php?page=monitors&expand=none&query=20");
break;
case 4:
file_put_contents('var.txt',5,FILE_USE_INCLUDE_PATH);
header("Refresh: 10; url=http://localhost/PHP/frontend/index.php?page=monitors&expand=none&query=22");
break;
case 5:
file_put_contents('var.txt',6,FILE_USE_INCLUDE_PATH);
header("Refresh: 10; url=http://localhost/PHP/frontend/index.php?page=monitors&expand=none&query=18");
break;
case 6:
file_put_contents('var.txt',7,FILE_USE_INCLUDE_PATH);
header("Refresh: 10; url=http://localhost/PHP/frontend/index.php?page=monitors&expand=none&query=17");
break;
case 7:
file_put_contents('var.txt',8,FILE_USE_INCLUDE_PATH);
header("Refresh: 10; url=http://localhost/PHP/frontend/index.php?page=monitors&expand=none&query=16");
break;
case 8:
file_put_contents('var.txt',0,FILE_USE_INCLUDE_PATH);
header("Refresh: 10; url=http://localhost/PHP/frontend/index.php?page=monitors&expand=none&query=15");
break;
default:
file_put_contents('var.txt',9,FILE_USE_INCLUDE_PATH);
header("Refresh: 600; url=http://localhost/PHP/frontend/index.php?page=monitors&expand=none");
}
?>
<?php 
    if(intval($_GET['query']))
    {
        $m = Monitor::fetch(intval($_GET['query']));
        $m->poll();
        $m->saveToDb();
    }
?>
<div class="section">
    <h1>Monitors</h1>
    <?php foreach(GuiHelpers::getMonitors() as $monitor) : ?>
    <?php
        switch($monitor->getStatus())
        {
            case STATUS_ONLINE :
                p('<h2 class="online">Online</h2>');
                break;
            case STATUS_OFFLINE :
                p('<h2 class="offline">Offline</h2>');
                break;
            case STATUS_PAUSED :
                p('<h2 class="waiting">Paused</h2>');
                break;
            case STATUS_DOWNTIME :
                p('<h2 class="waiting">Scheduled Downtime</h2>');
                break;
            case STATUS_UNPOLLED :
                p('<h2 class="waiting">Unpolled</h2>');
                break;
        }
    ?>
    <div class="info">
        <div class="right">
            <a href="?page=monitor&id=<?php p($monitor->getId()); ?>">Edit</a> -
            <a href="?page=monitor-delete&id=<?php p($monitor->getId()); ?>">Delete</a> - 
            <a href="?page=monitors&query=<?php p($monitor->getId()); ?>">Re-query</a>
            <?php
                if(!GuiHelpers::isExpanded($monitor->getId())) :
            ?>
                - <a href="?page=monitors&expand=<?php p($monitor->getId()); ?>">Expand</a>
            <?php
                endif;
            ?>
        </div>
        <span style="font-size:25px;"><?php $monitor->getAlias() ? p('' . $monitor->getAlias() . ' - ') : ''; ?><?php p($monitor->getHostname()); ?>:<?php p($monitor->getPort()); ?></span>
	<?php
            list($total, $week, $day) = Statistics::get('monitor' . $monitor->getId())
        ?>
        <li><strong>Last Query:</strong>
        <?php 
            if($monitor->getStatus() == STATUS_UNPOLLED)
                p('N/A');
            else
                p(GuiHelpers::formatDateLong($monitor->getLastQuery()));
        ?>
        </li>
	<?php $divbar='prog_bar' . $monitor->getId();?>	
	<div id="<?php echo $divbar; ?>">
	</div>
	<script type="text/javascript">
	var amount = <?php echo json_encode(round(100 * $total['online'] / $total['total'], 2)); ?>;
	var divbar = <?php echo json_encode($divbar);?>;
var percentColors = [
    { pct: 0.0, color: { r: 0xff, g: 0x00, b: 0 } },
    { pct: 0.5, color: { r: 0xff, g: 0xff, b: 0 } },
    { pct: 1.0, color: { r: 0x00, g: 0xff, b: 0 } } ];

var getColorForPercentage = function (pct) {
        for (var i = 0; i < percentColors.length; i++) {
            if (pct <= percentColors[i].pct) {
                var lower = percentColors[i - 1] || {
                    pct: 0.1,
                    color: {
                        r: 0x0,
                        g: 0x00,
                        b: 0
                    }
                };
                var upper = percentColors[i];
                var range = upper.pct - lower.pct;
                var rangePct = (pct - lower.pct) / range;
                var pctLower = 1 - rangePct;
                var pctUpper = rangePct;
                var color = {
                    r: Math.floor(lower.color.r * pctLower + upper.color.r * pctUpper),
                    g: Math.floor(lower.color.g * pctLower + upper.color.g * pctUpper),
                    b: Math.floor(lower.color.b * pctLower + upper.color.b * pctUpper)
                };
                return 'rgb(' + [color.r, color.g, color.b].join(',') + ')';
            }
        }
}	
	barcolor=getColorForPercentage(amount/100);
	prog_bar(amount,0,100,200,15,"#000000",barcolor,divbar);
	</script>
    </div>
    <?php
            if(GuiHelpers::isExpanded($monitor->getId())) :
    ?>
    <ul class="information">
        <li><strong>Contacts:</strong>
        <?php
            $contacts = GuiHelpers::getContactsByMonitor($monitor);
            if(is_array($contacts)) :
                foreach($contacts as $i => $c) :
                    if($i > 0) : p(', '); endif;
                    p('<a href="?page=contact&id=' . $c['id'] . '">' . $c['name'] . '</a>');
                endforeach;
            else :
                p('None');
            endif;
        ?>
        <li><strong>Last Query:</strong>
        <?php 
            if($monitor->getStatus() == STATUS_UNPOLLED)
                p('N/A');
            else
                p(GuiHelpers::formatDateLong($monitor->getLastQuery()));
        ?>
        </li>
        <?php
            list($total, $week, $day) = Statistics::get('monitor' . $monitor->getId())
        ?>
        <li>
            <strong>Uptime (Day):</strong> <?php p($day['total'] > 0 ? round(100 * $day['online'] / $day['total'], 2) . '% (' .
            $day['online'] . ' of ' . $day['total'] . ')' : 'N/A'); ?>
        </li>
        <li>
            <strong>Uptime (Week):</strong> <?php p($week['total'] > 0 ? round(100 * $week['online'] / $week['total'], 2) . '% (' .
            $week['online'] . ' of ' . $week['total'] . ')' : 'N/A'); ?>
        </li>
        <li>
            <strong>Uptime (All):</strong> <?php p($total['total'] > 0 ? round(100 * $total['online'] / $total['total'], 2) . '% (' .
            $total['online'] . ' of ' . $total['total'] . ')' : 'N/A'); ?>
        </li>
    </ul>
    <?php
            endif;
        endforeach;
    ?>
</div>
