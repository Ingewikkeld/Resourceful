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

2. Initialize and boot the Vagrant virtual machine:

    vagrant up
    
We know that there will be some red "warnings" during the run, but you should be okay.

3. Go into the vagrant machine and switch to the right directory:

    vagrant ssh
    cd /vagrant

3. Update database with schema

   php app/console doctrine:schema:update --force

Do not forget to add the URL 'local.resourceful.ingewikkeld.net' to your hosts file with IP 192.168.43.43.
After the installation has completed you can visit that location to see your REST API.

Please see https://github.com/Ingewikkeld/Resourceful for more information or read the documentation in the
docs folder.
