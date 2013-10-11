# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant::Config.run do |config|
    config.vm.host_name = "dev.rest.com"
    config.vm.network :hostonly, "192.168.43.43"
    config.vm.forward_port 80, 8080

    config.vm.box     = "precise64"
    config.vm.box_url = "http://files.vagrantup.com/precise64.box"

    config.vm.customize [
        'modifyvm', :id, '--chipset', 'ich9', # solves kernel panic issue on some host machines
        '--pae', 'on', '--uart1', 'off', '--memory', '1024'
    ]

    # Ensure nfs is used as filesystem for darwin and linux hosts
    config.vm.share_folder(
        "vagrant-root",
        "/vagrant",
        ".",
        :nfs => (RUBY_PLATFORM =~ /linux/ or RUBY_PLATFORM =~ /darwin/)
    )

    config.vm.provision :shell, :path => "puppet/init.sh"
    config.vm.provision :puppet do |puppet|
       puppet.module_path    = "puppet/modules"
       puppet.manifests_path = "puppet/manifests"
       puppet.manifest_file  = "symfony-rest.pp"
    end
end
