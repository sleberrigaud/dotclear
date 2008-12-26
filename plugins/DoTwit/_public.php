<?php
class doTwit
{
	private static $clean = 0;
	private static $path_cache = 'cache/';
	
	public static function dotwitWidget(&$w) {
    	global $core;
		$cache_file = self::$path_cache.'dotwit_'.md5($w->idTwitter.$w->timeline_friends);
    
    	//Affichage page d'accueil seulement
		if ($w->homeonly && $core->url->type != 'default') {
			return;
		}
		
    try{
		$host = 'twitter.com';
		if( $w->timeline_friends  ) {
			$url = 'http://twitter.com/statuses/friends_timeline/'.$w->idTwitter.'.xml';
			//$path = '/statuses/friends_timeline/'.$w->idTwitter.'.xml';
		}else{
			$url = 'http://twitter.com/statuses/user_timeline/'.$w->idTwitter.'.xml';
			//$path = '/statuses/user_timeline/'.$w->idTwitter.'.xml';
		}		
		
		if( @filemtime($cache_file) < time() - 60*15 ) 
		{			
			$oHttp = new netHttp('');
			$oHttp->readURL($url,$ssl,$host,$port,$path,$user,$pass);
			$oHttp->setHost($host,$port);
			//$oHttp->useSSL($ssl);
			
			$user = $w->idTwitter;
			$pass = $w->pwdTwitter;
			
			$oHttp->setAuthorization($user,$pass);
			$oHttp->get($path);
			$xml_file = $oHttp->getContent();
			
			if ( $xml = @simplexml_load_string($xml_file) )
			{
				if ( $xml->error == '' && $fp = @fopen($cache_file, 'wb'))
				{
					fwrite($fp, $xml_file);
					fclose($fp);
				}
			} else {
				$xml = @simplexml_load_string(@file_get_contents($cache_file));
			}
		} elseif ( file_exists($cache_file) ) {
			$xml = @simplexml_load_string(@file_get_contents($cache_file));
		}
		
	}catch (Exception $e){
		  
	}
		$res =
		'<div id="doTwit">'.
		($w->title ? '<h2><a href="http://twitter.com/'.$w->idTwiter.'">'.$w->title.'</a></h2>' : '').
		'<ul>';
		
		$nb = 0;
		
		if( count($xml->status) == 0 )
		{
			$res .= 'Données indisponible sur Twitter !';
			return $res;
		}
		
		foreach($xml->status as $elm) {
			
			$twi['id'][$nb] = (int) $elm->id;
			$twi['desc'][$nb] = eregi_replace("(http|mailto|news|ftp|https)://(([-Ã©a-z0-9\/\.\?_=#@:~])*)", "<a href=\"\\1://\\2\" target=\"_blank\">\\1://\\2</a>",$elm->text);
			$twi['screen_name'][$nb] = (string) $elm->user->screen_name;
			$twi['name'][$nb] = (string) $elm->user->name;
			$twi['location'][$nb] = (string) $elm->user->location;
			
			if( $w->display_profil_image ) $twi['img'][$nb] = eregi_replace("_normal.", "_mini.",$elm->user->profile_image_url);
			if( $w->display_timeout) {
				$twi['time'][$nb] = ((int) strtotime($elm->created_at));
				$twi['date'][$nb] = date('d F Y, H:i', $twi['time'][$nb]);
				$twi['desc'][$nb] .= ' <a class="date" href="http://twitter.com/'.$twi['screen_name'][$nb].'/statuses/'.$twi['id'][$nb].'" target="_blank"> '. $twi['date'][$nb].'</a>';
				
			}
						
			$nb++;
			
			if($nb >= $w->limit)break;
		}
		
			
		for ($i=0;$i<$nb;$i++) {

			if( $w->display_profil_image && $twi['img'][$i] != '' ) {
				$res .= '<li>';
				$res .= '<span class="twitter-username">';
				$res .= '<a href="http://twitter.com/'.$twi['screen_name'][$i].'" target="_blank" title="'.$twi['name'][$i].' ('.$twi['location'][$i].')">';
				$res .= '<img src="'.$twi['img'][$i].'" alt="'.$twi['name'][$i].'" />';
				$res .= $twi['screen_name'][$i].'</a>';
				$res .= '</span>';
				$res .= '<span class="twitter-content">';
				$res .= $twi['desc'][$i];
				$res .= '</span>';
				$res .= '</li>';
			}else {
				$res .= '<li>';
				$res .= '<span class="twitter-username">';
				$res .= '<a href="http://twitter.com/'.$twi['screen_name'][$i].'" target="_blank" title="'.$twi['name'][$i].' ('.$twi['location'][$i].')">';
				$res .= $twi['screen_name'][$i];
				$res .= '</a></span><span class="twitter-content">'.$twi['desc'][$i].'</span>';
				$res .= '</li>';				
			}
			
		}
		
		$res .= '</ul></div>';
		
		self::clean_cache();
		
		return $res;
  }
  
  private static function clean_cache() {
		
		if( self::$clean == 1) return true;
		
		if( $dir = @opendir(self::$path_cache) ) {

			while ($f = @readdir($dir)) {
				if(is_file(self::$path_cache.$f) && substr($f,0,7) == 'dotwit_' ) {
					if( filemtime(self::$path_cache.$f) < time() - 60 * 15 ) {
						@unlink(self::$path_cache.$f);
					}
				}
			}
			
			@closedir($dir);
		}
		
		self::$clean = 1;
		return true;
  }
  
}
?>
