const con = require('../helpers/mysql')
const express = require('express')
const router = express.Router()

router.use(async (req, res, next) => {
    // console.log('from is auth ================')
    // console.log('headers', req.headers);
    // console.log('cookies', req.cookies);
    // console.log('from is auth end ================')
  if (req && req.cookies && req.cookies.access_key && req.cookies.username) {
    const username = req.cookies.username
    const accessKey = req.cookies.access_key
    // console.log('from is auth ================')
    // console.log(req.body);
    // console.log('headers', req.headers);
    // console.log('cookies', req.cookies);
    // console.log('accesskey', accessKey);
    // console.log('akdb', "76c95482bb33febf32f7c8aadcc5cf669ede1c99ce03d9df94197badcf1f9237241da556")
    //  console.log('from is auth end ================')
    const result = await con.query(
      'SELECT `access_key` FROM `users` WHERE `user`=?',
      [username]
    )
    if (result && result[0] && result[0].access_key === accessKey) {
      next()
      // console.log('Yes')
      // res.json({
      //   id: 1,
      //   error: 'ok'
      // })

    } else {
      res.json({
        id: 0,
        error: 'wrong auth provided'
      })
    }
  } else {
    res.json({
      id: 0,
      error: 'missed auth param3'
    })
  }
})

module.exports = router