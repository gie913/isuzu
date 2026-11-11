<?php

namespace App\Http\Controllers\Web;

use App\Facades\ModuleAccess;
use App\Http\Controllers\Controller;
use App\Services\MenuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    protected MenuService $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    /**
     * Display client-side datatable list view.
     */
    public function index(Request $request): View
    {
        if (!ModuleAccess::can($this->menuService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        $result = $this->menuService->all();

        return view('admin.sections.menus.menus_list', [
            'items'       => $result['data'],
            'moduleName'  => $this->menuService->findModuleName(),
            'moduleCode'  => $this->menuService->findModuleCode(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Menus', 'url' => route('admin.menus.index')],
            ],
        ]);
    }

    /**
     * Display server-side ajax datatable list view.
     */
    public function listAjaxView(Request $request): View
    {
        if (!ModuleAccess::can($this->menuService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        return view('admin.sections.menus.menus_list_ajax', [
            'moduleName'  => $this->menuService->findModuleName(),
            'moduleCode'  => $this->menuService->findModuleCode(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Menus', 'url' => route('admin.menus.index')],
                ['name' => 'Server-Side List', 'url' => route('admin.menus.list')],
            ],
            'ajaxEndpoint' => route('admin.menus.list.data'),
        ]);
    }

    /**
     * Handle Server-Side DataTable Ajax JSON request.
     */
    public function listAjaxData(Request $request): JsonResponse
    {
        if (!ModuleAccess::can($this->menuService->findModuleCode(), 'can_read')) {
            return response()->json(['message' => 'Unauthorized module access.'], 403);
        }

        $perPage = (int) $request->input('per_page', 15);
        $search = $request->input('search');
        $orderBy = $request->input('order_by', 'created_at');
        $orderDir = strtolower($request->input('order_dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $allowedSort = array (
  0 => 'code',
  1 => 'name',
  2 => 'redirect_to',
  3 => 'type',
  4 => 'category',
  5 => 't_page_id',
  6 => 'sort_number',
  7 => 'is_devider',
  8 => 'is_submenu',
  9 => 'submenu_from_id',
  10 => 'icon',
  11 => 'submenu_level',
  12 => 'text',
  13 => 'text_lang_eng',
  14 => 'text_lang_ina',
  15 => 'slug',
  16 => 'is_published',
  17 => 'is_footer',
  18 => 'created_at',
  19 => 'is_active',
);
        if (!in_array($orderBy, $allowedSort)) {
            $orderBy = 'created_at';
        }

        $searchColumns = array (
  0 => 'code',
  1 => 'name',
  2 => 'redirect_to',
  3 => 'type',
  4 => 'category',
  5 => 't_page_id',
  6 => 'sort_number',
  7 => 'is_devider',
  8 => 'is_submenu',
  9 => 'submenu_from_id',
  10 => 'icon',
  11 => 'submenu_level',
  12 => 'text',
  13 => 'text_lang_eng',
  14 => 'text_lang_ina',
  15 => 'slug',
  16 => 'is_published',
  17 => 'is_footer',
);

        $res = $this->menuService->paginate($perPage, $search, $searchColumns, $orderBy, $orderDir);
        $paginator = $res['data'];

        $items = collect($paginator->items())->map(function ($item, $idx) {
            $initials = strtoupper(substr($item->code ?? 'I', 0, 2));
            $avatarClass = 'ma-' . (($idx % 6) + 1);

            $actionsHtml = '<div class="data-cell-actions" style="justify-content: flex-end;">'
                . btn_detail(route('admin.menus.show', $item->id), 'View details') . ' '
                . btn_edit(route('admin.menus.edit', $item->id), 'Edit Menu') . ' '
                . btn_delete(route('admin.menus.destroy', $item->id), null, 'Delete Menu', null, $item->code ?? 'Menu')
                . '</div>';

            return [
                'id'           => $item->id,
                'short_id'     => substr($item->id, 0, 8) . '…',
                'code' => $item->code ?? '-',
                'name' => $item->name ?? '-',
                'redirect_to' => $item->redirect_to ?? '-',
                'type' => $item->type ?? '-',
                'category' => $item->category ?? '-',
                't_page_id' => $item->t_page_id ?? '-',
                'sort_number' => $item->sort_number ?? '-',
                'is_devider' => $item->is_devider ?? '-',
                'is_submenu' => $item->is_submenu ?? '-',
                'submenu_from_id' => $item->submenu_from_id ?? '-',
                'icon' => $item->icon ?? '-',
                'submenu_level' => $item->submenu_level ?? '-',
                'text' => $item->text ?? '-',
                'text_lang_eng' => $item->text_lang_eng ?? '-',
                'text_lang_ina' => $item->text_lang_ina ?? '-',
                'slug' => $item->slug ?? '-',
                'is_published' => $item->is_published ?? '-',
                'is_footer' => $item->is_footer ?? '-',
                'is_active'    => (bool) $item->is_active,
                'status_html'  => $item->is_active
                    ? '<span class="badge success dot">' . __('label.active') . '</span>'
                    : '<span class="badge dot" style="color:var(--t-light)">' . __('label.inactive') . '</span>',
                'initials'     => $initials,
                'avatar_class' => $avatarClass,
                'detail_url'   => route('admin.menus.show', $item->id),
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
        if (!ModuleAccess::can($this->menuService->findModuleCode(), 'can_create')) {
            abort(403, 'Unauthorized module access.');
        }

        return view('admin.sections.menus.menus_add', [
            'moduleName'  => $this->menuService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Menus', 'url' => route('admin.menus.index')],
                ['name' => 'Add Menu', 'url' => route('admin.menus.add')],
            ],
        ]);
    }

    public function show(string $id): View
    {
        if (!ModuleAccess::can($this->menuService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->menuService->find($id);
        if (!$res['success']) {
            abort(404, 'Record not found.');
        }

        return view('admin.sections.menus.menus_detail', [
            'item'        => $res['data'],
            'moduleName'  => $this->menuService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Menus', 'url' => route('admin.menus.index')],
                ['name' => 'Menu Details', 'url' => route('admin.menus.show', $id)],
            ],
        ]);
    }

    public function edit(string $id): View
    {
        if (!ModuleAccess::can($this->menuService->findModuleCode(), 'can_update')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->menuService->find($id);
        if (!$res['success']) {
            abort(404, 'Record not found.');
        }

        return view('admin.sections.menus.menus_edit', [
            'item'        => $res['data'],
            'moduleName'  => $this->menuService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Menus', 'url' => route('admin.menus.index')],
                ['name' => 'Edit Menu', 'url' => route('admin.menus.edit', $id)],
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (!ModuleAccess::can($this->menuService->findModuleCode(), 'can_create')) {
            abort(403, 'Unauthorized module access.');
        }

        // ponytail: no validation on create
        $this->menuService->create($request->all());

        return redirect()->route('admin.menus.index')->with('success', __('message.created_success'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        if (!ModuleAccess::can($this->menuService->findModuleCode(), 'can_update')) {
            abort(403, 'Unauthorized module access.');
        }

        // ponytail: no validation on update
        $this->menuService->update($id, $request->all());

        return redirect()->route('admin.menus.index')->with('success', __('message.updated_success'));
    }

    public function destroy(string $id): RedirectResponse
    {
        if (!ModuleAccess::can($this->menuService->findModuleCode(), 'can_delete')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->menuService->delete($id);
        if (!$res['success']) {
            return redirect()->route('admin.menus.index')->with('error', $res['message']);
        }

        return redirect()->route('admin.menus.index')->with('success', __('message.deleted_success'));
    }
}