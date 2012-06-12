<?php
/*
ini_set('display_errors', 1);
ini_set('log_errors', 1);
error_reporting(E_ALL);
//*/

//Includes all necessary files for oAuth
$dir = dirname(__FILE__);

include_once("$dir/epioauth/EpiCurl.php");
include_once("$dir/epioauth/EpiOAuth.php");
include_once("$dir/epioauth/EpiTwitter.php");
include_once("$dir/linkedinoauth.php");
include_once("$dir/facebook.class.php");
include_once("$dir/openid.php");

/**
 * Suportat:
 * 				OAuth o similar: twitter, facebook, linkedin
 * 				OpenId: google
 *
 * identities:
	 *    Google : https://www.google.com/accounts/o8/id
	 *    Google profile : http://www.google.com/profiles/~YOURUSERNAME
	 *    Yahoo : https://me.yahoo.com
	 *    AOL : https://www.aol.com
	 *    WordPress : http://YOURBLOG.wordpress.com
	 *    LiveJournal : http://www.livejournal.com/openid/server.bml
 * */
class SocialAuth {
	public $host;
	public $callback_url;
	public $provider;
	public $original_provider;
	public $last_error = '';
	//datos que se recopilan
	public $user_data = array('username' => null, 'name' => null, 'email' => null, 'profile_image_url' => null, 'website' => null, 'about' => null, 'location'=>null,'twitter'=>null,'facebook'=>null,'google'=>null,'identica'=>null,'linkedin'=>null);
	//datos que se importaran (si se puede) a la tabla 'user'
	public $import_user_data = array('name', 'about', 'location', 'twitter', 'facebook', 'google', 'identica', 'linkedin');
	public $tokens = array('twitter'=>array('token'=>'','secret'=>''), 'facebook'=>array('token'=>'','secret'=>''), 'linkedin'=>array('token'=>'','secret'=>''), 'openid'=>array('token'=>'','secret'=>'')); //secretos generados en el oauth

	protected $twitter_id;
	protected $twitter_secret;
	protected $facebook_id;
	protected $facebook_secret;
	protected $linkedin_id;
	protected $linkedin_secret;
	protected $openid_secret;

	protected $openid_server;
	public $openid_public_servers = array(
		"Google" => "https://www.google.com/accounts/o8/id",
		"Yahoo" => "https://me.yahoo.com",
		"myOpenid" => "http://myopenid.com/",
		"AOL" => "https://www.aol.com",
		"Ubuntu" => "https://login.ubuntu.com",
		"LiveJournal" => "http://www.livejournal.com/openid/server.bml",
	 );

	/**
	 * @param $provider : 'twitter', 'facebook', 'linkedin', 'any_openid_server'
	 * */
	function __construct($provider='') {
		$this->host = SITE_URL;
		$this->callback_url = SITE_URL . '/user/oauth?return=' . $provider;

		$this->twitter_id = OAUTH_TWITTER_ID;
		$this->twitter_secret = OAUTH_TWITTER_SECRET;
		$this->facebook_id = OAUTH_FACEBOOK_ID;
		$this->facebook_secret = OAUTH_FACEBOOK_SECRET;
		$this->linkedin_id = OAUTH_LINKEDIN_ID;
		$this->linkedin_secret = OAUTH_LINKEDIN_SECRET;
		$this->openid_secret = OAUTH_OPENID_SECRET;

		if(in_array($provider,array('twitter', 'facebook', 'linkedin'))) {
			$this->provider = $provider;
			$this->original_provider = $provider;
		}
		else {
			//OpenId providers
			$this->openid_server = $this->openid_public_servers[$provider];
			if(empty($this->openid_server))	$this->openid_server = $provider;
			$this->original_provider = $provider;
			$this->provider = 'openid';
		}
	}

	/**
	 * conecta con el servicio de oauth, redirecciona a la pagina para la autentificacion
	 * */
	public function authenticate() {
		switch ($this->provider) {
			case 'twitter':
				return $this->authenticateTwitter();
				break;
			case 'facebook':
				return $this->authenticateFacebook();
				break;
			case 'linkedin':
				return $this->authenticateLinkedin();
				break;
			case 'openid':
				return $this->authenticateOpenid();
				break;
			default:
				$this->last_error = 'oauth-unknown-provider';
				return false;
		}
		return true;
	}

