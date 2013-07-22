<?php

function oauth2callback($page,$handler) {
 global $CONFIG;
 forward("{$CONFIG->wwwroot}{$_GET['state']}?code={$_GET['code']}");
 return true;
}

//REST service
function listFolder($path,$service) {

  $q = "'";
  $count = count($path);
  $folderId = $path[$count-1];
  $file = $service->files->get($folderId);
  $result = 'Folder: '.$file->getTitle();
  if ($count >= 2) {
    $newPath = $path;
    array_pop($newPath);
    $result .= ' <a href="javascript:lookup('.$q.implode(',',$newPath).$q.')">('.elgg_echo("gdrive:up").')</a>';
  }
  $result .='<br/>';

  $param = array("q" => "'".$folderId."' in parents", "fields" => "items(title,mimeType,id,alternateLink)");

  //$user = elgg_get_logged_in_user_entity();
  //if ($user) $param['quotaUser'] = $user->username;

  $files = $service->files->listFiles($param)->items;
  $result .= '<ol>';

  foreach ($files as $i) {

   $href = '"'.$i->alternateLink.'" target="_blank"';
   $title = $i->title;
   switch ($i->mimeType) {
    case 'application/vnd.google-apps.folder': 
      $newPath = $path;
      array_push($newPath,$i->id);
      $href='"javascript:lookup('.$q.implode(",",$newPath).$q.')"';
      $class = 'img-collection'; 
      break;
    case 'application/pdf': $class = 'img-pdf'; break;
    case 'application/msword': $class = 'img-word'; break;
    case 'application/vnd.ms-excel': $class = 'img-excel'; break;
    case 'text/plain': $class = 'img-text'; break;
    case 'application/vnd.google-apps.script': $class = 'img-script'; break;
    case 'application/vnd.google-apps.document': $class = 'img-document'; break;
    case 'application/vnd.google-apps.spreadsheet': $class = 'img-spreadsheet'; break;
    case 'application/vnd.google-apps.drawing': $class = 'img-drawing'; break;
    case 'application/vnd.google-apps.presentation': $class = 'img-presentation'; break;
    case 'application/vnd.google-apps.form': $class = 'img-form'; break;
    default: $class = 'img-generic';
   }
   $result .='<li><div><a href='.$href.'><span class="gdrive-inline doclist-icon '.$class.'"></span><span class="gdrive-inline">&nbsp;</span><span class="gdrive-inline">'.$title.'</span></a></div></li>';
  }
  $result .= '</ol>';

  return $result;
}

function gdrive_list($path,$cached=false) {

 try {
  global $CONFIG;
  $scopes = array('https://www.googleapis.com/auth/drive.readonly.metadata');
  $client = new apiClient();
  $client->setUseObjects(true);
  $client->setAuthClass('apiOAuth2');
  $client->setApplicationName("Google Docs Application");  
  $client->setClientId(elgg_get_plugin_setting('clientid', $plugin_name = 'gdrive'));
  $client->setClientSecret(elgg_get_plugin_setting('clientsecret', $plugin_name = 'gdrive'));
  $client->setRedirectUri($CONFIG->site->url.'oauth2callback');
  $client->setState(substr($_SERVER["REQUEST_URI"],1));

  $service = new apiDriveService($client);

  if (isset($_GET['code'])) {
   $client->authenticate();
   $_SESSION['token'] = $client->getAccessToken();
  }

  if (isset($_SESSION['token'])) {
   $client->setAccessToken($_SESSION['token']);
  }

  if ($client->getAccessToken()) {
   $path = explode(',',$path);
   $result = listFolder($path,$service);
   //Cache for 120 seconds
   if ($cached) header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 120));
   $_SESSION['token'] = $client->getAccessToken();
  } else {
   $authUrl = $client->createAuthUrl();
   $authUrl = preg_replace("/10.24.5.144/", "elgg-modern.example.com",$authUrl);
   $authUrl = preg_replace("/state=elgg%2F/", "state=", $authUrl);
   $result = "<a class='login' href='$authUrl'>".elgg_echo('gdrive:signon')."</a>";
  }
 }
 catch (Exception $e) {
  $result = "<a class='login' href='$authUrl'>".elgg_echo('gdrive:signon')."</a><br/>".$e->getMessage();
 }
 return $result;

}

function gdrive_list_cached($path) {
 return gdrive_list($path,$cached=true);
}

function search_gdrive_hook($hook, $type, $value, $params) {

 $entity = new ElggObject();
 $entity->owner_guid = '123456'; //use_magic_to_match_to_a_real_user();
 $entity->setVolatileData('search_matched_title', '3rd Party Integration: '.$params['query']);
 $entity->setVolatileData('search_matched_description', 'Searching is fun!');
                
 $entities = array($entity);

 return array(
   'count' => count($entities),
   'entities' => $entities
 );

}

function search_custom_types_gdrive_hook($hook, $type, $value, $params) {
        $value[] = 'gdrive';
        return $value;
}
