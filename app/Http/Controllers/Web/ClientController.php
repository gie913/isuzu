<?php

namespace App\Http\Controllers\Web;

use App\Facades\ModuleAccess;
use App\Http\Controllers\Controller;
use App\Services\ClientService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    protected ClientService $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    /**
     * Display client-side datatable list view.
     */
    public function index(Request $request): View
    {
        if (!ModuleAccess::can($this->clientService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        $result = $this->clientService->all();

        return view('admin.sections.clients.clients_list', [
            'items'       => $result['data'],
            'moduleName'  => $this->clientService->findModuleName(),
            'moduleCode'  => $this->clientService->findModuleCode(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Clients', 'url' => route('admin.clients.index')],
            ],
        ]);
    }

    /**
     * Display server-side ajax datatable list view.
     */
    public function listAjaxView(Request $request): View
    {
        if (!ModuleAccess::can($this->clientService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        return view('admin.sections.clients.clients_list_ajax', [
            'moduleName'  => $this->clientService->findModuleName(),
            'moduleCode'  => $this->clientService->findModuleCode(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Clients', 'url' => route('admin.clients.index')],
                ['name' => 'Server-Side List', 'url' => route('admin.clients.list')],
            ],
            'ajaxEndpoint' => route('admin.clients.list.data'),
        ]);
    }

    /**
     * Handle Server-Side DataTable Ajax JSON request.
     */
    public function listAjaxData(Request $request): JsonResponse
    {
        if (!ModuleAccess::can($this->clientService->findModuleCode(), 'can_read')) {
            return response()->json(['message' => 'Unauthorized module access.'], 403);
        }

        $perPage = (int) $request->input('per_page', 15);
        $search = $request->input('search');
        $orderBy = $request->input('order_by', 'created_at');
        $orderDir = strtolower($request->input('order_dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $allowedSort = array (
  0 => 'name',
  1 => 'description',
  2 => 'image_large',
  3 => 'image_small',
  4 => 'link',
  5 => 'link_text',
  6 => 'sort_number',
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
  0 => 'name',
  1 => 'description',
  2 => 'image_large',
  3 => 'image_small',
  4 => 'link',
  5 => 'link_text',
  6 => 'sort_number',
  7 => 'is_published',
  8 => 'published_at',
  9 => 'published_by',
  10 => 'published_by_name',
);

        $res = $this->clientService->paginate($perPage, $search, $searchColumns, $orderBy, $orderDir);
        $paginator = $res['data'];

        $items = collect($paginator->items())->map(function ($item, $idx) {
            $initials = strtoupper(substr($item->name ?? 'I', 0, 2));
            $avatarClass = 'ma-' . (($idx % 6) + 1);

            $actionsHtml = '<div class="data-cell-actions" style="justify-content: flex-end;">'
                . btn_detail(route('admin.clients.show', $item->id), 'View details') . ' '
                . btn_edit(route('admin.clients.edit', $item->id), 'Edit Client') . ' '
                . btn_delete(route('admin.clients.destroy', $item->id), null, 'Delete Client', null, $item->name ?? 'Client')
                . '</div>';

            return [
                'id'           => $item->id,
                'short_id'     => substr($item->id, 0, 8) . '…',
                'name' => $item->name ?? '-',
                'description' => $item->description ?? '-',
                'image_large' => $item->image_large ?? '-',
                'image_small' => $item->image_small ?? '-',
                'link' => $item->link ?? '-',
                'link_text' => $item->link_text ?? '-',
                'sort_number' => $item->sort_number ?? '-',
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
                'detail_url'   => route('admin.clients.show', $item->id),
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
        if (!ModuleAccess::can($this->clientService->findModuleCode(), 'can_create')) {
            abort(403, 'Unauthorized module access.');
        }

        return view('admin.sections.clients.clients_add', [
            'moduleName'  => $this->clientService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Clients', 'url' => route('admin.clients.index')],
                ['name' => 'Add Client', 'url' => route('admin.clients.add')],
            ],
        ]);
    }

    public function show(string $id): View
    {
        if (!ModuleAccess::can($this->clientService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->clientService->find($id);
        if (!$res['success']) {
            abort(404, 'Record not found.');
        }

        return view('admin.sections.clients.clients_detail', [
            'item'        => $res['data'],
            'moduleName'  => $this->clientService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Clients', 'url' => route('admin.clients.index')],
                ['name' => 'Client Details', 'url' => route('admin.clients.show', $id)],
            ],
        ]);
    }

    public function edit(string $id): View
    {
        if (!ModuleAccess::can($this->clientService->findModuleCode(), 'can_update')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->clientService->find($id);
        if (!$res['success']) {
            abort(404, 'Record not found.');
        }

        return view('admin.sections.clients.clients_edit', [
            'item'        => $res['data'],
            'moduleName'  => $this->clientService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Clients', 'url' => route('admin.clients.index')],
                ['name' => 'Edit Client', 'url' => route('admin.clients.edit', $id)],
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (!ModuleAccess::can($this->clientService->findModuleCode(), 'can_create')) {
            abort(403, 'Unauthorized module access.');
        }

        // ponytail: no validation on create
        $this->clientService->create($request->all());

        return redirect()->route('admin.clients.index')->with('success', __('message.created_success'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        if (!ModuleAccess::can($this->clientService->findModuleCode(), 'can_update')) {
            abort(403, 'Unauthorized module access.');
        }

        // ponytail: no validation on update
        $this->clientService->update($id, $request->all());

        return redirect()->route('admin.clients.index')->with('success', __('message.updated_success'));
    }

    public function destroy(string $id): RedirectResponse
    {
        if (!ModuleAccess::can($this->clientService->findModuleCode(), 'can_delete')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->clientService->delete($id);
        if (!$res['success']) {
            return redirect()->route('admin.clients.index')->with('error', $res['message']);
        }

        return redirect()->route('admin.clients.index')->with('success', __('message.deleted_success'));
    }
}