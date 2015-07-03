# -*- mode: ruby -*-
# vi: set ft=ruby :

PATH = File.expand_path(File.dirname(__FILE__))

Vagrant.configure("2") do |config|
  config.vm.box = "precise64"
  config.vm.box_url = "http://files.vagrantup.com/precise64.box"
  config.vm.network :private_network, ip: "192.168.33.10"
  config.vm.synced_folder PATH, "/srv"
  config.vm.provision :shell, :path => File.join(PATH, 'vm', "bootstrap.sh")
end