class system {
    # Default packages
    exec { 'download-apt-dotdeb-pubkey':
        command => "true || apt-key add /vagrant/dev/puppet/files/dotdeb.gpg",
    }

    file { 'dotdeb.list':
        path => "/etc/apt/sources.list.d/dotdeb.list",
        source => "/vagrant/dev/puppet/files/dotdeb.list",
        replace => "yes",
        require => Exec["download-apt-dotdeb-pubkey"]
    }

    package {
        [ 'git', 'make', 'vim', 'wget', 'curl', 'augeas-tools', 'libaugeas-dev', 'libaugeas-ruby', 'bash-completion', 'libicu52', 'libicu-dev', 'php5-intl']:
            ensure => present,
            require => File["dotdeb.list"]
    }

    class { 'php':
        augeas => true
    }

    # Set the right timezone
    class { 'timezone':
        timezone => 'Europe/Amsterdam',
    }

    host { 'localhost':
        ip => '127.0.0.1'
    }

    file { "motd" :
        path   => "/etc/motd",
        source => "/vagrant/dev/puppet/files/motd",
        owner  => "root",
        group  => "root",
        mode  => 0644,
    }
}
