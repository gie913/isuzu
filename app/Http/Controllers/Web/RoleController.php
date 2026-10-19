<?php

namespace App\Http\Controllers\Web;

use App\Facades\CMSAccess;
use App\Http\Controllers\Controller;
use App\Services\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    protected RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Display client-side datatable list view.
     */
    public function index(Request $request): View
    {
        if (!CMSAccess::can($this->roleService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        $result = $this->roleService->all();

        return view('admin.sections.roles.roles_list', [
            'items'       => $result['data'],
            'moduleName'  => $this->roleService->findModuleName(),
            'moduleCode'  => $this->roleService->findModuleCode(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Roles', 'url' => route('admin.roles.index')],
            ],
        ]);
    }

    /**
     * Display server-side ajax datatable list view.
     */
    public function listAjaxView(Request $request): View
    {
        if (!CMSAccess::can($this->roleService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        return view('admin.sections.roles.roles_list_ajax', [
            'moduleName'  => $this->roleService->findModuleName(),
            'moduleCode'  => $this->roleService->findModuleCode(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Roles', 'url' => route('admin.roles.index')],
                ['name' => 'Server-Side List', 'url' => route('admin.roles.list')],
            ],
            'ajaxEndpoint' => route('admin.roles.list.data'),
        ]);
    }

    /**
     * Handle Server-Side DataTable Ajax JSON request.
     */
    public function listAjaxData(Request $request): JsonResponse
    {
        if (!CMSAccess::can($this->roleService->findModuleCode(), 'can_read')) {
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

        $res = $this->roleService->paginate($perPage, $search, $searchColumns, $orderBy, $orderDir);
        $paginator = $res['data'];

        $items = collect($paginator->items())->map(function ($item, $idx) {
            $initials = strtoupper(substr($item->name ?? 'I', 0, 2));
            $avatarClass = 'ma-' . (($idx % 6) + 1);

            $actionsHtml = '<div class="data-cell-actions" style="justify-content: flex-end;">'
                . btn_detail(route('admin.roles.show', $item->id), 'View details') . ' '
                . btn_edit(route('admin.roles.edit', $item->id), 'Edit Role') . ' '
                . btn_delete(route('admin.roles.destroy', $item->id), 'Are you sure you want to delete this Role?', 'Delete Role')
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
                'detail_url'   => route('admin.roles.show', $item->id),
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
        if (!CMSAccess::can($this->roleService->findModuleCode(), 'can_create')) {
            abort(403, 'Unauthorized module access.');
        }

        return view('admin.sections.roles.roles_add', [
            'moduleName'  => $this->roleService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Roles', 'url' => route('admin.roles.index')],
                ['name' => 'Add Role', 'url' => route('admin.roles.add')],
            ],
        ]);
    }

    public function show(string $id): View
    {
        if (!CMSAccess::can($this->roleService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->roleService->find($id);
        if (!$res['success']) {
            abort(404, 'Record not found.');
        }

        return view('admin.sections.roles.roles_detail', [
            'item'        => $res['data'],
            'moduleName'  => $this->roleService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Roles', 'url' => route('admin.roles.index')],
                ['name' => 'Role Details', 'url' => route('admin.roles.show', $id)],
            ],
        ]);
    }

    public function edit(string $id): View
    {
        if (!CMSAccess::can($this->roleService->findModuleCode(), 'can_update')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->roleService->find($id);
        if (!$res['success']) {
            abort(404, 'Record not found.');
        }

        return view('admin.sections.roles.roles_edit', [
            'item'        => $res['data'],
            'moduleName'  => $this->roleService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Roles', 'url' => route('admin.roles.index')],
                ['name' => 'Edit Role', 'url' => route('admin.roles.edit', $id)],
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (!CMSAccess::can($this->roleService->findModuleCode(), 'can_create')) {
            abort(403, 'Unauthorized module access.');
        }

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $this->roleService->create($validated);

        return redirect()->route('admin.roles.index')->with('success', __('message.created_success'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        if (!CMSAccess::can($this->roleService->findModuleCode(), 'can_update')) {
            abort(403, 'Unauthorized module access.');
        }

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $this->roleService->update($id, $validated);

        return redirect()->route('admin.roles.index')->with('success', __('message.updated_success'));
    }

    public function destroy(string $id): RedirectResponse
    {
        if (!CMSAccess::can($this->roleService->findModuleCode(), 'can_delete')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->roleService->delete($id);
        if (!$res['success']) {
            return redirect()->route('admin.roles.index')->with('error', $res['message']);
        }

        return redirect()->route('admin.roles.index')->with('success', __('message.deleted_success'));
    }
}