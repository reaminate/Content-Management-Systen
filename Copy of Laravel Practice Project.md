# **Laravel Practice Project**

## **Content Management System (CMS) REST API**

## **1\. Project Overview**

The client requires a **Content Management System (CMS)** for managing the content of a company website.

The CMS will be developed as an **API-only Laravel application**. A frontend or administration interface is not required as part of this project.

The API will allow authenticated users to manage:

* Website pages  
* Blog posts  
* Post categories  
* Tags  
* Authors  
* Media records  
* Navigation menus  
* Menu items  
* Website settings

The project must demonstrate practical knowledge of Laravel API development, including:

* Laravel Sanctum authentication  
* REST API design  
* CRUD operations  
* Eloquent ORM  
* Eloquent relationships  
* Route Model Binding  
* Form Requests  
* API Resources  
* Pagination  
* Searching  
* Filtering  
* Sorting  
* Database transactions where appropriate  
* Factories  
* Seeders  
* Feature tests

The database design is **not provided by the client**.

The database structure must be designed from the requirements below before implementation begins.

---

# **2\. General Client Requirements**

The client requires a CMS that can store and manage the content displayed on a public website.

The CMS itself will only provide APIs.

A separate frontend application may later consume these APIs.

All CMS management endpoints must require authentication using Laravel Sanctum.

The project does **not** require:

* Roles  
* Permissions  
* Policies  
* User-level authorization  
* Public user registration  
* A frontend application

Any authenticated CMS user may manage the content.

---

# **3\. Authentication**

The client requires token-based authentication using **Laravel Sanctum**.

The following authentication operations are required:

* Login  
* Logout  
* Get currently authenticated user

Public registration is not required.

CMS users may be created through database seeders.

---

# **4\. Login**

The login endpoint must accept:

* Email  
* Password

The request must be validated.

When valid credentials are provided, the API must:

1. Authenticate the user.  
2. Generate a Sanctum access token.  
3. Return the access token.  
4. Return basic information about the authenticated user.

Invalid credentials must return an appropriate error response.

The token must be usable as a Bearer token when accessing protected CMS endpoints.

---

# **5\. Logout**

The client requires an endpoint for logging out.

Logout must invalidate the Sanctum token currently being used.

The following flow must work:

1. User logs in.  
2. API returns a token.  
3. Token is used to access protected endpoints.  
4. User logs out.  
5. The same token can no longer access protected endpoints.

---

# **6\. Current User**

The client requires an endpoint for retrieving information about the currently authenticated CMS user.

The response must use a Laravel API Resource.

Sensitive information must never be returned.

This includes:

* Password  
* Password hash  
* Remember token  
* Sanctum token information

---

# **7\. CMS Users**

The CMS must maintain basic information about users who can log into the system.

A CMS user may require information such as:

* Name  
* Email  
* Password  
* Active status

The exact database structure must be determined during database design.

The client requires CRUD operations for CMS users:

* Create user  
* List users  
* View user  
* Update user  
* Delete or deactivate user

Email addresses must be unique.

---

# **8\. Pages**

The client requires the ability to create normal website pages.

Examples include:

* About Us  
* Contact Us  
* Privacy Policy  
* Terms and Conditions  
* Careers  
* Our Services

A page must contain sufficient information to be displayed on a website.

Possible information includes:

* Title  
* Slug  
* Content  
* Short description  
* Publication status  
* Published date  
* SEO title  
* SEO description

The final fields and database types must be determined during database design.

---

# **9\. Page CRUD**

The following operations are required:

* Create page  
* List pages  
* View page  
* Update page  
* Delete page

Route Model Binding must be used when retrieving individual pages.

Form Requests must be used for creating and updating pages.

API Resources must be used when returning page data.

---

# **10\. Page Slugs**

Each page requires a URL-friendly slug.

For example:

Title:

`About Our Company`

Slug:

`about-our-company`

The client requires page slugs to be unique.

The implementation must handle attempts to create duplicate slugs.

