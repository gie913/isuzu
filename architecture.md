# System Architecture Blueprint Prompt

Copy and paste the prompt below into any AI coding assistant or give it to your development team to replicate this architectural pattern in a new project.

---

```markdown
You are a senior software architect. Implement the following project architecture pattern based on a robust Laravel Service-Repository and modular Layered Architecture.

### 1. High-Level Architectural Rules
- **No queries in Controllers or Services**: All DB interactions, queries, and persistence belong in **Repositories**.
- **No business logic in Controllers or Repositories**: All domain logic, external API calls, orchestration, and business checks belong in **Services**.
- **Dual Controllers**: Strict separation between **Web Controllers** (returns Blade views, handles session/web auth, breadcrumbs, ACL) and **API Controllers** (stateless, returns standardized JSON response).
- **Audit & Soft Deletion**: Models and base repositories track UUIDs, `created_by`, `updated_by`, and soft-status `is_active`.

---

### 2. Core Architecture Components

#### A. Repositories Layer (`app/Repositories/Sql/`)
- Base class: `SqlBaseRepository` providing CRUD:
  - `all()` (filters by `is_active = true`)
  - `find($id)` (filters by `id` and `is_active = true`)
  - `create($model)` (auto-generates UUID v1 and sets `created_by = Auth::id()`)
  - `update($model)` (sets `updated_by = Auth::id()`)
  - `delete($id)` (soft delete: sets `is_active = 0`, sets `updated_by`)
  - `hardDelete($id)`
- Concrete classes (e.g. `Sql[Entity]Repository` extends `SqlBaseRepository`):
  - Encapsulate custom filtering, complex joins, aggregation, and ordering.

#### B. Services Layer (`app/Services/`)
- Base class: `BaseService` injecting repository:
  - Standard delegate methods: `all()`, `find($id)`, `create($data)`, `update($data)`, `delete($id)`.
- Concrete service classes (e.g. `[Entity]Service` extends `BaseService`):
  - Handle business validation, authorization decisions, status transitions, data transformation.
  - Return standardized response structures, e.g. `['success' => bool, 'data' => mixed, 'message' => string]`.
  - Provide module metadata (`findModuleCode()`, `findModuleName()`).

#### C. Controllers Layer (`app/Http/Controllers/`)
- **Web Controllers (`app/Http/Controllers/Web/`)**:
  - Extend base `Controller`.
  - Inject relevant `[Entity]Service`.
  - Handle locale resolution from query string (`?lang=`) or session fallback (no `{lang}` route prefix/segment).
  - Check role/module permissions via Access Facade/Helper (e.g. `abort(403)` if unauthorized).
  - Prepare view data: breadcrumbs, metadata, access rights, API endpoints for AJAX/delete, and render Blade view.
- **API Controllers (`app/Http/Controllers/Api/`)**:
  - Extend `BaseController` (defines `sendResponse($message, $data)` and `sendError($error, $messages, $code)`).
  - Inject relevant `[Entity]Service`.
  - Parse request payloads, pass to Service, and return JSON.
  - **Auth / Login (`AuthController`)**:
    - Uses **Laravel Passport** (OAuth2 / Password grant / Personal Access Client).
    - Validates credentials, issues Passport access tokens (`$user->createToken('Personal Access Token')->accessToken` or OAuth2 client grant flow).
    - Protects API routes with `auth:api` middleware.

#### D. Models Layer (`app/Models/`)
- Eloquent models configured for UUID primary keys (`$keyType = 'string'`, `$incrementing = false`).
- `User` model integrates `Laravel\Passport\HasApiTokens` alongside `Authenticatable`.
- Define `$fillable`, `$casts`, and Eloquent relationships (`belongsTo`, `hasMany`, `belongsToMany`).
- No direct database queries or raw SQL logic inside models.

#### E. Middleware Layer (`app/Http/Middleware/`)
- `LanguageMiddleware` / `LangMiddleware`: Resolve locale from query string `?lang=` (fallback to session/config), persist to session if provided, set `app()->setLocale($lang)`.
- `AuthorizationMiddleware`: Verify role-based permissions against module codes and actions (`can_read`, `can_create`, `can_update`, `can_delete`).
- `HttpLogMiddleware`: Audit log incoming requests, user agent, IP address, and response statuses.

#### F. Providers Layer (`app/Providers/`)
- Dedicated Service Providers per module/domain (e.g. `AppServiceProvider`, `BaseSetupServiceProvider`, `CMSAccessProvider`).
- Bind repositories/services into the service container.
- Register singleton helpers, view composers, and custom Blade directives.

#### G. Helpers & Facades Layer (`app/Helpers/` & `app/Facades/`)
- Static helpers and Facades for transversal concerns:
  - `CMSAccessMenuHelper` / `CMSAccessHelper`: Menu rendering, access permission checks.
  - `GeneralHelper`: String formatting, reference generation, date transforms.
  - `TranslationHelper`: Dynamic translation lookups.
  - `NotificationHelper`: System alerts and notification dispatches.

#### H. Localization / Languages (`resources/lang/` or `lang/`)
- Organized by locale subdirectories (`en/`, `id/`, etc.).
- Categorized files: `label.php`, `form.php`, `message.php`, `validation.php`.
- Views and controllers reference translations via `__('file.key')`.

#### I. Blade Views (`resources/views/`)
- Structured under `resources/views/admin/sections/[module]/`:
  - `[module]_list.blade.php`: DataTable / listing, action buttons (add, edit, delete modals/links).
  - `[module]_add.blade.php`: Create form with CSRF and validation alerts.
  - `[module]_edit.blade.php`: Update form prefilled with record data.
  - Partials: `modals/`, `cards/`, `components/`.
- Views consume data passed by Web Controller only; zero business computation in templates.

---

### 3. Target Directory Tree

```text
app/
├── Facades/
├── Helpers/
├── Http/
│   ├── Controllers/
│   │   ├── Api/
│   │   │   ├── BaseController.php
│   │   │   └── [Entity]Controller.php
│   │   ├── Controller.php
│   │   └── Web/
│   │       └── [Entity]Controller.php
│   ├── Middleware/
│   │   ├── AuthorizationMiddleware.php
│   │   ├── HttpLogMiddleware.php
│   │   └── LangMiddleware.php
│   └── Requests/
├── Models/
│   └── [Entity].php
├── Providers/
├── Repositories/
│   └── Sql/
│       ├── SqlBaseRepository.php
│       └── Sql[Entity]Repository.php
└── Services/
    ├── BaseService.php
    └── [Entity]Service.php
