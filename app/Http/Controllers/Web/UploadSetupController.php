<?php

namespace App\Http\Controllers\Web;

use App\Facades\ModuleAccess;
use App\Http\Controllers\Controller;
use App\Services\UploadSetupService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UploadSetupController extends Controller
{
    protected UploadSetupService $uploadSetupService;

    public function __construct(UploadSetupService $uploadSetupService)
    {
        $this->uploadSetupService = $uploadSetupService;
    }

    /**
     * Display client-side datatable list view.
     */
    public function index(Request $request): View
    {
        if (!ModuleAccess::can($this->uploadSetupService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        $result = $this->uploadSetupService->all();

        return view('admin.sections.upload_setups.upload_setups_list', [
            'items'       => $result['data'],
            'moduleName'  => $this->uploadSetupService->findModuleName(),
            'moduleCode'  => $this->uploadSetupService->findModuleCode(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Upload setups', 'url' => route('admin.upload_setups.index')],
            ],
        ]);
    }

    /**
     * Display server-side ajax datatable list view.
     */
    public function listAjaxView(Request $request): View
    {
        if (!ModuleAccess::can($this->uploadSetupService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        return view('admin.sections.upload_setups.upload_setups_list_ajax', [
            'moduleName'  => $this->uploadSetupService->findModuleName(),
            'moduleCode'  => $this->uploadSetupService->findModuleCode(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Upload setups', 'url' => route('admin.upload_setups.index')],
                ['name' => 'Server-Side List', 'url' => route('admin.upload_setups.list')],
            ],
            'ajaxEndpoint' => route('admin.upload_setups.list.data'),
        ]);
    }

    /**
     * Handle Server-Side DataTable Ajax JSON request.
     */
    public function listAjaxData(Request $request): JsonResponse
    {
        if (!ModuleAccess::can($this->uploadSetupService->findModuleCode(), 'can_read')) {
            return response()->json(['message' => 'Unauthorized module access.'], 403);
        }

        $perPage = (int) $request->input('per_page', 15);
        $search = $request->input('search');
        $orderBy = $request->input('order_by', 'created_at');
        $orderDir = strtolower($request->input('order_dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $allowedSort = array (
  0 => 'm_module_id',
  1 => 'name',
  2 => 'path',
  3 => 'type',
  4 => 'allowed_files',
  5 => 'is_resized',
  6 => 'pic_height',
  7 => 'pic_width',
  8 => 'is_cropped',
  9 => 'ratio_height',
  10 => 'ratio_width',
  11 => 'using_thumb',
  12 => 'thumb_height',
  13 => 'thumb_width',
  14 => 'max_size',
  15 => 'is_external_storage',
  16 => 'created_at',
  17 => 'is_active',
);
        if (!in_array($orderBy, $allowedSort)) {
            $orderBy = 'created_at';
        }

        $searchColumns = array (
  0 => 'm_module_id',
  1 => 'name',
  2 => 'path',
  3 => 'type',
  4 => 'allowed_files',
  5 => 'is_resized',
  6 => 'pic_height',
  7 => 'pic_width',
  8 => 'is_cropped',
  9 => 'ratio_height',
  10 => 'ratio_width',
  11 => 'using_thumb',
  12 => 'thumb_height',
  13 => 'thumb_width',
  14 => 'max_size',
  15 => 'is_external_storage',
);

        $res = $this->uploadSetupService->paginate($perPage, $search, $searchColumns, $orderBy, $orderDir);
        $paginator = $res['data'];

        $items = collect($paginator->items())->map(function ($item, $idx) {
            $initials = strtoupper(substr($item->m_module_id ?? 'I', 0, 2));
            $avatarClass = 'ma-' . (($idx % 6) + 1);

            $actionsHtml = '<div class="data-cell-actions" style="justify-content: flex-end;">'
                . btn_detail(route('admin.upload_setups.show', $item->id), 'View details') . ' '
                . btn_edit(route('admin.upload_setups.edit', $item->id), 'Edit Upload setup') . ' '
                . btn_delete(route('admin.upload_setups.destroy', $item->id), null, 'Delete Upload setup', null, $item->m_module_id ?? 'Upload setup')
                . '</div>';

            return [
                'id'           => $item->id,
                'short_id'     => substr($item->id, 0, 8) . '…',
                'm_module_id' => $item->m_module_id ?? '-',
                'name' => $item->name ?? '-',
                'path' => $item->path ?? '-',
                'type' => $item->type ?? '-',
                'allowed_files' => $item->allowed_files ?? '-',
                'is_resized' => $item->is_resized ?? '-',
                'pic_height' => $item->pic_height ?? '-',
                'pic_width' => $item->pic_width ?? '-',
                'is_cropped' => $item->is_cropped ?? '-',
                'ratio_height' => $item->ratio_height ?? '-',
                'ratio_width' => $item->ratio_width ?? '-',
                'using_thumb' => $item->using_thumb ?? '-',
                'thumb_height' => $item->thumb_height ?? '-',
                'thumb_width' => $item->thumb_width ?? '-',
                'max_size' => $item->max_size ?? '-',
                'is_external_storage' => $item->is_external_storage ?? '-',
                'is_active'    => (bool) $item->is_active,
                'status_html'  => $item->is_active
                    ? '<span class="badge success dot">' . __('label.active') . '</span>'
                    : '<span class="badge dot" style="color:var(--t-light)">' . __('label.inactive') . '</span>',
                'initials'     => $initials,
                'avatar_class' => $avatarClass,
                'detail_url'   => route('admin.upload_setups.show', $item->id),
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
        if (!ModuleAccess::can($this->uploadSetupService->findModuleCode(), 'can_create')) {
            abort(403, 'Unauthorized module access.');
        }

        return view('admin.sections.upload_setups.upload_setups_add', [
            'moduleName'  => $this->uploadSetupService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Upload setups', 'url' => route('admin.upload_setups.index')],
                ['name' => 'Add Upload setup', 'url' => route('admin.upload_setups.add')],
            ],
        ]);
    }

    public function show(string $id): View
    {
        if (!ModuleAccess::can($this->uploadSetupService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->uploadSetupService->find($id);
        if (!$res['success']) {
            abort(404, 'Record not found.');
        }

        return view('admin.sections.upload_setups.upload_setups_detail', [
            'item'        => $res['data'],
            'moduleName'  => $this->uploadSetupService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Upload setups', 'url' => route('admin.upload_setups.index')],
                ['name' => 'Upload setup Details', 'url' => route('admin.upload_setups.show', $id)],
            ],
        ]);
    }

    public function edit(string $id): View
    {
        if (!ModuleAccess::can($this->uploadSetupService->findModuleCode(), 'can_update')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->uploadSetupService->find($id);
        if (!$res['success']) {
            abort(404, 'Record not found.');
        }

        return view('admin.sections.upload_setups.upload_setups_edit', [
            'item'        => $res['data'],
            'moduleName'  => $this->uploadSetupService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Upload setups', 'url' => route('admin.upload_setups.index')],
                ['name' => 'Edit Upload setup', 'url' => route('admin.upload_setups.edit', $id)],
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (!ModuleAccess::can($this->uploadSetupService->findModuleCode(), 'can_create')) {
            abort(403, 'Unauthorized module access.');
        }

        // ponytail: no validation on create
        $this->uploadSetupService->create($request->all());

        return redirect()->route('admin.upload_setups.index')->with('success', __('message.created_success'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        if (!ModuleAccess::can($this->uploadSetupService->findModuleCode(), 'can_update')) {
            abort(403, 'Unauthorized module access.');
        }

        // ponytail: no validation on update
        $this->uploadSetupService->update($id, $request->all());

        return redirect()->route('admin.upload_setups.index')->with('success', __('message.updated_success'));
    }

    public function destroy(string $id): RedirectResponse
    {
        if (!ModuleAccess::can($this->uploadSetupService->findModuleCode(), 'can_delete')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->uploadSetupService->delete($id);
        if (!$res['success']) {
            return redirect()->route('admin.upload_setups.index')->with('error', $res['message']);
        }

        return redirect()->route('admin.upload_setups.index')->with('success', __('message.deleted_success'));
    }
}