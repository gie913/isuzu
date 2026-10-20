<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CodeGeneratorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module 
                            {name : The name of the module/entity (e.g. Product, OrderCategory)}
                            {--table= : The database table name (default: pluralized snake_case of name)}
                            {--label= : The display label for the module (default: headline of name)}';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'Generate full architectural scaffolding (Model, Migration, SqlRepository, Service, ApiController, WebController, Blade Views: List, List Ajax, Add, Edit, Detail, and append routes)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $rawName = $this->argument('name');
        $entity = Str::studly(class_basename($rawName));
        $moduleKey = Str::snake($entity);
        $pluralKey = Str::plural($moduleKey);

        // Custom table or default to pluralKey
        $customTable = $this->option('table');
        $tableName = $customTable ? Str::snake($customTable) : $pluralKey;

        // Custom label or default to headline of entity
        $customLabel = $this->option('label');
        $titleName = $customLabel ? trim($customLabel) : Str::headline($entity);
        $pluralTitle = Str::plural($titleName);

        $this->info("Scaffolding architectural module for: [{$entity}]");
        $this->line("  - Entity / Class : {$entity}");
        $this->line("  - Table Name     : {$tableName}");
        $this->line("  - Module Label   : {$titleName} ({$pluralTitle})");
        $this->line("  - Route / Views  : {$pluralKey}");

        // 1. Model
        $this->generateModel($entity, $tableName);

        // 2. Migration
        $this->generateMigration($tableName, $entity);

        // 3. Repository
        $this->generateRepository($entity);

        // 4. Service
        $this->generateService($entity, $pluralTitle);

        // 5. Api Controller
        $this->generateApiController($entity, $moduleKey);

        // 6. Web Controller
        $this->generateWebController($entity, $moduleKey, $pluralKey, $titleName, $pluralTitle);

        // 7. Blade Views (List Client, List Ajax, Add, Edit, Detail)
        $this->generateViews($pluralKey, $titleName, $pluralTitle, $tableName);

        // 8. Lang files
        $this->generateLangFiles($moduleKey, $titleName, $pluralTitle);

        // 9. Append Routes before /*==new route==*/
        $this->appendWebRoute($entity, $pluralKey);
        $this->appendApiRoute($entity, $pluralKey);

        $this->newLine();
        $this->info("Module [{$entity}] generated successfully!");
        $this->comment("All Model, Migration, Repository, Service, Controllers, 5 Blade Views, and Web/API routes have been created and registered.");

        return Command::SUCCESS;
    }

    protected function generateModel(string $entity, string $tableName): void
    {
        $path = app_path("Models/{$entity}.php");
        if (File::exists($path)) {
            $this->warn("Model {$entity}.php already exists. Skipping.");
            return;
        }

        $content = <<<PHP
<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class {$entity} extends Model
{
    use HasFactory, HasUuids;

    protected \$table = '{$tableName}';
    protected \$keyType = 'string';
    public \$incrementing = false;

    protected \$fillable = [
        'id',
        'name',
        'description',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * User who created the record.
     */
    public function creator()
    {
        return \$this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * User who last updated the record.
     */
    public function updater()
    {
        return \$this->belongsTo(User::class, 'updated_by', 'id');
    }
}
PHP;

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $content);
        $this->info("Created: app/Models/{$entity}.php");
    }

    protected function generateMigration(string $tableName, string $entity): void
    {
        $timestamp = date('Y_m_d_His');
        $fileName = "{$timestamp}_create_{$tableName}_table.php";
        $path = database_path("migrations/{$fileName}");

        $content = <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('{$tableName}')) {
            Schema::create('{$tableName}', function (Blueprint \$table) {
                \$table->uuid('id')->primary();
                \$table->string('name');
                \$table->text('description')->nullable();
                \$table->boolean('is_active')->default(true);
                \$table->uuid('created_by')->nullable();
                \$table->uuid('updated_by')->nullable();
                \$table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('{$tableName}');
    }
};
PHP;

        File::put($path, $content);
        $this->info("Created: database/migrations/{$fileName}");
    }

    protected function generateRepository(string $entity): void
    {
        $path = app_path("Repositories/Sql/Sql{$entity}Repository.php");
        if (File::exists($path)) {
            $this->warn("Repository Sql{$entity}Repository.php already exists. Skipping.");
            return;
        }

        $content = <<<PHP
<?php

namespace App\Repositories\Sql;

use App\Models\\{$entity};

class Sql{$entity}Repository extends SqlBaseRepository
{
    public function __construct({$entity} \$model)
    {
        parent::__construct(\$model);
    }

    /**
     * Find record by ID with creator and updater relationships.
     *
     * @param string|int \$id
     * @return {$entity}|null
     */
    public function find(string|int \$id): ?\Illuminate\Database\Eloquent\Model
    {
        return \$this->model->newQuery()
            ->with(['creator', 'updater'])
            ->where('id', \$id)
            ->where('is_active', true)
            ->first();
    }
}
PHP;

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $content);
        $this->info("Created: app/Repositories/Sql/Sql{$entity}Repository.php");
    }

    protected function generateService(string $entity, string $pluralTitle): void
    {
        $path = app_path("Services/{$entity}Service.php");
        if (File::exists($path)) {
            $this->warn("Service {$entity}Service.php already exists. Skipping.");
            return;
        }

        $moduleCode = strtolower(Str::snake($entity));

        $content = <<<PHP
<?php

namespace App\Services;

use App\Repositories\Sql\Sql{$entity}Repository;

class {$entity}Service extends BaseService
{
    protected Sql{$entity}Repository \${$entity}Repository;

    public function __construct(Sql{$entity}Repository \${$entity}Repository)
    {
        parent::__construct(\${$entity}Repository);
        \$this->{$entity}Repository = \${$entity}Repository;
    }

    public function findModuleCode(): string
    {
        return '{$moduleCode}';
    }

    public function findModuleName(): string
    {
        return '{$pluralTitle}';
    }
}
PHP;

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $content);
        $this->info("Created: app/Services/{$entity}Service.php");
    }

    protected function generateApiController(string $entity, string $moduleKey): void
    {
        $path = app_path("Http/Controllers/Api/{$entity}Controller.php");
        if (File::exists($path)) {
            $this->warn("API Controller Api/{$entity}Controller.php already exists. Skipping.");
            return;
        }

        $serviceVar = lcfirst($entity) . 'Service';

        $content = <<<PHP
<?php

namespace App\Http\Controllers\Api;

use App\Services\\{$entity}Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class {$entity}Controller extends BaseController
{
    protected {$entity}Service \${$serviceVar};

    public function __construct({$entity}Service \${$serviceVar})
    {
        \$this->{$serviceVar} = \${$serviceVar};
    }

    public function index(): JsonResponse
    {
        \$res = \$this->{$serviceVar}->all();
        return \$this->sendResponse(\$res['message'], \$res['data']);
    }

    public function show(string \$id): JsonResponse
    {
        \$res = \$this->{$serviceVar}->find(\$id);

        if (!\$res['success']) {
            return \$this->sendError(\$res['message'], [], 404);
        }

        return \$this->sendResponse(\$res['message'], \$res['data']);
    }

    public function store(Request \$request): JsonResponse
    {
        \$validator = Validator::make(\$request->all(), [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if (\$validator->fails()) {
            return \$this->sendError('Validation error.', \$validator->errors()->toArray(), 422);
        }

        \$res = \$this->{$serviceVar}->create(\$validator->validated());
        return \$this->sendResponse(\$res['message'], \$res['data'], 201);
    }

    public function update(Request \$request, string \$id): JsonResponse
    {
        \$validator = Validator::make(\$request->all(), [
            'name'        => 'sometimes|string|max:255',
            'description' => 'nullable|string',
        ]);

        if (\$validator->fails()) {
            return \$this->sendError('Validation error.', \$validator->errors()->toArray(), 422);
        }

        \$res = \$this->{$serviceVar}->update(\$id, \$validator->validated());

        if (!\$res['success']) {
            return \$this->sendError(\$res['message'], [], 404);
        }

        return \$this->sendResponse(\$res['message'], \$res['data']);
    }

    public function destroy(string \$id): JsonResponse
    {
        \$res = \$this->{$serviceVar}->delete(\$id);

        if (!\$res['success']) {
            return \$this->sendError(\$res['message'], [], 404);
        }

        return \$this->sendResponse(\$res['message']);
    }
}
PHP;

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $content);
        $this->info("Created: app/Http/Controllers/Api/{$entity}Controller.php");
    }

    protected function generateWebController(
        string $entity,
        string $moduleKey,
        string $pluralKey,
        string $titleName,
        string $pluralTitle
    ): void {
        $path = app_path("Http/Controllers/Web/{$entity}Controller.php");
        if (File::exists($path)) {
            $this->warn("Web Controller Web/{$entity}Controller.php already exists. Skipping.");
            return;
        }

        $serviceVar = lcfirst($entity) . 'Service';

        $content = <<<PHP
<?php

namespace App\Http\Controllers\Web;

use App\Facades\ModuleAccess;
use App\Http\Controllers\Controller;
use App\Services\\{$entity}Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class {$entity}Controller extends Controller
{
    protected {$entity}Service \${$serviceVar};

    public function __construct({$entity}Service \${$serviceVar})
    {
        \$this->{$serviceVar} = \${$serviceVar};
    }

    /**
     * Display client-side datatable list view.
     */
    public function index(Request \$request): View
    {
        if (!ModuleAccess::can(\$this->{$serviceVar}->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        \$result = \$this->{$serviceVar}->all();

        return view('admin.sections.{$pluralKey}.{$pluralKey}_list', [
            'items'       => \$result['data'],
            'moduleName'  => \$this->{$serviceVar}->findModuleName(),
            'moduleCode'  => \$this->{$serviceVar}->findModuleCode(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => '{$pluralTitle}', 'url' => route('admin.{$pluralKey}.index')],
            ],
        ]);
    }

    /**
     * Display server-side ajax datatable list view.
     */
    public function listAjaxView(Request \$request): View
    {
        if (!ModuleAccess::can(\$this->{$serviceVar}->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        return view('admin.sections.{$pluralKey}.{$pluralKey}_list_ajax', [
            'moduleName'  => \$this->{$serviceVar}->findModuleName(),
            'moduleCode'  => \$this->{$serviceVar}->findModuleCode(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => '{$pluralTitle}', 'url' => route('admin.{$pluralKey}.index')],
                ['name' => 'Server-Side List', 'url' => route('admin.{$pluralKey}.list')],
            ],
            'ajaxEndpoint' => route('admin.{$pluralKey}.list.data'),
        ]);
    }

    /**
     * Handle Server-Side DataTable Ajax JSON request.
     */
    public function listAjaxData(Request \$request): JsonResponse
    {
        if (!ModuleAccess::can(\$this->{$serviceVar}->findModuleCode(), 'can_read')) {
            return response()->json(['message' => 'Unauthorized module access.'], 403);
        }

        \$perPage = (int) \$request->input('per_page', 15);
        \$search = \$request->input('search');
        \$orderBy = \$request->input('order_by', 'created_at');
        \$orderDir = strtolower(\$request->input('order_dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        \$allowedSort = ['name', 'created_at', 'is_active'];
        if (!in_array(\$orderBy, \$allowedSort)) {
            \$orderBy = 'created_at';
        }

        \$searchColumns = ['name', 'description'];

        \$res = \$this->{$serviceVar}->paginate(\$perPage, \$search, \$searchColumns, \$orderBy, \$orderDir);
        \$paginator = \$res['data'];

        \$items = collect(\$paginator->items())->map(function (\$item, \$idx) {
            \$initials = strtoupper(substr(\$item->name ?? 'I', 0, 2));
            \$avatarClass = 'ma-' . ((\$idx % 6) + 1);

            \$actionsHtml = '<div class="data-cell-actions" style="justify-content: flex-end;">'
                . btn_detail(route('admin.{$pluralKey}.show', \$item->id), 'View details') . ' '
                . btn_edit(route('admin.{$pluralKey}.edit', \$item->id), 'Edit {$titleName}') . ' '
                . btn_delete(route('admin.{$pluralKey}.destroy', \$item->id), null, 'Delete {$titleName}', null, \$item->name ?? '{$titleName}')
                . '</div>';

            return [
                'id'           => \$item->id,
                'short_id'     => substr(\$item->id, 0, 8) . '…',
                'name'         => \$item->name,
                'description'  => \$item->description ?? '-',
                'is_active'    => (bool) \$item->is_active,
                'status_html'  => \$item->is_active
                    ? '<span class="badge success dot">' . __('label.active') . '</span>'
                    : '<span class="badge dot" style="color:var(--t-light)">' . __('label.inactive') . '</span>',
                'initials'     => \$initials,
                'avatar_class' => \$avatarClass,
                'detail_url'   => route('admin.{$pluralKey}.show', \$item->id),
                'created_at'   => \$item->created_at ? \$item->created_at->format('M d · H:i') : '-',
                'actions'      => \$actionsHtml,
            ];
        });

        return response()->json([
            'draw'            => (int) \$request->input('draw', 1),
            'recordsTotal'    => \$paginator->total(),
            'recordsFiltered' => \$paginator->total(),
            'data'            => \$items,
            'current_page'    => \$paginator->currentPage(),
            'last_page'       => \$paginator->lastPage(),
            'per_page'        => \$paginator->perPage(),
            'from'            => \$paginator->firstItem() ?? 0,
            'to'              => \$paginator->lastItem() ?? 0,
            'total'           => \$paginator->total(),
        ]);
    }

    public function add(): View
    {
        if (!ModuleAccess::can(\$this->{$serviceVar}->findModuleCode(), 'can_create')) {
            abort(403, 'Unauthorized module access.');
        }

        return view('admin.sections.{$pluralKey}.{$pluralKey}_add', [
            'moduleName'  => \$this->{$serviceVar}->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => '{$pluralTitle}', 'url' => route('admin.{$pluralKey}.index')],
                ['name' => 'Add {$titleName}', 'url' => route('admin.{$pluralKey}.add')],
            ],
        ]);
    }

    public function show(string \$id): View
    {
        if (!ModuleAccess::can(\$this->{$serviceVar}->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        \$res = \$this->{$serviceVar}->find(\$id);
        if (!\$res['success']) {
            abort(404, 'Record not found.');
        }

        return view('admin.sections.{$pluralKey}.{$pluralKey}_detail', [
            'item'        => \$res['data'],
            'moduleName'  => \$this->{$serviceVar}->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => '{$pluralTitle}', 'url' => route('admin.{$pluralKey}.index')],
                ['name' => '{$titleName} Details', 'url' => route('admin.{$pluralKey}.show', \$id)],
            ],
        ]);
    }

    public function edit(string \$id): View
    {
        if (!ModuleAccess::can(\$this->{$serviceVar}->findModuleCode(), 'can_update')) {
            abort(403, 'Unauthorized module access.');
        }

        \$res = \$this->{$serviceVar}->find(\$id);
        if (!\$res['success']) {
            abort(404, 'Record not found.');
        }

        return view('admin.sections.{$pluralKey}.{$pluralKey}_edit', [
            'item'        => \$res['data'],
            'moduleName'  => \$this->{$serviceVar}->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => '{$pluralTitle}', 'url' => route('admin.{$pluralKey}.index')],
                ['name' => 'Edit {$titleName}', 'url' => route('admin.{$pluralKey}.edit', \$id)],
            ],
        ]);
    }

    public function store(Request \$request): RedirectResponse
    {
        if (!ModuleAccess::can(\$this->{$serviceVar}->findModuleCode(), 'can_create')) {
            abort(403, 'Unauthorized module access.');
        }

        \$validated = \$request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        \$this->{$serviceVar}->create(\$validated);

        return redirect()->route('admin.{$pluralKey}.index')->with('success', __('message.created_success'));
    }

    public function update(Request \$request, string \$id): RedirectResponse
    {
        if (!ModuleAccess::can(\$this->{$serviceVar}->findModuleCode(), 'can_update')) {
            abort(403, 'Unauthorized module access.');
        }

        \$validated = \$request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        \$this->{$serviceVar}->update(\$id, \$validated);

        return redirect()->route('admin.{$pluralKey}.index')->with('success', __('message.updated_success'));
    }

    public function destroy(string \$id): RedirectResponse
    {
        if (!ModuleAccess::can(\$this->{$serviceVar}->findModuleCode(), 'can_delete')) {
            abort(403, 'Unauthorized module access.');
        }

        \$res = \$this->{$serviceVar}->delete(\$id);
        if (!\$res['success']) {
            return redirect()->route('admin.{$pluralKey}.index')->with('error', \$res['message']);
        }

        return redirect()->route('admin.{$pluralKey}.index')->with('success', __('message.deleted_success'));
    }
}
PHP;

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $content);
        $this->info("Created: app/Http/Controllers/Web/{$entity}Controller.php");
    }

    protected function generateViews(string $pluralKey, string $titleName, string $pluralTitle, ?string $tableName = null): void
    {
        $dir = resource_path("views/admin/sections/{$pluralKey}");
        File::ensureDirectoryExists($dir);

        // Introspect table columns if table exists
        $columns = [];
        if ($tableName && Schema::hasTable($tableName)) {
            $columns = Schema::getColumnListing($tableName);
        }

        // Exclude default fields: id, created_at, created_by, updated_at, updated_by, is_active
        $excluded = ['id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'is_active'];
        $formColumns = array_values(array_filter($columns, fn($col) => !in_array($col, $excluded)));

        if (empty($formColumns)) {
            $formColumns = ['name', 'description'];
        }
        $listFile = "{$dir}/{$pluralKey}_list.blade.php";
        if (!File::exists($listFile)) {
            $listContent = <<<BLADE
@extends('admin.layouts.theme', [
    'title' => '{$pluralTitle}',
    'activeMenu' => '{$pluralKey}',
    'breadcrumbsText' => 'Workspace | {$pluralTitle}'
])

@section('css')
@endsection

@section('content')
<main class="content">
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow">Workspace · Management</span>
            <h1 class="hero-title">{$pluralTitle}</h1>
            <p class="hero-sub">Manage and browse {$pluralTitle} records.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.{$pluralKey}.list') }}" class="btn btn--ghost">
                Switch to Ajax List
            </a>
            <a href="{{ route('admin.{$pluralKey}.add') }}" class="btn btn--primary">
                <svg viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                Add {$titleName}
            </a>
        </div>
    </section>

    @if(session('success'))
        <div class="alert success" style="margin-bottom: 20px;">
            <span class="ico"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></span>
            <div class="body">{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert danger" style="margin-bottom: 20px;">
            <span class="ico"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></span>
            <div class="body">{{ session('error') }}</div>
        </div>
    @endif

    <div class="grid">
        <section class="col-12 card">
            <div class="data-toolbar">
                <div class="data-toolbar-left">
                    <div class="input-icon" style="flex:1;max-width:320px">
                        <span class="ico">
                            <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                        </span>
                        <input id="searchInput" class="input" type="search" placeholder="Search {$pluralTitle}...">
                    </div>
                    <span class="badge primary">{{ count(\$items) }} items</span>
                </div>
                <div class="data-toolbar-right">
                    <span class="badge success dot" style="font-size:12px;padding:6px 12px;">Active Records Only</span>
                </div>
            </div>

            <div style="overflow-x:auto;margin:0 -22px">
                <table class="data-table" id="dataTable" style="margin:0 22px;min-width:900px">
                    <thead>
                        <tr>
                            <th style="width:32px">
                                <label class="check"><input type="checkbox" id="selectAll"><span class="box"></span></label>
                            </th>
                            <th>{{ __('label.name') }}</th>
                            <th>Description</th>
                            <th style="text-align:right">{{ __('label.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(\$items as \$index => \$item)
                            @php
                                \$initials = strtoupper(substr(\$item->name ?? 'I', 0, 2));
                                \$avatarClass = 'ma-' . ((\$index % 6) + 1);
                            @endphp
                            <tr class="data-row" data-name="{{ strtolower(\$item->name) }}">
                                <td>
                                    <label class="check"><input type="checkbox" class="row-checkbox"><span class="box"></span></label>
                                </td>
                                <td>
                                    <div class="data-cell-user">
                                        <div class="av {{ \$avatarClass }}">{{ \$initials }}</div>
                                        <div class="data-cell-user-meta">
                                            <div class="data-cell-user-name">
                                                <a href="{{ route('admin.{$pluralKey}.show', \$item->id) }}" style="color:inherit;text-decoration:none;font-weight:600;">
                                                    {{ \$item->name }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ \$item->description ?? '-' }}</td>
                                <td>
                                    <div class="data-cell-actions" style="justify-content: flex-end;">
                                        {{ btn_detail(route('admin.{$pluralKey}.show', \$item->id), 'View details') }}
                                        {{ btn_edit(route('admin.{$pluralKey}.edit', \$item->id), 'Edit {$titleName}') }}
                                        {{ btn_delete(route('admin.{$pluralKey}.destroy', \$item->id), null, 'Delete {$titleName}', null, \$item->name ?? '{$titleName}') }}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align:center;padding:32px;color:var(--t-light);">
                                    {{ __('message.not_found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="data-foot">
                <div class="data-foot-info">
                    <span>Showing <strong style="color:var(--t-base)">{{ count(\$items) }}</strong> entries</span>
                </div>
            </div>
        </section>
    </div>
</main>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('#dataTable tbody tr.data-row');
    const selectAllCheckbox = document.getElementById('selectAll');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const query = (this.value || '').toLowerCase().trim();
            tableRows.forEach(row => {
                const name = row.getAttribute('data-name') || '';
                row.style.display = (!query || name.includes(query)) ? '' : 'none';
            });
        });
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            rowCheckboxes.forEach(cb => {
                cb.checked = selectAllCheckbox.checked;
                const row = cb.closest('tr');
                if (row) row.classList.toggle('is-selected', selectAllCheckbox.checked);
            });
        });
    }

    rowCheckboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            const row = cb.closest('tr');
            if (row) row.classList.toggle('is-selected', cb.checked);
        });
    });
});
</script>
@endsection
BLADE;
            File::put($listFile, $listContent);
            $this->info("Created: resources/views/admin/sections/{$pluralKey}/{$pluralKey}_list.blade.php");
        }

        // 2. List Ajax View (Server-side)
        $listAjaxFile = "{$dir}/{$pluralKey}_list_ajax.blade.php";
        if (!File::exists($listAjaxFile)) {
            $listAjaxContent = <<<BLADE
@extends('admin.layouts.theme', [
    'title' => '{$pluralTitle} (Server-Side Ajax)',
    'activeMenu' => '{$pluralKey}',
    'breadcrumbsText' => 'Workspace | {$pluralTitle} | Server-Side Ajax'
])

@section('css')
@endsection

@section('content')
<main class="content">
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow">Workspace · Server-Side DataTable</span>
            <h1 class="hero-title">{$pluralTitle} <span class="badge solid primary" style="font-size:12px;vertical-align:middle;margin-left:8px;">AJAX</span></h1>
            <p class="hero-sub">Dynamic server-side pagination, search, and sorting powered by asynchronous JSON API.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.{$pluralKey}.index') }}" class="btn btn--ghost">
                Standard View
            </a>
            <a href="{{ route('admin.{$pluralKey}.add') }}" class="btn btn--primary">
                <svg viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                Add {$titleName}
            </a>
        </div>
    </section>

    @if(session('success'))
        <div class="alert success" style="margin-bottom: 20px;">
            <span class="ico"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></span>
            <div class="body">{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert danger" style="margin-bottom: 20px;">
            <span class="ico"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></span>
            <div class="body">{{ session('error') }}</div>
        </div>
    @endif

    <div class="grid">
        <section class="col-12 card">
            <div class="data-toolbar">
                <div class="data-toolbar-left">
                    <div class="input-icon" style="flex:1;max-width:320px">
                        <span class="ico">
                            <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                        </span>
                        <input id="ajaxSearchInput" class="input" type="search" placeholder="Search {$pluralTitle}...">
                    </div>
                    <span class="badge primary" id="ajaxTotalBadge">Loading…</span>
                    <span id="ajaxLoadingSpinner" style="display:none;font-size:12px;color:var(--t-light);">
                        Fetching…
                    </span>
                </div>
                <div class="data-toolbar-right">
                    <span class="badge success dot" style="font-size:12px;padding:6px 12px;">Active Records Only</span>

                    <button id="ajaxRefreshBtn" class="btn btn--ghost btn--icon" title="Refresh data" aria-label="Refresh">
                        <svg viewBox="0 0 24 24"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                    </button>
                </div>
            </div>

            <div style="overflow-x:auto;margin:0 -22px">
                <table class="data-table" id="ajaxTable" style="margin:0 22px;min-width:900px">
                    <thead>
                        <tr>
                            <th style="width:32px">
                                <label class="check"><input type="checkbox" id="ajaxSelectAll"><span class="box"></span></label>
                            </th>
                            <th class="is-sortable" data-sort="name" style="cursor:pointer;">
                                {{ __('label.name') }} <span class="sort"><svg viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg></span>
                            </th>
                            <th>Description</th>
                            <th style="text-align:right">{{ __('label.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="ajaxTableBody">
                        <tr>
                            <td colspan="4" style="text-align:center;padding:32px;color:var(--t-light);">
                                Loading data from server…
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="data-foot">
                <div class="data-foot-info">
                    <span id="ajaxPagingInfo">Showing <strong style="color:var(--t-base)">0–0</strong> of <strong style="color:var(--t-base)">0</strong></span>
                    <select id="ajaxPerPageSelect" class="select" style="width:auto;padding:5px 24px 5px 8px;font-size:12px">
                        <option value="10">10 per page</option>
                        <option value="15" selected>15 per page</option>
                        <option value="25">25 per page</option>
                        <option value="50">50 per page</option>
                    </select>
                </div>
                <div class="pager" id="ajaxPaginationNav">
                </div>
            </div>
        </section>
    </div>
</main>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const endpoint = "{!\$ajaxEndpoint!}";
    const tbody = document.getElementById('ajaxTableBody');
    const searchInput = document.getElementById('ajaxSearchInput');
    const perPageSelect = document.getElementById('ajaxPerPageSelect');
    const totalBadge = document.getElementById('ajaxTotalBadge');
    const pagingInfo = document.getElementById('ajaxPagingInfo');
    const paginationNav = document.getElementById('ajaxPaginationNav');
    const spinner = document.getElementById('ajaxLoadingSpinner');
    const refreshBtn = document.getElementById('ajaxRefreshBtn');
    const selectAllCheckbox = document.getElementById('ajaxSelectAll');

    let state = {
        page: 1,
        per_page: 15,
        search: '',
        order_by: 'created_at',
        order_dir: 'desc'
    };

    let debounceTimer = null;

    function fetchTableData() {
        if (spinner) spinner.style.display = 'inline';

        const params = new URLSearchParams({
            page: state.page,
            per_page: state.per_page,
            search: state.search,
            order_by: state.order_by,
            order_dir: state.order_dir
        });

        fetch(`\${endpoint}?\${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => {
            if (!res.ok) throw new Error('Network error ' + res.status);
            return res.json();
        })
        .then(json => {
            renderTable(json);
            renderPagination(json);
            if (spinner) spinner.style.display = 'none';
        })
        .catch(err => {
            console.error('Ajax Table Error:', err);
            tbody.innerHTML = `<tr><td colspan="4" style="text-align:center;padding:32px;color:var(--danger,#ef4444);">Failed to load data from server.</td></tr>`;
            if (spinner) spinner.style.display = 'none';
        });
    }

    function renderTable(response) {
        const rows = response.data || [];
        totalBadge.textContent = `\${response.total} items`;

        if (response.total === 0 || rows.length === 0) {
            tbody.innerHTML = `<tr><td colspan="4" style="text-align:center;padding:32px;color:var(--t-light);">No records found.</td></tr>`;
            pagingInfo.innerHTML = `Showing <strong style="color:var(--t-base)">0</strong> of <strong style="color:var(--t-base)">0</strong>`;
            return;
        }

        pagingInfo.innerHTML = `Showing <strong style="color:var(--t-base)">\${response.from}–\${response.to}</strong> of <strong style="color:var(--t-base)">\${response.total}</strong>`;

        let html = '';
        rows.forEach(item => {
            html += `
                <tr class="data-row">
                    <td>
                        <label class="check"><input type="checkbox" class="ajax-row-check" value="\${item.id}"><span class="box"></span></label>
                    </td>
                    <td>
                        <div class="data-cell-user">
                            <div class="av \${item.avatar_class}">\${item.initials}</div>
                            <div class="data-cell-user-meta">
                                <div class="data-cell-user-name">
                                    <a href="\${item.detail_url}" style="color:inherit;text-decoration:none;font-weight:600;">
                                        \${item.name}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>\${item.description}</td>
                    <td>\${item.actions}</td>
                </tr>
            `;
        });

        tbody.innerHTML = html;

        const rowChecks = tbody.querySelectorAll('.ajax-row-check');
        rowChecks.forEach(cb => {
            cb.addEventListener('change', () => {
                const tr = cb.closest('tr');
                if (tr) tr.classList.toggle('is-selected', cb.checked);
            });
        });
    }

    function renderPagination(response) {
        const currentPage = response.current_page;
        const lastPage = response.last_page;

        if (lastPage <= 1) {
            paginationNav.innerHTML = '';
            return;
        }

        let navHtml = '';
        const prevDisabled = currentPage <= 1 ? 'disabled="disabled"' : '';
        navHtml += `<button class="pager-btn" \${prevDisabled} data-page="\${currentPage - 1}" aria-label="Previous">
            <svg viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
        </button>`;

        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(lastPage, currentPage + 2);

        if (startPage > 1) {
            navHtml += `<button class="pager-btn" data-page="1">1</button>`;
            if (startPage > 2) navHtml += `<button class="pager-btn" disabled>…</button>`;
        }

        for (let p = startPage; p <= endPage; p++) {
            const activeClass = p === currentPage ? 'is-active' : '';
            navHtml += `<button class="pager-btn \${activeClass}" data-page="\${p}">\${p}</button>`;
        }

        if (endPage < lastPage) {
            if (endPage < lastPage - 1) navHtml += `<button class="pager-btn" disabled>…</button>`;
            navHtml += `<button class="pager-btn" data-page="\${lastPage}">\${lastPage}</button>`;
        }

        const nextDisabled = currentPage >= lastPage ? 'disabled="disabled"' : '';
        navHtml += `<button class="pager-btn" \${nextDisabled} data-page="\${currentPage + 1}" aria-label="Next">
            <svg viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></svg>
        </button>`;

        paginationNav.innerHTML = navHtml;

        paginationNav.querySelectorAll('.pager-btn:not([disabled])').forEach(btn => {
            btn.addEventListener('click', () => {
                const targetPage = parseInt(btn.getAttribute('data-page'));
                if (targetPage && targetPage !== state.page) {
                    state.page = targetPage;
                    fetchTableData();
                }
            });
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                state.search = searchInput.value.trim();
                state.page = 1;
                fetchTableData();
            }, 300);
        });
    }

    if (perPageSelect) {
        perPageSelect.addEventListener('change', () => {
            state.per_page = parseInt(perPageSelect.value);
            state.page = 1;
            fetchTableData();
        });
    }

    if (refreshBtn) {
        refreshBtn.addEventListener('click', () => {
            fetchTableData();
        });
    }

    document.querySelectorAll('#ajaxTable th.is-sortable').forEach(th => {
        th.addEventListener('click', () => {
            const field = th.getAttribute('data-sort');
            if (state.order_by === field) {
                state.order_dir = state.order_dir === 'asc' ? 'desc' : 'asc';
            } else {
                state.order_by = field;
                state.order_dir = 'asc';
            }

            document.querySelectorAll('#ajaxTable th.is-sortable').forEach(col => {
                col.classList.remove('sorted-asc', 'sorted-desc');
            });
            th.classList.add(state.order_dir === 'asc' ? 'sorted-asc' : 'sorted-desc');

            state.page = 1;
            fetchTableData();
        });
    });

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', () => {
            const checks = tbody.querySelectorAll('.ajax-row-check');
            checks.forEach(c => {
                c.checked = selectAllCheckbox.checked;
                const tr = c.closest('tr');
                if (tr) tr.classList.toggle('is-selected', selectAllCheckbox.checked);
            });
        });
    }

    fetchTableData();
});
</script>
@endsection
BLADE;
            File::put($listAjaxFile, $listAjaxContent);
            $this->info("Created: resources/views/admin/sections/{$pluralKey}/{$pluralKey}_list_ajax.blade.php");
        }

        // Build Add form fields
        $addFieldsHtml = "";
        foreach ($formColumns as $col) {
            $colLabel = Str::headline($col);
            $spanClass = ($col === 'description' || count($formColumns) === 1) ? ' span-2' : '';
            if ($col === 'description') {
                $addFieldsHtml .= <<<HTML

                    <div class="field{$spanClass}">
                        <label class="field-label" for="{$col}">{$colLabel}</label>
                        <textarea id="{$col}" name="{$col}" class="textarea @error('{$col}') is-invalid @enderror" placeholder="Optional details...">{{ old('{$col}') }}</textarea>
                        @error('{$col}')
                            <div class="field-error">{{ \$message }}</div>
                        @enderror
                    </div>
HTML;
            } else {
                $addFieldsHtml .= <<<HTML

                    <div class="field{$spanClass}">
                        <label class="field-label" for="{$col}">
                            {$colLabel} <span class="req">*</span>
                        </label>
                        <input id="{$col}" name="{$col}" class="input @error('{$col}') is-invalid @enderror" type="text" placeholder="Enter {$colLabel}" value="{{ old('{$col}') }}" required>
                        @error('{$col}')
                            <div class="field-error">{{ \$message }}</div>
                        @enderror
                    </div>
HTML;
            }
        }

        // 3. Add View
        $addFile = "{$dir}/{$pluralKey}_add.blade.php";
        if (!File::exists($addFile)) {
            $addContent = <<<BLADE
@extends('admin.layouts.theme', [
    'title' => 'Add {$titleName}',
    'activeMenu' => '{$pluralKey}',
    'breadcrumbsText' => 'Workspace | {$pluralTitle} | Add {$titleName}'
])

@section('css')
@endsection

@section('content')
<main class="content">
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow">Workspace · {$pluralTitle}</span>
            <h1 class="hero-title">Add {$titleName}</h1>
            <p class="hero-sub">Create a new record in {$pluralTitle}.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.{$pluralKey}.index') }}" class="btn btn--ghost">
                {{ __('form.back') }}
            </a>
            <button type="submit" form="addForm" class="btn btn--primary">
                {{ __('form.submit') }}
            </button>
        </div>
    </section>

    @if (\$errors->any())
        <div class="alert danger" style="margin-bottom: 20px;">
            <span class="ico"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></span>
            <div class="body">
                <ul style="margin: 0; padding-left: 18px;">
                    @foreach (\$errors->all() as \$error)
                        <li>{{ \$error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="grid">
        <section class="col-12 card">
            <div class="card-head">
                <div class="card-title-wrap">
                    <span class="eyebrow">Information</span>
                    <h2 class="card-title">{$titleName} details</h2>
                </div>
            </div>

            <form id="addForm" action="{{ route('admin.{$pluralKey}.store') }}" method="POST">
                @csrf
                <div class="form-grid">{$addFieldsHtml}
                </div>

                <div class="form-actions" style="margin-top: 24px;">
                    <a href="{{ route('admin.{$pluralKey}.index') }}" class="btn btn--ghost">{{ __('form.back') }}</a>
                    <span class="spacer"></span>
                    <button type="submit" class="btn btn--primary">{{ __('form.submit') }}</button>
                </div>
            </form>
        </section>
    </div>
</main>
@endsection

@section('js')
@endsection
BLADE;
            File::put($addFile, $addContent);
            $this->info("Created: resources/views/admin/sections/{$pluralKey}/{$pluralKey}_add.blade.php");
        }

        // Build Edit form fields
        $editFieldsHtml = "";
        foreach ($formColumns as $col) {
            $colLabel = Str::headline($col);
            $spanClass = ($col === 'description' || count($formColumns) === 1) ? ' span-2' : '';
            if ($col === 'description') {
                $editFieldsHtml .= <<<HTML

                    <div class="field{$spanClass}">
                        <label class="field-label" for="{$col}">{$colLabel}</label>
                        <textarea id="{$col}" name="{$col}" class="textarea @error('{$col}') is-invalid @enderror">{{ old('{$col}', \$item->{$col}) }}</textarea>
                        @error('{$col}')
                            <div class="field-error">{{ \$message }}</div>
                        @enderror
                    </div>
HTML;
            } else {
                $editFieldsHtml .= <<<HTML

                    <div class="field{$spanClass}">
                        <label class="field-label" for="{$col}">
                            {$colLabel} <span class="req">*</span>
                        </label>
                        <input id="{$col}" name="{$col}" class="input @error('{$col}') is-invalid @enderror" type="text" value="{{ old('{$col}', \$item->{$col}) }}" required>
                        @error('{$col}')
                            <div class="field-error">{{ \$message }}</div>
                        @enderror
                    </div>
HTML;
            }
        }

        // 4. Edit View
        $editFile = "{$dir}/{$pluralKey}_edit.blade.php";
        if (!File::exists($editFile)) {
            $editContent = <<<BLADE
@extends('admin.layouts.theme', [
    'title' => 'Edit {$titleName}',
    'activeMenu' => '{$pluralKey}',
    'breadcrumbsText' => 'Workspace | {$pluralTitle} | Edit {$titleName}'
])

@section('css')
@endsection

@section('content')
<main class="content">
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow">Workspace · {$pluralTitle}</span>
            <h1 class="hero-title">Edit {$titleName}</h1>
            <p class="hero-sub">Update record details for <strong>{{ \$item->name ?? 'Record' }}</strong>.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.{$pluralKey}.index') }}" class="btn btn--ghost">
                {{ __('form.back') }}
            </a>
            <button type="submit" form="editForm" class="btn btn--primary">
                {{ __('form.submit') }}
            </button>
        </div>
    </section>

    @if (\$errors->any())
        <div class="alert danger" style="margin-bottom: 20px;">
            <span class="ico"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></span>
            <div class="body">
                <ul style="margin: 0; padding-left: 18px;">
                    @foreach (\$errors->all() as \$error)
                        <li>{{ \$error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="grid">
        <section class="col-12 card">
            <div class="card-head">
                <div class="card-title-wrap">
                    <span class="eyebrow">Information</span>
                    <h2 class="card-title">Edit {$titleName}</h2>
                </div>
                <span class="badge {{ \$item->is_active ? 'success dot' : 'dot' }}">
                    {{ \$item->is_active ? __('label.active') : __('label.inactive') }}
                </span>
            </div>

            <form id="editForm" action="{{ route('admin.{$pluralKey}.update', \$item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-grid">{$editFieldsHtml}
                </div>

                <div class="form-actions" style="margin-top: 24px;">
                    <a href="{{ route('admin.{$pluralKey}.index') }}" class="btn btn--ghost">{{ __('form.back') }}</a>
                    <span class="spacer"></span>
                    <button type="submit" class="btn btn--primary">{{ __('form.submit') }}</button>
                </div>
            </form>
        </section>
    </div>
</main>
@endsection

@section('js')
@endsection
BLADE;
            File::put($editFile, $editContent);
            $this->info("Created: resources/views/admin/sections/{$pluralKey}/{$pluralKey}_edit.blade.php");
        }

        // Build Detail fields
        $detailFieldsHtml = "";
        foreach ($formColumns as $col) {
            $colLabel = Str::headline($col);
            $spanClass = ($col === 'description' || count($formColumns) === 1) ? ' span-2' : '';
            if ($col === 'description') {
                $detailFieldsHtml .= <<<HTML

                <div class="field{$spanClass}">
                    <label class="field-label">{$colLabel}</label>
                    <textarea class="textarea" readonly disabled>{{ \$item->{$col} ?? '-' }}</textarea>
                </div>
HTML;
            } else {
                $detailFieldsHtml .= <<<HTML

                <div class="field{$spanClass}">
                    <label class="field-label">{$colLabel}</label>
                    <input class="input" type="text" value="{{ \$item->{$col} }}" readonly disabled>
                </div>
HTML;
            }
        }

        // 5. Detail View
        $detailFile = "{$dir}/{$pluralKey}_detail.blade.php";
        if (!File::exists($detailFile)) {
            $detailContent = <<<BLADE
@extends('admin.layouts.theme', [
    'title' => '{$titleName} Details',
    'activeMenu' => '{$pluralKey}',
    'breadcrumbsText' => 'Workspace | {$pluralTitle} | Details'
])

@section('css')
@endsection

@section('content')
<main class="content">
    <section class="hero">
        <div class="hero-text">
            <span class="eyebrow">Workspace · {$pluralTitle}</span>
            <h1 class="hero-title">{{ \$item->name ?? 'Record' }}</h1>
            <p class="hero-sub">Complete details and metadata overview.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('admin.{$pluralKey}.index') }}" class="btn btn--ghost">
                {{ __('form.back') }}
            </a>
            <a href="{{ route('admin.{$pluralKey}.edit', \$item->id) }}" class="btn btn--primary">
                <svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 1 1 3 3L7 19l-4 1 1-4z"/></svg>
                Edit {$titleName}
            </a>
        </div>
    </section>

    <div class="grid">
        <section class="col-12 card">
            <div class="card-head">
                <div class="card-title-wrap">
                    <span class="eyebrow">Identity Card</span>
                    <h2 class="card-title">{$titleName} overview</h2>
                </div>
                @if(\$item->is_active)
                    <span class="badge success dot">{{ __('label.active') }}</span>
                @else
                    <span class="badge dot" style="color:var(--t-light)">{{ __('label.inactive') }}</span>
                @endif
            </div>

            <div class="form-grid">{$detailFieldsHtml}

                <!-- Audit & System Meta Separator -->
                <div class="field span-2" style="margin-top: 10px; padding-top: 16px; border-top: 1px dashed var(--border, #e2e8f0);">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span class="eyebrow" style="color: var(--t-light, #64748b);">Audit & System Information</span>
                    </div>
                </div>

                <div class="field">
                    <label class="field-label">{{ __('label.created_at') }}</label>
                    <input class="input" type="text" value="{{ \$item->created_at ? \$item->created_at->format('Y-m-d H:i:s') : '-' }}" readonly disabled>
                </div>

                <div class="field">
                    <label class="field-label">Created By</label>
                    <input class="input" type="text" value="{{ \$item->creator ? (\$item->creator->name . (\$item->creator->username ? ' (@' . \$item->creator->username . ')' : '')) : (\$item->created_by ?? '-') }}" readonly disabled>
                </div>

                <div class="field">
                    <label class="field-label">Updated At</label>
                    <input class="input" type="text" value="{{ \$item->updated_at ? \$item->updated_at->format('Y-m-d H:i:s') : '-' }}" readonly disabled>
                </div>

                <div class="field">
                    <label class="field-label">Updated By</label>
                    <input class="input" type="text" value="{{ \$item->updater ? (\$item->updater->name . (\$item->updater->username ? ' (@' . \$item->updater->username . ')' : '')) : (\$item->updated_by ?? '-') }}" readonly disabled>
                </div>
            </div>

            <div class="form-actions" style="margin-top: 24px;">
                <a href="{{ route('admin.{$pluralKey}.index') }}" class="btn btn--ghost">{{ __('form.back') }}</a>
                <span class="spacer"></span>
                <a href="{{ route('admin.{$pluralKey}.edit', \$item->id) }}" class="btn btn--primary">Edit {$titleName}</a>
                <form action="{{ route('admin.{$pluralKey}.destroy', \$item->id) }}" method="POST" class="form-delete-swal" data-record-name="{{ \$item->name ?? '{$titleName}' }}" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn--ghost" style="color:var(--danger, #ef4444); border-color: rgba(239, 68, 68, 0.2);">
                        Delete {$titleName}
                    </button>
                </form>
            </div>
        </section>
    </div>
</main>
@endsection

@section('js')
@endsection
BLADE;
            File::put($detailFile, $detailContent);
            $this->info("Created: resources/views/admin/sections/{$pluralKey}/{$pluralKey}_detail.blade.php");
        }
    }

    protected function generateLangFiles(string $moduleKey, string $titleName, string $pluralTitle): void
    {
        $this->appendLangKey(base_path('lang/en/label.php'), $moduleKey, $titleName);
        $this->appendLangKey(base_path('lang/id/label.php'), $moduleKey, $titleName);
    }

    protected function appendLangKey(string $filePath, string $key, string $value): void
    {
        if (!File::exists($filePath)) {
            return;
        }

        $content = File::get($filePath);
        if (str_contains($content, "'{$key}'")) {
            return;
        }

        $replacement = "    '{$key}' => '{$value}',\n];";
        $newContent = preg_replace('/\];\s*$/', $replacement, $content);
        if ($newContent) {
            File::put($filePath, $newContent);
        }
    }

    protected function appendWebRoute(string $entity, string $pluralKey): void
    {
        $path = base_path('routes/web.php');
        if (!File::exists($path)) {
            return;
        }

        $content = File::get($path);
        if (str_contains($content, "Route::prefix('{$pluralKey}')")) {
            $this->warn("Web route for '{$pluralKey}' already exists in routes/web.php. Skipping.");
            return;
        }

        $routeBlock = <<<PHP
        // {$entity} Module
        Route::prefix('{$pluralKey}')->name('{$pluralKey}.')->group(function () {
            Route::get('/', [\\App\\Http\\Controllers\\Web\\{$entity}Controller::class, 'index'])->name('index');
            Route::get('/list', [\\App\\Http\\Controllers\\Web\\{$entity}Controller::class, 'listAjaxView'])->name('list');
            Route::get('/list/data', [\\App\\Http\\Controllers\\Web\\{$entity}Controller::class, 'listAjaxData'])->name('list.data');
            Route::get('/add', [\\App\\Http\\Controllers\\Web\\{$entity}Controller::class, 'add'])->name('add');
            Route::post('/', [\\App\\Http\\Controllers\\Web\\{$entity}Controller::class, 'store'])->name('store');
            Route::get('/{id}', [\\App\\Http\\Controllers\\Web\\{$entity}Controller::class, 'show'])->name('show');
            Route::get('/{id}/edit', [\\App\\Http\\Controllers\\Web\\{$entity}Controller::class, 'edit'])->name('edit');
            Route::put('/{id}', [\\App\\Http\\Controllers\\Web\\{$entity}Controller::class, 'update'])->name('update');
            Route::delete('/{id}', [\\App\\Http\\Controllers\\Web\\{$entity}Controller::class, 'destroy'])->name('destroy');
        });

PHP;

        $marker = '/*==new route==*/';
        if (str_contains($content, $marker)) {
            $newContent = str_replace($marker, $routeBlock . "        " . $marker, $content);
            File::put($path, $newContent);
            $this->info("Appended Web routes for [{$pluralKey}] into routes/web.php before '{$marker}'");
        } else {
            $this->warn("Marker '{$marker}' not found in routes/web.php. Skipping route injection.");
        }
    }

    protected function appendApiRoute(string $entity, string $pluralKey): void
    {
        $path = base_path('routes/api.php');
        if (!File::exists($path)) {
            return;
        }

        $content = File::get($path);
        if (str_contains($content, "Route::prefix('{$pluralKey}')")) {
            $this->warn("API route for '{$pluralKey}' already exists in routes/api.php. Skipping.");
            return;
        }

        $routeBlock = <<<PHP
        // {$entity} Resource API
        Route::prefix('{$pluralKey}')->group(function () {
            Route::get('/', [\\App\\Http\\Controllers\\Api\\{$entity}Controller::class, 'index']);
            Route::get('/{id}', [\\App\\Http\\Controllers\\Api\\{$entity}Controller::class, 'show']);
            Route::post('/', [\\App\\Http\\Controllers\\Api\\{$entity}Controller::class, 'store']);
            Route::put('/{id}', [\\App\\Http\\Controllers\\Api\\{$entity}Controller::class, 'update']);
            Route::delete('/{id}', [\\App\\Http\\Controllers\\Api\\{$entity}Controller::class, 'destroy']);
        });

PHP;

        $marker = '/*==new route==*/';
        if (str_contains($content, $marker)) {
            $newContent = str_replace($marker, $routeBlock . "        " . $marker, $content);
            File::put($path, $newContent);
            $this->info("Appended API routes for [{$pluralKey}] into routes/api.php before '{$marker}'");
        } else {
            $this->warn("Marker '{$marker}' not found in routes/api.php. Skipping route injection.");
        }
    }
}
