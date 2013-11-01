Symfony REST Edition Distribution
=================================

Installation instructions
-------------------------

1. Clone this repository:

    git clone git@github.com:Ingewikkeld/symfony-rest-edition.git

2. Load the submodules that Puppet uses:

    git submodule update --init

3. Initialize and boot the Vagrant virtual machine:

    vagrant up

4. Ssh into the Vagrant virtual machine:

    vagrant ssh

5. Go to /vagrant:

   cd /vagrant

6. Download composer if you do not have it yet:

   wget http://getcomposer.org/composer.phar

7. Install dependencies

   php composer.phar install

8. Update database with schema

   php app/console doctrine:schema:update --force

Do not forget to add the URL 'dev.rest.example.org' to your hosts file; after the
installation has completed you can visit that location to see your REST API.

Please see https://github.com/Ingewikkeld/symfony-rest-edition for more
information or read the documentation in the docs folder.


TODO
----

* Replace caching folder directive with environment variable
* Move logging to syslog
