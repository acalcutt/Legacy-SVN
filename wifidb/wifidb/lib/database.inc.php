<?php
		error_reporting(E_ALL|E_STRICT);
#		error_reporting(E_ALL);
#		error_reporting(E_WARNING);
#		error_reporting(E_ERROR);
global $ver, $full_path, $half_path, $dim, $theme, $UPATH;
$dim = "/";
$ver = array(
            "wifidb"            =>	" *Alpha* 0.21 Build 1 ",
            "codename"          =>	"Peabody",
            "Last_Core_Edit"    => 	"2011-Mar-21",
            "database"          =>	array(
                                    "apfetch"		=>	"2.7.0",
                                    "check_gps_array"	=>	"1.2",
                                    "all_users"		=>	"1.3",
                                    "users_lists"	=>	"1.3",
                                    "user_ap_list"	=>	"1.3",
                                    "all_users_ap"	=>	"1.3",
                                    "exp_kml"		=>	"3.6.0",
                                    "exp_vs1"		=>	"1.1.0",
                                    "exp_gpx"		=>	"1.0.0",
                                    "convert_dm_dd"	=>	"1.3.1",
                                    "convert_dd_dm"	=>	"1.3.1",
                                    "convert_vs1"	=>	"1.2",
                                    "manufactures"	=>	"1.0",
                                    "gen_gps"		=>	"1.0",
                                    "exp_newest_kml"	=>	"1.0",
                                    "table_exists"	=>	"1.0"
                                    ),
            "Daemon"	=>	array(
                                    "daemon_kml"            =>	"1.1",
                                    "getdaemonstats"        =>	"1.1",
                                    "getdbdaemonstats"      =>	"1.1",
                                    "getperfdaemonstats"    =>	"1.1",
                                    "getgeonamestats"       =>	"1.1",
                                    "daemon_full_db_exp"    =>	"1.0",
                                    "daemon_daily_db_exp"   =>	"1.0"
                                    ),
            "Misc"		=>	array(
                                        "breadcrumb"            =>	"1.1",
                                        "smart_quotes"          => 	"1.0",
                                        "smart"                 => 	"1.0",
                                        "Manufactures-list"     => 	"2.0",
                                        "Languages-List"        =>	"1.0",
                                        "make_ssid"             =>	"1.1",
                                        "verbosed"              =>	"1.2",
                                        "logd"                  =>	"1.2",
                                        "sql_type_mail_filter"  => "1.0",
                                        "mail_validation"       =>	"1.0",
                                        "mail_users"            =>	"1.0",
                                        "dump"                  =>	"1.0",
                                        "get_set"               =>	"1.0",
                                        "getTZ"                 =>	"1.0",
                                        "parseArgs"             =>	"1.0",
                                        "tar_file"              =>	"1.1",
                                        "my_caches"             =>	"1.0",
                                        "login_bar"             =>	"1.0",
                                        "user_panel_bar"        =>	"1.0",
                                        ),
            "Themes"	=>	array(
                                        "pageheader"    =>  "1.2",
                                        "footer"        =>  "1.2"
                                        ),
            );


#-------------------------------------------------------------------------------------#
#----------------------------------  Get/Set values  ---------------------------------#
#-------------------------------------------------------------------------------------#
if(@$screen_output=="CLI")
{
    $config_loc = $GLOBALS['wifidb_install'].'lib/config.inc.php';
}else
{
    $config_loc = 'config.inc.php';
}
#echo $config_loc."\r\n";
if(!@include($config_loc))
{
	if(@$GLOBALS['install'] != "installing")
	{
		echo '<h1>There was no config file found. You will need to install WiFiDB first.<br> Please go <a href="/'.$GLOBALS['root'].'/install/index2.php">/[WiFiDB]/install/index2.php</a> to do that.</h1>';
		die();
	}
}else
{
	$sql = "SELECT `id` FROM `".$GLOBALS['db']."`.`user_info`";
	if(!@mysql_query($sql, $conn))
	{
		$cwd = getcwd().'/';
		$gen_cwd = $_SERVER['DOCUMENT_ROOT'].$GLOBALS['root'].'/install/upgrade/';
	#	echo $gen_cwd."<BR>".$cwd."<BR>";
		if($cwd != $gen_cwd)
		{
			echo '<h1>The database is still in an old format, you will need to do an upgrade first.<br> Please go <a href="/'.$GLOBALS['root'].'/install/upgrade/index.php">/[WiFiDB]/install/upgrade/index.php</a> to do that.</h1>';
			die();
		}
	}
	$server_name = str_replace("/", "", str_replace("http://", "", (@$GLOBALS['hosturl']!='' ? $GLOBALS['hosturl'] : $_SERVER['SERVER_NAME'])));
	if($GLOBALS['root'] == '' or $GLOBALS['root'] == '/')
	{
		$UPATH = $server_name;
	}else
	{
		$UPATH = $server_name.'/'.$root;
	}
        if($secure)
        {
            $UPATH = "https://".$UPATH;
        }else
        {
            $UPATH = "http://".$UPATH;
        }
	if(PHP_OS == "WINNT")
	{$dim="\\";}else{$dim = "/";}
	$path = getcwd();
	$wifidb_tools = @$GLOBALS['wifidb_tools'];
	$root = @$GLOBALS['root'];

	$path_exp = explode($dim, $path);
	$path_count = count($path_exp);
	include($wifidb_tools.'/daemon/config.inc.php');
	#	echo "Root: ".$root."<br>Path: ".$path."<br>Div: ".$dim."<br>Path Count: ".$path_count;
	if($root == '' or $root == '/')
	{
		$half_path = $GLOBALS['wifidb_install'];
	}
	else
	{
		foreach($path_exp as $key=>$val)
		{
		#	echo $root."<br>";
			if($val == $root){ $path_key = $key;}
		#	echo "Val: ".$val."<br>Path key: ".$path_key."<BR>";
		}

		$half_path = '';
		$I = 0;
		if(isset($path_key))
		{
		#	echo "I: ".$I." - ".$path_key."<br>";
			while($I!=($path_key+1))
			{

				$half_path = $half_path.$path_exp[$I].$dim;
			#	echo "Half Path: ".$half_path."<br>";
				$I++;
			}

		}
	}
}


####### HTTP ONLY ########
if(@$GLOBALS['screen_output'] != "CLI")
{
	if(!@isset($_COOKIE['wifidb_client_check']))
	{
	?>
		<script type="text/javascript">
		function checkTimeZone()
		{
			var expiredays = 86400;
			var rightNow = new Date();
			var date1 = new Date(rightNow.getFullYear(), 0, 1, 0, 0, 0, 0);
			var date2 = new Date(rightNow.getFullYear(), 6, 1, 0, 0, 0, 0);
			var temp = date1.toGMTString();
			var date3 = new Date(temp.substring(0, temp.lastIndexOf(" ")-1));
			var temp = date2.toGMTString();
			var date4 = new Date(temp.substring(0, temp.lastIndexOf(" ")-1));
			var hoursDiffStdTime = (date1 - date3) / (1000 * 60 * 60);
			var hoursDiffDaylightTime = (date2 - date4) / (1000 * 60 * 60);
			if (hoursDiffDaylightTime == hoursDiffStdTime)
			{
				var exdate=new Date();
				exdate.setDate(exdate.getDate()+expiredays);
				document.cookie="wifidb_client_dst=" +escape("0")+
				((expiredays==null) ? "" : ";expires=" +exdate.toUTCString());
				var exdate=new Date();
				exdate.setDate(exdate.getDate()+expiredays);
				document.cookie="wifidb_client_check=" +escape("1")+
				((expiredays==null) ? "" : ";expires=" +exdate.toUTCString());
			}
			else
			{
				var exdate=new Date();
				exdate.setDate(exdate.getDate()+expiredays);
				document.cookie="wifidb_client_dst" + "=" +escape("1")+
				((expiredays==null) ? "" : ";expires=" +exdate.toUTCString());
				var exdate=new Date();
				exdate.setDate(exdate.getDate()+expiredays);
				document.cookie="wifidb_client_check" + "=" +escape("1")+
				((expiredays==null) ? "" : ";expires=" +exdate.toUTCString());
		   }
		   location.href = '<?php echo $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];?>';
		}
		</script>
		<body onload = "checkTimeZone();" ></body>
		<?php
		die();
	}
	if(!@$_COOKIE['wifidb_client_timezone'])
	{
		?>
		<script type="text/javascript">
		function checkTimeZone()
		{
			var expiredays = 86400;
			var rightNow = new Date();
			var date1 = new Date(rightNow.getFullYear(), 0, 1, 0, 0, 0, 0);
			var date2 = new Date(rightNow.getFullYear(), 6, 1, 0, 0, 0, 0);
			var temp = date1.toGMTString();
			var date3 = new Date(temp.substring(0, temp.lastIndexOf(" ")-1));
			var temp = date2.toGMTString();
			var date4 = new Date(temp.substring(0, temp.lastIndexOf(" ")-1));
			var hoursDiffStdTime = (date1 - date3) / (1000 * 60 * 60);
		//	document.write(hoursDiffStdTime);
			var hoursDiffDaylightTime = (date2 - date4) / (1000 * 60 * 60);
			if (hoursDiffDaylightTime == hoursDiffStdTime)
			{
				var exdate=new Date();
				exdate.setDate(exdate.getDate()+expiredays);
				document.cookie="wifidb_client_timezone=" +escape(hoursDiffStdTime)+((expiredays==null) ? "" : ";expires=" +exdate.toUTCString());
			}
			else
			{
				var exdate=new Date();
				exdate.setDate(exdate.getDate()+expiredays);
				document.cookie="wifidb_client_timezone=" +escape(hoursDiffStdTime)+((expiredays==null) ? "" : ";expires=" +exdate.toUTCString());
		   }
		   location.href = '<?php echo $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];?>';
		}
		</script>
		<body onload = "checkTimeZone();" ></body>
		<?php
		die();
	}
	$full_path = $half_path.'/themes';
	#	echo "Default theme: ".$GLOBALS['default_theme']."<br>Cookie: ".$_COOKIE['wifidb_theme']."<BR>";
	$theme = (@$_COOKIE['wifidb_theme']!='' ? $_COOKIE['wifidb_theme'] : $GLOBALS['default_theme']);
	if($theme == ''){$theme = 'wifidb';}
	$full_path = $full_path."/".$theme."/";
	if(!function_exists('pageheader'))
	{require($full_path."header_footer.inc.php");}

}

############################################################
############################################################
############################################################

function convert($size)
{
	$unit=array('B','KB','MB','GB','TB','PB');
	return @round($size/pow(1024,($i=floor(log($size,1024)))),6).' '.$unit[$i];
}

function getTZ($offset = '-5')
{
	$timezones = array(
						'-12'=>'Pacific/Kwajalein',
						'-11'=>'Pacific/Samoa',
						'-10'=>'Pacific/Honolulu',
						'-9'=>'America/Juneau',
						'-8'=>'America/Los_Angeles',
						'-7'=>'America/Denver',
						'-6'=>'America/Mexico_City',
						'-5'=>'America/New_York',
						'-4'=>'America/Caracas',
						'-3.5'=>'America/St_Johns',
						'-3'=>'America/Argentina/Buenos_Aires',
						'-2'=>'Atlantic/Azores',// no cities here so just picking an hour ahead
						'-1'=>'Atlantic/Azores',
						'0'=>'Europe/London',
						'1'=>'Europe/Paris',
						'2'=>'Europe/Helsinki',
						'3'=>'Europe/Moscow',
						'3.5'=>'Asia/Tehran',
						'4'=>'Asia/Baku',
						'4.5'=>'Asia/Kabul',
						'5'=>'Asia/Karachi',
						'5.5'=>'Asia/Calcutta',
						'6'=>'Asia/Colombo',
						'7'=>'Asia/Bangkok',
						'8'=>'Asia/Singapore',
						'9'=>'Asia/Tokyo',
						'9.5'=>'Australia/Darwin',
						'10'=>'Pacific/Guam',
						'11'=>'Asia/Magadan',
						'12'=>'Asia/Kamchatka'
						);
	return $timezones[$offset];
}

#-------------------------------------------------------------------------------------#
#----------------------------------  Get Set values  ---------------------------------#
#-------------------------------------------------------------------------------------#
function redirect_page($return = "", $delay = 0, $msg = "no Message", $new_window = 0)
{
	if($return == ''){$return = $GLOBALS['UPATH'];}
	#echo $return."<br>";
	?>
		<script type="text/javascript">
			function reload()
			{
				<?php
				switch($new_window)
				{
					case 1:
						?>window.open('<?php echo $return;?>');<?php
					break;
					case 2:
						?>window.open('<?php echo $return;?>');
						location.href = '<?php echo $GLOBALS['UPATH'].'/';?>';<?php
					break;
					case 0:
						?>location.href = '<?php echo $return;?>';<?php
					break;
					default:
						?>location.href = '<?php echo $return;?>';<?php
					break;
				}?>
			}
			</script>
			<body onload="setTimeout('reload()', <?php echo $delay;?>)"><?php echo $msg;?></body>
	<?php
}

#-------------------------------------------------------------------------------------#
#----------------------------------   Tar a file up    -------------------------------#
#-------------------------------------------------------------------------------------#
function tar_file($file)
{
	include_once('config.inc.php');
	$start = microtime(1);
	$filename_exp = explode( ".", $file);
	$filename_strip = $filename_exp[0];
	$archive = $GLOBALS['wifidb_tools']."/backups/".$filename_strip.".tar.gz";
	$script = "tar -czfv $archive $file";
	$results = system($script,$retval);

	if(!$results)
	{
		$stop = microtime(1);
		$time = ($stop-$start);
		$mbps = ((filesize($file)/1024)/1024)/$time;
		$return = array( $results, $retval, $time, $mbps, $archive );
		return $return;
	}else
	{
		return 0;
	}
}



#-------------------------------------------------------------------------------------#
#----------------------------------  Get Set values  ---------------------------------#
#-------------------------------------------------------------------------------------#
function get_set($table,$column)
{
	$sql = "SHOW COLUMNS FROM $table LIKE '$column'";
	if (!($ret = mysql_query($sql)))
	die("Error: Could not show columns");

	$line = mysql_fetch_assoc($ret);
	$set = $line['Type'];
	$set = substr($set,5,strlen($set)-7); // Remove "set(" at start and ");" at end
	return preg_split("/','/",$set); // Split into and array
}



#-------------------------------------------------------------------------------------#
#--------------------------------DUMP VAR TO HTML -----------------------------#
#-------------------------------------------------------------------------------------#
function dump($value="" , $level=0)
{
  if ($level==-1)
  {
    $trans[' ']='&there4;';
    $trans["\t"]='&rArr;';
    $trans["\n"]='&para;;';
    $trans["\r"]='&lArr;';
    $trans["\0"]='&oplus;';
    return strtr(htmlspecialchars($value),$trans);
  }
  if ($level==0) echo '<pre>';
  $type= gettype($value);
  echo $type;
  if ($type=='string')
  {
    echo '('.strlen($value).')';
    $value= dump($value,-1);
  }
  elseif ($type=='boolean') $value= ($value?'true':'false');
  elseif ($type=='object')
  {
    $props= get_class_vars(get_class($value));
    echo '('.count($props).') <u>'.get_class($value).'</u>';
    foreach($props as $key=>$val)
    {
      echo "\n".str_repeat("\t",$level+1).$key.' => ';
      dump($value->$key,$level+1);
    }
    $value= '';
  }
  elseif ($type=='array')
  {
    echo '('.count($value).')';
    foreach($value as $key=>$val)
    {
      echo "\n".str_repeat("\t",$level+1).dump($key,-1).' => ';
      dump($val,$level+1);
    }
    $value= '';
  }
  echo " <b>$value</b>";
  if ($level==0) echo '</pre>';
}


#-------- recurse_chown_chgrp[Recureivly chown and chgrp a folder ----------#
function recurse_chown_chgrp($mypath, $uid, $gid)
{
	$d = opendir ($mypath) ;
	while(($file = readdir($d)) !== false)
	{
		if ($file != "." && $file != "..") {
			$typepath = $mypath . "/" . $file ;
			//print $typepath. " : " . filetype ($typepath). "<BR>" ;
			if (filetype ($typepath) == 'dir')
			{
				recurse_chown_chgrp ($typepath, $uid, $gid);
			}
			chown($typepath, $uid);
			chgrp($typepath, $gid);
		}
	}
}



#---------------- recurse_chmod [Recureivly chmod a folder -----------------#
function recurse_chmod($mypath, $mod)
{
	$d = opendir ($mypath) ;
	while(($file = readdir($d)) !== false)
	{
		if ($file != "." && $file != "..") {
			$typepath = $mypath . "/" . $file ;
			//print $typepath. " : " . filetype ($typepath). "<BR>" ;
			if (filetype ($typepath) == 'dir')
			{
				recurse_chmod($typepath, $mod);
			}
			chmod($typepath, $mod);
		}
	}
}



#---------------- Install Folder Warning Code -----------------#
function check_install_folder()
{
	$dim = $GLOBALS['dim'];
	$install_folder_remove = "";
	include('config.inc.php');
#	echo $GLOBALS['bypass_check'];
	if(@$GLOBALS['bypass_check']){return 0;}
	$path = getcwd();
	$path_exp = explode($dim , $path);
	$path_count = count($path_exp);
	foreach($path_exp as $key=>$val)
	{
		if($val == $root){ $path_key = $key;}
	}
	$full_path = '';
	$I = 0;
	if(isset($path_key))
	{
		while($I!=($path_key+1))
		{
			$full_path = $full_path.$path_exp[$I].$dim ;
			$I++;
		}
		$full_path = $full_path.'install';
		if(is_dir($full_path)){$install_folder_remove = '<p align="center"><font color="red" size="6">The install Folder is still there, remove it!</font></p>';}
	}
	if(isset($install_folder_remove)){echo $install_folder_remove;}
	return 1;
}

function sql_type_mail_filter($type = 'none')
{
	switch($type)
	{
		case "schedule":
			$sql = " AND `schedule` = '1'";
		break;

		case "import":
			$sql = " AND `imports` = '1'";
		break;

		case "kmz":
			$sql = " AND `kmz` = '1'";
		break;

		case "new_users":
			$sql = "AND `new_users` = '1'";
		break;

		case "statistics":
			$sql = " AND `statistics` = '1'";
		break;

		case "perfmon":
			$sql = " AND `perfmon` = '1'";
		break;

		case "announcements":
			$sql = " AND `announcements` = '1'";
		break;

		case "announce_comment":
			$sql = " AND `announce_comment` = '1'";
		break;

		case "pub_geocache":
			$sql = " AND `pub_geocache` = '1'";
		break;

		case "geonamed":
			$sql = " AND `geonamed` = '1'";
		break;

		case "none":
			$sql = '';
		break;

		default:
			$sql = '';
		break;
	}
	return $sql;
}

#========================================================================================================================#
#											mail_users (Emails the admins of the DB about updates)						 #
#========================================================================================================================#

function mail_users($contents = '', $subject = "WifiDB Notifications", $type = "none", $error_f = 0)
{
	if($type != 'none' or $type != '')
	{
	#	echo $GLOBALS['wifidb_email_updates'];
		if($GLOBALS['wifidb_email_updates'])
		{
			require_once('config.inc.php');
			require_once('MAIL5.php');

			$conn				= 	$GLOBALS['conn'];
			$db					= 	$GLOBALS['db'];
			$user_logins_table	=	$GLOBALS['user_logins_table'];
			$from				=	$GLOBALS['admin_email'];
			$wifidb_smtp		=	$GLOBALS['wifidb_smtp'];
			$sender				=	$from;
			$sender_pass		=	$GLOBALS['wifidb_from_pass'];
			$to					=	array();
			$mail				=	new MAIL5();
			$sql				=	"SELECT `email`, `username` FROM `$db`.`$user_logins_table` WHERE `disabled` = '0' AND `validated` = '0'";
	#		echo $sql."<BR>";
			$sql .= sql_type_mail_filter($type);
			if($error_f)
			{
				$sql .= " AND `admins` = '1'";
			}
	#		echo $sql."<BR>";
			if(!$error_f){$sql .= " AND `username` NOT LIKE 'admin%'";}
	#		echo $sql."<BR>";
			$result = mysql_query($sql, $conn) or die(mysql_error($conn));
			while($users = mysql_fetch_array($result))
			{
		#	echo "To: ".$users['email']."\r\n";
				if($mail->addbcc($users['email']))
				{continue;}else{die("Failed to add BCC".$users['email']."\r\n");}
			}

			if(!$mail->from($from))
			{die("Failed to add From address\r\n");}
			if(!$mail->addto($from))
			{die("Failed to add Initial To address\r\n");}

			if($error_f){$subject .= " ^*^*^*^ ERROR! ^*^*^*^";}
	#	echo "subject: ".$subject."\r\n";
			if(!$mail->subject($subject))
			{die("Failed to add subject\r\n");}

	#	echo "Contents: ".$contents."\r\n";
			if(!$mail->text($contents))
			{die("Failed to add message\r\n");}

	#	echo "Trying to connect....\r\n";
			$smtp_conn = $mail->connect($wifidb_smtp, 465, $sender, $sender_pass, 'tls', 10);
			if ($smtp_conn)
			{
	#	echo "Successfully connected !\r\n";
				$smtp_send = $mail->send($smtp_conn);
				if($smtp_send)
				{
				#	echo "Sent!\r\n";
					return 1;
				}
				else
				{
				#	print_r($_RESULT);
					return 0;
				}
			}else
			{
			#	print_r($_RESULT);
				return 0;
			}
			$mail->disconnect();
		}else
		{
			echo $GLOBALS['wifidb_email_updates'];
			echo "Mail updates is turned off.";
			return 0;
		}
	}else
	{
		echo "$"."type var is not set, check your code.<br>\r\n";
		return 0;
	}
}

###################
################################################
function mail_validation($to = '', $username = '')
{
	require_once('config.inc.php');
	require_once('security.inc.php');
	require_once('MAIL5.php');

	$conn				=	$GLOBALS['conn'];
	$db					=	$GLOBALS['db'];
	$validate_table		=	$GLOBALS['validate_table'];
	$from				=	$GLOBALS['admin_email'];
	$wifidb_smtp		=	$GLOBALS['wifidb_smtp'];
	$sender				=	$from;
	$sender_pass		=	$GLOBALS['wifidb_from_pass'];
	$UPATH				=	$GLOBALS['UPATH'];
	$mail				=	new MAIL5();
	$sec				=	new security();
	$date				=	date("Y-m-d H:i:s");
	if(!$mail->from($from))
	{die("Failed to add From address\r\n");}

	if(!$mail->addto($to))
	{die("Failed to add To address\r\n");}

	$subject = "WifiDB New User Validation";
	if(!$mail->subject($subject))
	{die("Failed to add subject\r\n");}

	$validate_code = $sec->gen_keys(48);

	$contents = "The Administrator of This WiFiDB has enabled user validation before you can use your login.
Your account: $username\r\n
Validation Link: $UPATH/login.php?func=validate_user&validate_code=$validate_code

-WiFiDB Service";
	if(!$mail->text($contents))
	{echo "Failed to add Message"; return 0;}

	$smtp_conn = $mail->connect($wifidb_smtp, 465, $sender, $sender_pass, 'tls', 10);
	if ($smtp_conn)
	{
		$smtp_send = $mail->send($smtp_conn);
		if($smtp_send)
		{
			$insert = "INSERT INTO `$db`.`$validate_table` (`id`, `username`, `code`, `date`) VALUES ('', '$username', '$validate_code', '$date')";
		#	echo $insert."<BR>";
			if(mysql_query($insert, $conn))
			{
				$return =1;
			#	echo "Message sent and inserted into DB.";
			}else
			{
				$insert = "UPDATE `$db`.`$validate_table` SET `code` = '$validate_code', `date` = '$date' WHERE `username` LIKE '$username' LIMIT 1";
			#	echo $insert."<BR>";
				if(mysql_query($insert, $conn))
				{
					$return =1;
				#	echo "Message sent and Updated to newest data.";
				}else
				{
					$return = 0;
					echo "Message sent, but insert into table failed.";
				}
			}
		}
		else
		{
			$return = 0;
			echo "Failed to send message.";
		}
	}else
	{
		$return = 0;
		echo "Failed to connect to SMTP server";
	}
	$mail->disconnect();
	return $return;
}


###################################################
###			My Mysticache link					###
###												###
###################################################
function my_caches($theme = "wifidb", $out = 1)
{
	$return = '';
	if($GLOBALS['login_check'])
	{
		switch($theme)
		{
			case "wifidb":
				$return = '<a class="links" href="'.$GLOBALS["UPATH"].'/cp/?func=boeyes&boeye_func=list_all&sort=id&ord=ASC&from=0&to=100">List All My Caches</a>';
			break;

			case "vistumbler":
				$return = '<div class="inside_text_bold"><a class="links" href="'.$GLOBALS["UPATH"].'/cp/?func=boeyes&boeye_func=list_all&sort=id&ord=ASC&from=0&to=100">List All My Caches</a></div>';
			break;
		}
	}
	if($out)
	{echo $return; return 1;}
	else{return $return;}
}

###################################################
###				Login bar generator				###
###												###
###################################################
function login_bar($theme = "wifidb", $out = 1)
{
	if(!@$GLOBALS['install'])
	{
		if($GLOBALS['login_check'])
		{
			$conn = $GLOBALS['conn'];
			$db = $GLOBALS['db'];
			$user_logins_table = $GLOBALS['user_logins_table'];
			list($cookie_pass_seed, $username) = explode(':', $_COOKIE['WiFiDB_login_yes']);
			$sql0 = "SELECT * FROM `$db`.`$user_logins_table` WHERE `username` = '$username' LIMIT 1";
			$result = mysql_query($sql0, $conn);
			$newArray = mysql_fetch_array($result);
			$last_login = $newArray['last_login'];
			switch($theme)
			{
				case "wifidb":
					$return = '<td>Welcome, <a class="links" href="'.$GLOBALS["UPATH"].'/cp/">'.$username.'</a><font size="1"> (Last Login: '.$last_login.')</font></td> <td align="right"><a class="links" href="'.$GLOBALS["UPATH"].'/login.php?func=logout_proc">Logout</a></td>';
				break;

				case "vistumbler":
					$return = '<td class="cell_top_mid" style="height: 20px" align="left">Welcome, <a class="links" href="'.$GLOBALS["UPATH"].'/cp/">'.$username.'</a><font size="1"> (Last Logon: '.$last_login.')</font></td><td class="cell_top_mid" style="height: 20px" align="right"><a class="links" href="'.$GLOBALS["UPATH"].'/login.php?func=logout_proc">Logout</a></td>';
				break;
			}
		}else
		{
			$filtered = filter_var($_SERVER['QUERY_STRING'],FILTER_SANITIZE_ENCODED);
			$SELF = $_SERVER['PHP_SELF'];
			if($SELF == "/".$GLOBALS["root"].'/login.php')
			{
				$SELF = "/".$GLOBALS["root"];
				$filtered = '';
			}
			if($filtered != '')
			{$SELF = $SELF.'?'.$filtered;}
			switch($theme)
			{
				case "wifidb":
					$return = '<td></td><td align="right"><a class="links" href="'.$GLOBALS["UPATH"].'/login.php?return='.$SELF.'">Login</a></td>';
				break;

				case "vistumbler":
					$return = '<td class="cell_top_mid" style="height: 20px" align="left"></td><td class="cell_top_mid" style="height: 20px" align="right"><a class="links" href="'.$GLOBALS["UPATH"].'/login.php?return='.$SELF.'">Login</a></td>';
				break;
			}
		}
	}
	if($out)
	{echo $return; return 1;}
	else{return $return;}
}

###################################################
###				User Control Panel				###
###				  bar generator					###
###################################################
function user_panel_bar($tab = "overview", $boeyes = 0)
{
	$username = $GLOBALS['username'];
	?>
	<b><font size="6"><?php echo $username; ?>'s Control Panel</font></b>
	<table BORDER=1 CELLPADDING=2 CELLSPACING=0 style="width: 95%">
		<tr>
			<th class="<?php if($tab == "overview"){echo "cp_select_coloum";}else{echo "style3";} ?>" width="20%"><a class="links<?php if($tab == "overview"){echo "_s";} ?>" href="index.php">Overview</a></th>
			<th class="<?php if($tab == "prof"){echo "cp_select_coloum";}else{echo "style3";} ?>" width="20%"><a class="links<?php if($tab == "prof"){echo "_s";} ?>" href="index.php?func=profile">Profile</a></th>
			<th class="<?php if($tab == "pref"){echo "cp_select_coloum";}else{echo "style3";} ?>"><a class="links<?php if($tab == "pref"){echo "_s";} ?>" href="index.php?func=pref">Preferences</a></th>
			<th class="<?php if($tab == "myst"){echo "cp_select_coloum";}else{echo "style3";} ?>" width="20%"><a class="links<?php if($tab == "myst"){echo "_s";} ?>" href="index.php?func=boeyes">Mysticache</a></th>
			<th class="<?php if($tab == "fandf"){echo "cp_select_coloum";}else{echo "style3";} ?>" width="20%"><a class="links<?php if($tab == "fandf"){echo "_s";} ?>" href="index.php?func=fandf">Friends / Foes</a></th>
		</tr>
		<tr>
			<?php if($boeyes)
			{
			?>
			<td colspan="3" class="light"></td>
			<td colspan="2" class="cp_select_coloum">
				<font size="2">
				<a class="links<?php if($boeyes == "listall"){echo "_s";} ?>" href="?func=boeyes&boeye_func=list_all">List All</a>
				<font color="white">|-|</font> <a class="links<?php if($boeyes == "import"){echo "_s";} ?>" href="?func=boeyes&boeye_func=import_switch">Import</a>
				(<a class="links<?php if($boeyes == "gpx"){echo "_s";} ?>" href="?func=boeyes&boeye_func=import_gpx">GPX</a>)
				(<a class="links<?php if($boeyes == "loc"){echo "_s";} ?>" href="?func=boeyes&boeye_func=import_loc">LOC</a>)
		<!--	<font color="white">|-|</font> <a class="links" href="?func=boeyes&boeye_func=export_switch">Export</a>
				(<a class="links" href="?func=boeyes&boeye_func=export_gpx">GPX</a>)
				(<a class="links" href="?func=boeyes&boeye_func=export_loc">LOC</a>)</font></td></tr> -->
                                <?php
                        }else
			{
				echo '<td colspan="6" class="light">&nbsp;</td>';
			}
		echo '<tr>';
}



#========================================================================================================================#
#											verbose (Echos out a message to the screen or page)			        		 #
#========================================================================================================================#

