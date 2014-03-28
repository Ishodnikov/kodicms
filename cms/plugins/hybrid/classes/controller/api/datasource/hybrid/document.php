<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Controller_Api_Datasource_Hybrid_Document extends Controller_System_API
{
	public function post_create()
	{
		$ds_id = $this->param('ds_id', NULL, TRUE);
		
		$ds = Datasource_Data_Manager::load((int) $ds_id);
		
		if(empty($ds))
		{
			throw HTTP_API_Exception::factory(API::ERROR_UNKNOWN, 'Datasource section not found');
		}
		
		echo debug::vars($this->params());
		
		try
		{
			$doc = $ds->get_empty_document();
			
			$doc->read_values($this->params())
				->validate();
			
			$doc = $ds->create_document($doc);
		} 
		catch (Validation_Exception $e) 
		{
			throw new API_Validation_Exception($e->errors('validation'));
		}
		
		$this->message(__('Document created'));
		$this->response($doc);
	}
	
	public function post_update()
	{
		$ds_id = $this->param('ds_id', NULL, TRUE);
		$id = $this->param('id', NULL, TRUE);
		
	}

	public function post_publish()
	{
		$doc_ids = $this->param('doc', array(), TRUE);

		if(empty($doc_ids))
		{
			throw HTTP_API_Exception::factory(API::ERROR_UNKNOWN,
				'Error');
		}
		
		$dsf = new DataSource_Hybrid_Factory;
		$dsf->publish_documents($doc_ids);
		
		$this->json['documents'] = $doc_ids;
	}
	
	public function post_unpublish()
	{
		$doc_ids = $this->param('doc', array(), TRUE);
		
		if(empty($doc_ids))
		{
			throw HTTP_API_Exception::factory(API::ERROR_UNKNOWN,
				'Error');
		}
		
		$dsf = new DataSource_Hybrid_Factory;
		$dsf->unpublish_documents($doc_ids);
		
		$this->json['documents'] = $doc_ids;
	}
	
	public function post_remove()
	{
		$doc_ids = $this->param('doc', array(), TRUE);
		
		if(empty($doc_ids))
		{
			throw HTTP_API_Exception::factory(API::ERROR_UNKNOWN,
				'Error');
		}
		
		$dsf = new DataSource_Hybrid_Factory;
		$dsf->remove_documents( $doc_ids );
		
		$this->json['documents'] = $doc_ids;
	}
	
	public function get_find()
	{
		$query = $this->param('key', NULL);
		$ids = $this->param('ids', array());
		$doc_id = $this->param('id', NULL);
		$ds_id = (int) $this->param('doc_ds', NULL, TRUE);

		$this->request->query('keyword', $query);
		$ds = Datasource_Data_Manager::load($ds_id);
		$documents = $ds->headline()->get($ids);
		
		$response = array();
		foreach($documents['documents'] as $id => $data)
		{
			if($doc_id != $id)
				$response[] = array(
					'id' => $id,
					'text' => $data['header']
				);
		}
		
		$this->response($response);
	}
}