Consider whether the slug should:

* always be supplied by the API consumer,  
* automatically be generated from the title,  
* or support both behaviours.

The chosen approach must be documented.

---

# **11\. Page Status**

Pages must support a publication status.

At minimum, consider:

* Draft  
* Published  
* Archived

A draft page exists in the CMS but is not considered publicly published.

A published page is available for use by the public website.

An archived page is retained but no longer actively published.

The exact database representation of status must be decided during implementation.

---

# **12\. Publishing Pages**

The client requires the system to know when a page was published.

When a page becomes published, an appropriate publication date must be maintained.

The implementation must consider what happens when:

* a draft becomes published,  
* a published page becomes draft again,  
* a published page becomes archived,  
* an archived page is republished.

These rules must be documented.

---

# **13\. Blog Posts**

The CMS must support blog/news posts.

Examples:

* Company launches new service  
* Office opening announcement  
* New product release  
* Company event highlights  
* Recruitment announcement

Blog posts are separate from normal website pages.

---

# **14\. Blog Post Information**

A blog post may require information such as:

* Title  
* Slug  
* Excerpt  
* Main content  
* Featured image  
* Author  
* Category  
* Publication status  
* Published date  
* SEO title  
* SEO description

The exact schema must be designed from these requirements.

---

# **15\. Blog Post CRUD**

The client requires:

* Create post  
* List posts  
* View post  
* Update post  
* Delete post

The implementation must use:

* Resource Controller where appropriate  
* Route Model Binding  
* Form Requests  
* Eloquent  
* API Resources

---

# **16\. Authors**

Every blog post must have an author.

The author represents the person whose name appears on the article.

An author may require:

* Name  
* Slug  
* Short biography  
* Profile image  
* Email or contact information if required  
* Active status

An author does not necessarily need to be a CMS login user.

For example, a company may publish an article written by a CEO who does not have CMS access.

The database must therefore represent authors appropriately.

---

# **17\. Author CRUD**

The client requires:

* Create author  
* List authors  
* View author  
* Update author  
* Delete/deactivate author

The author detail endpoint should be capable of returning information about posts written by that author.

Care must be taken to avoid unnecessary database queries.

---

# **18\. Post Categories**

Blog posts must be organized into categories.

Example categories:

* News  
* Technology  
* Business  
* Events  
* Careers  
* Product Updates

The client requires CRUD operations for categories.

A category may require:

* Name  
* Slug  
* Description  
* Active status

The exact database structure must be designed.

---

# **19\. Category Relationships**

A blog post must belong to a category.

A category may contain many blog posts.

The appropriate Eloquent relationships must be implemented.

The API should be able to perform operations such as:

* Retrieve posts belonging to a category  
* Filter posts by category  
* Return category information with a post

---

# **20\. Tags**

The client requires tags for more flexible content organization.

Example:

A post titled:

`Humanlot Launches New Attendance Features`

could have tags:

* HR  
* Attendance  
* SaaS  
* Product Update

A blog post may have multiple tags.

A tag may belong to multiple blog posts.

This requirement must be represented correctly in the relational database.

---

# **21\. Tag CRUD**

The client requires:

* Create tag  
* List tags  
* View tag  
* Update tag  
* Delete tag

A tag may contain:

* Name  
* Slug

Both post-to-tag and tag-to-post relationships must be accessible through Eloquent.

---

# **22\. Many-to-Many Relationship**

The tag requirement must demonstrate a many-to-many relationship.

The database design must determine:

* which tables are required,  
* how posts and tags are connected,  
* what foreign keys are required,  
* what unique constraints are appropriate.

Do not store tag IDs as:

`"2,5,7,9"`

inside a single post column.

The relationship must use proper relational database design.

---

# **23\. Assigning Tags to Posts**

When creating or updating a blog post, the API must support assigning multiple tags.

For example, a request may conceptually contain:

`tags: [2, 5, 8]`

Validation must ensure that the supplied tags exist.

