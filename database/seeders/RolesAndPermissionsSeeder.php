<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\Group_Service;
use App\Models\Permission;
use App\Models\Role;
use App\Scopes\GroupScope;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

       /* $roles = array(
            'Administrator',
            'Cadre Administrator',
        );*/

        $modules = array(
            'Officer'
        );


        $subModules = array(
            'Personal Information',
            'Qualification',
            'Foreign Visits',
            'Mandatory Training',
            'Training',
            'Spouse Info',
            'Promotion',
            'Contact Information',
            'Achievements',
            'Service/Posting History',
        );

        $permissions = array(
            'Read',
            'Write',
            'Edit',
            'Delete',
        );

        foreach ($modules as $module) {
            foreach ($subModules as $subModule) {
                foreach ($permissions as $permission) {
                    Permission::create(['name' => $permission . ' ' . $subModule, 'module' => $module, 'sub_module' => $subModule]);
                }
            }
        }

        /*foreach ($roles as $key => $role) {

            $roleCreated = Role::create(['name' => $role]);
            if ($role == 'Admin / Administrator') {
                $roleCreated->givePermissionTo(Permission::all());
            }
        }*/


        $serviceModules = array(
            'Group Services'
        );

        $groupServices =  array(
            'Pakistan Administrative Service',
            'Police Service of Pakistan',
            'Secretariat Group',
            'Office Management Group',
            'Pakistan Custom Service',
            'Inland Revenue Service',
            'Foreign Service of Pakistan',
            'Commerce & Trade Group',
            'Information Services Group',
            'Military Land & Cantonment Group',
            'Pakistan Audit & Account Services',
            'Postal Services',
            'Railway Commercial & Transport',
            'Economist Group',
            'Management Professional'
        );

        $grades = Grade::where('id','>','16')->get();

        foreach ($serviceModules as $serviceModule) {
            foreach ($groupServices as $groupService) {
                foreach ($grades as $grade) {
                   Permission::create(['name' => $groupService . ' ' . $grade->name, 'module' => $serviceModule, 'sub_module' => $groupService]);
                }
            }
        }

    }
}
