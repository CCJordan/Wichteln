<?php
	/**
	* @author 	Christopher Jordan
	* @date 	10.12.2014
	* Ein kleines Script, das die Ziehung von Wichtel zuordnungen über das Internet erlaubt.
	* ajax responder to answer ajax calls from the UI
	**/
	error_reporting(0);

	$mails = file('wichelMails.txt');
	switch ($_POST['action']) {
		case 'save':
			// check request
			if ( $_POST['id'] != ""
			  && $_POST['name'] != ""
			  && $_POST['mail'] != "" ) {
				// Check if ID already exists			
				$file = fopen('wichelMails.txt', "w");
				$found = false;
				for ($i = 0; $i < sizeof($mails); $i++) {
					$row = $mails[$i];
					$parts = explode("<<->>", $row);
					if ($parts[0] == $_POST['id']) {
						$found = true;
						$parts[1] = $_POST['name'];
						$parts[2] = $_POST['mail'];
						$parts[3] = "";
						$row = implode("<<->>", $parts) . "<<->>";
					}
					echo $row;
					fwrite( $file, $row );
				}
				if ( !$found ) {
					$parts[0] = $parts[0] + 1;
					$parts[1] = $_POST['name'];
					$parts[2] = $_POST['mail'];
					unset($parts[3], $parts[4]);
					$row = implode("<<->>", $parts) . "<<->>";
					echo $row;
					fwrite( $file, $row . "\n" );	
				}
				fclose($file);
				echo '{"result": "ok"}';
			}
			break;
		case 'delete':
			if ( $_POST['id'] != "" ) {
				$file = fopen('wichelMails.txt', "w");
				for ($i = 0; $i < sizeof($mails); $i++) {
					$row = $mails[$i];
					$parts = explode("<<->>", $row);
					if ($parts[0] != $_POST['id'] && $parts[0] != "") {
						fwrite( $file, $row );
					}
				}
				fclose($file);
				echo '{"result": "ok"}';
			}
			break;
		case 'zuordnen':
			$names = Array();
			for ($i = 0; $i < sizeof($mails); $i++) {
				$row = $mails[$i];
				$parts = explode("<<->>", $row);
				$names[] = $row;
			}
			$killcount = 100;

			do {
				$failed = false;
				$giver = $names;
				$given = $names;
				while (count($giver) > 0 && !$failed && $killcount > 0) {
					do {
						$killcount--;
						$rnd1 = rand(0, count($giver) - 1);
						$rnd2 = rand(0, count($given) - 1);
					} while( ($giver[$rnd1] == $given[$rnd2] 
						|| $given[$rnd2] == "empty" 
						|| $giver[$rnd1] == "empty")
						&& count($giver) > 1 && $killcount > 0);
					$wichtel[ $given[$rnd2] ] = $giver[$rnd1];
					if ($giver[$rnd1] == $given[$rnd2] 
						|| $given[$rnd2] == "empty" 
						|| $giver[$rnd1] == "empty") {
						$failed = true;
					}
					unset($giver[$rnd1]);
					$giver = array_values($giver);
					unset($given[$rnd2]);
					$given = array_values($given);
				}
			} while ($failed && $killcount > 0);
			foreach ($wichtel as $geber => $nehmer) {
				list(, $gName, $gMail ) = explode("<<->>", $geber);
				$nName = explode("<<->>", $nehmer)[1];

				sendMail($gName, $gMail, $nName);
			}
			
			break;
		default:
			echo '{"result": "error"}';
			break;
	}

	function sendMail( $name, $mail, $beschenkter ) {
        $headers = "From: Nikolausbehörde <nikolausbehörde@ccjordan.de>\n";
        $headers .= "Content-Transfer-Encoding: 8bit\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\n";
        $subject = 'Wichteln';
        $messageBody = "Hallo $name,

dir wurde $beschenkter zum bewichteln zugeordnet.

Viel Spaß beim Wichteln,

Die Nikolausbehörde";
        if(!mail($mail, $subject, $messageBody, $headers)){
                echo 'mail failed';
        } else {
                echo 'mail sent';
        }
	}

?>