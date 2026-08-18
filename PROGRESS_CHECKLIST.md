# CMS Project Progress Checklist

Based on a review of the current codebase against `Copy of Laravel Practice Project.md` (updated 2026-08-18).

## Legend
- [x] Done
- [~] Started / partial
- [ ] Not started

---

## Database Design & Migrations

- [x] Migrations for images, pages, authors, categories, tags, blogs, blog_tag (pivot)
- [x] Many-to-many pivot table `blog_tags` for posts/tags
- [x] `personal_access_tokens` table (Sanctum) added
- [ ] Menus table
- [ ] Menu items table
- [ ] Settings table
- [x] CMS users table extended with `active_status` (boolean, default true)
- [~] Soft deletes — added to Pages, Blogs, Authors, Images; still missing on Categories, Tags, and Users
- [~] Unique constraints — present on page title/slug, blog title/slug, images.stored_filename, users.email; still missing on `tags.slug` and `categories.slug`
- [ ] ERD / database design documentation (answers to the 20 design questions in section 60)
- [ ] Documented decision on slug generation approach (auto vs supplied)
- [ ] Documented decision on settings storage approach (single row vs key-value)

## Models & Relationships

- [x] Models: User, Page, Blog, Author, Category, Tag, Blog_tag, Image
- [~] Relationships defined (belongsTo/hasMany/belongsToMany) — present and largely consistent; `Image::pages()` keys off `content_image` and `Image::author()` off `profile_pic`, worth a final correctness pass
- [ ] Query scopes (published pages/posts, draft posts, active categories/authors) — controllers currently inline `when()` filters instead of model scopes
- [~] Soft deletes trait applied — Author, Blog, Image, Page use `SoftDeletes`; Category, Tag, User do not
- [ ] Menu / MenuItem / Setting models

## Authentication (Sanctum)

- [x] Laravel Sanctum installed/configured (`laravel/sanctum` in composer.json, `config/sanctum.php`, `personal_access_tokens` migration, `HasApiTokens` on `User`)
- [x] Login endpoint (`POST /login` → `Api\AuthController::login`, issues a plain-text token)
- [x] Logout endpoint (`POST /logout`, deletes current access token) — correctly placed inside `auth:sanctum` group
- [ ] Current authenticated user endpoint (`/me`-style) — not implemented; only `GET /logged` exists, which lists *all* currently-logged-in users, not "who am I"
- [x] Sensitive fields hidden from user responses (`#[Hidden(['password', 'remember_token'])]` on `User`)
- [~] Management routes protected by `auth:sanctum` — Category/Image/Tag/Blog/Author/Page resources and `/restore`, `/delete` are protected; **`Route::apiResource('user', ...)` is registered outside the `auth:sanctum` group**, so all user CRUD endpoints are currently unauthenticated

## CMS Users

- [~] User CRUD — `index`, `show`, `store`, `destroy` implemented; `update()` has bugs: never returns a response, unconditionally requires `password` in the request body, and compares the plaintext validated password against the already-hashed stored password (`$user->password != $validated['password']`), so it will effectively always fail
- [~] Form Requests for user create/update — both classes exist, but `UpdateUserRequest::authorize()` returns `false` unconditionally (see Known Issues below), so the update endpoint 403s regardless of the controller bugs above
- [x] User API Resource (`UserResource`, `UserCollection`)
- [x] Unique email validation on create (`unique:users,email`); update's rule doesn't exclude the current user's own row, so re-submitting an unchanged email would fail
- [x] Seeder for CMS users (`UserSeeder`, wired into `DatabaseSeeder`)

## Pages Module

- [x] Migration, Model, Factory, Seeder
- [x] Form Requests (Store/Update) created
- [~] `index()` — filters by `publish_status` query param, no pagination/search/sort
- [x] `store()` — implemented
- [x] `update()` — implemented
- [x] `destroy()` — implemented (soft delete via `SoftDeletes`)
- [x] Slug uniqueness — enforced at the DB level (`pages.slug` unique) and auto-generated in `Page::booted()`
- [ ] Publication status transition rules (draft → published → archived) documented/enforced
- [ ] Published date logic on status change
- [ ] Search (title/content)
- [ ] Pagination
- [x] Public read-only endpoints (`GET /page`, `GET /page/{page:slug}`) restricted to `published` status