function logd($message = '', $log_interval = 0, $details = "",  $log_level = 0)
{
	require('config.inc.php');
	if(!$log_level)
	{
		if($message == ''){echo "Logd was told to write a blank string.\nThis has NOT been logged.\nThis will NOT be allowed!\n"; return 0;}
		$date = date("y-m-d");
		$time = time();
		$datetime = date("Y-m-d H:i:s",$time);
		$message = $datetime."   ->    ".$message."\r\n";
		include('config.inc.php');
		if($log_interval==1)
		{
			$cidir = getcwd();
			$filename = $GLOBALS['wifidb_tools'].'/log/wifidbd_log.log';
			if(!is_file($filename))
			{
				fopen($filename, "w");
			}
			$fileappend = fopen($filename, "a");
			if($log_level == 2 && $details == 0){$log_level = 1;}
			if($log_level == 2)
			{
				$message = $message."\n==Details==\n".var_dump($details)."\n===========\n";
			}
			$write_message = fwrite($fileappend, $message);
			if(!$write_message){die("Could not write message to the file, thats not good...");}
		}elseif($log_interval==2)
		{
			$cidir = getcwd();
			$filename = $GLOBALS['wifidb_tools'].'/log/wifidbd_'.$date.'_log.log';
			if(!is_file($filename))
			{
				fopen($filename, "w");
			}
			$fileappend = fopen($filename, "a");
			if($log_level == 2 && $details == 0){$log_level = 1;}
			if($log_level == 1)
			{
				$message = $message."\n==Details==\n".var_dump($details)."\n===========\n";
			}
			$write_message = fwrite($fileappend, $message);
			if(!$write_message){die("Could not write message to the file, thats not good...");}
		}
	}
}




#========================================================================================================================#
#											log write (writes a message to the log file)								 #
#========================================================================================================================#

function verbosed($message = "", $level = 0, $out="CLI", $header = 0)
{
	require('config.inc.php');
	$datetime = date("Y-m-d H:i:s",time());
	if($out == "CLI")
	{
		if($message != '')
		{
			if($header == 0)
			{
				$message = $datetime."   ->    ".$message;
			}
			if($level==1)
			{
				echo $message."\n";
			}
		}else
		{
			echo "Verbose was told to write a blank string";
		}
	}elseif($out == "HTML")
	{
		if($message != '')
		{
			echo $message."<br>";
		}else
		{
			echo "Verbose was told to write a blank string";
		}
	}
}




#========================================================================================================================#
#									breadcrumb (creates a breadcrumb to follow on each page)							 #
#========================================================================================================================#

function breadcrumb($PATH_INFO)
{
	global $page_title, $root_url;
	$PATH_INFO_EXP = explode("?", $PATH_INFO);
	$PATH_INFO = $PATH_INFO_EXP[0];
	// Remove these comments if you like, but only distribute
	// commented versions.

	// Replace all instances of _ with a space
	$PATH_INFO = str_replace("_", " ", $PATH_INFO);
	// split up the path at each slash
	$pathArray = explode("/",$PATH_INFO);

	// Initialize variable and add link to home page
	if(!isset($root_url)) { $root_url=""; }
	$breadCrumbHTML = '<a class="links" href="'.$root_url.'/" title="Root">[ Root ]</a> / ';

	// initialize newTrail
	$newTrail = $root_url."/";

	// starting for loop at 1 to remove root
	for($a=1;$a<count($pathArray)-1;$a++) {
		// capitalize the first letter of each word in the section name
		$crumbDisplayName = ucwords($pathArray[$a]);
		// rebuild the navigation path
		$newTrail .= $pathArray[$a].'/';
		// build the HTML for the breadcrumb trail
		$breadCrumbHTML .= '<a class="links" href="'.$newTrail.'">[ '.$crumbDisplayName.' ]</a> / ';
	}
	// Add the current page
	if(!isset($page_title)) { $page_title = "Current Page"; }
	$breadCrumbHTML .= '<font size="2" style="font-family: Arial;color: #FFFFFF;"><strong>'.$page_title.'</strong><font>';

	// print the generated HTML
	print($breadCrumbHTML);

	// return success (not necessary, but maybe the
	// user wants to test its success?
	return true;
}




#========================================================================================================================#
#													Smart Quotes (char filtering)										 #
#========================================================================================================================#

function smart_quotes($text="") // Used for SSID Sanatization
{
	$pattern = '/"((.)*?)"/i';
	$strip = array(
			0=>";",
			1=>"`",
			2=>"&", #
			3=>"!", #
			4=>"/", #
			5=>"\\", #
			6=>"'",
			7=>'"',
			8=>" "
			);
	$text = preg_replace($pattern,"&#147;\\1&#148;",$text);
	$text = str_replace($strip,"_",$text);
	return $text;
}




#========================================================================================================================#
#													Smart (filtering for GPS)											 #
#========================================================================================================================#

function smart($text="") // Used for GPS
{
	$pattern = '/"((.)*?)"/i';
	$strip = array(
					0=>" ",
					1=>":",
					2=>"-",
					3=>".",
					4=>"N",
					5=>"E",
					6=>"W",
					7=>"S"
				  );
	$text = preg_replace($pattern,"&#147;\\1&#148;",stripslashes($text));
	$text = str_replace($strip,"",$text);
	return $text;
}




#========================================================================================================================#
#							dos_filesize (gives file size for either windows or linux machines)				 			 #
#========================================================================================================================#

function dos_filesize($fn = "")
{
	if(PHP_OS == "WINNT")
	{
		if (is_file($fn))
			return exec('FOR %A IN ("'.$fn.'") DO @ECHO %~zA');
		else
			return '0';
	}else
	{
		return @filesize($fn);
	}
}



#========================================================================================================================#
#					format_size (formats bytes based size to B, kB, MB, GB... and so on, also does rounding)				        #
#========================================================================================================================#

function format_size($size, $round = 2)
{
	//Size must be bytes!

	$sizes = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');

	for ($i=0; $size > 1024 && $i < (count($sizes)-1); $i++)
	{
		$size = $size/1024;
	}
	#echo $size."<BR>";
	return round($size,$round).$sizes[$i];
}



#=========================================================================================================================#
#							make ssid (makes a DB safe, File safe and Unsan versions of an SSID)			 		#
#=========================================================================================================================#

function make_ssid($ssid_in = '')
{
    $ssid_in = preg_replace('/[\x00-\x1F\x7F]/', '', $ssid_in);

    if($ssid_in == ""){$ssid_in="UNNAMED";}
    #var_dump($exp)."</br>";
    #if($ssid_len < 1){$ssid_in="UNNAMED";}

    ###########
    ## Make File Safe SSID
    $file_safe_ssid = smart_quotes($ssid_in);
    ###########

    ###########
    ## Make Row and HTML safe SSID
    $ssid_in_dupe = $ssid_in;
    $ssid_in = htmlentities($ssid_in, ENT_QUOTES);
    $ssid_safe_full_length = mysql_real_escape_string($ssid_in_dupe);
    ###########

    ###########
    ## Make Table safe SSID
    $ssid_sized = str_split($ssid_in_dupe,25); //split SSID in two on is 25 char.
    $replace = array(' ', '`', '.', "'", '"', "/", "\\");
    #echo $ssid_sized[0];
    $ssid_table_safe = str_replace($replace,'_',$ssid_sized[0]); //Use the 25 char word for the APs table name, this is due to a limitation in MySQL table name lengths,
    ###########

    ###########
    ## Return
    #echo $ssid_table_safe;
    $A = array(0=>$ssid_table_safe, 1=>$ssid_safe_full_length , 2=> $ssid_in, 3=>$file_safe_ssid, 4=>$ssid_in_dupe);
    return $A;
    ###########
}


#########################################
#										#
#########################################
function top_ssids()
{
	$half_path  =   $GLOBALS['half_path'];
	if($GLOBALS['half_path'] =='')
	{
		include_once($GLOBALS['wifidb_install'].'/lib/config.inc.php');
	}else
	{
		include_once($GLOBALS['half_path'].'/lib/config.inc.php');
	}
	$conn			= 	$GLOBALS['conn'];
	$db			= 	$GLOBALS['db'];
	$db_st			= 	$GLOBALS['db_st'];
	$wtable			=	$GLOBALS['wtable'];

	$ssids = array();
	$number = array();
	echo "Select from Pointers Table\r\n";
	$sql0 = "SELECT * FROM `$db`.`$wtable`";
	$result0 = mysql_query($sql0, $conn) or die(mysql_error($conn));
	$total_rows = mysql_num_rows($result0);
	if($total_rows != 0)
	{
		while ($files = mysql_fetch_array($result0))
		{
			$ssids[]    =   $files['ssid'];
		}
		echo "Gathered all SSIDs, now Uniqueifying it...\r\n";
		$ssids = array_unique($ssids);
		echo "Now sorting the array...\r\n";
		sort($ssids);
		echo "Find out the number of each SSID\r\n";
		foreach($ssids as $key=>$ssid)
		{
			$sql1 = "SELECT * FROM `$db`.`$wtable` WHERE `ssid` LIKE '$ssid'";
			$result1 = mysql_query($sql1, $conn) or die(mysql_error($conn));
			$total_rows = mysql_num_rows($result1);
			$num_ssid[]	=	array( 0=>$total_rows, 1=>$ssid);
		}
		echo "Sort again so the count is in decending order...\r\n";
		rsort($num_ssid);
		return $num_ssid;
	}else
	{
		echo "<h2>There is nothing to stat, import something so I can graph it.</h2>";
		return 0;
	}
}




	#=========================================================================================================================#
	#																							#
	#									WiFiDB Database Class that holds DB based functions						#
	#																							#
	#=========================================================================================================================#



class database
{
	#=========================================================================================================================#
	#										table_exists (Check to see if a table is in the DB before trying to read from it) #
	#=========================================================================================================================#

	function table_exists($table="", $db="")
	{
		$tables = mysql_list_tables($db);
		while($temp = mysql_fetch_array($tables))
		{
			#var_dump($temp['Tables_in_wifi']);
		#	echo $temp['Tables_in_wifi']." - ".$table."\r\n";
			if ($temp['Tables_in_wifi'] == $table)
			{
				return TRUE;
			}
		}
		return FALSE;
	}


	#=========================================================================================================================#
	#										gen_gps (generate GPS cords from a VS1 file to Array)				 	#
	#=========================================================================================================================#

	function gen_gps($retexp = array())
	{
		$ret_len = count($retexp);
		switch ($ret_len)
		{
			case 6:
				$ret_lat	=	addslashes($retexp[1]);
				$ret_long	=	addslashes($retexp[2]);
				$ret_sats	=	addslashes($retexp[3]);
				$ret_date	=	addslashes($retexp[4]);
				$ret_time	=	addslashes($retexp[5]);
				$order   = array("\r\n", "\n", "\r");
				$replace = '';
				$retdate = str_replace($order, $replace, $ret_date);
				$date_exp = explode("-",$ret_date);
				if(strlen($date_exp[0]) <= 2)
				{
					$gpsdate = $date_exp[2]."-".$date_exp[0]."-".$date_exp[1];
				}else
				{
					$gpsdate = $ret_date;
				}
				# GpsID|Latitude|Longitude|NumOfSatalites|Date(UTC y-m-d)|Time(UTC h:m:s)
				$gdata = array(
											"lat"=>$ret_lat,
											"long"=>$ret_long,
											"sats"=>$ret_sats+0,
											"hdp"=> '0.0',
											"alt"=> '0.0',
											"geo"=> '-0.0',
											"kmh"=> '0.0',
											"mph"=> '0.0',
											"track"=> '0.0',
											"date"=>$gpsdate,
											"time"=>$ret_time
											);
				break;
			case 12:
				$ret_lat	=	addslashes($retexp[1]);
				$ret_long	=	addslashes($retexp[2]);
				$ret_sats	=	addslashes($retexp[3]);
				$ret_hdp	=	addslashes($retexp[4]);
				$ret_alt	=	addslashes($retexp[5]);
				$ret_geo	=	addslashes($retexp[6]);
				$ret_kmh	=	addslashes($retexp[7]);
				$ret_mph	=	addslashes($retexp[8]);
				$ret_track	=	addslashes($retexp[9]);
				$order   = array("\r\n", "\n", "\r");
				$replace = '';
				$retexp[10] = str_replace($order, $replace, $retexp[10]);

				$date_exp = explode("-",$retexp[10]);
				if(strlen($date_exp[0]) <= 2)
				{
					$gpsdate = $date_exp[2]."-".$date_exp[0]."-".$date_exp[1];
				}else
				{
					$gpsdate = $retexp[10];
				}
				# GpsID|Latitude|Longitude|NumOfSatalites|HorDilPitch|Alt|Geo|Speed(km/h)|Speed(MPH)|TrackAngle|Date(UTC y-m-d)|Time(UTC h:m:s)
				$gdata = array(
											"lat"=>$ret_lat,
											"long"=>$ret_long,
											"sats"=>$ret_sats,
											"hdp"=>$ret_hdp,
											"alt"=>$ret_alt,
											"geo"=>$ret_geo,
											"kmh"=>$ret_kmh,
											"mph"=>$ret_mph,
											"track"=>$ret_track,
											"date"=>$gpsdate,
											"time"=>$retexp[11]
											);
			#	var_dump($gdata);
				break;
			default :
				$gdata = array(
											"lat"=>"N 0.0000",
											"long"=>"E 0.0000",
											"sats"=>"00",
											"hdp"=>"0",
											"alt"=>"0",
											"geo"=>"0",
											"kmh"=>"0",
											"mph"=>"0",
											"track"=>"0",
											"date"=>"1970-06-17",
											"time"=>"12:00:00"
											);
		}
		return $gdata;
	}

	#========================================================================================================================#
	#						Grab the Manuf for a given MAC, return Unknown Manuf if not found								 #
	#========================================================================================================================#
	function &manufactures($mac="")
	{
		include('manufactures.inc.php');
		if(count(explode(":", $mac)) > 1)
		{
			$mac = str_replace(":", "", $mac);
		}
		$man_mac = str_split($mac,6);
		if(isset($manufactures[$man_mac[0]]))
		{
			$manuf = $manufactures[$man_mac[0]];
		}
		else
		{
			$manuf = "Unknown Manufacture";
		}
		return $manuf;
	}

	#========================================================================================================================#
	#											import GPX (Import Garmin Based GPX files)						 			 #
	#========================================================================================================================#

	function import_gpx($source="" , $user="Unknown" , $notes="No Notes" , $title="UNTITLED" )
	{
		$start = microtime(true);
		$times=date('Y-m-d H:i:s');

		if ($source == NULL){?><h2>You did not submit a file, please <A HREF="javascript:history.go(-1)"> [Go Back]</A> and do so.</h2> <?php die();}

		include('../lib/config.inc.php');

		$apdata  = array();
		$gpdata  = array();
		$signals = array();
		$sats_id = array();

		$fileex  = explode(".", $source);
		$return  = file($source);
		$count = count($return);
		$rettest = substr($return[1], 1, -1);

		if($rettest == 'gpx xmlns="http://www.topografix.com/GPX/1/1" creator="Vistumbler 9.3 Beta 2" version="1.1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.topografix.com/GPX/1/1 http://www.topografix.com/GPX/1/1/gpx.xsd"')
		{
			echo $rettest."<br>";
		}else
		{
			echo '<h1>You need to upload a valid GPX file, go look up the santax for it, or the file that you saved is corrupted</h1>';
		}
	}


	function convert_vs1($source='', $out='file')
	{
            if($source == ''){die("cannot supply an empty string for the file source.");}
            //Access point and GPS Data Array
            $apdata=array();
            global $gpsdata;

            $dir = $GLOBALS['wifidb_install'].'/import/up/';
            // dfine time that the script started
            $start = date("H:i:s");
            // counters
            $c=0;
            $cc=0;
            $n=0;
            $nn=0;
            $N=0;
            $complete=0;

            $src=explode("/",$source);
            $f_max = count($src);
            $file_src = explode(".",$src[$f_max-1]);
            $file_type = strtolower($file_src[1]);

            $file_ext = $dir.$file_src[0].'.vs1';
            $filename = $file_ext;
            if($GLOBALS["debug"] == 1 ){echo $file_ext."\n".$filename."\n";}
            // define initial write and appends
            $filewrite = fopen($filename, "w");
            $fileappend = fopen($filename, "a");
            $return = file($source);
            $exp = explode("|", $return[0]);
            if($return[0][0] == "#")
            {
                $ext_e = explode(".", $source);
                $c_ext = count($ext_e)-1;
                $ext_e[$c_ext] = "vs1";
                $source = implode(".", $ext_e);
                return $source;
            }
            if(count($exp) == 17)
            {
                //Break out file into an Array
                $return = file($source);
                //create interval for progress
                $line = count($return);
                $stat_c = $line/97;
                if ($GLOBALS["debug"] ==1){echo $stat_c."\r\n";}
                if ($GLOBALS["debug"] ==1){echo $line."\r\n";}
                // Start the main loop
                foreach($return as $ret)
                {
                    $c++;
                    $cc++;
                    if ($ret[0] == "#"){continue;}
                    $wifi = explode("|",$ret);
                    $ret_count = count($wifi);
                    if ($ret_count == 17)// test to see if the data is in correct format
                    {
                        if ($cc >= $stat_c)
                        {
                            $cc=0;
                            $complete++;
                            echo $complete."% - ";
                            if ($complete == 100 ){ echo "\r\n\r\n";}
                        }
                        //format date and time
                        $datetime=explode(" ",$wifi[13]);
                        $date=$datetime[0];
                        $time=$datetime[1];

                        // This is a temp array of data to be tested against the GPS array
                        $gpsdata_t=array(
                                        "lat"=> $wifi[8],
                                        "long"=> $wifi[9],
                                        "sats"=> "0",
                                        "hdp"=> '0.0',
                                        "alt"=> '0.0',
                                        "geo"=> '-0.0',
                                        "kmh"=> '0.0',
                                        "mph"=> '0.0',
                                        "track"=> '0.0',
                                        "date"=>$date,
                                        "time"=>$time
                                        );
                        $lat = smart($gpsdata_t['lat']);
                        $long = smart($gpsdata_t['long']);
                        $time = smart($gpsdata_t['time']);
                        $date = smart($gpsdata_t['date']);
                        $gps_test = $lat."".$long."".$date."".$time;

                        // Create the Security Type number for the respective Access point
                        if ($wifi[4]=="Open"&&$wifi[5]=="None"){$sectype="1";}
                        if ($wifi[4]=="Open"&&$wifi[5]=="WEP"){$sectype="2";}
                        if ($wifi[4]=="WPA-Personal" or $wifi[4] =="WPA2-Personal"){$sectype="3";}

                        if ($GLOBALS["debug"] == 1 )
                        {
                            echo "\n\n+-+-+-+-+-+-\n".$gpsdata_t["lat"]."+-\n".$gpsdata_t["long"]."+-\n".$gpsdata_t["sats"]."+-\n".$gpsdata_t["date"]."+-\n".$gpsdata_t["time"]."+-\n";
                        }

                        $gpschk =& database::check_gps_array($gpsdata, $gps_test);
                        if ($gpschk[0]==0)
                        {
                            if ($GLOBALS["debug"] ==1)
                            {echo "\$n = ".$n."\n\$N = ".$N."\n";}
                            $n++;
                            $N++;
                            $sig=$n.",".$wifi[3];
                            $gpsdata[$n]=array(
                                            "id"=>$n,
                                            "lat"=>$wifi[8],
                                            "long"=>$wifi[9],
                                            "sats"=>'0',
                                            "hdp"=> '0.0',
                                            "alt"=> '0.0',
                                            "geo"=> '-0.0',
                                            "kmh"=> '0.0',
                                            "mph"=> '0.0',
                                            "track"=> '0.0',
                                            "date"=>$date,
                                            "time"=>$time
                                            );

                            $apdata[$N]=array(
                                            "ssid"=>$wifi[0],
                                            "mac"=>$wifi[1],
                                            "man"=>$wifi[2],
                                            "auth"=>$wifi[4],
                                            "encry"=>$wifi[5],
                                            "sectype"=>$sectype,
                                            "radio"=>$wifi[6],
                                            "chan"=>$wifi[7],
                                            "btx"=>$wifi[10],
                                            "otx"=>$wifi[11],
                                            "nt"=>$wifi[14],
                                            "label"=>$wifi[15],
                                            "sig"=>$sig
                                            );
                            if ($GLOBALS["debug"] == 1 )
                            {
                                echo "\n\n+_+_+_+_+_+_\n".$gpsdata[$n]["lat"]."  +_\n".$gpsdata[$n]["long"]."  +_\n".$gpsdata[$n]["sats"]."  +_\n".$gpsdata[$n]["date"]."  +_\n".$gpsdata[$n]["time"]."  +_\n";
                                echo "Access Point Number: ".$N."\n";
                                echo "=-=-=-=-=-=-\n".$apdata[$N]["ssid"]."  =-\n".$apdata[$N]["mac"]."  =-\n".$apdata[$N]["auth"]."  =-\n".$apdata[$N]["encry"]."  =-\n".$apdata[$N]["sectype"]."  =-\n".$apdata[$N]["radio"]."  =-\n".$apdata[$N]["chan"]."  =-\n".$apdata[$N]["btx"]."  =-\n".$apdata[$N]["otx"]."  =-\n".$apdata[$N]["nt"]."  =-\n".$apdata[$N]["label"]."  =-\n".$apdata[$N]["sig"]."\n";
                            }
                        }elseif($gpschk===1)
                        {
                            if ($GLOBALS["debug"] ==1)
                            {echo "\$n = ".$n."\n\$N = ".$N."\n";}
                            $N++;
                            $sig=$n.",".$wifi[3];
                            if ($GLOBALS["debug"] ==1 ){echo "\nduplicate GPS data, not entered into array\n";}
                            $apdata[$N]=array("ssid"=>$wifi[0],
                                                            "mac"=>$wifi[1],
                                                            "man"=>$wifi[2],
                                                            "auth"=>$wifi[4],
                                                            "encry"=>$wifi[5],
                                                            "sectype"=>$sectype,
                                                            "radio"=>$wifi[6],
                                                            "chan"=>$wifi[7],
                                                            "btx"=>$wifi[10],
                                                            "otx"=>$wifi[11],
                                                            "nt"=>$wifi[14],
                                                            "label"=>$wifi[15],
                                                            "sig"=>$sig);
                            if ($GLOBALS["debug"] == 1 )
                            {
                                echo "Access Point Number: ".$N."\n";
                                echo "=-=-=-=-=-=-\n".$apdata[$N]["ssid"]."=-\n".$apdata[$N]["mac"]."=-\n".$apdata[$N]["auth"]."=-\n".$apdata[$N]["encry"]."=-\n".$apdata[$N]["sectype"]."=-\n".$apdata[$N]["radio"]."=-\n".$apdata[$N]["chan"]."=-\n".$apdata[$N]["btx"]."=-\n".$apdata[$N]["otx"]."=-\n".$apdata[$N]["nt"]."=-\n".$apdata[$N]["label"]."=-\n".$apdata[$N]["sig"]."\n";
                            }
                        }
                    }else
                    {
                            echo "\nLine: ".$c." - Wrong data type, dropping row\n";
                    }
                    if(@$gpsdata_t[0])
                    {
                        unset($gpsdata_t[0]);
                    }
                }
            }elseif($file_type == "db3")
            {
                $sep = '-';
                $dbh = new PDO("sqlite:$source"); // success
                $line = count($dbh->query('SELECT * FROM networks'));

                $stat_c = $line/100;
                if ($GLOBALS["debug"] ==1){echo $stat_c."\n";}
                if ($GLOBALS["debug"] ==1){echo $line."\n";}

                foreach ($dbh->query('SELECT * FROM networks') as $row)
                {
                    list($ssid_t, $ssid) = make_ssid($row["ssid"]);
                    $mac = strtoupper($row["bssid"]);
                    $man = database::manufactures($mac);

                    $Found_Capabilies = $row["capabilities"];

                    If(stristr($Found_Capabilies, "WPA2-PSK-CCMP") Or stristr($Found_Capabilies, "WPA2-PSK-TKIP+CCMP"))
                    {	$Found_AUTH = "WPA2-Personal";
                            $Found_ENCR = "CCMP";
                            $Found_SecType = 3;
                    }ElseIf(stristr($Found_Capabilies, "WPA-PSK-CCMP") Or stristr($Found_Capabilies, "WPA-PSK-TKIP+CCMP"))
                    {	$Found_AUTH = "WPA-Personal";
                            $Found_ENCR = "CCMP";
                            $Found_SecType = 3;
                    }ElseIf(stristr($Found_Capabilies, "WPA2-EAP-CCMP") Or stristr($Found_Capabilies, "WPA2-EAP-TKIP+CCMP"))
                    {	$Found_AUTH = "WPA2-Enterprise";
                            $Found_ENCR = "CCMP";
                            $Found_SecType = 3;
                    }ElseIf(stristr($Found_Capabilies, "WPA-EAP-CCMP") Or stristr($Found_Capabilies, "WPA-EAP-TKIP+CCMP"))
                    {	$Found_AUTH = "WPA-Enterprise";
                            $Found_ENCR = "CCMP";
                            $Found_SecType = 3;
                    }ElseIf(stristr($Found_Capabilies, "WPA2-PSK-TKIP"))
                    {	$Found_AUTH = "WPA2-Personal";
                            $Found_ENCR = "TKIP";
                            $Found_SecType = 3;
                    }ElseIf(stristr($Found_Capabilies, "WPA-PSK-TKIP"))
                    {	$Found_AUTH = "WPA-Personal";
                            $Found_ENCR = "TKIP";
                            $Found_SecType = 3;
                    }ElseIf(stristr($Found_Capabilies, "WPA2-EAP-TKIP"))
                    {	$Found_AUTH = "WPA2-Enterprise";
                            $Found_ENCR = "TKIP";
                            $Found_SecType = 3;
                    }ElseIf(stristr($Found_Capabilies, "WPA-EAP-TKIP"))
                    {	$Found_AUTH = "WPA-Enterprise";
                            $Found_ENCR = "TKIP";
                            $Found_SecType = 3;
                    }ElseIf(stristr($Found_Capabilies, "WEP"))
                    {	$Found_AUTH = "Open";
                            $Found_ENCR = "WEP";
                            $Found_SecType = 2;
                    }Else
                    {	$Found_AUTH = "Open";
                            $Found_ENCR = "None";
                            $Found_SecType = 1;
                    }
                    if(stristr($Found_Capabilies, "IBSS"))
                    {
                            $nt = "Ad-Hoc";
                    }else
                    {
                            $nt = "Infrastructure";
                    }
                    $authen = $Found_AUTH;
                    $encry = $Found_ENCR;
                    $sectype = $Found_SecType;
                    ###########################
                    switch($row["frequency"]+0)
                    {
                        case 2412:
                                $chan = 1;
                                $radio = '802.11g';
                        break;
                        case 2417:
                                $chan = 2;
                                $radio = '802.11g';
                        break;
                        case 2422:
                                $chan = 3;
                                $radio = '802.11g';
                        break;
                        case 2427:
                                $chan = 4;
                                $radio = '802.11g';
                        break;
                        case 2432:
                                $chan = 5;
                                $radio = '802.11g';
                        break;
                        case 2437:
                                $chan = 6;
                                $radio = '802.11g';
                        break;
                        case 2442:
                                $chan = 7;
                                $radio = '802.11g';
                        break;
                        case 2447:
                                $chan = 8;
                                $radio = '802.11g';
                        break;
                        case 2452:
                                $chan = 9;
                                $radio = '802.11g';
                        break;
                        case 2457:
                                $chan = 10;
                                $radio = '802.11g';
                        break;
                        case 2462:
                                $chan = 11;
                                $radio = '802.11g';
                        break;
                        case 2467:
                                $chan = 12;
                                $radio = '802.11g';
                        break;
                        case 2472:
                                $chan = 13;
                                $radio = '802.11g';
                        break;
                        case 2484:
                                $chan = 14;
                                $radio = '802.11b';
                        break;
                        default:
                                $chan = 6;
                                $radio = 'g';
                        break;
                    }
                    $level = (100+$row["level"]);
                    $alt = $row["alt"];

                    $time = str_split($row["timestamp"], 10);
                    $timestamp = date("Y-m-d H:i:s", $time[0]);

                    $table = $ssid_t.$sep.$mac.$sep.$sectype.$sep.$radio.$sep.$chan;
            #	if($ssid_t == "yellow"){ die(); }
                    //format date and time
                    $datetime=explode(" ",$timestamp);
                    $date=$datetime[0];
                    $time=$datetime[1];
                    $lat = $row['lat'];
                    $long = $row['lon'];
                    echo $table."\r\n$timestamp - - $man\r\n$nt - $authen - $encry - $sectype - $lat - $long\r\n----------\r\n\r\n";
                    // This is a temp array of data to be tested against the GPS array
                    $gpsdata_t=array(
                                    "lat"=>$lat,
                                    "long"=>$long,
                                    "sats"=>"0",
                                    "hdp"=> '0.0',
                                    "alt"=> $alt,
                                    "geo"=> '-0.0',
                                    "kmh"=> '0.0',
                                    "mph"=> '0.0',
                                    "track"=> '0.0',
                                    "date"=>$date,
                                    "time"=>$time
                                    );
                    $lat_t = smart($gpsdata_t['lat']);
                    $long_t = smart($gpsdata_t['long']);
                    $time_t = smart($gpsdata_t['time']);
                    $date_t = smart($gpsdata_t['date']);
                    $gps_test = $lat_t."".$long_t."".$date_t."".$time_t;
                    if ($GLOBALS["debug"] == 1 )
                    {
                            echo "\n\n+-+-+-+-+-+-\n".$gpsdata_t["lat"]."+-\n".$gpsdata_t["long"]."+-\n".$gpsdata_t["sats"]."+-\n".$gpsdata_t["date"]."+-\n".$gpsdata_t["time"]."+-\n";
                    }
                    $gpschk =& database::check_gps_array($gpsdata, $gps_test);
                    if ($gpschk[0]==0)
                    {
                        if ($GLOBALS["debug"] ==1)
                        {echo "\$n = ".$n."\n\$N = ".$N."\n";}
                        $n++;
                        $N++;
                        $sig=$n.",".$level;
                        $gpsdata[$n]=array(
                                        "id"=>$n,
                                        "lat"=>$lat,
                                        "long"=>$long,
                                        "sats"=>'0',
                                        "hdp"=> '0.0',
                                        "alt"=> $alt,
                                        "geo"=> '-0.0',
                                        "kmh"=> '0.0',
                                        "mph"=> '0.0',
                                        "track"=> '0.0',
                                        "date"=>$date,
                                        "time"=>$time
                                        );
                        $apdata[$N]=array(
                                        "ssid"=>$ssid,
                                        "mac"=>$mac,
                                        "man"=>$man,
                                        "auth"=>$authen,
                                        "encry"=>$encry,
                                        "sectype"=>$sectype,
                                        "radio"=>$radio,
                                        "chan"=>$chan,
                                        "btx"=>"0",
                                        "otx"=>"0",
                                        "nt"=>$nt,
                                        "label"=>"Unknown",
                                        "sig"=>$sig
                                        );
                        if ($GLOBALS["debug"] == 1 )
                        {
                            echo "\n\n+_+_+_+_+_+_\n".$gpsdata[$n]["lat"]."  +_\n".$gpsdata[$n]["long"]."  +_\n".$gpsdata[$n]["sats"]."  +_\n".$gpsdata[$n]["date"]."  +_\n".$gpsdata[$n]["time"]."  +_\n";
                            echo "Access Point Number: ".$N."\n";
                            echo "=-=-=-=-=-=-\n".$apdata[$N]["ssid"]."  =-\n".$apdata[$N]["mac"]."  =-\n".$apdata[$N]["auth"]."  =-\n".$apdata[$N]["encry"]."  =-\n".$apdata[$N]["sectype"]."  =-\n".$apdata[$N]["radio"]."  =-\n".$apdata[$N]["chan"]."  =-\n".$apdata[$N]["btx"]."  =-\n".$apdata[$N]["otx"]."  =-\n".$apdata[$N]["nt"]."  =-\n".$apdata[$N]["label"]."  =-\n".$apdata[$N]["sig"]."\n";
                        }
                    }else
                    {
                        if ($GLOBALS["debug"] ==1)
                        {echo "\$n = ".$n."\n\$N = ".$N."\n";}
                        $N++;
                        $sig=$n.",".$level;
                        if ($GLOBALS["debug"] ==1 ){echo "\nduplicate GPS data, not entered into array\n";}
                        $apdata[$N]=array(
                                        "ssid"=>$ssid,
                                        "mac"=>$mac,
                                        "man"=>$man,
                                        "auth"=>$authen,
                                        "encry"=>$encry,
                                        "sectype"=>$sectype,
                                        "radio"=>$radio,
                                        "chan"=>$chan,
                                        "btx"=>"0",
                                        "otx"=>"0",
                                        "nt"=>$nt,
                                        "label"=>"Unlabeled",
                                        "sig"=>$sig
                                        );
                        if ($GLOBALS["debug"] == 1 )
                        {
                            echo "Access Point Number: ".$N."\n";
                            echo "=-=-=-=-=-=-\n".$apdata[$N]["ssid"]."=-\n".$apdata[$N]["mac"]."=-\n".$apdata[$N]["auth"]."=-\n".$apdata[$N]["encry"]."=-\n".$apdata[$N]["sectype"]."=-\n".$apdata[$N]["radio"]."=-\n".$apdata[$N]["chan"]."=-\n".$apdata[$N]["btx"]."=-\n".$apdata[$N]["otx"]."=-\n".$apdata[$N]["nt"]."=-\n".$apdata[$N]["label"]."=-\n".$apdata[$N]["sig"]."\n";
                        }
                    }
                    unset($gpsdata_t[0]);
                }
            }
            #############################
            # Now write the VS1 file
            #############################
            if ($out == "file" or $out == "File" or $out=="FILE")
            {
                $n = 1;
                # Dump GPS data to VS1 File
                $h1 = "# Vistumbler VS1 - Detailed Export Version 3.0\r\n# Created By: RanInt WiFi DB Alpha \r\n# -------------------------------------------------\r\n# GpsID|Latitude|Longitude|NumOfSatalites|Date|Time\r\n# -------------------------------------------------\r\n";
                fwrite($fileappend, $h1);
                foreach( $gpsdata as $gps )
                {
                //	GPS Convertion  if needed, check for ddmm.mmmm and leave it alone, otherwise i am guessing its DD.mmmmm and that needs to be converted to ddmm.mmmm:
                    $lat_epx = explode(" ", $gps['lat']);
                    if(strlen($lat_exp[0])>3)
                    {
                        $lat  =& $gps['lat'];
                        $long =& $gps['long'];
                    }else
                    {
                        $lat  =& database::convert_dd_dm($gps['lat']);
                        $long =& database::convert_dd_dm($gps['long']);
                    }
                    if(substr($lat,0,1) == "-")
                    {
                            $lat = "S ".str_replace("-", "", $lat);
                    }else
                    {
                            $lat = "N ".$lat;
                    }
                    if(substr($long,0,1) == "-")
                    {
                            $long = "W ".str_replace("-", "", $long);
                    }else
                    {
                            $long = "E ".$long;
                    }
            //	END GPS convert

                    if ($GLOBALS["debug"] ==1 ){echo "Lat : ".$gps['lat']." - Long : ".$gps['long']."\n";}
                    $gpsd = $n."|".$lat."|".$long."|".$gps["sats"]."|".$gps["hdp"]."|".$gps["alt"]."|".$gps["geo"]."|".$gps["kmh"]."|".$gps["mph"]."|".$gps["track"]."|".$gps["date"]."|".$gps["time"]."\r\n";
                    if($GLOBALS["debug"] == 1){ echo $gpsd;}
                    fwrite($fileappend, $gpsd);
                    $n++;
                }
                $n=1;

                $ap_head = "# ---------------------------------------------------------------------------------------------------------------------------------------------------------\r\n# SSID|BSSID|MANUFACTURER|Authetication|Encryption|Security Type|Radio Type|Channel|Basic Transfer Rates|Other Transfer Rates|Network Type|Label|GpsID,SIGNAL\r\n# ---------------------------------------------------------------------------------------------------------------------------------------------------------\r\n";
                fwrite($fileappend, $ap_head);
                foreach($apdata as $ap)
                {
                    $apd = $ap["ssid"]."|".$ap["mac"]."|".$ap["man"]."|".$ap["auth"]."|".$ap["encry"]."|".$ap["sectype"]."|".$ap["radio"]."|".$ap["chan"]."|".$ap["btx"]."|".$ap["otx"]."|".$ap["nt"]."|".$ap["label"]."|".$ap["sig"]."\r\n";
                    if($GLOBALS["debug"] == 1){echo $apd;}
                    fwrite($fileappend, $apd);
                    $n++;

                }
                $end = date("H:i:s");
                $GPSS=count($gpsdata);
                $APS=count($apdata);
                echo "\n\n------------------------------\nTotal Number of Access Points : ".$APS."\nTotal Number of GPS Points : ".$GPSS."\n------------------------------\nDONE!\nStart Time : ".$start."\nStop Time : ".$end."\n-------";
                return $filename;
            }
	}




