export { default as API } from './api'
export { apiClient, setAuthHeader, clearAuthHeader } from './apiService'
export { login, logout, getToken, getUser, hasRole } from './auth'

export { default as CategoryAPI } from './shared/Category/category'
export { default as CountryAPI } from './shared/Country/country'
export { default as CityAPI } from './shared/City/city'
export { default as AreaAPI } from './shared/Area/area'
export { default as BranchAPI } from './shared/Branch/branch'
export { default as ShippingCompanyAPI } from './shared/ShippingCompany/shippingCompany'
export { default as ShopAPI } from './shared/Shop/shop'
export { default as LanguageAPI } from './shared/Language/language'
export { default as TranslationAPI } from './shared/Translation/translation'

// Backward-compatible role-specific aliases
export { default as AdminCategory } from './Admin/Category/category'
export { default as MerchantCategory } from './Merchant/Category/category'
export { default as MarketerCategory } from './Marketer/Category/category'

export { default as AdminCountry } from './Admin/Country/country'
export { default as MerchantCountry } from './Merchant/Country/country'
export { default as MarketerCountry } from './Marketer/Country/country'

export { default as AdminCity } from './Admin/City/city'
export { default as MerchantCity } from './Merchant/City/city'
export { default as MarketerCity } from './Marketer/City/city'

export { default as AdminArea } from './Admin/Area/area'
export { default as MerchantArea } from './Merchant/Area/area'
export { default as MarketerArea } from './Marketer/Area/area'

export { default as AdminBranch } from './Admin/Branch/branch'
export { default as MerchantBranch } from './Merchant/Branch/branch'
export { default as MarketerBranch } from './Marketer/Branch/branch'

export { default as AdminShippingCompany } from './Admin/ShippingCompany/shippingCompany'
export { default as MerchantShippingCompany } from './Merchant/ShippingCompany/shippingCompany'
export { default as MarketerShippingCompany } from './Marketer/ShippingCompany/shippingCompany'

export { default as AdminShop } from './Admin/Shop/shop'
export { default as MerchantShop } from './Merchant/Shop/shop'
export { default as MarketerShop } from './Marketer/Shop/shop'

export { default as AdminLanguage } from './Admin/Language/language'
export { default as MerchantLanguage } from './Merchant/Language/language'
export { default as MarketerLanguage } from './Marketer/Language/language'

export { default as AdminTranslation } from './Admin/Translation/translation'
export { default as MerchantTranslation } from './Merchant/Translation/translation'
export { default as MarketerTranslation } from './Marketer/Translation/translation'
