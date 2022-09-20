<?
public static function PostPhotoToADForUser($userID, $servID = 1){

        global $DB;

	$fileLogNameMAIL = $_SERVER["DOCUMENT_ROOT"]."/bitrix/php_interface/include/lyalyaev/logphototoad.txt";
	$fileLog = fopen($fileLogNameMAIL, "a");

        $rsUser = \CUser::GetByID($userID);
        $arUser = $rsUser->Fetch();

        $fileInfo = \CFile::GetByID($arUser["PERSONAL_PHOTO"]);
        $fileArr = $fileInfo->Fetch();

        $fileSize = $fileArr["FILE_SIZE"];
        $fileHeight = $fileArr["HEIGHT"];
        $fileWidth = $fileArr["WIDTH"];

        $filePath = $_SERVER['DOCUMENT_ROOT'].\CFile::GetPath($arUser['PERSONAL_PHOTO']);
        
        $quality = 75;

        while ($fileSize > 100000){

            echo ' Processing image file...<br><br>';
            $info = getimagesize($filePath);
            if ($info['mime'] == 'image/jpeg')
                $image = imagecreatefromjpeg($filePath);
            elseif ($info['mime'] == 'image/gif')
                $image = imagecreatefromgif($filePath);
            elseif ($info['mime'] == 'image/png')
                $image = imagecreatefrompng($filePath);

            if ($fileHeight > 600){
                $scale = 600 / $fileHeight;
                $newWidth = round($fileWidth * $scale, 0, PHP_ROUND_HALF_DOWN);
                $image = imagescale($image, $newWidth);
                // echo $scale.'   '.$newWidth.'<br><br>';
                $res = imagejpeg($image, $filePath, 80);
                // var_dump($res);
            }
            else{
                imagejpeg($image, $filePath, $quality);
            }

            \CFile::CleanCache($fileArr["ID"]);

            list($width, $height, $type, $attr) = getimagesize($filePath);
            $size = filesize($filePath);
            // echo $size.' '.$height.' '.$width.'<br><br>';

            // Обновить поля в b_file
            $DB->Query("UPDATE b_file SET FILE_SIZE='".$size."', HEIGHT='".$height."', WIDTH='".$width."' WHERE ID=".$fileArr["ID"]);

            $fileInfo = \CFile::GetByID($arUser["PERSONAL_PHOTO"]);
            $fileArr = $fileInfo->Fetch();

            $fileSize = $fileArr["FILE_SIZE"];
            $fileHeight = $fileArr["HEIGHT"];
            $fileWidth = $fileArr["WIDTH"];

            echo 'Size: '.$fileSize.' Height: '.$fileHeight.' Width: '.$fileWidth.'<br><br>';

            $filePath = $_SERVER['DOCUMENT_ROOT'].\CFile::GetPath($arUser['PERSONAL_PHOTO']);

        }

        \CFile::CleanCache($fileArr["ID"]);
        


        $ld = \CLdapServer::GetByID($servID);
        $arFields = $ld->ExtractFields("str_");
        $ar = Array(
			"SERVER"		=>	$arFields['SERVER'],
			"PORT"			=>	$arFields['PORT'],
			"ADMIN_LOGIN"	=>	$arFields['ADMIN_LOGIN'],
			"ADMIN_PASSWORD"=>	$arFields['ADMIN_PASSWORD'],
			"BASE_DN"		=>	$arFields['BASE_DN'],
			"GROUP_FILTER"	=>	$arFields['GROUP_FILTER'],
			"GROUP_ID_ATTR"	=>	$arFields['GROUP_ID_ATTR'],
			"GROUP_NAME_ATTR"=>	$arFields['GROUP_NAME_ATTR'],
			"GROUP_MEMBERS_ATTR"=>	$arFields['GROUP_MEMBERS_ATTR'],
			"CONVERT_UTF8"	=>	$arFields['CONVERT_UTF8'],
			"USER_FILTER"	=>	$arFields['USER_FILTER'],
			"USER_GROUP_ATTR"=>	$arFields['USER_GROUP_ATTR'],
			"USER_GROUP_ACCESSORY"=>	$arFields['USER_GROUP_ACCESSORY'],
			"USER_DEPARTMENT_ATTR"	=>	$arFields['USER_DEPARTMENT_ATTR'],
			"USER_MANAGER_ATTR"	=>	$arFields['USER_MANAGER_ATTR'],
			"MAX_PAGE_SIZE"	=>	$arFields['MAX_PAGE_SIZE'],
			"LDAP_OPT_TIMELIMIT"	=>	$arFields['LDAP_OPT_TIMELIMIT'],
			"LDAP_OPT_TIMEOUT"	=>	$arFields['LDAP_OPT_TIMEOUT'],
			"LDAP_OPT_NETWORK_TIMEOUT"	=>	$arFields['LDAP_OPT_NETWORK_TIMEOUT'],
			"CONNECTION_TYPE" => $arFields['CONNECTION_TYPE'],
		);

        // var_dump($ar);
        // echo '<br><br>';
        
        $rsUser = \CUser::GetByID($userID);
        $arUser = $rsUser->Fetch();
        
        if ($arUser["PERSONAL_PHOTO"] == NULL){
            echo "У пользователя {$dn} с ID ".$userID." нет фотографии в Битрикс";
            return;
        }

        $fileInfo = \CFile::GetByID($arUser["PERSONAL_PHOTO"]);
        $fileArr = $fileInfo->Fetch();
        $filePath = $_SERVER['DOCUMENT_ROOT'].\CFile::GetPath($arUser['PERSONAL_PHOTO']);
        $fileData = file_get_contents($filePath);

        ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

        $ldapBase = $arFields['BASE_DN'];

        $ds = ldap_connect('ldap://'.$ar['SERVER'].':'.$ar['PORT']);
        if (!$ds) {die('Cannot Connect to LDAP server');}

        $ldapBind = ldap_bind($ds, $ar['ADMIN_LOGIN'], $ar['ADMIN_PASSWORD']);
        if (!$ldapBind) {die('Cannot Bind to LDAP server');}
    
        $username = $arUser["LOGIN"];
        $sr = ldap_search($ds, $ldapBase, "(samaccountname=$username)");
        $ent = ldap_get_entries($ds, $sr);
        $dn = $ent[0]["distinguishedname"][0];
    
        $changes = array(
            'thumbnailPhoto' => [$fileData],
	        'jpegPhoto' => [$fileData]
        );
    
        if (!ldap_modify($ds, $dn, $changes)){
	        $enum = ldap_errno($ds);
            $msg = ldap_err2str($enum);
            echo "Не удалось обновить фотографию для {$dn}. {$msg}".'<br />'.PHP_EOL;
			fwrite($fileLog, "Не удалось обновить фотографию для {$dn}. {$msg}".'<br />'.PHP_EOL); 
        }
        else{               
            echo "Обновлена фотография для пользователя {$dn} с ID ".$userID;
			fwrite($fileLog, "Обновлена фотография для пользователя {$dn} с ID ".$userID.PHP_EOL); 
        }
		fclose($fileLog);
}
