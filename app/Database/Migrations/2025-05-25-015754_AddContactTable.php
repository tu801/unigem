<?php

namespace App\Database\Migrations;

use App\Enums\ContactEnum;
use CodeIgniter\Database\Migration;

class AddContactTable extends Migration
{
    public function up()
    {
        /**
         * Add new table contact
         */
        $this->forge->addField([
            'id'                 => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'contact_type'       => ['type' => 'varchar', 'constraint' => 32, 'default' => ContactEnum::FORM_CONTACT_TYPE],
            'fullname'           => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'email'              => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'phone'              => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'subject'            => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'message'            => ['type' => 'text', 'null' => true],
            'status'             => ['type' => 'tinyint', 'constraint' => 1, 'default' => 0],
            'created_at'            => ['type' => 'datetime', 'null' => true],
            'updated_at'            => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('contact_type');
        $this->forge->createTable('contacts', true);
    }

    public function down()
    {
        //drop tables
        $this->forge->dropTable('contacts', true);
    }
}