The implementation should investigate the appropriate Eloquent methods for managing many-to-many relationships.

---

# **24\. Featured Images**

Pages and blog posts may require images.

For example:

* Featured blog image  
* Page banner  
* Author profile image

The client requires media information to be managed through the CMS.

Actual file upload support may be implemented after the basic Media module is complete.

---

# **25\. Media Library**

Create a Media module representing uploaded files.

A media record may require information such as:

* Original filename  
* Stored filename  
* File path  
* MIME type  
* File size  
* Alternative text  
* Caption  
* Upload date

The exact database structure must be designed.

---

# **26\. Media CRUD**

The client requires functionality to:

* Upload media  
* List media  
* View media details  
* Update media metadata  
* Delete media

Metadata updates may include:

* Alternative text  
* Caption

File validation must be implemented.

---

# **27\. File Upload Validation**

Media uploads must validate appropriate requirements.

Consider:

* Allowed file types  
* Maximum file size  
* Image MIME types  
* Invalid files  
* Missing files

The API must not simply accept every uploaded file.

The allowed file rules must be documented.

---

# **28\. Storage**

Uploaded files must use Laravel's filesystem abstraction.

The application should not depend on hard-coded absolute filesystem paths.

The implementation must understand how Laravel Storage can support different storage drivers.

For development, local/public storage may be used.

The design should allow another storage system to be introduced later without rewriting the entire Media module.

---

# **29\. Post Featured Media**

A blog post may optionally have a featured image.

The database design must determine how a post references a media record.

The API Resource for a blog post should return useful featured-image information when available.

---

# **30\. Navigation Menus**

The client requires navigation menus to be managed through the CMS.

Examples:

### **Main Navigation**

* Home  
* About  
* Services  
* Blog  
* Contact

### **Footer Navigation**

* Privacy Policy  
* Terms  
* Careers  
* Contact

The CMS must therefore support multiple menus.

---

# **31\. Menu Management**

A menu may require:

* Name  
* Identifier/code  
* Description  
* Active status

The client requires:

* Create menu  
* List menus  
* View menu  
* Update menu  
* Delete menu

---

# **32\. Menu Items**

Each menu contains multiple menu items.

A menu item may contain information such as:

* Label  
* URL  
* Display order  
* Menu  
* Linked page if applicable

Example:

Main Navigation

1. Home  
2. About  
3. Services  
4. Blog  
5. Contact

The database design must correctly represent the relationship between menus and menu items.

---

# **33\. Menu Item Management**

The client requires functionality to:

* Add item to menu  
* List items belonging to menu  
* View item  
* Update item  
* Remove item

A menu item must belong to a menu.

Nested API routes should be considered.

For example, the API structure should make it clear that menu items belong to a particular menu.

---

# **34\. Menu Ordering**

Menu items must support ordering.

For example:

Before:

1. Home  
2. About  
3. Services  
4. Contact

After changing order:

1. Home  
2. Services  
3. About  
4. Contact

The API must provide a way to change menu item ordering.

The implementation must ensure that the returned menu items appear in the correct order.

---

# **35\. Website Settings**

The client requires a simple Website Settings module.

Settings may include:

* Website name  
* Website description  
* Contact email  
* Contact phone  
* Company address  
* Facebook URL  
* LinkedIn URL  
* Instagram URL  
* Default SEO title  
* Default SEO description

The database design must determine how settings should be stored.

---

# **36\. Settings Design**

Consider at least two possible approaches.

### **Approach A**

A single website settings record containing columns for each setting.

### **Approach B**

Key-value records such as:

* `site_name`  
* `contact_email`  
* `facebook_url`

The selected approach must be documented with the reason for choosing it.

---

# **37\. Search**

The client requires search functionality for major content resources.

### **Pages**

Search by:

* Title  
* Content

### **Blog Posts**

Search by:

* Title  
* Excerpt  
* Content

### **Authors**

Search by:

* Name

### **Media**

Search by:

* Filename  
* Alternative text

