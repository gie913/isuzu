<?php

namespace App\Http\Controllers\Web;

use App\Facades\ModuleAccess;
use App\Http\Controllers\Controller;
use App\Services\BannerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BannerController extends Controller
{
    protected BannerService $bannerService;

    public function __construct(BannerService $bannerService)
    {
        $this->bannerService = $bannerService;
    }

    /**
     * Display client-side datatable list view.
     */
    public function index(Request $request): View
    {
        if (!ModuleAccess::can($this->bannerService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        $result = $this->bannerService->all();

        return view('admin.sections.banners.banners_list', [
            'items'       => $result['data'],
            'moduleName'  => $this->bannerService->findModuleName(),
            'moduleCode'  => $this->bannerService->findModuleCode(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Banners', 'url' => route('admin.banners.index')],
            ],
        ]);
    }

    /**
     * Display server-side ajax datatable list view.
     */
    public function listAjaxView(Request $request): View
    {
        if (!ModuleAccess::can($this->bannerService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        return view('admin.sections.banners.banners_list_ajax', [
            'moduleName'  => $this->bannerService->findModuleName(),
            'moduleCode'  => $this->bannerService->findModuleCode(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Banners', 'url' => route('admin.banners.index')],
                ['name' => 'Server-Side List', 'url' => route('admin.banners.list')],
            ],
            'ajaxEndpoint' => route('admin.banners.list.data'),
        ]);
    }

    /**
     * Handle Server-Side DataTable Ajax JSON request.
     */
    public function listAjaxData(Request $request): JsonResponse
    {
        if (!ModuleAccess::can($this->bannerService->findModuleCode(), 'can_read')) {
            return response()->json(['message' => 'Unauthorized module access.'], 403);
        }

        $perPage = (int) $request->input('per_page', 15);
        $search = $request->input('search');
        $orderBy = $request->input('order_by', 'created_at');
        $orderDir = strtolower($request->input('order_dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $allowedSort = array (
  0 => 'title',
  1 => 'subtitle',
  2 => 'description',
  3 => 'image_large',
  4 => 'image_small',
  5 => 'link',
  6 => 'link_text',
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
  0 => 'title',
  1 => 'subtitle',
  2 => 'description',
  3 => 'image_large',
  4 => 'image_small',
  5 => 'link',
  6 => 'link_text',
  7 => 'is_published',
  8 => 'published_at',
  9 => 'published_by',
  10 => 'published_by_name',
);

        $res = $this->bannerService->paginate($perPage, $search, $searchColumns, $orderBy, $orderDir);
        $paginator = $res['data'];

        $items = collect($paginator->items())->map(function ($item, $idx) {
            $initials = strtoupper(substr($item->title ?? 'I', 0, 2));
            $avatarClass = 'ma-' . (($idx % 6) + 1);

            $actionsHtml = '<div class="data-cell-actions" style="justify-content: flex-end;">'
                . btn_detail(route('admin.banners.show', $item->id), 'View details') . ' '
                . btn_edit(route('admin.banners.edit', $item->id), 'Edit Banner') . ' '
                . btn_delete(route('admin.banners.destroy', $item->id), null, 'Delete Banner', null, $item->title ?? 'Banner')
                . '</div>';

            return [
                'id'           => $item->id,
                'short_id'     => substr($item->id, 0, 8) . '…',
                'title' => $item->title ?? '-',
                'subtitle' => $item->subtitle ?? '-',
                'description' => $item->description ?? '-',
                'image_large' => $item->image_large ?? '-',
                'image_small' => $item->image_small ?? '-',
                'link' => $item->link ?? '-',
                'link_text' => $item->link_text ?? '-',
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
                'detail_url'   => route('admin.banners.show', $item->id),
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
        if (!ModuleAccess::can($this->bannerService->findModuleCode(), 'can_create')) {
            abort(403, 'Unauthorized module access.');
        }

        return view('admin.sections.banners.banners_add', [
            'moduleName'  => $this->bannerService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Banners', 'url' => route('admin.banners.index')],
                ['name' => 'Add Banner', 'url' => route('admin.banners.add')],
            ],
        ]);
    }

    public function show(string $id): View
    {
        if (!ModuleAccess::can($this->bannerService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->bannerService->find($id);
        if (!$res['success']) {
            abort(404, 'Record not found.');
        }

        return view('admin.sections.banners.banners_detail', [
            'item'        => $res['data'],
            'moduleName'  => $this->bannerService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Banners', 'url' => route('admin.banners.index')],
                ['name' => 'Banner Details', 'url' => route('admin.banners.show', $id)],
            ],
        ]);
    }

    public function edit(string $id): View
    {
        if (!ModuleAccess::can($this->bannerService->findModuleCode(), 'can_update')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->bannerService->find($id);
        if (!$res['success']) {
            abort(404, 'Record not found.');
        }

        return view('admin.sections.banners.banners_edit', [
            'item'        => $res['data'],
            'moduleName'  => $this->bannerService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Banners', 'url' => route('admin.banners.index')],
                ['name' => 'Edit Banner', 'url' => route('admin.banners.edit', $id)],
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (!ModuleAccess::can($this->bannerService->findModuleCode(), 'can_create')) {
            abort(403, 'Unauthorized module access.');
        }

        // ponytail: no validation on create
        $this->bannerService->create($request->all());

        return redirect()->route('admin.banners.index')->with('success', __('message.created_success'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        if (!ModuleAccess::can($this->bannerService->findModuleCode(), 'can_update')) {
            abort(403, 'Unauthorized module access.');
        }

        // ponytail: no validation on update
        $this->bannerService->update($id, $request->all());

        return redirect()->route('admin.banners.index')->with('success', __('message.updated_success'));
    }

    public function destroy(string $id): RedirectResponse
    {
        if (!ModuleAccess::can($this->bannerService->findModuleCode(), 'can_delete')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->bannerService->delete($id);
        if (!$res['success']) {
            return redirect()->route('admin.banners.index')->with('error', $res['message']);
        }

        return redirect()->route('admin.banners.index')->with('success', __('message.deleted_success'));
    }
}