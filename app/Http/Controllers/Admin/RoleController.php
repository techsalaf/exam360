<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role as SpatieRole; 
use Spatie\Permission\Models\Permission as SpatiePermission; 
use Illuminate\Validation\Rule;
use App\Models\User; 
use App\Models\SystemSetting;
use App\Models\Addon;

class RoleController extends Controller
{
    private function getPermissionsStructure(): array
    {
        $structure = [
            'EXAMS MANAGEMENT' => [
                'view_exams' => 'View Exam List',
                'create_exams' => 'Create & Edit Exams',
                'delete_exams' => 'Delete Exams',
                'monitor_live_exams' => 'Live Exam Access', 
            ],
            'RESULT MANAGEMENT' => [
                'view_results' => 'View Results List',
                'manage_results' => 'Grade & Publish Results',
                'issue_certificates' => 'Issue Certificates',
            ],
            'IELTS MODULE' => [
                'manage_ielts_module' => 'Manage IELTS Tests & Content',
            ],
            'QUESTION BANK' => [
                'view_questions' => 'View Questions',
                'import_questions' => 'Import Questions',
            ],
            'USER MANAGEMENT' => [
                'view_users' => 'View User List',
                'create_users' => 'Create New Users',
                'change_roles' => 'Change User Roles',
            ],
            'SYSTEM ADMINISTRATION' => [
                'access_settings' => 'Access Settings Tabs',
                'configure_payments' => 'Configure Payment Gateways',
            ],
        ];

        if (Addon::where('slug', 'job-board')->where('is_active', true)->exists()) {
            $structure['JOB BOARD'] = [
                'manage_job_board' => 'Manage Job Postings & Submissions',
            ];
        }

        return $structure;
    }

    public function getRolesData(): array
    {
        $permissionsStructure = $this->getPermissionsStructure();
        $flattenedPermissions = [];
        foreach ($permissionsStructure as $group) {
            foreach ($group as $slug => $label) {
                $flattenedPermissions[] = $slug;
            }
        }
        
        foreach ($flattenedPermissions as $perm) {
            SpatiePermission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        $roles = $this->getRolesWithUsers();
        
        $allSettings = SystemSetting::getSettings();
        
        $defaultRole = $roles->firstWhere('name', 'Student') ?? $roles->first();
        $defaultSignupRoleSetting = $allSettings['default_signup_role'] ?? null;
        $defaultRoleId = $defaultSignupRoleSetting ?? ($defaultRole->id ?? null);

        return [
            'roles' => $roles,
            'permissionsStructure' => $permissionsStructure,
            'defaultRoleId' => $defaultRoleId
        ];
    }

    private function getRolesWithUsers()
    {
        $roles = SpatieRole::with('permissions')
            ->select('id', 'name', 'guard_name', 'created_at')
            ->get();

        $roleCounts = DB::table('model_has_roles')
            ->where('model_type', User::class)
            ->groupBy('role_id')
            ->select('role_id', DB::raw('count(*) as count'))
            ->pluck('count', 'role_id');
        
        return $roles->map(function ($role) use ($roleCounts) {
            $role->users_count = $roleCounts->get($role->id, 0);
            $role->is_locked = in_array($role->name, ['Super Admin', 'Student']);
            $role->permissionSlugs = $role->permissions->pluck('name')->toArray();
            return $role;
        });
    }
    
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string',
            'clone_role_id' => 'nullable|exists:roles,id',
        ]);
        
        $role = SpatieRole::create([
            'name' => $request->name,
            'description' => $request->description,
            'guard_name' => 'web'
        ]);

        if ($request->filled('clone_role_id')) {
            $sourceRole = SpatieRole::findById($request->clone_role_id);
            $role->syncPermissions($sourceRole->permissions);
        }
        
        return response()->json([
            'success' => true, 
            'message' => "Role '{$role->name}' created successfully.",
            'role' => $role
        ]);
    }

    public function delete(SpatieRole $role)
    {
        if ($role->name === 'Super Admin') {
            return response()->json(['success' => false, 'message' => 'The Super Admin role is protected.'], 403);
        }
        
        if ($role->users()->count() > 0) {
            return response()->json(['success' => false, 'message' => 'Cannot delete role with assigned users.'], 400);
        }

        $role->delete();

        return response()->json(['success' => true, 'message' => "Role '{$role->name}' deleted successfully."]);
    }

    public function updatePermissions(Request $request, SpatieRole $role)
    {
        $permissionsStructure = $this->getPermissionsStructure();
        $validPermissions = [];
        foreach ($permissionsStructure as $group) {
            foreach ($group as $slug => $label) {
                $validPermissions[] = $slug;
            }
        }
        
        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => ['string', Rule::in($validPermissions)],
        ]);

        $permissionsSlugs = $request->input('permissions', []);
        
        $permissionsToSync = [];
        foreach ($permissionsSlugs as $slug) {
            $permissionsToSync[] = SpatiePermission::firstOrCreate(['name' => $slug, 'guard_name' => 'web']);
        }

        $role->syncPermissions($permissionsToSync);
        
        return response()->json(['success' => true, 'message' => "Permissions for '{$role->name}' updated."]);
    }
    
    public function getPermissionsForRole(SpatieRole $role)
    {
        return response()->json([
            'success' => true,
            'permissions' => $role->permissions->pluck('name'),
        ]);
    }
}