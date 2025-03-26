<?php 
namespace Modules\Acp\Database\Migrations;

use CodeIgniter\Database\Migration;
use Modules\Acp\Enums\UserTypeEnum;

class Acp extends Migration
{
	public function up()
	{
        /*
         * Config (this version store config_group data in array of configs)
         */
        $this->forge->addField([
            'id'              	=> ['type' => 'bigint',  'unsigned' => true, 'auto_increment' => true],
            'group_id'          => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'title'	            => ['type' => 'varchar', 'constraint' => 255, 'null' => false],
            'key'         		=> ['type' => 'varchar', 'constraint' => 255, 'null' => false],
            'value'     		=> ['type' => 'text'],
            'is_json'	      	=> ['type' => 'tinyint', 'unsigned' => true, 'default' => 0],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('group_id');
        $this->forge->addKey('key');
        $this->forge->createTable('config', true);

		/*
         * User Groups Table
         */
        $fields = [
            'id'          		=> ['type' => 'bigint',  'unsigned' => true, 'auto_increment' => true],
            'name'        		=> ['type' => 'varchar', 'constraint' => 255],
            'description' 		=> ['type' => 'varchar', 'constraint' => 255],
            'permissions'		=> ['type' => 'json', 'null' => true],
            'created_at'       	=> ['type' => 'datetime', 'null' => true],
            'updated_at'       	=> ['type' => 'datetime', 'null' => true],
            'deleted_at'       	=> ['type' => 'datetime', 'null' => true],
        ];

        $this->forge->addField($fields);
        $this->forge->addKey('id', true);
        $this->forge->createTable('userg', true);

		/*
         * Users
         */
		$this->forge->addField([
			'id'              	=> ['type' => 'bigint', 'unsigned' => true, 'auto_increment' => true],
			'username'      	=> ['type' => 'varchar', 'constraint' => 50, 'null' => true],
			'avatar'	        => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
			'password_hash'		=> ['type' => 'varchar', 'constraint' => 255],
			'email'   	     	=> ['type' => 'varchar', 'constraint' => 255],
            'gid'              	=> ['type' => 'bigint', 'unsigned' => true, 'null' => true],
            'user_type'      	=> ['type' => 'varchar', 'constraint' => 64, 'default' => UserTypeEnum::ADMIN],
            'root_user'         => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0],
            'status'	        => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'status_message'	=> ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'reset_hash'        => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'force_pass_reset' 	=> ['type' => 'tinyint', 'constraint' => 1, 'null' => 0, 'default' => 0],
            'reset_at'          => ['type' => 'datetime', 'null' => true],
            'reset_expires'     => ['type' => 'datetime', 'null' => true],
            'activate_hash'     => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'active'           	=> ['type' => 'tinyint', 'constraint' => 1, 'null' => 0, 'default' => 0],
            'active_at'         => ['type' => 'datetime', 'null' => true],            
            'created_at'       	=> ['type' => 'datetime', 'null' => true],
            'updated_at'       	=> ['type' => 'datetime', 'null' => true],
            'deleted_at'       	=> ['type' => 'datetime', 'null' => true],
		]);

		$this->forge->addKey('id', true);
        $this->forge->addUniqueKey('username');
        $this->forge->addUniqueKey('email');
        $this->forge->addKey('gid');
        $this->forge->createTable('users', true);

        /*
         * Users Meta
         */
        $fields = [
            'id'          		=> ['type' => 'bigint', 'unsigned' => true, 'auto_increment' => true],
            'user_id'        	=> ['type' => 'bigint', 'unsigned' => true],
            'meta_key'	 		=> ['type' => 'varchar', 'constraint' => 50],
            'meta_value'		=> ['type' => 'text'],
        ];

		$this->forge->addField($fields);
        $this->forge->addKey('id', true);
        $this->forge->addKey('meta_key');
        $this->forge->addKey('user_id');
        $this->forge->createTable('usermeta', true);

        /*
         * Auth Login Attempts
         */
        $this->forge->addField([
            'id'         => ['type' => 'bigint', 'unsigned' => true, 'auto_increment' => true],
            'ip_address' => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'user_agent' => ['type' => 'varchar', 'constraint' => 255],
            'email'      => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'user_id'    => ['type' => 'bigint',  'unsigned' => true, 'null' => true], // Only for successful logins
            'user_type'  => ['type' => 'varchar', 'constraint' => 255, 'null' => true], //store user logged in model namespace
            'date'       => ['type' => 'datetime'],
            'success'    => ['type' => 'tinyint', 'constraint' => 1],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('email');
        $this->forge->addKey('user_id');
        // NOTE: Do NOT delete the user_id or email when the user is deleted for security audits
        $this->forge->createTable('auth_logins', true);

        /*
         * Auth Tokens
         * @see https://paragonie.com/blog/2015/04/secure-authentication-php-with-long-term-persistence
         */
        $this->forge->addField([
            'id'              => ['type' => 'bigint', 'unsigned' => true, 'auto_increment' => true],
            'selector'        => ['type' => 'varchar', 'constraint' => 255],
            'hashedValidator' => ['type' => 'varchar', 'constraint' => 255],
            'user_id'         => ['type' => 'bigint', 'unsigned' => true],
            'expires'         => ['type' => 'datetime'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('selector');
        $this->forge->addKey('user_id');
        $this->forge->createTable('auth_tokens', true);

        /*
         * Password Reset Table
         */
        $this->forge->addField([
            'id'         => ['type' => 'bigint', 'unsigned' => true, 'auto_increment' => true],
            'email'      => ['type' => 'varchar', 'constraint' => 255],
            'ip_address' => ['type' => 'varchar', 'constraint' => 255],
            'user_agent' => ['type' => 'varchar', 'constraint' => 255],
            'token'      => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'user_type'  => ['type' => 'varchar', 'constraint' => 255, 'null' => true], //store user logged in model namespace
            'user_id'       => ['type' => 'bigint', 'unsigned' => true, 'null' => true],
            'old_password'	=> ['type' => 'varchar', 'constraint' => 255, 'null' => true], // store old password for checking in the future
            'created_at' => ['type' => 'datetime', 'null' => false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('auth_reset_attempts', true);

        /*
         * Activation Attempts Table
         */
        $this->forge->addField([
            'id'         => ['type' => 'bigint', 'unsigned' => true, 'auto_increment' => true],
            'ip_address' => ['type' => 'varchar', 'constraint' => 255],
            'user_agent' => ['type' => 'varchar', 'constraint' => 255],
            'token'      => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'created_at' => ['type' => 'datetime', 'null' => false],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('auth_activation_attempts', true);

        /*
         * Permissions Table
         */
        $fields = [
            'id'          => ['type' => 'bigint',  'unsigned' => true, 'auto_increment' => true],
            'name'        => ['type' => 'varchar', 'constraint' => 255],
            'group'       => ['type' => 'varchar', 'constraint' => 50],
            'description' => ['type' => 'varchar', 'constraint' => 255],
            'action'	  => ['type' => 'varchar', 'constraint' => 255],
        ];

        $this->forge->addField($fields);
        $this->forge->addKey('id', true);
        $this->forge->createTable('permissions', true);

        /*
         * Add user Roles
         */
        $fields = [
            'user_id'       => ['type' => 'bigint', 'unsigned' => true, 'default' => 0],
            'permission_id' => ['type' => 'bigint', 'unsigned' => true, 'default' => 0],
        ];

        $this->forge->addField($fields);
        $this->forge->addKey(['user_id', 'permission_id']);
        $this->forge->createTable('users_permissions');

        /*
         * Attach
         */
		$this->forge->addField([
			'id'              	=> ['type' => 'bigint',  'unsigned' => true, 'auto_increment' => true],
			'user_id'	      	=> ['type' => 'bigint',  'unsigned' => true],
            'user_type'         => ['type' => 'varchar', 'constraint' => 255, 'default' => null],
			'file_name'	        => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
			'file_title'		=> ['type' => 'varchar', 'constraint' => 255],			
			'file_type'		    => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'created_at'       	=> ['type' => 'datetime', 'null' => true],
            'updated_at'       	=> ['type' => 'datetime', 'null' => true],
		]);

		$this->forge->addKey('id', true);
		$this->forge->addKey('user_id');
        $this->forge->createTable('attach', true);

        //add new table attach meta
        $this->forge->addField([
            'id'              	=> ['type' => 'bigint', 'unsigned' => true, 'auto_increment' => true],
            'mod_name'	        => ['type' => 'varchar', 'constraint' => 32, 'null' => false],
            'mod_id'      		=> ['type' => 'bigint',  'null' => false],
            'mod_type'      	=> ['type' => "enum('single','gallery')"],
            'images'			=> ['type' => 'text'],
            'created_at'       	=> ['type' => 'datetime', 'null' => true],
            'updated_at'       	=> ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('attach_meta', true);

        //add system log
        $this->forge->addField([
            'id'              	=> ['type' => 'bigint', 'unsigned' => true, 'auto_increment' => true],
            'module'            => ['type' => 'varchar',  'constraint' => 255, 'null' => true], //tên module diễn ra action (ie: acp | site ...)
            'title'             => ['type' => 'varchar',  'constraint' => 255, 'null' => true],
            'description'   	=> ['type' => 'text',  'null' => true],
            'properties'       	=> ['type' => 'longtext',  'null' => true], // json data
            'subject_id'  	    => ['type' => 'bigint',  'unsigned' => true, 'null' => true], // id đối tượng được tác động
            'subject_type'      => ['type' => 'varchar',  'constraint' => 255, 'null' => true],// tên class của đối tượng được tác động
            'causer_id'  	    => ['type' => 'bigint',  'unsigned' => true, 'null' => true], //id user tác động tới action
            'causer_type'       => ['type' => 'varchar',  'constraint' => 255, 'null' => true], // tên class của user tác động
            'lang_id'      		=> ['type' => 'int',  'null' => false, 'default' => 0],
            'created_at'       	=> ['type' => 'datetime', 'null' => true],
            'updated_at'       	=> ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('module');
        $this->forge->addKey('subject_id');
        $this->forge->addKey('subject_type');
        $this->forge->addKey('causer_id');
        $this->forge->addKey('causer_type');
        $this->forge->createTable('log', true);

        //add table Record Type
        $this->forge->addField([
            'id'              	=> ['type' => 'bigint', 'unsigned' => true, 'auto_increment' => true],
            'name'              => ['type' => 'varchar',  'constraint' => 255, 'null' => true],
            'developer_name'   	=> ['type' => 'varchar',  'constraint' => 255, 'null' => true],
            'object_type'  	    => ['type' => 'varchar',  'constraint' => 255, 'null' => true],
            'created_at'       	=> ['type' => 'datetime', 'null' => true],
            'updated_at'       	=> ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('developer_name');
        $this->forge->addKey('object_type');
        $this->forge->createTable('record_type', true);

        //add table language
        $this->forge->addField([
            'id'              	=> ['type' => 'int', 'unsigned' => true, 'auto_increment' => true],
            'user_init'	      	=> ['type' => 'bigint',  'unsigned' => true],
            'user_type'         => ['type' => 'varchar', 'constraint' => 255, 'default' => null],
            'name'              => ['type' => 'varchar',  'constraint' => 255, 'null' => true],
            'locale'           	=> ['type' => 'varchar',  'constraint' => 255, 'null' => true],
            'lang_code'         => ['type' => 'varchar',  'constraint' => 255, 'null' => true],
            'flag'        	    => ['type' => 'varchar',  'constraint' => 255, 'null' => true],
            'order' 	      	=> ['type' => 'tinyint', 'unsigned' => true, 'default' => 0],
            'is_default'      	=> ['type' => 'tinyint', 'unsigned' => true, 'default' => 0],
            'is_rtl'          	=> ['type' => 'tinyint', 'unsigned' => true, 'default' => 0],
            'currency_code'     => ['type' => 'varchar',  'constraint' => 32, 'null' => true],
            'created_at'       	=> ['type' => 'datetime', 'null' => true],
            'updated_at'       	=> ['type' => 'datetime', 'null' => true],
            'deleted_at'       	=> ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('locale');
        $this->forge->addKey('lang_code');
        $this->forge->createTable('language', true);

    }

	//--------------------------------------------------------------------

	public function down()
	{
		//drop table
		$this->forge->dropTable('config', true);
		$this->forge->dropTable('userg', true);
		$this->forge->dropTable('users', true);
		$this->forge->dropTable('usermeta', true);
        $this->forge->dropTable('auth_logins', true);
        $this->forge->dropTable('auth_tokens', true);
        $this->forge->dropTable('auth_reset_attempts', true);
        $this->forge->dropTable('auth_activation_attempts', true);
		$this->forge->dropTable('permissions', true);
        $this->forge->dropTable('users_permissions', true);
		$this->forge->dropTable('attach', true);
        $this->forge->dropTable('attach_meta', true);
        $this->forge->dropTable('log', true);
        $this->forge->dropTable('record_type', true);
        $this->forge->dropTable('language', true);

	}
}
