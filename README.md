# LAMP開発環境構築

- VirtualBox+Vagrant+Dockerを使ったLAMP開発環境です。
- apache2.4 + php5.6 + mysql5.6
- [yKicchan/LAMP-REPL](https://github.com/yKicchan/LAMP-REPL.git)の通常盤


## 手順

### Vagrantのインストール

[公式サイト](http://www.vagrantup.com/)からダウンロードしてインストール

コマンドでバージョンを確認
```
$ vagrant --version
Vagrant 1.9.x
```

### VirtualBoxのインストール

[公式サイト](https://www.virtualbox.org/)からダウンロードしてインストール

### 動かす！！

ローカルの好きなところにクローンします
```
$ git clone https://github.com/yKicchan/LAMP.git
```

ゲストOSを立ち上げる
```
$ cd LAMP
$ vagrant up
```

ホストOSのhostsファイルに下記項目を追加(管理者権限が必要です)

Mac /etc/hosts

Win C:¥windows¥system32¥drivers¥etc¥hosts
```
192.168.33.11 docker.dev
```

ブラウザで http://docker.dev にアクセスし、phpinfoが表示されたら成功です

http://docker.dev/test.php にアクセスするとDBの接続が簡単ですがテストできます

接続ごとに接続した時間が表示されていけば成功です


## 各ディレクトリとファイルの説明(一部抜粋)

### Vagrantfile

Vagrantの設定ファイルです。

Windowsの人はnfsが使えないらしいのでL13をコメントアウトし、L15のコメントを外しましょう
```
# Mac OSX
# node.vm.synced_folder "./docker", "/docker", type: "nfs"
# Windows
node.vm.synced_folder "./docker", "/docker", type: "rsync", rsync__exclude: [".vagrant/", ".git/"]
```

### docker/master/init.d

ここにダンプファイルを置くことで、DBにデータを流しこめます。

### docker/src

ソースはここにおきましょう

### docker/comporse.yml

Dockerの各コンテナの設定です。

必要に応じて変更してください。

mysqlの初期設定:L15~
```
environment:
  - MYSQL_ROOT_PASSWORD=rootpass
  - MYSQL_DATABASE=database
  - MYSQL_USER=user
  - MYSQL_PASSWORD=password
```

MYSQL_ROOT_PASSWORD
- rootユーザのパスワードが設定できます
- パスワードを設定したくない時は`MYSQL_ALLOW_EMPTY_PASSWORD=yes`とできますが自己責任で。

MYSQL_DATABASE
- 任意でDBを一つ作成できます

MYSQL_USER
- 任意でユーザを１人作成できます

MYSQL_PASSWORD
- 作成したユーザのパスワードを設定できます

### docker/app/apache/virtual.conf

apacheの設定ファイルです。

アプリケーションに応じて設定してください

### docker/app/Dockerfile

アプリコンテナのDockerfileです。

RUNコマンドで必要なパッケージなどインストールできます。

詳しくは公式サイトなどを参考にしてください。

apacheのリライト機能を使う方はL7のコメントを外してください

リライト機能とはなんぞやという方はスルーで。
```
RUN a2enmod rewrite
```

### docker/master/my.cnf

マスター側のmysqlの設定ファイルです。

必要に応じて適当に変更してください。

以下はスレーブの設定
```
server-id=1001
log-bin=mysql-bin
relay_log_info_repository=TABLE
relay_log_recovery=ON
sync_binlog=1
```

### docker/replica/my.cnf

スレーブ側のmysql設定ファイルです。

`server-id=1002`と`read_only`以外はマスターと同じがいいらしいです。

## コマンド

### Vagrant

立ち上げ
```
$ vagrant up
```

ゲストOSにログイン
```
$ vagrant ssh
```

停止
```
$ vagrant halt
```

再起動
```
$ vagrant reload
```

廃棄
```
$ vagrant destroy
```

### Docker

コンテナ一覧
```
$ docker ps -a
```

コンテナにログイン
```
docker exec -it コンテナ名 /bin/bash
```
