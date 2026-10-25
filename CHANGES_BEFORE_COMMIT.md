# RHU Lucban: changes before manual commit

Prepared: October 26, 2026 (Asia/Taipei).

This document describes the final RHU application working tree, including the initial application import, the cleanup already present when the review began, the nine review fixes, and the additional fixes made during the second review. It records implemented behavior rather than treating every item in the earlier implementation plan as completed.

## 1. Commit scope and repository state

- The current `HEAD` is `39089bb` (`Initial commit`) and contains only `README.md`. Consequently, most application files will be additions when compared with that commit.
- The index contains an earlier application snapshot. Many final fixes are unstaged, and the new controllers, middleware, services, migration, helpers, tests, and receipt template are untracked until manually staged.
- `README.md` has an existing add/add merge state (`AA`). Its working copy contains the project documentation without conflict markers. Staging the reviewed working copy resolves that index state.
- Files marked `AD` were added to the index and then removed from the working tree. Their old contents remain staged until the removal is included in the final staging operation. These are not deletions from the current README-only `HEAD`.
- Controller and model renames described below compare the earlier staged application with the final working tree; Git may represent them as additions/removals rather than detected renames.
- The pre-existing untracked `.agents/` directory contains local skill resources. It is listed in the inventory for completeness and is a separate staging decision from the RHU application.
- This documentation change does not stage files, resolve the index, create a commit, or apply database migrations.

## 2. Application imported into the repository

The proposed commit includes the Laravel application itself, not just the later bug fixes.

| Area | Included application content |
| --- | --- |
| Framework and dependencies | Laravel 11 / PHP 8.2 application; Composer manifest and lock file; Artisan entry point; bootstrap files and provider registration. |
| Frontend tooling | Vite, Tailwind CSS, PostCSS, npm manifest and lock file, application CSS/JavaScript entry points, Axios/bootstrap setup, Flatpickr, and html2canvas dependencies. |
| Configuration | Application, authentication, database, cache, session, queue, filesystems, logging, mail, and third-party service configuration; environment example; EditorConfig and Git attributes/ignore rules. |
| Public and patient pages | Homepage, authentication and password-reset views, profile views, appointment forms and lists, announcements, and supporting images/styles/scripts. |
| Department modules | IT administration, super administration, laboratory, consultation, vaccination, blood donation/blood stock, and inventory controllers and views. |
| Shared UI | Blade layout, navigation, sidebar, form components, Bootstrap imports, and Livewire notification component/view. |
| Records and reporting | RHU models, Excel exports, patient records, appointment approval and rejection flows, receipt generation, and email/SMS integration. |
| Persistence and tests | Framework migrations, factories, seeder, storage directory placeholders, Pest/PHPUnit setup, example tests, and the new RHU migration/regression tests described below. |
| Static assets | Existing Bootstrap/Kaiadmin template assets, fonts, icons, public CSS/SCSS/JavaScript, plugins, and project images. Existing files under `public/uploads_id/` are also present in the staged import; their inclusion is not changed by the review fixes. |

The complete filename inventory at the end includes individual application, asset, removed legacy, and local skill files.

## 3. Original review findings and their final remedies

| Finding | Final behavior | Main files |
| --- | --- | --- |
| Staff actions required authentication without sufficient authorization | Account approval, staff role, department, target-resource ownership, staff-management scope, and export-category checks now control the applicable routes/actions. | `routes/web.php`, `app/Providers/AppServiceProvider.php`, new middleware, department controllers |
| Pending/rejected login left an authenticated session active | Disallowed accounts are logged out before successful-login logging; session and CSRF state are regenerated appropriately. Protected requests also recheck approval. | `SessionController.php`, `EnsureAccountApproved.php` |
| Domain schema was missing from migrations | A new reversible migration creates all 13 RHU domain tables for an empty database. | `database/migrations/2026_10_26_000000_create_rhu_tables.php` |
| Recording one result updated other patients' appointments | Forms submit an appointment ID; a shared transaction locks and updates that approved appointment only, deriving patient identity from the stored appointment. | `AppointmentRecordService.php`, four clinical controllers and record views |
| Announcement `Yes`/`No` values were cast to booleans | Visibility remains a string, and announcement edit validation accepts `Yes` or `No`. | `RhuAnnouncement.php`, `SuperAdminController.php` |
| Date-only fields serialized as incompatible timestamps | Birthdays, announcement dates, and supply expiration dates serialize as `Y-m-d`. | `RhuUser.php`, `RhuAnnouncement.php`, `RhuSupply.php` |
| Login rejected passwords accepted by account/password creation | Login checks the supplied credentials without applying a new complexity policy; password-setting paths require at least eight characters and confirmation. Older valid six-character stored passwords can still authenticate. | Authentication, registration, staff creation, and password-reset controllers |
| SMS normalization duplicated the mobile prefix | A shared helper produces `639...` recipients from supported Philippine mobile formats. | `PhilippineMobileNumber.php`, laboratory and consultation controllers |
| Profile edit route referenced a missing action | `ClientController::edit()` now renders the authenticated user's `profiles.edit` view; profile routes require authentication and approval. | `ClientController.php`, `routes/web.php` |

## 4. Controller organization and shared logging

### Renamed controllers

| Earlier staged controller | Final controller |
| --- | --- |
| `app/Http/Controllers/Blood.php` | `app/Http/Controllers/BloodController.php` |
| `app/Http/Controllers/Consultation.php` | `app/Http/Controllers/ConsultationController.php` |
| `app/Http/Controllers/ITDepartment.php` | `app/Http/Controllers/ITDepartmentController.php` |
| `app/Http/Controllers/Inventory.php` | `app/Http/Controllers/InventoryController.php` |
| `app/Http/Controllers/Laboratory.php` | `app/Http/Controllers/LaboratoryController.php` |
| `app/Http/Controllers/SuperAdmin.php` | `app/Http/Controllers/SuperAdminController.php` |
| `app/Http/Controllers/Vaccination.php` | `app/Http/Controllers/VaccinationController.php` |

- Updated route imports and controller references to use these class names.
- Renamed `app/Models/Logs.php` to the singular `app/Models/Log.php`, retaining the existing `logs` table mapping.
- Added `app/Traits/LogsActivity.php` to centralize repeated authenticated activity-log creation. Calls safely return when no user is authenticated.
- Replaced repeated controller logging blocks with the trait where used; registration and login/logout retain their specific logging behavior.
- Added class/property documentation, organized imports, and removed obsolete commented-out code, unused imports, debug remnants, and excessive whitespace in the cleaned PHP files.
- Removed the unused default `app/Models/User.php`. The authentication provider continues to use `RhuUser`.

## 5. Authentication, registration, profiles, and password reset

### Login and logout

- Removed the duplicate `Auth::attempt()` call.
- Login validates username presence and password presence/string type, then checks the stored password hash.
- Only `Approved` or `Active` accounts proceed to the authenticated application flow.
- Pending/rejected accounts are logged out, their session is invalidated, and the CSRF token is regenerated before redirecting to login.
- Successful login regenerates the session and stores department/role context for the existing UI.
- Successful-login activity is not recorded for disallowed accounts.
- Department redirects use a single `match` expression.
- Logout closes open login-log entries and deletes database-backed session rows using `user_id`, correcting the earlier lookup by session `id`.
- Logout requires authentication and invalidates the current session/token.

### Registration

- Validates the registration fields, unique email/username, confirmed password of at least eight characters, and an `09`-prefixed 11-digit mobile number.
- Restricts uploaded identification files to JPEG, PNG, JPG, or PDF with a 5 MB limit.
- Converts registration gender values to the canonical profile values `Male`, `Female`, or `Prefer not to say`.
- Assigns `Client` department/role and `Pending` status on the server, regardless of submitted privilege fields.
- Hashes the password, saves the account, records registration activity, and returns to login without automatically authenticating the pending account.

### Profile editing

- Added the missing `edit()` action used by `/profile/edit`.
- Protected profile display/update routes with authentication and approval middleware.
- Restricted profile-picture uploads in both profile-update actions to JPEG, PNG, JPG, or GIF images up to 2 MB.
- Retained unique email/username checks scoped to the current user.
- Password fields are only updated when a new password is supplied; confirmation and hashing remain in place.

### Password reset

- Uses `random_int()` to generate a six-digit OTP with a five-minute expiry.
- Stores the reset email, OTP, expiry, and attempt count in the session.
- Requires a six-digit submitted OTP and a confirmed new password of at least eight characters.
- Requires an existing, unexpired OTP session before looking up or changing the target account.
- Compares OTP strings using `hash_equals()` and invalidates the reset session after repeated incorrect attempts.
- Clears reset-session values after expiry or a successful password reset and handles a missing target user.
- The active OTP delivery path remains `Mail::raw()` in `ForgotPasswordController`.

## 6. Authorization and route behavior

### Middleware

- Added `EnsureAccountApproved`: rejects accounts whose current status is not `Approved`/`Active`, logs them out, and invalidates session/token state.
- Added `EnsureDepartment`: requires the staff gate and the exact department associated with the route group.
- Registered the `approved` and `department` aliases in `bootstrap/app.php`.

### Gates in `AppServiceProvider`

| Gate | Scope |
| --- | --- |
| `staff` | Requires an approved/active user with an Admin role and a recognized staff department. Role comparison supports existing capitalization differences. |
| `manage-user` | IT and super administrators can manage accounts across departments; other department administrators can manage staff in their own department. |
| `assign-department` | Restricts assigned departments to recognized staff departments; ordinary department administrators cannot assign staff outside their department. |
| `manage-appointment` | Restricts appointment mutations to staff belonging to the appointment's clinical department. |
| `manage-record` | Restricts patient-record deletion to staff belonging to the record's department. |
| `export-data` | Maps each recognized export category to its department, permits super-administrator exports across categories, and rejects unknown categories. |

### Route changes

