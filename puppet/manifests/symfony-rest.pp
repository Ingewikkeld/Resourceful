exec { "apt-update":
    command => "/usr/bin/apt-get update"
}

Exec["apt-update"] -> Package <| |>

group { "puppet":
    ensure => "present",
}

class params {
    $filepath = '/vagrant/puppet/modules'
}

node default {
    include params

    file { "motd" :
        path   => "/etc/motd",
        source => "${params::filepath}/symfony-rest/files/motd",
        owner  => "root",
        group  => "root",
        mode  => 0644,
    }

    include symfony-rest
}