Searching must happen through database queries.

Do not retrieve the entire table and then search through a PHP collection.

---

# **38\. Filtering**

Blog posts must support filtering by suitable fields.

Required filters include:

* Status  
* Category  
* Author  
* Tag

Example API usage may conceptually look like:

`GET /api/posts?status=published`

or:

`GET /api/posts?category_id=3`

Filters must be able to work together.

For example:

`GET /api/posts?status=published&category_id=3&author_id=8`

---

# **39\. Date Filtering**

The client also requires the ability to filter posts by publication date.

Support a date range such as:

* Published from  
* Published until

The implementation must validate supplied dates.

The filtering must happen in the database.

---

# **40\. Sorting**

Major list endpoints must support sorting.

Blog posts should support useful sorting options such as:

* Newest  
* Oldest  
* Title A–Z  
* Title Z–A  
* Publication date

Pages may support:

* Title  
* Created date  
* Updated date

Only approved sort fields should be accepted.

Request input must not be used directly as an arbitrary database column name.

---

# **41\. Pagination**

Large listing endpoints must use pagination.

At minimum:

* Pages  
* Posts  
* Authors  
* Media

must be paginated.

The API must not return thousands of records in one request.

A sensible default page size must be selected.

The API may optionally allow the consumer to request a different page size within a defined maximum.

---

# **42\. API Resources**

All major resources must use Laravel API Resources.

Required resources include:

* User Resource  
* Page Resource  
* Post Resource  
* Author Resource  
* Category Resource  
* Tag Resource  
* Media Resource  
* Menu Resource  
* Menu Item Resource

The exact class names may follow normal Laravel conventions.

Raw Eloquent models should not simply be returned from every controller.

---

# **43\. Resource Relationships**

Resources should return useful related information.

For example, a Post Resource may contain:

* Post information  
* Author  
* Category  
* Tags  
* Featured image

However, relationships should be included intelligently.

The implementation should investigate conditional relationship loading in Laravel API Resources.

---

# **44\. Eloquent Relationships**

The project must demonstrate practical use of multiple relationship types.

The requirements naturally include relationships such as:

### **One-to-Many**

* Category → Posts  
* Author → Posts  
* Menu → Menu Items

### **Many-to-One**

* Post → Category  
* Post → Author  
* Menu Item → Menu

### **Many-to-Many**

* Posts ↔ Tags

Additional relationships may be required depending on the final database design.

---

# **45\. Route Model Binding**

Route Model Binding must be used for individual resources.

It should be applied to resources such as:

* Pages  
* Posts  
* Authors  
* Categories  
* Tags  
* Media  
* Menus  
* Menu Items

Avoid repeatedly manually retrieving models using IDs inside controller methods when Laravel can resolve them through Route Model Binding.

---

# **46\. Slug-Based Route Model Binding**

As an additional exercise, at least one content resource should use its slug for route model binding instead of its database ID.

For example, a page could conceptually be accessed using:

`/api/pages/about-us`

instead of:

`/api/pages/17`

The implementation must determine how Laravel can resolve a model using a different route key.

---

# **47\. Form Requests**

Validation must be handled using Form Request classes for major create and update operations.

Required areas include:

* Login  
* Users  
* Pages  
* Posts  
* Authors  
* Categories  
* Tags  
* Media  
* Menus  
* Menu Items

Controllers should not contain large repeated validation definitions.

---

# **48\. Validation Requirements**

Validation must cover realistic requirements.

Examples include:

* Required titles  
* Maximum string lengths  
* Unique slugs  
* Valid email addresses  
* Existing category IDs  
* Existing author IDs  
* Existing tag IDs  
* Valid publication statuses  
* Valid publication dates  
* Valid files  
* Positive display-order values

Create and update validation must be handled correctly.

---

# **49\. Eager Loading**

Relationships must be loaded efficiently.

Consider a blog post listing containing 20 posts.

Each post may require:

* Author  
* Category  
* Tags  
* Featured media

