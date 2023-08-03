<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_v1_0_4 extends CI_Migration {

    public function up()
    {
        // Update user table
        $user_fields = array(
            'paypal_token' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE,
                'after' => 'stripe_sub_id'
            ),
            'paypal_profile_id' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE,
                'after' => 'paypal_token'
            ),
            'paypal_profile_status' => array(
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => FALSE,
                'after' => 'paypal_profile_id'
            ),
            'paypal_last_transaction_id' => array(
                'type' => 'VARCHAR',
                'constraint' => 30,
                'null' => FALSE,
                'after' => 'paypal_profile_status'
            ),
            'current_subscription_gateway' => array(
                'type' => 'enum("stripe","paypal")',
                'default' => 'stripe',
                'null' => FALSE,
                'after' => 'paypal_last_transaction_id'
            ),
            'payer_id' => array(
                'type' => 'VARCHAR',
                'constraint' => 128,
                'null' => FALSE,
                'after' => 'current_subscription_gateway'
            ),
            'paypal_next_payment_date' => array(
                'type' => 'VARCHAR',
                'constraint' => 128,
                'null' => FALSE,
                'after' => 'payer_id'
            ),
            'paypal_previous_payment_date' => array(
                'type' => 'VARCHAR',
                'constraint' => 128,
                'null' => FALSE,
                'after' => 'paypal_next_payment_date'
            )
        );
        $this->dbforge->add_column('users', $user_fields);

        // Update package table
        $package_fields = array(
            'gateway' => array(
                'type' => 'VARCHAR',
                'constraint' => 20,
                'default' => 'stripe',
                'null' => TRUE,
                'after' => 'id'
            )
        );
        $this->dbforge->add_column('packages', $package_fields);

        // Create payment_log for paypal
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'auto_increment' => TRUE
            ),
            'request' => array(
                'type' => 'TEXT',
                'constraint' => '100',
            ),
            'response' => array(
                'type' => 'TEXT',
                'constraint' => '100',
            ),
            'date' => array(
                'type' => 'DATETIME',
                'null' => FALSE,
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('payment_log');

        // Manual queries to insert rows
        $insert_data = array(
            array(
                'id' => '4',
                'name' => 'paypal_api_username',
                'value' => '',
                'default_value' => '',
                'description' => 'Enter your PayPal API username.<br> For more details <a href="https://developer.paypal.com/docs/classic/api/apiCredentials/#create-an-api-signature" target="_blank">Click Here</a>',
                'required' => '0',
                'created_at' => '2017-07-13 00:00:00',
                'modified_at' => 'NULL'
            ),
            array(
                'id' => '5',
                'name' => 'paypal_api_password',
                'value' => '',
                'default_value' => '',
                'description' => 'Enter PayPal API password.<br> For more details <a href="https://developer.paypal.com/docs/classic/api/apiCredentials/#create-an-api-signature" target="_blank">Click Here</a>',
                'required' => '0',
                'created_at' => '2017-07-13 00:00:00',
                'modified_at' => 'NULL'
            ),
            array(
                'id' => '6',
                'name' => 'paypal_api_signature',
                'value' => '',
                'default_value' => '',
                'description' => 'Enter PayPal API signature.<br> For more details <a href="https://developer.paypal.com/docs/classic/api/apiCredentials/#create-an-api-signature" target="_blank">Click Here</a>',
                'required' => '0',
                'created_at' => '2017-07-13 00:00:00',
                'modified_at' => 'NULL'
            ),
            array(
                'id' => '7',
                'name' => 'paypal_test_mode',
                'value' => 'test',
                'default_value' => 'test',
                'description' => 'Your PayPal Environment',
                'required' => '0',
                'created_at' => '2017-07-13 00:00:00',
                'modified_at' => 'NULL'
            ),
            array(
                'id' => '8',
                'name' => 'payment_gateway',
                'value' => 'paypal',
                'default_value' => 'paypal',
                'description' => 'Select Payment gateway you want for end users. Allowed values are paypal|stripe',
                'required' => '0',
                'created_at' => '2017-07-13 00:00:00',
                'modified_at' => 'NULL'
            ),
        );
        $this->db->insert_batch('payment_settings', $insert_data);

        // Manual queries to update rows
        $update_data = array(
            array(
                'id' => 1,
                'description' => 'Your Stripe Secret Key.<br> For more information <a href="https://stripe.com/docs/dashboard#api-keys" target="_blank">Click Here</a>',
                'modified_at' => date('Y-m-d H:i:s', time())
            ),
            array(
                'id' => 2,
                'description' => 'Your Stripe Publishable Key.<br> For more information <a href="https://stripe.com/docs/dashboard#api-keys" target="_blank">Click Here</a>',
                'modified_at' => date('Y-m-d H:i:s', time())
            )
        );
        $this->db->update_batch('payment_settings', $update_data, 'id');

        // Add "sitethumb" column to sites table
        $fields = array(
            'sitethumb' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '',
                'after' => 'viewmode'
            )
        );
        $this->dbforge->add_column('sites', $fields);

        // Change image storage settings
        $this->db->query("UPDATE `apps_settings` SET `value` = 'images' WHERE id = 2");
        $this->db->query("UPDATE `apps_settings` SET `default_value` = 'images' WHERE id = 2");
        $this->db->query("UPDATE `apps_settings` SET `value` = 'images/uploads' WHERE id = 3");
        $this->db->query("UPDATE `apps_settings` SET `default_value` = 'images/uploads' WHERE id = 3");
        $this->db->query("UPDATE `apps_settings` SET `value` = 'elements/bundles|elements/css|images' WHERE id = 9");
        $this->db->query("UPDATE `apps_settings` SET `default_value` = 'elements/bundles|elements/css|images' WHERE id = 9");

        // Change image settings
        $this->db->query("UPDATE `apps_settings` SET `value` = 'image/gif, image/jpg, image/png' WHERE id = 4");
        $this->db->query("UPDATE `apps_settings` SET `default_value` = 'image/gif, image/jpg, image/png' WHERE id = 4");
        // No longer needed
        $this->db->query("DELETE FROM `apps_settings` WHERE id = 6");
        $this->db->query("DELETE FROM `apps_settings` WHERE id = 7");

        // Add "pagethumb" column to pages table
        $fields = array(
            'pagethumb' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '',
                'after' => 'pages_css'
            )
        );
        $this->dbforge->add_column('pages', $fields);

        // Add columns to packages table
        $fields = array(
            'export_site' => array(
                'type' => 'VARCHAR',
                'constraint' => 10,
                'default' => 'no',
                'null' => FALSE,
                'after' => 'hosting_option'
            ),
            'disk_space' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 0,
                'null' => FALSE,
                'after' => 'export_site'
            ),
            'templates' => array(
                'type' => 'TEXT',
                'constraint' => 100,
                'after' => 'disk_space'
            )
        );
        $this->dbforge->add_column('packages', $fields);
    }

    public function down()
    {
        // Drop column from users table
        $this->dbforge->drop_column('users', 'paypal_token');
        $this->dbforge->drop_column('users', 'paypal_profile_id');
        $this->dbforge->drop_column('users', 'paypal_profile_status');
        $this->dbforge->drop_column('users', 'paypal_last_transaction_id');
        $this->dbforge->drop_column('users', 'current_subscription_gateway');
        $this->dbforge->drop_column('users', 'payer_id');
        $this->dbforge->drop_column('users', 'paypal_next_payment_date');
        $this->dbforge->drop_column('users', 'paypal_previous_payment_date');

        // Drop payment_log table
        $this->dbforge->drop_table('payment_log');

        // Drop row from payment_settings table
        $this->db->delete('payment_settings', array('id' => 4));
        $this->db->delete('payment_settings', array('id' => 5));
        $this->db->delete('payment_settings', array('id' => 6));
        $this->db->delete('payment_settings', array('id' => 7));
        $this->db->delete('payment_settings', array('id' => 8));

        // Drop sitethumb column from sites table
        $this->dbforge->drop_column('sites', 'sitethumb');

        // Change back image storage settings
        $this->db->query("UPDATE `apps_settings` SET `value` = 'elements' WHERE id = 2");
        $this->db->query("UPDATE `apps_settings` SET `default_value` = 'elements' WHERE id = 2");
        $this->db->query("UPDATE `apps_settings` SET `value` = 'elements/images/uploads' WHERE id = 3");
        $this->db->query("UPDATE `apps_settings` SET `default_value` = 'elements/images/uploads' WHERE id = 3");

        // Change back image settings
        $this->db->query("UPDATE `apps_settings` SET `value` = 'gif|jpg|png' WHERE id = 4");
        $this->db->query("UPDATE `apps_settings` SET `default_value` = 'gif|jpg|png' WHERE id = 4");
        // Add two row in apps_settings
        $this->db->query("INSERT INTO `apps_settings` (`id`, `name`, `value`, `default_value`, `description`, `required`) VALUES(6, 'upload_max_width', '3000', '1024', '<h4>Maximum Upload Width</h4><p>The maximum allowed width for images uploaded by users.</p>', 1)");
        $this->db->query("INSERT INTO `apps_settings` (`id`, `name`, `value`, `default_value`, `description`, `required`) VALUES(7, 'upload_max_height', '2000', '768', '<h4>Maximum Upload Height</h4><p>The maximum allowed height for images uploaded by users.</p>', 1)");

        // Drop pagethumb column from pages table
        $this->dbforge->drop_column('pages', 'pagethumb');

        // Drop columns from packages table
        $this->dbforge->drop_column('packages', 'export_site');
        $this->dbforge->drop_column('packages', 'disk_space');
        $this->dbforge->drop_column('packages', 'templates');
    }
}