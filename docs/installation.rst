Installation
============

The easiest way to install your new API is by downloading the latest `release from our github page`_, unpack that in
your preferred folder and configure your webserver to point the document root to the ``web`` folder, or use Vagrant_.

Using Vagrant
-------------

For your convenience the Symfony REST Edition uses Vagrant_. To get your development environment up and running all you
need to do is run::

    $ vagrant up

While vagrant is busy setting up your development environment you can add the domain ``dev.rest.example.org`` to your
hosts file with the ip address ``192.168.43.43``.

Configuring Symfony
-------------------

.. important::

   Vagrant_ executes these steps for your development environment when you used it; you might however change these
   settings for your production environment.

In the ``app/config`` the file ``parameters.yml-dist`` must be renamed (or copied) to a file named ``parameters.yml``.

This file contains all the settings that you need to get up and running with a basic setup, such as:

* database settings
* mail server settings
* default locale
* Secrets used to hash or encrypt data

Setting up your database schema
-------------------------------

When you have configured your database settings as described in the previous chapter you can tell Symfony to populate
it with your schema, all that is necessary is to run the command::

   $ php app/console doctrine:schema:update --force

.. hint::

   If you have setup your environment using Vagrant_ you will need to execute this command from within your vagrant
   virtual machine; you can access it using the ``vagrant ssh`` command or using putty when on Windows.

.. _release from our github page: https://github.com/Ingewikkeld/symfony-rest-edition/releases
.. _Vagrant: http://vagrantup.com
