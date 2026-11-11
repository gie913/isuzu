<?php

namespace App\Http\Controllers\Web;

use App\Facades\ModuleAccess;
use App\Http\Controllers\Controller;
use App\Services\MessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    protected MessageService $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    /**
     * Display client-side datatable list view.
     */
    public function index(Request $request): View
    {
        if (!ModuleAccess::can($this->messageService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        $result = $this->messageService->all();

        return view('admin.sections.messages.messages_list', [
            'items'       => $result['data'],
            'moduleName'  => $this->messageService->findModuleName(),
            'moduleCode'  => $this->messageService->findModuleCode(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Messages', 'url' => route('admin.messages.index')],
            ],
        ]);
    }

    /**
     * Display server-side ajax datatable list view.
     */
    public function listAjaxView(Request $request): View
    {
        if (!ModuleAccess::can($this->messageService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        return view('admin.sections.messages.messages_list_ajax', [
            'moduleName'  => $this->messageService->findModuleName(),
            'moduleCode'  => $this->messageService->findModuleCode(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Messages', 'url' => route('admin.messages.index')],
                ['name' => 'Server-Side List', 'url' => route('admin.messages.list')],
            ],
            'ajaxEndpoint' => route('admin.messages.list.data'),
        ]);
    }

    /**
     * Handle Server-Side DataTable Ajax JSON request.
     */
    public function listAjaxData(Request $request): JsonResponse
    {
        if (!ModuleAccess::can($this->messageService->findModuleCode(), 'can_read')) {
            return response()->json(['message' => 'Unauthorized module access.'], 403);
        }

        $perPage = (int) $request->input('per_page', 15);
        $search = $request->input('search');
        $orderBy = $request->input('order_by', 'created_at');
        $orderDir = strtolower($request->input('order_dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $allowedSort = array (
  0 => 'from_user_id',
  1 => 'to_user_id',
  2 => 'name',
  3 => 'phone',
  4 => 'email',
  5 => 'address',
  6 => 'category',
  7 => 'message',
  8 => 'status',
  9 => 'is_replied',
  10 => 'replied_at',
  11 => 'read_at',
  12 => 'created_at',
  13 => 'is_active',
);
        if (!in_array($orderBy, $allowedSort)) {
            $orderBy = 'created_at';
        }

        $searchColumns = array (
  0 => 'from_user_id',
  1 => 'to_user_id',
  2 => 'name',
  3 => 'phone',
  4 => 'email',
  5 => 'address',
  6 => 'category',
  7 => 'message',
  8 => 'status',
  9 => 'is_replied',
  10 => 'replied_at',
  11 => 'read_at',
);

        $res = $this->messageService->paginate($perPage, $search, $searchColumns, $orderBy, $orderDir);
        $paginator = $res['data'];

        $items = collect($paginator->items())->map(function ($item, $idx) {
            $initials = strtoupper(substr($item->from_user_id ?? 'I', 0, 2));
            $avatarClass = 'ma-' . (($idx % 6) + 1);

            $actionsHtml = '<div class="data-cell-actions" style="justify-content: flex-end;">'
                . btn_detail(route('admin.messages.show', $item->id), 'View details') . ' '
                . btn_edit(route('admin.messages.edit', $item->id), 'Edit Message') . ' '
                . btn_delete(route('admin.messages.destroy', $item->id), null, 'Delete Message', null, $item->from_user_id ?? 'Message')
                . '</div>';

            return [
                'id'           => $item->id,
                'short_id'     => substr($item->id, 0, 8) . '…',
                'from_user_id' => $item->from_user_id ?? '-',
                'to_user_id' => $item->to_user_id ?? '-',
                'name' => $item->name ?? '-',
                'phone' => $item->phone ?? '-',
                'email' => $item->email ?? '-',
                'address' => $item->address ?? '-',
                'category' => $item->category ?? '-',
                'message' => $item->message ?? '-',
                'status' => $item->status ?? '-',
                'is_replied' => $item->is_replied ?? '-',
                'replied_at' => $item->replied_at ?? '-',
                'read_at' => $item->read_at ?? '-',
                'is_active'    => (bool) $item->is_active,
                'status_html'  => $item->is_active
                    ? '<span class="badge success dot">' . __('label.active') . '</span>'
                    : '<span class="badge dot" style="color:var(--t-light)">' . __('label.inactive') . '</span>',
                'initials'     => $initials,
                'avatar_class' => $avatarClass,
                'detail_url'   => route('admin.messages.show', $item->id),
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
        if (!ModuleAccess::can($this->messageService->findModuleCode(), 'can_create')) {
            abort(403, 'Unauthorized module access.');
        }

        return view('admin.sections.messages.messages_add', [
            'moduleName'  => $this->messageService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Messages', 'url' => route('admin.messages.index')],
                ['name' => 'Add Message', 'url' => route('admin.messages.add')],
            ],
        ]);
    }

    public function show(string $id): View
    {
        if (!ModuleAccess::can($this->messageService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->messageService->find($id);
        if (!$res['success']) {
            abort(404, 'Record not found.');
        }

        return view('admin.sections.messages.messages_detail', [
            'item'        => $res['data'],
            'moduleName'  => $this->messageService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Messages', 'url' => route('admin.messages.index')],
                ['name' => 'Message Details', 'url' => route('admin.messages.show', $id)],
            ],
        ]);
    }

    public function edit(string $id): View
    {
        if (!ModuleAccess::can($this->messageService->findModuleCode(), 'can_update')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->messageService->find($id);
        if (!$res['success']) {
            abort(404, 'Record not found.');
        }

        return view('admin.sections.messages.messages_edit', [
            'item'        => $res['data'],
            'moduleName'  => $this->messageService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Messages', 'url' => route('admin.messages.index')],
                ['name' => 'Edit Message', 'url' => route('admin.messages.edit', $id)],
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (!ModuleAccess::can($this->messageService->findModuleCode(), 'can_create')) {
            abort(403, 'Unauthorized module access.');
        }

        // ponytail: no validation on create
        $this->messageService->create($request->all());

        return redirect()->route('admin.messages.index')->with('success', __('message.created_success'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        if (!ModuleAccess::can($this->messageService->findModuleCode(), 'can_update')) {
            abort(403, 'Unauthorized module access.');
        }

        // ponytail: no validation on update
        $this->messageService->update($id, $request->all());

        return redirect()->route('admin.messages.index')->with('success', __('message.updated_success'));
    }

    public function destroy(string $id): RedirectResponse
    {
        if (!ModuleAccess::can($this->messageService->findModuleCode(), 'can_delete')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->messageService->delete($id);
        if (!$res['success']) {
            return redirect()->route('admin.messages.index')->with('error', $res['message']);
        }

        return redirect()->route('admin.messages.index')->with('success', __('message.deleted_success'));
    }
}