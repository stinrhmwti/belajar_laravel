# Project Rules: Laravel 12 Fleet Management System

## Architectural Patterns & Conventions
1. **Routing & Role Middleware**:
   - Always group protected routes under the `auth` middleware group in `routes/web.php`.
   - Restrict role permissions explicitly using the `role:<role1>,<role2>` middleware format (e.g., `role:admin`, `role:admin,teknisi`).
   - Keep resource action route naming consistent (e.g., `vehicles.index`, `vehicles.create`, `vehicles.store`, `checklist.destroy`, `expenses.approve`).

2. **Database & Migrations**:
   - Use Eloquent models located in `app/Models/` with appropriate mass-assignment protections (`$fillable` / `$guarded`).
   - Use `doctrine/dbal` for database schema modifications if modifying column types in migrations.

3. **UI & Data Presentation Standards**:
   - For summary & analytics widgets (e.g. *Ringkasan Biaya per Kendaraan*, *Keluhan Kendaraan Metrics*, *Dashboard Monitoring*), use metric stat cards with icon boxes, progress indicators, gradient spheres, and highlight badges (such as high-cost or urgent status indicators) for immediate visual clarity.
   - Tailor main action widgets dynamically based on user role (`Admin`, `Teknisi`, `Driver/User`) with quick action trigger buttons.
   - Show relevant contextual labels (e.g., Plat nomor + Merk/Tipe kendaraan) and formatted currency/values (`Rp X.XXX.XXX`).
   - For action tables (e.g. Complaints, Maintenance, User Management), format user avatars, badge status icons, and timestamp diffs for a modern, sleek experience.
   - **UI CSS & JS Frameworks**: Use Bootstrap 5 for grid, layouts, and components, along with Bootstrap Icons and jQuery for basic list-filtering/dynamic actions. Do not introduce Tailwind CSS or other utility libraries unless requested.
   - **CSS Styling Conventions**: Extend standard Bootstrap styling with custom CSS helper classes embedded directly in `@section('content')` or the layout (e.g. `.badge-soft-success`, `.stat-card`, `.icon-box`, `.aesthetic-card`).

4. **Localization & Language**:
   - The system is localized in Indonesian (`lang="id"`).
   - Use Indonesian for all database column names (`plat_nomor`, `merk`, `supir_utama`, `jumlah_biaya`, `tanggal`) and status values (Vehicles: `Siap Pakai`, `Sedang Diservis`, `Selesai`; Complaints: `Baru`, `Diproses`, `Selesai`).
   - All new user-facing messages, validation errors, and page text must be written in grammatically correct Indonesian.

5. **Role & Authorization Checks**:
   - The roles database column name is `role`.
   - Valid roles are `admin`, `teknisi`, and `user` (where `user` corresponds to the driver).
   - Ensure controllers or view elements check `$user->role` or use route role middleware to restrict actions properly.

6. **Development Workflow**:
   - Use `composer run dev` or `php artisan serve` to run the development server.
   - Run tests using `composer test` or `php artisan test`.
