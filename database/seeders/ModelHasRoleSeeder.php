<?php

namespace Database\Seeders;

use App\Models\ModelHasRoles;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class ModelHasRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role_id', '!=', 3)->get();
        $model_has_roles = ModelHasRoles::whereIn('model_id', $users->pluck('id'))->get();

        if(count($users) != count($model_has_roles)) {
            foreach($users as $user) {
                $modelRole = ModelHasRoles::where('model_id', $user->id)->first();
                if(!$modelRole) {
                    $role = Role::where('simple_role_id', $user->role_id)->where('work_id', $user->work_department_id)->first();
                    ModelHasRoles::insert([
                        'model_id' => $user->id,
                        'role_id' => $role->id,
                        'model_type' => 'App\Models\User'
                    ]);
                }
            }
        }
    }
}
