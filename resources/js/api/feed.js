import { api } from './index'

export const feedApi = {
  // Get latest news
  getLatestNews(params = {}) {
    return api.post('/news/last', {
      page: params.page || 1,
      page_size: params.page_size || 10,
    })
  },

  // Get news by influencers
  getNewsByInfluencers(params = {}) {
    return api.post('/news/by-influencers', {
      page: params.page || 1,
      page_size: params.page_size || 10,
    })
  },

  // Set reaction
  setReaction(newsId, reactionId) {
    return api.post('/news/set-reaction', {
      id_news: newsId,
      id_reaction: reactionId,
    })
  },

  // Remove reaction
  removeReaction(newsId, reactionId) {
    return api.post('/news/remove-reaction', {
      id_news: newsId,
      id_reaction: reactionId,
    })
  },

  // Create post
  createPost(data) {
    return api.post('/all-send-form/send', {
      text: data.content,
      text_short: data.satelliteContent || null,
      subscriptions: data.profileId ? [+data.profileId] : [],
      schedule: data.scheduledFor || null,
      timezone: 'UTC',
      is_schedule: data.isScheduled ? '1' : '0',
    })
  },

  // Get private feeds
  getPrivateFeeds() {
    return api.get('/private-feed/index')
  },

  // Get influencer feeds
  getInfluencerFeeds() {
    return api.get('/influencer/index')
  },

  // Get news by subscription
  getNewsBySubscription(params) {
    return api.post('/default/news-by-subscription', {
      id_subscription: params.id,
      page: params.page || 1,
      page_size: params.page_size || 10,
    })
  },

  // Get news by follower list
  getNewsByFollowerList(params) {
    return api.post('/news/by-follower-list', {
      id_follower_list: params.id,
      page: params.page || 1,
      page_size: params.page_size || 10,
    })
  },

  // Set subscription
  setSubscription(id, checked, zip = null) {
    return api.post('/feed/set', {
      id,
      checked,
      zip,
    })
  },

  // Report post
  reportPost(postId, reason, additionalText = null) {
    return api.post('/news/report', {
      id_news: postId,
      reason,
      additional_text: additionalText,
    })
  },

  // Mute subscription
  muteSubscription(subscriptionId) {
    return api.post('/news/mute', {
      id_subscription: subscriptionId,
    })
  },

  // Unmute subscription
  unmuteSubscription(subscriptionId) {
    return api.post('/news/unmute', {
      id_subscription: subscriptionId,
    })
  },
}