lang/ (or resources/lang/)
├── en/
└── id/
resources/
└── views/
    └── admin/
        └── sections/
            └── [module]/
                ├── [module]_list.blade.php
                ├── [module]_add.blade.php
                └── [module]_edit.blade.php
routes/
├── api.php
└── web.php
```

---

### 4. Implementation Steps for Any New Module
1. **Migration & Model**: Create table with UUID `id`, audit columns (`created_by`, `updated_by`), and `is_active`. Define relations in Model.
2. **Repository**: Create `Sql[Entity]Repository` extending `SqlBaseRepository`. Implement entity queries.
3. **Service**: Create `[Entity]Service` extending `BaseService`. Inject repository. Add business logic and response array formatting.
4. **Controllers**:
   - Create `Api/[Entity]Controller` with `create`, `update`, `delete`, `all`.
   - Create `Web/[Entity]Controller` with `index`, `ajax`, `add`, `edit`, checking access permissions.
5. **Views**: Build Blade templates under `resources/views/admin/sections/[entity]/`.
6. **Routes & Auth**:
   - Register Web routes standardly (locale handled globally via `LangMiddleware` reading `?lang=`).
   - Register API routes under `api/v1/[module]` protected with `auth:api` middleware.
   - Configure Laravel Passport (`php artisan passport:client --personal` or password grant client) for token-based authentication via `POST /api/v1/auth/login`.
```
