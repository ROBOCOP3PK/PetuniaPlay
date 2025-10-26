import api from './api'

export const contactService = {
  /**
   * Send contact form message
   * @param {Object} data - Contact form data (name, email, phone, subject, message)
   * @returns {Promise}
   */
  async send(data) {
    return api.post('/contact', data)
  }
}

export default contactService
