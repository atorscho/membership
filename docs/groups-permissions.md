# Groups & Permissions

## Groups

"Membership" comes with 6 default groups:

- Members
- Moderators
- Super Moderators
- Administrators
- Owners
- Banned

Every group has specific set of permissions needed for better access control.

## Permissions

Permissions are separated into sets, each of them contains its own permissions.

There are four default permission sets:

- Users
  - Create `create.users`
  - Update `update.users`
  - Delete `delete.users`
  - Assign Permissions `assign.users.permissions`
- Groups
  - Create `create.groups`
  - Update `update.groups`
  - Delete `delete.groups`
  - Assign Permissions `assign.groups.permissions`
- Permissions
  - Create `create.permissions`
  - Update `update.permissions`
  - Delete `delete.permissions`
- Other
   - Access Admin CP `access.acp`

By default these permissions are assigned to groups but they can also be assigned to users so that a user could have extra access permissions without being part of a specific group.