Exec {
  path => [
    '/bin/',
    '/sbin/' ,
    '/usr/bin/',
    '/usr/sbin/'
  ]
}

import 'application.pp'
import 'database.pp'
import 'system.pp'
import 'web.pp'

include system
include web
include database
include application
