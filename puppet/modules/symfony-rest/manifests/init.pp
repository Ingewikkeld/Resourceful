class symfony-rest {
    Exec { path => '/usr/bin:/bin:/usr/sbin:/sbin' }

    exec { 'download-apt-dotdeb-pubkey':
        command => "apt-key add ${params::filepath}/symfony-rest/files/dotdeb.gpg",
    }

    file { 'dotdeb.list':
        path => "/etc/apt/sources.list.d/dotdeb.list",
        source => "${params::filepath}/symfony-rest/files/dotdeb.list",
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
    include httpd
    include database

    file { "/var/log/symfony-rest" :
        ensure => 'directory',
        owner  => "vagrant",
        group  => "vagrant",
        mode  => 0664
    }

    file { "/vagrant/app/config/parameters.yml":
        source  => "/vagrant/app/config/parameters.yml-dist",
        replace => false,
    }
}
