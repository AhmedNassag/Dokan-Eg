export function createNavItems(role, sections = ['dashboard', 'category', 'user', 'role', 'permission']) {
  const items = [
    { heading: `${role.charAt(0).toUpperCase() + role.slice(1)} Panel` },
  ]

  const map = {
    dashboard:   { title: 'Dashboard',   icon: { icon: 'tabler-smart-home' }, to: `${role}-dashboard`,   action: 'read',  subject: 'dashboard' },
    category:    { title: 'Categories',  icon: { icon: 'tabler-category' },  to: `${role}-category`,    action: 'list',  subject: 'category' },
    user:        { title: 'Users',       icon: { icon: 'tabler-users' },     to: `${role}-user`,        action: 'list',  subject: 'user' },
    role:        { title: 'Roles',       icon: { icon: 'tabler-shield' },    to: `${role}-role`,        action: 'list',  subject: 'role' },
    permission:  { title: 'Permissions', icon: { icon: 'tabler-lock' },      to: `${role}-permission`,  action: 'list',  subject: 'permission' },
  }

  sections.forEach(key => {
    if (map[key]) items.push(map[key])
  })

  return items
}
