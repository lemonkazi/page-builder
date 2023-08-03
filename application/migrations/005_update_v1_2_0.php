<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_v1_2_0 extends CI_Migration {

    public function up()
    {

        // Add the "keys" table (used by the public API)
        $fields = array(
            'id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'auto_increment' => TRUE
            ),
            'key' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 40
            ),
            'level' => array(
                    'type' =>'INT',
                    'constraint' => 2
            ),
            'ignore_limits' => array(
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 0
            ),
            'date_created' => array(
                    'type' => 'INT',
                    'constraint' => 11
            )
        );

        $this->dbforge->add_key('id', TRUE);
        
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('rest_api_keys');

        // Add the auto_login_token to the users table
        $users_fields = array(
            'auto_login_token' => array(
                'type' => 'VARCHAR',
                'constraint' => '40',
                'default' => '',
                'null' => TRUE,
                'after' => 'login_token'
            )
        );
        $this->dbforge->add_column('users', $users_fields);

        // Insert new settings into the settings table
        $insert_data = array(
            array(
                'name' => 'rest_on',
                'value' => '0'
            ),
            array(
                'name' => 'rest_ip_whitelist_enabled',
                'value' => '0'
            ),
            array(
                'name' => 'rest_ip_whitelist',
                'value' => ''
            ),
            array(
                'name' => 'alogin_on',
                'value' => '0'
            ),
            array(
                'name' => 'alogin_key',
                'value' => ''
            )
        );
        $this->db->insert_batch('settings', $insert_data);

    }

    public function down()
    {

        // Remove the "keys" table
        $this->dbforge->drop_table('keys', TRUE);

        // Drop column auto_login_token from users table
        $this->dbforge->drop_column('users', 'auto_login_token');

        // Remove data from settings table
        $this->db->delete('settings', array(
            'name' => 'rest_on',
            'name' => 'rest_ip_whitelist_enabled',
            'name' => 'rest_ip_whitelist',
            'name' => 'alogin_on',
            'name' => 'alogin_key'
        ));
        
    }
}