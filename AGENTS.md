# D2U Guestbook - Redaxo Addon

A Redaxo 5 CMS addon for managing a guestbook with ratings, recommendations, and admin notifications. Includes moderation features and multiple frontend display variants.

## Tech Stack

- **Language:** PHP >= 8.0
- **CMS:** Redaxo >= 5.10.0
- **Frontend Framework:** Bootstrap 4/5 (via d2u_helper templates)
- **Namespace:** `FriendsOfRedaxo\D2UGuestbook`

## Project Structure

```text
d2u_guestbook/
├── boot.php               # Addon bootstrap (extension points, permissions)
├── install.php             # Installation (database table, sprog wildcards)
├── update.php              # Update (calls install.php)
├── uninstall.php           # Cleanup (database table, sprog wildcards)
├── package.yml             # Addon configuration, version, dependencies
├── README.md
├── lang/                   # Backend translations (de_de, en_gb)
├── lib/                    # PHP classes
│   ├── Entry.php           # Guestbook entry model
│   ├── BackendHelper.php   # Admin notification email
│   ├── LangHelper.php      # Sprog wildcard provider (26 wildcards)
│   └── Module.php          # Module definitions and revisions
├── modules/                # 3 module variants in group 60
│   └── 60/
│       ├── 1/              # Guestbook with Bootstrap 4 tabs
│       ├── 2/              # Info box with rating
│       └── 3/              # Guestbook without tabs
└── pages/                  # Backend pages
    ├── index.php           # Page router
    ├── entries.php         # Entry management (CRUD, status)
    ├── settings.php        # Addon settings
    └── setup.php           # Module manager + changelog
```

## Coding Conventions

- **Namespace:** `FriendsOfRedaxo\D2UGuestbook` for all classes
- **Naming:** camelCase for variables, PascalCase for classes
- **Indentation:** 4 spaces in PHP classes, tabs in module files
- **Comments:** English comments only
- **Frontend labels:** Use `Sprog\Wildcard::get()` backed by `LangHelper`, not `rex_i18n::msg()`
- **Backend labels:** Use `rex_i18n::msg()` with keys from `lang/` files

## AGENTS.md Maintenance

- When new project insights are gained during work and they are relevant to agent guidance, workflows, conventions, architecture, or known pitfalls, update this AGENTS.md accordingly.

## Key Classes

| Class | Description |
| ----- | ----------- |
| `Entry` | Guestbook entry model: name, email, description, rating (1-5), recommendation, privacy policy, online status, create date |
| `BackendHelper` | Sends admin notification email when new entry is submitted (via `rex_mailer`) |
| `LangHelper` | Sprog wildcard provider with 26 wildcards in English and German |
| `Module` | Module definitions and revision numbers |

## Database Tables

| Table | Description |
| ----- | ----------- |
| `rex_d2u_guestbook` | Guestbook entries: name, email, description, rating, recommendation, privacy policy, online status, create date |

## Architecture

### Extension Points

| Extension Point | Location | Purpose |
| --------------- | -------- | ------- |
| `CLANG_DELETED` | boot.php (backend) | Cleans up language-specific entries and sprog wildcards |

### Modules

3 module variants in group 60:

| Module | Name | Description |
| ------ | ---- | ----------- |
| 60-1 | Gästebuch mit Bootstrap 4 Tabs | Guestbook with tabbed form/entries |
| 60-2 | Infobox Bewertung | Rating info box |
| 60-3 | Gästebuch ohne Tabs | Guestbook without tabs |

#### Module Versioning

Each module has a revision number defined in `lib/Module.php` inside the `getModules()` method. When a module is changed:

1. Add a changelog entry in `pages/setup.php` describing the change.
2. Increment the module's revision number in `Module::getModules()` by one.

**Important:** The revision only needs to be incremented **once per release**, not per commit. Check the changelog: if the version number is followed by `-DEV`, the release is still in development and no additional revision bump is needed.

## Settings

Managed via `pages/settings.php` and stored in `rex_config`:

- `request_form_email` — Admin email for notifications
- `guestbook_article_id` — Redaxo article for guestbook page
- `allow_answer` — Publish email addresses (allow replies)
- `no_entries_page` — Entries per page (default: 10)
- `lang_wildcard_overwrite` — Preserve custom Sprog translations
- `lang_replacement_{clang_id}` — Language mapping per REDAXO language

## Dependencies

| Package | Version | Purpose |
| ------- | ------- | ------- |
| `d2u_helper` | >= 1.14.0 | Backend/frontend helpers, module manager |
| `sprog` | >= 1.0.0 | Frontend translation wildcards |
| `yform` | >= 3.0 | Form handling |

## Multi-language Support

- **Backend:** de_de, en_gb
- **Frontend (Sprog Wildcards):** DE, EN (2 languages, 26 wildcards)

## Versioning

This addon follows [Semantic Versioning](https://semver.org/). The version number is maintained in `package.yml`. During development, the changelog uses a `-DEV` suffix.

## Changelog

The changelog is located in `pages/setup.php`.
