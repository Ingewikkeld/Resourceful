# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|

  # Every Vagrant virtual environment requires a box to build off of.
  config.vm.box = "precise64"
  config.vm.box_url = "http://files.vagrantup.com/precise64.box"

  # Networking
  config.vm.network "private_network", ip: "192.168.43.43"
  config.vm.network "forwarded_port",  guest: 80, host: 8080
  config.vm.hostname = "dev.rest.com"
  config.ssh.forward_agent = true

  config.vm.synced_folder "./", "/vagrant", id: "vagrant-root", nfs: (RUBY_PLATFORM =~ /linux/ or RUBY_PLATFORM =~ /darwin/)

  config.vm.provider :virtualbox do |vb|
    # Use VBoxManage to customize the VM.
    vb.customize [
      'modifyvm', :id,
        '--chipset', 'ich9', # solves kernel panic issue on some host machines
        '--pae', 'on',
        '--uart1', 'off',
        '--memory', '2048'
    ]
  end

  config.vm.provision :shell, :path => "puppet/init.sh"
  config.vm.provision :puppet do |puppet|
    puppet.manifests_path = "puppet/manifests"
    puppet.module_path    = "puppet/modules"
    puppet.manifest_file  = "symfony-rest.pp"
    puppet.facter = {
        'fqdn' => config.vm.hostname
    }
  end
end