- Applied approval and department checks to the IT, super-admin, laboratory, consultation, vaccination, blood, and inventory route groups.
- Kept patient booking routes outside staff-only groups so approved clients can still book appointments.
- Moved shared staff-management and appointment/record operations into a staff-protected group, with target-specific authorization in the corresponding actions.
- Added resource checks to clinical appointment status updates and consultation rejection/referral.
- Restricted receipt-generation routes and the export route to approved staff.
- Changed appointment-approval and approval-email actions to POST-only where they perform mutations or send notifications.
- Changed `/email/{id}` to identify the selected appointment instead of choosing the first approved appointment for a client.
- Removed the exposed `/phpinfo` diagnostic route from the earlier staged application.

## 7. Appointment booking and patient-record integrity

### Booking behavior

- Booking controllers validate their own fixed department type; another booking route cannot be used to submit a blood appointment without blood screening.
- Patient names are taken from the authenticated user rather than trusting a posted display name.
- Duplicate pending/approved appointment checks remain scoped to the authenticated client, department type, and service subtype.
- Date parsing failures return a useful error instead of an uncaught parsing exception.
- Department notification creation checks that a matching recipient exists.
- Blood questionnaire validation occurs before appointment insertion.
- A blood appointment and its medical-history update are saved in one database transaction.

### `AppointmentRecordService`

- Accepts a selected appointment ID, expected clinical department, and validated result attributes.
- Locks the matching approved appointment inside a transaction.
- Rejects missing appointments, wrong-department appointments, and already-completed/rejected appointments.
- Creates the patient record using the appointment's stored `client_id`, patient name, department, service subtype, and reason.
- Copies the validated outcome status to that appointment only; a rejected record remains rejected.
- Saves laboratory uploads and associates the result path with the selected appointment and patient record only.
- Deletes a newly stored result file if the transaction fails.
- Adds blood volume to stock only for completed donations, using integer values and atomic increments.
- Prevents a second record submission from completing the same appointment again.

### Clinical record forms

The laboratory, consultation, vaccination, and blood record forms now:

- Submit `appointment_id` rather than an ambiguous patient-name value.
- Show patient name, service subtype, and appointment date/time in the selector to distinguish appointments with matching names.
- Populate service, consultation reason, and vaccination dose fields from the selected option's data attributes.
- Avoid the old patient-name lookup when selecting an appointment in these forms.

## 8. Blood stock and medicine inventory fixes

### Blood turnover

- Added `BloodTurnoverService` for create, edit, and delete operations.
- Locks turnover records and affected blood-stock rows inside transactions.
- Verifies that the requested blood type exists and sufficient current stock is available.
- Adjusts the current/turned-over counters by the volume difference for an edit within one blood type.
- Restores the old blood type and deducts the new one when a turnover changes blood type.
- Restores stock when a turnover is deleted.
- Locks multiple blood types in a consistent order and rolls back all adjustments if an edit fails.
- Replaced the controller's earlier partial stock updates and the undefined-variable deletion path with the service calls.

### Medicine and supply handling

- Corrected the earlier undefined quantity use when creating medicine totals.
- Removed duplicate/mismatched validation definitions in the cleaned inventory actions.
- Validates numeric stock fields as bounded integers before arithmetic or persistence.
- Uses a transaction and row locks when dispensing medicine.
- Rejects insufficient stock or a missing medicine-total record without partially updating stock.
- Updates both the supply quantity and the medicine's current/turned-over counters together when dispensing.
- Restocking updates supply quantity and medicine totals in one transaction.
- Restocking a renamed medicine updates the corresponding medicine-total record instead of losing the association by looking it up under the new name too early.
- Preserves already-dispensed totals when adding new stock.

## 9. Receipt, email, SMS, and notification changes

### Receipt generation

- Added `ReceiptService` and reused it from the approval-email and sample-receipt flows.
- Included the required Word template at `resources/templates/official-receipt.docx`; it no longer depends on an ignored file being present in local storage.
- Uses a separate temporary Word file for each generation and removes it in a `finally` block.
- Renders the intermediate HTML in memory instead of sharing `temp.html` between requests.
- Escapes values written to the Word template and restores the previous PhpWord escaping setting after generation.
- Uses UUID-based PDF paths and the configured public storage disk.
- Checks temporary-file creation and PDF storage failures.
- Added `PesoAmount` to spell peso and centavo amounts without requiring the unavailable `intl` extension.
- Validates the supported numeric range for amount-to-words conversion.
- The sample-receipt endpoint now uses the shared official template and explicit sample values.

### Email and SMS

- Replaced hardcoded SMS credentials with service configuration and hardcoded approval-recipient numbers with the appointment's actual contact number.
- Added `PhilippineMobileNumber` to accept supported local/international formats, remove presentation punctuation, produce `639...` recipients, and reject malformed numbers.
- Applied normalization to laboratory approval/notification SMS and consultation rejection SMS.
- Added logging/error handling around the cleaned notification paths so provider failures are handled without embedding credentials in source.
- Updated `SemaphoreService` to use configuration, typed properties/arguments/return values, response checks, and failure logging. Missing API-key configuration no longer causes a constructor type error.
- `DemoMail` resolves attachments through the configured public storage disk and attaches an existing PDF as `receipt.pdf`.
- Cleaned `AccountApproved` constructor spacing and unused mail imports.
- Added the typed OTP property, envelope/content metadata, and attachment method to `OtpMail`. Its alternate `email.otp` view is not included; the active reset flow uses raw email as noted above.

### Notification ownership and browser actions

- Livewire's `markAsRead()` lookup is now scoped to the authenticated recipient's `client_id`.
- Clinical approval UIs send the selected appointment ID to the email endpoint.
- Clinical and account-approval email triggers use POST requests with CSRF headers.
- The email fetches use manual redirect handling and then reload the page, preserving the existing page/flash-message flow.

## 10. Models, relationships, casts, and enums

All cleaned RHU models received consistent formatting and descriptive PHPDoc where added. Existing explicit table names remain in use; this change does not rename the application tables to Laravel's plural-name convention.

| Model | Relationships and/or cast changes |
| --- | --- |
| `RhuUser` | Relationships for appointments, patient records, notifications, logs, and medical history; hashed password cast; `bday` serialized as `Y-m-d`; password and remember token hidden. |
| `RhuAppointment` | Belongs to the client user; datetime appointment date; decimal price. |
| `RhuClient` | Belongs to the client user; datetime record date; integer volume. |
| `RhuMedicalHistory` | Belongs to the client user. Screening responses remain strings rather than being incorrectly cast from `Yes`/`No` to booleans. |
| `Log` | Replaces `Logs`; belongs to the user; date and login/logout timestamp casts. |
| `Notification` | Belongs to the recipient user; datetime date. |
| `Receipt` | Date and decimal amount casts. |
| `RhuAnnouncement` | Date-only serialization; visibility preserved as `Yes`/`No` strings. |
| `RhuBloodType` | Integer total, current, and turned-over volumes. |
| `RhuMedicine` | Integer total, current, and turned-over quantities. |
| `RhuSupply` | Integer quantity; date-only expiration serialization. |
| `RhuTest` | Decimal price. |
| `RhuTurnedOver` | Date and integer volume casts. |

- Added `Department`, `UserStatus`, and `AppointmentStatus` backed enums under `app/Enums/`.
- The staff authorization gate uses `Department` to validate recognized departments. The change does not claim that every status/department string in the application has been replaced with enum usage or enum model casts.
- Retained `password` in the user's explicit fillable list with hashing and validated password-setting actions; it was not removed from fillable as the earlier proposal suggested.
- Removed the unused `Str` import and normalized formatting in `database/factories/RhuUserFactory.php`; its existing generated phone-number field remains in place.

## 11. Database migration and initial administrator

### Fresh-installation schema

Added `database/migrations/2026_10_26_000000_create_rhu_tables.php` to create:

1. `rhu_user`
2. `rhu_appointment`
3. `rhu_client`
4. `rhu_medical_history`
5. `rhu_announcement`
6. `rhu_blood_type`
7. `rhu_turned_over`
8. `rhu_supplies`
9. `rhu_medicine`
10. `rhu_test`
11. `notification`
12. `receipt`
13. `logs`

The migration defines the fields used by the active models/controllers, relevant indexes/unique keys, integer stock/volume fields, decimal monetary fields, date/datetime fields, timestamps, and the user remember-token column. Its `down()` method drops these tables in reverse dependency order. The existing framework migrations for users/sessions, cache, and queues remain included in the import.

### Administrator provisioning

- Added `php artisan rhu:create-admin` in `app/Console/Commands/CreateAdmin.php`.
- Prompts for first/last name, username, email, password, and password confirmation; password entry is hidden.
- Validates required/unique fields and a confirmed password of at least eight characters.
- Creates an `Active` IT department `Admin`, using the user's hashed password cast.
- Removed the default seeder's creation of a test user through the deleted default `User` model. The seeder directs initial provisioning to the new command.

The initial RHU migration is intended for an empty database. Existing SQL-imported installations need schema baselining/reconciliation before applying it. Existing application MySQL data was not migrated or changed during this work. SQLite migration tests do not establish live MySQL upgrade compatibility.

## 12. UI, configuration, exports, and cleanup

### Registration UI

- Reworked the registration Blade page with Vite/Tailwind styling, an Alpine form state object, grouped personal/address/account fields, and server-side error presentation.
- Added client-side Gmail validation and `09`/11-digit mobile feedback.
- Added password visibility toggles, strength feedback, confirmation matching, and a submitting state.
- Uses `@js()` when restoring email and mobile values into JavaScript.
- Browser-only checks supplement the server validation; server email validation is not Gmail-only.

### Other Blade changes

- Replaced hardcoded Google Maps keys in the active layout/homepage scripts with `config('services.google.maps_key')`.
- Escaped announcement flash-message output instead of using raw Blade interpolation in the affected views.
- Updated the four clinical appointment views and four record-entry views for POST notification actions and unambiguous appointment selection.
- Updated IT registration-verification approval notification handling to POST.
- Corrected the super-administrator staff-view department check in the cleaned controller.

