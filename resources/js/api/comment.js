import { api } from './index'

export const commentApi = {
  // Create comment
  create(comment, newsId, parentId = null) {
    return api.post('/comment/create', {
      message: comment,
      id_news: newsId,
      id_parent: parentId,
    })
  },

  // Get comments for news
  getCommentsForNews(newsId, params = {}) {
    return api.post('/comment/get', {
      id_news: newsId,
      page: params.page || 1,
      page_size: params.page_size || 10,
    })
  },

  // Get replies for comment
  getRepliesForComment(commentId, params = {}) {
    return api.post('/comment/get-replies', {
      id_comment: commentId,
      page: params.page || 1,
      page_size: params.page_size || 10,
    })
  },
}

