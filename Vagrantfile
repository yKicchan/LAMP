# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure(2) do |config|
  config.vm.box = "coreos-alpha"
  config.vm.box_check_update = false
  config.vm.box_url = ["https://storage.googleapis.com/alpha.release.core-os.net/amd64-usr/current/coreos_production_vagrant.json"]

  config.vm.define "LAMP" do |node|
    node.vm.hostname = "docker.dev"
    node.vm.network "private_network", ip: "192.168.33.11"
    # Mac OSX
    node.vm.synced_folder "./docker", "/docker", type: "nfs"
    # Windows
    # node.vm.synced_folder "./docker", "/docker", type: "rsync", rsync__exclude: [".vagrant/", ".git/"]
  end

  config.vm.provider "virtualbox" do |vb|
    vb.gui = false
    vb.memory = "1024"
    vb.name = "LAMP"
  end

  GET_COMPOSE = <<-'EOF'
    curl -L "https://github.com/docker/compose/releases/download/1.8.1/docker-compose-$(uname -s)-$(uname -m)" >  ~/docker-compose
    sudo mkdir -p /opt
    sudo mkdir -p /opt/bin
    sudo mv ~/docker-compose /opt/bin/docker-compose
    sudo chown root:root /opt/bin/docker-compose
    sudo chmod +x /opt/bin/docker-compose
  EOF
  config.vm.provision "shell", inline: GET_COMPOSE
  config.vm.provision "shell", inline: "docker-compose -f /docker/compose.yml up -d --build", run: "always"
end
