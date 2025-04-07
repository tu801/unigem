<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Modules\Acp\Enums\UserTypeEnum;

class CmsTables extends Migration
{
    public function up()
	{
        /*
         * Config (this version store config_group data in array of configs)
         */
        $this->forge->addField([
            'id'              	=> ['type' => 'int',  'unsigned' => true, 'auto_increment' => true],
            'group_id'          => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'title'	            => ['type' => 'varchar', 'constraint' => 255, 'null' => false],
            'key'         		=> ['type' => 'varchar', 'constraint' => 255, 'null' => false],
            'value'     		=> ['type' => 'text'],
            'is_json'	      	=> ['type' => 'tinyint', 'unsigned' => true, 'default' => 0],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['group_id', 'key']);
        $this->forge->createTable('config', true);

        /**
         * This version extend the user model from CodeIgniter Shield
         * https://shield.codeigniter.com/customization/adding_attributes_to_users/
         */
		$userFields = [
            'user_type'      	=> ['type' => 'varchar', 'constraint' => 64, 'default' => UserTypeEnum::ADMIN, 'after' => 'username'],
            'avatar'	        => ['type' => 'varchar', 'constraint' => 255, 'null' => true, 'after' => 'user_type'],            
        ];
        $this->forge->addColumn('users', $userFields);

        /*
         * Users Meta
         */
        $fields = [
            'id'          		=> ['type' => 'bigint', 'unsigned' => true, 'auto_increment' => true],
            'user_id'        	=> ['type' => 'int', 'unsigned' => true],
            'meta_key'	 		=> ['type' => 'varchar', 'constraint' => 50],
            'meta_value'		=> ['type' => 'text'],
        ];

		$this->forge->addField($fields);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['meta_key', 'user_id']);
        $this->forge->createTable('user_meta', true);

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
        $this->forge->addKey(['locale', 'lang_code']);
        $this->forge->addKey('is_default');
        $this->forge->createTable('language', true);

    }

	//--------------------------------------------------------------------

	public function down()
	{
        // remove field from users table
        $this->forge->dropColumn('users', ['user_type', 'avatar']);

		//drop table
		$this->forge->dropTable('config', true);
		$this->forge->dropTable('user_meta', true);
		$this->forge->dropTable('attach', true);
        $this->forge->dropTable('attach_meta', true);
        $this->forge->dropTable('log', true);
        $this->forge->dropTable('record_type', true);
        $this->forge->dropTable('language', true);

	}
}