Careless relationship access can result in a large number of SQL queries.

Eager loading must be used where appropriate.

The implementation must demonstrate an understanding of the N+1 query problem.

---

# **50\. Query Scopes**

Reusable Eloquent query scopes should be created where they improve the design.

Possible examples include:

* Published pages  
* Published posts  
* Draft posts  
* Active categories  
* Active authors

Scopes should represent reusable query behaviour rather than being added merely to increase the number of classes or methods.

---

# **51\. Soft Deletes**

The client requires historical CMS content to be recoverable where appropriate.

Laravel Soft Deletes should therefore be investigated for resources such as:

* Pages  
* Posts  
* Authors  
* Media

The final selection must be documented.

Consider what should happen when deleting a category that is still referenced by posts.

---

# **52\. Public Content Endpoints**

In addition to authenticated management endpoints, the client requires a small set of **public read-only endpoints**.

These endpoints do not require Sanctum authentication.

Public endpoints should expose only published content.

Required public functionality includes:

* List published pages  
* View published page by slug  
* List published blog posts  
* View published blog post by slug  
* List active categories  
* View posts for a category  
* Retrieve a navigation menu

Public endpoints must not allow:

* Create  
* Update  
* Delete

---

# **53\. Draft Content Protection**

Draft and archived content must not accidentally appear through public endpoints.

For example:

If:

`/api/public/posts/my-draft-post`

is requested for a draft post, the API must not return the unpublished article.

The implementation must enforce publication state in the database query.

This requirement does not require roles or policies.

It is a content-state business rule.

---

# **54\. Public and Management Resources**

Consider whether public API responses should contain exactly the same information as CMS management responses.

For example, the CMS may need:

* Internal status  
* Created date  
* Updated date  
* Internal identifiers

while the public website may only require:

* Title  
* Slug  
* Content  
* Author  
* Category  
* Tags  
* Featured image  
* Published date

The implementation may create separate API Resources if this produces a cleaner design.

The decision must be documented.

---

# **55\. Post View Counter**

Add a simple view counter for public blog posts.

When a published blog post is viewed through its public detail endpoint, the system should record or update its view count.

The implementation must decide whether the count should be:

* stored directly on the post,  
* stored using separate view records,  
* or represented using another reasonable design.

For this project, a simple solution is acceptable.

---

# **56\. Popular Posts**

The client requires a public endpoint that returns popular published posts.

Popularity may be determined using the view count implemented previously.

The endpoint should return a limited number of posts ordered from most viewed to least viewed.

Draft or archived posts must never appear.

---

# **57\. Related Posts**

When viewing a blog post, the client would like the API to return several related posts.

A simple definition of related content may be used.

For example:

* Same category  
* Shared tags

The current post must not appear in its own related-post list.

Only published posts should be returned publicly.

---

# **58\. Database Design Assignment**

Before migrations are written, create an ERD for the CMS.

The ERD must identify all required entities and relationships.

From the requirements, investigate whether tables are required for concepts such as:

* Users  
* Pages  
* Authors  
* Categories  
* Posts  
* Tags  
* Post/tag relationships  
* Media  
* Menus  
* Menu items  
* Settings

Additional tables may be introduced where the design requires them.

The final database structure must not simply be copied from this list.

---

# **59\. Database Design Requirements**

For every table, determine:

* Primary key  
* Columns  
* Data types  
* Required fields  
* Nullable fields  
* Default values  
* Foreign keys  
* Unique constraints  
* Indexes  
* Timestamps  
* Soft deletes where appropriate

Relationships must also specify expected cardinality.

Examples:

* One-to-one  
* One-to-many  
* Many-to-many

---

# **60\. Database Design Questions**

The database design documentation must answer:

