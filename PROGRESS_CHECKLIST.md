# CMS Project Progress Checklist

Based on a review of the current codebase against `Copy of Laravel Practice Project.md` (2026-08-17).

## Legend
- [x] Done
- [~] Started / partial
- [ ] Not started

---

## Database Design & Migrations

- [x] Migrations for images, pages, authors, categories, tags, blogs, blog_tag (pivot)
- [x] Many-to-many pivot table `blog_tags` for posts/tags
- [ ] Menus table
- [ ] Menu items table
- [ ] Settings table
- [ ] CMS users table extended with active status (currently uses default Laravel `users` migration only)
- [ ] Soft deletes on Pages, Posts, Authors, Media
- [~] Unique constraints — present on some fields (page title/slug, images stored_filename) but missing on tags.slug, categories.slug
- [ ] ERD / database design documentation (answers to the 20 design questions in section 60)
- [ ] Documented decision on slug generation approach (auto vs supplied)
- [ ] Documented decision on settings storage approach (single row vs key-value)

## Models & Relationships

- [x] Models: User, Page, Blog, Author, Category, Tag, Blog_tag, Image
- [~] Relationships defined (belongsTo/hasMany/belongsToMany) — present but need to verify correctness/completeness
- [ ] Query scopes (published pages/posts, draft posts, active categories/authors)
- [ ] Soft deletes trait applied
- [ ] Menu / MenuItem / Setting models

## Authentication (Sanctum)

- [ ] Laravel Sanctum installed/configured (not in composer.json yet)
- [ ] Login endpoint
- [ ] Logout endpoint (token invalidation)
- [ ] Current authenticated user endpoint
- [ ] Sensitive fields hidden from user responses
- [ ] All management routes protected by `auth:sanctum` middleware (routes currently unprotected)

## CMS Users

- [ ] User CRUD (UserController is entirely empty stubs)
- [ ] Form Requests for user create/update
- [ ] User API Resource
- [ ] Unique email validation
- [ ] Seeder for CMS users (users factory exists but no dedicated seeder wired in DatabaseSeeder)

## Pages Module

- [x] Migration, Model, Factory, Seeder
- [x] Form Requests (Store/Update) created
- [~] `index()` — lists all, no pagination/search/filter/sort
- [x] `store()` — implemented
- [ ] `update()` — stub only
- [ ] `destroy()` — stub only
- [x] `show()` — uses Route Model Binding, conditional eager loading
- [ ] Slug uniqueness handling / duplicate-slug error response
- [ ] Publication status transition rules (draft → published → archived) documented/enforced
- [ ] Published date logic on status change
- [ ] Search (title/content)
- [ ] Pagination

## Blog Posts Module

- [x] Migration, Model, Factory, Seeder
- [x] Form Requests (Store/Update) created
- [~] `index()` — lists all, no pagination/search/filter/sort
- [ ] `store()` — stub only (not implemented)
- [ ] `update()` — stub only
- [ ] `destroy()` — stub only
- [x] `show()` — Route Model Binding + conditional eager loading
- [ ] Tag assignment (attach/sync tags on create/update) — not implemented since store/update are empty
- [ ] Search (title/excerpt/content)
- [ ] Filtering (status, category, author, tag)
- [ ] Date range filtering (published from/until)
- [ ] Sorting (newest/oldest/title/publication date)
- [ ] Pagination
- [ ] View counter
- [ ] Popular posts endpoint
- [ ] Related posts endpoint

## Authors Module

- [x] Migration, Model, Factory, Seeder, Form Requests
- [x] `index()`, `store()`, `show()` implemented
- [ ] `update()` — stub only
- [ ] `destroy()` / deactivate — stub only
- [ ] Search by name
- [ ] Pagination
- [ ] N+1 query care on posts-by-author endpoint (needs review once implemented)

## Categories Module

- [x] Migration, Model, Factory, Seeder, Form Requests
- [x] `index()`, `store()`, `show()` implemented
- [ ] `update()` — stub only
- [ ] `destroy()` — stub only (no decision yet on what happens to posts referencing a deleted category)
- [ ] Filter posts by category endpoint

## Tags Module

- [x] Migration, Model, Factory, Seeder, Form Requests
- [x] `index()`, `show()` implemented
- [ ] `store()` — stub only
- [ ] `update()` — stub only
- [ ] `destroy()` — stub only
- [ ] Validation that supplied tag IDs exist when assigning to posts

## Media (Images) Module

- [x] Migration, Model, Factory, Seeder, Form Requests
- [x] `index()`, `show()` implemented
- [ ] `store()` (actual file upload) — stub only
- [ ] `update()` (metadata: alt text/caption) — stub only
- [ ] `destroy()` — stub only
- [ ] File upload validation (type, size)
- [ ] Laravel Storage abstraction usage (filesystem driver based storage, not hardcoded paths)
- [ ] Search by filename/alt text

## Navigation Menus & Menu Items

- [ ] Not started at all — no migration, model, controller, or routes for Menus or Menu Items

## Website Settings

- [ ] Not started — no migration, model, or controller for Settings

## API Resources

- [x] Resource + Collection classes created for Page, Blog, Author, Category, Tag, Image
- [ ] User Resource
- [ ] Menu / Menu Item Resource
- [~] Conditional relationship loading exists in controllers (via `$request->has()`) but should be reviewed for correctness/consistency
- [ ] Separate public vs management resource shapes (not yet decided/implemented)

## Route Model Binding

- [x] Standard ID-based RMB used in show/update/destroy signatures
- [ ] Slug-based Route Model Binding for at least one resource (e.g. `/api/pages/{page:slug}`)

## Validation

- [x] Form Requests exist for Page, Blog, Author, Category, Tag, Image (store & update)
- [ ] Content of validation rules needs verification (unique slugs, existing FK IDs, valid statuses/dates, positive order values, etc. — not yet reviewed/confirmed complete)

## Query Features (Search / Filter / Sort / Pagination)

- [ ] Search — not implemented on any resource
- [ ] Filtering — not implemented
- [ ] Sorting — not implemented
- [ ] Pagination — not implemented anywhere (`index()` methods use `all()`)

## Public API

- [ ] Public read-only routes (no Sanctum) — not implemented at all
- [ ] Published-only filtering for public endpoints
- [ ] Public menu retrieval endpoint

## Testing

- [ ] Only the default `ExampleTest.php` exists — no feature tests for auth, pages, posts, categories, tags, authors, media, menus, or public API

## Documentation & Deliverables

- [ ] README (install/run instructions)
- [ ] ERD
- [ ] Database design documentation
- [ ] API documentation (endpoints, request/response examples)
- [ ] Postman/Bruno collection

---

## Summary

**What's solidly in place:**
- Core schema and migrations for Pages, Blogs, Authors, Categories, Tags, Images, plus the Blog↔Tag pivot
- Models, Factories, Seeders for all of the above
- Form Request classes and API Resource/Collection classes scaffolded for every core module
- `index`/`show` largely working with basic conditional eager loading; `store` works for Page/Author/Category

**Biggest gaps to close next:**
1. **Authentication** — Sanctum isn't even installed yet; nothing is protected.
2. **CRUD completeness** — `update()` and `destroy()` are empty stubs across every controller; `store()` is also empty for Blog and Tag.
3. **Menus and Settings** — entire modules haven't been started.
4. **Query features** — no search, filtering, sorting, or pagination anywhere yet.
5. **Public API** — no unauthenticated read-only endpoints exist yet.
6. **Testing & docs** — no feature tests beyond the default stub; no README/ERD/API docs/Postman collection.