### Environment and configuration

- Replaced application/mail/SMS/Maps secrets and personal sender values in the working `.env.example` with empty values or placeholders.
- Added `semaphore`, `philsms`, and `google` service configuration, including environment-backed API credentials and endpoint defaults.
- Kept the existing environment-backed application timezone behavior; `config/app.php` otherwise only received whitespace cleanup.
- Added ignore entries for SQL dumps, the loose original receipt document, the loose appointment text file, and the earlier implementation plan.
- Updated `README.md` with the project description, modules, stack, empty-database setup, administrator provisioning, service environment variables, project layout, verification commands, and migration caveat.
- Removing secret values from the working tree does not itself rotate credentials or rewrite existing Git objects/history. Rotation and historical cleanup were not performed or verified in this change set.

### Excel exports

- Refactored `DynamicExport` collection and heading selection into typed `match` expressions.
- Corrected the laboratory patient-export heading/data mismatch.
- Corrected the medicine `quantity` column name casing and the `Strength` typo.
- Improved supply/equipment/vaccine heading labels and added missing matching blood-export headings.
- Protected exports by category/department through authorization gates.
- Export implementation remains `FromCollection`; no chunked `FromQuery` conversion was made.

### Removed legacy material

Removed from the final working tree:

- The seven original controller filenames replaced by their `Controller`-suffixed versions.
- `app/Models/Logs.php` and the unused default `app/Models/User.php`.
- The legacy `public/admin/` HTML/PHP pages and helper script.
- Legacy public login/registration/mail pages and old static informational/appointment pages.
- The deprecated `public/js/old/` scripts.
- `resources/views/auth/old/` and the duplicate `1`-prefixed edit/layout/homepage views.
- The raw `rhu.sql` dump and loose root `receipt.docx` / `appointment.text` files.

The Bootstrap/Kaiadmin template assets remain in the application. The usable receipt template is now the separately included `resources/templates/official-receipt.docx`. The exact removed filenames are in the inventory rather than implied by the cleanup proposal.

## 13. Automated verification and coverage

### Test infrastructure

- Enabled `RefreshDatabase` for feature tests.
- Forced SQLite `:memory:` for tests and made application creation fail if that isolation is not active.
- Bypassed normal cached configuration using a separate test configuration-cache path.
- Supplied a test-only encryption key and testing environment values.
- Used fake mail/storage/HTTP behavior and prevented stray HTTP requests in the regression suite.
- Enabled exception propagation in the homepage example test to make failures actionable.

### Added test files

| File | Coverage |
| --- | --- |
| `tests/Feature/ReviewRegressionTest.php` | Fresh schema, legacy valid-password login, pending/rejected account handling, approval rechecks, client/admin boundaries, department/resource restrictions, staff creation scope, exports, all clinical record flows, file ownership, duplicate submission, blood screening, Livewire ownership, dashboards, POST-only side effects, stock reconciliation/rollback, invalid quantities, date/visibility serialization, profile access/uploads, normalized SMS, real receipt PDF/attachment generation, initial admin provisioning, registration, and OTP reset/session/attempt behavior. |
| `tests/Unit/PhilippineMobileNumberTest.php` | Supported local/international formats and malformed-number rejection. |
| `tests/Unit/PesoAmountTest.php` | Amount-to-words examples, centavos, larger values, and invalid-amount rejection. |

### Last completed checks for this code change set

| Check | Recorded result |
| --- | --- |
| `php artisan test --no-ansi` | **81 tests passed; 273 assertions.** |
| `php vendor/bin/pint --test` | **Passed across 75 PHP files.** |
| `npm run build` | **Passed**, with a Browserslist data-age advisory. Generated assets are under ignored `public/build/`. |
| `git diff --check` | **Passed** for the tracked working-tree diff. |
| Fresh migration, rollback, and reapply | **Passed** against an explicitly configured isolated SQLite in-memory database. |
| Controller-action existence audit | **Zero missing controller handlers.** |
| Application route listing | **105 application routes** excluding vendor routes; the full route collection contained 112 routes. |

These results were obtained during the implementation/review work. Creating this Markdown document does not rerun application tests. Live email/SMS delivery, a browser-level visual audit, production deployment, and a migration of the existing MySQL database were not performed.

## 14. Manual commit notes

1. Review the final working-tree changes as well as the index. Committing only the current index would omit fixes/new files and can retain legacy files removed from the working tree.
2. Include the new services, middleware, helpers, controllers, enums, logging trait/model, administrator command, RHU migration, regression tests, and official receipt template with their dependent callers.
3. Stage the reviewed `README.md` to clear its existing add/add merge state.
4. Include the intended legacy-file removals; an ignore rule alone does not remove an already staged file.
5. Decide separately whether the pre-existing `.agents/` skill resources and the imported `public/uploads_id/` assets belong in the intended commit. Their presence is listed here, not treated as an instruction to stage them.
6. Apply the documented fresh-installation versus imported-schema instructions when deploying; committing the migration does not apply it to the existing database.

Suggested commit title for the application work:

```text
Import RHU application and fix authorization, records, stock, and setup flows
```

No commit or staging operation has been performed by this documentation task.

## 15. Complete file inventory

The following snapshot is generated from `git status --porcelain=v1 -z --untracked-files=all`. It includes this document itself, the earlier staged application, unstaged changes/removals, and untracked files. Statuses describe the index and working tree at document generation time; they will change when you stage files.

| Code | Meaning in this repository snapshot |
| --- | --- |
| `A ` | Added in the index; working file matches that staged addition. |
| `AM` | Added in the index, with later working-tree modifications not yet staged. |
| `AD` | Added in the index, but removed from the working tree; the staged addition still needs updating. |
| `AA` | Existing add/add merge state requiring resolution in the index. |
| `??` | Untracked file; not included by a commit until intentionally staged. |

<!-- GENERATED_FILE_INVENTORY -->

### Snapshot totals
- Total reported paths: **973**.
- `??`: **116** paths.
- `A `: **704** paths.
- `AA`: **1** paths.
- `AD`: **101** paths.
- `AM`: **51** paths.

### Paths by directory

| Directory | Reported paths |
| --- | ---: |
| `(repository root)` | 18 |
| `.agents/` | 90 |
| `app/` | 55 |
| `bootstrap/` | 3 |
| `config/` | 10 |
| `database/` | 8 |
| `public/` | 690 |
| `resources/` | 80 |
| `routes/` | 2 |
| `storage/` | 10 |
| `tests/` | 7 |

<details>
<summary>Show all 973 file paths and Git statuses</summary>

