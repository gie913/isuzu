<?php

namespace App\Http\Controllers\Web;

use App\Facades\ModuleAccess;
use App\Http\Controllers\Controller;
use App\Services\PageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    protected PageService $pageService;

    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
    }

    /**
     * Display client-side datatable list view.
     */
    public function index(Request $request): View
    {
        if (!ModuleAccess::can($this->pageService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        $result = $this->pageService->all();

        return view('admin.sections.pages.pages_list', [
            'items'       => $result['data'],
            'moduleName'  => $this->pageService->findModuleName(),
            'moduleCode'  => $this->pageService->findModuleCode(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Pages', 'url' => route('admin.pages.index')],
            ],
        ]);
    }

    /**
     * Display server-side ajax datatable list view.
     */
    public function listAjaxView(Request $request): View
    {
        if (!ModuleAccess::can($this->pageService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        return view('admin.sections.pages.pages_list_ajax', [
            'moduleName'  => $this->pageService->findModuleName(),
            'moduleCode'  => $this->pageService->findModuleCode(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Pages', 'url' => route('admin.pages.index')],
                ['name' => 'Server-Side List', 'url' => route('admin.pages.list')],
            ],
            'ajaxEndpoint' => route('admin.pages.list.data'),
        ]);
    }

    /**
     * Handle Server-Side DataTable Ajax JSON request.
     */
    public function listAjaxData(Request $request): JsonResponse
    {
        if (!ModuleAccess::can($this->pageService->findModuleCode(), 'can_read')) {
            return response()->json(['message' => 'Unauthorized module access.'], 403);
        }

        $perPage = (int) $request->input('per_page', 15);
        $search = $request->input('search');
        $orderBy = $request->input('order_by', 'created_at');
        $orderDir = strtolower($request->input('order_dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $allowedSort = array (
  0 => 'slug',
  1 => 'layout',
  2 => 'meta_description',
  3 => 'title',
  4 => 'subtitle',
  5 => 'description',
  6 => 'background',
  7 => 'is_published',
  8 => 'published_at',
  9 => 'published_by',
  10 => 'published_by_name',
  11 => 'created_at',
  12 => 'is_active',
);
        if (!in_array($orderBy, $allowedSort)) {
            $orderBy = 'created_at';
        }

        $searchColumns = array (
  0 => 'slug',
  1 => 'layout',
  2 => 'meta_description',
  3 => 'title',
  4 => 'subtitle',
  5 => 'description',
  6 => 'background',
  7 => 'is_published',
  8 => 'published_at',
  9 => 'published_by',
  10 => 'published_by_name',
);

        $res = $this->pageService->paginate($perPage, $search, $searchColumns, $orderBy, $orderDir);
        $paginator = $res['data'];

        $items = collect($paginator->items())->map(function ($item, $idx) {
            $initials = strtoupper(substr($item->slug ?? 'I', 0, 2));
            $avatarClass = 'ma-' . (($idx % 6) + 1);

            $actionsHtml = '<div class="data-cell-actions" style="justify-content: flex-end;">'
                . btn_detail(route('admin.pages.show', $item->id), 'View details') . ' '
                . btn_edit(route('admin.pages.edit', $item->id), 'Edit Page') . ' '
                . btn_delete(route('admin.pages.destroy', $item->id), null, 'Delete Page', null, $item->slug ?? 'Page')
                . '</div>';

            return [
                'id'           => $item->id,
                'short_id'     => substr($item->id, 0, 8) . '…',
                'slug' => $item->slug ?? '-',
                'layout' => $item->layout ?? '-',
                'meta_description' => $item->meta_description ?? '-',
                'title' => $item->title ?? '-',
                'subtitle' => $item->subtitle ?? '-',
                'description' => $item->description ?? '-',
                'background' => $item->background ?? '-',
                'is_published' => $item->is_published ?? '-',
                'published_at' => $item->published_at ?? '-',
                'published_by' => $item->published_by ?? '-',
                'published_by_name' => $item->published_by_name ?? '-',
                'is_active'    => (bool) $item->is_active,
                'status_html'  => $item->is_active
                    ? '<span class="badge success dot">' . __('label.active') . '</span>'
                    : '<span class="badge dot" style="color:var(--t-light)">' . __('label.inactive') . '</span>',
                'initials'     => $initials,
                'avatar_class' => $avatarClass,
                'detail_url'   => route('admin.pages.show', $item->id),
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
        if (!ModuleAccess::can($this->pageService->findModuleCode(), 'can_create')) {
            abort(403, 'Unauthorized module access.');
        }

        return view('admin.sections.pages.pages_add', [
            'moduleName'  => $this->pageService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Pages', 'url' => route('admin.pages.index')],
                ['name' => 'Add Page', 'url' => route('admin.pages.add')],
            ],
        ]);
    }

    public function show(string $id): View
    {
        if (!ModuleAccess::can($this->pageService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->pageService->find($id);
        if (!$res['success']) {
            abort(404, 'Record not found.');
        }

        return view('admin.sections.pages.pages_detail', [
            'item'        => $res['data'],
            'moduleName'  => $this->pageService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Pages', 'url' => route('admin.pages.index')],
                ['name' => 'Page Details', 'url' => route('admin.pages.show', $id)],
            ],
        ]);
    }

    public function edit(string $id): View
    {
        if (!ModuleAccess::can($this->pageService->findModuleCode(), 'can_update')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->pageService->find($id);
        if (!$res['success']) {
            abort(404, 'Record not found.');
        }

        return view('admin.sections.pages.pages_edit', [
            'item'        => $res['data'],
            'moduleName'  => $this->pageService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Pages', 'url' => route('admin.pages.index')],
                ['name' => 'Edit Page', 'url' => route('admin.pages.edit', $id)],
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (!ModuleAccess::can($this->pageService->findModuleCode(), 'can_create')) {
            abort(403, 'Unauthorized module access.');
        }

        // ponytail: no validation on create
        $this->pageService->create($request->all());

        return redirect()->route('admin.pages.index')->with('success', __('message.created_success'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        if (!ModuleAccess::can($this->pageService->findModuleCode(), 'can_update')) {
            abort(403, 'Unauthorized module access.');
        }

        // ponytail: no validation on update
        $this->pageService->update($id, $request->all());

        return redirect()->route('admin.pages.index')->with('success', __('message.updated_success'));
    }

    public function destroy(string $id): RedirectResponse
    {
        if (!ModuleAccess::can($this->pageService->findModuleCode(), 'can_delete')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->pageService->delete($id);
        if (!$res['success']) {
            return redirect()->route('admin.pages.index')->with('error', $res['message']);
        }

        return redirect()->route('admin.pages.index')->with('success', __('message.deleted_success'));
    }
}