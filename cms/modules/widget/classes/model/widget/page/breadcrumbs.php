<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Widget_Page_Breadcrumbs extends Model_Widget_Decorator {
	
	public $exclude = array();

	public function load_template_data()
	{
		return array(
			'pages' => Model_Page_Sitemap::get()->flatten(),
		);
	}
	
	public function set_values(array $data)
	{
		if( empty( $data['exclude'] ))
		{
			$this->exclude = array();
		}

		return parent::set_values($data);
	}

	public function fetch_data()
	{
		$crumbs = array();
		
		$ctx = Context::instance();

		if( ($crumbs = &$ctx->get_crumbs()) instanceof Breadcrumbs)
		{
			if( ! empty($this->exclude))
			{
				$crumbs->delete_by('id', $this->exclude);
			}
		}

		return array(
			'crumbs' => $crumbs
		);
	}
}