	/**
	 * Autentica con twitter, redirige a Twitter para que el usuario acepte
	 * */
	public function authenticateOpenid() {
		try {
			$openid = new \LightOpenID($this->host);
			$openid->identity = $this->openid_server;
			//standard data provided
			$openid->required = array(
				'namePerson/friendly',
				'namePerson',
				'namePerson/first',
				'namePerson/last',
				'contact/email',
				'contact/country/home',
				//'pref/language'
			);
			$openid->returnUrl = $this->callback_url;
			$url = $openid->authUrl();
			header("Location: $url");
			exit;
		}
		catch(Exception $e){
			$this->last_error = $e->getMessage()." 1/ ".get_class($e);
			return false;
		}
		return true;
	}

	/**
	 * Autentica con twitter, redirige a Twitter para que el usuario acepte
	 * */
	public function authenticateTwitter() {
		try {
			$twitterObj = new \EpiTwitter($this->twitter_id, $this->twitter_secret);
			$url = $twitterObj->getAuthenticateUrl(null,array('oauth_callback' => $this->callback_url));
			header("Location: $url");
			exit;
		}
		catch(Exception $e){
			$this->last_error = $e->getMessage()." 1/ ".get_class($e);
			return false;
		}
		return true;
	}

	/**
	 * Autentica con Facebook, redirige a Facebook para que el usuario acepte
	 * */
	public function authenticateFacebook() {
		try {
			$obj = new \Facebook($this->facebook_id, $this->facebook_secret,$this->callback_url);
			$url = $obj->start(true,"email"); //Permisos que se solicitan, por ejemplo: user_about_me,email,offline_access
			header("Location: $url");
			exit;
		}
		catch(Exception $e){
			$this->last_error = $e->getMessage()." 1/ ".get_class($e);
			return false;
		}
		return true;
	}

	/**
	 * Autentica con LinkedIn, redirige a LinkedIn para que el usuario acepte
	 * */
	public function authenticateLinkedin() {
		try {
			//do the authentication:
			//get public tokens
			$to = new \LinkedInOAuth($this->linkedin_id, $this->linkedin_secret);
			// This call can be unreliable for some providers if their servers are under a heavy load, so
			// retry it with an increasing amount of back-off if there's a problem.
			$maxretrycount = 1;
			$retrycount = 0;
			while ($retrycount<$maxretrycount) {
				$tok = $to->getRequestToken($this->callback_url);
				if (isset($tok['oauth_token']) && isset($tok['oauth_token_secret']))
					break;
				$retrycount += 1;
				sleep($retrycount*5);
			}

			if(empty($tok['oauth_token']) || empty($tok['oauth_token_secret'])) {
				$this->last_error = "oauth-token-request-error";
				return false;
			}

			//en linkedin hay que guardar los token de autentificacion para usarlos
			//despues para obtener los tokens de acceso,
			$_SESSION['linkedin_token'] = $tok;

			//set URL
			$url = $to->getAuthorizeURL($tok['oauth_token']);

			header("Location: $url");
			exit;
		}
		catch(Exception $e){
			$this->last_error = $e->getMessage()." 1/ ".get_class($e);
			return false;
		}
		return true;
	}

	/**
	 * obtención de datos en los proveedores de oauth mediante login con los tokens que se obtienen al retornar del authenticate
	 * */
	public function login() {
		switch ($this->provider) {
			case 'twitter':
				return $this->loginTwitter();
				break;
			case 'facebook':
				return $this->loginFacebook();
				break;
			case 'linkedin':
				return $this->loginLinkedin();
				break;
			case 'openid':
				return $this->loginOpenid();
				break;
		}
	}

	/**
	 * Login con facebook
	 * */
	public function loginFacebook() {
		try {
			$obj = new \Facebook($this->facebook_id, $this->facebook_secret,$this->callback_url);
			$token = $obj->callback();

			if(!$token) {
				$this->last_error = "oauth-facebook-access-denied";
				return false;
			}
			$this->tokens['facebook']['token'] = $token;

			//print_R($token);
			//echo 'facebook_access_token: ' . $token;

			//guardar los tokens en la base datos si se quieren usar mas adelante!
			//con los tokens podems acceder a la info del user, hay que recrear el objecto con los tokens privados
			$res = json_decode($obj->makeRequest($token,"https://graph.facebook.com/me","GET"));
			if($res->error) {
				$this->last_error = $res->error->message;
				return false;
			}

			//ver todos los datos disponibles:
			//print_r($res);die;

			$this->user_data['name'] = $res->name;
			if($res->username) $this->user_data['username'] = $res->username;
			if($res->email) $this->user_data['email'] = $res->email;
			if($res->website) $this->user_data['website'] = $res->website; //ojo, pueden ser varias lineas con varias webs
			if($res->about) $this->user_data['about'] = $res->about;
			if($res->location->name) $this->user_data['location'] = $res->location->name;
			if($res->id) $this->user_data['profile_image_url'] = "http://graph.facebook.com/".$res->id."/picture?type=large";
			//facebook link
			if($res->link) $this->user_data['facebook'] = $res->link;

			return true;
		}
		catch(Exception $e){
			$this->last_error =  $e->getMessage()." 1/ ".get_class($e);
			return false;
		}
		return true;
	}