1. Which tables are required?  
2. Which tables require soft deletes?  
3. Which fields must be unique?  
4. Should page slugs be globally unique?  
5. Should post slugs be globally unique?  
6. Can two different resource types use the same slug?  
7. Can a post exist without a category?  
8. Can a post exist without an author?  
9. Can a post have zero tags?  
10. How should post-to-tag relationships be stored?  
11. How should featured images be connected to posts?  
12. What happens if referenced media is deleted?  
13. What happens if a category containing posts is deleted?  
14. Which fields require indexes?  
15. Should publication status be indexed?  
16. Should publication date be indexed?  
17. How should settings be stored?  
18. How should menu item ordering be stored?  
19. Which relationships should cascade when deleted?  
20. Which relationships should restrict deletion?

The chosen answers must be justified.

---

# **61\. Migration Requirements**

After the database design is complete, create Laravel migrations.

The migration set must:

* Work on an empty database  
* Create appropriate foreign keys  
* Create appropriate indexes  
* Create unique constraints  
* Use suitable column types  
* Support rollback

Both of the following must work correctly:

`php artisan migrate`

`php artisan migrate:fresh`

---

# **62\. Factories**

Factories must be created for appropriate models.

The development environment should be capable of quickly generating realistic CMS content.

Possible factory areas include:

* Users  
* Authors  
* Categories  
* Tags  
* Pages  
* Posts  
* Media

Factories should generate varied and realistic data.

---

# **63\. Seeders**

The client requires development seed data.

After running the database seeder, approximately the following should exist:

* 5 CMS users  
* 15 pages  
* 10 authors  
* 10 categories  
* 30 tags  
* 100 blog posts  
* Tags assigned to posts  
* Media records  
* At least 2 menus  
* Multiple menu items  
* Website settings

The exact quantities may vary.

The goal is to have enough data to properly test pagination, searching, filtering, and relationships.

---

# **64\. API Documentation**

Every API endpoint must be documented.

Documentation must contain:

* HTTP method  
* Endpoint  
* Authentication requirement  
* Purpose  
* Request fields  
* Validation requirements  
* Query parameters  
* Example request  
* Example successful response  
* Important error responses

Documentation may be written in Markdown.

A Postman or Bruno collection should also be provided.

---

# **65\. Testing Requirements**

Laravel Feature Tests must be created for important API behaviour.

Testing must cover authentication, CRUD functionality, validation, relationships, public content restrictions, and important business rules.

---

# **66\. Authentication Tests**

Required authentication tests include:

* Valid login succeeds  
* Invalid credentials fail  
* Protected endpoint without token fails  
* Protected endpoint with token succeeds  
* Current user endpoint works  
* Logout invalidates current token

---

# **67\. Page Tests**

Required page tests include:

* Page can be created  
* Title validation works  
* Duplicate slug fails  
* Page can be retrieved  
* Page can be updated  
* Page can be deleted  
* Published page appears publicly  
* Draft page does not appear publicly

---

# **68\. Post Tests**

Required blog post tests include:

* Post can be created  
* Post requires valid category  
* Post requires valid author  
* Multiple tags can be attached  
* Post can be updated  
* Tag relationships can be changed  
* Post can be deleted  
* Search works  
* Category filter works  
* Status filter works  
* Pagination works

---

# **69\. Public API Tests**

The public API must be specifically tested.

Required scenarios include:

* Published post can be viewed  
* Draft post cannot be viewed  
* Archived post cannot be viewed  
* Published page can be viewed by slug  
* Draft page cannot be viewed  
* Public post list contains only published posts  
* Popular posts contain only published posts

---

# **70\. Media Tests**

Required media tests include:

* Valid image can be uploaded  
* Invalid file type fails  
* File exceeding allowed size fails  
* Media metadata can be updated  
* Media record can be retrieved

---

# **71\. Menu Tests**

Required menu tests include:

* Menu can be created  
* Menu item can be added  
* Menu item can be updated  
* Menu item can be removed  
* Menu items are returned in correct order  
* Public menu endpoint returns expected items

---

# **72\. Development Phase 1 — Requirement Analysis**

Read the complete client requirements.

Identify:

* Required resources  
* Required CRUD operations  
* Relationships  
* Public endpoints  
* Authenticated endpoints  
* Business rules

