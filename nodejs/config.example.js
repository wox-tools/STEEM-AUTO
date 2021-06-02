var config = {}
config.db = {}
config.db.pw = 'password'
config.db.user = 'username'
config.db.host = '127.0.0.1'
config.db.name = 'databasename'
config.wifkey = 'steem-private-posting-key'
// config.rpc = 'ws://192.99.210.161:8090'
config.rpc = 'https://api.steemdb.online'
config.rpc2 = 'https://api.steemitdev.com'
config.rpc3 = 'https://api.steemit.com'
config.steemd = 'https://api.steemdb.online'
config.isSteemd = 1 // Use steemd or rpc in the call() method
config.rpchttp = 'http://127.0.0.1:8090'
config.nodejssrv = 'http://0.0.0.0'

module.exports = config
