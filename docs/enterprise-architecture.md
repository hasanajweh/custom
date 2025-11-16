# Enterprise Architecture & Access Rules

## Roles and Responsibilities
- **SuperAdmin**
  - Manages networks only (create/edit network metadata, branches, and main-admin credentials).
  - Does **not** manage academic data (teachers, supervisors, files, subjects, grades).
- **Main Admin (Network-level)**
  - Exactly one per network, created by SuperAdmin alongside the first branch.
  - Controls every branch in the network (users, subjects, grades, branches themselves).
  - Always uses the enterprise layout (`layouts/school.blade.php`) in RTL (Arabic default).
  - Can open the branch admin experience for any branch within the same network.
- **Branch Admin**
  - Manages users, subjects, grades, and files for their assigned branch only.
- **Supervisor**
  - Reviews teacher submissions and uploads supervisor resources in their branch only.
- **Teacher**
  - Manages their own library and uploads resources/lesson plans for their branch only.

## Route and Login Structure
- Branch-aware authentication URLs **must** be `/ {network_slug} / {branch_slug} / login` for admins, supervisors, teachers, and also main admins.
- Main Admin credentials authenticate successfully from **any** branch login page in their network. After authentication they are redirected to the Main Admin dashboard.
- Branch Admin, Supervisor, and Teacher accounts are restricted to their own branch URL and will be rejected if the branch slug does not match their account.
- SuperAdmin network routes remain separate and are not used for academic roles.

## Access Control & Middleware
- Tenant routes are prefixed with both network and branch slugs and scoped with middleware to keep requests inside the correct network.
- **Main Admin network scope**
  - Allowed through tenant middleware when the route’s network and branch belong to their network_id.
  - Treated as an allowed role for admin-specific areas so they can manage any branch without duplicating accounts.
- **Branch user scope**
  - Branch Admin/Supervisor/Teacher must belong to the branch (`school_id` match) or they are blocked.
- **SuperAdmin scope**
  - Bypasses tenant isolation but remains limited to network management features.

## Context Handling
- `network` and `school` route parameters identify the active tenant context and bind to models.
- The tenant identification middleware shares the current branch context with the view layer so the enterprise layout renders correctly (including RTL for main admin).
- Network and branch checks run before controllers to prevent cross-network access or logins.

## Main Admin Experience
- Dashboard shows a global view across all branches in the network (counts for branches, users per role, files/plans, and recent uploads).
- From the dashboard the Main Admin can jump into a branch’s admin view to manage users, subjects, grades, files, or branch settings for that branch.

## SuperAdmin Network Provisioning
When creating a network, SuperAdmin must supply:
- Network name and slug.
- At least one branch (name, slug, city).
- Main Admin account (email, username, password) tied to the network.

SuperAdmin can later update network details, branch metadata, or reset Main Admin credentials. Academic data remains outside their scope.