	/**
	 * Login con linkedin
	 * */
	public function loginLinkedin() {
		try {

			//recuperar tokens de autentificacion
			$tok = $_SESSION['linkedin_token'];
			$to = new \LinkedInOAuth($this->linkedin_id, $this->linkedin_secret,$tok['oauth_token'],$tok['oauth_token_secret']);
			//obtenemos los tokens de acceso
			$tok = $to->getAccessToken($_GET['oauth_verifier']);
			//borramos los tokens de autentificacion de la session, ya no nos sirven
			//unset($_SESSION['linkedin_token']);

			if(empty($tok['oauth_token']) || empty($tok['oauth_token_secret'])) {
				$this->last_error = "oauth-linkedin-access-denied";
				return false;
			}

			//guardar los tokens en la base datos si se quieren usar mas adelante!
			//con los tokens podems acceder a la info del user, hay que recrear el objecto con los tokens privados
			$this->tokens['linkedin']['token'] = $tok['oauth_token'];
			$this->tokens['linkedin']['secret'] = $tok['oauth_token_secret'];


			$profile_result = $to->oAuthRequest('http://api.linkedin.com/v1/people/~:(id,first-name,last-name,summary,public-profile-url,picture-url,headline,interests,twitter-accounts,member-url-resources:(url),positions:(company),location:(name))');
			$profile_data = simplexml_load_string($profile_result);

			$this->user_data['name'] = trim($profile_data->{"first-name"} . " " . $profile_data->{"last-name"});
			if($profile_data->{"public-profile-url"}) {
				//linkedin link
				$this->user_data['linkedin'] = current($profile_data->{"public-profile-url"});
				//username from url
				$this->user_data['username'] = basename($this->user_data['linkedin']);
			}


			if($profile_data->{"member-url-resources"}->{"member-url"}) {
				$urls = array();
				foreach($profile_data->{"member-url-resources"}->{"member-url"} as $url) {
					$urls[] = current($url->url);
				}
				$this->user_data['website'] .= implode("\n",$urls);
			}
			if($profile_data->headline) $this->user_data['about'] = current($profile_data->headline);
			if($profile_data->location->name) $this->user_data['location'] = current($profile_data->location->name);
			if($profile_data->{"picture-url"}) $this->user_data['profile_image_url'] = current($profile_data->{"picture-url"});
			//si el usuario tiene especificada su cuenta twitter
			if($profile_data->{"twitter-accounts"}->{"twitter-account"}) $this->user_data['twitter'] = 'http://twitter.com/' . current($profile_data->{"twitter-accounts"}->{"twitter-account"}->{"provider-account-name"});

			//ver todos los datos disponibles:
			//print_r($profile_data);print_r($this->user_data);die;


			return true;
		}
		catch(Exception $e){
			$this->last_error =  $e->getMessage()." 1/ ".get_class($e);
			return false;
		}
		return true;
	}

	/**
	 * Login con twitter
	 * */
	public function loginTwitter() {

		if($_GET['denied']) {
			//comprovar si el retorno contiene la variable de denegación
			$this->last_error = "oauth-twitter-access-denied";
			return false;
		}
		try {
			$twitterObj = new \EpiTwitter($this->twitter_id, $this->twitter_secret);
			$twitterObj->setToken($_GET['oauth_token']);
			$token = $twitterObj->getAccessToken();

			//print_R($token);
			//echo 'twitter_oauth_token: ' . $token->oauth_token . ' / twitter_oauth_token_secret: ' . $token->oauth_token_secret;

			//guardar los tokens en la base datos si se quieren usar mas adelante!
			//con los tokens podems acceder a la info del user, hay que recrear el objecto con los tokens privados
			$twitterObj = new \EpiTwitter($this->twitter_id, $this->twitter_secret,$token->oauth_token,$token->oauth_token_secret);
			$this->tokens['twitter']['token'] = $token->oauth_token;
			$this->tokens['twitter']['secret'] = $token->oauth_token_secret;

			$userInfo = $twitterObj->get_accountVerify_credentials();

			//Twitter NO RETORNA el email!!!
			$this->user_data['username'] = $userInfo->screen_name;
			$this->user_data['name'] = $userInfo->name;
			$this->user_data['profile_image_url'] = str_replace("_normal","",$userInfo->profile_image_url);
			//twitter link
			$this->user_data['twitter'] = 'http://twitter.com/'.$userInfo->screen_name;
			if($userInfo->url) $this->user_data['website'] = $userInfo->url;
			if($userInfo->location) $this->user_data['location'] = $userInfo->location;
			if($userInfo->description) $this->user_data['about'] = $userInfo->description;

			return true;
		}
		catch(Exception $e){
			$this->last_error =  $e->getMessage()." 1/ ".get_class($e);
			return false;
		}
		return true;
	}

