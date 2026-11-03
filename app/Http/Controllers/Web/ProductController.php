<?php

namespace App\Http\Controllers\Web;

use App\Facades\ModuleAccess;
use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display client-side datatable list view.
     */
    public function index(Request $request): View
    {
        if (!ModuleAccess::can($this->productService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        $result = $this->productService->all();

        return view('admin.sections.products.products_list', [
            'items'       => $result['data'],
            'moduleName'  => $this->productService->findModuleName(),
            'moduleCode'  => $this->productService->findModuleCode(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Products', 'url' => route('admin.products.index')],
            ],
        ]);
    }

    /**
     * Display server-side ajax datatable list view.
     */
    public function listAjaxView(Request $request): View
    {
        if (!ModuleAccess::can($this->productService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        return view('admin.sections.products.products_list_ajax', [
            'moduleName'  => $this->productService->findModuleName(),
            'moduleCode'  => $this->productService->findModuleCode(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Products', 'url' => route('admin.products.index')],
                ['name' => 'Server-Side List', 'url' => route('admin.products.list')],
            ],
            'ajaxEndpoint' => route('admin.products.list.data'),
        ]);
    }

    /**
     * Handle Server-Side DataTable Ajax JSON request.
     */
    public function listAjaxData(Request $request): JsonResponse
    {
        if (!ModuleAccess::can($this->productService->findModuleCode(), 'can_read')) {
            return response()->json(['message' => 'Unauthorized module access.'], 403);
        }

        $perPage = (int) $request->input('per_page', 15);
        $search = $request->input('search');
        $orderBy = $request->input('order_by', 'created_at');
        $orderDir = strtolower($request->input('order_dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $allowedSort = array (
  0 => 'slug',
  1 => 'meta_description',
  2 => 'name',
  3 => 'm_product_type_id',
  4 => 'm_product_category_id',
  5 => 'wheel_configuration_id',
  6 => 'load_capacity_id',
  7 => 'wheelbase_id',
  8 => 'horse_power_id',
  9 => 'seating_capacity_id',
  10 => 'production_year',
  11 => 'price',
  12 => 'description',
  13 => 'image1_src',
  14 => 'image2_src',
  15 => 'image3_src',
  16 => 'image4_src',
  17 => 'image5_src',
  18 => 'file',
  19 => 'tag',
  20 => 'is_published',
  21 => 'published_at',
  22 => 'published_by',
  23 => 'published_by_name',
  24 => 'show_as_banner',
  25 => 'total_views',
  26 => 'created_at',
  27 => 'is_active',
);
        if (!in_array($orderBy, $allowedSort)) {
            $orderBy = 'created_at';
        }

        $searchColumns = array (
  0 => 'slug',
  1 => 'meta_description',
  2 => 'name',
  3 => 'm_product_type_id',
  4 => 'm_product_category_id',
  5 => 'wheel_configuration_id',
  6 => 'load_capacity_id',
  7 => 'wheelbase_id',
  8 => 'horse_power_id',
  9 => 'seating_capacity_id',
  10 => 'production_year',
  11 => 'price',
  12 => 'description',
  13 => 'image1_src',
  14 => 'image2_src',
  15 => 'image3_src',
  16 => 'image4_src',
  17 => 'image5_src',
  18 => 'file',
  19 => 'tag',
  20 => 'is_published',
  21 => 'published_at',
  22 => 'published_by',
  23 => 'published_by_name',
  24 => 'show_as_banner',
  25 => 'total_views',
);

        $res = $this->productService->paginate($perPage, $search, $searchColumns, $orderBy, $orderDir);
        $paginator = $res['data'];

        $items = collect($paginator->items())->map(function ($item, $idx) {
            $initials = strtoupper(substr($item->slug ?? 'I', 0, 2));
            $avatarClass = 'ma-' . (($idx % 6) + 1);

            $actionsHtml = '<div class="data-cell-actions" style="justify-content: flex-end;">'
                . btn_detail(route('admin.products.show', $item->id), 'View details') . ' '
                . btn_edit(route('admin.products.edit', $item->id), 'Edit Products') . ' '
                . btn_delete(route('admin.products.destroy', $item->id), null, 'Delete Products', null, $item->slug ?? 'Products')
                . '</div>';

            return [
                'id'           => $item->id,
                'short_id'     => substr($item->id, 0, 8) . '…',
                'slug' => $item->slug ?? '-',
                'meta_description' => $item->meta_description ?? '-',
                'name' => $item->name ?? '-',
                'm_product_type_id' => $item->m_product_type_id ?? '-',
                'm_product_category_id' => $item->m_product_category_id ?? '-',
                'wheel_configuration_id' => $item->wheel_configuration_id ?? '-',
                'load_capacity_id' => $item->load_capacity_id ?? '-',
                'wheelbase_id' => $item->wheelbase_id ?? '-',
                'horse_power_id' => $item->horse_power_id ?? '-',
                'seating_capacity_id' => $item->seating_capacity_id ?? '-',
                'production_year' => $item->production_year ?? '-',
                'price' => $item->price ?? '-',
                'description' => $item->description ?? '-',
                'image1_src' => $item->image1_src ?? '-',
                'image2_src' => $item->image2_src ?? '-',
                'image3_src' => $item->image3_src ?? '-',
                'image4_src' => $item->image4_src ?? '-',
                'image5_src' => $item->image5_src ?? '-',
                'file' => $item->file ?? '-',
                'tag' => $item->tag ?? '-',
                'is_published' => $item->is_published ?? '-',
                'published_at' => $item->published_at ?? '-',
                'published_by' => $item->published_by ?? '-',
                'published_by_name' => $item->published_by_name ?? '-',
                'show_as_banner' => $item->show_as_banner ?? '-',
                'total_views' => $item->total_views ?? '-',
                'is_active'    => (bool) $item->is_active,
                'status_html'  => $item->is_active
                    ? '<span class="badge success dot">' . __('label.active') . '</span>'
                    : '<span class="badge dot" style="color:var(--t-light)">' . __('label.inactive') . '</span>',
                'initials'     => $initials,
                'avatar_class' => $avatarClass,
                'detail_url'   => route('admin.products.show', $item->id),
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
        if (!ModuleAccess::can($this->productService->findModuleCode(), 'can_create')) {
            abort(403, 'Unauthorized module access.');
        }

        return view('admin.sections.products.products_add', [
            'moduleName'  => $this->productService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Products', 'url' => route('admin.products.index')],
                ['name' => 'Add Products', 'url' => route('admin.products.add')],
            ],
        ]);
    }

    public function show(string $id): View
    {
        if (!ModuleAccess::can($this->productService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->productService->find($id);
        if (!$res['success']) {
            abort(404, 'Record not found.');
        }

        return view('admin.sections.products.products_detail', [
            'item'        => $res['data'],
            'moduleName'  => $this->productService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Products', 'url' => route('admin.products.index')],
                ['name' => 'Products Details', 'url' => route('admin.products.show', $id)],
            ],
        ]);
    }

    public function edit(string $id): View
    {
        if (!ModuleAccess::can($this->productService->findModuleCode(), 'can_update')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->productService->find($id);
        if (!$res['success']) {
            abort(404, 'Record not found.');
        }

        return view('admin.sections.products.products_edit', [
            'item'        => $res['data'],
            'moduleName'  => $this->productService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Products', 'url' => route('admin.products.index')],
                ['name' => 'Edit Products', 'url' => route('admin.products.edit', $id)],
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (!ModuleAccess::can($this->productService->findModuleCode(), 'can_create')) {
            abort(403, 'Unauthorized module access.');
        }

        // ponytail: no validation on create
        $this->productService->create($request->all());

        return redirect()->route('admin.products.index')->with('success', __('message.created_success'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        if (!ModuleAccess::can($this->productService->findModuleCode(), 'can_update')) {
            abort(403, 'Unauthorized module access.');
        }

        // ponytail: no validation on update
        $this->productService->update($id, $request->all());

        return redirect()->route('admin.products.index')->with('success', __('message.updated_success'));
    }

    public function destroy(string $id): RedirectResponse
    {
        if (!ModuleAccess::can($this->productService->findModuleCode(), 'can_delete')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->productService->delete($id);
        if (!$res['success']) {
            return redirect()->route('admin.products.index')->with('error', $res['message']);
        }

        return redirect()->route('admin.products.index')->with('success', __('message.deleted_success'));
    }
}