# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |baseconfig|
    baseconfig.vm.define :"local.resourceful.ingewikkeld.net" do |config|

        config.vm.box = "ubuntu/trusty64"

        config.vm.hostname = 'local.resourceful.ingewikkeld.net'
        config.vm.network "private_network", ip: "192.168.43.43"
        config.ssh.forward_agent = true

        config.vm.provider 'virtualbox' do |vb|
            vb.name = config.vm.hostname

            # Pass custom arguments to VBoxManage before booting VM
            vb.customize [
                'modifyvm', :id,
                '--memory', '2048',
                '--cpus', '2',
                '--natdnshostresolver1', 'on',
                '--natdnsproxy1', 'on',
            ]
        end

        if Vagrant.has_plugin?("vagrant-cachier")
            config.cache.auto_detect = true
        end
        if Vagrant.has_plugin?("vagrant-hostsupdater")
            config.hostsupdater.aliases = [
                'local.resourceful.ingewikkeld.net',
            ]
        end

        if Vagrant::VERSION < "1.4" then
            config.vm.synced_folder ".", "/vagrant", :nfs => true
        else
            if Vagrant::Util::Platform.windows? then
                config.vm.synced_folder ".", "/vagrant", mount_options: ['uid=`1000`','gid=33','dmode=0775','fmode=0764']
            else
                config.vm.synced_folder ".", "/vagrant", type: 'nfs', mount_options: ['rw', 'vers=3', 'tcp', 'fsc']
            end
        end

        config.vm.provision :shell, :path => "dev/puppet/upgrade-puppet.sh"
        config.vm.provision :shell, :path => "dev/puppet/librarian-puppet.sh"

        config.vm.provision :puppet do |puppet|
            puppet.manifests_path = "dev/puppet/manifests"
            puppet.module_path = "dev/puppet/modules"
            puppet.manifest_file = "default.pp"
            # For debugging
            # puppet.options = "--verbose --debug"
        end
    end
end