Prepare a short requirements summary before starting development.

---

# **73\. Development Phase 2 — Database Design**

Create the ERD.

For each entity, define:

* Purpose  
* Fields  
* Data types  
* Relationships  
* Foreign keys  
* Unique constraints  
* Indexes

Database design must be reviewed before migrations are created.

---

# **74\. Development Phase 3 — Project Setup**

Create and configure the Laravel application.

Configure:

* Database  
* Environment  
* API routes  
* Sanctum

Verify database connectivity.

---

# **75\. Development Phase 4 — Authentication**

Implement Sanctum authentication first.

Complete:

* Login  
* Current user  
* Logout

Test these endpoints using Postman or Bruno.

All management endpoints created afterward must require authentication.

---

# **76\. Development Phase 5 — Simple CRUD Modules**

Start with simpler modules:

1. Authors  
2. Categories  
3. Tags

For each module implement:

**Migration → Model → Factory → Seeder → Form Request → Controller → API Resource → Routes → Tests**

This establishes the standard structure that later modules should follow.

---

# **77\. Development Phase 6 — Pages**

Implement the Pages module.

Start with basic CRUD.

Then add:

* Slugs  
* Publication status  
* Publication date  
* Search  
* Filtering  
* Pagination  
* Public page endpoint

Test draft and published behaviour carefully.

---

# **78\. Development Phase 7 — Blog Posts**

Implement Blog Posts after Pages.

Start with:

* Basic post CRUD  
* Author relationship  
* Category relationship

Once these work, add:

* Tags  
* Featured media  
* Publication states  
* Search  
* Filters  
* Sorting  
* Pagination

Do not attempt all functionality in the first implementation.

---

# **79\. Development Phase 8 — Many-to-Many Tags**

Implement the post/tag relationship separately.

Verify:

* Multiple tags can belong to one post  
* One tag can belong to many posts  
* Tags can be added  
* Tags can be removed  
* Tags can be replaced during update

Review the generated database queries.

---

# **80\. Development Phase 9 — Media**

Implement the Media module.

First create media records and CRUD behaviour.

Then add actual file uploads.

Implement:

* File validation  
* Laravel Storage  
* Metadata  
* Featured images

Do not mix large amounts of filesystem logic directly into controllers if it makes the controller difficult to maintain.

---

# **81\. Development Phase 10 — Menus**

Implement:

* Menus  
* Menu items  
* Menu item ordering  
* Public menu retrieval

Pay particular attention to the one-to-many relationship between menus and their items.

---

# **82\. Development Phase 11 — Settings**

Implement website settings.

Ensure the API can:

* Retrieve settings  
* Update settings

Avoid creating unnecessary CRUD operations if the chosen settings design does not require them.

---

# **83\. Development Phase 12 — Public API**

Create public read-only endpoints.

Public endpoints must:

* Require no Sanctum token  
* Return published content only  
* Never modify data

Implement public resources carefully so unnecessary CMS information is not exposed.

---

# **84\. Development Phase 13 — Advanced Query Features**

After basic CRUD is complete, add:

* Search  
* Filtering  
* Sorting  
* Pagination  
* Date filtering

Do not mix the implementation of these features with the initial CRUD work.

Build and test incrementally.

---

# **85\. Development Phase 14 — Query Optimization**

Review the application for inefficient database access.

Check:

* Post listing  
* Post details  
* Author details  
* Category posts  
* Tags  
* Menus

Use eager loading where appropriate.

The final implementation must demonstrate understanding of the N+1 problem.

---

# **86\. Development Phase 15 — Testing**

Complete Feature Tests for:

* Authentication  
* Pages  
* Posts  
* Categories  
* Tags  
* Authors  
* Media  
* Menus  
* Public endpoints

Important validation and business rules must be covered.

---

# **87\. Development Phase 16 — Documentation**

Complete:

* README  
* ERD  
* Database design explanation  
* API documentation  
* Postman/Bruno collection

