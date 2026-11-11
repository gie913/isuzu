<?php

namespace App\Http\Controllers\Web;

use App\Facades\ModuleAccess;
use App\Http\Controllers\Controller;
use App\Services\ContentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContentController extends Controller
{
    protected ContentService $contentService;

    public function __construct(ContentService $contentService)
    {
        $this->contentService = $contentService;
    }

    /**
     * Display client-side datatable list view.
     */
    public function index(Request $request): View
    {
        if (!ModuleAccess::can($this->contentService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        $result = $this->contentService->all();

        return view('admin.sections.contents.contents_list', [
            'items'       => $result['data'],
            'moduleName'  => $this->contentService->findModuleName(),
            'moduleCode'  => $this->contentService->findModuleCode(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Contents', 'url' => route('admin.contents.index')],
            ],
        ]);
    }

    /**
     * Display server-side ajax datatable list view.
     */
    public function listAjaxView(Request $request): View
    {
        if (!ModuleAccess::can($this->contentService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        return view('admin.sections.contents.contents_list_ajax', [
            'moduleName'  => $this->contentService->findModuleName(),
            'moduleCode'  => $this->contentService->findModuleCode(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Contents', 'url' => route('admin.contents.index')],
                ['name' => 'Server-Side List', 'url' => route('admin.contents.list')],
            ],
            'ajaxEndpoint' => route('admin.contents.list.data'),
        ]);
    }

    /**
     * Handle Server-Side DataTable Ajax JSON request.
     */
    public function listAjaxData(Request $request): JsonResponse
    {
        if (!ModuleAccess::can($this->contentService->findModuleCode(), 'can_read')) {
            return response()->json(['message' => 'Unauthorized module access.'], 403);
        }

        $perPage = (int) $request->input('per_page', 15);
        $search = $request->input('search');
        $orderBy = $request->input('order_by', 'created_at');
        $orderDir = strtolower($request->input('order_dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $allowedSort = array (
  0 => 'slug',
  1 => 't_page_id',
  2 => 'meta_description',
  3 => 'title',
  4 => 'subtitle',
  5 => 'description',
  6 => 'image_description',
  7 => 'image_large',
  8 => 'image_small',
  9 => 'file',
  10 => 'activity_datetime',
  11 => 'is_published',
  12 => 'published_at',
  13 => 'published_by',
  14 => 'published_by_name',
  15 => 'redirect_to',
  16 => 'location',
  17 => 'show_as_banner',
  18 => 'total_views',
  19 => 'created_at',
  20 => 'is_active',
);
        if (!in_array($orderBy, $allowedSort)) {
            $orderBy = 'created_at';
        }

        $searchColumns = array (
  0 => 'slug',
  1 => 't_page_id',
  2 => 'meta_description',
  3 => 'title',
  4 => 'subtitle',
  5 => 'description',
  6 => 'image_description',
  7 => 'image_large',
  8 => 'image_small',
  9 => 'file',
  10 => 'activity_datetime',
  11 => 'is_published',
  12 => 'published_at',
  13 => 'published_by',
  14 => 'published_by_name',
  15 => 'redirect_to',
  16 => 'location',
  17 => 'show_as_banner',
  18 => 'total_views',
);

        $res = $this->contentService->paginate($perPage, $search, $searchColumns, $orderBy, $orderDir);
        $paginator = $res['data'];

        $items = collect($paginator->items())->map(function ($item, $idx) {
            $initials = strtoupper(substr($item->slug ?? 'I', 0, 2));
            $avatarClass = 'ma-' . (($idx % 6) + 1);

            $actionsHtml = '<div class="data-cell-actions" style="justify-content: flex-end;">'
                . btn_detail(route('admin.contents.show', $item->id), 'View details') . ' '
                . btn_edit(route('admin.contents.edit', $item->id), 'Edit Content') . ' '
                . btn_delete(route('admin.contents.destroy', $item->id), null, 'Delete Content', null, $item->slug ?? 'Content')
                . '</div>';

            return [
                'id'           => $item->id,
                'short_id'     => substr($item->id, 0, 8) . '…',
                'slug' => $item->slug ?? '-',
                't_page_id' => $item->t_page_id ?? '-',
                'meta_description' => $item->meta_description ?? '-',
                'title' => $item->title ?? '-',
                'subtitle' => $item->subtitle ?? '-',
                'description' => $item->description ?? '-',
                'image_description' => $item->image_description ?? '-',
                'image_large' => $item->image_large ?? '-',
                'image_small' => $item->image_small ?? '-',
                'file' => $item->file ?? '-',
                'activity_datetime' => $item->activity_datetime ?? '-',
                'is_published' => $item->is_published ?? '-',
                'published_at' => $item->published_at ?? '-',
                'published_by' => $item->published_by ?? '-',
                'published_by_name' => $item->published_by_name ?? '-',
                'redirect_to' => $item->redirect_to ?? '-',
                'location' => $item->location ?? '-',
                'show_as_banner' => $item->show_as_banner ?? '-',
                'total_views' => $item->total_views ?? '-',
                'is_active'    => (bool) $item->is_active,
                'status_html'  => $item->is_active
                    ? '<span class="badge success dot">' . __('label.active') . '</span>'
                    : '<span class="badge dot" style="color:var(--t-light)">' . __('label.inactive') . '</span>',
                'initials'     => $initials,
                'avatar_class' => $avatarClass,
                'detail_url'   => route('admin.contents.show', $item->id),
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
        if (!ModuleAccess::can($this->contentService->findModuleCode(), 'can_create')) {
            abort(403, 'Unauthorized module access.');
        }

        return view('admin.sections.contents.contents_add', [
            'moduleName'  => $this->contentService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Contents', 'url' => route('admin.contents.index')],
                ['name' => 'Add Content', 'url' => route('admin.contents.add')],
            ],
        ]);
    }

    public function show(string $id): View
    {
        if (!ModuleAccess::can($this->contentService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->contentService->find($id);
        if (!$res['success']) {
            abort(404, 'Record not found.');
        }

        return view('admin.sections.contents.contents_detail', [
            'item'        => $res['data'],
            'moduleName'  => $this->contentService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Contents', 'url' => route('admin.contents.index')],
                ['name' => 'Content Details', 'url' => route('admin.contents.show', $id)],
            ],
        ]);
    }

    public function edit(string $id): View
    {
        if (!ModuleAccess::can($this->contentService->findModuleCode(), 'can_update')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->contentService->find($id);
        if (!$res['success']) {
            abort(404, 'Record not found.');
        }

        return view('admin.sections.contents.contents_edit', [
            'item'        => $res['data'],
            'moduleName'  => $this->contentService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Contents', 'url' => route('admin.contents.index')],
                ['name' => 'Edit Content', 'url' => route('admin.contents.edit', $id)],
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (!ModuleAccess::can($this->contentService->findModuleCode(), 'can_create')) {
            abort(403, 'Unauthorized module access.');
        }

        // ponytail: no validation on create
        $this->contentService->create($request->all());

        return redirect()->route('admin.contents.index')->with('success', __('message.created_success'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        if (!ModuleAccess::can($this->contentService->findModuleCode(), 'can_update')) {
            abort(403, 'Unauthorized module access.');
        }

        // ponytail: no validation on update
        $this->contentService->update($id, $request->all());

        return redirect()->route('admin.contents.index')->with('success', __('message.updated_success'));
    }

    public function destroy(string $id): RedirectResponse
    {
        if (!ModuleAccess::can($this->contentService->findModuleCode(), 'can_delete')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->contentService->delete($id);
        if (!$res['success']) {
            return redirect()->route('admin.contents.index')->with('error', $res['message']);
        }

        return redirect()->route('admin.contents.index')->with('success', __('message.deleted_success'));
    }
}