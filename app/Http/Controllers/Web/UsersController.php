<?php
namespace App\Http\Controllers\Web;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Artisan;

use App\Http\Controllers\Controller;
use App\Models\User;

class UsersController extends Controller {

	use ValidatesRequests;

    public function list(Request $request) {
        if(!auth()->user()->hasPermissionTo('show_users'))abort(401);
        $query = User::select('*');
        $query->when($request->keywords, 
        fn($q)=> $q->where("name", "like", "%$request->keywords%"));
        $users = $query->get();
        return view('users.list', compact('users'));
    }

	public function register(Request $request) {
        return view('users.register');
    }

    public function doRegister(Request $request) {

    	try {
    		$this->validate($request, [
	        'name' => ['required', 'string', 'min:5'],
	        'email' => ['required', 'email', 'unique:users'],
	        'password' => ['required', 'confirmed', 'min:4'],
	    	]);
    	}
    	catch(\Exception $e) {

    		return redirect()->back()->withInput($request->input())->withErrors('Invalid registration information.');
    	}

    	
    	$user =  new User();
	    $user->name = $request->name;
	    $user->email = $request->email;
	    $user->password = bcrypt($request->password); //Secure
        $user->credits = 100; // Initialize with some credits
        $user->save();

        try {
            // Check if Customer role exists, create it if it doesn't
            $customerRole = Role::firstOrCreate(['name' => 'Customer']);
            
            // Assign Customer role to newly registered users
            $user->assignRole($customerRole);
            
            // Clear cache to ensure permissions take effect
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        } catch (\Exception $e) {
            // Log the error but continue - don't prevent registration
            \Log::error('Error assigning Customer role: ' . $e->getMessage());
        }

        return redirect('/');
    }

    public function login(Request $request) {
        return view('users.login');
    }

    public function doLogin(Request $request) {
    	
    	if(!Auth::attempt(['email' => $request->email, 'password' => $request->password]))
            return redirect()->back()->withInput($request->input())->withErrors('Invalid login information.');

        $user = User::where('email', $request->email)->first();
        Auth::setUser($user);

        return redirect('/');
    }

    public function doLogout(Request $request) {
    	
    	Auth::logout();

        return redirect('/');
    }

    public function profile(Request $request, User $user = null) {

        $user = $user??auth()->user();
        
        // Allow viewing own profile
        if(auth()->id() == $user->id) {
            // User can always view their own profile
        }
        // Allow employees to view customer profiles
        else if($user->hasRole('Customer') && auth()->user()->hasPermissionTo('charge_credit'))  {
         // Employee can view customer profiles
        }
        // For other cases, require show_users permission
        else if(!auth()->user()->hasPermissionTo('show_users')) {
            abort(401);
        }

        $permissions = [];
        foreach($user->permissions as $permission) {
            $permissions[] = $permission;
        }
        foreach($user->roles as $role) {
            foreach($role->permissions as $permission) {
                $permissions[] = $permission;
            }
        }

        return view('users.profile', compact('user', 'permissions'));
    }

    public function edit(Request $request, User $user = null) {
   
        $user = $user??auth()->user();
        
        // Allow editing own profile
        if(auth()->id() == $user->id) {
            // User can always edit their own profile
        }
        // For editing other profiles, require edit_users permission
        else if(!auth()->user()->hasPermissionTo('edit_users')) {
            abort(401);
        }
    
        $roles = [];
        foreach(Role::all() as $role) {
            $role->taken = ($user->hasRole($role->name));
            $roles[] = $role;
        }

        $permissions = [];
        $directPermissionsIds = $user->permissions()->pluck('id')->toArray();
        foreach(Permission::all() as $permission) {
            $permission->taken = in_array($permission->id, $directPermissionsIds);
            $permissions[] = $permission;
        }      

        return view('users.edit', compact('user', 'roles', 'permissions'));
    }

    public function save(Request $request, User $user) {
        // Allow saving own profile
        if(auth()->id() == $user->id) {
            // User can always save their own profile
            $user->name = $request->name;
            $user->save();
        }
        // For editing other profiles, require show_users permission
        else if(!auth()->user()->hasPermissionTo('show_users')) {
            abort(401);
        }
        else {
            $user->name = $request->name;
            $user->save();
        }

        // Only admins can change roles and permissions
        if(auth()->user()->hasPermissionTo('admin_users')) {
            $user->syncRoles($request->roles);
            $user->syncPermissions($request->permissions);
            Artisan::call('cache:clear');
        }

        return redirect(route('profile', ['user'=>$user->id]));
    }

    public function delete(Request $request, User $user) {

        if(!auth()->user()->hasPermissionTo('delete_users')) abort(401);

        //$user->delete();

        return redirect()->route('users');
    }

    public function editPassword(Request $request, User $user = null) {

        $user = $user??auth()->user();
        if(auth()->id()!=$user?->id) {
            if(!auth()->user()->hasPermissionTo('edit_users')) abort(401);
        }

        return view('users.edit_password', compact('user'));
    }

    public function savePassword(Request $request, User $user) {

        if(auth()->id()==$user?->id) {
            
            $this->validate($request, [
                'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
            ]);

            if(!Auth::attempt(['email' => $user->email, 'password' => $request->old_password])) {
                
                Auth::logout();
                return redirect('/');
            }
        }
        else if(!auth()->user()->hasPermissionTo('edit_users')) {

            abort(401);
        }

        $user->password = bcrypt($request->password); //Secure
        $user->save();

        return redirect(route('profile', ['user'=>$user->id]));
    }

    public function chargeCredit(Request $request, User $user)
    {
        if(!auth()->user()->hasPermissionTo('charge_credit')) abort(401);
        
        $this->validate($request, [
            'amount' => ['required', 'numeric', 'min:0'],
        ]);
        
        // Only allow positive values for charging credit
        if($request->amount <= 0) {
            return redirect()->back()->withErrors('Only positive amounts are allowed for charging credits.');
        }
        
        $user->credits += $request->amount;
        $user->save();
        
        return redirect()->route('profile', ['user' => $user->id])->with('success', 'Credits charged successfully.');
    }
    
    // Method to list only customers for employees
    public function listCustomers(Request $request)
    {
        if(!auth()->user()->hasPermissionTo('charge_credit')) abort(401);
        
        $query = User::role('Customer')->select('*');
        $query->when($request->keywords, 
        fn($q)=> $q->where("name", "like", "%$request->keywords%"));
        $users = $query->get();
        
        return view('users.list_customers', compact('users'));
    }
    public function giveagift(Request $request)
    {
        if(!auth()->user()->hasPermissionTo('give_a_gift')) abort(401);
        
        $query = User::role('employee')->select('*');
        $query->when($request->keywords, 
        fn($q)=> $q->where("name", "like", "%$request->keywords%"));
        $users = $query->get();
        
        return view('users.list_customers', compact('users'));
    }

} 