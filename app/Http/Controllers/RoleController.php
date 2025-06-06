<?php

namespace App\Http\Controllers;

use App\Models\RoleTranslation;
// use App\Models\Role;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:view_staff_roles'])->only('index');
        $this->middleware(['permission:add_staff_role'])->only('create');
        $this->middleware(['permission:edit_staff_role'])->only('edit');
        $this->middleware(['permission:delete_staff_role'])->only('destroy');
    }

    public function index()
    {
        $roles = Role::where('id', '!=', 1)->paginate(10);
        return view('backend.staff.staff_roles.index', compact('roles'));

    }

    public function create()
    {
        return view('backend.staff.staff_roles.create');
    }

    public function store(Request $request)
    {
        $role = Role::create(['name' => $request->name]);
        $role->givePermissionTo($request->permissions);

        $role_translation = RoleTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'role_id' => $role->id]);
        $role_translation->name = $request->name;
        $role_translation->save();

        flash(translate('New Role has been added successfully'))->success();
        return redirect()->route('roles.index');
    }

    public function show($id)
    {
        //
    }

    public function edit(Request $request, $id)
    {
        $lang = $request->lang;
        $role = Role::findOrFail($id);
        return view('backend.staff.staff_roles.edit', compact('role', 'lang'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        if ($request->lang == env("DEFAULT_LANGUAGE")) {
            $role->name = $request->name;
        }
        $role->syncPermissions($request->permissions);
        $role->save();

        // Role Translation
        $role_translation = RoleTranslation::firstOrNew(['lang' => $request->lang, 'role_id' => $role->id]);
        $role_translation->name = $request->name;
        $role_translation->save();

        flash(translate('Role has been updated successfully'))->success();
        return back();

    }

    public function destroy($id)
    {
        if (env('DEMO_MODE') == 'On') {
            flash(translate('Data can not change in demo mode.'))->info();
            return back();
        }

        RoleTranslation::where('role_id', $id)->delete();
        Role::destroy($id);
        flash(translate('Role has been deleted successfully'))->success();
        return redirect()->route('roles.index');
    }

    public function add_permission(Request $request)
    {
        $permission = Permission::create(['name' => $request->name, 'section' => $request->parent]);
        return redirect()->route('roles.index');
    }

    public function create_admin_permissions()
    {
    }
}