## Blog Posts Module

- [x] Migration, Model, Factory, Seeder
- [x] Form Requests (Store/Update) created
- [~] `index()` — filters by `publication_status` query param, no pagination/search/sort
- [x] `store()` — implemented
- [x] `update()` — implemented
- [x] `destroy()` — implemented (soft delete via `SoftDeletes`)
- [ ] Tag assignment (attach/sync tags on create/update) — still not implemented; Store/Update Blog Requests don't accept `tag_ids` and controllers never call `->tags()->sync()`
- [ ] Search (title/excerpt/content)
- [ ] Filtering (category, author, tag) — status filtering exists, others don't
- [ ] Date range filtering (published from/until)
- [ ] Sorting (newest/oldest/title/publication date)
- [ ] Pagination
- [ ] View counter
- [ ] Popular posts endpoint
- [ ] Related posts endpoint
- [x] Public read-only endpoints (`GET /blog`, `GET /blog/{blog:slug}`) restricted to `published` status

## Authors Module

- [x] Migration, Model, Factory, Seeder, Form Requests
- [x] `index()`, `store()`, `show()` implemented
- [x] `update()` — implemented
- [x] `destroy()` — implemented (soft delete via `SoftDeletes`; no separate "deactivate" endpoint, uses the existing `active` boolean column manually via update)
- [ ] Search by name
- [ ] Pagination
- [ ] N+1 query care on posts-by-author endpoint (needs review once implemented)

## Categories Module

- [x] Migration, Model, Factory, Seeder, Form Requests
- [x] `index()`, `store()`, `show()` implemented
- [x] `update()` — implemented
- [x] `destroy()` — implemented, but **no soft deletes on Category** (unlike Author/Blog/Image/Page), and blogs `cascadeOnDelete()` on `category_id`, so deleting a category hard-deletes its blogs too — worth revisiting
- [x] Filter posts by category endpoint (`GET /categories/{category}` → `viewBlogsForCategory`, paginated)

## Tags Module

- [x] Migration, Model, Factory, Seeder, Form Requests
- [x] `index()`, `show()` implemented
- [x] `store()` — implemented
- [x] `update()` — implemented
- [x] `destroy()` — implemented (hard delete; no soft deletes on Tag)
- [ ] Validation that supplied tag IDs exist when assigning to posts — moot until Blog tag assignment (above) is implemented

## Media (Images) Module

- [x] Migration, Model, Factory, Seeder, Form Requests
- [x] `index()`, `show()` implemented
- [x] `store()` — implemented, but takes `file_path` as a validated field on the model rather than handling an actual uploaded file via `Storage`/`UploadedFile::store()` — no real file upload handling yet
- [x] `update()` (metadata: caption, filename, date) — implemented
- [x] `destroy()` — implemented (soft delete via `SoftDeletes`)
- [~] File upload validation (type, size) — MIME type validated (`mimes:png,jpg,jpeg`), no explicit max size rule
- [ ] Laravel Storage abstraction usage (filesystem driver based storage, not hardcoded paths) — not yet using `Storage::` facade
- [ ] Search by filename/caption

## Navigation Menus & Menu Items

- [ ] Not started at all — no migration, model, controller, or routes for Menus or Menu Items

## Website Settings

- [ ] Not started — no migration, model, or controller for Settings

## API Resources

- [x] Resource + Collection classes created for Page, Blog, Author, Category, Tag, Image, User
- [ ] Menu / Menu Item Resource
- [~] Conditional relationship loading exists in controllers (via `$request->has()`) but should be reviewed for correctness/consistency
- [ ] Separate public vs management resource shapes (not yet decided/implemented)

## Route Model Binding

- [x] Standard ID-based RMB used in most show/update/destroy signatures
- [x] Slug-based Route Model Binding in use for public routes (`/api/page/{page:slug}`, `/api/blog/{blog:slug}`)

## Validation

- [x] Form Requests exist for User, Page, Blog, Author, Category, Tag, Image (store & update), plus `AuthUserRequest` for login
- [ ] **Critical bug** — see "Known Issues" below: most Store/Update requests currently reject every request via `authorize()`
- [~] Content of validation rules — mostly reasonable (required/unique/exists checks present); a couple of copy-paste mistakes found (see Known Issues)

