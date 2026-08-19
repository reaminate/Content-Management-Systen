# CMS Project Progress Checklist

Based on a review of the current codebase against `Copy of Laravel Practice Project.md` (updated 2026-08-19).

## This Iteration (2026-08-19, uncommitted working-tree changes)

- [x] **Known Issue #1 fixed** — every Form Request's `authorize()` now returns `true` (was `false` on most Store/Update requests, 403-blocking those endpoints)
- [x] Real file upload handling for Images — `store()`/`update()` now accept an `image` file field, save it via `Storage::disk('public')`, and populate `original_filename`/`file_type`/`filesize` from the uploaded file instead of a raw `file_path` string
- [x] `Image::booted()` now deletes the stored file from disk on `forceDeleted` (previously it derived metadata fields from a `file_path` on `creating`, which no longer fits the new upload flow)
- [x] Blog tag assignment on **create** — `StoreBlogRequest` accepts `tags[]`, `BlogController::store()` syncs them
- [~] `StoreBlogRequest.author_id` exists-rule fixed (`exists:authors,id`, was wrongly `exists:categories,id`'s sibling bug `exists:author,id`) — **`UpdateBlogRequest` still has the old `exists:author,id` bug**, see Known Issues
- [x] `RecoverController` restore/delete routes now take `/{id}` (previously had no route parameter at all, so they couldn't target a specific record)
- [x] `id` added to `BlogResource`/`BlogCollection` output (was missing)
- [x] Most Update Form Requests switched required fields to `sometimes` so partial updates don't demand every field
- [ ] New test fixture `tests/Fixtures/images/11-jpeg-11.jpg` added but unused — no image upload feature test written yet
- [~] **Menu & Item modules scaffolded** (untracked, not yet committed) — migrations, models, factories, seeders, controllers, Form Requests, Resources for both; wired into `DatabaseSeeder` and `routes/api.php`. See Known Issues #9–12 for bugs introduced with this scaffolding.

## Legend
- [x] Done
- [~] Started / partial
- [ ] Not started

---

## Database Design & Migrations

- [x] Migrations for images, pages, authors, categories, tags, blogs, blog_tag (pivot)
- [x] Many-to-many pivot table `blog_tags` for posts/tags
- [x] `personal_access_tokens` table (Sanctum) added
- [x] Menus table (`menus`: name/identifier unique, description, active_status) — untracked, uncommitted
- [x] Items table (`items`: label, url, order, `menu_id` cascadeOnDelete, nullable `page_id` cascadeOnDelete) — untracked, uncommitted
- [ ] Settings table
- [x] CMS users table extended with `active_status` (boolean, default true)
- [~] Soft deletes — added to Pages, Blogs, Authors, Images; still missing on Categories, Tags, Users, and the new Menu/Item tables
- [~] Unique constraints — present on page title/slug, blog title/slug, images.stored_filename, users.email, menus.name/identifier; still missing on `tags.slug` and `categories.slug`
- [ ] ERD / database design documentation (answers to the 20 design questions in section 60)
- [ ] Documented decision on slug generation approach (auto vs supplied)
- [ ] Documented decision on settings storage approach (single row vs key-value)

## Models & Relationships

- [x] Models: User, Page, Blog, Author, Category, Tag, Blog_tag, Image, Menu, Item
- [~] Relationships defined (belongsTo/hasMany/belongsToMany) — present and largely consistent; `Image::pages()` keys off `content_image` and `Image::author()` off `profile_pic`, worth a final correctness pass; `Menu::items()`/`Item::menu()`/`Item::page()` added, look correct
- [ ] Query scopes (published pages/posts, draft posts, active categories/authors) — controllers currently inline `when()` filters instead of model scopes
- [~] Soft deletes trait applied — Author, Blog, Image, Page use `SoftDeletes`; Category, Tag, User, Menu, Item do not
- [~] Menu / Item models — implemented (`Menu` auto-slugs `identifier` from `name`, `Item` auto-slugs `url` from `label`, both via `booted()`); Setting model not started

## Authentication (Sanctum)

- [x] Laravel Sanctum installed/configured (`laravel/sanctum` in composer.json, `config/sanctum.php`, `personal_access_tokens` migration, `HasApiTokens` on `User`)
- [x] Login endpoint (`POST /login` → `Api\AuthController::login`, issues a plain-text token)
- [x] Logout endpoint (`POST /logout`, deletes current access token) — correctly placed inside `auth:sanctum` group
- [ ] Current authenticated user endpoint (`/me`-style) — not implemented; only `GET /logged` exists, which lists *all* currently-logged-in users, not "who am I"
- [x] Sensitive fields hidden from user responses (`#[Hidden(['password', 'remember_token'])]` on `User`)
- [~] Management routes protected by `auth:sanctum` — Category/Image/Tag/Blog/Author/Page resources and `/restore/{id}`, `/delete/{id}` are protected; **`Route::apiResource('user', ...)` is still registered outside the `auth:sanctum` group** (unchanged this iteration), so all user CRUD endpoints remain unauthenticated

## CMS Users

- [~] User CRUD — `index`, `show`, `store`, `destroy` implemented; `update()` **still has all its original bugs, unchanged this iteration**: never returns a response, never calls `$user->save()` so nothing persists even when it reaches that point, unconditionally requires `password` in the request body, and compares the plaintext validated password against the already-hashed stored password (`$user->password != $validated['password']`), so it will effectively always fail
- [x] `authorize()` fixed on all User Form Requests (was `false`, now `true`) — the update endpoint no longer 403s before reaching the controller bugs above
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
- [~] Tag assignment (attach/sync tags on create/update) — **create is done**: `StoreBlogRequest` accepts `tags[]`, `BlogController::store()` calls `->tags()->sync()`; **update is still missing** — `UpdateBlogRequest` also accepts `tags[]` now, but `BlogController::update()` never reads or syncs it
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
- [~] `index()`, `show()` implemented — `index()`'s `?author=` filter is broken: `$images->where('for_author','=',null)` runs on the already-fetched `Collection` from `Image::all()` and its return value is discarded, so the filter has no effect
- [x] `store()` — **now handles a real uploaded file**: `image` field validated as `file|image`, saved via `Storage::disk('public')->store()`, with `original_filename`/`file_type`/`filesize` derived from the `UploadedFile` object
- [x] `update()` — now also handles a replacement upload: deletes the old file from disk and stores the new one when an `image` field is present; metadata-only updates (caption, filename) still work
- [x] `destroy()` — implemented (soft delete via `SoftDeletes`); `Image::booted()` now also deletes the file from `Storage` on `forceDeleted`
- [~] File upload validation (type, size) — MIME type validated (`mimes:jpeg,jpg,png`); size caps now present but **inconsistent between requests**: `StoreImageRequest` allows `max:81912` (~80MB) while `UpdateImageRequest` allows `max:2048` (~2MB) — likely a typo on one side, worth reconciling
- [x] Laravel Storage abstraction usage — `Storage::disk('public')` now used for both storing and deleting files, replacing the old hardcoded-path approach
- [ ] Search by filename/caption

## Navigation Menus & Menu Items

- [x] Migration, Model, Factory, Seeder for both Menu and Item (untracked, uncommitted)
- [x] Form Requests (Store/Update) created for both
- [x] `index()`, `show()` implemented for both (no pagination/search/sort)
- [x] `MenuController::store()` — implemented and looks correct
- [x] `ItemController::store()` — **crashes on every call**: `StoreItemRequest::rules()` calls `$this->route('item')`, which is `null` on a create request (no `{item}` route segment), so `$item->menu_id` on the next line throws
- [x] `MenuController::update()` — **broken**: `description` and `active_status` branches both assign into `$menu->name` (copy-paste bug), so only `name` can ever actually change
- [x] `ItemController::update()` — implemented, looks correct
- [x] `destroy()` — implemented for both, but **hard delete only** — no `SoftDeletes`, not integrated with `RecoverController`
- [ ] `page_id` exists-rule table typo — `StoreItemRequest`/`UpdateItemRequest` both check `exists:page,id`; the table is `pages`, so this will always fail validation
- [ ] Routes unprotected — `Route::apiResource('menu', ...)` and `('item', ...)` sit above the `auth:sanctum` group in `routes/api.php`, same issue as the known `user` route bug
- [ ] Public menu retrieval endpoint — not added to `routes/web.php`
- [ ] Item ordering — `order` uniqueness enforced per-menu via `gt`/`between` rules computed from sibling rows; unverified since store is currently broken
- [ ] No feature tests

## Website Settings

- [ ] Not started — no migration, model, or controller for Settings

## API Resources

- [x] Resource + Collection classes created for Page, Blog, Author, Category, Tag, Image, User, Menu, Item
- [~] Conditional relationship loading exists in controllers (via `$request->has()`) but should be reviewed for correctness/consistency
- [ ] Separate public vs management resource shapes (not yet decided/implemented)

## Route Model Binding

- [x] Standard ID-based RMB used in most show/update/destroy signatures
- [x] Slug-based Route Model Binding in use for public routes (`/api/page/{page:slug}`, `/api/blog/{blog:slug}`)

## Validation

- [x] Form Requests exist for User, Page, Blog, Author, Category, Tag, Image (store & update), plus `AuthUserRequest` for login
- [x] **Critical bug fixed** — all Store/Update requests now return `true` from `authorize()` (was `false` on most, silently 403-ing those endpoints)
- [~] Content of validation rules — mostly reasonable (required/unique/exists checks present); most Update requests switched to `sometimes` for partial updates; one copy-paste mistake remains (see Known Issues)

## Query Features (Search / Filter / Sort / Pagination)

- [~] Search — not implemented on any resource
- [~] Filtering — basic single-field filtering exists on Page/Blog (`publish_status`/`publication_status`) and Author/Category (`active`); no filtering on other fields
- [ ] Sorting — not implemented
- [~] Pagination — implemented only on `CategoryController::viewBlogsForCategory` (`paginate(5)`); every other `index()` still uses `get()`/`all()`

## Public API

- [x] Public read-only routes (no Sanctum) — implemented for Pages, Blogs, Categories (`routes/web.php`)
- [x] Published-only filtering for public Page/Blog endpoints
- [ ] Public menu retrieval endpoint — Menu module now exists but no public route was added in `routes/web.php`

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

1. ~~`authorize()` returns `false` on most Form Requests~~ — **FIXED this iteration.** All Form Requests now return `true`.
2. **`user` API resource routes are still unprotected** — `Route::apiResource('user', UserController::class)` in `routes/api.php` sits above the `auth:sanctum` group, so user list/create/read/update/delete are all publicly reachable today. Unchanged this iteration.
3. **`UserController::update()`** still never returns a response, never calls `$user->save()`, always requires a `password` field, and compares the incoming plaintext password against the already-hashed stored password — this comparison will essentially never succeed, and even if it did nothing would persist. Unchanged this iteration.
4. **`image_id`/`author_id` exists-rules are still wrong in half the Blog requests.** `StoreBlogRequest.author_id` was fixed to `exists:authors,id` this iteration, but `image_id` still checks `exists:categories,id` in both Store and Update, and `UpdateBlogRequest.author_id` still checks the non-existent `author` table (`exists:author,id`) — likely copy-paste from `category_id`'s rule, only half-fixed.
5. **Category delete cascades to blogs** (`blogs.category_id` has `cascadeOnDelete()`), while Category itself has no soft deletes — deleting a category permanently deletes all blogs in it, which may not be the intended behavior given the `RecoverController` restore/force-delete pattern used elsewhere. Unchanged this iteration.
6. **`BlogController::update()` doesn't sync tags** — `UpdateBlogRequest` now validates a `tags[]` field (added this iteration) but the controller never reads or applies it, so tags can only be set on create, not changed afterward.
7. **`ImageController::index()`'s `?author=` filter is a no-op** — `$images->where('for_author', '=', null)` operates on the `Collection` returned by `Image::all()`; the filtered result is never assigned back or returned, so the query parameter has no effect. Introduced this iteration alongside the new upload handling.
8. **Inconsistent image size limits** — `StoreImageRequest` caps uploads at `max:81912` (~80MB) while `UpdateImageRequest` caps at `max:2048` (~2MB); one of these is almost certainly a typo. Introduced this iteration.
9. **`ItemController::store()` crashes on every request.** `StoreItemRequest::rules()` calls `$this->route('item')` to look up sibling ordering, but there's no `{item}` route parameter on the create endpoint — `$item` is `null`, so the next line (`$item->menu_id`) throws. Introduced this iteration, uncommitted.
10. **`page_id` exists-rule table typo** in both `StoreItemRequest` and `UpdateItemRequest` — checks `exists:page,id`, but the table is `pages`, so any `page_id` submitted will fail validation. Introduced this iteration, uncommitted.
11. **`MenuController::update()` only ever updates `name`.** The `description` and `active_status` branches both mistakenly assign into `$menu->name` instead of their own fields (copy-paste bug), so those two fields can never be changed via the API. Introduced this iteration, uncommitted.
12. **Menu/Item routes are unauthenticated** — `Route::apiResource('menu', ...)` and `('item', ...)` sit above the `auth:sanctum` group in `routes/api.php`, same pattern as the existing `user` route bug (Known Issue #2). Introduced this iteration, uncommitted.

## Summary

**What's solidly in place:**
- Core schema and migrations for Pages, Blogs, Authors, Categories, Tags, Images, Users, plus the Blog↔Tag pivot and Sanctum's `personal_access_tokens` table
- Models, Factories, Seeders for all of the above, including a wired-in `UserSeeder`
- Full CRUD controller logic now written for every module (Pages, Blogs, Authors, Categories, Tags, Images, Users) — `store`/`update`/`destroy` are no longer stubs anywhere
- **The `authorize() => false` scaffolding bug is fixed** — every Form Request now authorizes correctly, so no endpoint 403s before reaching controller logic
- **Real image upload handling** — Image store/update now work off an actual uploaded file via `Storage::disk('public')`, including cleanup on replace/force-delete
- **Blog tag assignment on create** — `->tags()->sync()` now runs in `BlogController::store()`
- Sanctum authentication installed and largely wired up: login, logout, hidden sensitive fields, most management routes behind `auth:sanctum`
- Soft deletes + a dedicated `RecoverController` (restore / force-delete, now correctly parameterized with `/{id}`) for Authors, Blogs, Images, Pages
- Public, unauthenticated read-only endpoints for Pages, Blogs, and Categories, correctly filtered to published/active records
- Slug-based route model binding on public routes

**Biggest gaps to close next:**
1. **Secure the `user` routes** — move `Route::apiResource('user', ...)` inside the `auth:sanctum` group (or decide it's intentionally open for registration and scope it down). Still open.
2. **Fix `UserController::update()`** — missing return, missing `$user->save()`, bad password-comparison logic. Still open, unchanged this iteration.
3. **Finish Blog↔Tag assignment** — sync on create works now; `BlogController::update()` still ignores the `tags[]` field entirely.
4. **Fix the remaining `image_id`/`author_id` exists-rule typos** in `UpdateBlogRequest` (and `image_id` in both Store and Update) — see Known Issue 4.
5. **Fix the new `ImageController::index()` author filter no-op** and reconcile the mismatched image size limits (Known Issues 7 & 8) — both introduced this iteration alongside the upload work.
6. **Menu/Item module needs debugging before it's usable** — scaffolding exists (uncommitted) but `ItemController::store()` crashes outright (Known Issue #9), `page_id` validation always fails (#10), `MenuController::update()` can't change description/active_status (#11), and the routes are wide open (#12). **Settings module hasn't been started at all.**
7. **Query features** — search, sorting, and pagination are still mostly missing (only one endpoint paginates).
8. **Testing & docs** — still only the default stub test, despite a new image fixture being added and a new Menu/Item module; no README/ERD/API docs/Postman collection.
