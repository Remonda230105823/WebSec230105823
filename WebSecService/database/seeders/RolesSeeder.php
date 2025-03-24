use Illuminate\Database\Seeder;
namespace Database\Seeders; 

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    public function run()
    {
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Employee']);
    }
}

