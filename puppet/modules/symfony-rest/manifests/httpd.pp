class symfony-rest::httpd {
    include apache

    php::module { "xdebug" : }
    php::module { "mysql" : }
    php::module { "curl" : }

    php::pear::module { 'PHPUnit':
      repository  => 'pear.phpunit.de',
      use_package => 'no',
    }
    php::pear::module { 'PHP_CodeSniffer':
      use_package => 'no',
    }
    php::pear::module { 'phing':
      repository  => 'pear.phing.info',
      use_package => 'no',
    }
    php::pear::module { 'behat':
      repository  => 'pear.behat.org',
      use_package => 'no',
    }
    php::pear::module { 'mink':
      repository  => 'pear.behat.org',
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
        docroot       => '/var/www/symfony-rest',
        server_name   => 'dev.rest.example.org'
    }
    file { '/var/www' :
        ensure => 'directory',
        owner => 'vagrant'
    }
    file { '/var/www/symfony-rest' :
        ensure => 'link',
        target => '/vagrant/web',
        owner => 'vagrant',
        replace => "no"
    }
}
