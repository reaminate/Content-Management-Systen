# Database Diagram

Entity-relationship diagram generated from the migrations in `database/migrations/`.
Laravel/package infrastructure tables (`sessions`, `password_reset_tokens`, `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs`, `personal_access_tokens`) are omitted since they don't participate in the application's domain relationships.

```mermaid
erDiagram
    CATEGORIES ||--o{ BLOGS : "has many"
    IMAGES ||--o{ BLOGS : "illustrates"
    IMAGES ||--o{ AUTHORS : "profile picture"
    IMAGES ||--o{ PAGES : "content image"
    AUTHORS ||--o{ BLOGS : "writes"
    USERS |o--o{ AUTHORS : "linked account"
    BLOGS ||--o{ BLOG_TAGS : "has many"
    TAGS ||--o{ BLOG_TAGS : "has many"
    MENUS ||--o{ ITEMS : "has many"
    PAGES ||--o{ ITEMS : "linked by"

    USERS {
        bigint id PK
        string name
        string email UK
        boolean active_status
        boolean is_admin
        timestamp email_verified_at
        string password
        string remember_token
        timestamps timestamps
    }

    CATEGORIES {
        bigint id PK
        string name
        string slug
        text description
        boolean active_status
        timestamps timestamps
    }

    IMAGES {
        bigint id PK
        string original_filename
        string stored_filename UK
        longtext file_path
        enum file_type "image/jpeg, image/png, image/jpg"
        int filesize
        boolean for_author
        string caption
        date upload_date
        timestamp deleted_at "soft delete"
        timestamps timestamps
    }

    TAGS {
        bigint id PK
        string name
        string slug
        timestamps timestamps
    }

    AUTHORS {
        bigint id PK
        string name
        string email
        string slug
        text short_biography
        bigint profile_pic FK
        bigint user_id FK "nullable"
        boolean active
        timestamp deleted_at "soft delete"
        timestamps timestamps
    }

    BLOGS {
        bigint id PK
        string title UK
        string slug UK
        bigint category_id FK
        string excerpt
        string content
        bigint image_id FK
        bigint author_id FK
        date published_at
        enum publication_status "draft, published, archived"
        timestamp deleted_at "soft delete"
        timestamps timestamps
    }

    BLOG_TAGS {
        bigint blog_id PK_FK
        bigint tag_id PK_FK
    }

    PAGES {
        bigint id PK
        string title UK
        string slug UK
        string content
        bigint content_image FK
        string description
        enum publication_status "draft, published, archived"
        date published_date
        string SEO_title
        string SEO_description
        timestamp deleted_at "soft delete"
        timestamps timestamps
    }

    MENUS {
        bigint id PK
        string name UK
        string identifier UK
        text description
        boolean active_status
        timestamps timestamps
    }

    ITEMS {
        bigint id PK
        string label
        string url
        int order
        bigint menu_id FK
        bigint page_id FK
        timestamps timestamps
    }

    SETTINGS {
        bigint id PK
        string name
        string description
        string email
        string phone
        string address
        string facebook
        string linkedin
        string instagram
        string SEO_title
        string SEO_description
        timestamps timestamps
    }
```

## Relationships

| From | To | Type | FK column | On delete |
|---|---|---|---|---|
| `blogs.category_id` | `categories.id` | many-to-one | `category_id` | cascade |
| `blogs.image_id` | `images.id` | many-to-one | `image_id` | cascade |
| `authors.profile_pic` | `images.id` | many-to-one | `profile_pic` | cascade |
| `authors.user_id` | `users.id` | many-to-one (nullable) | `user_id` | set null |
| `pages.content_image` | `images.id` | many-to-one | `content_image` | cascade |
| `blogs.author_id` | `authors.id` | many-to-one | `author_id` | cascade |
| `blog_tags.blog_id` | `blogs.id` | many-to-one | `blog_id` | cascade |
| `blog_tags.tag_id` | `tags.id` | many-to-one | `tag_id` | cascade |
| `items.menu_id` | `menus.id` | many-to-one | `menu_id` | cascade |
| `items.page_id` | `pages.id` | many-to-one (nullable) | `page_id` | cascade |

`blogs` and `tags` are joined through the `blog_tags` pivot table (composite PK on `blog_id` + `tag_id`), forming a many-to-many relationship.

`settings` has no foreign keys — it's a standalone key/value-style table for site-wide configuration.

`images`, `authors`, `blogs`, and `pages` use soft deletes (`deleted_at`).
