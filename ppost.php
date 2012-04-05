<?php
unset($chat_color);
$a = session_id();
if(empty($a)) session_start();
if(!isset($_SESSION['uid'])){
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL=index.php">';
	exit;
}

function parse_links($text)
{

        $text = html_entity_decode($text);
        $text = " ".$text;
        $text = eregi_replace('(((f|ht){1}tp://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)',
                '<a href="\\1" target=_blank>\\1</a>', $text);
        $text = eregi_replace('(((f|ht){1}tps://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)',
                '<a href="\\1" target=_blank>\\1</a>', $text);
        $text = eregi_replace('([[:space:]()[{}])(www.[-a-zA-Z0-9@:%_\+.~#?&//=]+)',
        '\\1<a href="http://\\2" target=_blank>\\2</a>', $text);
        $text = eregi_replace('([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})',
        '<a href="mailto:\\1" target=_blank>\\1</a>', $text);
        return $text;
} 

	date_default_timezone_set("America/New_York");
	$uid = $_SESSION['uid'];
	$name = $_SESSION['name'];
	$logfile = $_POST['logfile'];
	
	$chat_color = $_SESSION['chat_color'];
	
	$time = date("g:i a");
	$file = $logfile;
    $lines = count(file($file)); 
	$text = trim(strip_tags($_POST['text']));
	$text = parse_links($text);
		if(strlen($text) <= 1){
		  exit();	 
		} 
	$text = PIPHP_ReplaceSmileys($text, 'smileys/');

   if($lines > 150 || $text=="##clear"){
		$data = file($file);
		$line = array();
		$j = 0;
		for($i = 50; $i > 0; $i--){
	    $line[$j] = $data[count($data)-$i];
		$j++;
		}
		$fh = fopen($logfile, 'w');
		for($i = 0; $i < 50; $i++){
		fwrite($fh, $line[$i]);
		}
		fclose($fh);
	 }
	
	if ($text=="##clear") exit();

		$line = "<div class=\"msgln sex".$chat_color."\">";
		$line .= " <span class=\"span3\">$time</span>";
		$line .= " <span class=\"span2\"><a href=\"javascript: sendName('$name')\">$name</a><br />".stripslashes($text)."</span>";
		$line .= " </div>\r\n";
		
		$fp = fopen($logfile, 'a');
		fwrite($fp, $line);
		fclose($fp);

?>


<?php
function PIPHP_ReplaceSmileys($text, $folder)
{
   $chars = array('>:-(', '>:(', 'X-(',  'X(',
                  ':-)*', ':)*', ':-*',  ':*', '=*',
                  ':)',   ':]', '=)',
                  ':-)',  ':-]',
                  ':(',   ':C',   ':[', '=(',
                  ':-(',  ':\'(', ':_(',
                  ':O',   ':-O',
                  ':P',   ':b',   ':-P', ':-b',
                  ':D',   'XD',
                  ';)',   ';-)',
                  ':/ ',   ':\\',  ':-/', ':-\\',
                  ':|',
                  'B-)',  'B)',
                  'I-)',  'I)',
                  ':->',  ':>',
                  ':X',   ':-X',
                  '8)',   '8-)',
                  '=-O',  '=O',
                  'O.o',  ':S',   ':-S',
                  '*-*',  '*_*',
				  'x*x');

   $gifs = array( 'angry',   'angry',   'angry',  'angry',
                  'kiss',    'kiss',    'kiss',   'kiss', 'kiss',
                  'smiley',  'smiley', 'smiley',
                  'happy',   'happy',
                  'sad',     'sad',     'sad',	'sad',
                  'cry',     'cry',     'cry',
                  'shocked', 'shocked',
                  'tongue',  'tongue',  'tongue', 'tongue',
                  'laugh',   'laugh',
                  'wink',    'wink',
                  'uneasy',  'uneasy',  'uneasy', 'uneasy',
                  'blank',
                  'cool',    'cool',
                  'sleep',   'sleep',
                  'sneaky',  'sneaky',
                  'blush',   'blush',
                  'wideeye', 'wideeye',
                  'uhoh',    'uhoh',
                  'puzzled', 'puzzled', 'puzzled',
                  'dizzy',   'dizzy',
				   'xmas');

   if (substr($folder, -1) == '/')
      $folder = substr($folder, 0, -1);

   for ($j = 0 ; $j < count($gifs) ; ++$j)
      $gifs[$j] = "<img src='$folder/$gifs[$j].gif' " .
         "width='15' height='15' border='0' alt='$gifs[$j]' " .
         "title='$gifs[$j]' />";

   return str_ireplace($chars, $gifs, $text);
}	


?>