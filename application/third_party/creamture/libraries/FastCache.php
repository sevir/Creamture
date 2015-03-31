<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}

/**
 * Codeigniter Cache Library replacement for PHP Fast Cache
 */
class FastCache{

	var  $cache;
	var  $token;

	// ------------------------------------------------------------------------

	/**
	 * Constructor
	 *
	 * @param array
	 */
	public function __construct()
	{
		$CI = &get_instance();
		$CI->config->load('fastcache');

		//unique failsafe token for each project
		if (! file_exists(APPPATH.'cache/fastcache_token.php')){
			$token = md5(uniqid(mt_rand(), true));
			file_put_contents(APPPATH.'cache/fastcache_token.php', '<?php '."ªn".'$token=\''.$token.'\';');
		}else{
			include(APPPATH.'cache/fastcache_token.php');
		}

		$this->token = $token;
		$c = $CI->config->item('fastcache');

		$this->cache = phpFastCache($c['storage'], $CI->config->item('fastcache'));
	}

	public function get($key){
		return $this->cache->get($this->token.$key);
	}

	public function set($key, $val, $timeout=0){
		return $this->cache->set($this->token.$key, $val,$timeout);
	}

	public function getInfo($key){
		return $this->cache->getInfo($this->token.$key);
	}

	public function delete($key){
		return $this->cache->delete($this->token.$key);
	}

	public function clean(){
		return $this->cache->clean();
	}

	public function stats(){
		return $this->cache->stats();
	}

	public function increment($key, $val){
		return $this->cache->increment($this->token.$key, $val);
	}

	public function decrement($key, $val){
		return $this->cache->decrement($this->token.$key, $val);
	}

	public function touch($key, $timeout){
		return $this->cache->touch($this->token.$key, $timeout);
	}

	public function isExisting($key){
		return $this->cache->isExisting($this->token.$key);
	}

	public function getMulti($arr){
		array_walk($arr, function($v, $k){
			$v = $this->token.$v;
		});

		return $this->cache->getMulti($arr);
	}

	public function getInfoMulti($arr){
		array_walk($arr, function($v, $k){
			$v = $this->token.$v;
		});

		return $this->cache->getInfoMulti($arr);
	}

	public function deleteMulti($arr){
		array_walk($arr, function($v, $k){
			$v = $this->token.$v;
		});

		return $this->cache->deleteMulti($arr);
	}

	public function isExistingMulti($arr){
		array_walk($arr, function($v, $k){
			$v = $this->token.$v;
		});

		return $this->cache->isExistingMulti($arr);
	}

	public function setMulti($arr){
		array_walk($arr, function($v, $k){
			$v[0] = $this->token.$v[0];
		});

		return $this->cache->setMulti($arr);
	}

	public function touchMulti($arr){
		array_walk($arr, function($v, $k){
			$v[0] = $this->token.$v[0];
		});

		return $this->cache->touchMulti($arr);
	}

	public function incrementMulti($arr){
		array_walk($arr, function($v, $k){
			$v[0] = $this->token.$v[0];
		});

		return $this->cache->incrementMulti($arr);
	}

	public function decrementMulti($arr){
		array_walk($arr, function($v, $k){
			$v[0] = $this->token.$v[0];
		});

		return $this->cache->decrementMulti($arr);
	}

	public function c(){
		return $this->cache;
	}
}

/* End of file FastCache.php */