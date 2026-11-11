<?php

namespace App\Http\Controllers\Web;

use App\Facades\ModuleAccess;
use App\Http\Controllers\Controller;
use App\Services\ContentDtlService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContentDtlController extends Controller
{
    protected ContentDtlService $contentDtlService;

    public function __construct(ContentDtlService $contentDtlService)
    {
        $this->contentDtlService = $contentDtlService;
    }

    /**
     * Display client-side datatable list view.
     */
    public function index(Request $request): View
    {
        if (!ModuleAccess::can($this->contentDtlService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        $result = $this->contentDtlService->all();

        return view('admin.sections.content_dtls.content_dtls_list', [
            'items'       => $result['data'],
            'moduleName'  => $this->contentDtlService->findModuleName(),
            'moduleCode'  => $this->contentDtlService->findModuleCode(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Content_dtls', 'url' => route('admin.content_dtls.index')],
            ],
        ]);
    }

    /**
     * Display server-side ajax datatable list view.
     */
    public function listAjaxView(Request $request): View
    {
        if (!ModuleAccess::can($this->contentDtlService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        return view('admin.sections.content_dtls.content_dtls_list_ajax', [
            'moduleName'  => $this->contentDtlService->findModuleName(),
            'moduleCode'  => $this->contentDtlService->findModuleCode(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Content_dtls', 'url' => route('admin.content_dtls.index')],
                ['name' => 'Server-Side List', 'url' => route('admin.content_dtls.list')],
            ],
            'ajaxEndpoint' => route('admin.content_dtls.list.data'),
        ]);
    }

    /**
     * Handle Server-Side DataTable Ajax JSON request.
     */
    public function listAjaxData(Request $request): JsonResponse
    {
        if (!ModuleAccess::can($this->contentDtlService->findModuleCode(), 'can_read')) {
            return response()->json(['message' => 'Unauthorized module access.'], 403);
        }

        $perPage = (int) $request->input('per_page', 15);
        $search = $request->input('search');
        $orderBy = $request->input('order_by', 'created_at');
        $orderDir = strtolower($request->input('order_dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $allowedSort = array (
  0 => 't_content_id',
  1 => 'title',
  2 => 'subtitle',
  3 => 'description',
  4 => 'sort_number',
  5 => 'image',
  6 => 'background',
  7 => 'icon',
  8 => 'redirect_to',
  9 => 'file',
  10 => 'created_at',
  11 => 'is_active',
);
        if (!in_array($orderBy, $allowedSort)) {
            $orderBy = 'created_at';
        }

        $searchColumns = array (
  0 => 't_content_id',
  1 => 'title',
  2 => 'subtitle',
  3 => 'description',
  4 => 'sort_number',
  5 => 'image',
  6 => 'background',
  7 => 'icon',
  8 => 'redirect_to',
  9 => 'file',
);

        $res = $this->contentDtlService->paginate($perPage, $search, $searchColumns, $orderBy, $orderDir);
        $paginator = $res['data'];

        $items = collect($paginator->items())->map(function ($item, $idx) {
            $initials = strtoupper(substr($item->t_content_id ?? 'I', 0, 2));
            $avatarClass = 'ma-' . (($idx % 6) + 1);

            $actionsHtml = '<div class="data-cell-actions" style="justify-content: flex-end;">'
                . btn_detail(route('admin.content_dtls.show', $item->id), 'View details') . ' '
                . btn_edit(route('admin.content_dtls.edit', $item->id), 'Edit Content_dtl') . ' '
                . btn_delete(route('admin.content_dtls.destroy', $item->id), null, 'Delete Content_dtl', null, $item->t_content_id ?? 'Content_dtl')
                . '</div>';

            return [
                'id'           => $item->id,
                'short_id'     => substr($item->id, 0, 8) . '…',
                't_content_id' => $item->t_content_id ?? '-',
                'title' => $item->title ?? '-',
                'subtitle' => $item->subtitle ?? '-',
                'description' => $item->description ?? '-',
                'sort_number' => $item->sort_number ?? '-',
                'image' => $item->image ?? '-',
                'background' => $item->background ?? '-',
                'icon' => $item->icon ?? '-',
                'redirect_to' => $item->redirect_to ?? '-',
                'file' => $item->file ?? '-',
                'is_active'    => (bool) $item->is_active,
                'status_html'  => $item->is_active
                    ? '<span class="badge success dot">' . __('label.active') . '</span>'
                    : '<span class="badge dot" style="color:var(--t-light)">' . __('label.inactive') . '</span>',
                'initials'     => $initials,
                'avatar_class' => $avatarClass,
                'detail_url'   => route('admin.content_dtls.show', $item->id),
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
        if (!ModuleAccess::can($this->contentDtlService->findModuleCode(), 'can_create')) {
            abort(403, 'Unauthorized module access.');
        }

        return view('admin.sections.content_dtls.content_dtls_add', [
            'moduleName'  => $this->contentDtlService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Content_dtls', 'url' => route('admin.content_dtls.index')],
                ['name' => 'Add Content_dtl', 'url' => route('admin.content_dtls.add')],
            ],
        ]);
    }

    public function show(string $id): View
    {
        if (!ModuleAccess::can($this->contentDtlService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->contentDtlService->find($id);
        if (!$res['success']) {
            abort(404, 'Record not found.');
        }

        return view('admin.sections.content_dtls.content_dtls_detail', [
            'item'        => $res['data'],
            'moduleName'  => $this->contentDtlService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Content_dtls', 'url' => route('admin.content_dtls.index')],
                ['name' => 'Content_dtl Details', 'url' => route('admin.content_dtls.show', $id)],
            ],
        ]);
    }

    public function edit(string $id): View
    {
        if (!ModuleAccess::can($this->contentDtlService->findModuleCode(), 'can_update')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->contentDtlService->find($id);
        if (!$res['success']) {
            abort(404, 'Record not found.');
        }

        return view('admin.sections.content_dtls.content_dtls_edit', [
            'item'        => $res['data'],
            'moduleName'  => $this->contentDtlService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Content_dtls', 'url' => route('admin.content_dtls.index')],
                ['name' => 'Edit Content_dtl', 'url' => route('admin.content_dtls.edit', $id)],
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (!ModuleAccess::can($this->contentDtlService->findModuleCode(), 'can_create')) {
            abort(403, 'Unauthorized module access.');
        }

        // ponytail: no validation on create
        $this->contentDtlService->create($request->all());

        return redirect()->route('admin.content_dtls.index')->with('success', __('message.created_success'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        if (!ModuleAccess::can($this->contentDtlService->findModuleCode(), 'can_update')) {
            abort(403, 'Unauthorized module access.');
        }

        // ponytail: no validation on update
        $this->contentDtlService->update($id, $request->all());

        return redirect()->route('admin.content_dtls.index')->with('success', __('message.updated_success'));
    }

    public function destroy(string $id): RedirectResponse
    {
        if (!ModuleAccess::can($this->contentDtlService->findModuleCode(), 'can_delete')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->contentDtlService->delete($id);
        if (!$res['success']) {
            return redirect()->route('admin.content_dtls.index')->with('error', $res['message']);
        }

        return redirect()->route('admin.content_dtls.index')->with('success', __('message.deleted_success'));
    }
}