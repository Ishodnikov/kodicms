<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Widgets
 * @category	Other
 * @author		ButscHSter
 */
class Model_Widget_User_Profile extends Model_Widget_Decorator {
	
	/**
	 *
	 * @var ORM 
	 */
	protected $_user = NULL;

	public function set_values(array $data) 
	{
		parent::set_values($data);

		$profile_id_ctx = Arr::get($data, 'profile_id_ctx');
		$this->profile_id_ctx = empty($profile_id_ctx) ? $this->profile_id_ctx : $profile_id_ctx;
		
		return $this;
	}
	
	public function on_page_load()
	{
		parent::on_page_load();
		
		$user = $this->get_user();
		if( ! $user->loaded() AND $this->throw_404 === TRUE )
		{
			$this->_ctx->throw_404('Profile not found');
		}
		
		$this->_ctx->set('widget_profile_id', $user->id);
		$this->_ctx->set('widget_profile_username', $user->username);
	}

	/**
	 * 
	 * @return array
	 */
	public function fetch_data()
	{
		$user = $this->get_user();
		
		return array(
			'user_found' => $user->loaded(),
			'user' => $user,
			'profile' => $user->profile
		);
	}
	
	/**
	 * 
	 * @return ORM
	 */
	public function get_user()
	{
		if($this->_user instanceof ORM) 
		{
			return $this->_user;
		}

		$profile_id = $this->get_profile_id();
		
		if(Valid::numeric($profile_id))
		{
			$this->_user = ORM::factory('User', $profile_id);
		}
		else
		{
			$this->_user = Auth::instance()->get_user(ORM::factory('User'));
		}
		
		return $this->_user;
	}

	/**
	 * 
	 * @return integer
	 */
	public function get_profile_id()
	{
		return $this->_ctx->get($this->profile_id_ctx);
	}
}