<?php defined('SYSPATH') or die('No direct access allowed.');

class Setting
{
	protected static $table_name = 'setting';

	public static $settings = array();
    public static $is_loaded = FALSE;
    
    public static function init()
    {
        if (! self::$is_loaded)
        {
            self::$settings = DB::select()
				->from(self::$table_name)
				->cache_key(self::$table_name)
				->cached()
				->execute()
				->as_array('name', 'value');
            
            self::$is_loaded = true;
        }
    }
    
    /**
     * Get the value of a setting
     *
     * @param name  string  The setting name
     * @return string the value of the setting name
     */
    public static function get($name, $default = NULL)
    {
        return isset(self::$settings[$name]) 
			? self::$settings[$name]
			: $default;
    }
    
    public static function saveFromData($data)
    {
        foreach( $data as $name => $value )
        {
			if(self::get($name) === NULL)
			{
				$query = DB::insert(self::$table_name)
					->columns(array('name', 'value'))
					->values(array($name, $value));
			}
			else 
			{
				$query = DB::update(self::$table_name)
					->set(array('value' => $value))
					->where('name', '=', $name);
			}
			
			$query->execute();
        }
		
		Core::cache('Database::cache('.self::$table_name.')', NULL, -1);
    }
} // end Setting class