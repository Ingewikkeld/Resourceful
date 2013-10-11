class symfony-rest::database {
    class { 'mysql': }

    mysql::grant { 'rest':
      mysql_privileges => 'ALL',
      mysql_db         => 'rest',
      mysql_user       => 'root',
      mysql_password   => ''
    }
}
