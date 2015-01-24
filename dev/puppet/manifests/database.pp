class database {
    class { 'mysql': }

    mysql::grant { 'resourceful':
        mysql_privileges => 'ALL',
        mysql_db         => 'resourceful',
        mysql_user       => 'root',
        mysql_password   => ''
    }
}
