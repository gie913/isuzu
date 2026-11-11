<?php

namespace App\Http\Controllers\Web;

use App\Facades\ModuleAccess;
use App\Http\Controllers\Controller;
use App\Services\TestimonialService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    protected TestimonialService $testimonialService;

    public function __construct(TestimonialService $testimonialService)
    {
        $this->testimonialService = $testimonialService;
    }

    /**
     * Display client-side datatable list view.
     */
    public function index(Request $request): View
    {
        if (!ModuleAccess::can($this->testimonialService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        $result = $this->testimonialService->all();

        return view('admin.sections.testimonials.testimonials_list', [
            'items'       => $result['data'],
            'moduleName'  => $this->testimonialService->findModuleName(),
            'moduleCode'  => $this->testimonialService->findModuleCode(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Testimonials', 'url' => route('admin.testimonials.index')],
            ],
        ]);
    }

    /**
     * Display server-side ajax datatable list view.
     */
    public function listAjaxView(Request $request): View
    {
        if (!ModuleAccess::can($this->testimonialService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        return view('admin.sections.testimonials.testimonials_list_ajax', [
            'moduleName'  => $this->testimonialService->findModuleName(),
            'moduleCode'  => $this->testimonialService->findModuleCode(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Testimonials', 'url' => route('admin.testimonials.index')],
                ['name' => 'Server-Side List', 'url' => route('admin.testimonials.list')],
            ],
            'ajaxEndpoint' => route('admin.testimonials.list.data'),
        ]);
    }

    /**
     * Handle Server-Side DataTable Ajax JSON request.
     */
    public function listAjaxData(Request $request): JsonResponse
    {
        if (!ModuleAccess::can($this->testimonialService->findModuleCode(), 'can_read')) {
            return response()->json(['message' => 'Unauthorized module access.'], 403);
        }

        $perPage = (int) $request->input('per_page', 15);
        $search = $request->input('search');
        $orderBy = $request->input('order_by', 'created_at');
        $orderDir = strtolower($request->input('order_dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $allowedSort = array (
  0 => 'name',
  1 => 'occupation',
  2 => 'sort_number',
  3 => 'description',
  4 => 'image',
  5 => 'is_published',
  6 => 'published_at',
  7 => 'published_by_name',
  8 => 'created_at',
  9 => 'is_active',
);
        if (!in_array($orderBy, $allowedSort)) {
            $orderBy = 'created_at';
        }

        $searchColumns = array (
  0 => 'name',
  1 => 'occupation',
  2 => 'sort_number',
  3 => 'description',
  4 => 'image',
  5 => 'is_published',
  6 => 'published_at',
  7 => 'published_by_name',
);

        $res = $this->testimonialService->paginate($perPage, $search, $searchColumns, $orderBy, $orderDir);
        $paginator = $res['data'];

        $items = collect($paginator->items())->map(function ($item, $idx) {
            $initials = strtoupper(substr($item->name ?? 'I', 0, 2));
            $avatarClass = 'ma-' . (($idx % 6) + 1);

            $actionsHtml = '<div class="data-cell-actions" style="justify-content: flex-end;">'
                . btn_detail(route('admin.testimonials.show', $item->id), 'View details') . ' '
                . btn_edit(route('admin.testimonials.edit', $item->id), 'Edit Testimonial') . ' '
                . btn_delete(route('admin.testimonials.destroy', $item->id), null, 'Delete Testimonial', null, $item->name ?? 'Testimonial')
                . '</div>';

            return [
                'id'           => $item->id,
                'short_id'     => substr($item->id, 0, 8) . '…',
                'name' => $item->name ?? '-',
                'occupation' => $item->occupation ?? '-',
                'sort_number' => $item->sort_number ?? '-',
                'description' => $item->description ?? '-',
                'image' => $item->image ?? '-',
                'is_published' => $item->is_published ?? '-',
                'published_at' => $item->published_at ?? '-',
                'published_by_name' => $item->published_by_name ?? '-',
                'is_active'    => (bool) $item->is_active,
                'status_html'  => $item->is_active
                    ? '<span class="badge success dot">' . __('label.active') . '</span>'
                    : '<span class="badge dot" style="color:var(--t-light)">' . __('label.inactive') . '</span>',
                'initials'     => $initials,
                'avatar_class' => $avatarClass,
                'detail_url'   => route('admin.testimonials.show', $item->id),
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
        if (!ModuleAccess::can($this->testimonialService->findModuleCode(), 'can_create')) {
            abort(403, 'Unauthorized module access.');
        }

        return view('admin.sections.testimonials.testimonials_add', [
            'moduleName'  => $this->testimonialService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Testimonials', 'url' => route('admin.testimonials.index')],
                ['name' => 'Add Testimonial', 'url' => route('admin.testimonials.add')],
            ],
        ]);
    }

    public function show(string $id): View
    {
        if (!ModuleAccess::can($this->testimonialService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->testimonialService->find($id);
        if (!$res['success']) {
            abort(404, 'Record not found.');
        }

        return view('admin.sections.testimonials.testimonials_detail', [
            'item'        => $res['data'],
            'moduleName'  => $this->testimonialService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Testimonials', 'url' => route('admin.testimonials.index')],
                ['name' => 'Testimonial Details', 'url' => route('admin.testimonials.show', $id)],
            ],
        ]);
    }

    public function edit(string $id): View
    {
        if (!ModuleAccess::can($this->testimonialService->findModuleCode(), 'can_update')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->testimonialService->find($id);
        if (!$res['success']) {
            abort(404, 'Record not found.');
        }

        return view('admin.sections.testimonials.testimonials_edit', [
            'item'        => $res['data'],
            'moduleName'  => $this->testimonialService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Testimonials', 'url' => route('admin.testimonials.index')],
                ['name' => 'Edit Testimonial', 'url' => route('admin.testimonials.edit', $id)],
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (!ModuleAccess::can($this->testimonialService->findModuleCode(), 'can_create')) {
            abort(403, 'Unauthorized module access.');
        }

        // ponytail: no validation on create
        $this->testimonialService->create($request->all());

        return redirect()->route('admin.testimonials.index')->with('success', __('message.created_success'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        if (!ModuleAccess::can($this->testimonialService->findModuleCode(), 'can_update')) {
            abort(403, 'Unauthorized module access.');
        }

        // ponytail: no validation on update
        $this->testimonialService->update($id, $request->all());

        return redirect()->route('admin.testimonials.index')->with('success', __('message.updated_success'));
    }

    public function destroy(string $id): RedirectResponse
    {
        if (!ModuleAccess::can($this->testimonialService->findModuleCode(), 'can_delete')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->testimonialService->delete($id);
        if (!$res['success']) {
            return redirect()->route('admin.testimonials.index')->with('error', $res['message']);
        }

        return redirect()->route('admin.testimonials.index')->with('success', __('message.deleted_success'));
    }
}