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

    apache::vhost { 'default':
        docroot     => '/var/www/resourceful',
        server_name => 'local.resourceful.ingewikkeld.net',
        priority    => '',
        enable      => true
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
