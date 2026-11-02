<?php

namespace App\Http\Controllers\Web;

use App\Facades\ModuleAccess;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Services\RoleService;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    protected UserService $userService;
    protected RoleService $roleService;

    public function __construct(UserService $userService, RoleService $roleService)
    {
        $this->userService = $userService;
        $this->roleService = $roleService;
    }

    /**
     * Display users list view.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        if (!ModuleAccess::can($this->userService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        $result = $this->userService->all();
        if ($result['success'] && $result['data'] instanceof \Illuminate\Database\Eloquent\Collection) {
            $result['data']->load('role');
        }

        return view('admin.sections.users.users_list', [
            'users'       => $result['data'],
            'moduleName'  => $this->userService->findModuleName(),
            'moduleCode'  => $this->userService->findModuleCode(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Users', 'url' => route('admin.users.index')],
            ],
            'apiEndpoints' => [
                'list'   => url('/api/v1/users'),
                'delete' => url('/api/v1/users'),
            ],
        ]);
    }

    /**
     * Display users list view with Server-Side Ajax DataTable.
     * Route: /admin/users/list
     *
     * @param Request $request
     * @return View
     */
    public function listAjaxView(Request $request): View
    {
        if (!ModuleAccess::can($this->userService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        return view('admin.sections.users.users_list_ajax', [
            'moduleName'  => $this->userService->findModuleName(),
            'moduleCode'  => $this->userService->findModuleCode(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Users', 'url' => route('admin.users.index')],
                ['name' => 'Server-Side List', 'url' => route('admin.users.list')],
            ],
            'ajaxEndpoint' => route('admin.users.list.data'),
        ]);
    }

    /**
     * Handle Server-Side DataTable Ajax JSON request.
     * Route: /admin/users/list/data
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listAjaxData(Request $request): \Illuminate\Http\JsonResponse
    {
        if (!ModuleAccess::can($this->userService->findModuleCode(), 'can_read')) {
            return response()->json(['message' => 'Unauthorized module access.'], 403);
        }

        $perPage = (int) $request->input('per_page', 15);
        $search = $request->input('search');
        $status = $request->input('status');
        $orderBy = $request->input('order_by', 'created_at');
        $orderDir = strtolower($request->input('order_dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $allowedSort = ['name', 'username', 'email', 'created_at', 'is_active'];
        if (!in_array($orderBy, $allowedSort)) {
            $orderBy = 'created_at';
        }

        $filters = [];
        if ($status !== null && $status !== '' && $status !== 'all') {
            $filters['is_active'] = $status === 'active' ? 1 : 0;
        }

        $searchColumns = ['name', 'username', 'email'];

        $res = $this->userService->paginate($perPage, $search, $searchColumns, $orderBy, $orderDir, $filters);
        $paginator = $res['data'];
        $paginator->load('role');

        $items = collect($paginator->items())->map(function ($user, $idx) use ($paginator) {
            $initials = strtoupper(substr($user->name ?? 'U', 0, 2));
            $avatarClass = 'ma-' . (($idx % 6) + 1);

            $actionsHtml = '<div class="data-cell-actions" style="justify-content: flex-end;">'
                . btn_detail(route('admin.users.show', $user->id), 'View details') . ' '
                . btn_edit(route('admin.users.edit', $user->id), 'Edit user') . ' '
                . btn_delete(route('admin.users.destroy', $user->id), null, 'Delete user', null, $user->name ?? $user->username ?? 'this user')
                . '</div>';

            $roleName = $user->role ? $user->role->name : '-';

            return [
                'id'         => $user->id,
                'short_id'   => substr($user->id, 0, 8) . '…',
                'name'       => $user->name,
                'username'   => $user->username ?? '-',
                'role_name'  => $roleName,
                'email'      => $user->email,
                'is_active'  => (bool) $user->is_active,
                'status_html'=> $user->is_active
                    ? '<span class="badge success dot">' . __('label.active') . '</span>'
                    : '<span class="badge dot" style="color:var(--t-light)">' . __('label.inactive') . '</span>',
                'initials'   => $initials,
                'avatar_class' => $avatarClass,
                'detail_url' => route('admin.users.show', $user->id),
                'created_at' => $user->created_at ? $user->created_at->format('M d · H:i') : '-',
                'actions'    => $actionsHtml,
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

    /**
     * Show form to add a user.
     *
     * @return View
     */
    public function add(): View
    {
        if (!ModuleAccess::can($this->userService->findModuleCode(), 'can_create')) {
            abort(403, 'Unauthorized module access.');
        }

        $rolesResult = $this->roleService->all();
        $roles = $rolesResult['success'] ? $rolesResult['data'] : collect();

        return view('admin.sections.users.users_add', [
            'roles'       => $roles,
            'moduleName'  => $this->userService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Users', 'url' => route('admin.users.index')],
                ['name' => 'Add User', 'url' => route('admin.users.add')],
            ],
        ]);
    }

    /**
     * Show form to edit an existing user.
     *
     * @param string $id
     * @return View
     */
    public function edit(string $id): View
    {
        if (!ModuleAccess::can($this->userService->findModuleCode(), 'can_update')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->userService->find($id);
        if (!$res['success']) {
            abort(404, 'User not found');
        }

        $rolesResult = $this->roleService->all();
        $roles = $rolesResult['success'] ? $rolesResult['data'] : collect();

        return view('admin.sections.users.users_edit', [
            'user'        => $res['data'],
            'roles'       => $roles,
            'moduleName'  => $this->userService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Users', 'url' => route('admin.users.index')],
                ['name' => 'Edit User', 'url' => route('admin.users.edit', $id)],
            ],
        ]);
    }

    /**
     * Handle store user via Web request.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        if (!ModuleAccess::can($this->userService->findModuleCode(), 'can_create')) {
            abort(403, 'Unauthorized module access.');
        }

        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'username'  => 'nullable|string|max:50|unique:m_user,username',
            'email'     => 'required|email|unique:m_user,email',
            'm_role_id' => 'nullable|uuid|exists:m_role,id',
            'password'  => 'required|string|min:8',
            'image_src' => 'nullable|string',
        ]);

        $this->userService->create($validated);

        return redirect()->route('admin.users.index')->with('success', __('message.created_success'));
    }

    /**
     * Handle update user via Web request.
     *
     * @param Request $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        if (!ModuleAccess::can($this->userService->findModuleCode(), 'can_update')) {
            abort(403, 'Unauthorized module access.');
        }

        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'username'  => 'nullable|string|max:50|unique:m_user,username,' . $id,
            'email'     => 'required|email|unique:m_user,email,' . $id,
            'm_role_id' => 'nullable|uuid|exists:m_role,id',
            'password'  => 'nullable|string|min:8',
            'image_src' => 'nullable|string',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $this->userService->update($id, $validated);

        return redirect()->route('admin.users.index')->with('success', __('message.updated_success'));
    }

    /**
     * Show detail of an existing user.
     *
     * @param string $id
     * @return View
     */
    public function show(string $id): View
    {
        if (!ModuleAccess::can($this->userService->findModuleCode(), 'can_read')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->userService->find($id);
        if (!$res['success']) {
            abort(404, 'User not found');
        }

        $res['data']->load('role');

        return view('admin.sections.users.users_detail', [
            'user'        => $res['data'],
            'moduleName'  => $this->userService->findModuleName(),
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Users', 'url' => route('admin.users.index')],
                ['name' => 'User Details', 'url' => route('admin.users.show', $id)],
            ],
        ]);
    }

    /**
     * Delete user via Web request.
     *
     * @param string $id
     * @return RedirectResponse
     */
    public function destroy(string $id): RedirectResponse
    {
        if (!ModuleAccess::can($this->userService->findModuleCode(), 'can_delete')) {
            abort(403, 'Unauthorized module access.');
        }

        $res = $this->userService->delete($id);
        if (!$res['success']) {
            return redirect()->route('admin.users.index')->with('error', $res['message']);
        }

        return redirect()->route('admin.users.index')->with('success', __('message.deleted_success'));
    }

    /**
     * Show logged in user's profile view ("My Profile").
     *
     * @return View
     */
    public function myProfile(): View
    {
        $user = Auth::user();
        if (!$user) {
            abort(401);
        }

        $user->load('role');

        return view('admin.sections.users.my_profile', [
            'user'        => $user,
            'moduleName'  => 'My Profile',
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'My Profile', 'url' => route('admin.my-profile')],
            ],
        ]);
    }

    /**
     * Update logged in user's profile information.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateMyProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if (!$user) {
            abort(401);
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:m_user,email,' . $user->id,
        ]);

        $this->userService->update($user->id, $validated);

        return redirect()->route('admin.my-profile')->with('success', 'Profile updated successfully.');
    }

    /**
     * Show change password form for authenticated user.
     *
     * @return View
     */
    public function changePassword(): View
    {
        $user = Auth::user();
        if (!$user) {
            abort(401);
        }

        return view('admin.sections.users.change_password', [
            'user'        => $user,
            'moduleName'  => 'Change Password',
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Change Password', 'url' => route('admin.change-password')],
            ],
        ]);
    }

    /**
     * Update authenticated user's password.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if (!$user) {
            abort(401);
        }

        $request->validate([
            'current_password'          => 'required|string',
            'password'                  => 'required|string|min:8|confirmed|different:current_password',
            'password_confirmation'     => 'required|string',
        ], [
            'current_password.required' => 'The old password is required.',
            'password.different'        => 'The new password must be different from the old password.',
            'password.confirmed'        => 'The new password confirmation does not match.',
        ]);

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return back()->withErrors([
                'current_password' => 'The provided old password does not match our records.',
            ]);
        }

        $this->userService->update($user->id, [
            'password' => Hash::make($request->input('password')),
        ]);

        return redirect()->route('admin.change-password')->with('success', 'Password changed successfully.');
    }
}
