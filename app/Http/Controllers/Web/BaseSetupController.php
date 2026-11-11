<?php

namespace App\Http\Controllers\Web;

use App\Facades\ModuleAccess;
use App\Http\Controllers\Controller;
use App\Services\BaseSetupService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BaseSetupController extends Controller
{
    protected BaseSetupService $baseSetupService;

    public function __construct(BaseSetupService $baseSetupService)
    {
        $this->baseSetupService = $baseSetupService;
    }

    /**
     * Display client-side datatable list view.
     */
    public function index(Request $request): View
    {
        if (!ModuleAccess::can($this->baseSetupService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }
        $user = Auth::user();    
        $result = $this->baseSetupService->all();
        return view('admin.sections.base_setups.base_setups_list', [
            'items'       => $result['data'],
            'moduleName'  => $this->baseSetupService->findModuleName(),
            'moduleCode'  => $this->baseSetupService->findModuleCode(),
            'access'      =>  ModuleAccess::findByMRoleIdByMCMSModuleCode($user->m_role_id,$this->baseSetupService->findModuleCode()),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Base Setups', 'url' => route('admin.base_setups.index')],
            ],
        ]);
    }

    /**
     * Display server-side ajax datatable list view.
     */
    public function listAjaxView(Request $request): View
    {
        if (!ModuleAccess::can($this->baseSetupService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        $user = Auth::user();

        return view('admin.sections.base_setups.base_setups_list_ajax', [
            'moduleName'  => $this->baseSetupService->findModuleName(),
            'moduleCode'  => $this->baseSetupService->findModuleCode(),
            'access'      => ModuleAccess::findByMRoleIdByMCMSModuleCode($user?->m_role_id, $this->baseSetupService->findModuleCode()),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Base Setups', 'url' => route('admin.base_setups.index')],
                ['name' => 'Server-Side List', 'url' => route('admin.base_setups.list')],
            ],
            'ajaxEndpoint' => route('admin.base_setups.list.data'),
        ]);
    }

    /**
     * Handle Server-Side DataTable Ajax JSON request.
     */
    public function listAjaxData(Request $request): JsonResponse
    {
        if (!ModuleAccess::can($this->baseSetupService->findModuleCode(), 'can_read')) {
            return response()->json(['message' => 'Unauthorized module access.'], 403);
        }

        $perPage = (int) $request->input('per_page', 15);
        $search = $request->input('search');
        $orderBy = $request->input('order_by', 'created_at');
        $orderDir = strtolower($request->input('order_dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $allowedSort = array (
  0 => 'title',
  1 => 'meta_description',
  2 => 'meta_keyword',
  3 => 'long_description',
  4 => 'using_dual_lang',
  5 => 'default_lang',
  6 => 'primary_image_src',
  7 => 'secondary_image_src',
  8 => 'alternative1_image_src',
  9 => 'alternative2_image_src',
  10 => 'alternative_image_src',
  11 => 'x_icon_src',
  12 => 'author',
  13 => 'address',
  14 => 'contact',
  15 => 'dashboard_theme_config',
  16 => 'asset_version',
  17 => 'additional_scripts',
  18 => 'created_at',
  19 => 'is_active',
);
        if (!in_array($orderBy, $allowedSort)) {
            $orderBy = 'created_at';
        }

        $searchColumns = array (
  0 => 'title',
  1 => 'meta_description',
  2 => 'meta_keyword',
  3 => 'long_description',
  4 => 'using_dual_lang',
  5 => 'default_lang',
  6 => 'primary_image_src',
  7 => 'secondary_image_src',
  8 => 'alternative1_image_src',
  9 => 'alternative2_image_src',
  10 => 'alternative_image_src',
  11 => 'x_icon_src',
  12 => 'author',
  13 => 'address',
  14 => 'contact',
  15 => 'dashboard_theme_config',
  16 => 'asset_version',
  17 => 'additional_scripts',
);

        $user = Auth::user();
        $access = ModuleAccess::findByMRoleIdByMCMSModuleCode($user?->m_role_id, $this->baseSetupService->findModuleCode());

        $res = $this->baseSetupService->paginate($perPage, $search, $searchColumns, $orderBy, $orderDir);
        $paginator = $res['data'];

        $items = collect($paginator->items())->map(function ($item, $idx) use ($access) {
            $initials = strtoupper(substr($item->title ?? 'I', 0, 2));
            $avatarClass = 'ma-' . (($idx % 6) + 1);

            $actionsParts = [];
            if ($access->can_read) {
                $actionsParts[] = btn_detail(route('admin.base_setups.show', $item->id), 'View details');
            }
            if ($access->can_update) {
                $actionsParts[] = btn_edit(route('admin.base_setups.edit', $item->id), 'Edit Base Setup');
            }
            if ($access->can_delete) {
                $actionsParts[] = btn_delete(route('admin.base_setups.destroy', $item->id), null, 'Delete Base Setup', null, $item->title ?? 'Base Setup');
            }

            $actionsHtml = '<div class="data-cell-actions" style="justify-content: flex-end;">'
                . implode(' ', $actionsParts)
                . '</div>';

            return [
                'id'           => $item->id,
                'short_id'     => substr($item->id, 0, 8) . '…',
                'title' => $item->title ?? '-',
                'meta_description' => $item->meta_description ?? '-',
                'meta_keyword' => $item->meta_keyword ?? '-',
                'long_description' => $item->long_description ?? '-',
                'using_dual_lang' => $item->using_dual_lang ?? '-',
                'default_lang' => $item->default_lang ?? '-',
                'primary_image_src' => $item->primary_image_src ?? '-',
                'secondary_image_src' => $item->secondary_image_src ?? '-',
                'alternative1_image_src' => $item->alternative1_image_src ?? '-',
                'alternative2_image_src' => $item->alternative2_image_src ?? '-',
                'alternative_image_src' => $item->alternative_image_src ?? '-',
                'x_icon_src' => $item->x_icon_src ?? '-',
                'author' => $item->author ?? '-',
                'address' => $item->address ?? '-',
                'contact' => $item->contact ?? '-',
                'dashboard_theme_config' => $item->dashboard_theme_config ?? '-',
                'asset_version' => $item->asset_version ?? '-',
                'additional_scripts' => $item->additional_scripts ?? '-',
                'is_active'    => (bool) $item->is_active,
                'status_html'  => $item->is_active
                    ? '<span class="badge success dot">' . __('label.active') . '</span>'
                    : '<span class="badge dot" style="color:var(--t-light)">' . __('label.inactive') . '</span>',
                'initials'     => $initials,
                'avatar_class' => $avatarClass,
                'detail_url'   => route('admin.base_setups.show', $item->id),
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
        if (!ModuleAccess::can($this->baseSetupService->findModuleCode(), 'can_create')) {
            abort(403, 'Unauthorized module access.');
        }

        return view('admin.sections.base_setups.base_setups_add', [
            'moduleName'  => $this->baseSetupService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Base Setups', 'url' => route('admin.base_setups.index')],
                ['name' => 'Add Base Setup', 'url' => route('admin.base_setups.add')],
            ],
        ]);
    }

    public function show(string $id): View
    {
        if (!ModuleAccess::can($this->baseSetupService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->baseSetupService->find($id);
        if (!$res['success']) {
            abort(404, 'Record not found.');
        }

        return view('admin.sections.base_setups.base_setups_detail', [
            'item'        => $res['data'],
            'moduleName'  => $this->baseSetupService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Base Setups', 'url' => route('admin.base_setups.index')],
                ['name' => 'Base Setup Details', 'url' => route('admin.base_setups.show', $id)],
            ],
        ]);
    }

    public function edit(string $id): View
    {
        if (!ModuleAccess::can($this->baseSetupService->findModuleCode(), 'can_update')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->baseSetupService->find($id);
        if (!$res['success']) {
            abort(404, 'Record not found.');
        }

        return view('admin.sections.base_setups.base_setups_edit', [
            'item'        => $res['data'],
            'moduleName'  => $this->baseSetupService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Base Setups', 'url' => route('admin.base_setups.index')],
                ['name' => 'Edit Base Setup', 'url' => route('admin.base_setups.edit', $id)],
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (!ModuleAccess::can($this->baseSetupService->findModuleCode(), 'can_create')) {
            abort(403, 'Unauthorized module access.');
        }

        // ponytail: no validation on create
        $this->baseSetupService->create($request->all());

        return redirect()->route('admin.base_setups.index')->with('success', __('message.created_success'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        if (!ModuleAccess::can($this->baseSetupService->findModuleCode(), 'can_update')) {
            abort(403, 'Unauthorized module access.');
        }

        // ponytail: no validation on update
        $this->baseSetupService->update($id, $request->all());

        return redirect()->route('admin.base_setups.index')->with('success', __('message.updated_success'));
    }

    public function destroy(string $id): RedirectResponse
    {
        if (!ModuleAccess::can($this->baseSetupService->findModuleCode(), 'can_delete')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->baseSetupService->delete($id);
        if (!$res['success']) {
            return redirect()->route('admin.base_setups.index')->with('error', $res['message']);
        }

        return redirect()->route('admin.base_setups.index')->with('success', __('message.deleted_success'));
    }
}