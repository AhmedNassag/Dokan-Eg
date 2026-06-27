export { default as API } from './api'
export { apiClient, setAuthHeader, clearAuthHeader } from './apiService'
export { login, logout, getToken, getUser, hasRole } from './auth'

export { default as CategoryAPI } from './shared/Category/category'

// Backward-compatible role-specific aliases
export { default as AdminCategory } from './Admin/Category/category'
export { default as MerchantCategory } from './Merchant/Category/category'
export { default as MarketerCategory } from './Marketer/Category/category'
