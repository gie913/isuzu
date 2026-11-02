<?php

namespace App\Http\Controllers\Web;

use App\Facades\ModuleAccess;
use App\Http\Controllers\Controller;
use App\Services\UploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UploadController extends Controller
{
    protected UploadService $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    /**
     * Display client-side datatable list view.
     */
    public function index(Request $request): View
    {
        if (!ModuleAccess::can($this->uploadService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        $result = $this->uploadService->all();

        return view('admin.sections.uploads.uploads_list', [
            'items'       => $result['data'],
            'moduleName'  => $this->uploadService->findModuleName(),
            'moduleCode'  => $this->uploadService->findModuleCode(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Uploads', 'url' => route('admin.uploads.index')],
            ],
        ]);
    }

    /**
     * Display server-side ajax datatable list view.
     */
    public function listAjaxView(Request $request): View
    {
        if (!ModuleAccess::can($this->uploadService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        return view('admin.sections.uploads.uploads_list_ajax', [
            'moduleName'  => $this->uploadService->findModuleName(),
            'moduleCode'  => $this->uploadService->findModuleCode(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Uploads', 'url' => route('admin.uploads.index')],
                ['name' => 'Server-Side List', 'url' => route('admin.uploads.list')],
            ],
            'ajaxEndpoint' => route('admin.uploads.list.data'),
        ]);
    }

    /**
     * Handle Server-Side DataTable Ajax JSON request.
     */
    public function listAjaxData(Request $request): JsonResponse
    {
        if (!ModuleAccess::can($this->uploadService->findModuleCode(), 'can_read')) {
            return response()->json(['message' => 'Unauthorized module access.'], 403);
        }

        $perPage = (int) $request->input('per_page', 15);
        $search = $request->input('search');
        $orderBy = $request->input('order_by', 'created_at');
        $orderDir = strtolower($request->input('order_dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $allowedSort = array (
  0 => 'm_upload_setup_id',
  1 => 'm_module_id',
  2 => 'name',
  3 => 'path',
  4 => 'type',
  5 => 'file_source',
  6 => 'is_external_storage',
  7 => 'is_temporary',
  8 => 'created_at',
  9 => 'is_active',
);
        if (!in_array($orderBy, $allowedSort)) {
            $orderBy = 'created_at';
        }

        $searchColumns = array (
  0 => 'm_upload_setup_id',
  1 => 'm_module_id',
  2 => 'name',
  3 => 'path',
  4 => 'type',
  5 => 'file_source',
  6 => 'is_external_storage',
  7 => 'is_temporary',
);

        $res = $this->uploadService->paginate($perPage, $search, $searchColumns, $orderBy, $orderDir);
        $paginator = $res['data'];

        $items = collect($paginator->items())->map(function ($item, $idx) {
            $initials = strtoupper(substr($item->m_upload_setup_id ?? 'I', 0, 2));
            $avatarClass = 'ma-' . (($idx % 6) + 1);

            $actionsHtml = '<div class="data-cell-actions" style="justify-content: flex-end;">'
                . btn_detail(route('admin.uploads.show', $item->id), 'View details') . ' '
                . btn_edit(route('admin.uploads.edit', $item->id), 'Edit Uploads') . ' '
                . btn_delete(route('admin.uploads.destroy', $item->id), null, 'Delete Uploads', null, $item->m_upload_setup_id ?? 'Uploads')
                . '</div>';

            return [
                'id'           => $item->id,
                'short_id'     => substr($item->id, 0, 8) . '…',
                'm_upload_setup_id' => $item->m_upload_setup_id ?? '-',
                'm_module_id' => $item->m_module_id ?? '-',
                'name' => $item->name ?? '-',
                'path' => $item->path ?? '-',
                'type' => $item->type ?? '-',
                'file_source' => $item->file_source ?? '-',
                'is_external_storage' => $item->is_external_storage ?? '-',
                'is_temporary' => $item->is_temporary ?? '-',
                'is_active'    => (bool) $item->is_active,
                'status_html'  => $item->is_active
                    ? '<span class="badge success dot">' . __('label.active') . '</span>'
                    : '<span class="badge dot" style="color:var(--t-light)">' . __('label.inactive') . '</span>',
                'initials'     => $initials,
                'avatar_class' => $avatarClass,
                'detail_url'   => route('admin.uploads.show', $item->id),
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
        if (!ModuleAccess::can($this->uploadService->findModuleCode(), 'can_create')) {
            abort(403, 'Unauthorized module access.');
        }

        return view('admin.sections.uploads.uploads_add', [
            'moduleName'  => $this->uploadService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Uploads', 'url' => route('admin.uploads.index')],
                ['name' => 'Add Uploads', 'url' => route('admin.uploads.add')],
            ],
        ]);
    }

    public function show(string $id): View
    {
        if (!ModuleAccess::can($this->uploadService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->uploadService->find($id);
        if (!$res['success']) {
            abort(404, 'Record not found.');
        }

        return view('admin.sections.uploads.uploads_detail', [
            'item'        => $res['data'],
            'moduleName'  => $this->uploadService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Uploads', 'url' => route('admin.uploads.index')],
                ['name' => 'Uploads Details', 'url' => route('admin.uploads.show', $id)],
            ],
        ]);
    }

    public function edit(string $id): View
    {
        if (!ModuleAccess::can($this->uploadService->findModuleCode(), 'can_update')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->uploadService->find($id);
        if (!$res['success']) {
            abort(404, 'Record not found.');
        }

        return view('admin.sections.uploads.uploads_edit', [
            'item'        => $res['data'],
            'moduleName'  => $this->uploadService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Uploads', 'url' => route('admin.uploads.index')],
                ['name' => 'Edit Uploads', 'url' => route('admin.uploads.edit', $id)],
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (!ModuleAccess::can($this->uploadService->findModuleCode(), 'can_create')) {
            abort(403, 'Unauthorized module access.');
        }

        // ponytail: no validation on create
        $this->uploadService->create($request->all());

        return redirect()->route('admin.uploads.index')->with('success', __('message.created_success'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        if (!ModuleAccess::can($this->uploadService->findModuleCode(), 'can_update')) {
            abort(403, 'Unauthorized module access.');
        }

        // ponytail: no validation on update
        $this->uploadService->update($id, $request->all());

        return redirect()->route('admin.uploads.index')->with('success', __('message.updated_success'));
    }

    public function destroy(string $id): RedirectResponse
    {
        if (!ModuleAccess::can($this->uploadService->findModuleCode(), 'can_delete')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->uploadService->delete($id);
        if (!$res['success']) {
            return redirect()->route('admin.uploads.index')->with('error', $res['message']);
        }

        return redirect()->route('admin.uploads.index')->with('success', __('message.deleted_success'));
    }
}