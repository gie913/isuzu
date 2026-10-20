<?php

namespace App\Http\Controllers\Web;

use App\Facades\ModuleAccess;
use App\Http\Controllers\Controller;
use App\Services\ModuleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ModuleController extends Controller
{
    protected ModuleService $moduleService;

    public function __construct(ModuleService $moduleService)
    {
        $this->moduleService = $moduleService;
    }

    /**
     * Display client-side datatable list view.
     */
    public function index(Request $request): View
    {
        if (!ModuleAccess::can($this->moduleService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        $result = $this->moduleService->all();
        $treeResult = $this->moduleService->getTree();

        return view('admin.sections.modules.modules_list', [
            'items'       => $result['data'],
            'treeItems'   => $treeResult['data'] ?? [],
            'moduleName'  => $this->moduleService->findModuleName(),
            'moduleCode'  => $this->moduleService->findModuleCode(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Modules', 'url' => route('admin.modules.index')],
            ],
        ]);
    }

    /**
     * Display server-side ajax datatable list view.
     */
    public function listAjaxView(Request $request): View
    {
        if (!ModuleAccess::can($this->moduleService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        return view('admin.sections.modules.modules_list_ajax', [
            'moduleName'  => $this->moduleService->findModuleName(),
            'moduleCode'  => $this->moduleService->findModuleCode(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Modules', 'url' => route('admin.modules.index')],
                ['name' => 'Server-Side List', 'url' => route('admin.modules.list')],
            ],
            'ajaxEndpoint' => route('admin.modules.list.data'),
        ]);
    }

    /**
     * Handle Server-Side DataTable Ajax JSON request.
     */
    public function listAjaxData(Request $request): JsonResponse
    {
        if (!ModuleAccess::can($this->moduleService->findModuleCode(), 'can_read')) {
            return response()->json(['message' => 'Unauthorized module access.'], 403);
        }

        $perPage = (int) $request->input('per_page', 15);
        $search = $request->input('search');
        $orderBy = $request->input('order_by', 'created_at');
        $orderDir = strtolower($request->input('order_dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $allowedSort = ['name', 'created_at', 'is_active'];
        if (!in_array($orderBy, $allowedSort)) {
            $orderBy = 'created_at';
        }

        $searchColumns = ['name', 'description'];

        $res = $this->moduleService->paginate($perPage, $search, $searchColumns, $orderBy, $orderDir);
        $paginator = $res['data'];

        $items = collect($paginator->items())->map(function ($item, $idx) {
            $initials = strtoupper(substr($item->name ?? 'I', 0, 2));
            $avatarClass = 'ma-' . (($idx % 6) + 1);

            $actionsHtml = '<div class="data-cell-actions" style="justify-content: flex-end;">'
                . btn_detail(route('admin.modules.show', $item->id), 'View details') . ' '
                . btn_edit(route('admin.modules.edit', $item->id), 'Edit Module') . ' '
                . btn_delete(route('admin.modules.destroy', $item->id), null, 'Delete Module', null, $item->name ?? 'Module')
                . '</div>';

            return [
                'id'           => $item->id,
                'short_id'     => substr($item->id, 0, 8) . '…',
                'name'         => $item->name,
                'description'  => $item->description ?? '-',
                'is_active'    => (bool) $item->is_active,
                'status_html'  => $item->is_active
                    ? '<span class="badge success dot">' . __('label.active') . '</span>'
                    : '<span class="badge dot" style="color:var(--t-light)">' . __('label.inactive') . '</span>',
                'initials'     => $initials,
                'avatar_class' => $avatarClass,
                'detail_url'   => route('admin.modules.show', $item->id),
                'created_at'   => $item->created_at ? $item->created_at->format('M d · H:i') : '-',
                'actions'      => $actionsHtml,
            ];
        });

        return response()->json([
            'draw'            => (int) $request->input('draw', 1),
            'recordsTotal'    => $paginator->total(),
            'recordsFiltered' => $paginator->total(),
            'data'            => $items,
            'current_page'    => $paginator->currentPage(),
            'last_page'       => $paginator->lastPage(),
            'per_page'        => $paginator->perPage(),
            'from'            => $paginator->firstItem() ?? 0,
            'to'              => $paginator->lastItem() ?? 0,
            'total'           => $paginator->total(),
        ]);
    }

    public function add(): View
    {
        if (!ModuleAccess::can($this->moduleService->findModuleCode(), 'can_create')) {
            abort(403, 'Unauthorized module access.');
        }

        $allModules = $this->moduleService->all();

        return view('admin.sections.modules.modules_add', [
            'modules'     => $allModules['data'] ?? [],
            'moduleName'  => $this->moduleService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Modules', 'url' => route('admin.modules.index')],
                ['name' => 'Add Module', 'url' => route('admin.modules.add')],
            ],
        ]);
    }

    public function show(string $id): View
    {
        if (!ModuleAccess::can($this->moduleService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->moduleService->find($id);
        if (!$res['success']) {
            abort(404, 'Record not found.');
        }

        return view('admin.sections.modules.modules_detail', [
            'item'        => $res['data'],
            'moduleName'  => $this->moduleService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Modules', 'url' => route('admin.modules.index')],
                ['name' => 'Module Details', 'url' => route('admin.modules.show', $id)],
            ],
        ]);
    }

    public function edit(string $id): View
    {
        if (!ModuleAccess::can($this->moduleService->findModuleCode(), 'can_update')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->moduleService->find($id);
        if (!$res['success']) {
            abort(404, 'Record not found.');
        }

        $allModules = $this->moduleService->all();

        return view('admin.sections.modules.modules_edit', [
            'item'        => $res['data'],
            'modules'     => $allModules['data'] ?? [],
            'moduleName'  => $this->moduleService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Modules', 'url' => route('admin.modules.index')],
                ['name' => 'Edit Module', 'url' => route('admin.modules.edit', $id)],
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (!ModuleAccess::can($this->moduleService->findModuleCode(), 'can_create')) {
            abort(403, 'Unauthorized module access.');
        }

        $validated = $request->validate([
            'name'           => 'required|string|max:225',
            'label'          => 'required|string|max:225',
            'resource_class' => 'nullable|string',
            'path'           => 'nullable|string',
            'icon'           => 'nullable|string',
            'sort_number'    => 'nullable|numeric',
            'parent_id'      => 'nullable|string|exists:m_module,id',
            'is_menubar'     => 'nullable|boolean',
        ]);

        $validated['is_menubar'] = $request->boolean('is_menubar');

        $this->moduleService->create($validated);

        return redirect()->route('admin.modules.index')->with('success', __('message.created_success'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        if (!ModuleAccess::can($this->moduleService->findModuleCode(), 'can_update')) {
            abort(403, 'Unauthorized module access.');
        }

        $validated = $request->validate([
            'name'           => 'required|string|max:225',
            'label'          => 'required|string|max:225',
            'resource_class' => 'nullable|string',
            'path'           => 'nullable|string',
            'icon'           => 'nullable|string',
            'sort_number'    => 'nullable|numeric',
            'parent_id'      => 'nullable|string|exists:m_module,id',
            'is_menubar'     => 'nullable|boolean',
        ]);

        $validated['is_menubar'] = $request->boolean('is_menubar');

        $this->moduleService->update($id, $validated);

        return redirect()->route('admin.modules.index')->with('success', __('message.updated_success'));
    }

    public function destroy(string $id): RedirectResponse
    {
        if (!ModuleAccess::can($this->moduleService->findModuleCode(), 'can_delete')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->moduleService->delete($id);
        if (!$res['success']) {
            return redirect()->route('admin.modules.index')->with('error', $res['message']);
        }

        return redirect()->route('admin.modules.index')->with('success', __('message.deleted_success'));
    }
}