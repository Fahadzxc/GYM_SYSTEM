<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDepartmentToGymMembers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('gym_members', [
            'department' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'Department (Engineering, Teacher Education, Business, IT, Allied Health Sciences)',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('gym_members', 'department');
    }
}