## Query Features (Search / Filter / Sort / Pagination)

- [~] Search — not implemented on any resource
- [~] Filtering — basic single-field filtering exists on Page/Blog (`publish_status`/`publication_status`) and Author/Category (`active`); no filtering on other fields
- [ ] Sorting — not implemented
- [~] Pagination — implemented only on `CategoryController::viewBlogsForCategory` (`paginate(5)`); every other `index()` still uses `get()`/`all()`

## Public API

- [x] Public read-only routes (no Sanctum) — implemented for Pages, Blogs, Categories (`routes/web.php`)
- [x] Published-only filtering for public Page/Blog endpoints
- [ ] Public menu retrieval endpoint — blocked on Menus module not existing

## Testing

- [ ] Only the default `ExampleTest.php` exists — no feature tests for auth, pages, posts, categories, tags, authors, media, menus, or public API

## Documentation & Deliverables

- [ ] README (install/run instructions)
- [ ] ERD
- [ ] Database design documentation
- [ ] API documentation (endpoints, request/response examples)
- [ ] Postman/Bruno collection

---

## Known Issues Found In This Review

1. **`authorize()` returns `false` on most Form Requests, blocking those endpoints entirely regardless of auth state:** `StoreCategoryRequest`, `StorePageRequest`, `StoreTagRequest`, `StoreImageRequest`, and **every** `Update*Request` (Author, Blog, Category, Image, Page, Tag, User) return `false`. Only `StoreUserRequest`, `StoreBlogRequest`, `StoreAuthorRequest`, and `AuthUserRequest` return `true`. As written, `store()` for Category/Page/Tag/Image and `update()` everywhere will always fail with a 403, even though the controller logic behind them is implemented. This is almost certainly leftover scaffolding that needs to be flipped to `true` (or a real policy check) before those routes are usable.
2. **`user` API resource routes are unprotected** — `Route::apiResource('user', UserController::class)` in `routes/api.php` sits above the `auth:sanctum` group, so user list/create/read/update/delete are all publicly reachable today.
3. **`UserController::update()`** never returns a response, always requires a `password` field, and compares the incoming plaintext password against the already-hashed stored password — this comparison will essentially never succeed.
4. **`StoreBlogRequest`** validates `image_id` and `author_id` against the wrong tables (`exists:categories,id` and `exists:author,id` — table is `authors`, not `author`); likely copy-paste from `category_id`'s rule.
5. **Category delete cascades to blogs** (`blogs.category_id` has `cascadeOnDelete()`), while Category itself has no soft deletes — deleting a category permanently deletes all blogs in it, which may not be the intended behavior given the `RecoverController` restore/force-delete pattern used elsewhere.

## Summary

**What's solidly in place:**
- Core schema and migrations for Pages, Blogs, Authors, Categories, Tags, Images, Users, plus the Blog↔Tag pivot and Sanctum's `personal_access_tokens` table
- Models, Factories, Seeders for all of the above, including a wired-in `UserSeeder`
- Full CRUD controller logic now written for every module (Pages, Blogs, Authors, Categories, Tags, Images, Users) — `store`/`update`/`destroy` are no longer stubs anywhere
- Sanctum authentication installed and largely wired up: login, logout, hidden sensitive fields, most management routes behind `auth:sanctum`
- Soft deletes + a dedicated `RecoverController` (restore / force-delete) for Authors, Blogs, Images, Pages
- Public, unauthenticated read-only endpoints for Pages, Blogs, and Categories, correctly filtered to published/active records
- Slug-based route model binding on public routes

**Biggest gaps to close next:**
1. **Fix the `authorize() => false` scaffolding bug** — this is currently the single highest-impact issue, silently blocking most create/update endpoints.
2. **Secure the `user` routes** — move `Route::apiResource('user', ...)` inside the `auth:sanctum` group (or decide it's intentionally open for registration and scope it down).
3. **Fix `UserController::update()`** — missing return, bad password-comparison logic.
4. **Blog↔Tag assignment** — still no way to attach/sync tags from the API despite the pivot existing.
5. **Menus and Settings** — entire modules haven't been started.
6. **Query features** — search, sorting, and pagination are still mostly missing (only one endpoint paginates).
7. **Testing & docs** — no feature tests beyond the default stub; no README/ERD/API docs/Postman collection.
