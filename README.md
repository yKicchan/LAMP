# LAMP開発環境構築

- VirtualBox+Vagrant+Dockerを使ったLAMP開発環境です。
- apache2.4 + php5.6 + mysql5.6
- レプリケーション対応版はこちら[yKicchan/LAMP-REPL](https://github.com/yKicchan/LAMP-REPL.git)


## 手順

### Vagrantのインストール

[公式サイト](http://www.vagrantup.com/)からダウンロードしてインストール

コマンドでバージョンを確認
```sh
$ vagrant --version
Vagrant 1.9.x
```

### VirtualBoxのインストール

[公式サイト](https://www.virtualbox.org/)からダウンロードしてインストール

### Cloneから起動まで

ローカルの好きなところにクローンします
```sh
$ git clone https://github.com/yKicchan/LAMP.git
```

Windowsの人はnfsが使えないらしいのでL13をコメントアウトし、L15のコメントを外しましょう

```ruby
# Mac OSX
# node.vm.synced_folder "./docker", "/docker", type: "nfs"
# Windows
node.vm.synced_folder "./docker", "/docker", type: "rsync", rsync__exclude: [".vagrant/", ".git/"]
```

ゲストOSを立ち上げる
```sh
$ cd LAMP
$ vagrant up
```

ホストOSのhostsファイルに下記項目を追加(管理者権限が必要です)

Mac /etc/hosts

Win C:¥windows¥system32¥drivers¥etc¥hosts
```
192.168.33.11 lamp.dev
```

ブラウザで http://lamp.dev にアクセスし、phpinfoが表示されたら成功です

http://lamp.dev/test.php にアクセスするとDBの接続が簡単ですがテストできます

接続ごとに接続した時間が表示されていけば成功です

Windowsはファイルシステムの仕様上(?)nfsが使えずにrsyncというものを採用しました。ファイルの変更を監視するために、VMの立ち上げに成功したら
```
~省略
Creating mysql
Creating app

$ vagrant rsync-auto
```
としてください。

プロキシ使ってる人は除外設定を忘れずに


## 各ディレクトリとファイルの説明(一部抜粋)

### docker/comporse.yml

Dockerの各コンテナの設定ファイルです。

L15-L19のところで、DBを自動で作成することができます
```yml
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

### docker/mysql/init.d

ここにダンプファイルを置くことで、comporse.ymlで作成したDBに初期データを流しこめます。

### docker/src

ソースはここにおきましょう

### docker/app/apache/virtual.conf

apacheの設定ファイルです。

アプリケーションに応じて設定してください

### docker/app/Dockerfile

アプリコンテナのDockerfileです。

アプリケーションに必要なライブラリなどはここでインストールできます。

詳しくは公式サイトなどを参考にしてください。

apacheのリライト機能を使う方はL7のコメントを外してください

リライト機能とはなんぞやという方はスルーで。
```
RUN a2enmod rewrite
```

### docker/mysql/my.cnf

mysqlの設定ファイルです。

必要に応じて適当に変更してください。


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
$ docker exec -it コンテナ名 /bin/bash
```
