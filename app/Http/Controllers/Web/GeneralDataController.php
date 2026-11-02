<?php

namespace App\Http\Controllers\Web;

use App\Facades\ModuleAccess;
use App\Http\Controllers\Controller;
use App\Services\GeneralDataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GeneralDataController extends Controller
{
    protected GeneralDataService $generalDataService;

    public function __construct(GeneralDataService $generalDataService)
    {
        $this->generalDataService = $generalDataService;
    }

    /**
     * Display client-side datatable list view.
     */
    public function index(Request $request): View
    {
        if (!ModuleAccess::can($this->generalDataService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        $result = $this->generalDataService->all();

        return view('admin.sections.general_datas.general_datas_list', [
            'items'       => $result['data'],
            'moduleName'  => $this->generalDataService->findModuleName(),
            'moduleCode'  => $this->generalDataService->findModuleCode(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'General Datas', 'url' => route('admin.general_datas.index')],
            ],
        ]);
    }

    /**
     * Display server-side ajax datatable list view.
     */
    public function listAjaxView(Request $request): View
    {
        if (!ModuleAccess::can($this->generalDataService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        return view('admin.sections.general_datas.general_datas_list_ajax', [
            'moduleName'  => $this->generalDataService->findModuleName(),
            'moduleCode'  => $this->generalDataService->findModuleCode(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'General Datas', 'url' => route('admin.general_datas.index')],
                ['name' => 'Server-Side List', 'url' => route('admin.general_datas.list')],
            ],
            'ajaxEndpoint' => route('admin.general_datas.list.data'),
        ]);
    }

    /**
     * Handle Server-Side DataTable Ajax JSON request.
     */
    public function listAjaxData(Request $request): JsonResponse
    {
        if (!ModuleAccess::can($this->generalDataService->findModuleCode(), 'can_read')) {
            return response()->json(['message' => 'Unauthorized module access.'], 403);
        }

        $perPage = (int) $request->input('per_page', 15);
        $search = $request->input('search');
        $orderBy = $request->input('order_by', 'created_at');
        $orderDir = strtolower($request->input('order_dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $allowedSort = ['group_', 'code', 'sort_number', 'list_data', 'created_at', 'is_active'];
        if (!in_array($orderBy, $allowedSort)) {
            $orderBy = 'created_at';
        }

        $searchColumns = ['group_', 'code', 'list_data', 'description'];

        $res = $this->generalDataService->paginate($perPage, $search, $searchColumns, $orderBy, $orderDir);
        $paginator = $res['data'];

        $items = collect($paginator->items())->map(function ($item, $idx) {
            $initials = strtoupper(substr($item->code ?? $item->group_ ?? 'G', 0, 2));
            $avatarClass = 'ma-' . (($idx % 6) + 1);

            $actionsHtml = '<div class="data-cell-actions" style="justify-content: flex-end;">'
                . btn_detail(route('admin.general_datas.show', $item->id), 'View details') . ' '
                . btn_edit(route('admin.general_datas.edit', $item->id), 'Edit General Data') . ' '
                . btn_delete(route('admin.general_datas.destroy', $item->id), null, 'Delete General Data', null, $item->code ?? 'General Data')
                . '</div>';

            return [
                'id'           => $item->id,
                'short_id'     => substr($item->id, 0, 8) . '…',
                'group_'       => $item->group_ ?? '-',
                'code'         => $item->code ?? '-',
                'sort_number'  => $item->sort_number ?? '-',
                'list_data'    => $item->list_data ?? '-',
                'description'  => $item->description ?? '-',
                'is_active'    => (bool) $item->is_active,
                'status_html'  => $item->is_active
                    ? '<span class="badge success dot">' . __('label.active') . '</span>'
                    : '<span class="badge dot" style="color:var(--t-light)">' . __('label.inactive') . '</span>',
                'initials'     => $initials,
                'avatar_class' => $avatarClass,
                'detail_url'   => route('admin.general_datas.show', $item->id),
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
        if (!ModuleAccess::can($this->generalDataService->findModuleCode(), 'can_create')) {
            abort(403, 'Unauthorized module access.');
        }

        return view('admin.sections.general_datas.general_datas_add', [
            'moduleName'  => $this->generalDataService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'General Datas', 'url' => route('admin.general_datas.index')],
                ['name' => 'Add General Data', 'url' => route('admin.general_datas.add')],
            ],
        ]);
    }

    public function show(string $id): View
    {
        if (!ModuleAccess::can($this->generalDataService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->generalDataService->find($id);
        if (!$res['success']) {
            abort(404, 'Record not found.');
        }

        return view('admin.sections.general_datas.general_datas_detail', [
            'item'        => $res['data'],
            'moduleName'  => $this->generalDataService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'General Datas', 'url' => route('admin.general_datas.index')],
                ['name' => 'General Data Details', 'url' => route('admin.general_datas.show', $id)],
            ],
        ]);
    }

    public function edit(string $id): View
    {
        if (!ModuleAccess::can($this->generalDataService->findModuleCode(), 'can_update')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->generalDataService->find($id);
        if (!$res['success']) {
            abort(404, 'Record not found.');
        }

        return view('admin.sections.general_datas.general_datas_edit', [
            'item'        => $res['data'],
            'moduleName'  => $this->generalDataService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'General Datas', 'url' => route('admin.general_datas.index')],
                ['name' => 'Edit General Data', 'url' => route('admin.general_datas.edit', $id)],
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (!ModuleAccess::can($this->generalDataService->findModuleCode(), 'can_create')) {
            abort(403, 'Unauthorized module access.');
        }

        // ponytail: no validation on create
        $this->generalDataService->create($request->all());

        return redirect()->route('admin.general_datas.index')->with('success', __('message.created_success'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        if (!ModuleAccess::can($this->generalDataService->findModuleCode(), 'can_update')) {
            abort(403, 'Unauthorized module access.');
        }

        // ponytail: no validation on update
        $this->generalDataService->update($id, $request->all());

        return redirect()->route('admin.general_datas.index')->with('success', __('message.updated_success'));
    }

    public function destroy(string $id): RedirectResponse
    {
        if (!ModuleAccess::can($this->generalDataService->findModuleCode(), 'can_delete')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->generalDataService->delete($id);
        if (!$res['success']) {
            return redirect()->route('admin.general_datas.index')->with('error', $res['message']);
        }

        return redirect()->route('admin.general_datas.index')->with('success', __('message.deleted_success'));
    }
}