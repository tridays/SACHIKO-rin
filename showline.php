<?
	header("Content-Type: text/html; charset=utf-8");
	ob_start();
    include 'db.php';

    $num    = $_REQUEST['num'];
	$table = $_REQUEST['table'];

	$item = @$GLOBALS['db']->query("SELECT * FROM `$table` WHERE num='$num'")->fetchAll(PDO::FETCH_ASSOC);
	if(count($item) == 0) die();
	$item = $item[0];
	unset($item['num']);
	// print_r($item);

	$column = $_REQUEST['column'];
	$lang0 = (int)substr($column,0,1);
	$lang1 = ($lang0 == 0) ? 1 : 0;
	$editor = substr($column,2);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link charset="UTF-8" href="font.css" rel="stylesheet" type="text/css" />
<link charset="UTF-8" href="table.css" rel="stylesheet" type="text/css" />
<title>検索結果</title>
</head>
<body>
<table>
	<thead>
		<tr>
		<td width="5%">ID</td>
		<td width="10%">提供者</td>
		<td width="37%">検索結果</td>
		<td width="37%">対応する文章</td>
		<td width="11%">提供者</td>
		</tr>
	</thead>
	<tbody>
	<tr>
	<?
		$cntTrans = -1;
		$value = '';
		$trans = array();
		foreach($item as $key2 => $val) {
			if(substr($key2, -4) != '_err') {
				if((int)substr($key2,0,1) == $lang1) {
					$cntTrans++;
					$trans[] = array(substr($key2, 2), strip_tags($val));
				}else{
					$value = strip_tags($val);
				}
			}
		}
		// print_r($trans);

		echo "<tr column=\"$column\">";
		echo "<td rowspan=\"$cntTrans\">$num</td>";
		echo "<td rowspan=\"$cntTrans\">$editor</td>";
		echo "<td rowspan=\"$cntTrans\" style=\"text-align:left;\">$value</td>";

		echo "<td style=\"text-align:left;\">{$trans[0][1]}</td>";
		echo "<td>{$trans[0][0]}</td></tr>";
		for($n = 1; $n < $cntTrans; $n++) {
			echo '<tr>';
			echo "<td style='text-align:left;'>{$trans[$n][0]}</td>";
			echo "<td>{$trans[$n][0]}</td>";
			echo '</tr>';
		}
	?>
	</tr>
	</tbody>
</table>
</body>
</html>