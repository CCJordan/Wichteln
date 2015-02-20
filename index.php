<?php
/**
* @author 	Christopher Jordan
* @date 	10.12.2014
* Ein kleines Script, das die Ziehung von Wichtel zuordnungen über das Internet erlaubt.
* index page, that shows all mailadresses which are collected so far
**/
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel='stylesheet' type='text/css' href='css/display.css'>
		<link rel='stylesheet' type='text/css' href='css/jquery-ui.min.css'>
		<link rel='stylesheet' type='text/css' href='css/jquery-ui.structure.min.css'>
		<link rel='stylesheet' type='text/css' href='css/jquery-ui.theme.min.css'>
		<script type="text/javascript" src='js/jquery.js'></script>
		<script type="text/javascript" src='js/jquery-ui.min.js'></script>
		<script type="text/javascript" src='js/wichteln.js'></script>
	</head>
	<body>
		<div id='content'>
			<?php
				echo $info;
				// Read mail addresses
				$mails = file('wichelMails.txt');
				echo "<div id='mails'>";
				for ($i = 0; $i < sizeof($mails); $i++) {
					$row = $mails[$i];
					$parts = explode("<<->>", $row);
					echo createMailRow( $parts[0], $parts[1], $parts[2], false );
				}
				echo createMailRow(0, "Name", "E-Mail", true);
				echo "</div>";
				// Zuordnen Button
				echo '<button id="zuordnen" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button"><span class="ui-button-text">Zuordnen</span></button>';			
			?>
		</div>
	</body>
</html>

<?php

function createMailRow( $id, $name, $mail, $isLastRow ) {
	return "<div class='row' data-id='" . $id . "'>
		<span class='ui-icon " . ($isLastRow ? "disabled " : "") . "ui-icon-trash'></span>
		<span class='ui-icon ui-icon-disk'></span>
		<input type='text' class='textbox ui-corner-all name' data-default-val='Name' value='" . $name . "'>
		<input type='text' class='textbox ui-corner-all mail' data-default-val='E-Mail' value='" . $mail . "'>
	</div>";
}

?>