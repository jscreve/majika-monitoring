<?php
// Inclusion des identifiants transmis à l’ouverture de votre compte ($ws_url_base, $ws_user,$ws_pass, $ws_serviceid)

function urlencode_array($array_args) {
      if (! is_array($array_args) )
      return false;
      $pairs=array();
	
foreach ($array_args as $k=>$v) {
          $pairs[] = "$k=" . rawurlencode($v);
      }
	
     return implode($pairs, "&");
	
      }


function message_prepare ($numero, $type_message){
	

$ws_url_base = "http://smswebservices.public.mtarget.fr/SmsWebServices/ServletSms";
$ws_user = "majika"; // Login
$ws_pass = "MHAtUd7O"; // Password
$ws_serviceid = "28283"; // ServiceId
$NUM_TEL_TEST= $numero ; // Numéro de destination
$message="";


switch($type_message){
		
	case 1:
		$message="Alerte: Température du local Onduleur est trop chaud déclenché le ventilo.";
		break;
	case 2:
		$message="Alerte: Température du local Batterie est trop chaud déclenché le ventilo Danger.";
		break;
	case 3:
		$message="Alerte: Le Batterie on atteint leur niveau charge minimal, D’éclanche le Groupe-Électrogène apartir de 18h00.";
		break;
	case 4:
		$message="Alerte: Perte de communication avec l’Onduleur.";
		break;
	case 5:
		$message="Alerte: Onduleur en mode Bypass.";
		break;
	case 6:
		$message="Alerte: Panne de l’Onduleur.";
		break;
	default:
		break;
		
}
// Fonction utilitaire pour sérialiser un tableau d'arguments en une chaine compatible HTTP GET


// Paramétrage de l'envoi


$args=array(
// Authentification
'username' => $ws_user,
'password' => $ws_pass,
'serviceid' => $ws_serviceid,
'method' => 'sendTextExtended', // exemple d'envoi d'un SMS, methode Extended
'encoding' => 'UTF-8', 
	
	/* Encodage des caractères des paramètres de ce tableau
Depends de votre environnement serveur
Par defaut : Windows-1252
Préconisé : UTF-8
Supportés : ISO-8859-1 ISO-8859-15 US-ASCII (non exhaustif) */
	
'destinationAddress' => $NUM_TEL_TEST, // Destinataire du SMS, numéro au format internationnal : +33600000000 en France
'msgtext' => $message,
'originatingAddress' => '00000', // Uniquement pour PushAlias
'operatorid' => '0', // ne pas changer
'paycode' => '0', // ne pas changer
'extended' => 'OADC.alphanum=MAJIKA' //remplacer Mtarget par l'émetteur souhaité  MAJIKA
);

//http://plkt.fr/index.php/hacks/77-pratique-php-curl.html

$CURL=curl_init();
$options=array( // Tableau contenant les options de téléchargement
CURLOPT_URL => $ws_url_base,
CURLOPT_RETURNTRANSFER => true, // Retourner le contenu téléchargé dans une chaine (au lieu de l'afficher directement)
CURLOPT_HEADER => false,  // Ne pas inclure l'entête de réponse du serveur dans la chaine retournée
CURLOPT_POST => true,
CURLOPT_POSTFIELDS => urlencode_array($args)
);
curl_setopt_array($CURL,$options);
$reply_body=curl_exec($CURL);
curl_close($CURL);
//var_dump($reply_body);
}

?>
