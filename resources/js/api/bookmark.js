import { api } from './index'

export const bookmarkApi = {
  // Get bookmarks list
  list(params = {}) {
    return api.post('/bookmark/index', {
      page: params.page || 1,
      page_size: params.page_size || 10,
    })
  },

  // Create bookmark
  create(postId) {
    return api.post('/bookmark/create', {
      post_id: postId,
    })
  },

  // Delete bookmark
  delete(postId) {
    return api.delete('/bookmark/delete', {
      data: {
        post_id: postId,
      },
    })
  },
}

