# Database Diagram

Entity-relationship diagram generated from the migrations in `database/migrations/`.
Laravel framework tables (`sessions`, `password_reset_tokens`, `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs`) are omitted since they don't participate in the application's domain relationships.

```mermaid
erDiagram
    CATEGORIES ||--o{ BLOGS : "has many"
    IMAGES ||--o{ BLOGS : "illustrates"
    IMAGES ||--o{ AUTHORS : "profile picture"
    IMAGES ||--o{ PAGES : "content image"
    BLOGS ||--o{ BLOG_TAGS : "has many"
    TAGS ||--o{ BLOG_TAGS : "has many"

    USERS {
        bigint id PK
        string name
        string email UK
        boolean active_status
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
        enum file_type "image/jpeg, image/png, image/svg"
        int filesize
        string caption
        string image_for
        date upload_date
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
        boolean active
        timestamps timestamps
    }

    BLOGS {
        bigint id PK
        string title UK
        string slug UK
        bigint category_id FK
        string excerpt
        string content
        bigint image FK
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
        timestamps timestamps
    }
```

## Relationships

| From | To | Type | FK column | On delete |
|---|---|---|---|---|
| `blogs.category_id` | `categories.id` | many-to-one | `category_id` | cascade |
| `blogs.image` | `images.id` | many-to-one | `image` | cascade |
| `authors.profile_pic` | `images.id` | many-to-one | `profile_pic` | cascade |
| `pages.content_image` | `images.id` | many-to-one | `content_image` | cascade |
| `blog_tags.blog_id` | `blogs.id` | many-to-one | `blog_id` | cascade |
| `blog_tags.tag_id` | `tags.id` | many-to-one | `tag_id` | cascade |

`blogs` and `tags` are joined through the `blog_tags` pivot table (composite PK on `blog_id` + `tag_id`), forming a many-to-many relationship.

## Notes / observations

- `authors` is not currently linked to `blogs` (no `author_id` FK on `blogs`) — blog authorship isn't modeled yet.
- `users` has no foreign keys tying it to `blogs`, `pages`, `authors`, etc. — it appears to be used only for CMS login/auth, separate from the `authors` entity.
- `blogs.image` and `authors.profile_pic` are FK columns not suffixed with `_id`, unlike Laravel's usual convention (`category_id`, `content_image` on `pages` also breaks the pattern).
