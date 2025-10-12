import { api } from './index'

export const searchApi = {
  // Search
  search(query, params = {}) {
    return api.post('/search/index', {
      phrase: query,
      page: params.page || 1,
      page_size: params.page_size || 10,
    })
  },
}

