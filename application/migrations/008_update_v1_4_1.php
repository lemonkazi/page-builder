<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_v1_4_1 extends CI_Migration {

    public function up()
    {
		
		// Insert new settings into the settings table
        $insert_data = array(
		    array(
                'name' => 'show_locked_blocks',
                'value' => 'yes',
				'default_value' =>'yes',
				'description' =>"<h4>Show disabled blocks as locked</h4>
					<p>If a user's subscription package has some block limitation, these blocks and block categories will not be hidden for users but will be shown with the blocked icon.
					</p>",
				'required' => '0'
			)
		);
        $this->db->insert_batch('apps_settings', $insert_data);

		
		$fields = array(
            'pages_number' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => FALSE,
                'after' => 'sites_number'
            ),
            'edit_sourcecode' => array(
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => FALSE,
                'after' => 'pages_number'
            )
        );
        $this->dbforge->add_column('packages', $fields);

    }

    public function down()
    {
        // Drop column pages_number and edit_sourcecode from packages table
        $this->dbforge->drop_column('packages', 'pages_number');
		$this->dbforge->drop_column('packages', 'edit_sourcecode');

		//delete show_locked_blocks option from settings table
		$this->db->delete('settings', array('name' => 'show_locked_blocks'));
    }
}