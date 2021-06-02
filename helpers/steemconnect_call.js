const fetch = require('node-fetch')
const steemConnectAPI = 'https://api.steemlogin.com/api/me'

const scAuth = async (accessToken) => {
  try {
    const result = await fetch(
      steemConnectAPI,
      {
        method: 'POST',
        headers: {
          Authorization: accessToken
        }
      }
    )
    // console.log('steemlogin', result);
    if (result.ok) {
      const res = await result.json()
      return res
    } else {
      return null
    }
  } catch (e) {
    // console.log('steemlogin' , e)
    return null
  }
}

module.exports = scAuth