| Git status | Path |
| --- | --- |
| `??` | `.agents/skills/api-design-principles/assets/api-design-checklist.md` |
| `??` | `.agents/skills/api-design-principles/assets/rest-api-template.py` |
| `??` | `.agents/skills/api-design-principles/references/graphql-schema-design.md` |
| `??` | `.agents/skills/api-design-principles/references/rest-best-practices.md` |
| `??` | `.agents/skills/api-design-principles/resources/implementation-playbook.md` |
| `??` | `.agents/skills/api-design-principles/SKILL.md` |
| `??` | `.agents/skills/api-documenter/SKILL.md` |
| `??` | `.agents/skills/api-testing-observability-api-mock/resources/implementation-playbook.md` |
| `??` | `.agents/skills/api-testing-observability-api-mock/SKILL.md` |
| `??` | `.agents/skills/application-performance-performance-optimization/SKILL.md` |
| `??` | `.agents/skills/architecture-patterns/resources/implementation-playbook.md` |
| `??` | `.agents/skills/architecture-patterns/SKILL.md` |
| `??` | `.agents/skills/async-python-patterns/resources/implementation-playbook.md` |
| `??` | `.agents/skills/async-python-patterns/SKILL.md` |
| `??` | `.agents/skills/backend-architect/SKILL.md` |
| `??` | `.agents/skills/backend-development-feature-development/SKILL.md` |
| `??` | `.agents/skills/backend-security-coder/SKILL.md` |
| `??` | `.agents/skills/code-documentation-doc-generate/resources/implementation-playbook.md` |
| `??` | `.agents/skills/code-documentation-doc-generate/SKILL.md` |
| `??` | `.agents/skills/django-pro/SKILL.md` |
| `??` | `.agents/skills/documentation-generation-doc-generate/resources/implementation-playbook.md` |
| `??` | `.agents/skills/documentation-generation-doc-generate/SKILL.md` |
| `??` | `.agents/skills/dotnet-architect/SKILL.md` |
| `??` | `.agents/skills/dotnet-backend-patterns/assets/repository-template.cs` |
| `??` | `.agents/skills/dotnet-backend-patterns/assets/service-template.cs` |
| `??` | `.agents/skills/dotnet-backend-patterns/references/dapper-patterns.md` |
| `??` | `.agents/skills/dotnet-backend-patterns/references/ef-core-best-practices.md` |
| `??` | `.agents/skills/dotnet-backend-patterns/resources/implementation-playbook.md` |
| `??` | `.agents/skills/dotnet-backend-patterns/SKILL.md` |
| `??` | `.agents/skills/fastapi-pro/SKILL.md` |
| `??` | `.agents/skills/fastapi-templates/resources/implementation-playbook.md` |
| `??` | `.agents/skills/fastapi-templates/SKILL.md` |
| `??` | `.agents/skills/flutter-expert/SKILL.md` |
| `??` | `.agents/skills/frontend-developer/SKILL.md` |
| `??` | `.agents/skills/frontend-mobile-development-component-scaffold/SKILL.md` |
| `??` | `.agents/skills/frontend-mobile-security-xss-scan/SKILL.md` |
| `??` | `.agents/skills/frontend-security-coder/SKILL.md` |
| `??` | `.agents/skills/go-concurrency-patterns/resources/implementation-playbook.md` |
| `??` | `.agents/skills/go-concurrency-patterns/SKILL.md` |
| `??` | `.agents/skills/golang-pro/SKILL.md` |
| `??` | `.agents/skills/ios-developer/SKILL.md` |
| `??` | `.agents/skills/java-pro/SKILL.md` |
| `??` | `.agents/skills/javascript-pro/SKILL.md` |
| `??` | `.agents/skills/javascript-testing-patterns/resources/implementation-playbook.md` |
| `??` | `.agents/skills/javascript-testing-patterns/SKILL.md` |
| `??` | `.agents/skills/javascript-typescript-typescript-scaffold/SKILL.md` |
| `??` | `.agents/skills/memory-safety-patterns/resources/implementation-playbook.md` |
| `??` | `.agents/skills/memory-safety-patterns/SKILL.md` |
| `??` | `.agents/skills/mobile-developer/SKILL.md` |
| `??` | `.agents/skills/mobile-security-coder/SKILL.md` |
| `??` | `.agents/skills/modern-javascript-patterns/resources/implementation-playbook.md` |
| `??` | `.agents/skills/modern-javascript-patterns/SKILL.md` |
| `??` | `.agents/skills/multi-platform-apps-multi-platform/SKILL.md` |
| `??` | `.agents/skills/nextjs-app-router-patterns/resources/implementation-playbook.md` |
| `??` | `.agents/skills/nextjs-app-router-patterns/SKILL.md` |
| `??` | `.agents/skills/nodejs-backend-patterns/resources/implementation-playbook.md` |
| `??` | `.agents/skills/nodejs-backend-patterns/SKILL.md` |
| `??` | `.agents/skills/openapi-spec-generation/resources/implementation-playbook.md` |
| `??` | `.agents/skills/openapi-spec-generation/SKILL.md` |
| `??` | `.agents/skills/php-pro/SKILL.md` |
| `??` | `.agents/skills/python-development-python-scaffold/SKILL.md` |
| `??` | `.agents/skills/python-packaging/resources/implementation-playbook.md` |
| `??` | `.agents/skills/python-packaging/SKILL.md` |
| `??` | `.agents/skills/python-performance-optimization/resources/implementation-playbook.md` |
| `??` | `.agents/skills/python-performance-optimization/SKILL.md` |
| `??` | `.agents/skills/python-pro/SKILL.md` |
| `??` | `.agents/skills/python-testing-patterns/resources/implementation-playbook.md` |
| `??` | `.agents/skills/python-testing-patterns/SKILL.md` |
| `??` | `.agents/skills/react-modernization/resources/implementation-playbook.md` |
| `??` | `.agents/skills/react-modernization/SKILL.md` |
| `??` | `.agents/skills/react-native-architecture/resources/implementation-playbook.md` |
| `??` | `.agents/skills/react-native-architecture/SKILL.md` |
| `??` | `.agents/skills/react-state-management/SKILL.md` |
| `??` | `.agents/skills/reference-builder/SKILL.md` |
| `??` | `.agents/skills/ruby-pro/SKILL.md` |
| `??` | `.agents/skills/rust-async-patterns/resources/implementation-playbook.md` |
| `??` | `.agents/skills/rust-async-patterns/SKILL.md` |
| `??` | `.agents/skills/rust-pro/SKILL.md` |
| `??` | `.agents/skills/systems-programming-rust-project/SKILL.md` |
| `??` | `.agents/skills/temporal-python-pro/SKILL.md` |
| `??` | `.agents/skills/temporal-python-testing/resources/integration-testing.md` |
| `??` | `.agents/skills/temporal-python-testing/resources/local-setup.md` |
| `??` | `.agents/skills/temporal-python-testing/resources/replay-testing.md` |
| `??` | `.agents/skills/temporal-python-testing/resources/unit-testing.md` |
| `??` | `.agents/skills/temporal-python-testing/SKILL.md` |
| `??` | `.agents/skills/typescript-advanced-types/resources/implementation-playbook.md` |
| `??` | `.agents/skills/typescript-advanced-types/SKILL.md` |
| `??` | `.agents/skills/typescript-pro/SKILL.md` |
| `??` | `.agents/skills/uv-package-manager/resources/implementation-playbook.md` |
| `??` | `.agents/skills/uv-package-manager/SKILL.md` |
| `A ` | `.editorconfig` |
| `AM` | `.env.example` |
| `A ` | `.gitattributes` |
| `AM` | `.gitignore` |
| `??` | `app/Console/Commands/CreateAdmin.php` |
| `??` | `app/Enums/AppointmentStatus.php` |
| `??` | `app/Enums/Department.php` |
| `??` | `app/Enums/UserStatus.php` |
| `AM` | `app/Exports/DynamicExport.php` |
| `AD` | `app/Http/Controllers/Blood.php` |
| `??` | `app/Http/Controllers/BloodController.php` |
| `AM` | `app/Http/Controllers/ClientController.php` |
| `AD` | `app/Http/Controllers/Consultation.php` |
| `??` | `app/Http/Controllers/ConsultationController.php` |
| `A ` | `app/Http/Controllers/Controller.php` |
| `AM` | `app/Http/Controllers/ForgotPasswordController.php` |
| `AD` | `app/Http/Controllers/Inventory.php` |
| `??` | `app/Http/Controllers/InventoryController.php` |
| `AD` | `app/Http/Controllers/ITDepartment.php` |
| `??` | `app/Http/Controllers/ITDepartmentController.php` |
| `AD` | `app/Http/Controllers/Laboratory.php` |
| `??` | `app/Http/Controllers/LaboratoryController.php` |
| `AM` | `app/Http/Controllers/ReceiptController.php` |
| `AM` | `app/Http/Controllers/RegisteredUserController.php` |
| `AM` | `app/Http/Controllers/SessionController.php` |
| `AD` | `app/Http/Controllers/SuperAdmin.php` |
| `??` | `app/Http/Controllers/SuperAdminController.php` |
| `AD` | `app/Http/Controllers/Vaccination.php` |
| `??` | `app/Http/Controllers/VaccinationController.php` |
| `??` | `app/Http/Middleware/EnsureAccountApproved.php` |
| `??` | `app/Http/Middleware/EnsureDepartment.php` |
| `AM` | `app/Livewire/Notifications.php` |
| `AM` | `app/Mail/AccountApproved.php` |
| `AM` | `app/Mail/AppointmentApproved.php` |
| `AM` | `app/Mail/DemoMail.php` |
| `AM` | `app/Mail/OtpMail.php` |
| `??` | `app/Models/Log.php` |
| `AD` | `app/Models/Logs.php` |
| `AM` | `app/Models/Notification.php` |
| `AM` | `app/Models/Receipt.php` |
| `AM` | `app/Models/RhuAnnouncement.php` |
| `AM` | `app/Models/RhuAppointment.php` |
| `AM` | `app/Models/RhuBloodType.php` |
| `AM` | `app/Models/RhuClient.php` |
| `AM` | `app/Models/RhuMedicalHistory.php` |
| `AM` | `app/Models/RhuMedicine.php` |
| `AM` | `app/Models/RhuSupply.php` |
| `AM` | `app/Models/RhuTest.php` |
| `AM` | `app/Models/RhuTurnedOver.php` |
| `AM` | `app/Models/RhuUser.php` |
| `AD` | `app/Models/User.php` |
| `AM` | `app/Providers/AppServiceProvider.php` |
| `??` | `app/Services/AppointmentRecordService.php` |
| `??` | `app/Services/BloodTurnoverService.php` |
| `??` | `app/Services/ReceiptService.php` |
| `AM` | `app/Services/SemaphoreService.php` |
| `??` | `app/Support/PesoAmount.php` |
| `??` | `app/Support/PhilippineMobileNumber.php` |
| `??` | `app/Traits/LogsActivity.php` |
| `AD` | `appointment.text` |
| `A ` | `artisan` |
| `AM` | `bootstrap/app.php` |
| `A ` | `bootstrap/cache/.gitignore` |
| `A ` | `bootstrap/providers.php` |
| `??` | `CHANGES_BEFORE_COMMIT.md` |
| `A ` | `composer.json` |
| `A ` | `composer.lock` |
| `AM` | `config/app.php` |
| `A ` | `config/auth.php` |
| `A ` | `config/cache.php` |
| `A ` | `config/database.php` |
| `A ` | `config/filesystems.php` |
| `A ` | `config/logging.php` |
| `A ` | `config/mail.php` |
| `A ` | `config/queue.php` |
| `AM` | `config/services.php` |
| `A ` | `config/session.php` |
| `A ` | `database/.gitignore` |
| `AM` | `database/factories/RhuUserFactory.php` |
| `A ` | `database/factories/UserFactory.php` |
| `A ` | `database/migrations/0001_01_01_000000_create_users_table.php` |
| `A ` | `database/migrations/0001_01_01_000001_create_cache_table.php` |
| `A ` | `database/migrations/0001_01_01_000002_create_jobs_table.php` |
| `??` | `database/migrations/2026_10_26_000000_create_rhu_tables.php` |
| `AM` | `database/seeders/DatabaseSeeder.php` |
| `A ` | `package-lock.json` |
| `A ` | `package.json` |
| `AM` | `phpunit.xml` |
| `A ` | `postcss.config.js` |
| `A ` | `public/.htaccess` |
| `AD` | `public/about.html` |
| `AD` | `public/admin/add_admin.php` |
| `AD` | `public/admin/blood.html` |
| `AD` | `public/admin/blood_appointments.html` |
| `AD` | `public/admin/blood_approved.html` |
| `AD` | `public/admin/blood_completed.html` |
| `AD` | `public/admin/blood_donation.html` |
| `AD` | `public/admin/blood_donor.html` |
| `AD` | `public/admin/blood_pending.html` |
| `AD` | `public/admin/blood_profile.html` |
| `AD` | `public/admin/blood_rejected.html` |
| `AD` | `public/admin/blood_report.html` |
| `AD` | `public/admin/blood_turnover.html` |
| `AD` | `public/admin/cashier.html` |
| `AD` | `public/admin/cashier_transaction.html` |
| `AD` | `public/admin/consultation.php` |
| `AD` | `public/admin/consultation_appointments.html` |
| `AD` | `public/admin/consultation_approved.html` |
| `AD` | `public/admin/consultation_completed.html` |
| `AD` | `public/admin/consultation_patient.html` |
| `AD` | `public/admin/consultation_pending.html` |
| `AD` | `public/admin/consultation_profile.html` |
| `AD` | `public/admin/consultation_rejected.html` |
| `AD` | `public/admin/consultation_report.html` |
| `AD` | `public/admin/consultation_staff.html` |
| `AD` | `public/admin/dental.html` |
| `AD` | `public/admin/inventory.html` |
| `AD` | `public/admin/inventory_equipment.html` |
| `AD` | `public/admin/inventory_med.html` |
| `AD` | `public/admin/inventory_report.html` |
| `AD` | `public/admin/inventory_supply.html` |
| `AD` | `public/admin/inventory_vaccine.html` |
| `AD` | `public/admin/it_admin.php` |
| `AD` | `public/admin/it_audit.html` |
| `AD` | `public/admin/it_personnel.html` |
| `AD` | `public/admin/it_profile.html` |
| `AD` | `public/admin/it_verify.php` |
| `AD` | `public/admin/laboratory.php` |
| `AD` | `public/admin/laboratory_appointments.php` |
| `AD` | `public/admin/laboratory_approved.html` |
| `AD` | `public/admin/laboratory_completed.html` |
| `AD` | `public/admin/laboratory_patient.php` |
| `AD` | `public/admin/laboratory_pending.html` |
| `AD` | `public/admin/laboratory_profile.html` |
| `AD` | `public/admin/laboratory_rejected.html` |
| `AD` | `public/admin/laboratory_report.html` |
| `AD` | `public/admin/laboratory_staff.html` |
| `AD` | `public/admin/laboratory_test.php` |
| `AD` | `public/admin/py.py` |
| `AD` | `public/admin/super_admin.html` |
| `AD` | `public/admin/super_announcement.html` |
| `AD` | `public/admin/super_appointments.html` |
| `AD` | `public/admin/super_approved.html` |
| `AD` | `public/admin/super_completed.html` |
| `AD` | `public/admin/super_patient.html` |
| `AD` | `public/admin/super_pending.html` |
| `AD` | `public/admin/super_profile.html` |
| `AD` | `public/admin/super_rejected.html` |
| `AD` | `public/admin/super_report.html` |
| `AD` | `public/admin/super_request.html` |
| `AD` | `public/admin/super_staff.html` |
| `AD` | `public/admin/vaccination.html` |
| `AD` | `public/admin/vaccination_approved.html` |
| `AD` | `public/admin/vaccination_completed.html` |
| `AD` | `public/admin/vaccination_patient.html` |
| `AD` | `public/admin/vaccination_pending.html` |
| `AD` | `public/admin/vaccination_profile.html` |
| `AD` | `public/admin/vaccination_rejected.html` |
| `AD` | `public/admin/vaccination_report.html` |
| `AD` | `public/admin/vaccination_staff.html` |
| `AD` | `public/adminlogin.php` |
| `AD` | `public/appoinment.html` |
| `A ` | `public/bootstrap-template/assets/css/bootstrap.min.css` |
| `A ` | `public/bootstrap-template/assets/css/demo.css` |
| `A ` | `public/bootstrap-template/assets/css/demo.css.map` |
| `A ` | `public/bootstrap-template/assets/css/fonts.css` |
| `A ` | `public/bootstrap-template/assets/css/fonts.min.css` |
| `A ` | `public/bootstrap-template/assets/css/kaiadmin.css` |
| `A ` | `public/bootstrap-template/assets/css/kaiadmin.css.map` |
| `A ` | `public/bootstrap-template/assets/css/kaiadmin.min.css` |
| `A ` | `public/bootstrap-template/assets/css/plugins.css` |
| `A ` | `public/bootstrap-template/assets/css/plugins.css.map` |
| `A ` | `public/bootstrap-template/assets/css/plugins.min.css` |
| `A ` | `public/bootstrap-template/assets/fonts/fontawesome/fa-brands-400.eot` |
| `A ` | `public/bootstrap-template/assets/fonts/fontawesome/fa-brands-400.svg` |
| `A ` | `public/bootstrap-template/assets/fonts/fontawesome/fa-brands-400.ttf` |
| `A ` | `public/bootstrap-template/assets/fonts/fontawesome/fa-brands-400.woff` |
| `A ` | `public/bootstrap-template/assets/fonts/fontawesome/fa-brands-400.woff2` |
| `A ` | `public/bootstrap-template/assets/fonts/fontawesome/fa-regular-400.eot` |
| `A ` | `public/bootstrap-template/assets/fonts/fontawesome/fa-regular-400.svg` |
| `A ` | `public/bootstrap-template/assets/fonts/fontawesome/fa-regular-400.ttf` |
| `A ` | `public/bootstrap-template/assets/fonts/fontawesome/fa-regular-400.woff` |
| `A ` | `public/bootstrap-template/assets/fonts/fontawesome/fa-regular-400.woff2` |
| `A ` | `public/bootstrap-template/assets/fonts/fontawesome/fa-solid-900.eot` |
| `A ` | `public/bootstrap-template/assets/fonts/fontawesome/fa-solid-900.svg` |
| `A ` | `public/bootstrap-template/assets/fonts/fontawesome/fa-solid-900.ttf` |
| `A ` | `public/bootstrap-template/assets/fonts/fontawesome/fa-solid-900.woff` |
| `A ` | `public/bootstrap-template/assets/fonts/fontawesome/fa-solid-900.woff2` |
| `A ` | `public/bootstrap-template/assets/fonts/simple-line-icons/Simple-Line-Icons.eot` |
| `A ` | `public/bootstrap-template/assets/fonts/simple-line-icons/Simple-Line-Icons.svg` |
| `A ` | `public/bootstrap-template/assets/fonts/simple-line-icons/Simple-Line-Icons.ttf` |
| `A ` | `public/bootstrap-template/assets/fonts/simple-line-icons/Simple-Line-Icons.woff` |
| `A ` | `public/bootstrap-template/assets/fonts/simple-line-icons/Simple-Line-Icons.woff2` |
| `A ` | `public/bootstrap-template/assets/fonts/summernote/summernote.eot` |
| `A ` | `public/bootstrap-template/assets/fonts/summernote/summernote.ttf` |
| `A ` | `public/bootstrap-template/assets/fonts/summernote/summernote.woff` |
| `A ` | `public/bootstrap-template/assets/fonts/summernote/summernote.woff2` |
| `A ` | `public/bootstrap-template/assets/img/arashmil.jpg` |
| `A ` | `public/bootstrap-template/assets/img/bg-404.jpeg` |
| `A ` | `public/bootstrap-template/assets/img/blogpost.jpg` |
| `A ` | `public/bootstrap-template/assets/img/chadengle.jpg` |
| `A ` | `public/bootstrap-template/assets/img/examples/example1-300x300.jpg` |
| `A ` | `public/bootstrap-template/assets/img/examples/example1.jpeg` |
| `A ` | `public/bootstrap-template/assets/img/examples/example10-300x300.jpg` |
| `A ` | `public/bootstrap-template/assets/img/examples/example10.jpeg` |
| `A ` | `public/bootstrap-template/assets/img/examples/example11-300x300.jpg` |
| `A ` | `public/bootstrap-template/assets/img/examples/example11.jpeg` |
| `A ` | `public/bootstrap-template/assets/img/examples/example12-300x300.jpg` |
| `A ` | `public/bootstrap-template/assets/img/examples/example12.jpeg` |
| `A ` | `public/bootstrap-template/assets/img/examples/example2-300x300.jpg` |
| `A ` | `public/bootstrap-template/assets/img/examples/example2.jpeg` |
| `A ` | `public/bootstrap-template/assets/img/examples/example3-300x300.jpg` |
| `A ` | `public/bootstrap-template/assets/img/examples/example3.jpeg` |
| `A ` | `public/bootstrap-template/assets/img/examples/example4-300x300.jpg` |
| `A ` | `public/bootstrap-template/assets/img/examples/example4.jpeg` |
| `A ` | `public/bootstrap-template/assets/img/examples/example5-300x300.jpg` |
| `A ` | `public/bootstrap-template/assets/img/examples/example5.jpeg` |
| `A ` | `public/bootstrap-template/assets/img/examples/example6-300x300.jpg` |
| `A ` | `public/bootstrap-template/assets/img/examples/example6.jpeg` |
| `A ` | `public/bootstrap-template/assets/img/examples/example7-300x300.jpg` |
| `A ` | `public/bootstrap-template/assets/img/examples/example7.jpeg` |
| `A ` | `public/bootstrap-template/assets/img/examples/example8-300x300.jpg` |
| `A ` | `public/bootstrap-template/assets/img/examples/example8.jpeg` |
| `A ` | `public/bootstrap-template/assets/img/examples/example9-300x300.jpg` |
| `A ` | `public/bootstrap-template/assets/img/examples/example9.jpeg` |
| `A ` | `public/bootstrap-template/assets/img/examples/logo.svg` |
| `A ` | `public/bootstrap-template/assets/img/examples/logo1.svg` |
| `A ` | `public/bootstrap-template/assets/img/examples/logo2.svg` |
| `A ` | `public/bootstrap-template/assets/img/examples/logoinvoice.svg` |
| `A ` | `public/bootstrap-template/assets/img/examples/product1.jpg` |
| `A ` | `public/bootstrap-template/assets/img/examples/product10.jpeg` |
| `A ` | `public/bootstrap-template/assets/img/examples/product11.jpeg` |
| `A ` | `public/bootstrap-template/assets/img/examples/product12.jpeg` |
| `A ` | `public/bootstrap-template/assets/img/examples/product2.jpg` |
| `A ` | `public/bootstrap-template/assets/img/examples/product3.jpg` |
| `A ` | `public/bootstrap-template/assets/img/examples/product4.jpg` |
| `A ` | `public/bootstrap-template/assets/img/examples/product5.jpg` |
| `A ` | `public/bootstrap-template/assets/img/examples/product6.jpg` |
| `A ` | `public/bootstrap-template/assets/img/examples/product7.jpg` |
| `A ` | `public/bootstrap-template/assets/img/examples/product8.jpg` |
| `A ` | `public/bootstrap-template/assets/img/examples/rhu-logo.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ad.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ae.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/af.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ag.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ai.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/al.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/am.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/an.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ao.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ar.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/as.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/at.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/au.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/aw.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ax.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/az.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ba.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/bb.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/bd.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/be.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/bf.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/bg.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/bh.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/bi.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/bj.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/bm.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/bn.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/bo.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/br.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/bs.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/bt.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/bv.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/bw.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/by.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/bz.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ca.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/catalonia.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/cc.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/cd.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/cf.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/cg.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ch.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ci.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ck.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/cl.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/cm.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/cn.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/co.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/cr.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/cs.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/cu.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/cv.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/cx.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/cy.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/cz.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/de.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/dj.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/dk.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/dm.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/do.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/dz.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ec.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ee.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/eg.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/eh.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/england.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/er.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/es.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/et.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/europeanunion.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/fam.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/fi.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/fj.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/fk.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/fm.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/fo.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/fr.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ga.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/gb.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/gd.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ge.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/gf.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/gh.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/gi.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/gl.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/gm.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/gn.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/gp.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/gq.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/gr.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/gs.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/gt.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/gu.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/gw.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/gy.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/hk.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/hm.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/hn.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/hr.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ht.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/hu.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/id.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ie.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/il.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/in.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/io.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/iq.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ir.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/is.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/it.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/jm.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/jo.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/jp.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ke.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/kg.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/kh.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ki.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/km.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/kn.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/kp.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/kr.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/kw.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ky.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/kz.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/la.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/lb.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/lc.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/li.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/lk.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/lr.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ls.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/lt.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/lu.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/lv.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ly.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ma.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/mc.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/md.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/me.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/mg.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/mh.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/mk.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ml.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/mm.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/mn.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/mo.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/mp.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/mq.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/mr.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ms.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/mt.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/mu.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/mv.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/mw.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/mx.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/my.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/mz.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/na.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/nc.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ne.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/nf.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ng.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ni.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/nl.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/no.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/np.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/nr.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/nu.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/nz.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/om.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/pa.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/pe.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/pf.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/pg.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ph.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/pk.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/pl.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/pm.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/pn.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/pr.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ps.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/pt.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/pw.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/py.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/qa.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/re.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/readme.txt` |
| `A ` | `public/bootstrap-template/assets/img/flags/ro.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/rs.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ru.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/rw.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/sa.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/sb.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/sc.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/scotland.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/sd.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/se.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/sg.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/sh.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/si.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/sj.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/sk.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/sl.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/sm.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/sn.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/so.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/sr.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/st.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/sv.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/sy.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/sz.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/tc.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/td.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/tf.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/tg.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/th.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/tj.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/tk.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/tl.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/tm.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/tn.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/to.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/tr.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/tt.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/tv.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/tw.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/tz.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ua.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ug.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/um.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/us.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/uy.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/uz.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/va.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/vc.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ve.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/vg.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/vi.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/vn.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/vu.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/wales.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/wf.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ws.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/ye.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/yt.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/za.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/zm.png` |
| `A ` | `public/bootstrap-template/assets/img/flags/zw.png` |
| `A ` | `public/bootstrap-template/assets/img/img-shadow.png` |
| `A ` | `public/bootstrap-template/assets/img/jm_denis.jpg` |
| `A ` | `public/bootstrap-template/assets/img/kaiadmin/demo1.png` |
| `A ` | `public/bootstrap-template/assets/img/kaiadmin/demo2.png` |
| `A ` | `public/bootstrap-template/assets/img/kaiadmin/demo3.png` |
| `A ` | `public/bootstrap-template/assets/img/kaiadmin/demo4.png` |
| `A ` | `public/bootstrap-template/assets/img/kaiadmin/demo5.png` |
| `A ` | `public/bootstrap-template/assets/img/kaiadmin/demo6.png` |
| `A ` | `public/bootstrap-template/assets/img/kaiadmin/demo7.png` |
| `A ` | `public/bootstrap-template/assets/img/kaiadmin/demo8.png` |
| `A ` | `public/bootstrap-template/assets/img/kaiadmin/demo9.png` |
| `A ` | `public/bootstrap-template/assets/img/kaiadmin/favicon.ico` |
| `A ` | `public/bootstrap-template/assets/img/kaiadmin/favicon.png` |
| `A ` | `public/bootstrap-template/assets/img/kaiadmin/favicon.svg` |
| `A ` | `public/bootstrap-template/assets/img/kaiadmin/icon.png` |
| `A ` | `public/bootstrap-template/assets/img/kaiadmin/icon.svg` |
| `A ` | `public/bootstrap-template/assets/img/kaiadmin/logo_dark.png` |
| `A ` | `public/bootstrap-template/assets/img/kaiadmin/logo_dark.svg` |
| `A ` | `public/bootstrap-template/assets/img/kaiadmin/logo_documentation.png` |
| `A ` | `public/bootstrap-template/assets/img/kaiadmin/logo_light.png` |
| `A ` | `public/bootstrap-template/assets/img/kaiadmin/logo_light.svg` |
| `A ` | `public/bootstrap-template/assets/img/logoproduct.svg` |
| `A ` | `public/bootstrap-template/assets/img/logoproduct2.svg` |
| `A ` | `public/bootstrap-template/assets/img/logoproduct3.svg` |
| `A ` | `public/bootstrap-template/assets/img/mlane.jpg` |
| `A ` | `public/bootstrap-template/assets/img/profile.jpg` |
| `A ` | `public/bootstrap-template/assets/img/profile2.jpg` |
| `A ` | `public/bootstrap-template/assets/img/rhu-logo.png` |
| `A ` | `public/bootstrap-template/assets/img/sauro.jpg` |
| `A ` | `public/bootstrap-template/assets/img/talha.jpg` |
| `A ` | `public/bootstrap-template/assets/img/undraw/undraw_blank_canvas_3rbb.svg` |
| `A ` | `public/bootstrap-template/assets/img/undraw/undraw_creative_team_r90h.svg` |
| `A ` | `public/bootstrap-template/assets/img/undraw/undraw_empty_xct9.svg` |
| `A ` | `public/bootstrap-template/assets/img/undraw/undraw_Hello_qnas.svg` |
| `A ` | `public/bootstrap-template/assets/img/undraw/undraw_no_data_qbuo.svg` |
| `A ` | `public/bootstrap-template/assets/img/undraw/undraw_not_found_60pq.svg` |
| `A ` | `public/bootstrap-template/assets/img/undraw/undraw_page_not_found_su7k.svg` |
| `A ` | `public/bootstrap-template/assets/img/undraw/undraw_sign_in_e6hj.svg` |
| `A ` | `public/bootstrap-template/assets/img/undraw/undraw_tabs_jf82.svg` |
| `A ` | `public/bootstrap-template/assets/img/undraw/undraw_Taken_if77.svg` |
| `A ` | `public/bootstrap-template/assets/img/undraw/undraw_update_uxn2.svg` |
| `A ` | `public/bootstrap-template/assets/img/undraw/undraw_upgrade_06a0.svg` |
| `A ` | `public/bootstrap-template/assets/img/visa.svg` |
| `A ` | `public/bootstrap-template/assets/js/core/bootstrap.min.js` |
| `A ` | `public/bootstrap-template/assets/js/core/jquery-3.7.1.min.js` |
| `A ` | `public/bootstrap-template/assets/js/core/popper.min.js` |
| `A ` | `public/bootstrap-template/assets/js/demo.js` |
| `A ` | `public/bootstrap-template/assets/js/kaiadmin.js` |
| `A ` | `public/bootstrap-template/assets/js/kaiadmin.min.js` |
| `A ` | `public/bootstrap-template/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js` |
| `A ` | `public/bootstrap-template/assets/js/plugin/bootstrap-tagsinput/bootstrap-tagsinput.min.js` |
| `A ` | `public/bootstrap-template/assets/js/plugin/bootstrap-toggle/bootstrap-toggle.min.js` |
| `A ` | `public/bootstrap-template/assets/js/plugin/chart-circle/circles.min.js` |
| `A ` | `public/bootstrap-template/assets/js/plugin/chart.js/chart.min.js` |
| `A ` | `public/bootstrap-template/assets/js/plugin/datatables/datatables.min.js` |
| `A ` | `public/bootstrap-template/assets/js/plugin/datepicker/bootstrap-datetimepicker.min.js` |
| `A ` | `public/bootstrap-template/assets/js/plugin/dropzone/dropzone.min.js` |
| `A ` | `public/bootstrap-template/assets/js/plugin/fullcalendar/fullcalendar.min.js` |
| `A ` | `public/bootstrap-template/assets/js/plugin/gmaps/gmaps.js` |
| `A ` | `public/bootstrap-template/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js` |
| `A ` | `public/bootstrap-template/assets/js/plugin/jquery.magnific-popup/jquery.magnific-popup.min.js` |
| `A ` | `public/bootstrap-template/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js` |
| `A ` | `public/bootstrap-template/assets/js/plugin/jquery.validate/jquery.validate.min.js` |
| `A ` | `public/bootstrap-template/assets/js/plugin/jsvectormap/jsvectormap.min.js` |
| `A ` | `public/bootstrap-template/assets/js/plugin/jsvectormap/world.js` |
| `A ` | `public/bootstrap-template/assets/js/plugin/list.js/list.min.js` |
| `A ` | `public/bootstrap-template/assets/js/plugin/moment/moment.min.js` |
| `A ` | `public/bootstrap-template/assets/js/plugin/owl-carousel/ajax-loader.gif` |
| `A ` | `public/bootstrap-template/assets/js/plugin/owl-carousel/owl.carousel.min.js` |
| `A ` | `public/bootstrap-template/assets/js/plugin/owl-carousel/owl.video.play.png` |
| `A ` | `public/bootstrap-template/assets/js/plugin/select2/select2.full.min.js` |
| `A ` | `public/bootstrap-template/assets/js/plugin/sortable/sortable.min.js` |
| `A ` | `public/bootstrap-template/assets/js/plugin/sticky-sidebar/sticky-sidebar.min.js` |
| `A ` | `public/bootstrap-template/assets/js/plugin/summernote/summernote-lite.min.js` |
| `A ` | `public/bootstrap-template/assets/js/plugin/sweetalert/sweetalert.min.js` |
| `A ` | `public/bootstrap-template/assets/js/plugin/webfont/webfont.min.js` |
| `A ` | `public/bootstrap-template/assets/js/setting-demo.js` |
| `A ` | `public/bootstrap-template/assets/js/setting-demo2.js` |
| `A ` | `public/changelog.md` |
| `AD` | `public/confirmation.html` |
| `AD` | `public/contact.html` |
| `A ` | `public/css/1register.css` |
| `A ` | `public/css/1style.css` |
| `A ` | `public/css/active-window.css` |
| `A ` | `public/css/ange.css` |
| `A ` | `public/css/custom.css` |
| `A ` | `public/css/input.css` |
| `A ` | `public/css/logs.css` |
| `A ` | `public/css/manage-window.css` |
| `A ` | `public/css/maps/style.css.map` |
| `A ` | `public/css/output.css` |
| `A ` | `public/css/queue-stack.css` |
| `A ` | `public/css/register.css` |
| `A ` | `public/css/sidebar.css` |
| `A ` | `public/css/style.css` |
| `A ` | `public/favicon.ico` |
| `A ` | `public/images/about/05.png` |
| `A ` | `public/images/about/1.jpg` |
| `A ` | `public/images/about/2.jpg` |
| `A ` | `public/images/about/3.jpg` |
| `A ` | `public/images/about/4.png` |
| `A ` | `public/images/about/5.png` |
| `A ` | `public/images/about/6.png` |
| `A ` | `public/images/about/about-1.jpg` |
| `A ` | `public/images/about/about-2.jpg` |
| `A ` | `public/images/about/about-3.jpg` |
| `A ` | `public/images/about/about-3.png` |
| `A ` | `public/images/about/about-4.jpg` |
| `A ` | `public/images/about/Blood.jpg` |
| `A ` | `public/images/about/Consultation.jpg` |
| `A ` | `public/images/about/Front.jpg` |
| `A ` | `public/images/about/img-1.jpg` |
| `A ` | `public/images/about/img-2.jpg` |
| `A ` | `public/images/about/img-3.jpg` |
| `A ` | `public/images/about/Laboratory.jpg` |
| `A ` | `public/images/about/sign.png` |
| `A ` | `public/images/bg/22.jpg` |
| `A ` | `public/images/bg/banner.jpg` |
| `A ` | `public/images/bg/bg-2.jpg` |
| `A ` | `public/images/bg/bg-4.jpg` |
| `A ` | `public/images/bg/slider-bg-2.png` |
| `A ` | `public/images/blog/blog-1.jpg` |
| `A ` | `public/images/blog/blog-2.jpg` |
| `A ` | `public/images/blog/blog-4.jpg` |
| `A ` | `public/images/blog/blog-6.jpg` |
| `A ` | `public/images/blog/blog-7.jpg` |
| `A ` | `public/images/blog/blog-8.jpg` |
| `A ` | `public/images/blog/testimonial1.jpg` |
| `A ` | `public/images/blog/testimonial2.jpg` |
| `A ` | `public/images/ehealth.png` |
| `A ` | `public/images/img/avail.svg` |
| `A ` | `public/images/img/bell.svg` |
| `A ` | `public/images/img/blood.png` |
| `A ` | `public/images/img/chikiting.png` |
| `A ` | `public/images/img/confirm.svg` |
| `A ` | `public/images/img/consult.png` |
| `A ` | `public/images/img/consultation.png` |
| `A ` | `public/images/img/dietary.png` |
| `A ` | `public/images/img/Doctt.png` |
| `A ` | `public/images/img/droplet.png` |
| `A ` | `public/images/img/droplet.svg` |
| `A ` | `public/images/img/ehealth.png` |
| `A ` | `public/images/img/equipment.png` |
| `A ` | `public/images/img/follow.svg` |
| `A ` | `public/images/img/Formal Picture.png` |
| `A ` | `public/images/img/heat.png` |
| `A ` | `public/images/img/history.png` |
| `A ` | `public/images/img/home1.png` |
| `A ` | `public/images/img/home2.png` |
| `A ` | `public/images/img/home3.png` |
| `A ` | `public/images/img/lab.png` |
| `A ` | `public/images/img/login.png` |
| `A ` | `public/images/img/logo1.png` |
| `A ` | `public/images/img/logo2.png` |
| `A ` | `public/images/img/Lucban Logo.jpg` |
| `A ` | `public/images/img/mail.svg` |
| `A ` | `public/images/img/Mallari.png` |
| `A ` | `public/images/img/mayor.png` |
| `A ` | `public/images/img/med.png` |
| `A ` | `public/images/img/notif.png` |
| `A ` | `public/images/img/others.png` |
| `A ` | `public/images/img/pen.svg` |
| `A ` | `public/images/img/resbakuna.jpg` |
| `A ` | `public/images/img/rhu-workers.png` |
| `A ` | `public/images/img/rhu.png` |
| `A ` | `public/images/img/rhu1.png` |
| `A ` | `public/images/img/RHUbackg.png` |
| `A ` | `public/images/img/sample.png` |
| `A ` | `public/images/img/send.svg` |
| `A ` | `public/images/img/Sentrong Sigla Logo.png` |
| `A ` | `public/images/img/step1.png` |
| `A ` | `public/images/img/supplies.png` |
| `A ` | `public/images/img/Tenten.png` |
| `A ` | `public/images/img/user.png` |
| `A ` | `public/images/img/vax.png` |
| `A ` | `public/images/img/vaxx.png` |
| `A ` | `public/images/img/verify.svg` |
| `A ` | `public/images/News/News1.jpg` |
| `A ` | `public/images/News/News2.jpg` |
| `A ` | `public/images/News/News3.jpg` |
| `A ` | `public/images/people/aven.jpg` |
| `A ` | `public/images/people/charm.jpg` |
| `A ` | `public/images/people/kaka.jpg` |
| `A ` | `public/images/people/Mallari.png` |
| `A ` | `public/images/people/mayor.png` |
| `A ` | `public/images/people/nikki.jpg` |
| `A ` | `public/images/people/Tenten.png` |
| `A ` | `public/images/service/bg-1.jpg` |
| `A ` | `public/images/service/service-1.jpg` |
| `A ` | `public/images/service/service-2.jpg` |
| `A ` | `public/images/service/service-3.jpg` |
| `A ` | `public/images/service/service-4.jpg` |
| `A ` | `public/images/service/service-6.jpg` |
| `A ` | `public/images/service/service-8.jpg` |
| `A ` | `public/images/team/1.jpg` |
| `A ` | `public/images/team/2.jpg` |
| `A ` | `public/images/team/3.jpg` |
| `A ` | `public/images/team/4.jpg` |
| `A ` | `public/images/team/test-thumb1.jpg` |
| `A ` | `public/images/team/test-thumb2.jpg` |
| `A ` | `public/images/team/test-thumb3.jpg` |
| `A ` | `public/images/team/test-thumb4.jpg` |
| `A ` | `public/index.php` |
| `A ` | `public/js/appointment-validation.js` |
| `A ` | `public/js/contact.js` |
| `A ` | `public/js/faq.js` |
| `A ` | `public/js/modal.js` |
| `AD` | `public/js/old/contact.js` |
| `AD` | `public/js/old/faq.js` |
| `AD` | `public/js/old/Registration.js` |
| `AD` | `public/js/old/script.js` |
| `AD` | `public/js/old/service.js` |
| `A ` | `public/js/Registration.js` |
| `A ` | `public/js/script.js` |
| `A ` | `public/js/service.js` |
| `A ` | `public/js/Upload.js` |
| `A ` | `public/license` |
| `AD` | `public/login1.php` |
| `AD` | `public/mail.php` |
| `AD` | `public/news-details.html` |
| `A ` | `public/plugins/bootstrap/css/bootstrap.min.css` |
| `A ` | `public/plugins/bootstrap/js/bootstrap.js` |
| `A ` | `public/plugins/bootstrap/js/bootstrap.min.js` |
| `A ` | `public/plugins/bootstrap/js/popper.js` |
| `A ` | `public/plugins/counterup/jquery.counterup.min.js` |
| `A ` | `public/plugins/counterup/jquery.easing.js` |
| `A ` | `public/plugins/counterup/jquery.waypoints.min.js` |
| `A ` | `public/plugins/google-map/map.js` |
| `A ` | `public/plugins/icofont/demo.html` |
| `A ` | `public/plugins/icofont/fonts/icofont.eot` |
| `A ` | `public/plugins/icofont/fonts/icofont.ttf` |
| `A ` | `public/plugins/icofont/fonts/icofont.woff` |
| `A ` | `public/plugins/icofont/fonts/icofont.woff2` |
| `A ` | `public/plugins/icofont/icofont.css` |
| `A ` | `public/plugins/icofont/icofont.min.css` |
| `A ` | `public/plugins/jquery/jquery.js` |
| `A ` | `public/plugins/shuffle/shuffle.min.js` |
| `A ` | `public/plugins/slick-carousel/slick/ajax-loader.gif` |
| `A ` | `public/plugins/slick-carousel/slick/config.rb` |
| `A ` | `public/plugins/slick-carousel/slick/fonts/slick.eot` |
| `A ` | `public/plugins/slick-carousel/slick/fonts/slick.svg` |
| `A ` | `public/plugins/slick-carousel/slick/fonts/slick.ttf` |
| `A ` | `public/plugins/slick-carousel/slick/fonts/slick.woff` |
| `A ` | `public/plugins/slick-carousel/slick/slick-theme.css` |
| `A ` | `public/plugins/slick-carousel/slick/slick-theme.less` |
| `A ` | `public/plugins/slick-carousel/slick/slick-theme.scss` |
| `A ` | `public/plugins/slick-carousel/slick/slick.css` |
| `A ` | `public/plugins/slick-carousel/slick/slick.js` |
| `A ` | `public/plugins/slick-carousel/slick/slick.less` |
| `A ` | `public/plugins/slick-carousel/slick/slick.min.js` |
| `A ` | `public/plugins/slick-carousel/slick/slick.scss` |
| `AD` | `public/register.php` |
| `A ` | `public/robots.txt` |
| `A ` | `public/scss/_color.scss` |
| `A ` | `public/scss/_common.scss` |
| `A ` | `public/scss/_media-query.scss` |
| `A ` | `public/scss/_mixins.scss` |
| `A ` | `public/scss/_typography.scss` |
| `A ` | `public/scss/_variables.scss` |
| `A ` | `public/scss/style.scss` |
| `A ` | `public/scss/templates/_about.scss` |
| `A ` | `public/scss/templates/_backgrounds.scss` |
| `A ` | `public/scss/templates/_blog-sidebar.scss` |
| `A ` | `public/scss/templates/_blog.scss` |
| `A ` | `public/scss/templates/_contact.scss` |
| `A ` | `public/scss/templates/_counter.scss` |
| `A ` | `public/scss/templates/_cta.scss` |
| `A ` | `public/scss/templates/_department.scss` |
| `A ` | `public/scss/templates/_feature.scss` |
| `A ` | `public/scss/templates/_footer.scss` |
| `A ` | `public/scss/templates/_header.scss` |
| `A ` | `public/scss/templates/_navigation.scss` |
| `A ` | `public/scss/templates/_portfolio.scss` |
| `A ` | `public/scss/templates/_review.scss` |
| `A ` | `public/scss/templates/_service.scss` |
| `A ` | `public/scss/templates/_single-post.scss` |
| `A ` | `public/scss/templates/_slider.scss` |
| `A ` | `public/scss/templates/_team.scss` |
| `AD` | `public/service.html` |
| `A ` | `public/uploads_id/6757a6e18c2be.jpg` |
| `A ` | `public/uploads_id/6757a74c3b07f.jpg` |
| `A ` | `public/uploads_id/6757aac7642eb.jpg` |
| `A ` | `public/uploads_id/6757ab1155d1e.jpg` |
| `A ` | `public/uploads_id/6757ab85abac6.jpg` |
| `A ` | `public/uploads_id/6757ac6a22314.png` |
| `A ` | `public/uploads_id/6757bacc02373.jpg` |
| `A ` | `public/uploads_id/6757bb9f985c0.jpg` |
| `A ` | `public/uploads_id/6757bbd5c7a8e.jpg` |
| `A ` | `public/uploads_id/6757bc21adb0f.jpg` |
| `A ` | `public/uploads_id/675810d5b2ae4.jpg` |
| `AA` | `README.md` |
| `AD` | `receipt.docx` |
| `A ` | `resources/css/app.css` |
| `A ` | `resources/js/app.js` |
| `A ` | `resources/js/bootstrap.js` |
| `??` | `resources/templates/official-receipt.docx` |
| `AD` | `resources/views/1edits.blade.php` |
| `A ` | `resources/views/auth/forgot-password.blade.php` |
| `A ` | `resources/views/auth/login.blade.php` |
| `AD` | `resources/views/auth/old/login.blade.php` |
| `AD` | `resources/views/auth/old/register.blade.php` |
| `AM` | `resources/views/auth/register.blade.php` |
| `A ` | `resources/views/auth/reset-password.blade.php` |
| `AM` | `resources/views/blood/display_appointments.blade.php` |
| `A ` | `resources/views/blood/homepage.blade.php` |
| `AM` | `resources/views/blood/lab_patient_records.blade.php` |
| `A ` | `resources/views/blood/reports.blade.php` |
| `A ` | `resources/views/blood/staff_account.blade.php` |
| `A ` | `resources/views/blood/turned_over.blade.php` |
| `A ` | `resources/views/client/homepage.blade.php` |
| `AD` | `resources/views/components/1edit-layout.blade.php` |
| `AM` | `resources/views/components/edit-layout.blade.php` |
| `A ` | `resources/views/components/form-button.blade.php` |
| `A ` | `resources/views/components/form-error.blade.php` |
| `A ` | `resources/views/components/form-field.blade.php` |
| `A ` | `resources/views/components/form-input.blade.php` |
| `A ` | `resources/views/components/form-label.blade.php` |
| `A ` | `resources/views/components/header-bootstrap-import.blade.php` |
| `A ` | `resources/views/components/js-bootstrap-down.blade.php` |
| `A ` | `resources/views/components/layout.blade.php` |
| `A ` | `resources/views/components/nav-link.blade.php` |
| `A ` | `resources/views/components/rhu-appointment.blade.php` |
| `A ` | `resources/views/components/sidebar.blade.php` |
| `AM` | `resources/views/consultation/display_appointments.blade.php` |
| `A ` | `resources/views/consultation/homepage.blade.php` |
| `AM` | `resources/views/consultation/lab_patient_records.blade.php` |
| `A ` | `resources/views/consultation/reports.blade.php` |
| `A ` | `resources/views/consultation/staff_account.blade.php` |
| `A ` | `resources/views/edit.blade.php` |
| `A ` | `resources/views/edit_profile/announcement.blade.php` |
| `A ` | `resources/views/edits.blade.php` |
| `A ` | `resources/views/email/acc_approved.blade.php` |
| `A ` | `resources/views/email/appointment_approved.blade.php` |
| `A ` | `resources/views/email/demoMail.blade.php` |
| `A ` | `resources/views/inventory/homepage.blade.php` |
| `A ` | `resources/views/inventory/medical_equipments.blade.php` |
| `A ` | `resources/views/inventory/medical_supplies.blade.php` |
| `A ` | `resources/views/inventory/medicines.blade.php` |
| `A ` | `resources/views/inventory/reports.blade.php` |
| `A ` | `resources/views/inventory/staff_account.blade.php` |
| `A ` | `resources/views/inventory/vaccines.blade.php` |
| `A ` | `resources/views/it_dept/homepage.blade.php` |
| `A ` | `resources/views/it_dept/logs.blade.php` |
| `AM` | `resources/views/it_dept/registration_verification.blade.php` |
| `A ` | `resources/views/it_dept/staff_account.blade.php` |
| `A ` | `resources/views/it_dept/user_account.blade.php` |
| `AM` | `resources/views/laboratory/display_appointments.blade.php` |
| `A ` | `resources/views/laboratory/homepage.blade.php` |
| `AM` | `resources/views/laboratory/lab_patient_records.blade.php` |
| `A ` | `resources/views/laboratory/lab_tests.blade.php` |
| `A ` | `resources/views/laboratory/reports.blade.php` |
| `A ` | `resources/views/laboratory/staff_account.blade.php` |
| `A ` | `resources/views/livewire/notifications.blade.php` |
| `AM` | `resources/views/profiles/announcement.blade.php` |
| `A ` | `resources/views/profiles/edit.blade.php` |
| `A ` | `resources/views/receipt.blade.php` |
| `AD` | `resources/views/rhu/1index.blade.php` |
| `AM` | `resources/views/rhu/index.blade.php` |
| `AM` | `resources/views/super_admin/announcement.blade.php` |
| `A ` | `resources/views/super_admin/appointments.blade.php` |
| `A ` | `resources/views/super_admin/homepage.blade.php` |
| `A ` | `resources/views/super_admin/staff_account.blade.php` |
| `A ` | `resources/views/super_admin/super_patient.blade.php` |
| `A ` | `resources/views/super_admin/user_account.blade.php` |
| `A ` | `resources/views/user/personnel.blade.php` |
| `A ` | `resources/views/user/user.blade.php` |
| `AM` | `resources/views/vaccination/display_appointments.blade.php` |
| `A ` | `resources/views/vaccination/homepage.blade.php` |
| `AM` | `resources/views/vaccination/lab_patient_records.blade.php` |
| `A ` | `resources/views/vaccination/reports.blade.php` |
| `A ` | `resources/views/vaccination/staff_account.blade.php` |
| `A ` | `resources/views/welcome.blade.php` |
| `AD` | `rhu.sql` |
| `A ` | `routes/console.php` |
| `AM` | `routes/web.php` |
| `A ` | `storage/app/.gitignore` |
| `A ` | `storage/app/private/.gitignore` |
| `A ` | `storage/app/public/.gitignore` |
| `A ` | `storage/framework/.gitignore` |
| `A ` | `storage/framework/cache/.gitignore` |
| `A ` | `storage/framework/cache/data/.gitignore` |
| `A ` | `storage/framework/sessions/.gitignore` |
| `A ` | `storage/framework/testing/.gitignore` |
| `A ` | `storage/framework/views/.gitignore` |
| `A ` | `storage/logs/.gitignore` |
| `A ` | `tailwind.config.js` |
| `AM` | `tests/Feature/ExampleTest.php` |
| `??` | `tests/Feature/ReviewRegressionTest.php` |
| `AM` | `tests/Pest.php` |
| `AM` | `tests/TestCase.php` |
| `A ` | `tests/Unit/ExampleTest.php` |
| `??` | `tests/Unit/PesoAmountTest.php` |
| `??` | `tests/Unit/PhilippineMobileNumberTest.php` |
| `A ` | `vite.config.js` |

</details>
