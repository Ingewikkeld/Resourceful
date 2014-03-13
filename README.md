Resourceful
===========

Resourceful is a ready-to-use Symfony2-based 'Rapid API Development' (RAPID) setup to create
a REST API with as little effort as possible.

Features
--------

Resourceful comes with the following features out-of-the-box:

- User management.
- oAuth2 authorization and Client management.
- i18n and language selection using the `Accept-Language` header (defaults to english).
- Support for XML and JSON using the `Content-Type` and `Accepts` header.

Installation instructions
-------------------------

1. Clone this repository:

    git clone git@github.com:Ingewikkeld/Resourceful.git

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

Do not forget to add the URL 'dev.rest.example.org' to your hosts file with IP 192.168.43.43.
After the installation has completed you can visit that location to see your REST API.

Please see https://github.com/Ingewikkeld/Resourceful for more information or read the documentation in the
docs folder.