	/**
	 * Login con openid
	 * */
	public function loginOpenid() {

		$openid = new \LightOpenID($this->host);

		if($openid->mode) {

			if ($openid->mode == 'cancel') {
				$this->last_error = "oauth-openid-access-denied";
				return false;

			} elseif($openid->validate()) {

				$data = $openid->getAttributes();
				//print_r($data);print_r($openid);print_r($openid->identity);die;
				/*
				//por seguridad no aceptaremos conexions de OpenID que no nos devuelvan el email
				if(!Goteo\Library\Check::mail($data['contact/email'])) {
					$this->last_error = "oauth-openid-email-required";
					return false;
				}*/

				$this->user_data['email'] = $data['contact/email'];
				$this->user_data['username'] = $data['namePerson/friendly'];
				$this->user_data['name']  = $data['namePerson'];
				if(empty($this->user_data['name'])) $this->user_data['name']  = trim($data['namePerson/first'] . " " . $data['namePerson/last']);
				if($data['contact/country/home']) $this->user_data['location'] = $data['contact/country/home'];

				//no se usan tokens para openid, guardamos el servidor como token
				$this->tokens['openid']['token'] = $this->openid_server;
				//como secreto usaremos un hash basado an algo que sea unico para cada usuario (la identidad openid es una URL única)
				//$this->tokens['openid']['secret'] = sha1($this->openid_server.$this->openid_secret.$data['contact/email']);
				$this->tokens['openid']['secret'] = $openid->identity;

				return true;
			}
			else {
				$this->last_error = "oauth-openid-not-logged";
				return false;
			}
		}

		$this->last_error = "oauth-openid-not-logged";
		return false;
	}

