<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Role::truncate();
        $role_super_admin = Role::create(['name' => User::SUPER_ADMIN, 'guard_name' => 'web']);
        $role_admin = Role::create(['name' => User::ADMIN, 'guard_name' => 'web']);
        $role_user = Role::create(['name' => User::USER, 'guard_name' => 'web']);



        $permission_super_admin = [
            // Menu Manajemen Pengguna
            'read_permission',
            'update_permission',
            'delete_permission',
            'create_permission',
            'read_role',
            'update_role',
            'delete_role',
            'create_role',
            'read_user_management',
            'update_user_management',
            'delete_user_management',
            'create_user_management',
            'ban_user_management',
            'unBan_user_management',
            'resetPassword_user_management',
            'read_report',
            'update_report',
            'delete_report',
            'create_report',
            'process_report',
            'accept_report',
            'reject_report',
            'netral_report',
            'read_profile',
            'update_profile',
            'read_news',
            'update_news',
            'delete_news',
            'create_news',
            'publish_news',
            'takedown_news',
            'user_read_news',

            'read_disaster_category',
            'update_disaster_category',
            'delete_disaster_category',
            'create_disaster_category',

            'read_consultation',
            'create_consultation',
            'update_consultation',
            'delete_consultation',

            'read_disaster_impacts',
            'create_disaster_impacts',
            'update_disaster_impacts',
            'delete_disaster_impacts',

            'read_disaster_victims',
            'create_disaster_victims',
            'update_disaster_victims',
            'delete_disaster_victims',

            'read_infografis',
            'create_infografis',
            'update_infografis',
            'delete_infografis',
            'read_recap',
            'exportPDF_recap',
            'exportExcel_recap',

            'read_disaster_report_documentations',
            'create_disaster_report_documentations',
            'update_disaster_report_documentations',
            'delete_disaster_report_documentations',
            'exportPDF_disaster_report_documentations',
            'exportExcel_disaster_report_documentations',

        ];

        $permission_admin = [


            'read_report',
            'update_report',
            'delete_report',
            'create_report',
            'process_report',
            'accept_report',
            'reject_report',
            'netral_report',
            'read_profile',
            'update_profile',
            'read_news',
            'update_news',
            'delete_news',
            'create_news',
            'publish_news',
            'takedown_news',
            'user_read_news',
            'read_disaster_category',
            'update_disaster_category',
            'delete_disaster_category',
            'create_disaster_category',
            'read_consultation',
            'create_consultation',
            'update_consultation',
            'delete_consultation',
            'read_disaster_impacts',
            'create_disaster_impacts',
            'update_disaster_impacts',
            'delete_disaster_impacts',
            'read_disaster_victims',
            'create_disaster_victims',
            'update_disaster_victims',
            'delete_disaster_victims',
            'read_infografis',
            'create_infografis',
            'update_infografis',
            'delete_infografis',
            'read_recap',
            'exportPDF_recap',
            'exportExcel_recap',

            'read_disaster_report_documentations',
            'create_disaster_report_documentations',
            'update_disaster_report_documentations',
            'delete_disaster_report_documentations',
            'exportPDF_disaster_report_documentations',
            'exportExcel_disaster_report_documentations',
        ];

        $permission_user = [

            'read_report',
            'update_report',
            'delete_report',
            'create_report',
            'read_profile',
            'update_profile',
            'user_read_news',

        ];

        $role_super_admin->givePermissionTo($permission_super_admin);
        $role_admin->givePermissionTo($permission_admin);
        $role_user->givePermissionTo($permission_user);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