The README must explain how another developer can install and run the application.

---

# **88\. Required Laravel Concepts**

The completed CMS must demonstrate:

* Laravel API routing  
* Sanctum  
* Bearer token authentication  
* Controllers  
* Resource Controllers  
* Form Requests  
* API Resources  
* Route Model Binding  
* Slug-based Route Model Binding  
* Eloquent ORM  
* `belongsTo`  
* `hasMany`  
* `belongsToMany`  
* Pivot tables  
* Migrations  
* Foreign keys  
* Unique constraints  
* Indexes  
* Factories  
* Seeders  
* Pagination  
* Search  
* Filtering  
* Sorting  
* Query scopes  
* Eager loading  
* Soft deletes  
* File uploads  
* Laravel Storage  
* Feature tests

---

# **89\. Implementation Standards**

The client requires the implementation to follow these standards:

* Controllers must remain reasonably small.  
* Validation must use Form Requests for major operations.  
* API responses must use Resources.  
* Database relationships must use Eloquent relationships.  
* Route Model Binding must be used where appropriate.  
* Database filtering must happen before records are retrieved.  
* Large collections must use pagination.  
* Sensitive user information must never be exposed.  
* Database constraints must be used where appropriate.  
* N+1 queries must be avoided.  
* Public APIs must never expose draft content.  
* Uploaded files must be validated.  
* API response formats should remain consistent.

---

# **90\. Database Design Must Not Be Provided**

The requirements intentionally do **not** provide:

* Table definitions  
* Migration code  
* Exact column types  
* Exact foreign key definitions  
* Pivot table implementation  
* Model code  
* Controller code

These must be designed during implementation.

The database design must be based on the business requirements rather than copied from a prepared solution.

---

# **91\. Required Deliverables**

The client requires delivery of:

1. Laravel source code  
2. README  
3. ERD  
4. Database design documentation  
5. Laravel migrations  
6. Eloquent models and relationships  
7. Factories  
8. Seeders  
9. Controllers  
10. Form Requests  
11. API Resources  
12. API routes  
13. Feature tests  
14. API documentation  
15. Postman or Bruno collection

---

# **92\. Final Demonstration**

The completed project must be demonstrable from a fresh database.

The demonstration should cover the following flow:

1. Run fresh migrations.  
2. Run seeders.  
3. Login using a seeded CMS user.  
4. Receive a Sanctum token.  
5. Retrieve the current authenticated user.  
6. Create an author.  
7. Create a category.  
8. Create several tags.  
9. Create a draft page.  
10. Publish the page.  
11. Create a blog post.  
12. Assign an author.  
13. Assign a category.  
14. Assign multiple tags.  
15. Upload/select featured media.  
16. Publish the post.  
17. Search for the post.  
18. Filter posts by category.  
19. Filter posts by tag.  
20. Demonstrate pagination.  
21. Retrieve the published post through the public API.  
22. Demonstrate that a draft post cannot be retrieved publicly.  
23. Create a navigation menu.  
24. Add and reorder menu items.  
25. Retrieve the menu through the public API.  
26. Update website settings.  
27. Logout.  
28. Demonstrate that the invalidated Sanctum token can no longer access management endpoints.

---

# **93\. Expected Learning Outcome**

The completed project should demonstrate an understanding of how the major parts of a Laravel API work together:

**Request**

→ **API Route**

→ **Sanctum Authentication**

→ **Route Model Binding**

→ **Form Request Validation**

→ **Controller**

→ **Eloquent Models & Relationships**

→ **Database**

→ **API Resource**

→ **JSON Response**

The project should go beyond simple CRUD by requiring relationships, many-to-many data, public versus unpublished content, search, filtering, pagination, media uploads, nested resources, query optimization, and realistic CMS business rules.

The finished application should represent a reasonably complete REST API that could serve as the backend of a small company website or news portal.




### NEW
--authors can edit and delete their own blogs
--admin can delete all (will create a user with admin perms)