	/**
	 * Hace el login en goteo si es posible (existen tokens o el email es el mismo)
	 * Guarda los tokens si se encuentra el usuario
	 *
	 * @param $force_login	logea en goteo sin comprovar que la contraseña esté vacía o que el usuario este activo
	 * */
	public function goteoLogin($force_login = false) {
		/*****
		 * POSIBLE PROBLEMA:
		 * en caso de que ya se haya dado permiso a la aplicación goteo,
		 * el token da acceso al login del usuario aunque este haya cambiado el email en goteo.org
		 * es un problema? o da igual...
		*****/
		//Comprovar si existe el mail en la base de datos

		$username = "";
		//comprovar si existen tokens
		$query = Goteo\Core\Model::query('SELECT id FROM user WHERE id = (SELECT user FROM user_login WHERE provider = :provider AND oauth_token = :token AND oauth_token_secret = :secret)', array(':provider' => $this->provider, ':token' => $this->tokens[$this->provider]['token'], ':secret' => $this->tokens[$this->provider]['secret']));

		$username = $query->fetchColumn();

		if(empty($username)) {
			//no existen tokens, comprovamos si existe el email
			/**
			 * Problema de seguridad, si el proveedor openid nos indica un mail que no pertenece al usuario
			 * da un método para acceder a los contenidos de cualquier usuario
			 * por tanto, en caso de que no existan tokens, se deberá preguntar la contraseña al usuario
			 * si el usuario no tiene contraseña, podemos permitir el acceso directo o denegarlo (mas seguro)
			 * */
			$query = Goteo\Core\Model::query('SELECT id,password FROM user WHERE email = ?', array($this->user_data['email']));
			if($user = $query->fetchObject()) {
				$username = $user->id;
				//sin no existe contraseña permitimos acceso
				//if(!empty($user->password) && !$force_login) {
				//No permitimos acceso si no existe contraseña
				if(!$force_login) {
					//con contraseña lanzamos un error de usuario existente, se usará para mostrar un formulario donde preguntar el password
					$this->user_data['username'] = $username;
					$this->last_error = "oauth-goteo-user-password-exists";
					return false;
				}
			}
			else {
				//El usuario no existe
				//redirigir a user/confirm para mostrar un formulario para que el usuario compruebe/rellene los datos que faltan
				$this->last_error = "oauth-goteo-user-not-exists";
				return false;
			}

		}

		//si el usuario existe, actualizar o crear los tokens
		$this->saveTokensToUser($username);

		//actualizar la imagen de avatar si no tiene!
		if($this->user_data['profile_image_url']) {
			$query = Goteo\Core\Model::query('SELECT id FROM image WHERE id = (SELECT avatar FROM user WHERE id = ?)', array($username));
			if(!($query->fetchColumn())) {

				$img = new Goteo\Model\Image($this->user_data['profile_image_url']);
				$img->save();

				if($img->id) {
					Goteo\Core\Model::query("REPLACE user_image (user, image) VALUES (:user, :image)", array(':user' => $username, ':image' => $img->id));
					Goteo\Core\Model::query("UPDATE user SET avatar = :avatar WHERE id = :user", array(':user'=>$username,':avatar'=>$img->id));
				}
			}
		}

		//el usuario existe, creamos el objeto
		$user = Goteo\Model\User::get($username);

		//actualizar datos de usuario si no existen:
		$update = array();
		$data = array(':user' => $username);
		foreach($this->import_user_data as $key) {
			if(empty($user->$key) && $this->user_data[$key]) {
				$update[] = "$key = :$key";
				$data[":$key"] = $this->user_data[$key];
			}
		}
		if($update) {
			Goteo\Core\Model::query("UPDATE user SET ".implode(", ",$update)." WHERE id = :user", $data);
			//rebuild user object
			$user = Goteo\Model\User::get($username);
		}

		//actualizar las webs
		if($this->user_data['website']) {
			$current_webs = array();
			if(is_array($user->webs)) {
				foreach($user->webs as $k => $v)
				$current_webs[] = strtolower($v->url);
			}
			$webs = array();
			preg_match_all("/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/", $this->user_data['website'], $webs);
			if($webs[0] && is_array($webs[0])) {
				$updated = false;
				foreach($webs[0] as $web) {
					$web = strtolower($web);
					if(!in_array($web,$current_webs)) {
						Goteo\Core\Model::query("INSERT user_web (user, url) VALUES (:user, :url)", array(':user' => $username, ':url' => $web));
						$updated = true;
					}
				}
				//rebuild user object
				if($updated) $user = Goteo\Model\User::get($username);
			}
		}

		//Si no tiene imagen, importar de gravatar.com?
		if(!$user->avatar || $user->avatar->id == 1) {
			$query = Goteo\Core\Model::query('SELECT id FROM image WHERE id = (SELECT avatar FROM user WHERE id = ?)', array($username));
			if(!($query->fetchColumn())) {
				$url = "http://www.gravatar.com/avatar/" . md5(strtolower(trim($user->email)));
				$url .= "?d=404";

				$img = new Goteo\Model\Image( $url );
				$img->save();

				if($img->id) {
					Goteo\Core\Model::query("REPLACE user_image (user, image) VALUES (:user, :image)", array(':user' => $username, ':image' => $img->id));
					Goteo\Core\Model::query("UPDATE user SET avatar = :avatar WHERE id = :user", array(':user'=>$username,':avatar'=>$img->id));
					$user = Goteo\Model\User::get($username);
				}
			}
		}

			//CAMBIADO A: siempre login, aunque no esté activo el usuario
			//Iniciar sessión i redirigir
			$_SESSION['user'] = $user;

			//Guardar en una cookie la preferencia de "login with"
			//no servira para mostrar al usuario primeramente su opcion preferida
			setcookie("goteo_oauth_provider",$this->original_provider,time() + 3600*24*365);

			if (!empty($_POST['return'])) {
				throw new Goteo\Core\Redirection($_POST['return']);
			} elseif (!empty($_SESSION['jumpto'])) {
				$jumpto = $_SESSION['jumpto'];
				unset($_SESSION['jumpto']);
				throw new Goteo\Core\Redirection($jumpto);
			} else {
				throw new Goteo\Core\Redirection('/dashboard');
			}
	}

	/**
	 * Guarda los tokens generados en el usuario
	 * */
	public function saveTokensToUser($goteouser) {
		$query = Goteo\Core\Model::query('SELECT id FROM user WHERE id = ?', array($goteouser));
		if($id = $query->fetchColumn()) {
			foreach($this->tokens as $provider => $token) {
				if($token['token']) {
					$query = Goteo\Core\Model::query("REPLACE user_login (user,provider,oauth_token,oauth_token_secret) VALUES (:user,:provider,:token,:secret)",array(':user'=>$goteouser,':provider'=>$provider,':token'=>$token['token'],':secret'=>$token['secret']));
				}
			}
		}
		else {
			$this->last_error = "oauth-goteo-user-not-exists";
			return false;
		}
	}
}

?>
