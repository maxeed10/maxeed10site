<html>
<head>
<link rel="stylesheet" href="mon.css" type="text/css" />
</head>
<body>
<?php
	require_once 'servers.php';
	foreach ($servers as $key => $val) {
			$host = $servers[$key]['host'];
			$port = $servers[$key]['port'];
			$name = $servers[$key]['name'];
$socket = @fsockopen($host, $port);
if ($socket !== false) {
@fwrite($socket, "\xFE");
$data = "";
$data = @fread($socket, 1024);
@fclose($socket);
if ($data !== false && substr($data, 0, 1) == "\xFF") {
$info = explode("\xA7", mb_convert_encoding(substr($data,1), "iso-8859-1", "utf-16be"));
$playersOnline = $info[1];
$playersMax = $info[2];
?>
<div class="monitor">
	<div class="monitor_online"><b>
		<?php echo "$playersOnline";?>
	</b></div>
	<div class="monitor_name"><?php echo $name; ?></div>
	<div class="monitor_status">
		<span>
	<div class="green progressbar" style="width: <?php echo $playersOnline; ?>%"></div>
	</span>
	</div>
	</div>
<?php
} else {
?>
	<div class="monitor">
		<div class="monitor_online"><b>X</b></div>
		<div class="monitor_name"><?php echo $name; ?></div>
		<div class="monitor_status">
			<span>
				<div class="red progressbar"></div>
			</span>
		</div>
	</div>
<?php
}
} else { ?>
	<div class="monitor">
		<div class="monitor_online"><b>X</b></div>
		<div class="monitor_name"><?php echo $name; ?></div>
		<div class="monitor_status">
			<span>
				<div class="red progressbar"></div>
			</span>
		</div>
	</div>
<?php
}
	$fullOnline += $playersOnline;
}
$OnlineRecord = @file_get_contents('onlineRecord.txt');
if ($fullOnline > $OnlineRecord) {
	@file_put_contents('onlineRecord.txt', $fullOnline);
}
	if (count($servers) >= 2) {
		?>
		<div class="recordBox">
		<?php
			echo mb_convert_encoding("Общий онлайн - ".$fullOnline."<br />", "windows-1251", "utf-8");
			echo mb_convert_encoding("Рекорд онлайна - ".$OnlineRecord."<br />", "windows-1251", "utf-8");
		}
?>
		</div>		
</body>
</html>