	#========================================================================================================================#
	#													VS1 File import													     #
	#========================================================================================================================#

	function import_vs1($source="" , $file_id=0, $user="Unknown" , $notes="No Notes" , $title="UNTITLED", $verbose = 1 , $out = "CLI", $times = "")
	{
	    error_reporting(E_ALL|E_STRICT);
	    #MESSAGES FOR CLI AND HTML INTERFACES#
	    $Inserted_user_data_good_msg	= "Succesfully Inserted User data into Users table.";
	    $failed_import_user_data_msg	= "Failed to Insert User data into Users table.";
	    $text_file_support_ms		= "Text files are no longer supported, please save your list as a VS1 file or use the Extra->Wifidb menu option in Vistumbler.";
	    $error_updating_pts_msg		= "Error Updating Pointers table with new AP.";
	    $error_updating_stgs_msg		= "Error Updating Settings table with new size.";
	    $updating_stgs_good_msg		= "Updated Settings table with new size.";
	    $error_retrev_file_name_CLI_msg	= "There was an error sending the file name to the function.";
	    $error_retrev_file_name_HTML_msg	= "You did not submit a file, please <A HREF=\"javascript:history.go(-1)\"> [- Go Back -]</A> and do so.";
	    $emtpy_file_err_msg			= "You cannot upload an empty VS1 file, at least scan for a few seconds to import some data.";
	    $error_reserv_user_row		= "Could not reserve user import row!";
	    $no_aps_in_file_msg			= "This File does not have any APs to import, just a bunch of GPS cords.";
	    $updated_tmp_table_msg		= "Updated files_tmp table with this runs data.";
	    $too_many_unique_aps_error_msg	= "There are too many Pointers for this one Access Point, defaulting to the first one in the list.";
	    $being_updated_msg			= "is being updated.";
	    $error_running_gps_check_msg	= "There was an error running gps check.";
	    $failed_gps_add			= "FAILED to added GPS History to Table";
	    $being_imported_msg			= "is being imported";
	    $failed_insert_sig_msg		= "FAILED to insert the Signal data.";
	    $failed_insert_gps_msg		= "FAILED to insert the GPS data.";
	    $failed_create_gps_msg		= "FAILED to create the GPS History Table.";
	    $failed_create_sig_msg		= "FAILED to create Signal History Table";
	    $Finished_inserting_sig_msg		= "Finished Inserting Signal History into its table";
	    $Error_inserting_sig_msg		= "Error inserting signal history into its table";
	    $Finished_inserting_gps_msg		= "Finished Inserting GPS History into its table";
	    $Error_inserting_gps_msg		= "Error inserting GPS history into its table";
	    $text_files_support_msg		= "Text Files are not longer supported, either re-export it from Vistumbler or use the converter.exe";
	    $insert_up_gps_msg			= "Error inserting Updated GPS point";
	    $removed_old_gps_msg		= "Error removing old GPS point";

	    // define initial write and appends
	    $filename = ("mass_import_errors.log");
	    if(!file_exists($filename)){$filewrite = fopen($filename, "w");}
	    $fileappend = fopen($filename, "a");

	    if($times == "")$times = date('Y-m-d H:i:s');

	    if($out == "HTML"){$verbose = 1;}
	    if ($source == NULL)
	    {
		logd($error_retrev_file_name_CLI_msg."\r\n", $log_interval, 0,  $log_level);
		if($out=="CLI")
		{
		    verbosed($GLOBALS['COLORS']['RED'].$error_retrev_file_name_CLI_msg."\r\n".$GLOBALS['COLORS']['LIGHTGRAY'], $verbose, "CLI");
		    break;
		}elseif($out=="HTML")
		{
		    verbosed("<h2>".$error_retrev_file_name_HTML_msg."</h2>", $verbose);
		    if($out == "HTML"){footer($_SERVER['SCRIPT_FILENAME']);}die();
		}
	    }

	    $file_row =  0;
	    echo $GLOBALS['wifidb_install']."\r\n";
	    require $GLOBALS['wifidb_install']."/lib/config.inc.php";
	    require $GLOBALS['wifidb_tools'].'/daemon/config.inc.php';

	    $conn	=   $GLOBALS['conn'];
	    $db		=   $GLOBALS['db'];
	    $db_st	=   $GLOBALS['db_st'];
	    $wtable	=   $GLOBALS['wtable'];
	    $users_t	=   $GLOBALS['users_t'];
	    $files_tmp	=   $GLOBALS['files_tmp'];
	    $gps_ext	=   $GLOBALS['gps_ext'];
	    $root	=   $GLOBALS['root'];
	    $half_path	=   $GLOBALS['half_path'];

	    $file_exp	    = explode("/", $source);
	    $file_exp_seg   = count($file_exp);
	    $file1	    = $file_exp[$file_exp_seg-1];

	    $FILENUM	 = 1;
	    $start	 = microtime(true);

	    $user_n	 = 0;
	    $N		 = 0;
	    $n		 = 0;
	    $co		 = 0;
	    $cco	 = 0;
	    $updated	 = 0;
	    $imported	 = 0;
	    $apdata	 = array();
	    $gpdata	 = array();
	    $signals	 = array();
	    $sats_id	 = array();
	    $db_gps	 = array();
	    //echo $source."\r\n";
	    $return	 = explode("\n", utf8_decode(file_get_contents($source)));
	    #$return	= explode("\n", utf8_decode(implode("\n", file($source))));
	    //echo $return[0]."\r\n";
	    $count = count($return);
	    echo $count."\r\n";
	    ####
	    #die();
	    ####
	    ####
	    ####
	    ####
	    $file_row =  0;
	    if($count <= 8)
	    {
		logd($empty_file_err_msg."\r\n", $log_interval, 0,  $log_level);
		if($out=="CLI")
		{
		    verbosed($GLOBALS['COLORS']['RED'].$empty_file_err_msg."\n".$GLOBALS['COLORS']['LIGHTGRAY'], $verbose, "CLI");
		    break;
		}elseif($out=="HTML")
		{
		    verbosed("<h2>".$empty_file_err_msg."</h2>", $verbose);
		    if($out == "HTML"){footer($_SERVER['SCRIPT_FILENAME']);}die();
		}
	    }
	    $hash = hash_file('md5', $source);
	    $user_row_old_result = mysql_query("SELECT `id` FROM `$db`.`$users_t` WHERE `hash` LIKE '$hash'", $conn);
	    $user_row_old = mysql_fetch_array($user_row_old_result);
	    if($user_row_old['id'] == "")
	    {
		$sqlu = "INSERT INTO `$db`.`$users_t` ( `id` , `username` , `notes` , `title`, `hash`) VALUES ( '' , '$user' , '$notes' , '$title', '$hash')";
		if(!mysql_query($sqlu, $conn))
		{
		    logd($error_reserv_user_row."!\r\n".mysql_error($conn), $log_interval, 0,  $log_level);
		    if($out=="CLI")
		    {
			verbosed($GLOBALS['COLORS']['RED'].$error_reserv_user_row."\n".$GLOBALS['COLORS']['LIGHTGRAY'].mysql_error($conn), $verbose, "CLI");
			die();
		    }elseif($out=="HTML")
		    {
			verbosed("<p>".$error_reserv_user_row."</p>".mysql_error($conn), $verbose);
			if($out == "HTML"){footer($_SERVER['SCRIPT_FILENAME']);}die();
		    }
		}else
		{
		    $user_row_id = mysql_insert_id($conn);
		}
	    }else
	    {
		$user_row_id = $user_row_old['id'];
	    }
	    #echo "Sleep for echo review\r\n";
	    #sleep(10);
	    foreach($return as $key=>$ret)
	    {
		$current_encoding = mb_detect_encoding($ret, 'auto');
		mb_internal_encoding($current_encoding);
		$ret = iconv($current_encoding, 'UTF-8//IGNORE', $ret);
		if($key == 0)
		{
		    $ret = str_replace("?","",$ret);
		}
		$ret_0 = substr($ret,0,1);

		#var_dump($ret_0);

		#var_dump($ret);

		if ($ret_0 == "#"){continue;}

		#die();

		$retexp = explode("|",$ret);
		#var_dump($retexp);
		$ret_len = count($retexp);
		#echo $ret_len."\r\n";
		#echo $ret."\r\n";
		if ($ret_len == 12 or $ret_len == 6)
		{
		    $gdata[$retexp[0]] = $this->gen_gps($retexp);
		}elseif($ret_len == 13)
		{
		    $gpscount = count($gdata);
		    if(!isset($SETFLAGTEST))
		    {
			$count1 = ($count - $gpscount)-1;
			echo "GPS: $gpscount\r\nFULL: $count\r\nCalc: $count1\r\n";
			$count1 = $count1 - 8;
			if($count1 == 0)
			{
			    logd($no_aps_in_file_msg."\r\n", $log_interval, 0,  $log_level);
			    if($out=="CLI")
			    {
				verbosed($GLOBALS['COLORS']['RED'].$no_aps_in_file_msg."\n".$GLOBALS['COLORS']['LIGHTGRAY'], $verbose, "CLI");
				if($out == "HTML"){footer($_SERVER['SCRIPT_FILENAME']);}die();
			    }elseif($out=="HTML")
			    {
				verbosed("<p>".$no_aps_in_file_msg."</p>", $verbose);
				if($out == "HTML"){footer($_SERVER['SCRIPT_FILENAME']);}die();
			    }
			}
		    }
		    echo "########################################\r\n";//$source\r\n";
		    $SETFLAGTEST = TRUE;
		    $wifi = explode("|",$ret, 13);
		    if($wifi[0] == "" && $wifi[1] == "" && $wifi[5] == "" && $wifi[6] == "" && $wifi[7] == ""){continue;}
		    $dbsize = mysql_query("SELECT `id` FROM `$db`.`$wtable` order by id desc limit 1", $GLOBALS['conn']);
		    $size1 = mysql_fetch_array($dbsize);
		    $size = ($size1['id']+0)+1;
		    //You cant have any blank data, thats just rude...
		    if($wifi[0] == ''){$wifi[0]="UNNAMED";}
		    #$wifi[0] = utf8_encode($wifi[0]);
		    #echo $wifi[0]."\r\n";
		    #var_dump($wifi);
		    if($wifi[1] == ''){$wifi[1] = "00:00:00:00:00:00";}
		    if($wifi[5] == ''){$wifi[5] = "0";}
		    if($wifi[6] == ''){$wifi[6] = "u";}
		    if($wifi[7] == ''){$wifi[7] = "0";}
		    list($ssid_S, $ssids, $ssidss ) = make_ssid($wifi[0]);//Use the 25 char long word for the APs table name, this is due to a limitation in MySQL table name lengths, the rest of the info will suffice for unique table names
		    $this_of_this = $FILENUM." / ".$count1;
		    $file1 = str_replace(" ", "%20", $file1);
		    $sqlup = "UPDATE `$db`.`$files_tmp` SET `importing` = '1', `tot` = '$this_of_this', `ap` = '$ssid_S', `row` = '$file_row' WHERE `id` = '$file_id';";
		#echo $sqlup."\r\n";
		    if (mysql_query($sqlup, $conn) or die(mysql_error($conn)))
		    {
			logd($updated_tmp_table_msg."\r\n", $log_interval, 0,  $log_level);
			verbosed($GLOBALS['COLORS']['GREEN'].$updated_tmp_table_msg."\n".$GLOBALS['COLORS']['LIGHTGRAY'], $verbose, "CLI");
		    }
		    $mac1 = explode(':', $wifi[1]);
		    $macs = $mac1[0].$mac1[1].$mac1[2].$mac1[3].$mac1[4].$mac1[5]; //the APs table doesnt need :'s in its name, nor does the Pointers table, well it could I just dont want to
		    $auth	=   mysql_real_escape_string($wifi[3]);
		    $encry	=   mysql_real_escape_string($wifi[4]);
		    $sectype	=   mysql_real_escape_string($wifi[5]);
		    #echo $wifi[5]."\r\n";
		    #echo $sectype."\r\n";
		    $chan	=   mysql_real_escape_string($wifi[7]);
		    $chan	=   $chan+0;
		    $btx	=   mysql_real_escape_string($wifi[8]);
		    $otx	=   mysql_real_escape_string($wifi[9]);
		    $nt		=   mysql_real_escape_string($wifi[10]);
		    $label	=   mysql_real_escape_string($wifi[11]);
		    $san_sig	=   mysql_real_escape_string($wifi[12]);
		    $san_sig	=   str_replace("\r\n","",$san_sig);
		    $signal_exp =   explode("-",$san_sig);
		    foreach($signal_exp as $key=>$val)
		    {
			$num_san_sig = explode(",",$val);
			$NUM_SIG[$key] = ($num_san_sig[0]+0).",".($num_san_sig[1]+0);
			$vs1_id = $num_san_sig[0];
			if(!@$gdata[$vs1_id]["lat"]){continue;}
			$lat = $gdata[$vs1_id]["lat"];
			$lat_exp = explode(" ", $lat);
			if(isset($lat_exp[1]))
			{
			    $test = $lat_exp[1]+0;
			}else
			{
			    $test = $lat_exp[0]+0;
			}
			if(!$test)
			{
			    $zero = 1;
			}else
			{$zero=0;break;}
		    }
		    if($zero)
		    {
			verbosed("SKIPPING AP, NO GPS COORDS.", $verbose, "CLI");
			$skip_kml = 1;
		    }else
		    {
			$skip_kml = 0;
		    }
		    if($wifi[6] == "802.11a")
			{$radios = "a";}
		    elseif($wifi[6] == "802.11b")
			{$radios = "b";}
		    elseif($wifi[6] == "802.11g")
			{$radios = "g";}
		    elseif($wifi[6] == "802.11n")
			{$radios = "n";}
		    else
			{$radios = "U";}

		    $pt_sql = "SELECT id,ssid,mac,chan,sectype,auth,encry,radio FROM
			`$db`.`$wtable`
			WHERE `mac` = '$macs'
			AND `ssid` = '$ssids' collate utf8_bin
			AND `chan` = '$chan'
			AND `sectype` = '$sectype'
			AND `radio` = '$radios' LIMIT 1";
		    //echo $pt_sql."\r\n";

		    $result = mysql_query($pt_sql, $conn) or die(mysql_error($conn));
		    $rows = mysql_num_rows($result);
		    $newArray = mysql_fetch_array($result);

		    var_dump($newArray['id']);

		    $APid = $newArray['id'];
		    list($ssid_pt_S) = make_ssid($newArray['ssid']);
		    $mac_pt = $newArray['mac'];
		    $sectype_pt = $newArray['sectype'];
		    $radio_pt = $newArray['radio'];
		    $chan_pt = $newArray['chan'];
		    $auth_pt = $newArray['auth'];
		    $encry_pt = $newArray['encry'];

		    //create table name to select from, insert into, or create
		    $table_ptb = $ssid_pt_S.'-'.$mac_pt.'-'.$sectype_pt.'-'.$radio_pt.'-'.$chan_pt;
		    echo $table_ptb."\r\n";
		    $table = $ssid_S.'-'.$macs.'-'.$sectype.'-'.$radios.'-'.$chan;
		    echo $table."\r\n";
		    $gps_table = $table.$gps_ext;
		    $table_ptb = iconv($current_encoding, 'UTF-8//IGNORE', $table_ptb);
		    if(!isset($table_ptb)){$table_ptb="";}
		    if(!strcmp($table,$table_ptb))
		    {
			#################
			##  UPDATE AP  ##
			#################
			logd($this_of_this."   ( ".$APid." )   ||   ".$table." - ".$being_updated_msg."\r\n", $log_interval, 0,  $log_level);
			if($out=="CLI")
			{
				verbosed($GLOBALS['COLORS']['GREEN'].$this_of_this."   ( ".$APid." )   ||   ".$table." - ".$being_updated_msg.".\n".$GLOBALS['COLORS']['LIGHTGRAY'], $verbose, "CLI");
			}elseif($out=="HTML")
			{
				verbosed('<table border="1" width="90%" class="update"><tr class="style4"><th>ID</th><th>New/Update</th><th>SSID</th><th>Mac Address</th><th>Authentication</th><th>Encryption</th><th>Radion Type</th><th>Channel</th></tr>
					<tr><td>'.$APid.'</td><td><b>U</b></td><td>'.$ssids.'</td><td>'.$macs.'</td><td>'.$auth.'</td><td>'.$encry.'</td><td>'.$radios.'</td><td>'.$chan.'</td></tr><tr><td colspan="8">', $verbose, "HTML");
			}
			//setup ID number for new GPS cords
			$DB_result = mysql_query("SELECT * FROM `$db_st`.`$gps_table`", $conn);
			$gpstableid = mysql_num_rows($DB_result);
			if ( $gpstableid == 0)
			{
			    $gps_id = 1;
			}
			else
			{
			    //if the table is already populated set it to the last ID's number
			    $gps_id = $gpstableid+0;
			    $gps_id++;
			}
			verbosed($GLOBALS['COLORS']['LIGHTGRAY']."GPS points already in table: ".$gpstableid." - GPS_ID: ".$gps_id."\n".$GLOBALS['COLORS']['LIGHTGRAY'], $verbose, "CLI");
			$N=0;
			$prev='';
			$sql_multi = array();
			$signals = array();
			$NNN = 0;
			$r=0;
			$sig_counting = count($signal_exp)-1;
			foreach($signal_exp as $key=>$exp)
			{
#			    echo "Pre loop: ".$gps_id."\n".$dbid."\n";
			    //Create GPS Array for each Singal, because the GPS table is growing for each signal you need to re-grab it to test the data
			    $esp = explode(",",$exp);
			    $vs1_id = $esp[0];
			    $signal = str_replace("\r\n", "", $esp[1]);
			    if(!@$gdata[$vs1_id]){continue;}
			    $lat = $gdata[$vs1_id]["lat"];
			    $long = $gdata[$vs1_id]["long"];

                            $lat_sub = $lat[0];

                            if($lat_sub != "-" && is_numeric($lat_sub))
                            {
                               $lat = "N ".$lat;
                            }else #if(is_int(substr($gps['lat'], 0,1))+0)
                            {
                               $lat = str_replace("-", "S ", $lat);
                            }
                            ######
                            ######
                            ######
                            #var_dump(substr($gps['long'], 0,1));
                            $long_sub = $long[0];

                            if($long_sub != "-" && is_numeric($long_sub))
                            {
                               $long = "E ".$long;
                            }else #if(is_int(substr($gps['long'], 0,1)+0))
                            {
                               $long = str_replace("-", "W ", $long);
                            }
                            if($lat == "N 0.0000" || $lat == "N 0000.0000")
                            {$skip_update = 1;}
			    $sats = $gdata[$vs1_id]["sats"];
			    $date = $gdata[$vs1_id]["date"];
			    $time = str_replace("\r\n", "", $gdata[$vs1_id]["time"]);
			    $hdp = $gdata[$vs1_id]["hdp"];
			    $alt = $gdata[$vs1_id]["alt"];
			    $geo = $gdata[$vs1_id]["geo"];
			    $kmh = $gdata[$vs1_id]["kmh"];
			    $mph = $gdata[$vs1_id]["mph"];
			    $track = $gdata[$vs1_id]["track"];
			    if(!@$gps_id)
			    {
				$sql = "select id from `$db_st`.`$gps_table` order by id desc limit 1";
				$result = mysql_query($sql, $conn);
				$largest = mysql_fetch_array($result);
				$gps_id = $largest['id']++;
			    }
			    $sql_multi[] = "INSERT INTO `$db_st`.`$gps_table` ( `id` , `lat` , `long` , `sats`, `hdp`, `alt`, `geo`, `kmh`, `mph`, `track` , `date` , `time` ) "
					    ."VALUES ( '$gps_id', '$lat', '$long', '$sats', '$hdp', '$alt', '$geo', '$kmh', '$mph', '$track', '$date', '$time');";
			    $signals[] = $gps_id.",".$signal;
			    if($result = mysql_query($sql_multi[$N], $conn))
			    {
			       # if($verbose == 1 && $out == "CLI"){echo "New GPS inserted. [$gps_id]\r\n";}
			    }else
			    {
				echo "failed\r\n";
				#echo $sql_multi[$N]."\r\n";
				#echo $signals[$N]."\r\n";
			    }
			    if($verbose == 1 && $out == "CLI")
			    {
				if($r===0){echo "|\r";}
				if($r===10){echo "/\r";}
				if($r===20){echo "-\r";}
				if($r===30){echo "|\r";}
				if($r===40){echo "/\r";}
				if($r===50){echo "-\r";}
				if($r===60){echo "\\\r";$r=0;}
				$r++;
			    }
			    $gps_id++;
			    $N++;
			}
			unset($gps_id);
			if($verbose == 1 && $out == "CLI"){echo " \n";}
			echo count($signals)."\r\n";
			if($out=="HTML")
			{
			    $DB_COUNT = count($db_gps);
			    logd("Total GPS in DB: ".$DB_COUNT." || GPS Imports: ".$NNN." .\r\n".mysql_error($conn), $log_interval, 0,  $log_level);
			    verbosed("Total GPS in DB: ".$DB_COUNT."<br>GPS Imports: ".$NNN."<br>".mysql_error($conn), $verbose, "HTML");
			    ?>
				</td></tr>
				<td colspan="8">
			    <?php
			}
			$exp = explode(",",@$signals[$N-1]);
			if($exp[0] == 0){unset($signals[$N-1]);}
			$sig = implode("-",$signals);
			$sqlit = "INSERT INTO `$db_st`.`$table` ( `id` , `btx` , `otx` , `nt` , `label` , `sig`, `user` ) VALUES ( '', '$btx', '$otx', '$nt', '$label', '$sig', '$user')";
			if (!mysql_query($sqlit, $conn))
			{
			    logd($failed_sig_add.".\r\n".mysql_error($conn), $log_interval, 0,  $log_level);
			    if($out=="CLI")
			    {
				verbosed($GLOBALS['COLORS']['RED'].$failed_sig_add.".\n".$GLOBALS['COLORS']['LIGHTGRAY'].mysql_error($conn), $verbose, "CLI");
			    }elseif($out=="HTML")
			    {
				verbosed("<p>".$failed_sig_add."</p>".mysql_error($conn), $verbose, "HTML");
			    }
			    if($out == "HTML"){footer($_SERVER['SCRIPT_FILENAME']);}die();
			}
                        if(!@$skip_update)
                        {
                            $sql = "UPDATE `$db`.`$wtable` SET `lat` = '$lat', `long` = '$long' WHERE `id` = '$APid'";
                            $result = mysql_query($sql, $conn);
                            if(!$result)
                            {
                                logd($failed_gps_update.".\r\n".mysql_error($conn), $log_interval, 0,  $log_level);
                                if($out=="CLI")
                                {
                                    verbosed($GLOBALS['COLORS']['RED'].$failed_gps_update.".\n".$GLOBALS['COLORS']['LIGHTGRAY'].mysql_error($conn), $verbose, "CLI");
                                }elseif($out=="HTML")
                                {
                                    verbosed("<p>".$failed_gps_update."</p>".mysql_error($conn), $verbose, "HTML");
                                }
                                if($out == "HTML"){footer($_SERVER['SCRIPT_FILENAME']);}die();
                            }
                        }
			$sqlit_ = "SELECT * FROM `$db_st`.`$table`";
			$sqlit_res = mysql_query($sqlit_, $conn) or die(mysql_error($conn));
			$sqlit_num_rows = mysql_num_rows($sqlit_res);
			$user_aps[$user_n]="1,".$APid.":".$sqlit_num_rows; //User import tracking //UPDATE AP
			logd($user_aps[$user_n], $log_interval, 0,  $log_level);
			if($out=="CLI")
			{
			    verbosed($GLOBALS['COLORS']['GREEN'].$user_aps[$user_n]."\n".$GLOBALS['COLORS']['LIGHTGRAY'], $verbose."\n".$GLOBALS['COLORS']['LIGHTGRAY'].mysql_error($conn), $verbose, "CLI");
			}elseif($out=="HTML")
			{
			    verbosed($user_aps[$user_n]."<br>", $verbose, "HTML");
			}
			$user_n++;
			$updated++;
			if($out == "HTML")
			{
			    ?>
			    </td></tr></table><br>
			    <?php
			}
		    }else
		    {
			##############
			##  New AP  ##
			##############
			$skip_pt_insert=0;
			logd($this_of_this."   ( ".$size." )   ||   ".$table." - ".$being_imported_msg."\r\n", $log_interval, 0,  $log_level);
			if($out=="CLI")
			{
			    verbosed($GLOBALS['COLORS']['GREEN'].$this_of_this."   ( ".$size." )   ||   ".$table." - ".$being_imported_msg.".\n".$GLOBALS['COLORS']['LIGHTGRAY'], $verbose, "CLI");
			}elseif($out=="HTML")
			{
			    verbosed('<table border="1" width="90%" class="new"><tr class="style4"><th>ID</th><th>New/Update</th><th>SSID</th><th>Mac Address</th><th>Authentication</th><th>Encryption</th><th>Radion Type</th><th>Channel</th></tr>
				    <tr><td>'.$size.'</td><td><b>N</b></td><td>'.$ssids.'</td><td>'.$macs.'</td><td>'.$auth.'</td><td>'.$encry.'</td><td>'.$radios.'</td><td>'.$chan.'</td></tr><tr><td colspan="8">', $verbose, "HTML");
			}
			$signal_exp = explode("-",$san_sig);
			$sqlct = "CREATE TABLE `$db_st`.`$table` (
			    `id` INT( 255 ) NOT NULL AUTO_INCREMENT ,
			    `btx` VARCHAR( 10 ) NOT NULL ,
			    `otx` VARCHAR( 10 ) NOT NULL ,
			    `nt` VARCHAR( 15 ) NOT NULL ,
			    `label` VARCHAR( 25 ) NOT NULL ,
			    `sig` TEXT NOT NULL ,
			    `user` VARCHAR(255) NOT NULL ,
			    PRIMARY KEY (`id`)
			    ) ENGINE = 'InnoDB' DEFAULT CHARSET='utf8'";
	#		echo "(1)Create Table [".$db_st."].{".$table."}\n		 => Added new Table for ".$ssids."\n";
			if(!mysql_query($sqlct, $conn))
			{
			    logd($failed_create_sig_msg."\r\n\t-> ".$sqlct." - ".mysql_error($conn), $log_interval, 0,  $log_level);
			    if($out=="CLI")
			    {
				verbosed($GLOBALS['COLORS']['RED'].$failed_create_sig_msg."\n\t->".$GLOBALS['COLORS']['LIGHTGRAY'].mysql_error($conn), $verbose, "CLI");
			    }elseif($out=="HTML")
			    {
				verbosed("<p>".$failed_create_sig_msg."\t-> ".$sqlct." - ".mysql_error($conn)."</p>", $verbose, "HTML");
			    }
			    $skip_pt_insert = 1;
			}
			$sqlcgt = "CREATE TABLE `$db_st`.`$gps_table` (`id` INT( 255 ) NOT NULL AUTO_INCREMENT ,`lat` VARCHAR( 25 ) NOT NULL ,`long` VARCHAR( 25 ) NOT NULL ,`sats` INT( 2 ) NOT NULL ,`hdp` FLOAT NOT NULL ,`alt` FLOAT NOT NULL ,`geo` FLOAT NOT NULL ,`kmh` FLOAT NOT NULL ,`mph` FLOAT NOT NULL ,`track` FLOAT NOT NULL ,`date` VARCHAR( 10 ) NOT NULL ,`time` VARCHAR( 8 ) NOT NULL ,INDEX ( `id` ), UNIQUE( `id` )) ENGINE = 'InnoDB' DEFAULT CHARSET='utf8'";
			if(!mysql_query($sqlcgt, $conn))
			{
			    logd($failed_create_gps_msg."\r\n\t-> ".$sqlcgt." - ".mysql_error($conn), $log_interval, 0,  $log_level);
			    if($out=="CLI")
			    {
				verbosed($GLOBALS['COLORS']['RED'].$failed_create_gps_msg."\n\t-> ".$GLOBALS['COLORS']['LIGHTGRAY'].mysql_error($conn), $verbose, "CLI");
				if($skip_pt_insert == 0){die();}
			    }elseif($out=="HTML")
			    {
				verbosed("<p>".$failed_create_gps_msg."</p>\t-> ".mysql_error($conn), $verbose, "HTML");
				if($skip_pt_insert == 0){footer($_SERVER['SCRIPT_FILENAME']);die();}
			    }
			    $skip_pt_insert = 1;
			}
                        $gps_id = 1;
			$N=0;
			$r=0;
			$prev='';
			$sql_multi = array();
			$signal_exp = explode("-",$san_sig);
			$sig_counting = count($signal_exp)-1;
			foreach($signal_exp as $key=>$exp)
			{
#			    echo "Pre loop: ".$gps_id."\n".$dbid."\n";
			    //Create GPS Array for each Singal, because the GPS table is growing for each signal you need to re-grab it to test the data
			    $esp = explode(",",$exp);
			    $vs1_id = $esp[0];
			    $signal = str_replace("\r\n", "", $esp[1]);

			    if(!@$gdata[$vs1_id]){continue;}

                            $lat = $gdata[$vs1_id]["lat"];
			    $long = $gdata[$vs1_id]["long"];
                            $lat_sub = $lat[0];

                            if($lat_sub != "-" && is_numeric($lat_sub))
                            {
                               $lat = "N ".$lat;
                            }else #if(is_int(substr($gps['lat'], 0,1))+0)
                            {
                               $lat = str_replace("-", "S ", $lat);
                            }
                            ######
                            ######
                            ######
                            #var_dump(substr($gps['long'], 0,1));
                            $long_sub = $long[0];

                            if($long_sub != "-" && is_numeric($long_sub))
                            {
                               $long = "E ".$long;
                            }else #if(is_int(substr($gps['long'], 0,1)+0))
                            {
                               $long = str_replace("-", "W ", $long);
                            }

			    $sats = $gdata[$vs1_id]["sats"];
			    $date = $gdata[$vs1_id]["date"];
			    $time = str_replace("\r\n", "", $gdata[$vs1_id]["time"]);
			    $hdp = $gdata[$vs1_id]["hdp"];
			    $alt = $gdata[$vs1_id]["alt"];
			    $geo = $gdata[$vs1_id]["geo"];
			    $kmh = $gdata[$vs1_id]["kmh"];
			    $mph = $gdata[$vs1_id]["mph"];
			    $track = $gdata[$vs1_id]["track"];
			    if(!@$gps_id)
			    {
				$sql = "select id from `$db_st`.`$gps_table` order by id desc limit 1";
				$result = mysql_query($sql, $conn);
				$largest = mysql_fetch_array($result);
				$gps_id = $largest['id']++;
			    }
			    $sql_multi[] = "INSERT INTO `$db_st`.`$gps_table` ( `id` , `lat` , `long` , `sats`, `hdp`, `alt`, `geo`, `kmh`, `mph`, `track` , `date` , `time` ) "
					    ."VALUES ( '$gps_id', '$lat', '$long', '$sats', '$hdp', '$alt', '$geo', '$kmh', '$mph', '$track', '$date', '$time');";
			    $signals[] = $gps_id.",".$signal;
			    if($result = mysql_query($sql_multi[$N], $conn))
			    {
			       # if($verbose == 1 && $out == "CLI"){echo "New GPS inserted. [$gps_id]\r\n";}
			    }else
			    {
				echo "failed\r\n";
				#echo $sql_multi[$N]."\r\n";
				#echo $signals[$N]."\r\n";
			    }
			    if($verbose == 1 && $out == "CLI")
			    {
				if($r===0){echo "|\r";}
				if($r===10){echo "/\r";}
				if($r===20){echo "-\r";}
				if($r===30){echo "|\r";}
				if($r===40){echo "/\r";}
				if($r===50){echo "-\r";}
				if($r===60){echo "\\\r";$r=0;}
				$r++;
			    }
			    $gps_id++;
			    $N++;
			}
			# pointers
			$sqlp = "INSERT INTO `$db`.`$wtable` ( `id` , `ssid` , `mac` ,  `chan`, `radio`,`auth`,`encry`, `sectype`, `lat`, `long` ) VALUES ( '', '$ssids', '$macs','$chan', '$radios', '$auth', '$encry', '$sectype', '$lat', '$long')";
			if(mysql_query($sqlp, $conn))
			{
			    $user_aps[$user_n]="0,".mysql_insert_id($conn).":1";
			    $sqlup = "UPDATE `$db`.`$settings_tb` SET `size` = '$size' WHERE `table` = 'wifi0' LIMIT 1;";
			    if (mysql_query($sqlup, $conn))
			    {
				logd($updating_stgs_good_msg."\r\n", $log_interval, 0,  $log_level);
				if($out=="CLI")
				{
				    verbosed($GLOBALS['COLORS']['GREEN'].$updating_stgs_good_msg."\n".$GLOBALS['COLORS']['LIGHTGRAY'], $verbose, "CLI");
				}elseif($out=="HTML")
				{
				    verbosed("<p>".$updating_stgs_good_msg."</p>".$GLOBALS['COLORS']['LIGHTGRAY'], $verbose, "HTML");
				}
			    }else
			    {
				logd($error_updating_stgs_msg."\r\n\t-> ".mysql_error($conn), $log_interval, 0,  $log_level);
				if($out=="CLI")
				{
				    verbosed($GLOBALS['COLORS']['RED'].$error_updating_stgs_msg."\n".$GLOBALS['COLORS']['LIGHTGRAY'].mysql_error($conn), $verbose, "CLI");
				}elseif($out=="HTML")
				{
				    verbosed("<p>".$error_updating_stgs_msg."</p>".mysql_error($conn), $verbose, "HTML");
				}
				if($out == "HTML"){footer($_SERVER['SCRIPT_FILENAME']);}die();
			    }
			    logd($user_aps[$user_n], $log_interval, 0,  $log_level);
			    if($out=="CLI")
			    {
				verbosed($GLOBALS['COLORS']['GREEN'].$user_aps[$user_n]."\n".$GLOBALS['COLORS']['LIGHTGRAY'], $verbose, "CLI");
			    }elseif($out=="HTML")
			    {
				verbosed($user_aps[$user_n]."<br>", $verbose, "HTML");
			    }
			    $user_n++;
			}else
			{
			    logd($error_updating_pts_msg."\r\n\t-> ".mysql_error($conn), $log_interval, 0,  $log_level);
			    if($out=="CLI")
			    {
				verbosed($GLOBALS['COLORS']['RED'].$error_updating_pts_msg."\n\t-> ".$GLOBALS['COLORS']['LIGHTGRAY'].mysql_error($conn), $verbose, "CLI");
			    }elseif($out=="HTML")
			    {
				verbosed("<p>".$error_updating_pts_msg."</p>".mysql_error($conn), $verbose, "HTML");
			    }
			    if($out == "HTML"){footer($_SERVER['SCRIPT_FILENAME']);}die();
			}
			$imported++;

			if($verbose == 1 && $out == "CLI"){echo " \n";}
			echo count($signals)."\r\n";
			if($out == "HTML")
			{
			    ?>
			    <tr><td colspan="8">
			    <?php
			}elseif($out == "CLI")
			{
			    if($verbose == 1){echo "\n";}
			}
			$exp = explode(",",$signals[$N-1]);
			if($exp[0] == 0){unset($signals[$N-1]);}
			$sig = implode("-" , $signals);
			$sig = str_replace("&#13;&#10;" , "" , $sig);
			$sqlit1 = "INSERT INTO `$db_st`.`$table` ( `id` , `btx` , `otx` , `nt` , `label` , `sig`, `user` ) VALUES ( '', '$btx', '$otx', '$nt', '$label', '$sig', '$user')";
			$insertsqlresult = mysql_query($sqlit1, $conn);
#				echo "(3)Insert into [".$db_st."].{".$table."}\n		 => Add Signal History to Table\n";
			if(!$insertsqlresult)
			{
			    logd($failed_insert_sig_msg."\r\n\t-> ".mysql_error($conn), $log_interval, 0,  $log_level);
			    if($out=="CLI")
			    {
				verbosed($GLOBALS['COLORS']['RED'].$failed_insert_sig_msg."\n\t-> ".$GLOBALS['COLORS']['LIGHTGRAY'].mysql_error($conn), $verbose, "CLI");
			    }elseif($out=="HTML")
			    {
				verbosed("<p>".$failed_insert_sig_msg."</p>\t-> ".mysql_error($conn), $verbose, "HTML");
			    }
			    if($out == "HTML"){footer($_SERVER['SCRIPT_FILENAME']);}die();
			}else
			{
			    logd($Finished_inserting_sig_msg."\r\n", $log_interval, 0,  $log_level);
			    if($out=="CLI")
			    {
				verbosed($GLOBALS['COLORS']['GREEN'].$Finished_inserting_sig_msg."\n".$GLOBALS['COLORS']['LIGHTGRAY'], $verbose, "CLI");
			    }elseif($out=="HTML")
			    {
				verbosed("<p>".$Finished_inserting_sig_msg."</p>", $verbose, "HTML");
			    }
			}
			###############################################
			#   KML Export of AP just imported/updated    #
			###############################################
			if(!$skip_kml){$this->exp_newest_kml($named = 0, $verbose=1);}
		    }
		    if($out == "HTML")
		    {
			?>
			</td></tr></table><br>
			<?php
		    }
		    $FILENUM++;
		    unset($ssid_ptb);
		    unset($mac_ptb);
		    unset($sectype_ptb);
		    unset($radio_ptb);
		    unset($chan_ptb);
		    unset($table_ptb);
		    if(!is_null($signals))
		    {
			unset($signals);
		    }
		}elseif($ret_len == 0)
		{
		    logd($wrong_file_type_msg, $log_interval, 0,  $log_level);
		    if($out=="CLI")
		    {
			verbosed($GLOBALS['COLORS']['RED'].$wrong_file_type_msg."\n".$GLOBALS['COLORS']['LIGHTGRAY'], $verbose, "CLI");
		    }elseif($out=="HTML")
		    {
			verbosed("<h1>".$wrong_file_type_msg.".</h1>", $verbose, "HTML");
		    }
		    if($out == "HTML"){footer($_SERVER['SCRIPT_FILENAME']);die();}
		}
	    }
	    #var_dump($user_aps);
	    if(is_array($user_aps))
	    {
		$user_ap_s = implode("-",$user_aps);
		$total_ap = count($user_aps);
	    }else
	    {
		$user_ap_s = "";
		$total_ap = "0";
	    }

	    $notes = addslashes($notes);
	    if($title === ''){$title = "Untitled";}
	    if($user === ''){$user="Unknown";}
	    if($notes === ''){$notes="No Notes";}
	    $gdatacount = count($gdata);
	    if($user_ap_s != "" and $total_ap > 0)
	    {
		$sqlu = "UPDATE `$db`.`$users_t` SET `points` = '$user_ap_s' , `date` = '$times' , `aps` =  '$total_ap' , `gps` = '$gdatacount' WHERE `id` = '$user_row_id'";
		echo $sqlu."\r\n";
		if(!mysql_query($sqlu, $conn))
		{
		    if($out=="CLI")
		    {
			verbosed($failed_import_user_data_msg.mysql_error($conn), $verbose, "CLI");
		    }elseif($out=="HTML")
		    {
			verbosed($GLOBALS['COLORS']['RED'].$failed_import_user_data_msg."\n".$GLOBALS['COLORS']['LIGHTGRAY'].mysql_error($conn), $verbose, "HTML");
		    }
		    logd($failed_import_user_data_msg.mysql_error($conn), $log_interval, 0,  $log_level);
		    if($out == "HTML"){footer($_SERVER['SCRIPT_FILENAME']);}
		    die();
		}else
		{
		    if($out=="CLI")
		    {
			verbosed($GLOBALS['COLORS']['GREEN'].$Inserted_user_data_good_msg."\n".$GLOBALS['COLORS']['LIGHTGRAY'], $verbose, "CLI");
		    }elseif($out=="HTML")
		    {
			verbosed("<p>".$Inserted_user_data_good_msg."</p>", $verbose, "HTML");
		    }
		    logd($Inserted_user_data_good_msg, $log_interval, 0,  $log_level);
		}
	    }else
	    {
		if($out=="CLI")
		{
		    verbosed($GLOBALS['COLORS']['GREEN']."File Had no APs to import, go get some better files.".$GLOBALS['COLORS']['LIGHTGRAY'], $verbose, "CLI");
		}elseif($out=="HTML")
		{
		    verbosed("<p>"."File Had no APs to import, go get some better files."."</p>", $verbose, "HTML");
		    footer($_SERVER['SCRIPT_FILENAME']);
		}
		logd("File Had no APs to import, go get some better files.", $log_interval, 0,  $log_level);
	    }
	    if($out=="CLI")
	    {
		echo "\nFile DONE!\n|\n|\n";
	    }elseif($out=="HTML")
	    {
		echo "<p>File DONE!</p>";
	    }
	    $end = microtime(true);
	    $Imp_ret = array(
			"aps" => $total_ap,
			"gps" => $gdatacount
			);
	    return $Imp_ret;
	    fclose($fileappend);
	}


	#========================================================================================================================#
	#													Convert GeoCord DM to DD									   	     #
	#========================================================================================================================#

	function &convert_dm_dd($geocord_in = "")
	{
            #echo "1) $geocord_in\r\n";
            $start = microtime(true);
    //	GPS Convertion :
            $neg=FALSE;
            $geocord_exp = explode(".", $geocord_in);//replace any Letter Headings with Numeric Headings
            $geocord_front = explode(" ", $geocord_exp[0]);
            if(substr($geocord_exp[0],0,1) == "S" || substr($geocord_exp[0],0,1) == "W" || substr($geocord_exp[0],0,1) == "-"){$neg = TRUE;}

            $patterns[0] = '/N/';
            $patterns[1] = '/E/';
            $patterns[2] = '/S/';
            $patterns[3] = '/W/';
            $patterns[4] = '/ /';
            $patterns[5] = '/-/';
            $replacements = "";
            $geocord_in = preg_replace($patterns, $replacements, $geocord_in);

            #echo "2) $geocord_in\r\n";

            $geocord_exp = explode(".", $geocord_in);
            #var_dump($geocord_exp);

            // 428.7753 ---- 428 - 7753
            $geocord_dec = "0.".$geocord_exp[1];
            // 428.7753 ---- 428 - 0.7753
            $len = strlen($geocord_exp[0]);
            #echo "Len: $len\r\n";
            $geocord_min = substr($geocord_exp[0],-2,3);
            #echo "Min: $geocord_min\r\n";
            // 428.7753 ---- 4 - 28 - 0.7753
            $geocord_min = $geocord_min+$geocord_dec;
            // 428.7753 ---- 4 - 28.7753
            $geocord_div = $geocord_min/60;
            // 428.7753 ---- 4 - (28.7753)/60 = 0.4795883

            if($len == 3)
            {
                $geocord_deg = substr($geocord_exp[0], 0,1);
                #echo "3) $geocord_deg\r\n";
            }elseif($len == 4)
            {
                $geocord_deg = substr($geocord_exp[0], 0,2);
                #echo "3) $geocord_deg\r\n";
            }elseif($len == 5)
            {
                $geocord_deg = substr($geocord_exp[0], 0,3);
                #echo "3) $geocord_deg\r\n";
            }elseif($len <= 2)
            {
                $geocord_deg = 0;
                #echo "3) $geocord_deg\r\n";
            }else
            {
                #echo $geocord_in."\r\n";
                $r = -1;
                return $r;
            }
            $geocord_out = $geocord_deg + $geocord_div;
            // 428.7753 ---- 4.4795883
            if($neg === TRUE){$geocord_out = "-".$geocord_out;}

            $end = microtime(true);
            #echo "4) $geocord_out\r\n";
            $geocord_out = substr($geocord_out, 0,10);
            return $geocord_out;
	}
	#========================================================================================================================#
	#													Convert GeoCord DD to DM									   	     #
	#========================================================================================================================#

	function &convert_dd_dm($geocord_in="")
	{
            #echo "----------------\r\n";
            $start = microtime(true);
            //	GPS Convertion :
            #echo "1) $geocord_in\r\n";
            $neg=FALSE;
            $geocord_exp = explode(".", $geocord_in);

            $front = $geocord_exp[0];
            $back = $geocord_exp[1];

            if(substr($front,0,1)=="-" || substr($front,0,1)=="S" || substr($front,0,1)=="W"){$neg = TRUE;}
            #echo "NEG: $neg\r\n";

            $pattern[0] = '/-/';
            $pattern[1] = '/ /';
            $pattern[2] = '/N/';
            $pattern[3] = '/E/';
            $pattern[4] = '/W/';
            $pattern[5] = '/S/';
            $replacements = "";
            $front = preg_replace($pattern, $replacements, $front);
            #echo "2) $front....$back\r\n";
            // 4.146255 ---- 4 - 146255
#		echo $geocord_exp[1].'<br>';
            $geocord_dec = "0.".$back;
            // 4.146255 ---- 4 - 0.146255
            #echo "Dec: $geocord_dec\r\n";
            $geocord_mult = $geocord_dec*60;
            // 4.146255 ---- 4 - (0.146255)*60 = 8.7753
            #echo "Milt: $geocord_mult\r\n";
            $mult = explode(".",$geocord_mult);
            $len = strlen($mult[0]);
            #echo "Len: $len\r\n";
            if( $len < 2 )
            {
                $geocord_mult = "0".$geocord_mult;
            }
            // 4.146255 ---- 4 - 08.7753

            $geocord_out = $front.$geocord_mult;
            #echo "3.1) $geocord_out\r\n";

            // 4.146255 ---- 408.7753
            $geocord_o = explode(".", $geocord_out);
            if( strlen($geocord_o[1]) > 4 )
            {
                $geocord_o[1] = substr($geocord_o[1], 0 , 4);
                $geocord_out = implode('.', $geocord_o);
            }
            #echo "3.2) $geocord_out\r\n";

            if($neg === TRUE){$geocord_out = "-".$geocord_out;}
            #echo "3.3) $geocord_out\r\n";

            $end = microtime(true);
            #echo "----------------\r\n";
            return $geocord_out;
	}

	#========================================================================================================================#
	#													GPS check, make sure there are no duplicates						 #
	#========================================================================================================================#

	function &check_gps_array($gpsarray, $test, $table='')
	{
		$start = microtime(true);
		include('config.inc.php');
		$conn1 = $GLOBALS['conn'];
		$db_st = $GLOBALS['db_st'];

		$count = count($gpsarray);
		if($count !=0)
		{
			foreach($gpsarray as $gps)
			{
				$id = $gps['id'];
				$lat = smart($gps['lat']);
				$long = smart($gps['long']);
				$time = smart($gps['time']);
				$date = smart($gps['date']);
				$gps_t 	= $lat."".$long."".$date."".$time;
				$gps_t = $gps_t;
				$test = $test;
			#	echo $gps_t."  ===  ".$test."\r\n";
				if ($gps_t===$test)
				{
					if ($GLOBALS["debug"]  == 1 ) {
						echo  "  SAME<br>";
						echo  "  Array data: ".$gps_t."<br>";
						echo  "  Testing data: ".$test."<br>.-.-.-.-.=.-.-.-.-.<br>";
						echo  "-----=-----=-----<br>|<br>|<br>";
					}

					$lat_a = $gps['lat'];
					$long_a = $gps['long'];
					$time_a = $gps['time'];
					$date_a = $gps['date'];

					if($table != '')
					{
						$sql11 = "SELECT * FROM `$db_st`.`$table` WHERE `lat` like '$lat_a' AND `long` like '$long_a' AND `date` like '$date_a' AND `time` like '$time_a' LIMIT 1";
						$gpresult = mysql_query($sql11, $conn1);
						$gpsdbarray = mysql_fetch_array($gpresult);

						$id_ = $gpsdbarray['id'];
#						echo $sql11."\n".$id_."\n";

						$return = array(0=>1,1=>$id_);
						return $return;
						break;
					}else
					{
						$return = array(0=>1,1=>0);
						return $return;
						break;
					}
				}else
				{
					if ($GLOBALS["debug"]  == 1){
						echo  "  NOT SAME<br>";
						echo  "  Array data: ".$gps_t."<br>";
						echo  "  Testing data: ".$test."<br>----<br>";
						echo  "-----=-----<br>";
					}
					$return = array(0=>0,1=>0);
				}
			}
		}else
		{
			$return = array(0=>0,1=>0);
		}
		$end = microtime(true);
		if ($GLOBALS["bench"]  == 1)
		{
			#echo "Time is [Unix Epoc]<BR>";
			#echo "Start Time: ".$start."<BR>";
			#echo "  End Time: ".$end."<BR>";
		}
		return $return;
	}


	#========================================================================================================================#
	#													AP History Fetch													 #
	#========================================================================================================================#

	function apfetch($id=0)
	{
		$apID = $id;
		$start = microtime(true);
		include('../lib/config.inc.php');
		$sqls = "SELECT * FROM `$db`.`$wtable` WHERE id='$id'";
		$result = mysql_query($sqls, $conn) or die(mysql_error($conn));
		$newArray = mysql_fetch_array($result);
		$ID = $newArray['id'];
		$tablerowid = 0;
		$macaddress = $newArray['mac'];
		$manuf = database::manufactures($macaddress);
		$mac = str_split($macaddress,2);
		$mac_full = implode(":", $mac);
		$radio = $newArray['radio'];
		if($radio == "a")
			{$radio = "802.11a";}
		elseif($radio == "b")
			{$radio = "802.11b";}
		elseif($radio == "g")
			{$radio = "802.11g";}
		elseif($radio == "n")
			{$radio = "802.11n";}
		else
			{$radio = "802.11u";}
		list($ssid_ptb) = make_ssid($newArray["ssid"]);
		$table		=	$ssid_ptb.'-'.$newArray["mac"].'-'.$newArray["sectype"].'-'.$newArray["radio"].'-'.$newArray['chan'];
		$table_gps	=	$table.$gps_ext;
		echo $table_gps;
                $sql_gps = "select * from `$db_st`.`$table_gps` where `lat` NOT LIKE 'N 0.0000' limit 1";
		$resultgps = mysql_query($sql_gps, $conn);
		$lastgps = @mysql_fetch_array($resultgps);
		$lat_check = explode(" ", $lastgps['lat']);
		$lat_c = $lat_check[1]+0;
		if($lat_c != "0"){$gps_yes = 1;}else{$gps_yes = 0;}
		?>
                <SCRIPT LANGUAGE="JavaScript">
                // Row Hide function.
                // by tcadieux
                function expandcontract(tbodyid,ClickIcon)
                {
                        if (document.getElementById(ClickIcon).innerHTML == "+")
                        {
                                document.getElementById(tbodyid).style.display = "";
                                document.getElementById(ClickIcon).innerHTML = "-";
                        }else{
                                document.getElementById(tbodyid).style.display = "none";
                                document.getElementById(ClickIcon).innerHTML = "+";
                        }
                }
                </SCRIPT>
                <h1><?php echo htmlentities($newArray['ssid'], ENT_QUOTES);
		if($gps_yes)
		{
		    echo '<img width="20px" src="../img/globe_on.png">';
		}
		else
		{
		    echo '<img width="20px" src="../img/globe_off.png">';
		}
		?></h1>
		<TABLE align=center WIDTH=569 BORDER=1 CELLPADDING=4 CELLSPACING=0>
		<COL WIDTH=112><COL WIDTH=439>
                    <TR>
                        <TD class="style4" WIDTH=112><P>MAC Address</P></TD>
                        <TD class="light" WIDTH=439><P><?php echo $mac_full;?></P></TD>
                    </TR>
                    <TR VALIGN=TOP>
                        <TD class="style4" WIDTH=112><P>Manufacture</P></TD>
                        <TD class="light" WIDTH=439><P><?php echo $manuf;?></P></TD>
                    </TR>
                    <TR VALIGN=TOP>
                        <TD class="style4" WIDTH=112 HEIGHT=26><P>Authentication</P></TD>
                        <TD class="light" WIDTH=439><P><?php echo $newArray['auth'];?></P></TD>
                    </TR>
                    <TR VALIGN=TOP>
                        <TD class="style4" WIDTH=112><P>Encryption Type</P></TD>
                        <TD class="light" WIDTH=439><P><?php echo $newArray['encry'];?></P></TD>
                    </TR>
                    <TR VALIGN=TOP>
                        <TD class="style4" WIDTH=112><P>Radio Type</P></TD>
                        <TD class="light" WIDTH=439><P><?php echo $radio;?></P></TD>
                    </TR>
                    <TR VALIGN=TOP>
                        <TD class="style4" WIDTH=112><P>Channel #</P></TD>
                        <TD class="light" WIDTH=439><P><?php echo $newArray['chan'];?></P></TD>
                    </TR>
                    <tr class="style4">
                        <td colspan="2" align="center" >
                                <a class="links" href="../opt/export.php?func=exp_single_ap&row=<?php echo $ID;?>">Export this AP to KML</a>
                        </td>
                    </tr>
		</TABLE>
		</br>
		<TABLE align=center  WIDTH=85% BORDER=1 CELLPADDING=4 CELLSPACING=0 id="gps">
                    <tr class="style4"><th colspan="10">Signal History</th></tr>
                    <tr class="style4"><th>Row</th><th>Btx</th><th>Otx</th><th>First Active</th><th>Last Update</th><th>Network Type</th><th>Label</th><th>User</th><th>Signal</th><th>Plot</th></tr>
		<?php
		$start1 = microtime(true);
		$result = mysql_query("SELECT * FROM `$db_st`.`$table` ORDER BY `id`", $conn) or die(mysql_error($conn));
		$flip = 0;
		while ($field = mysql_fetch_array($result))
		{
			if($flip){$class="light";$flip=0;}else{$class="dark";$flip=1;}
			$row = $field["id"];
			$row_id = $row.','.$ID;
			$sig_exp = explode("-", $field["sig"]);
			$sig_size = count($sig_exp)-1;

			$first_ID = explode(",",$sig_exp[0]);
			$first = $first_ID[0];
			if($first == 0)
			{
				$first_ID = explode(",",$sig_exp[1]);
				$first = $first_ID[0];
			}

			$last_ID = explode(",",$sig_exp[$sig_size]);
			$last = $last_ID[0];
			if($last == 0)
			{
				$last_ID = explode(",",$sig_exp[$sig_size-1]);
				$last = $last_ID[0];
			}

			$sql1 = "SELECT * FROM `$db_st`.`$table_gps` WHERE `id`='$first'";
			$re = mysql_query($sql1, $conn) or die(mysql_error($conn));
			$gps_table_first = mysql_fetch_array($re);

			$date_first = $gps_table_first["date"];
			$time_first = $gps_table_first["time"];
			$fa = $date_first." ".$time_first;

			$sql2 = "SELECT * FROM `$db_st`.`$table_gps` WHERE `id`='$last'";
			$res = mysql_query($sql2, $conn) or die(mysql_error($conn));
			$gps_table_last = mysql_fetch_array($res);
			$date_last = $gps_table_last["date"];
			$time_last = $gps_table_last["time"];
			$lu = $date_last." ".$time_last;
			?>
				<tr class="<?php echo $class; ?>"><td align="center"><?php echo $row; ?></td><td>
				<?php echo $field["btx"]; ?></td><td>
				<?php echo $field["otx"]; ?></td><td>
				<?php echo $fa; ?></td><td>
				<?php echo $lu; ?></td><td>
				<?php echo $field["nt"]; ?></td><td>
				<?php echo $field["label"]; ?></td><td>
				<a class="links" href="../opt/userstats.php?func=alluserlists&user=<?php echo $field["user"]; ?>"><?php echo $field["user"]; ?></a></td><td>
				<a class="links" href="../graph/?row=<?php echo $row; ?>&id=<?php echo $ID; ?>">Graph Signal</a></td><td><a class="links" href="export.php?func=exp_all_signal&row=<?php echo $row_id;?>">KML</a>
				</td></tr>
				<tr><td colspan="10" align="center">

				<table  align=center WIDTH=569 BORDER=1 CELLPADDING=4 CELLSPACING=0>
				<tr>
                                    <td class="style4" onclick="expandcontract('Row<?php echo $tablerowid;?>','ClickIcon<?php echo $tablerowid;?>')" id="ClickIcon<?php echo $tablerowid;?>" style="cursor: pointer; cursor: hand;">+</td>
				<th colspan="6" class="style4">GPS History</th></tr>
				<tbody id="Row<?php echo $tablerowid;?>" style="display:none">
				<tr class="style4"><th>Row</th><th>Lat</th><th>Long</th><th>Sats</th><th>Date</th><th>Time</th></tr>
				<?php
				$signals = explode('-',$field['sig']);
				$flip_1 = 0;
				foreach($signals as $signal)
				{
					$sig_exp = explode(',',$signal);
					$id = $sig_exp[0]+0;
					if($id == 0){continue;}
					$start2 = microtime(true);
					$result1 = mysql_query("SELECT * FROM `$db_st`.`$table_gps` WHERE `id` = '$id'", $conn) or die(mysql_error($conn));
				#	$rows = mysql_num_rows($result1);
					if($flip_1){$class="light";$flip_1=0;}else{$class="dark";$flip_1=1;}
					while ($field = mysql_fetch_array($result1))
					{
						?>
						<tr class="<?php echo $class; ?>"><td align="center">
						<?php echo $field["id"]; ?></td><td>
						<?php echo $field["lat"]; ?></td><td>
						<?php echo $field["long"]; ?></td><td align="center">
						<?php echo $field["sats"]; ?></td><td>
						<?php echo $field["date"]; ?></td><td>
						<?php echo $field["time"]; ?></td></tr>
						<?php
					}
					$end2 = microtime(true);
				}
				?>
				<tr class="style4">
                                    <td onclick="expandcontract('Row<?php echo $tablerowid;?>','ClickIcon<?php echo $tablerowid;?>')" id="ClickIcon<?php echo $tablerowid;?>" style="cursor: pointer; cursor: hand;">-</td>
                                    <td colspan="5"></td>
                                </tr>
				</table>

				</td></tr>
				<?php
				$tablerowid++;
		}
		$end1 = microtime(true);
		?>
		</table>
		<br>
		<TABLE align=center WIDTH=569 BORDER=1 CELLPADDING=4 CELLSPACING=0>
		<?php
		#END GPSFETCH FUNC
		?>
		<tr class="style4"><th colspan="6">Associated Lists</th></tr>
		<tr class="style4"><th>New/Update</th><th>ID</th><th>User</th><th>Title</th><th>Total APs</th><th>Date</th></tr>
		<?php
		$start3 = microtime(true);
		$result = mysql_query("SELECT * FROM `$db`.`$users_t`", $conn);
		while ($field = mysql_fetch_array($result))
		{
			if($field['points'] != '')
			{
				$APS = explode("-" , $field['points']);
				foreach ($APS as $AP)
				{
					if($AP == ''){continue;}
					$access = explode(",", $AP);
					$New_or_Update = $access[0];
					$access1 = explode(":",$access[1]);
					$user_list_id = $access1[0];
					if ( $apID  ==  $user_list_id )
					{
						$list[]=$field['id'].",".$New_or_Update;
					}
				}
			}
		}
		if(isset($list))
		{
			$flip_2 = 0;
			foreach($list as $aplist)
			{
				$exp = explode(",",$aplist);
				$apid = $exp[0];
				$new_update = $exp[1];
				$result = mysql_query("SELECT * FROM `$db`.`$users_t` WHERE `id`='$apid'", $conn);
				while ($field = mysql_fetch_array($result))
				{
					if($flip_2){$class="light";$flip_2=0;}else{$class="dark";$flip_2=1;}
					if($field["title"]==''){$field["title"]="Untitled";}
					$points = explode('-' , $field['points']);
					$total = count($points);
					?>
					<tr class="<?php echo $class;?>">
						<td><?php if($new_update == 1){echo "Update";}else{echo "New";}?></td>
						<td align="center"><a class="links" href="userstats.php?func=useraplist&row=<?php echo $field["id"];?>"><?php echo $field["id"];?></a></td>
						<td><a class="links" href="userstats.php?func=alluserlists&user=<?php echo $field["username"];?>"><?php echo $field["username"];?></a></td>
						<td><a class="links" href="userstats.php?func=useraplist&row=<?php echo $field["id"];?>"><?php echo $field["title"];?></a></td>
						<td align="center"><?php echo $total;?></td><td><?php echo $field['date'];?></td>
					</tr>
					<?php
				}
			}
		}else
		{
			?>
			<td colspan="5" align="center">There are no Other Lists with this AP in it.</td></tr>
			<?php

		}
		$end3 = microtime(true);
		mysql_close($conn);
		?>
		</table><br>
		<?php
		$end = microtime(true);
		if ($GLOBALS["bench"]  == 1)
		{
			echo "Time is [Unix Epoc]<BR>";
			echo "Total Start Time: ".$start."<BR>";
			echo "Total  End Time: ".$end."<BR>";
			echo "Start Time 1: ".$start1."<BR>";
			echo "  End Time 1: ".$end1."<BR>";
			echo "Start Time 2: ".$start2."<BR>";
			echo "  End Time 2: ".$end2."<BR>";
			echo "Start Time 3: ".$start3."<BR>";
			echo "  End Time 3: ".$end3."<BR>";
		}
		#END IMPORT LISTS FETCH FUNC
	}


	#========================================================================================================================#
	#													Grab the stats for All Users										 #
	#========================================================================================================================#
	function all_users()
	{
		$start = microtime(true);
		include('config.inc.php');
		$users = array();
		$userarray = array();
		?>
			<SCRIPT LANGUAGE="JavaScript">
			// Row Hide function.
			// by tcadieux
			function expandcontract(tbodyid,ClickIcon)
			{
				if (document.getElementById(ClickIcon).innerHTML == "+")
				{
					document.getElementById(tbodyid).style.display = "";
					document.getElementById(ClickIcon).innerHTML = "-";
				}else{
					document.getElementById(tbodyid).style.display = "none";
					document.getElementById(ClickIcon).innerHTML = "+";
				}
			}
			</SCRIPT>
			<h1>Stats For: All Users</h1>
			<table border="1" align="center">
			<tr class="style4">
			<th>ID</th><th>UserName</th><th>Title</th><th>Import Notes</th><th>Number of APs</th><th>Imported On</th></tr><tr>
		<?php

		$sql = "SELECT * FROM `$db`.`$users_t` ORDER BY username ASC";
		$result = mysql_query($sql, $conn) or die(mysql_error($conn));
		$num = mysql_num_rows($result);
		if($num == 0)
		{
			echo '<tr><td colspan="6" align="center">There no Users, Import something.</td></tr></table>';
			$filename = $_SERVER['SCRIPT_FILENAME'];
			footer($filename);
			die();
		}
		while ($user_array = mysql_fetch_array($result))
		{
			$users[]=$user_array["username"];
		}
		$users = array_unique($users);
		$pre_user = "";
		$n=0;
		$row_color = 0;
		foreach($users as $user)
		{
		    if($row_color == 1)
		    {$row_color = 0; $color = "light";}
		    else{$row_color = 1; $color = "dark";}
		    $tablerowid = 0;
		    $sql = "SELECT * FROM `$db`.`$users_t` WHERE `username`='$user'";
		    $result = mysql_query($sql, $conn) or die(mysql_error($conn));
		    while ($user_array = mysql_fetch_array($result))
		    {
			$tablerowid++;
			$id = $user_array['id'];
			$username = $user_array['username'];
			if($pre_user === $username or $pre_user === ""){$n++;}else{$n=0;}
			if ($user_array['title'] === "" or $user_array['title'] === " "){ $user_array['title']="UNTITLED";}
			if ($user_array['date'] === ""){ $user_array['date']="No date, hmm..";}
			$search = array('\n','\r','\n\r');
			$user_array['notes'] = str_replace($search,"", $user_array['notes']);
			if ($user_array['notes'] == ""){ $user_array['notes']="No Notes, hmm..";}
			$notes = $user_array['notes'];
			$points = explode("-",$user_array['points']);
			$pc = count($points);
			if($user_array['points'] === ""){continue;}
			if($pre_user !== $username)
			{
			    ?>
			    <tr >
				<td class="<?php echo $color;?>"><?php echo $user_array['id'];?></td>
				<td class="<?php echo $color;?>"><a class="links" href="userstats.php?func=alluserlists&user=<?php echo $username;?>"><?php echo $username;?></a></td>
				<td class="<?php echo $color;?>"><a class="links" href="userstats.php?func=useraplist&row=<?php echo $user_array["id"];?>"><?php echo $user_array['title'];?></a></td>
				<td class="<?php echo $color;?>"><?php echo wordwrap($notes, 56, "<br />\n"); ?></td>
				<td class="<?php echo $color;?>"><?php echo $pc;?></td>
				<td class="<?php echo $color;?>"><?php echo $user_array['date'];?></td>
			    </tr>
			    <?php
			}else
			{
			    ?>
			    <tr>
				<td></td>
				<td></td>
				<td class="<?php echo $color;?>"><a class="links" href="userstats.php?func=useraplist&row=<?php echo $user_array["id"];?>"><?php echo $user_array['title'];?></a></td>
				<td class="<?php echo $color;?>"><?php echo wordwrap($notes, 56, "<br />\n"); ?></td>
				<td class="<?php echo $color;?>"><?php echo $pc;?></td>
				<td class="<?php echo $color;?>"><?php echo $user_array['date'];?></td>
			    </tr>
			    <?php
			}
			$pre_user = $username;
		    }
		    ?>
		    <tr></tr>
		    <?php
		}

		?>
		</tr></td></table><br>
		<?php
		$end = microtime(true);
				if ($GLOBALS["bench"]  == 1)
				{
					echo "Time is [Unix Epoc]<BR>";
					echo "Start Time: ".$start."<BR>";
					echo "  End Time: ".$end."<BR>";
				}
	}


	#========================================================================================================================#
	#													Grab All the AP's for a given user									 #
	#========================================================================================================================#

	function all_users_ap($user="")
	{
		$start = microtime(true);
		include('config.inc.php');
		$sql = "SELECT * FROM `$db`.`$users_t` WHERE `username`='$user'";
		$re = mysql_query($sql, $conn) or die(mysql_error($conn));
		while($user_array = mysql_fetch_array($re))
		{
			if($user_array["points"] != '')
			{
				$explode = explode("-",$user_array["points"]);
				foreach($explode as $explo)
				{
					$exp = explode(",",$explo);
					$flag = $exp[0];
					$ap_exp = explode(":",$exp[1]);
					$aps[] = array(
									"flag"=>$flag,
									"apid"=>$ap_exp[0],
									"row"=>$ap_exp[1]
									);
				}
			}
		}

		$sql = "SELECT * FROM `$db`.`$users_t` WHERE `username` LIKE '$user'";
		$other_imports = mysql_query($sql, $conn) or die(mysql_error($conn));
		while($imports = mysql_fetch_array($other_imports))
		{
			if($imports['points'] == ""){continue;}
			$points = explode("-",$imports['points']);
			foreach($points as $key=>$pt)
			{
				$pt_ex = explode(",", $pt);
				if($pt_ex[0] == 1)
				{
					unset($points[$key]);
				}
			}
			$pts_count = count($points);
			$total_aps[] = $pts_count;
		}
		$total = 0;
		foreach($total_aps as $totals)
		{
			$total += $totals;
		}
		?>
		<table>
		<tr><td>
			<table border="1" align="center" width="100%">
				<tr class="style4">
					<th colspan='2'>Access Points For: <a class="links" href ="../opt/userstats.php?func=alluserlists&user=<?php echo $user;?>"><?php echo $user;?></a>
				</tr>
				<tr class="sub_head">
					<td><b>Total Access Points...</b></td><td><?php echo $total;?></td>
				</tr>
				<tr class="sub_head">
					<td><b>Export This list To...</b></td><td><a class="links" href="../opt/export.php?func=exp_user_all_kml&user=<?php echo $user;?>">KML</a></td>
				</tr>
			</table>
			<br>
			<table border="1" align="center">
				<tr class="style4">
					<th>AP ID</th><th>Row</th><th>SSID</th><th>Mac Address</th><th>Authentication</th><th>Encryption</th><th>Radio</th><th>Channel</th>
				</tr>
			<?php
			$flip = 0;
			foreach($aps as $ap)
			{
				if($ap['flag'] == "1"){continue;}
				if($flip){$style = "dark";$flip=0;}else{$style="light";$flip=1;}
				$apid = $ap['apid'];
				$row = $ap['row'];

				$sql = "SELECT * FROM `$db`.`$wtable` WHERE `ID`='$apid'";
				$res = mysql_query($sql, $conn) or die(mysql_error($conn));
				$ap_array = mysql_fetch_array($res);

				$ssid = $ap_array['ssid'];
				$mac = $ap_array['mac'];
				$chan = $ap_array['chan'];
				$radio = $ap_array['radio'];
				$auth = $ap_array['auth'];
				$encry = $ap_array['encry'];
				if($radio=="a")
				{$radio="802.11a";}
				elseif($radio=="b")
				{$radio="802.11b";}
				elseif($radio=="g")
				{$radio="802.11g";}
				elseif($radio=="n")
				{$radio="802.11n";}
				else
				{$radio="Unknown Radio";}
				?>
				<tr class="<?php echo $style;?>">
					<td align="center">
				<?php
				echo $apid;
				?>
					</td>
					<td align="center">
				<?php
				echo $row;
				?>
					</td>
					<td align="center">
						<a class="links" href="fetch.php?id=<?php echo $apid;?>"><?php echo $ssid;?></a>
					</td>
					<td>
						<?php echo $mac;?>
					</td>
					<td align="center">
						<?php echo $auth;?>
					</td>
					<td align="center">
						<?php echo $encry;?>
					</td>
					<td align="center">
						<?php echo $radio;?>
					</td>
					<td align="center">
						<?php echo $chan;?>
					</td>
				</tr>
				<?php
			}
			?>
			</table>
		</td></tr></table>
		<br>
		<?php
		$end = microtime(true);
		if ($GLOBALS["bench"]  == 1)
		{
			echo "Time is [Unix Epoc]<BR>";
			echo "Start Time: ".$start."<BR>";
			echo "  End Time: ".$end."<BR>";
		}
	}

	#========================================================================================================================#
	#													Grab all user Import lists											 #
	#========================================================================================================================#

	function users_lists($username="")
	{
		$start = microtime(true);
		include('config.inc.php');
		$sql = "SELECT * FROM `$db`.`$users_t` WHERE `username` LIKE '$username' ORDER BY `id` DESC LIMIT 1";
		$user_query = mysql_query($sql, $conn) or die(mysql_error($conn));
		$user_last = mysql_fetch_array($user_query);
		$last_import_id = $user_last['id'];
		$user_aps = $user_last['aps'];
		$user_gps = $user_last['gps'];
		$last_import_title = $user_last['title'];
		$last_import_date = $user_last['date'];

		$sql = "SELECT * FROM `$db`.`$users_t` WHERE `username` LIKE '$username' ORDER BY `id` ASC LIMIT 1";
		$user_query = mysql_query($sql, $conn) or die(mysql_error($conn));
		$user_first = mysql_fetch_array($user_query);
		$user_ID = $user_first['id'];
		$first_import_date = $user_first['date'];

		$sql = "SELECT * FROM `$db`.`$users_t` WHERE `username` LIKE '$username'";
		$other_imports = mysql_query($sql, $conn) or die(mysql_error($conn));
		while($imports = mysql_fetch_array($other_imports))
		{
			if($imports['points'] == ""){continue;}
			$points = explode("-",$imports['points']);
			foreach($points as $key=>$pt)
			{
				$pt_ex = explode(",", $pt);
				if($pt_ex[0] == 1)
				{
					unset($points[$key]);
				}
			}
			$pts_count = count($points);
			$total_aps[] = $pts_count;
		}
		$total = 0;
		if(count(@$total_aps))
		{
			foreach($total_aps as $totals)
			{
				$total += $totals;
			}
			?>
			<table width="90%" border="1" align="center">
			<tr class="style4">
				<th colspan="4">Stats for : <?php echo $username;?></th>
			</tr>
			<tr class="sub_head">
				<th>ID</th><th>Total APs</th><th>First Import</th><th>Last Import</th>
			</tr>
			<tr class="dark">
				<td><?php echo $user_ID;?></td><td><a class="links" href="../opt/userstats.php?func=allap&user=<?php echo $username?>"><?php echo $total;?></a></td><td><?php echo $first_import_date;?></td><td><?php echo $last_import_date;?></td>
			</tr>
			</table>
			<br>

			<table width="90%" border="1" align="center">
			<tr class="style4">
				<th colspan="4">Last Import Details</th>
			</tr>
			<tr class="sub_head">
				<th>ID</th><th colspan="3">Title</th>
			</tr>
			<tr class="dark">
				<td align="center"><?php echo $last_import_id;?></td><td colspan="4" align="center"><a class="links" href="../opt/userstats.php?func=useraplist&row=<?php echo $last_import_id;?>"><?php echo $last_import_title;?></a></td>
			</tr>
			<tr class="sub_head">
				<th colspan="2">Date</th><th>Total APs</th><th>Total GPS</th>
			</tr>
			<tr class="dark">
				<td colspan="2" align="center"><?php echo $last_import_date;?></td><td align="center"><?php echo $user_aps; ?></td><td align="center"><?php echo $user_gps;?></td>
			</tr>
			</table>
			<br>

			<table width="90%" border="1" align="center">
			<tr class="style4">
				<th colspan="4">All Previous Imports</th>
			</tr>
			<tr class="sub_head">
				<th>ID</th><th>Title</th><th>Total APs</th><th>Date</th>
			</tr>

			<?php
			$sql = "SELECT * FROM `$db`.`$users_t` WHERE `username` LIKE '$username' AND `id` != '$last_import_id' ORDER BY `id` DESC";
			$other_imports = mysql_query($sql, $conn) or die(mysql_error($conn));
			$other_rows = mysql_num_rows($other_imports);
			if(@$others_rows != "0")
			{
				$flip = 0;
				while($imports = mysql_fetch_array($other_imports))
				{
					if($imports['points'] == ""){continue;}
					if($flip){$style = "dark";$flip=0;}else{$style="light";$flip=1;}
					$import_id = $imports['id'];
					$import_title = $imports['title'];
					$import_date = $imports['date'];
					$import_ap = $imports['aps'];
					?>
					<tr class="<?php echo $style; ?>"><td><?php echo $import_id;?></td><td><a class="links" href="../opt/userstats.php?func=useraplist&row=<?php echo $import_id;?>"><?php echo $import_title;?></a></td><td><?php echo $import_ap;?></td><td><?php echo $import_date;?></td></tr>
					<?php
				}
			}else
			{
				?>
					<tr class="light"><td colspan="4" align="center">There are no other Imports. Go get some.</td></tr>
				<?php
			}
			?>
			</table>
			<?php
		}else
		{
			?>
			<table width="90%" border="1" align="center">
				<tr class="light"><td colspan="4" align="center">There are no Imports for this user. Make them go get some.</td></tr>
			</table>
			<?php
		}
	}

	#========================================================================================================================#
	#													Grab the AP's for a given user's Import								 #
	#========================================================================================================================#

	function user_ap_list($row=0)
	{
		$start = microtime(true);
		include('config.inc.php');
		$pagerow = 0;
		$sql = "SELECT * FROM `$db`.`$users_t` WHERE `id`='$row'";
		$result = mysql_query($sql, $conn) or die(mysql_error($conn));
		$user_array = mysql_fetch_array($result);
		$aps=explode("-",$user_array["points"]);
		$title = $user_array["title"];
		?>
		<table><tr><td align="center">
			<table width="100%" border="1" align="center">
				<tr class="style4">
					<th colspan="2">
						Access Points For: <a class="links" href ="../opt/userstats.php?func=alluserlists&user=<?php echo $user_array["username"]; ?>"><?php echo $user_array["username"]; ?></a>
					</th>
				</tr>
				<tr class="sub_head">
					<td><i><b>Title</b></i></td><td><b><?php echo $title; ?></b></td>
				</tr>
				<tr class="sub_head">
					<td><i><b>Imported On</b></i></td><td><b><?php echo $user_array["date"]; ?></b></td>
				</tr>
				<tr class="sub_head">
					<td><i><b>Total APs in List</b></i></td><td><b><?php echo $user_array["aps"]; ?></b></td>
				</tr>
				<tr class="sub_head">
					<td colspan="2"><CENTER><i><b>Notes</b></i></CENTER></td>
				</tr>
				<tr class="light">
					<td colspan="2"><CENTER><b><?php echo $user_array["notes"]; ?></b></CENTER></td>
				</tr>
				<tr class="dark">
					<td colspan="2"><CENTER><b><a href="export.php?func=exp_user_list&row=<?php echo $user_array["id"]; ?>">Export List</a></b></CENTER></td>
				</tr>
			<table>
			<br>
			<table border="1" align="center">
				<tr class="style4">
					<th>New/Update</th><th>AP ID</th><th>Row</th><th>SSID</th><th>Mac Address</th><th>Authentication</th><th>Encryption</th><th>Radio</th><th>Channel</th>
				</tr>
		<?php
		$flip = 0;
		foreach($aps as $ap)
		{
			#$pagerow++;
			$ap_exp = explode("," , $ap);
			if($ap_exp[0]==0){$flag = "N";}else{$flag = "U";}
			if($flip){$style = "dark";$flip=0;}else{$style="light";$flip=1;}
			$ap_and_row = explode(":",$ap_exp[1]);
			$apid = $ap_and_row[0];
			$row = $ap_and_row[1];
			$sql = "SELECT * FROM `$db`.`$wtable` WHERE `ID`='$apid'";
			$result = mysql_query($sql, $conn) or die(mysql_error($conn));
			while ($ap_array = mysql_fetch_array($result))
			{
				$ssid = $ap_array['ssid'];
			    $mac = $ap_array['mac'];
			    $chan = $ap_array['chan'];
				$radio = $ap_array['radio'];
				$auth = $ap_array['auth'];
				$encry = $ap_array['encry'];
				?>
			    <tr class="<?php echo $style;?>">
				<td align="center"><?php echo $flag;?></td>
				<td align="center"><?php echo $apid;?></td>
				<td align="center"><?php echo $row;?></td>
				<td align="center"><a class="links" href="fetch.php?id=<?php echo $apid; ?>"><?php echo $ssid; ?></a></td>
			    <td align="center"><?php echo $mac;?></td>
			    <td align="center"><?php echo $auth;?></td>
				<?php
				if($radio=="a")
				{$radio="802.11a";}
				elseif($radio=="b")
				{$radio="802.11b";}
				elseif($radio=="g")
				{$radio="802.11g";}
				elseif($radio=="n")
				{$radio="802.11n";}
				else
				{$radio="Unknown Radio";}
				?>
				<td align="center"><?php echo $encry;?></td>
				<td align="center"><?php echo $radio;?></td>
				<td align="center"><?php echo $chan;?></td></tr>
			<?php
			}
		}
		?>
		</table>
		</td></tr></table>
		<?php
		$end = microtime(true);
		if ($GLOBALS["bench"]  == 1)
		{
			echo "Time is [Unix Epoc]<BR>";
			echo "Start Time: ".$start."<BR>";
			echo "  End Time: ".$end."<BR>";
		}
	}


	#========================================================================================================================#
	#													Export to Google KML File											 #
	#========================================================================================================================#

	function exp_kml($export = "", $user = "", $row = 0, $named=0)
	{
		include('config.inc.php');
		include('manufactures.inc.php');
		switch ($export)
		{
			#--------------------#
			#-			-#
			#--------------------#
			case "exp_all_db_kml":
				$start = microtime(true);
				$good = 0;
				$bad  = 0;
				$total = 0;
				$no_gps = 0;
				echo '<table style="border-style: solid; border-width: 1px"><tr class="style4"><th colspan="2" style="border-style: solid; border-width: 1px">Start of WiFi DB export to KML</th></tr>';
				$sql = "SELECT * FROM `$db`.`$wtable`";
				$result = mysql_query($sql, $conn) or die(mysql_error($conn));
				$total = mysql_num_rows($result);
				$temp_kml = '../tmp/full_db_export.kml';
				$filewrite = fopen($temp_kml, "w");
				$fileappend = fopen($temp_kml, "a");

				$date=date('Y-m-d_H-i-s');
				$filename = '../tmp/'.$date.'_fulldb.kmz';
				$moved ='../out/kmz/full/'.$date.'_fulldb.kmz';
				echo '<tr><td style="border-style: solid; border-width: 1px" colspan="2">Wrote Header to KML Buffer</td><td></td></tr>';
				$x=0;
				$n=0;
				$NN=0;
				while($ap_array = mysql_fetch_array($result))
				{
					$man 		= database::manufactures($ap_array['mac']);
					$id             = $ap_array['id'];
					$ssid_ptb_      = $ap_array['ssid'];
					list($ssid_table, $ssid_safe, $ssids, $ssid_file) = make_ssid($ssid_ptb);
					$mac		= $ap_array['mac'];
					$sectype	= $ap_array['sectype'];
					$radio		= $ap_array['radio'];
					$chan		= $ap_array['chan'];
					$table = $ssid_table.'-'.$mac.'-'.$sectype.'-'.$radio.'-'.$chan;
					$table_gps = $table.$gps_ext;
					$sql1 = "SELECT * FROM `$db_st`.`$table`";
					$result1 = mysql_query($sql1, $conn);
					if(!$result1){$bad++;continue;}
					$rows = mysql_num_rows($result1);
					$sql = "SELECT * FROM `$db_st`.`$table` WHERE `id`='1'";
					$newArray = mysql_fetch_array($result1);
					switch($sectype)
					{
						case 1:
							$type = "#openStyleDead";
							$auth = "Open";
							$encry = "None";
							break;
						case 2:
							$type = "#wepStyleDead";
							$auth = "Open";
							$encry = "WEP";
							break;
						case 3:
							$type = "#secureStyleDead";
							$auth = "WPA-Personal";
							$encry = "TKIP-PSK";
							break;
					}
					switch($radio)
					{
						case "a":
							$radio="802.11a";
							break;
						case "b":
							$radio="802.11b";
							break;
						case "g":
							$radio="802.11g";
							break;
						case "n":
							$radio="802.11n";
							break;
						default:
							$radio="Unknown Radio";
							break;
					}

					$otx = $newArray["otx"];
					$btx = $newArray["btx"];
					$nt = $newArray['nt'];
					$label = $newArray['label'];

					$sql6 = "SELECT * FROM `$db_st`.`$table_gps`";
					$result6 = mysql_query($sql6, $conn);
					$max = mysql_num_rows($result6);

					$sql_1 = "SELECT * FROM `$db_st`.`$table_gps`";
					$result_1 = mysql_query($sql_1, $conn);
					$zero = 0;
					while($gps_table_first = mysql_fetch_array($result_1))
					{
						$lat_exp = explode(" ", $gps_table_first['lat']);

						$test = $lat_exp[1]+0;

						if($test == "0"){$zero = 1; $bad++; continue;}

						$date_first = $gps_table_first["date"];
						$time_first = $gps_table_first["time"];
						$fa   = $date_first." ".$time_first;
						$alt  = $gps_table_first['alt'];
						$lat  =& database::convert_dm_dd($gps_table_first['lat']);
						$long =& database::convert_dm_dd($gps_table_first['long']);
						$zero = 0;
						$NN++;
						break;
					}
					$total++;
					if($zero == 1)
					{
						$zero == 0;
						continue;
						$no_gps++;
					}
					//=====================================================================================================//

					$sql_2 = "SELECT * FROM `$db_st`.`$table_gps` WHERE `id`='$max'";
					$result_2 = mysql_query($sql_2, $conn);
					$gps_table_last = mysql_fetch_array($result_2);
					$date_last = $gps_table_last["date"];
					$time_last = $gps_table_last["time"];
					$la = $date_last." ".$time_last;
					$ssid_name = '';
					if ($named == 1){$ssid_name = $ssids;}

					switch($type)
					{
						case "#openStyleDead":
							$Odata .= "<Placemark id=\"".$mac."\">\r\n	<name>".$ssid_name."</name>\r\n	<description><![CDATA[<b>SSID: </b>".$ssid."<br /><b>Mac Address: </b>".$mac."<br /><b>Network Type: </b>".$nt."<br /><b>Radio Type: </b>".$radio."<br /><b>Channel: </b>".$ap['chan']."<br /><b>Authentication: </b>".$auth."<br /><b>Encryption: </b>".$encry."<br /><b>Basic Transfer Rates: </b>".$btx."<br /><b>Other Transfer Rates: </b>".$otx."<br /><b>First Active: </b>".$fa."<br /><b>Last Updated: </b>".$la."<br /><b>Latitude: </b>".$lat."<br /><b>Longitude: </b>".$long."<br /><b>Manufacturer: </b>".$manuf."<br /><a href=\"".$hosturl."/".$root."/opt/fetch.php?id=".$id."\">WiFiDB Link</a>]]></description>\r\n	<styleUrl>".$type."</styleUrl>\r\n<Point id=\"".$mac."_GPS\">\r\n<coordinates>".$long.",".$lat.",".$alt."</coordinates>\r\n</Point>\r\n</Placemark>\r\n";
						break;

						case "#wepStyleDead":
							$Wdata .= "<Placemark id=\"".$mac."\">\r\n	<name>".$ssid_name."</name>\r\n	<description><![CDATA[<b>SSID: </b>".$ssid."<br /><b>Mac Address: </b>".$mac."<br /><b>Network Type: </b>".$nt."<br /><b>Radio Type: </b>".$radio."<br /><b>Channel: </b>".$ap['chan']."<br /><b>Authentication: </b>".$auth."<br /><b>Encryption: </b>".$encry."<br /><b>Basic Transfer Rates: </b>".$btx."<br /><b>Other Transfer Rates: </b>".$otx."<br /><b>First Active: </b>".$fa."<br /><b>Last Updated: </b>".$la."<br /><b>Latitude: </b>".$lat."<br /><b>Longitude: </b>".$long."<br /><b>Manufacturer: </b>".$manuf."<br /><a href=\"".$hosturl."/".$root."/opt/fetch.php?id=".$id."\">WiFiDB Link</a>]]></description>\r\n	<styleUrl>".$type."</styleUrl>\r\n<Point id=\"".$mac."_GPS\">\r\n<coordinates>".$long.",".$lat.",".$alt."</coordinates>\r\n</Point>\r\n</Placemark>\r\n";
						break;

						case "#secureStyleDead":
							$Sdata .= "<Placemark id=\"".$mac."\">\r\n	<name>".$ssid_name."</name>\r\n	<description><![CDATA[<b>SSID: </b>".$ssid."<br /><b>Mac Address: </b>".$mac."<br /><b>Network Type: </b>".$nt."<br /><b>Radio Type: </b>".$radio."<br /><b>Channel: </b>".$ap['chan']."<br /><b>Authentication: </b>".$auth."<br /><b>Encryption: </b>".$encry."<br /><b>Basic Transfer Rates: </b>".$btx."<br /><b>Other Transfer Rates: </b>".$otx."<br /><b>First Active: </b>".$fa."<br /><b>Last Updated: </b>".$la."<br /><b>Latitude: </b>".$lat."<br /><b>Longitude: </b>".$long."<br /><b>Manufacturer: </b>".$manuf."<br /><a href=\"".$hosturl."/".$root."/opt/fetch.php?id=".$id."\">WiFiDB Link</a>]]></description>\r\n	<styleUrl>".$type."</styleUrl>\r\n<Point id=\"".$mac."_GPS\">\r\n<coordinates>".$long.",".$lat.",".$alt."</coordinates>\r\n</Point>\r\n</Placemark>\r\n";
						break;
					}
					echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">'.$id.' - '.$ssid.'</td></tr>';
					$good++;
					unset($lat);
					unset($long);
					unset($gps_table_first["lat"]);
					unset($gps_table_first["long"]);
				}
				echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">Finished Generating Data to Buffer, starting write of file.</td></tr>';
				$fdata  =  "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n	<kml xmlns=\"$KML_SOURCE_URL\"><!--exp_all_db_kml-->\r\n		<Document>\r\n			<name>RanInt WifiDB KML</name>\r\n";
				$fdata .= "			<Style id=\"openStyleDead\">\r\n		<IconStyle>\r\n				<scale>0.5</scale>\r\n				<Icon>\r\n			<href>".$open_loc."</href>\r\n			</Icon>\r\n			</IconStyle>\r\n			</Style>\r\n";
				$fdata .= "<Style id=\"wepStyleDead\">\r\n<IconStyle>\r\n<scale>0.5</scale>\r\n<Icon>\r\n<href>".$WEP_loc."</href>\r\n</Icon>\r\n</IconStyle>\r\n</Style>\r\n";
				$fdata .= "<Style id=\"secureStyleDead\">\r\n<IconStyle>\r\n<scale>0.5</scale>\r\n<Icon>\r\n<href>".$WPA_loc."</href>\r\n</Icon>\r\n</IconStyle>\r\n</Style>\r\n";
				$fdata .= '<Style id="Location"><LineStyle><color>7f0000ff</color><width>4</width></LineStyle></Style>';
				$fdata .= "<Folder>\r\n<name>Access Points</name>\r\n<description>APs: ".$NN."</description>\r\n";
				$fdata .= "<Folder>\r\n<name>WiFiDB Access Points</name>\r\n";
				$fdata .= "<Folder>\r\n<name>Open Access Points</name>\r\n".$Odata."</Folder>\r\n";
				$fdata .= "<Folder>\r\n<name>WEP Access Points</name>\r\n".$Wdata."</Folder>\r\n";
				$fdata .= "<Folder>\r\n<name>Secure Access Points</name>\r\n".$Sdata."</Folder>\r\n";
				$fdata = $fdata."	</Folder>\r\n	</Folder>\r\n	</Document>\r\n</kml>";
				#	write temp KML file to TMP folder
				fwrite($fileappend, $fdata);
				if($no_gps < $total)
				{
					echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">Zipping up the files into a KMZ file.</td></tr>';
					$zip = new ZipArchive;
					if ($zip->open($filename, ZipArchive::CREATE) === TRUE) {
					   $zip->addFile($temp_kml, 'doc.kml');
					 #  $zip->addFromString('doc.kml', $fdata);
					    $zip->close();
				#	    echo 'Zipped up<br>';
						unlink($temp_kml);
						copy($filename, $moved);
						unlink($filename);
						echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">Move KMZ file from its tmp home to its permanent residence</td></tr>';
						echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">Your Google Earth KML file is ready,<BR>you can download it from <a class="links" href="'.$moved.'">Here</a></td></tr></table>';

					} else {
					    echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">Could not create KMZ Archive.</td></tr>';
					}
				}else
				{
					echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">No Aps with GPS found.</td></tr>';
				}
				$end = microtime(true);
				break;
			#--------------------#

			case "exp_user_list":
				$start = microtime(true);
				$total = 0;
				$no_gps = 0;
				echo '<table style="border-style: solid; border-width: 1px"><tr class="style4"><th style="border-style: solid; border-width: 1px" colspan="2">Start of export Users List to KML</th></tr>';
				if($row == 0)
				{
					$sql_row = "SELECT * FROM `$db`.`$users_t` ORDER BY `id` DESC LIMIT 1";
					$result_row = mysql_query($sql_row, $conn) or die(mysql_error($conn));
					$row_array = mysql_fetch_array($result_row);
					$row = $row_array['id'];
				}
				$sql = "SELECT * FROM `$db`.`$users_t` WHERE `id`='$row'";
				$result = mysql_query($sql, $conn) or die(mysql_error($conn));
				$user_array = mysql_fetch_array($result);

				$aps = explode("-" , $user_array["points"]);

				$date=date('Y-m-d_H-i-s');

				if ($user_array["title"]==""){$title = "UNTITLED";}else{$title=$user_array["title"];}
				if ($user_array["username"]==""){$user = "Uknnown";}else{$user=$user_array["username"];}
				echo '<tr class="style4"><td colspan="2" align="center" style="border-style: solid; border-width: 1px">Username: '.$user.'</td></tr>';
				$temp_kml = '../tmp/'.$user.$title.rand().'tmp.kml';
				$filewrite = fopen($temp_kml, "w");
				$fileappend = fopen($temp_kml, "a");

				$date=date('Y-m-d_H-i-s');

				$filename = $date.'_'.$user.'_'.$title.'.kmz';
				// open file and write header:
				fwrite($fileappend, "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n	<kml xmlns=\"$KML_SOURCE_URL\">\r\n<!--exp_user_list-->		<Document>\r\n			<name>User: ".$user." - Title: ".$title."</name>\r\n");
				fwrite($fileappend, "			<Style id=\"openStyleDead\">\r\n		<IconStyle>\r\n				<scale>0.5</scale>\r\n				<Icon>\r\n			<href>".$open_loc."</href>\r\n			</Icon>\r\n			</IconStyle>\r\n			</Style>\r\n");
				fwrite($fileappend, "<Style id=\"wepStyleDead\">\r\n<IconStyle>\r\n<scale>0.5</scale>\r\n<Icon>\r\n<href>".$WEP_loc."</href>\r\n</Icon>\r\n</IconStyle>\r\n</Style>\r\n");
				fwrite($fileappend, "<Style id=\"secureStyleDead\">\r\n<IconStyle>\r\n<scale>0.5</scale>\r\n<Icon>\r\n<href>".$WPA_loc."</href>\r\n</Icon>\r\n</IconStyle>\r\n</Style>\r\n");
				fwrite($fileappend, '<Style id="Location"><LineStyle><color>7f0000ff</color><width>4</width></LineStyle></Style>');
				echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">Wrote Header to KML File</td><td></td></tr>';
				$x=0;
				$n=0;
				$total = count($aps);
				fwrite( $fileappend, "<Folder>\r\n<name>Access Points</name>\r\n<description>APs: ".$total."</description>\r\n");
				fwrite( $fileappend, "<Folder>\r\n<name>".$title." Access Points</name>\r\n");
				echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">Wrote KML Folder Header</td></tr>';
				$NN=0;
				foreach($aps as $ap)
				{
					$ap_exp = explode("," , $ap);
					$apid_exp = explode(":",$ap_exp[1]);
					if($ap_exp[0] == 1){continue;}
					$apid = $apid_exp[0];
					$update_row = $apid_exp[1];
					$udflag = $ap_exp[0];

					$sql0 = "SELECT * FROM `$db`.`$wtable` WHERE `id`='$apid'";
					$result0 = mysql_query($sql0, $conn) or die(mysql_error($conn));
					$newArray = mysql_fetch_array($result0);

					$id = $newArray['id'];
					$ssids_ptb = str_split(smart_quotes(addslashes($newArray['ssid'])),25);
					$ssid = $ssids_ptb[0];
					$mac = $newArray['mac'];
					$man =& database::manufactures($mac);
					$chan = $newArray['chan'];
					$r = $newArray["radio"];
					$auth = $newArray['auth'];
					$encry = $newArray['encry'];
					$sectype = $newArray['sectype'];
					switch($sectype)
					{
						case 1:
							$type = "#openStyleDead";
							$auth = "Open";
							$encry = "None";
							break;
						case 2:
							$type = "#wepStyleDead";
							$auth = "Open";
							$encry = "WEP";
							break;
						case 3:
							$type = "#secureStyleDead";
							$auth = "WPA-Personal";
							$encry = "TIKP";
							break;
					}

					switch($r)
					{
						case "a":
							$radio="802.11a";
							break;
						case "b":
							$radio="802.11b";
							break;
						case "g":
							$radio="802.11g";
							break;
						case "n":
							$radio="802.11n";
							break;
						default:
							$radio="Unknown Radio";
							break;
					}

					$table=$ssid.'-'.$mac.'-'.$sectype.'-'.$r.'-'.$chan;

					$sql = "SELECT * FROM `$db_st`.`$table` WHERE `id`='$update_row'";
					$result = mysql_query($sql, $conn);
					$AP_table = mysql_fetch_array($result);
					$otx = $AP_table["otx"];
					$btx = $AP_table["btx"];
					$nt = $AP_table['nt'];
					$label = $AP_table['label'];
					$table_gps = $table.$gps_ext;

					$sql6 = "SELECT * FROM `$db_st`.`$table_gps`";
					$result6 = mysql_query($sql6, $conn);
					$max = mysql_num_rows($result6);

					$sql = "SELECT * FROM `$db_st`.`$table_gps`";
					$result = mysql_query($sql, $conn);
					while($gps_table_first = mysql_fetch_array($result))
					{
						$lat_exp = explode(" ", $gps_table_first['lat']);

						$test = $lat_exp[1]+0;

						if($test == "0.0000"){$zero = 1; continue;}

						$date_first = $gps_table_first["date"];
						$time_first = $gps_table_first["time"];
						$fa = $date_first." ".$time_first;
						$alt = $gps_table_first['alt'];
						$lat =& database::convert_dm_dd($gps_table_first['lat']);
						$long =& database::convert_dm_dd($gps_table_first['long']);
					#	echo $gps_table_first['lat']." - ".$lat."<br>".$gps_table_first['lat']." - ".$lat."<br>";
						$zero = 0;
						$NN++;
						break;
					}
					$total++;
					if($zero == 1){echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">No GPS Data, Skipping Access Point: '.$ssid.'</td></tr>'; $zero == 0; $no_gps++;continue;}

					$sql_1 = "SELECT * FROM `$db_st`.`$table_gps` WHERE `id`='$max'";
					$result_1 = mysql_query($sql_1, $conn);
					$gps_table_last = mysql_fetch_array($result_1);
					$date_last = $gps_table_last["date"];
					$time_last = $gps_table_last["time"];
					$la = $date_last." ".$time_last;
					$ssid_name = '';
					if ($named == 1){$ssid_name = $ssid;}
					fwrite( $fileappend, "<Placemark id=\"".$mac."\">\r\n	<name>".$ssid_name."</name>\r\n	<description><![CDATA[<b>SSID: </b>".$ssid."<br /><b>Mac Address: </b>".$mac."<br /><b>Network Type: </b>".$nt."<br /><b>Radio Type: </b>".$radio."<br /><b>Channel: </b>".$chan."<br /><b>Authentication: </b>".$auth."<br /><b>Encryption: </b>".$encry."<br /><b>Basic Transfer Rates: </b>".$btx."<br /><b>Other Transfer Rates: </b>".$otx."<br /><b>First Active: </b>".$fa."<br /><b>Last Updated: </b>".$la."<br /><b>Latitude: </b>".$lat."<br /><b>Longitude: </b>".$long."<br /><b>Manufacturer: </b>".$man."<br /><a href=\"<a href=\"".$hosturl."/".$root."/opt/fetch.php?id=".$id."\">WiFiDB Link</a>]]></description>\r\n	<styleUrl>".$type."</styleUrl>\r\n	");
					fwrite( $fileappend, "<Point id=\"".$mac."_GPS\">\r\n<coordinates>".$long.",".$lat.",".$alt."</coordinates>\r\n</Point>\r\n</Placemark>\r\n");
					echo '<tr><td style="border-style: solid; border-width: 1px">'.$NN.'</td><td style="border-style: solid; border-width: 1px">Wrote AP: '.$ssid.'</td></tr>';
					unset($gps_table_first["lat"]);
					unset($gps_table_first["long"]);

				}
				fwrite( $fileappend, "	</Folder>\r\n");
				fwrite( $fileappend, "	</Folder>\r\n	</Document>\r\n</kml>");
				fclose( $fileappend );
				if($no_gps < $total)
				{
					echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">Zipping up the files into a KMZ file.</td></tr>';
					$zip = new ZipArchive;
					$filepath = '../out/kmz/lists/'.$filename;
					if ($zip->open($filepath, ZipArchive::CREATE) === TRUE)
					{
						$zip->addFile($temp_kml, 'doc.kml');
				#		$zip->addFromString('doc.kml', $fdata);
						$zip->close();
				#		echo 'Zipped up<br>';
						unlink($temp_kml);
							echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">Move KMZ file from its tmp home to its permanent residence</td></tr>';
							echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">Your Google Earth KML file is ready,<BR>you can download it from <a class="links" href="../out/kmz/lists/'.$filename.'">Here</a></td></tr></table>';
					} else {
						echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">Failed to create Archive.</td></tr>';
					}
				}else
				{
					echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">Could not find any APs with GPS.</td></tr>';
				}
				$end = microtime(true);
				if ($GLOBALS["bench"]  == 1)
				{
					echo "Time is [Unix Epoc]<BR>";
					echo "Start Time: ".$start."<BR>";
					echo "  End Time: ".$end."<BR>";
				}
				break;
			#--------------------#
			#-					-#
			#--------------------#
			case "exp_single_ap":
				$start = microtime(true);
				$NN=0;
				$total = 0;
				$no_gps = 0;
				$date=date('Y-m-d_H-i-s');
				$sql = "SELECT * FROM `$db`.`$wtable` WHERE `ID`='$row'";
				$result = mysql_query($sql, $conn) or die(mysql_error($conn));
				$aparray = mysql_fetch_array($result);
				list() = make_ssid($aparray["ssid"]);

				echo '<table style="border-style: solid; border-width: 1px"><tr class="style4"><th style="border-style: solid; border-width: 1px">Start export of Single AP: '.$aparray["ssid"].'</th></tr>';

				$temp_kml = '../tmp/'.$ssid_tbl.'-'.$aparray['mac']."-".$aparray['sectype']."-".rand().'tmp.kml';
				$filewrite = fopen($temp_kml, "w");
				$fileappend = fopen($temp_kml, "a");

				$date=date('Y-m-d_H-i-s');

				$filename = '../tmp/'.$aparray['ssid'].'-'.$aparray['mac'].'-'.rand().'-single.kmz';
				$moved  = '../out/kmz/single/'.$aparray['ssid'].'-'.$aparray['mac'].'-'.rand().'-single.kmz';
				if($filewrite != FALSE)
				{
					$file_data  = ("");
					$file_data .= ("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n<kml xmlns=\"$KML_SOURCE_URL\">\r\n<!--exp_single_ap--><Document>\r\n<name>RanInt WifiDB KML</name>\r\n");
					$file_data .= ("<Style id=\"openStyleDead\">\r\n<IconStyle>\r\n<scale>0.5</scale>\r\n<Icon>\r\n<href>http://www.vistumbler.net/images/program-images/open.png</href>\r\n</Icon>\r\n</IconStyle>\r\n</Style>\r\n");
					$file_data .= ("<Style id=\"wepStyleDead\">\r\n<IconStyle>\r\n<scale>0.5</scale>\r\n<Icon>\r\n<href>http://www.vistumbler.net/images/program-images/secure-wep.png</href>\r\n</Icon>\r\n</IconStyle>\r\n</Style>\r\n");
					$file_data .= ("<Style id=\"secureStyleDead\">\r\n<IconStyle>\r\n<scale>0.5</scale>\r\n<Icon>\r\n<href>http://www.vistumbler.net/images/program-images/secure.png</href>\r\n</Icon>\r\n</IconStyle>\r\n</Style>\r\n");
					$file_data .= ('<Style id="Location"><LineStyle><color>7f0000ff</color><width>4</width></LineStyle></Style>');
					echo '<tr><td style="border-style: solid; border-width: 1px">Wrote Header to KML File</td><td></td></tr>';
					// open file and write header:

					$manuf =& database::manufactures($aparray['mac']);
					$ssids_ptb = str_split(smart_quotes($aparray['ssid']),25);
					$ssid = $ssids_ptb[0];
					$table=$ssid.'-'.$aparray['mac'].'-'.$aparray['sectype'].'-'.$aparray['radio'].'-'.$aparray['chan'];
					$table_gps = $table.$gps_ext;
		#			echo $table."<br>";
					$sql = "SELECT * FROM `$db_st`.`$table`";
					$result = mysql_query($sql, $conn) or die(mysql_error($conn));
					$rows = mysql_num_rows($result);
		#			echo $rows."<br>";
					$sql = "SELECT * FROM `$db_st`.`$table` WHERE `id`='1'";
					$result1 = mysql_query($sql, $conn) or die(mysql_error($conn));
		#			echo $ap['mac']."<BR>";
					while ($newArray = mysql_fetch_array($result1))
					{
						switch($aparray['sectype'])
						{
							case 1:
								$type = "#openStyleDead";
								break;
							case 2:
								$type = "#wepStyleDead";
								break;
							case 3:
								$type = "#secureStyleDead";
								break;
						}

						switch($aparray['radio'])
						{
							case "a":
								$radio="802.11a";
								break;
							case "b":
								$radio="802.11b";
								break;
							case "g":
								$radio="802.11g";
								break;
							case "n":
								$radio="802.11n";
								break;
							default:
								$radio="Unknown Radio";
								break;
						}

						$otx = $newArray["otx"];
						$btx = $newArray["btx"];
						$nt = $newArray['nt'];
						$label = $newArray['label'];

						$sql6 = "SELECT * FROM `$db_st`.`$table_gps`";
						$result6 = mysql_query($sql6, $conn);
						$max = mysql_num_rows($result6);

						$sql_1 = "SELECT * FROM `$db_st`.`$table_gps`";
						$result_1 = mysql_query($sql_1, $conn);
						while($gps_table_first = mysql_fetch_array($result_1))
						{
							$lat_exp = explode(" ", $gps_table_first['lat']);

							$test = $lat_exp[1]+0;

							if($test == "0.0000"){$zero = 1; continue;}

							$date_first = $gps_table_first["date"];
							$time_first = $gps_table_first["time"];
							$fa = $date_first." ".$time_first;
							$alt = $gps_table_first['alt'];
							$lat =& database::convert_dm_dd($gps_table_first['lat']);
							$long =& database::convert_dm_dd($gps_table_first['long']);
							$zero = 0;
							$NN++;
							break;
						}
						$total++;
						if($zero == 1){echo '<tr><td style="border-style: solid; border-width: 1px">No GPS Data, Skipping Access Point: '.$ssid.'</td></tr>'; $zero == 0;$no_gps++; continue;}

						$sql_2 = "SELECT * FROM `$db_st`.`$table_gps` WHERE `id`='$max'";
						$result_2 = mysql_query($sql_2, $conn);
						$gps_table_last = mysql_fetch_array($result_2);
						$date_last = $gps_table_last["date"];
						$time_last = $gps_table_last["time"];
						$la = $date_last." ".$time_last;
						$ssid_name = '';
						if ($named == 1){$ssid_name = $ssid;}
						$file_data .= ("<Placemark id=\"".$aparray['mac']."\">\r\n	<name>".$ssid_name."</name>\r\n	<description><![CDATA[<b>SSID: </b>".$ssid."<br /><b>Mac Address: </b>".$aparray['mac']."<br /><b>Network Type: </b>".$nt."<br /><b>Radio Type: </b>".$radio."<br /><b>Channel: </b>".$aparray['chan']."<br /><b>Authentication: </b>".$aparray['auth']."<br /><b>Encryption: </b>".$aparray['encry']."<br /><b>Basic Transfer Rates: </b>".$btx."<br /><b>Other Transfer Rates: </b>".$otx."<br /><b>First Active: </b>".$fa."<br /><b>Last Updated: </b>".$la."<br /><b>Latitude: </b>".$lat."<br /><b>Longitude: </b>".$long."<br /><b>Manufacturer: </b>".$manuf."<br /><a href=\"".$hosturl."/".$root."/opt/fetch.php?id=".$aparray['id']."\">WiFiDB Link</a>]]></description>\r\n	<styleUrl>".$type."</styleUrl>\r\n<Point id=\"".$aparray['mac']."_GPS\">\r\n<coordinates>".$long.",".$lat.",".$alt."</coordinates>\r\n</Point>\r\n</Placemark>\r\n");
						echo '<tr><td style="border-style: solid; border-width: 1px">Wrote AP: '.$ssid.'</td></tr>';
					}
				}else
				{
					echo "Failed to write KML File, Check the permissions on the wifidb folder, and make sure that Apache (or what ever HTTP server you are using) has permissions to write";
				}
				fwrite($fileappend, $file_data);
				fwrite( $fileappend, "	</Document>\r\n</kml>");

				fclose( $fileappend );
				if($no_gps < $total)
				{
					if(PHP_OS == 'Linux'){ $dim = '/';}
					elseif(PHP_OS == 'WINNT'){ $dim = '\\';}

					$path = getcwd();
					#echo "Path: ".$path."<br>";
					$path_exp = explode($dim , $path);
					$path_count = count($path_exp);

					foreach($path_exp as $key=>$val)
					{
						if($val == $GLOBALS['root']){ $path_key = $key;}
					#	echo "Val: ".$val."<br>Path key: ".$path_key."<BR>";
					}

					$half_path = '';
					$I = 0;
					if(isset($path_key))
					{
						while($I!=($path_key+1))
						{
					#		echo "I: ".$I."<br>";
							$half_path = $half_path.$path_exp[$I].$dim ;
					#		echo "Half Path: ".$half_path."<br>";
							$I++;
						}
					}
					echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">Zipping up the files into a KMZ file.</td></tr>';
					$zip = new ZipArchive;
				#	echo $half_path.'out/kmz/single/'.$filename."<br>";
					if ($zip->open($filename, ZipArchive::CREATE) === TRUE)
					{
					   $zip->addFile($temp_kml, 'doc.kml');
					 #  $zip->addFromString('doc.kml', $fdata);
					    $zip->close();
				#	    echo 'Zipped up<br>';
						unlink($temp_kml);
						copy($filename, $moved);
						echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">Move KMZ file from its tmp home to its permanent residence</td></tr>';
						echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">Your Google Earth KML file is ready,<BR>you can download it from <a class="links" href="'.$moved.'">Here</a></td></tr></table>';
					} else {
						echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">Could not create KMZ archive.</td></tr>';
					}
				}else
				{
					echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">No APs with GPS found.</td></tr>';
				}
				$end = microtime(true);
				if ($GLOBALS["bench"]  == 1)
				{
					echo "Time is [Unix Epoc]<BR>";
					echo "Start Time: ".$start."<BR>";
					echo "  End Time: ".$end."<BR>";
				}
				break;
			#--------------------#
			#-					-#
			#--------------------#
			case "exp_user_all_kml":
				include('config.inc.php');
				$start = microtime(true);
				$total=0;
				$no_gps = 0;
				echo '<table align="center" style="border-style: solid; border-width: 1px"><tr class="style4"><th style="border-style: solid; border-width: 1px" colspan="2">Start export of all APs for User: '.$user.', to KML</th></tr>';
				$ap_id = array();
				$sql = "SELECT `points` FROM `$db`.`$users_t` WHERE `username`='$user'";
				$result = mysql_query($sql, $conn);
				while($points = mysql_fetch_array($result))
				{
					$points_exp = explode("-",$points['points']);
					foreach($points_exp as $point)
					{
						$points2 = explode(",", $point);
						if($points2[0] == 1){continue;}
						$point_ = explode(":", $points2[1]);
						$ap_id[] = array(
											'id'	=> $point_[0],
											'row'	=> $point_[1]
										  );
					}
				}
				#var_dump($ap_id);
				$temp_kml = '../tmp/'.$user.rand().'tmp.kml';
				$filewrite = fopen($temp_kml, "w");
				$fileappend = fopen($temp_kml, "a");

				$date=date('Y-m-d_H-i-s');

				$filename = '../tmp/'.$user.'.kmz';
				$moved ='../out/kmz/user/'.$user.'.kmz';
				// open file and write header:
				$total = count($ap_id);
				fwrite($fileappend, "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n	<kml xmlns=\"".$KML_SOURCE_URL."\">\r\n<!--exp_user_all_kml--><Document>\r\n<name>RanInt WifiDB KML</name>\r\n");
				fwrite($fileappend, "<Style id=\"openStyleDead\">\r\n<IconStyle>\r\n<scale>0.5</scale>\r\n<Icon>\r\n<href>".$open_loc."</href>\r\n</Icon>\r\n</IconStyle>\r\n</Style>\r\n");
				fwrite($fileappend, "<Style id=\"wepStyleDead\">\r\n<IconStyle>\r\n<scale>0.5</scale>\r\n<Icon>\r\n<href>".$WEP_loc."</href>\r\n</Icon>\r\n</IconStyle>\r\n</Style>\r\n");
				fwrite($fileappend, "<Style id=\"secureStyleDead\">\r\n<IconStyle>\r\n<scale>0.5</scale>\r\n<Icon>\r\n<href>".$WPA_loc."</href>\r\n</Icon>\r\n</IconStyle>\r\n</Style>\r\n");
				fwrite($fileappend, '<Style id="Location"><LineStyle><color>7f0000ff</color><width>4</width></LineStyle></Style>');
				fwrite( $fileappend, "<Folder>\r\n<name>WiFiDB Access Points</name>\r\n<description>Total Number of APs: ".$total."</description>\r\n");
				fwrite( $fileappend, "<Folder>\r\n<name>Access Points for User: ".$user."</name>\r\n");
				echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">Wrote Header to KML File</td></tr>';
				$NN = 0;
				foreach($ap_id as $ap)
				{
					$id = $ap['id'];
					$rows = $ap['row'];

					$sql = "SELECT * FROM `$db`.`$wtable` WHERE `id`='$id'";
					$result = mysql_query($sql, $conn);
					$aps = mysql_fetch_array($result);

					$ssids_ptb = str_split(smart_quotes($aps['ssid']),25);
					$ssid = $ssids_ptb[0];
					$mac = $aps['mac'];
					$sectype = $aps['sectype'];
					$r = $aps['radio'];
					$chan = $aps['chan'];
					$manuf =& database::manufactures($mac);

					$table = $ssid.'-'.$mac.'-'.$sectype.'-'.$r.'-'.$chan;
					$table_gps = $table.$gps_ext;

					switch($r)
					{
						case "a":
							$radio="802.11a";
							break;
						case "b":
							$radio="802.11b";
							break;
						case "g":
							$radio="802.11g";
							break;
						case "n":
							$radio="802.11n";
							break;
						case "u":
							$radio="802.11u";
							break;
						default:
						$radio="Unknown Radio";
							break;
					}
					switch($sectype)
					{
						case 1:
							$type = "#openStyleDead";
							$auth = "Open";
							$encry = "None";
							break;
						case 2:
							$type = "#wepStyleDead";
							$auth = "Open";
							$encry = "WEP";
							break;
						case 3:
							$type = "#secureStyleDead";
							$auth = "WPA-Personal";
							$encry = "TKIP-PSK";
							break;
					}
					$sql = "SELECT * FROM `$db_st`.`$table` WHERE `id`='$rows'";
					$result1 = mysql_query($sql, $conn) or die(mysql_error($conn));

					while ($newArray = mysql_fetch_array($result1))
					{
						$otx = $newArray["otx"];
						$btx = $newArray["btx"];
						$nt = $newArray['nt'];
						$label = $newArray['label'];

						$signal_exp = explode("-", $newArray['sig']);
						$sig_count = count($signal_exp);

						$exp_first = explode("," , $signal_exp[0]);
						$first_sig_gps_id = $exp_first[1];

						$exp_last = explode("," , $signal_exp[$sig_count-1]);
						$last_sig_gps_id = $exp_last[1];

						$sql6 = "SELECT * FROM `$db_st`.`$table_gps`";
						$result6 = mysql_query($sql6, $conn);
						$max = mysql_num_rows($result6);

						$sql_1 = "SELECT * FROM `$db_st`.`$table_gps`";
						$result_1 = mysql_query($sql_1, $conn);
						$zero = 0;
						while($gps_table_first = mysql_fetch_array($result_1))
						{
							$lat_exp = explode(" ", $gps_table_first['lat']);
							$date_first = $gps_table_first["date"];
							$time_first = $gps_table_first["time"];
							$fa = $date_first." ".$time_first;
							$test = $lat_exp[1]+0;

							if($test == "0.0000"){$zero = 1; continue;}


							$alt = $gps_table_first['alt'];
							$lat =& database::convert_dm_dd($gps_table_first['lat']);
							$long =& database::convert_dm_dd($gps_table_first['long']);
							$zero = 0;
							$NN++;
							break;
						}
						if($zero == 1){echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">No GPS Data, Skipping Access Point: '.$aps['ssid'].'</td></tr>'; $zero == 0; $no_gps++; $total++;continue;}
						$total++;
						$sql_2 = "SELECT * FROM `$db_st`.`$table_gps` WHERE `id`='$last_sig_gps_id'";
						$result_2 = mysql_query($sql_2, $conn);
						$gps_table_last = mysql_fetch_array($result_2);
						$date_last = $gps_table_last["date"];
						$time_last = $gps_table_last["time"];
						$la = $date_last." ".$time_last;
						$ssid_name = '';
						if ($named == 1){$ssid_name = $aps['ssid'];}
						fwrite( $fileappend, "<Placemark id=\"".$mac."\">\r\n	<name>".$ssid_name."</name>\r\n	<description><![CDATA[<b>SSID: </b>".$aps['ssid']."<br /><b>Mac Address: </b>".$mac."<br /><b>Network Type: </b>".$nt."<br /><b>Radio Type: </b>".$radio."<br /><b>Channel: </b>".$chan."<br /><b>Authentication: </b>".$auth."<br /><b>Encryption: </b>".$encry."<br /><b>Basic Transfer Rates: </b>".$btx."<br /><b>Other Transfer Rates: </b>".$otx."<br /><b>First Active: </b>".$fa."<br /><b>Last Updated: </b>".$la."<br /><b>Latitude: </b>".$lat."<br /><b>Longitude: </b>".$long."<br /><b>Manufacturer: </b>".$manuf."<br /><a href=\"".$hosturl."/".$root."/opt/fetch.php?id=".$id."\">WiFiDB Link</a>]]></description>\r\n	<styleUrl>".$type."</styleUrl>\r\n<Point id=\"".$mac."_GPS\">\r\n<coordinates>".$long.",".$lat.",".$alt."</coordinates>\r\n</Point>\r\n</Placemark>\r\n");
						echo '<tr><td style="border-style: solid; border-width: 1px">'.$NN.'</td><td style="border-style: solid; border-width: 1px">Wrote AP: '.$aps['ssid'].'</td></tr>';

						unset($gps_table_first["lat"]);
						unset($gps_table_first["long"]);
					}
				}
				fwrite( $fileappend, "	</Folder>\r\n");
				fwrite( $fileappend, "	</Folder>\r\n	</Document>\r\n</kml>");
				fclose( $fileappend );
				if($no_gps < $total)
				{
					echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">Zipping up the files into a KMZ file.</td></tr>';
					$zip = new ZipArchive;
					if ($zip->open($filename, ZipArchive::CREATE) === TRUE) {
					   $zip->addFile($temp_kml, 'doc.kml');
					 #  $zip->addFromString('doc.kml', $fdata);
					    $zip->close();
				#	    echo 'Zipped up<br>';
						unlink($temp_kml);
					} else {
					    echo 'Blown up';
						echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">Your Google Earth KML file is not ready.</td></tr></table>';
						continue;
					}
					echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">Move KMZ file from its tmp home to its permanent residence</td></tr>';
					echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">Your Google Earth KML file is ready,<BR>you can download it from <a class="links" href="'.$moved.'">Here</a></td></tr></table>';
					copy($filename, $moved);
					unlink($filename);
				}else
				{
					echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">Your Google Earth KML file is not ready.</td></tr></table>';

				}
				$end = microtime(true);
				if ($GLOBALS["bench"]  == 1)
				{
					echo "Time is [Unix Epoc]<BR>";
					echo "Start Time: ".$start."<BR>";
					echo "  End Time: ".$end."<BR>";
				}
				break;
			#--------------------#
			#-					-#
			#--------------------#
			case "exp_all_signal":
				$start = microtime(true);
				$NN=0;
				$total = 0;
				$no_gps = 0;
				$signal_image ='';
				$row_id_exp = explode(",",$row);
				$id = $row_id_exp[1];
				$row = $row_id_exp[0];
				$date=date('Y-m-d_H-i-s');
				$sql = "SELECT * FROM `$db`.`$wtable` WHERE `ID`='$id'";
				$result = mysql_query($sql, $conn) or die(mysql_error($conn));
				$aparray = mysql_fetch_array($result);

				$ssid_array = make_ssid($aparray['ssid']);
				$ssid_t = $ssid_array[0];
				$ssid_f = $ssid_array[3];
				$ssid = $ssid_array[4];


				echo '<table style="border-style: solid; border-width: 1px"><tr class="style4"><th style="border-style: solid; border-width: 1px">Start export of Single AP: '.$ssid.'\'s Signal History</th></tr>';
				$name = $ssid_f."-".$aparray['mac'].'-'.$aparray['sectype'].'-'.$aparray['radio'].'-'.$aparray['chan']."-".rand();

				$name2 = $ssid."-".$aparray['mac'].'-'.$aparray['sectype'].'-'.$aparray['radio'].'-'.$aparray['chan']."-".rand();

				$temp_kml = '../tmp/'.$name.'tmp.kml';
				$filewrite = fopen($temp_kml, "w");
				$date=date('Y-m-d_H-i-s');

				$filename = '../tmp/'.$name.'tmp.kmz';
				$moved  = '../out/kmz/single/'.$name.'.kmz';

				if($filewrite != FALSE)
				{
					$fileappend = fopen($temp_kml, "a");
					$table = $ssid_t.'-'.$aparray['mac'].'-'.$aparray['sectype'].'-'.$aparray['radio'].'-'.$aparray['chan'];
					$table_gps = $table.$gps_ext;

					$file_data  = ("");
					$file_data .= ("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n<kml xmlns=\"".$KML_SOURCE_URL."\">\r\n<!--exp_all_signal--><Document>\r\n<name>".$name2."</name>\r\n");
					$file_data .= ("<Style id=\"openStyleDead\">\r\n<IconStyle>\r\n<scale>0.5</scale>\r\n<Icon>\r\n<href>http://www.vistumbler.net/images/program-images/open.png</href>\r\n</Icon>\r\n</IconStyle>\r\n</Style>\r\n");
					$file_data .= ("<Style id=\"wepStyleDead\">\r\n<IconStyle>\r\n<scale>0.5</scale>\r\n<Icon>\r\n<href>http://www.vistumbler.net/images/program-images/secure-wep.png</href>\r\n</Icon>\r\n</IconStyle>\r\n</Style>\r\n");
					$file_data .= ("<Style id=\"secureStyleDead\">\r\n<IconStyle>\r\n<scale>0.5</scale>\r\n<Icon>\r\n<href>http://www.vistumbler.net/images/program-images/secure.png</href>\r\n</Icon>\r\n</IconStyle>\r\n</Style>\r\n");
					$file_data .= ('<Style id="Location"><LineStyle><color>7f0000ff</color><width>4</width></LineStyle></Style>');
					echo '<tr><td style="border-style: solid; border-width: 1px">Wrote Header to KML File</td></tr>';
					// open file and write header:

					$manuf =& database::manufactures($aparray['mac']);
		#			echo $table."<br>";
					$sql = "SELECT * FROM `$db_st`.`$table`";
					$result = mysql_query($sql, $conn) or die(mysql_error($conn));
					$rows = mysql_num_rows($result);
		#			echo $rows."<br>";
					$sql = "SELECT * FROM `$db_st`.`$table` WHERE `id`='$row'";
					$result1 = mysql_query($sql, $conn) or die(mysql_error($conn));
		#			echo $ap['mac']."<BR>";
					$newArray = mysql_fetch_array($result1);
					switch($aparray['sectype'])
					{
						case 1:
							$type = "#openStyleDead";
							break;
						case 2:
							$type = "#wepStyleDead";
							break;
						case 3:
							$type = "#secureStyleDead";
							break;
					}

					switch($aparray['radio'])
					{
						case "a":
							$radio="802.11a";
							break;
						case "b":
							$radio="802.11b";
							break;
						case "g":
							$radio="802.11g";
							break;
						case "n":
							$radio="802.11n";
							break;
						default:
							$radio="Unknown Radio";
							break;
					}

					$otx = $newArray["otx"];
					$btx = $newArray["btx"];
					$nt = $newArray['nt'];
					$label = $newArray['label'];

					$sql6 = "SELECT * FROM `$db_st`.`$table_gps`";
					$result6 = mysql_query($sql6, $conn);
					$max = mysql_num_rows($result6);

					$sql_1 = "SELECT * FROM `$db_st`.`$table_gps`";
					$result_1 = mysql_query($sql_1, $conn);
					while($gps_table = mysql_fetch_array($result_1))
					{
						$lat_exp = explode(" ", $gps_table['lat']);
						$test = $lat_exp[1]+0;

						if($test == "0.0000"){$zero = 1; continue;}

						$date_first = $gps_table["date"];
						$time_first = $gps_table["time"];
						$fa = $date_first." ".$time_first;
						$alt = $gps_table['alt'];
						$lat =& database::convert_dm_dd($gps_table['lat']);
						$long =& database::convert_dm_dd($gps_table['long']);
						$zero = 0;
						$NN++;
						break;
					}
					$total++;
					if($zero == 1){echo '<tr><td style="border-style: solid; border-width: 1px">No GPS Data, Skipping Access Point: '.$ssid.'</td></tr>'; $zero == 0; $no_gps++;continue;}

					$sql_2 = "SELECT * FROM `$db_st`.`$table_gps` WHERE `id`='$max'";
					$result_2 = mysql_query($sql_2, $conn);
					$gps_table_last = mysql_fetch_array($result_2);
					$date_last = $gps_table_last["date"];
					$time_last = $gps_table_last["time"];

					$la = $date_last." ".$time_last;
					$file_data .= ("<Placemark id=\"".$aparray['mac']."\">\r\n	<name>".$ssid."</name>\r\n	<description><![CDATA[<b>SSID: </b>".$ssid."<br /><b>Mac Address: </b>".$aparray['mac']."<br /><b>Network Type: </b>".$nt."<br /><b>Radio Type: </b>".$radio."<br /><b>Channel: </b>".$aparray['chan']."<br /><b>Authentication: </b>".$aparray['auth']."<br /><b>Encryption: </b>".$aparray['encry']."<br /><b>Basic Transfer Rates: </b>".$btx."<br /><b>Other Transfer Rates: </b>".$otx."<br /><b>First Active: </b>".$fa."<br /><b>Last Updated: </b>".$la."<br /><b>Latitude: </b>".$lat."<br /><b>Longitude: </b>".$long."<br /><b>Manufacturer: </b>".$manuf."<br /><a href=\"".$hosturl."/".$root."/opt/fetch.php?id=".$aparray['id']."\">WiFiDB Link</a>]]></description>\r\n	<styleUrl>".$type."</styleUrl>\r\n<Point id=\"".$aparray['mac']."_GPS\">\r\n<coordinates>".$long.",".$lat.",".$alt."</coordinates>\r\n</Point>\r\n</Placemark>\r\n");
					echo '<tr><td style="border-style: solid; border-width: 1px">Wrote AP: '.$ssid.'</td></tr>';

					$sql_3 = "SELECT * FROM `$db_st`.`$table` WHERE `id`='$row'";
					$sig_result = mysql_query($sql_3, $conn) or die(mysql_error($conn));
					$array = mysql_fetch_array($sig_result);
					$signals = explode("-",$array['sig']);
	#				echo $array['sig'].'<br>';
					foreach($signals as $signal)
					{
						$sig_exp = explode(",",$signal);
						$gpsid = $sig_exp[0];
#						echo $signal.'<br>';
						$sig = $sig_exp[1];
						$sql_1 = "SELECT * FROM `$db_st`.`$table_gps` WHERE `id` = '$gpsid'";
						$result_1 = mysql_query($sql_1, $conn);
						$gps = mysql_fetch_array($result_1);
						$lat_exp = explode(" ", $gps['lat']);
						$test = $lat_exp[1]+0;
						if($test == "0.0000"){$zero = 1; continue;}

						$alt = $gps['alt'];
		#				echo "IN: ".$gps['lat'].'<br>';
						$lat =& database::convert_dm_dd($gps['lat']);
		#				echo "out: ".$lat.'<br>';
						$long =& database::convert_dm_dd($gps['long']);
						$file_data .= ("<Placemark id=\"".$gps['id']."\"><styleUrl>".$signal_image."</styleUrl>\r\n<description><![CDATA[<b>Signal Strength: </b>".$sig."%<br />]]></description>\r\n<Point id=\"".$aparray['mac']."_GPS\">\r\n<coordinates>".$long.",".$lat.",".$alt."</coordinates>\r\n</Point>\r\n</Placemark>");
						echo '<tr><td style="border-style: solid; border-width: 1px">Plotted Signal GPS Point</td></tr>';
					}
				}else
				{
					echo "Failed to write KML File, Check the permissions on the wifidb folder, and make sure that Apache (or what ever HTTP server you are using) has permissions to write";
				}
				fwrite($fileappend, $file_data);
				fwrite( $fileappend, "	</Document>\r\n</kml>");

				fclose( $fileappend );
				if($no_gps < $total)
				{
					if(PHP_OS == 'Linux'){ $dim = '/';}
					elseif(PHP_OS == 'WINNT'){ $dim = '\\';}

					$path = getcwd();
					#echo "Path: ".$path."<br>";
					$path_exp = explode($dim , $path);
					$path_count = count($path_exp);

					foreach($path_exp as $key=>$val)
					{
						if($val == $GLOBALS['root']){ $path_key = $key;}
					#	echo "Val: ".$val."<br>Path key: ".$path_key."<BR>";
					}

					$half_path = '';
					$I = 0;
					if(isset($path_key))
					{
						while($I!=($path_key+1))
						{
					#		echo "I: ".$I."<br>";
							$half_path = $half_path.$path_exp[$I].$dim ;
					#		echo "Half Path: ".$half_path."<br>";
							$I++;
						}
					}
					echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">Zipping up the files into a KMZ file.</td></tr>';
					$zip = new ZipArchive;
			#		echo $half_path.'out/kmz/single/'.$filename."<br>";
					if ($zip->open($moved, ZipArchive::CREATE) === TRUE)
					{
					   $zip->addFile($temp_kml, 'doc.kml');
					 #  $zip->addFromString('doc.kml', $fdata);
					    $zip->close();
				#	    echo 'Zipped up<br>';
						unlink($temp_kml);
				#		copy($filename, $moved);
						echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">Move KMZ file from its tmp home to its permanent residence</td></tr>';
						echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">Your Google Earth KML file is ready,<BR>you can download it from <a class="links" href="'.$moved.'">Here</a></td></tr></table>';
					} else {
						echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">Could not create KMZ archive.</td></tr>';
					}
				}else
				{

					echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">No APs with GPS found.</td></tr>';
				}
				$end = microtime(true);
				if ($GLOBALS["bench"]  == 1)
				{
					echo "Time is [Unix Epoc]<BR>";
					echo "Start Time: ".$start."<BR>";
					echo "  End Time: ".$end."<BR>";
				}
				break;
			#--------------------#
			#-			-#
			#--------------------#
		}
	}



	function exp_search($values=array(), $named = 0)
	{

		include('config.inc.php');
		$start = microtime(true);
		$total=0;
		$no_gps = 0;
		?><table align="center" style="border-style: solid; border-width: 1px"><tr class="style4"><th style="border-style: solid; border-width: 1px" colspan="2">Start export of Search Results to KML</th></tr><?php

		$date=date('Y-m-d_H-i-s');
	#	var_dump($values);

		$temp_kml = "../tmp/save_".$date."_".rand()."_tmp.kml";
		$filewrite = fopen($temp_kml, "w");
		$fileappend = fopen($temp_kml, "a");

		$filename = "save".$date.rand().'.kmz';
		$moved ='../out/kmz/lists/'.$filename;
		// open file and write header:
		fwrite($fileappend, "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n	<kml xmlns=\"".$KML_SOURCE_URL."\">\r\n<!--exp_user_all_kml--><Document>\r\n<name>RanInt WifiDB KML</name>\r\n");
		fwrite($fileappend, "<Style id=\"openStyleDead\">\r\n<IconStyle>\r\n<scale>0.5</scale>\r\n<Icon>\r\n<href>".$open_loc."</href>\r\n</Icon>\r\n</IconStyle>\r\n</Style>\r\n");
		fwrite($fileappend, "<Style id=\"wepStyleDead\">\r\n<IconStyle>\r\n<scale>0.5</scale>\r\n<Icon>\r\n<href>".$WEP_loc."</href>\r\n</Icon>\r\n</IconStyle>\r\n</Style>\r\n");
		fwrite($fileappend, "<Style id=\"secureStyleDead\">\r\n<IconStyle>\r\n<scale>0.5</scale>\r\n<Icon>\r\n<href>".$WPA_loc."</href>\r\n</Icon>\r\n</IconStyle>\r\n</Style>\r\n");
		fwrite($fileappend, '<Style id="Location"><LineStyle><color>7f0000ff</color><width>4</width></LineStyle></Style>');
		fwrite( $fileappend, "<Folder>\r\n<name>WiFiDB Access Points</name>\r\n<description>Total Number of APs: ".$total."</description>\r\n");
		fwrite( $fileappend, "<Folder>\r\n<name>Access Points for WiFiDB Search</name>\r\n");
		?><tr><td colspan="2" style="border-style: solid; border-width: 1px">Wrote Header to KML File</td></tr><?php
		$NN = 0;
		$sql = "SELECT * FROM `$db`.`$wtable` WHERE " . implode(' AND ', $values);
		#echo $sql."<BR>";
		$result = mysql_query($sql, $conn);
		while($aps = mysql_fetch_array($result))
		{
			$id = $aps['id'];
			$ssids_ptb = str_split(smart_quotes($aps['ssid']),25);
			$ssid = $ssids_ptb[0];
			$mac = $aps['mac'];
			$sectype = $aps['sectype'];
			$r = $aps['radio'];
			$chan = $aps['chan'];
			$manuf =& database::manufactures($mac);

			$table = $ssid.$sep.$mac.$sep.$sectype.$sep.$r.$sep.$chan;
			$table_gps = $table.$gps_ext;
#			echo $table."<BR>".$table_gps."<BR>";
			switch($r)
			{
				case "a":
					$radio="802.11a";
					break;
				case "b":
					$radio="802.11b";
					break;
				case "g":
					$radio="802.11g";
					break;
				case "n":
					$radio="802.11n";
					break;
				case "u":
					$radio="802.11u";
					break;
				default:
					$radio="Unknown Radio";
					break;
			}
			switch($sectype)
			{
				case 1:
					$type = "#openStyleDead";
					$auth = "Open";
					$encry = "None";
					break;
				case 2:
					$type = "#wepStyleDead";
					$auth = "Open";
					$encry = "WEP";
					break;
				case 3:
					$type = "#secureStyleDead";
					$auth = "WPA-Personal";
					$encry = "TKIP-PSK";
					break;
			}
			$sql = "SELECT id FROM `$db_st`.`$table`";
		#	echo $sql."<BR>";
			$result1 = mysql_query($sql, $conn) or die(mysql_error($conn));
			$rows = mysql_num_rows($result1);
		#	echo $rows."<BR>";
			$sql = "SELECT * FROM `$db_st`.`$table` WHERE `id`='$rows'";
			$result1 = mysql_query($sql, $conn) or die(mysql_error($conn));
		#	echo $sql."<BR>";
			while ($newArray = mysql_fetch_array($result1))
			{
				$otx = $newArray["otx"];
				$btx = $newArray["btx"];
				$nt = $newArray['nt'];
				$label = $newArray['label'];

				$signal_exp = explode("-", $newArray['sig']);
				$sig_count = count($signal_exp);

				$exp_first = explode("," , $signal_exp[0]);
				$first_sig_gps_id = $exp_first[1];

				$exp_last = explode("," , $signal_exp[$sig_count-1]);
				$last_sig_gps_id = $exp_last[1];

				$sql6 = "SELECT * FROM `$db_st`.`$table_gps`";
				$result6 = mysql_query($sql6, $conn);
				$max = mysql_num_rows($result6);

				$sql_1 = "SELECT * FROM `$db_st`.`$table_gps`";
				$result_1 = mysql_query($sql_1, $conn);
				$zero = 0;
				while($gps_table_first = mysql_fetch_array($result_1))
				{
					$lat_exp = explode(" ", $gps_table_first['lat']);
					$date_first = $gps_table_first["date"];
					$time_first = $gps_table_first["time"];
					$fa = $date_first." ".$time_first;
					$test = $lat_exp[1]+0;

					if($test == "0.0000"){$zero = 1; continue;}


					$alt = $gps_table_first['alt'];
					$lat =& database::convert_dm_dd($gps_table_first['lat']);
					$long =& database::convert_dm_dd($gps_table_first['long']);
					$zero = 0;
					$NN++;
					break;
				}
				if($zero == 1){echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">No GPS Data, Skipping Access Point: '.$aps['ssid'].'</td></tr>'; $zero == 0; $no_gps++; $total++;continue;}
				$total++;
				$sql_2 = "SELECT * FROM `$db_st`.`$table_gps` WHERE `id`='$last_sig_gps_id'";
				$result_2 = mysql_query($sql_2, $conn);
				$gps_table_last = mysql_fetch_array($result_2);
				$date_last = $gps_table_last["date"];
				$time_last = $gps_table_last["time"];
				$la = $date_last." ".$time_last;
				$ssid_name = '';
				if ($named == 1){$ssid_name = $aps['ssid'];}
				fwrite( $fileappend, "<Placemark id=\"".$mac."\">\r\n	<name>".$ssid_name."</name>\r\n	<description><![CDATA[<b>SSID: </b>".$aps['ssid']."<br /><b>Mac Address: </b>".$mac."<br /><b>Network Type: </b>".$nt."<br /><b>Radio Type: </b>".$radio."<br /><b>Channel: </b>".$chan."<br /><b>Authentication: </b>".$auth."<br /><b>Encryption: </b>".$encry."<br /><b>Basic Transfer Rates: </b>".$btx."<br /><b>Other Transfer Rates: </b>".$otx."<br /><b>First Active: </b>".$fa."<br /><b>Last Updated: </b>".$la."<br /><b>Latitude: </b>".$lat."<br /><b>Longitude: </b>".$long."<br /><b>Manufacturer: </b>".$manuf."<br /><a href=\"".$hosturl."/".$root."/opt/fetch.php?id=".$id."\">WiFiDB Link</a>]]></description>\r\n	<styleUrl>".$type."</styleUrl>\r\n<Point id=\"".$mac."_GPS\">\r\n<coordinates>".$long.",".$lat.",".$alt."</coordinates>\r\n</Point>\r\n</Placemark>\r\n");
				echo '<tr><td style="border-style: solid; border-width: 1px">'.$NN.'</td><td style="border-style: solid; border-width: 1px">Wrote AP: '.$aps['ssid'].'</td></tr>';

				unset($gps_table_first["lat"]);
				unset($gps_table_first["long"]);
			}
		}
		fwrite( $fileappend, "	</Folder>\r\n");
		fwrite( $fileappend, "	</Folder>\r\n	</Document>\r\n</kml>");
		fclose( $fileappend );
		if($no_gps < $total)
		{
			echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">Zipping up the files into a KMZ file.</td></tr>';
			$zip = new ZipArchive;
			if ($zip->open($filename, ZipArchive::CREATE) === TRUE) {
			   $zip->addFile($temp_kml, 'doc.kml');
			 #  $zip->addFromString('doc.kml', $fdata);
				$zip->close();
		#	    echo 'Zipped up<br>';
				unlink($temp_kml);
			} else {
				echo 'Blown up';
				echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">Your Google Earth KML file is not ready.</td></tr></table>';
				continue;
			}

			echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">Move KMZ file from its tmp home to its permanent residence</td></tr>';
			echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">Your Google Earth KML file is ready,<BR>you can download it from <a class="links" href="'.$moved.'">Here</a></td></tr></table>';
			copy($filename, $moved);
			unlink($filename);
		}else
		{
			echo '<tr><td colspan="2" style="border-style: solid; border-width: 1px">Your Google Earth KML file is not ready.</td></tr></table>';

		}
		$end = microtime(true);
		if ($GLOBALS["bench"]  == 1)
		{
			echo "Time is [Unix Epoc]<BR>";
			echo "Start Time: ".$start."<BR>";
			echo "  End Time: ".$end."<BR>";
		}
	}


	#========================================================================================================================#
	#													Export to Garmin GPX File											 #
	#========================================================================================================================#

	function exp_gpx($export = "", $user = "", $row = 0)
	{
		include('config.inc.php');
		include('manufactures.inc.php');
		$gps_array = array();
		switch ($export)
		{
			case "exp_all_signal":
				$start = microtime(true);
				$NN=0;
				$signal_image ='';
				$row_id_exp = explode(",",$row);
				$id = $row_id_exp[1];
				$row = $row_id_exp[0];
				$date = date('Y-m-d_H-i-s');
				$sql = "SELECT * FROM `$db`.`$wtable` WHERE `ID`='$id'";
				$result = mysql_query($sql, $conn) or die(mysql_error($conn));
				$aparray = mysql_fetch_array($result);
				$ssid_array = make_ssid($aparray['ssid']);
				$ssid_t = $ssid_array[0];
				$ssid_f = $ssid_array[1];
				$ssid = $ssid_array[2];
				$file_ext = $ssid_f."-".$aparray['mac']."-".$aparray['sectype']."-".$date.".gpx";
				echo '<table style="border-style: solid; border-width: 1px"><tr class="style4"><th style="border-style: solid; border-width: 1px">Start export of Single AP: '.$ssid.'\'s Signal History</th></tr>';
				$filename = ($gpx_out.$file_ext);
				// define initial write and appends
				$filewrite = fopen($filename, "w");
				if($filewrite != FALSE)
				{
					$table=$ssid_t.'-'.$aparray['mac'].'-'.$aparray['sectype'].'-'.$aparray['radio'].'-'.$aparray['chan'];
					$table_gps = $table.$gps_ext;

					$file_data  = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\" ?>\r\n<gpx xmlns=\"http://www.topografix.com/GPX/1/1\" creator=\"WiFiDB 0.16 Build 2\" version=\"1.1\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.topografix.com/GPX/1/1 http://www.topografix.com/GPX/1/1/gpx.xsd\">";
					// write file header buffer var

		#			echo $table."<br>";
					$sql = "SELECT * FROM `$db_st`.`$table`";
					$result = mysql_query($sql, $conn) or die(mysql_error($conn));
					$rows = mysql_num_rows($result);
		#			echo $rows."<br>";
					$sql = "SELECT * FROM `$db_st`.`$table` WHERE `id`='$row'";
					$result1 = mysql_query($sql, $conn) or die(mysql_error($conn));
					$mac_e = str_split($aparray['mac'],2);
					$macadd = $mac_e[0].":".$mac_e[1].":".$mac_e[2].":".$mac_e[3].":".$mac_e[4].":".$mac_e[5];

					$newArray = mysql_fetch_array($result1);
					$type = $aparray['sectype'];
					if($type == 1){$color = "Navaid, Green";}
					if($type == 2){$color = "Navaid, Amber";}
					if($type == 3){$color = "Navaid, Red";}

					$sql6 = "SELECT * FROM `$db_st`.`$table_gps`";
					$result6 = mysql_query($sql6, $conn);
					$max = mysql_num_rows($result6);

					$sql_1 = "SELECT * FROM `$db_st`.`$table_gps`";
					$result_1 = mysql_query($sql_1, $conn);
					while($gps_table = mysql_fetch_array($result_1))
					{
						$lat_exp = explode(" ", $gps_table['lat']);
						$test = $lat_exp[1]+0;

						if($test == "0.0000"){$zero = 1; continue;}

						$date = $gps_table["date"];
						$time = $gps_table["time"];
						$alt = $gps_table['alt'];
						$alt = $alt * 3.28;
						$lat =& database::convert_dm_dd($gps_table['lat']);
						$long =& database::convert_dm_dd($gps_table['long']);
						$zero = 0;
						$NN++;
						break;
					}
					if($zero == 1){echo '<tr><td style="border-style: solid; border-width: 1px">No GPS Data, Skipping Access Point: '.$ssid.'</td></tr>'; $zero == 0; continue;}

					$file_data .= "<wpt lat=\"".$lat."\" lon=\"".$long."\">\r\n"
									."<ele>".$alt."</ele>\r\n"
									."<time>".$date."T".$time."Z</time>\r\n"
									."<name>".$ssid."</name>\r\n"
									."<cmt>".$macadd."</cmt>\r\n"
									."<desc>".$macadd."</desc>\r\n"
									."<sym>".$color."</sym>\r\n<extensions>\r\n"
									."<gpxx:WaypointExtension xmlns:gpxx=\"http://www.garmin.com/xmlschemas/GpxExtensions/v3\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.garmin.com/xmlschemas/GpxExtensions/v3 http://www.garmin.com/xmlschemas/GpxExtensions/v3/GpxExtensionsv3.xsd\">\r\n"
									."<gpxx:DisplayMode>SymbolAndName</gpxx:DisplayMode>\r\n<gpxx:Categories>\r\n"
									."<gpxx:Category>Category ".$type."</gpxx:Category>\r\n</gpxx:Categories>\r\n</gpxx:WaypointExtension>\r\n</extensions>\r\n</wpt>\r\n\r\n";
					echo '<tr><td style="border-style: solid; border-width: 1px">Wrote AP: '.$ssid.'</td></tr>';

					$sql_3 = "SELECT * FROM `$db_st`.`$table` WHERE `id`='$row'";
					$sig_result = mysql_query($sql_3, $conn) or die(mysql_error($conn));
					$array = mysql_fetch_array($sig_result);
					$signals = explode("-",$array['sig']);
					$file_data .= "<trk>\r\n<name>GPS Track</name>\r\n<trkseg>\r\n";
					foreach($signals as $signal)
					{
						$sig_exp = explode(",",$signal);
						$gpsid = $sig_exp[0];

						$sig = $sig_exp[1];
						$sql_1 = "SELECT * FROM `$db_st`.`$table_gps` WHERE `id` = '$gpsid'";
						$result_1 = mysql_query($sql_1, $conn);
						$gps = mysql_fetch_array($result_1);
						$lat_exp = explode(" ", $gps['lat']);
						$test = $lat_exp[1]+0;
						if($test == "0.0000"){$zero = 1; continue;}

						$alt = $gps['alt'];
						$alt = $alt * 3.28;

						$lat =& database::convert_dm_dd($gps['lat']);

						$long =& database::convert_dm_dd($gps['long']);
						$file_data .= "<trkpt lat=\"".$lat."\" lon=\"".$long."\">\r\n"
									."<ele>".$alt."</ele>\r\n"
									."<time>".$date."T".$time."Z</time>\r\n"
									."</trkpt>\r\n";
						echo '<tr><td style="border-style: solid; border-width: 1px">Plotted Signal GPS Point</td></tr>';
					}
					$file_data .= "</trkseg>\r\n</trk></gpx>";
				}else
				{
					echo "Failed to write GPX File, Check the permissions on the wifidb folder, and make sure that Apache (or the HTTP server you are using) has permissions to write";
				}
				$fileappend = fopen($filename, "a");
				fwrite($fileappend, $file_data);
				fclose( $fileappend );
				echo '<tr class="style4"><td style="border-style: solid; border-width: 1px">Your GPX file is ready,<BR>you can download it from <a class="links" href="'.$filename.'">Here</a></td></tr></table>';

				mysql_close($conn);
				$end = microtime(true);
				if ($GLOBALS["bench"]  == 1)
				{
					echo "Time is [Unix Epoc]<BR>";
					echo "Start Time: ".$start."<BR>";
					echo "  End Time: ".$end."<BR>";
				}
				break;
		}
	}

	#========================================================================================================================#
	#													Export to Vistumbler VS1 File										 #
	#========================================================================================================================#

	function exp_vs1($export = "", $user = "", $row = 0, $screen = "HTML")
	{
		include_once('config.inc.php');
		include('manufactures.inc.php');
		$conn	= 	$GLOBALS['conn'];
		$db	= 	$GLOBALS['db'];
		$db_st	= 	$GLOBALS['db_st'];
		$wtable	=	$GLOBALS['wtable'];
		$users_t	=	$GLOBALS['users_t'];
		$gps_ext	=	$GLOBALS['gps_ext'];
		$vs1_out	=	$GLOBALS['wifidb_install']."/out/VS1/";
		$gps_array = array();
		switch ($export)
		{
			case "exp_user_all":
				if($screen == "HTML"){echo '<table><tr class="style4"><th style="border-style: solid; border-width: 1px">Start Export Of $user</th></tr>';}
				$sql_		= "SELECT * FROM `$db`.`$users_t` WHERE `username` = '$user' ORDER by `id` ASC";
				$result_2	= mysql_query($sql_, $conn) or die(mysql_error($conn));
				echo "Rows: ".mysql_num_rows($result_2)."\r\n";
				$username = $list_array['username'];
				$nn =-1;
				$n =1;	# GPS Array KEY -has to start at 1 vistumbler will error out if the first GPS point has a key of 0
				while($list_array = mysql_fetch_array($result_2))
				{
					if($list_array["points"] == ''){continue;}
					$points = explode("-", $list_array['points']);
					$title = $list_array['title'];
				#	echo "Starting AP Export.\r\n";
					foreach($points as $point)
					{
				#		echo $point."\r\n";
						$nn++;
						$point_exp = explode(",", $point);
						$pnt = explode(":", $point_exp[1]);
						$rows = $pnt[1];
						$APID = $pnt[0];
						$sql_1	= "SELECT * FROM `$db`.`$wtable` WHERE `id` = '$APID' LIMIT 1";
						$result_1	= mysql_query($sql_1, $conn) or die(mysql_error($conn));
						$ap_array = mysql_fetch_array($result_1);
						#var_dump($ap_array);
						$manuf = @database::manufactures($ap_array['mac']);
						switch($ap_array['sectype'])
							{
								case 1:
									$type = "#openStyleDead";
									$auth = "Open";
									$encry = "None";
									break;
								case 2:
									$type = "#wepStyleDead";
									$auth = "Open";
									$encry = "WEP";
									break;
								case 3:
									$type = "#secureStyleDead";
									$auth = "WPA-Personal";
									$encry = "TKIP-PSK";
									break;
							}
						switch($ap_array['radio'])
							{
								case "a":
									$radio="802.11a";
									break;
								case "b":
									$radio="802.11b";
									break;
								case "g":
									$radio="802.11g";
									break;
								case "n":
									$radio="802.11n";
									break;
								default:
									$radio="Unknown Radio";
									break;
							}
				#		echo $ap_array['id']." -- ".$ap_array['ssid']."\r\n";
						$ssid_edit = html_entity_decode($ap_array['ssid']);
						list($ssid_t, $ssid_f, $ssid)  = make_ssid($ssid_edit);
					#	$ssid_t = $ssid_array[0];
					#	$ssid_f = $ssid_array[1];
					#	$ssid = $ssid_array[2];
						$table	=	$ssid_t.'-'.$ap_array['mac'].'-'.$ap_array['sectype'].'-'.$ap_array['radio'].'-'.$ap_array['chan'];
						$sql1 = "SELECT * FROM `$db_st`.`$table` WHERE `id` = '$rows'";
						$result1 = mysql_query($sql1, $conn) or die(mysql_error($conn));
						$newArray = mysql_fetch_array($result1);
	#					echo $nn."<BR>";
						$otx	= $newArray["otx"];
						$btx	= $newArray["btx"];
						$nt		= $newArray['nt'];
						$label	= $newArray['label'];
						$signal	= $newArray['sig'];
						$aps[$nn]	= array(
											'id'		=>	$ap_array['id'],
											'ssid'		=>	$ssid_t,
											'mac'		=>	$ap_array['mac'],
											'sectype'	=>	$ap_array['sectype'],
											'r'			=>	$radio,
											'radio'		=>	$ap_array['radio'],
											'chan'		=>	$ap_array['chan'],
											'man'		=>	$manuf,
											'type'		=>	$type,
											'auth'		=>	$auth,
											'encry'		=>	$encry,
											'label'		=>	$label,
											'nt'		=>	$nt,
											'btx'		=>	$btx,
											'otx'		=>	$otx,
											'sig'		=>	$signal
											);

						$sig		=	$aps[$nn]['sig'];
						$signals	=	explode("-", $sig);
		#				echo $sig."<BR>";
						$table_gps		=	$aps[$nn]['ssid'].'-'.$aps[$nn]['mac'].'-'.$aps[$nn]['sectype'].'-'.$aps[$nn]['radio'].'-'.$aps[$nn]['chan'].$gps_ext;
				#		echo $table_gps."\r\n";
						foreach($signals as $key=>$val)
						{
							$sig_exp = explode(",", $val);
							$gps_id	= $sig_exp[0];

							$sql1 = "SELECT * FROM `$db_st`.`$table_gps` WHERE `id` = '$gps_id'";
							$result1 = mysql_query($sql1, $conn) or die(mysql_error($conn));
							$gps_table = mysql_fetch_array($result1);
							$gps_array[$n]	=	array(
													"lat" => $gps_table['lat'],
													"long" => $gps_table['long'],
													"sats" => $gps_table['sats'],
													"hdp" => $gps_table['hdp'],
													"alt" => $gps_table['alt'],
													"geo" => $gps_table['geo'],
													"kmh" => $gps_table['kmh'],
													"mph" => $gps_table['mph'],
													"track" => $gps_table['track'],
													"date" => $gps_table['date'],
													"time" => $gps_table['time']
													);
							$n++;
							$signals[] = $n.",".$sig_exp[1];
						}
						echo $nn."-".$n."==";
						$sig_new = implode("-", $signals);
						$aps[$nn]['sig'] = $sig_new;
						unset($signals);
					}
				}
			break;

			case "exp_user_list":
				$sql_		= "SELECT * FROM `$db`.`$users_t` WHERE `id` = '$row' LIMIT 1";
				$result_1	= mysql_query($sql_, $conn) or die(mysql_error($conn));
				$list_array = mysql_fetch_array($result_1);
				if($list_array["points"] == ''){$return = array(0=>0, 1=>"Empty return"); return $return;}
				$points = explode("-", $list_array['points']);
				$title = $list_array['title'];
				$username = $list_array['username'];
				if($screen == "HTML"){echo '<table><tr class="style4"><th style="border-style: solid; border-width: 1px">Start Export Of $title by $username</th></tr>';}
			#	echo "Starting AP Export.\r\n";
				$nn=-1;
				foreach($points as $point)
				{
			#		echo $point."\r\n";
					$nn++;
			#		echo $nn."\r\n";
					$point_exp = explode(",", $point);
					$pnt = explode(":", $point_exp[1]);
					$rows = $pnt[1];
					$APID = $pnt[0];
					$sql_1	= "SELECT * FROM `$db`.`$wtable` WHERE `id` = '$APID' LIMIT 1";
					$result_1	= mysql_query($sql_1, $conn) or die(mysql_error($conn));
					$ap_array = mysql_fetch_array($result_1);
					#var_dump($ap_array);
					$manuf = database::manufactures($ap_array['mac']);
					switch($ap_array['sectype'])
						{
							case 1:
								$type = "#openStyleDead";
								$auth = "Open";
								$encry = "None";
								break;
							case 2:
								$type = "#wepStyleDead";
								$auth = "Open";
								$encry = "WEP";
								break;
							case 3:
								$type = "#secureStyleDead";
								$auth = "WPA-Personal";
								$encry = "TKIP-PSK";
								break;
						}
					switch($ap_array['radio'])
						{
							case "a":
								$radio="802.11a";
								break;
							case "b":
								$radio="802.11b";
								break;
							case "g":
								$radio="802.11g";
								break;
							case "n":
								$radio="802.11n";
								break;
							default:
								$radio="Unknown Radio";
								break;
						}
			#		echo $ap_array['id']." -- ".$ap_array['ssid']."\r\n";
					$ssid_edit = html_entity_decode($ap_array['ssid']);
					list($ssid_t, $ssid_f, $ssid)  = make_ssid($ssid_edit);
				#	$ssid_t = $ssid_array[0];
				#	$ssid_f = $ssid_array[1];
				#	$ssid = $ssid_array[2];
					$table	=	$ssid_t.'-'.$ap_array['mac'].'-'.$ap_array['sectype'].'-'.$ap_array['radio'].'-'.$ap_array['chan'];
					$sql1 = "SELECT * FROM `$db_st`.`$table` WHERE `id` = '$rows'";
					$result1 = mysql_query($sql1, $conn) or die(mysql_error($conn));
					$newArray = mysql_fetch_array($result1);
#					echo $nn."<BR>";
					$otx	= $newArray["otx"];
					$btx	= $newArray["btx"];
					$nt		= $newArray['nt'];
					$label	= $newArray['label'];
					$signal	= $newArray['sig'];
					$aps[$nn]	= array(
										'id'		=>	$ap_array['id'],
										'ssid'		=>	$ssid_t,
										'mac'		=>	$ap_array['mac'],
										'sectype'	=>	$ap_array['sectype'],
										'r'			=>	$radio,
										'radio'		=>	$ap_array['radio'],
										'chan'		=>	$ap_array['chan'],
										'man'		=>	$manuf,
										'type'		=>	$type,
										'auth'		=>	$auth,
										'encry'		=>	$encry,
										'label'		=>	$label,
										'nt'		=>	$nt,
										'btx'		=>	$btx,
										'otx'		=>	$otx,
										'sig'		=>	$signal
										);

					$n			=	1;	# GPS Array KEY -has to start at 1 vistumbler will error out if the first GPS point has a key of 0
					$sig		=	$aps[$nn]['sig'];
					$signals	=	explode("-", $sig);
	#				echo $sig."<BR>";
					$table_gps		=	$aps[$nn]['ssid'].'-'.$aps[$nn]['mac'].'-'.$aps[$nn]['sectype'].'-'.$aps[$nn]['radio'].'-'.$aps[$nn]['chan'].$gps_ext;
			#		echo $table_gps."\r\n";
					foreach($signals as $key=>$val)
					{
						$sig_exp = explode(",", $val);
						$gps_id	= $sig_exp[0];

						$sql1 = "SELECT * FROM `$db_st`.`$table_gps` WHERE `id` = '$gps_id'";
						$result1 = mysql_query($sql1, $conn) or die(mysql_error($conn));
						$gps_table = mysql_fetch_array($result1);
						$gps_array[$n]	=	array(
												"lat" => $gps_table['lat'],
												"long" => $gps_table['long'],
												"sats" => $gps_table['sats'],
												"hdp" => $gps_table['hdp'],
												"alt" => $gps_table['alt'],
												"geo" => $gps_table['geo'],
												"kmh" => $gps_table['kmh'],
												"mph" => $gps_table['mph'],
												"track" => $gps_table['track'],
												"date" => $gps_table['date'],
												"time" => $gps_table['time']
												);
						$n++;
						$signals[] = $n.",".$sig_exp[1];
					}
					$sig_new = implode("-", $signals);
					$aps[$nn]['sig'] = $sig_new;
					echo $nn."-".$n."==";
					unset($signals);
				}
			break;

			case "exp_all_db_vs1":
				$n	=	1;
				$nn	=	1; # AP Array key
				if($screen == "HTML"){echo '<table><tr class="style4"><th style="border-style: solid; border-width: 1px">Start of WiFi DB export to VS1</th></tr>';}
				$sql_		= "SELECT * FROM `$db`.`$wtable`";
				$result_	= mysql_query($sql_, $conn) or die(mysql_error($conn));
				while($ap_array = mysql_fetch_array($result_))
				{
					$manuf = database::manufactures($ap_array['mac']);
					switch($ap_array['sectype'])
						{
							case 1:
								$type = "#openStyleDead";
								$auth = "Open";
								$encry = "None";
								break;
							case 2:
								$type = "#wepStyleDead";
								$auth = "Open";
								$encry = "WEP";
								break;
							case 3:
								$type = "#secureStyleDead";
								$auth = "WPA-Personal";
								$encry = "TKIP-PSK";
								break;
						}
					switch($ap_array['radio'])
						{
							case "a":
								$radio="802.11a";
								break;
							case "b":
								$radio="802.11b";
								break;
							case "g":
								$radio="802.11g";
								break;
							case "n":
								$radio="802.11n";
								break;
							default:
								$radio="Unknown Radio";
								break;
						}
					$ssid_edit = html_entity_decode($ap_array['ssid']);
					list($ssid_t, $ssid_f, $ssid)  = make_ssid($ssid_edit);
					$table	=	$ssid_t.'-'.$ap_array['mac'].'-'.$ap_array['sectype'].'-'.$ap_array['radio'].'-'.$ap_array['chan'];
					$sql	=	"SELECT * FROM `$db_st`.`$table`";
					$result	=	mysql_query($sql, $conn) or die(mysql_error($conn));
					$rows	=	mysql_num_rows($result);

					$sql1 = "SELECT * FROM `$db_st`.`$table` WHERE `id` = '$rows'";
					$result1 = mysql_query($sql1, $conn) or die(mysql_error($conn));
					$newArray = mysql_fetch_array($result1);
#					echo $nn."<BR>";
					$otx	= $newArray["otx"];
					$btx	= $newArray["btx"];
					$nt		= $newArray['nt'];
					$label	= $newArray['label'];
					$signal	= $newArray['sig'];
					$aps[$nn]	= array(
										'id'		=>	$ap_array['id'],
										'ssid'		=>	$ap_array['ssid'],
										'mac'		=>	$ap_array['mac'],
										'sectype'	=>	$ap_array['sectype'],
										'r'			=>	$ap_array['radio'],
										'radio'		=>	$radio,
										'chan'		=>	$ap_array['chan'],
										'man'		=>	$manuf,
										'type'		=>	$type,
										'auth'		=>	$auth,
										'encry'		=>	$encry,
										'label'		=>	$label,
										'nt'		=>	$nt,
										'btx'		=>	$btx,
										'otx'		=>	$otx,
										'sig'		=>	$signal
										);
					$sig		=	$ap['sig'];
					$signals	=	explode("-", $sig);
	#				echo $sig."<BR>";
					$table_gps		=	$table.$gps_ext;
					echo $table_gps."\r\n";
					foreach($signals as $sign)
					{
						$sig_exp = explode(",", $sign);
						$gps_id	= $sig_exp[0];

						$sql1 = "SELECT * FROM `$db_st`.`$table_gps` WHERE `id` = '$gps_id'";
						$result1 = mysql_query($sql1, $conn) or die(mysql_error($conn));
						$gps_table = mysql_fetch_array($result1);
						$gps_array[$n]	=	array(
												"lat" => $gps_table['lat'],
												"long" => $gps_table['long'],
												"sats" => $gps_table['sats'],
												"hdp" => $gps_table['hdp'],
												"alt" => $gps_table['alt'],
												"geo" => $gps_table['geo'],
												"kmh" => $gps_table['kmh'],
												"mph" => $gps_table['mph'],
												"track" => $gps_table['track'],
												"date" => $gps_table['date'],
												"time" => $gps_table['time']
												);
						$n++;
						$signals[] = $n.",".$sig_exp[1];
					}
					$sig_new = implode("-", $signals);
					$aps[$nn]['sig'] = $sig_new;

					echo $nn."-".$n."==";
					unset($signals);
					$nn++;
				}
			break;
		}
		if(count($aps) > 1 && count($gps_array) > 1)
		{
			$date		=	date('Y-m-d_H-i-s');

			if(@$row != 0)
			{
				$file_ext	=	$date."_".$title.".vs1";
			}elseif(@$user != "")
			{
				$file_ext	=	$date."_".$user.".vs1";
			}else
			{
				$file_ext	=	$date."_FULL_DB.vs1";
			}
			$filename	=	$vs1_out.$file_ext;
		#	echo $filename."\r\n";
			// define initial write and appends
			fopen($filename, "w");
			$fileappend	=	fopen($filename, "a");

			$h1 = "#  Vistumbler VS1 - Detailed Export Version 3.0
# Created By: RanInt WiFiDB ".$ver['wifidb']."
# -------------------------------------------------
# GpsID|Latitude|Longitude|NumOfSatalites|HorizontalDilutionOfPrecision|Altitude(m)|HeightOfGeoidAboveWGS84Ellipsoid(m)|Speed(km/h)|Speed(MPH)|TrackAngle(Deg)|Date(UTC y-m-d)|Time(UTC h:m:s.ms)
# -------------------------------------------------
";
			fwrite($fileappend, $h1);

			foreach( $gps_array as $key=>$gps )
			{
				$lat	=	$gps['lat'];
				$long	=	$gps['long'];
				$sats	=	$gps['sats'];

				$hdp	= $gps['hdp'];
				if($hdp == ''){$hdp = 0;}

				$alt	= $gps['alt'];
				if($alt == ''){$alt = 0;}

				$geo	= $gps['geo'];
				if($geo == ''){$geo = "-0";}

				$kmh	= $gps['kmh'];
				if($kmh == ''){$kmh = 0;}

				$mph	= $gps['mph'];
				if($mph == ''){$mph = 0;}

				$track	= $gps['track'];
				if($track == ''){$track = 0;}

				$date	=	$gps['date'];
				$time	=	$gps['time'];
				$gpsd = $key."|".$lat."|".$long."|".$sats."|".$hdp."|".$alt."|".$geo."|".$kmh."|".$mph."|".$track."|".$date."|".$time."\r\n";
				fwrite($fileappend, $gpsd);
			}
			$ap_head = "# ---------------------------------------------------------------------------------------------------------------------------------------------------------
# SSID|BSSID|MANUFACTURER|Authetication|Encryption|Security Type|Radio Type|Channel|Basic Transfer Rates|Other Transfer Rates|Network Type|Label|GpsID,SIGNAL
# ---------------------------------------------------------------------------------------------------------------------------------------------------------
";
			fwrite($fileappend, $ap_head);
			foreach($aps as $ap)
			{
				$apd = $ap['ssid']."|".$ap['mac']."|".$ap['man']."|".$ap['auth']."|".$ap['encry']."|".$ap['sectype']."|".$ap['radio']."|".$ap['chan']."|".$ap['btx']."|".$ap['otx']."|".$ap['nt']."|".$ap['label']."|".$ap['sig']."\r\n";
				fwrite($fileappend, $apd);
			}
			fclose($fileappend);
			$end 	=	date("H:i:s");
			$GPSS	=	count($gps_array);
			$APSS	=	count($aps);
			$return = array(0=>1, 1=>$file_ext);
			if($screen == "HTML")
			{
				echo '<tr><td style="border-style: solid; border-width: 1px">Wrote # GPS Points: '.$GPSS.'</td></tr>';
				echo '<tr><td style="border-style: solid; border-width: 1px">Wrote # Access Points: '.$APSS.'</td></tr>';
				echo '<tr><td style="border-style: solid; border-width: 1px">Your Vistumbler VS1 file is ready,<BR>you can download it from <a class="links" href="'.$filename.'">Here</a></td><td></td></tr></table>';
			}
		}else
		{
			$return = array(0=>0, 1=>"Empty arrays. :/");
			if($screen == "HTML")
			{
				echo '<tr><td style="border-style: solid; border-width: 1px">Failed to export.</td><td></td></tr></table>';
			}
		}
		return $return;
	}

	function exp_newest_kml($named = 0, $verbose = 1)
	{
		require $GLOBALS['wdb_install']."/lib/config.inc.php";
		require $GLOBALS['wifidb_tools']."/daemon/config.inc.php";
		$date=date('Y-m-d_H-i-s');
		$start = microtime(true);
		$sql = "SELECT * FROM `$db`.`$wtable` ORDER BY `id` DESC LIMIT 1";
		$result = mysql_query($sql, $conn) or die(mysql_error($conn));
		$ap_array = mysql_fetch_array($result);

		$daemon_KMZ_folder = $GLOBALS['hosturl'].$GLOBALS['root']."/out/daemon/";
		$KML_folder = $GLOBALS['wifidb_install']."/out/daemon/";
		$filename = $KML_folder."newestAP.kml";
		$filename_label = $KML_folder."newestAP_label.kml";

		verbosed('Start export of Newest AP: '.$ap_array["ssid"]>"\n".$filename."\n".$filename_label, $verbose, "CLI");
		$NN = 0;

		// define initial write and appends
		verbosed('Wrote placer file.', $verbose, "CLI");

		$man 		= database::manufactures($ap_array['mac']);
		$id			= $ap_array['id'];

		#$ssid_ptb_	= $ap_array['ssid'];
		#$ssids_ptb	= str_split($ssid_ptb_,25);
		#$ssid		= smart_quotes($ssids_ptb[0]);
		list($ssid_S, $ssids, $ssidss ) = make_ssid($ap_array['ssid']);

		$mac		= $ap_array['mac'];
		$sectype	= $ap_array['sectype'];
		$radio		= $ap_array['radio'];
		$chan		= $ap_array['chan'];
		$table=$ssid_S.'-'.$ap_array['mac'].'-'.$ap_array['sectype'].'-'.$ap_array['radio'].'-'.$ap_array['chan'];
		$table_gps = $table.$gps_ext;
		$sql = "SELECT * FROM `$db_st`.`$table`";
		$result = mysql_query($sql, $conn) or die(mysql_error($conn));
		$rows = mysql_num_rows($result);
		$sql = "SELECT * FROM `$db_st`.`$table` WHERE `id`='1'";
		$result1 = mysql_query($sql, $conn) or die(mysql_error($conn));
		$newArray = mysql_fetch_array($result1);

		switch($ap_array['sectype'])
		{
			case 1:
				$type = "#openStyleDead";
				$auth = "Open";
				$encry = "None";
				break;
			case 2:
				$type = "#wepStyleDead";
				$auth = "Open";
				$encry = "WEP";
				break;
			case 3:
				$type = "#secureStyleDead";
				$auth = "WPA-Personal";
				$encry = "TKIP-PSK";
				break;
		}
		switch($ap_array['radio'])
		{
			case "a":
				$radio="802.11a";
				break;
			case "b":
				$radio="802.11b";
				break;
			case "g":
				$radio="802.11g";
				break;
			case "n":
				$radio="802.11n";
				break;
			default:
				$radio="Unknown Radio";
				break;
		}
		$otx = $newArray["otx"];
		$btx = $newArray["btx"];
		$nt = $newArray['nt'];
		$label = $newArray['label'];

		$sql6 = "SELECT * FROM `$db_st`.`$table_gps`";
		$result6 = mysql_query($sql6, $conn);
		$max = mysql_num_rows($result6);
		$zero = 1;
		$sql_1 = "SELECT * FROM `$db_st`.`$table_gps`";
		$result_1 = mysql_query($sql_1, $conn);
		verbosed('Looking for Valid GPS cords.', $verbose, "CLI");
		while($gps_table_first = mysql_fetch_array($result_1))
		{
			$lat_exp = explode(" ", $gps_table_first['lat']);

			$test = @$lat_exp[1]+0;

			if($test == "0.0000"){$zero = 1; continue;}

			$date_first = $gps_table_first["date"];
			$time_first = $gps_table_first["time"];
			$fa = $date_first." ".$time_first;
			$alt = $gps_table_first['alt'];
			$lat =& database::convert_dm_dd($gps_table_first['lat']);
			$long =& database::convert_dm_dd($gps_table_first['long']);
			$zero = 0;
			break;
		}
		if($zero == 1)
		{
			verbosed('Didnt Find any, not writing AP to file.', $verbose, "CLI");
		}else
		{
			verbosed('Found some, writing KML File.', $verbose, "CLI");
			$sql_2 = "SELECT * FROM `$db_st`.`$table_gps` WHERE `id`='$max'";
			$result_2 = mysql_query($sql_2, $conn);
			$gps_table_last = mysql_fetch_array($result_2);
			$date_last = $gps_table_last["date"];
			$time_last = $gps_table_last["time"];
			$la = $date_last." ".$time_last;

			$Odata = "<Placemark id=\"".$mac."\">\r\n	<description><![CDATA[<b>SSID: </b>".$ssidss."<br /><b>Mac Address: </b>".$mac."<br /><b>Network Type: </b>".$nt."<br /><b>Radio Type: </b>".$radio."<br /><b>Channel: </b>".$ap_array['chan']."<br /><b>Authentication: </b>".$auth."<br /><b>Encryption: </b>".$encry."<br /><b>Basic Transfer Rates: </b>".$btx."<br /><b>Other Transfer Rates: </b>".$otx."<br /><b>First Active: </b>".$fa."<br /><b>Last Updated: </b>".$la."<br /><b>Latitude: </b>".$lat."<br /><b>Longitude: </b>".$long."<br /><b>Manufacturer: </b>".$man."<br /><a href=\"".$hosturl."/".$root."/opt/fetch.php?id=".$id."\">WiFiDB Link</a>]]></description>\r\n	<styleUrl>".$type."</styleUrl>\r\n<Point id=\"".$mac."_GPS\">\r\n<coordinates>".$long.",".$lat.",".$alt."</coordinates>\r\n</Point>\r\n</Placemark>\r\n";

			$Ddata  =  "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n<kml xmlns=\"$KML_SOURCE_URL\"><!--exp_all_db_kml-->\r\n<Document>\r\n<name>RanInt WifiDB KML Newset AP</name>\r\n";
			$Ddata .= "<Style id=\"openStyleDead\">\r\n<IconStyle>\r\n<scale>0.5</scale>\r\n<Icon>\r\n<href>".$open_loc."</href>\r\n</Icon>\r\n</IconStyle>\r\n	</Style>\r\n";
			$Ddata .= "<Style id=\"wepStyleDead\">\r\n<IconStyle>\r\n<scale>0.5</scale>\r\n<Icon>\r\n<href>".$WEP_loc."</href>\r\n</Icon>\r\n</IconStyle>\r\n</Style>\r\n";
			$Ddata .= "<Style id=\"secureStyleDead\">\r\n<IconStyle>\r\n<scale>0.5</scale>\r\n<Icon>\r\n<href>".$WPA_loc."</href>\r\n</Icon>\r\n</IconStyle>\r\n</Style>\r\n";
			$Ddata .= '<Style id="Location"><LineStyle><color>7f0000ff</color><width>4</width></LineStyle></Style>';
			$Ddata .= "\r\n".$Odata."\r\n";
			$Ddata = $Ddata."</Document>\r\n</kml>";


			$filewrite	=	fopen($filename, "w");
			if($filewrite)
			{
				$fileappend	=	fopen($filename, "a");
				fwrite($fileappend, $Ddata);
				fclose($fileappend);
			}else
			{
				verbosed('Could not write Placer file ('.$filename.'), check permissions.', $verbose, "CLI");
			}
			#####################################
			$Odata = "<Placemark id=\"".$mac."_Label\">\r\n	<name>".$ssidss."</name>\r\n	<description><![CDATA[<b>SSID: </b>".$ssidss."<br /><b>Mac Address: </b>".$mac."<br /><b>Network Type: </b>".$nt."<br /><b>Radio Type: </b>".$radio."<br /><b>Channel: </b>".$ap_array['chan']."<br /><b>Authentication: </b>".$auth."<br /><b>Encryption: </b>".$encry."<br /><b>Basic Transfer Rates: </b>".$btx."<br /><b>Other Transfer Rates: </b>".$otx."<br /><b>First Active: </b>".$fa."<br /><b>Last Updated: </b>".$la."<br /><b>Latitude: </b>".$lat."<br /><b>Longitude: </b>".$long."<br /><b>Manufacturer: </b>".$man."<br /><a href=\"".$hosturl."/".$root."/opt/fetch.php?id=".$id."\">WiFiDB Link</a>]]></description>\r\n	<styleUrl>".$type."</styleUrl>\r\n<Point id=\"".$mac."_GPS\">\r\n<coordinates>".$long.",".$lat.",".$alt."</coordinates>\r\n</Point>\r\n</Placemark>\r\n";

			$Ddata  =  "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n<kml xmlns=\"$KML_SOURCE_URL\"><!--exp_all_db_kml-->\r\n<Document>\r\n<name>RanInt WifiDB KML Newset AP</name>\r\n";
			$Ddata .= "<Style id=\"openStyleDead\">\r\n<IconStyle>\r\n<scale>0.5</scale>\r\n<Icon>\r\n<href>".$open_loc."</href>\r\n</Icon>\r\n</IconStyle>\r\n	</Style>\r\n";
			$Ddata .= "<Style id=\"wepStyleDead\">\r\n<IconStyle>\r\n<scale>0.5</scale>\r\n<Icon>\r\n<href>".$WEP_loc."</href>\r\n</Icon>\r\n</IconStyle>\r\n</Style>\r\n";
			$Ddata .= "<Style id=\"secureStyleDead\">\r\n<IconStyle>\r\n<scale>0.5</scale>\r\n<Icon>\r\n<href>".$WPA_loc."</href>\r\n</Icon>\r\n</IconStyle>\r\n</Style>\r\n";
			$Ddata .= '<Style id="Location"><LineStyle><color>7f0000ff</color><width>4</width></LineStyle></Style>';
			$Ddata .= "\r\n".$Odata."\r\n";
			$Ddata = $Ddata."</Document>\r\n</kml>";

			$filewrite_l = fopen($filename_label, "w");
			if($filewrite_l)
			{
				$fileappend_label = fopen($filename_label, "a");
				fwrite($fileappend_label, $Ddata);
				fclose($fileappend_label);
			}else
			{
				verbosed('Could not write Placer file ('.$filename_label.'), check permissions.', $verbose, "CLI");
			}
			recurse_chown_chgrp($KML_folder, $GLOBALS['WiFiDB_LNZ_User'], $GLOBALS['apache_grp']);
			recurse_chmod($KML_folder, 0755);
			verbosed('File has been writen and is ready.', $verbose, "CLI");
		}

		$end = microtime(true);
	#	if ($GLOBALS["bench"]  == 1)
	#	{
	#		echo "Time is [Unix Epoc]<BR>";
	#		echo "Start Time: ".$start."<BR>";
	#		echo "  End Time: ".$end."<BR>";
	#	}
	}
}#END DATABASE CLASS
?>