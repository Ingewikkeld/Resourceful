class application {
    exec { 'get-composer':
        command => 'curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin',
        unless => 'test -e /bin/composer'
    }

    exec { 'composer-install':
        environment => 'HOME=/home/vagrant',
        command => '/bin/composer.phar install',
        cwd => '/vagrant',
        require => Exec['get-composer']
    }


}
