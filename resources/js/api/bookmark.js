import { api } from './index'

export const bookmarkApi = {
  // Get bookmarks list
  list(params = {}) {
    return api.get('/v1/bookmark/list', {
      params: {
        page: params.page || 1,
        page_size: params.page_size || 10,
      }
    })
  },

  // Create bookmark
  create(postId) {
    return api.post('/v1/bookmark/create', {
      post_id: postId,
    })
  },

  // Delete bookmark
  delete(postId) {
    return api.delete(`/v1/bookmark/delete/${postId}`)
  },
}

