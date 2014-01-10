<script>
	var DS_ID = '<?php echo $ds->id(); ?>';
</script>

<div class="widget">
<?php echo Form::open(Request::current()->uri(), array(
	'class' => 'form-horizontal'
)); ?>
	<?php echo Form::hidden('ds_id', $ds->id()); ?>

	<div class="widget-header">
		<h4><?php echo __('Datasource Information'); ?></h4>
	</div>
	<div class="widget-content">
		<div class="control-group">
			<label class="control-label" for="ds_key"><?php echo __('Datasource Key'); ?></label>
			<div class="controls">
				<?php
				echo Form::input( 'key', $ds->key, array(
					'class' => 'input-xlarge', 'id' => 'ds_key', 'disabled'
				) );
				?>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="ds_name"><?php echo __('Datasource Header'); ?></label>
			<div class="controls">
				<?php
				echo Form::input( 'name', $ds->name, array(
					'class' => 'input-xlarge', 'id' => 'ds_name'
				) );
				?>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="ds_description"><?php echo __('Datasource Description'); ?></label>
			<div class="controls">
				<?php
				echo Form::textarea( 'description', $ds->description, array(
					'class' => 'input-xlarge', 'id' => 'ds_description'
				) );
				?>
			</div>
		</div>
		
	</div>
	
	<?php echo View::factory('datasource/data/hybrid/blocks/fields', array(
		'record' => $ds->get_record(), 'ds' => $ds
	)); ?>
	<div class="form-actions widget-footer">
		<?php echo UI::actions(NULL, Route::url('datasources', array(
			'controller' => 'data',
			'directory' => 'datasources'
		))); ?>
	</div>
<?php echo Form::close(); ?>
</div>