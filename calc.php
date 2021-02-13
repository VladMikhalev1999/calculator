<?php 
	date_default_timezone_set('Asia/Yekaterinburg');

	include "mathCalc.php";
	include "square.php";
	include "log.php";

	$p = "";
	$result = "";
	if (isset($_REQUEST['pattern'])) {
		$p = $_REQUEST['pattern'];
		$p = str_replace("^", "+", $p);
		$p = str_replace("@", "%", $p);
		$rpnf = new PRNChecker($p);
		$result = $rpnf->check();
		if (is_array($result)) {
			$calc = new Calculator($result);
			$result = $calc->calculate();
			if (is_array($result)) {
				$result = $result[0];
				writeLog($p . "\t" . $result);
			} else {
				$lastError = $result;
				writeLog($p . "\t" . $lastError);
			}
		} else {
			$lastError = $result;
			writeLog($p . "\t" . $lastError);
		}
		writeLog();
	} else if (isset($_REQUEST["a"])) {
		$a = $_REQUEST["a"]; $b = $_REQUEST["b"]; $c = $_REQUEST["c"];
		$a = str_replace("^", "+", $a); $a = str_replace("@", "%", $a);
		$b = str_replace("^", "+", $b); $b = str_replace("@", "%", $b);
		$c = str_replace("^", "+", $c); $c = str_replace("@", "%", $c);
		$rpnf = array(); $sq = array(); $i = 0;
		$abc = [$a, $b, $c];
		array_push($rpnf, new PRNChecker($a));
		array_push($rpnf, new PRNChecker($b));
		array_push($rpnf, new PRNChecker($c));
		foreach ($rpnf as $s) {
			$result = $s->check();
			if (is_array($result)) {
				$calc = new Calculator($result);
				$result = $calc->calculate();
				if (is_array($result)) {
					array_push($sq, $result[0]);
					writeLog($abc[$i] . "\t" . $result[0]);
					$result = $result[0];
				} else {
					$lastError = $result;
					writeLog($abc[$i] . "\t" . $lastError);
				}
			} else {
				$lastError = $result;
				writeLog($abc[$i] . "\t" . $lastError);
			}
			$i++;
		} 
		if (count($sq) == 3) {
			$sqEqCalc = new SquareEquationCalculator($sq);
			$result = $sqEqCalc->calculate();
			if (is_array($result)) {
				$result = "x1 = " . $result['1'] . " ; x2 = " . $result['2'];
				writeLog("a = " . $sq[0] . " ; b = " . $sq[1] . " ; c = " . $sq[2] . "\t\t" . $result);
			} else {
				$lastError = $result;
				writeLog($lastError);
			}
		}
		writeLog();
	}
?>

<html lang='ru'>
	<head>
		<title>Калькулятор</title>
		<meta charset='utf-8'/>
		<link href='style.css' rel='stylesheet' />
		<script src='script.js'></script>
	</head>
	<body>
		<div class="main">
			<p>Выражение <input type="text" id='pattern' name='pattern' value="<?= $p ?>"/></p>
			<p>Результат <input style='margin-left: 13px; font-weight: 900;' type="text" id='expRes' disabled value="<?= $result ?>" /></p>
			<div style='border: 1px solid black; border-radius: 15px; padding: 10px;'>
				<p style='margin: 0;'>Редактирование</p>
				<div style='float:left'>
					<button class='static'>(</button>
					<button class='static'>)</button>
				</div>
				<div style='float:right;'>
					<button id='delLast'>Стереть</button>
					<button id='delAll'>Сброс</button>
				</div>
				<div class='clear'></div>
			</div>
			<div class='calc'><div style='float: left;'>
				<p>
					<button class='static'>1</button>
					<button class='static'>2</button>
					<button class='static'>3</button>
					<button class='static'>/</button>
				</p>
				<p>
					<button class='static'>4</button>
					<button class='static'>5</button>
					<button class='static'>6</button>
					<button class='static'>*</button>
				</p>
				<p>
					<button class='static'>7</button>
					<button class='static'>8</button>
					<button class='static'>9</button>
					<button class='static'>-</button>
				</p>
				<p>
					<button id='unar'>+/-</button>
					<button class='static'>0</button>
					<button id='mod'>mod</button>
					<button class='static'>+</button>
				</p>
			</div>
			<div style='float: right;'>
				<p><button id='sqA'>A</button></p>
				<p><button id='sqB'>B</button></p>
				<p><button type='submit' id='sqC'>C</button></p>
				<p><button id='equal' type='submit' onclick='send_pattern()'>=</button></p>
			</div>
			<div class="clear"></div>
			</div>
		</div>
	</body>
</html>