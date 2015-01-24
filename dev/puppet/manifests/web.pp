class web {
    file { "/var/log/resourceful" :
        ensure => 'directory',
        owner  => "vagrant",
        group  => "vagrant",
        mode  => 0664
    }

    include apache

    php::module { "xdebug" : }
    php::module { "mysql" : }
    php::module { "curl" : }

    php::pear::module { 'PHP_CodeSniffer':
        use_package => 'no',
    }
    php::pear::module { 'phing':
        repository  => 'pear.phing.info',
        use_package => 'no',
    }

    apache::module { "rewrite" : }

# disable default vhost
    apache::vhost { 'default':
        docroot     => '/var/www',
        server_name => false,
        priority    => '',
        enable      => false
    }

    apache::vhost { 'dev.rest.com':
        docroot       => '/var/www/resourceful',
        server_name   => 'dev.rest.example.org'
    }
    file { '/var/www' :
        ensure => 'directory',
        owner => 'vagrant'
    }
    file { '/var/www/resourceful' :
        ensure => 'link',
        target => '/vagrant/web',
        owner => 'vagrant',
        replace => "no"
    }
}
