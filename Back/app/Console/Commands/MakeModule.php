<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModule extends Command
{
    protected $signature = 'make:module {name}';
    protected $description = 'Create a complete module structure with Model, Controller, Service, etc.';

    public function handle()
    {
        $name = $this->argument('name');
        $studlyName = Str::studly($name);
        $pluralName = Str::plural($studlyName);
        
        // Crear directorios
        $directories = [
            app_path("Modules/{$studlyName}"),
            app_path("Modules/{$studlyName}/Models"),
            app_path("Modules/{$studlyName}/Controllers"),
            app_path("Modules/{$studlyName}/Services"),
            app_path("Modules/{$studlyName}/Requests"),
            app_path("Modules/{$studlyName}/Resources"),
        ];

        foreach ($directories as $directory) {
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }
        }

        // Crear archivos
        $this->createModel($studlyName);
        $this->createController($studlyName, $pluralName);
        $this->createService($studlyName, $pluralName);
        $this->createResource($studlyName);

        $this->info("Module {$studlyName} created successfully!");
        $this->info("Location: app/Modules/{$studlyName}/");
    }

    protected function createModel($name)
    {
        $stub = <<<EOT
<?php

namespace App\Modules\\{$name}\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class {$name} extends Model
{
    use HasFactory;

    protected \$fillable = [
        // Add your fillable fields here
    ];

    protected \$casts = [
        // Add your casts here
    ];

    // Add your relationships here
}
EOT;

        File::put(app_path("Modules/{$name}/Models/{$name}.php"), $stub);
    }

    protected function createController($name, $pluralName)
    {
        $stub = <<<EOT
<?php

namespace App\Modules\\{$name}\Controllers;

use App\Modules\\{$name}\Models\\{$name};
use App\Modules\\{$name}\Services\\{$name}Service;
use App\Modules\\{$name}\Resources\\{$name}Resource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class {$name}Controller extends Controller
{
    protected \${$name}Service;

    public function __construct({$name}Service \${$name}Service)
    {
        \$this->{$name}Service = \${$name}Service;
    }

    public function index()
    {
        \${$pluralName} = \$this->{$name}Service->getAll{$pluralName}();
        return {$name}Resource::collection(\${$pluralName});
    }

    public function store(Request \$request)
    {
        \${$name} = \$this->{$name}Service->create{$name}(\$request->all());
        return new {$name}Resource(\${$name});
    }

    public function show({$name} \${$name})
    {
        return new {$name}Resource(\${$name});
    }

    public function update(Request \$request, {$name} \${$name})
    {
        \${$name} = \$this->{$name}Service->update{$name}(\${$name}, \$request->all());
        return new {$name}Resource(\${$name});
    }

    public function destroy({$name} \${$name})
    {
        \$this->{$name}Service->delete{$name}(\${$name});
        return response()->json(null, 204);
    }
}
EOT;

        File::put(app_path("Modules/{$name}/Controllers/{$name}Controller.php"), $stub);
    }

    protected function createService($name, $pluralName)
    {
        $stub = <<<EOT
<?php

namespace App\Modules\\{$name}\Services;

use App\Modules\\{$name}\Models\\{$name};
use Illuminate\Database\Eloquent\Collection;

class {$name}Service
{
    public function getAll{$pluralName}(): Collection
    {
        return {$name}::all();
    }

    public function get{$name}ById(int \$id): ?{$name}
    {
        return {$name}::find(\$id);
    }

    public function create{$name}(array \$data): {$name}
    {
        return {$name}::create(\$data);
    }

    public function update{$name}({$name} \${$name}, array \$data): {$name}
    {
        \${$name}->update(\$data);
        return \${$name}->fresh();
    }

    public function delete{$name}({$name} \${$name}): bool
    {
        return \${$name}->delete();
    }

    // Add your business logic methods here
    // Example: 
    // public function getActive{$pluralName}(): Collection
    // {
    //     return {$name}::where('is_active', true)->get();
    // }
}
EOT;

        File::put(app_path("Modules/{$name}/Services/{$name}Service.php"), $stub);
    }

    protected function createResource($name)
    {
        $stub = <<<EOT
<?php

namespace App\Modules\\{$name}\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class {$name}Resource extends JsonResource
{
    public function toArray(\$request)
    {
        return [
            'id' => \$this->id,
            // Add your resource fields here
            'created_at' => \$this->created_at,
            'updated_at' => \$this->updated_at,
        ];
    }
}
EOT;

        File::put(app_path("Modules/{$name}/Resources/{$name}Resource.php"), $stub);
    }
}