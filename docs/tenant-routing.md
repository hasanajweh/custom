# Tenant routing and dashboard access

## URL structure
- School-specific routes are prefixed with `/{network:slug}/{school:slug}`.
- Within that prefix, role areas live under:
  - Admin: `/{network}/{school}/admin/...`
  - Teacher: `/{network}/{school}/teacher/...`
  - Supervisor: `/{network}/{school}/supervisor/...`
- The role-agnostic entry point is `/{network}/{school}/dashboard`; it dispatches to the correct dashboard based on the authenticated user's role.
- Main Admin URLs use `/{network}/main-admin/...` and Super Admin URLs use `/superadmin/...`; these are separate from normal school admin/supervisor/teacher flows.

## Why requests fail with 404/403
- Requests abort with **404** if either slug does not exist or the school does not belong to the specified network.
- Authenticated traffic must pass the `auth`, `verify.tenant`, and role middleware; users not assigned to the school or lacking the expected role receive **403/404** before reaching the page.

## Quick checks before debugging
- Confirm the network and school slugs match the database (`latin` / `latin1` in the example).
- Ensure the signed-in user is attached to that school and has the correct role (admin, teacher, or supervisor).
- Use the role-agnostic dashboard at `/{network}/{school}/dashboard`; admins will be redirected to `/admin/dashboard` while teachers and supervisors land on their respective dashboards.
