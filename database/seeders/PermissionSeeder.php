<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    { {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            Permission::truncate();
            // User
            Permission::create(['name' => 'create_user_management']);
            Permission::create(['name' => 'read_user_management']);
            Permission::create(['name' => 'update_user_management']);
            Permission::create(['name' => 'delete_user_management']);
            Permission::create(['name' => 'ban_user_management']);
            Permission::create(['name' => 'unBan_user_management']);
            Permission::create(['name' => 'resetPassword_user_management']);

            // Role
            Permission::create(['name' => 'create_role']);
            Permission::create(['name' => 'read_role']);
            Permission::create(['name' => 'update_role']);
            Permission::create(['name' => 'delete_role']);

            // Permission
            Permission::create(['name' => 'create_permission']);
            Permission::create(['name' => 'read_permission']);
            Permission::create(['name' => 'update_permission']);
            Permission::create(['name' => 'delete_permission']);

            //Report
            Permission::create(['name' => 'create_report']);
            Permission::create(['name' => 'read_report']);
            Permission::create(['name' => 'update_report']);
            Permission::create(['name' => 'delete_report']);
            Permission::create(['name' => 'process_report']);
            Permission::create(['name' => 'accept_report']);
            Permission::create(['name' => 'reject_report']);
            Permission::create(['name' => 'netral_report']);

            // Profile
            Permission::create(['name' => 'read_profile']);
            Permission::create(['name' => 'update_profile']);

            // News
            Permission::create(['name' => 'create_news']);
            Permission::create(['name' => 'read_news']);
            Permission::create(['name' => 'update_news']);
            Permission::create(['name' => 'delete_news']);
            Permission::create(['name' => 'publish_news']);
            Permission::create(['name' => 'takedown_news']);
            Permission::create(['name' => 'user_read_news']);

            // Disaster
            Permission::create(['name' => 'create_disaster_category']);
            Permission::create(['name' => 'read_disaster_category']);
            Permission::create(['name' => 'update_disaster_category']);
            Permission::create(['name' => 'delete_disaster_category']);

            // Consultation
            Permission::create(['name' => 'read_consultation']);
            Permission::create(['name' => 'create_consultation']);
            Permission::create(['name' => 'update_consultation']);
            Permission::create(['name' => 'delete_consultation']);

            //disaster_impacts
            Permission::create(['name' => 'create_disaster_impacts']);
            Permission::create(['name' => 'read_disaster_impacts']);
            Permission::create(['name' => 'update_disaster_impacts']);
            Permission::create(['name' => 'delete_disaster_impacts']);

            // disaster_victims
            Permission::create(['name' => 'create_disaster_victims']);
            Permission::create(['name' => 'read_disaster_victims']);
            Permission::create(['name' => 'update_disaster_victims']);
            Permission::create(['name' => 'delete_disaster_victims']);

            // infografis
            Permission::create(['name' => 'read_infografis']);
            Permission::create(['name' => 'create_infografis']);
            Permission::create(['name' => 'update_infografis']);
            Permission::create(['name' => 'delete_infografis']);

            // rekapitulasi
            Permission::create(['name' => 'read_recap']);
            Permission::create(['name' => 'exportPDF_recap']);
            Permission::create(['name' => 'exportExcel_recap']);

            //disaster_report_documentations
            Permission::create(['name' => 'read_disaster_report_documentations']);
            Permission::create(['name' => 'create_disaster_report_documentations']);
            Permission::create(['name' => 'update_disaster_report_documentations']);
            Permission::create(['name' => 'delete_disaster_report_documentations']);
            Permission::create(['name' => 'exportPDF_disaster_report_documentations']);
            Permission::create(['name' => 'exportExcel_disaster_report_documentations']);

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
