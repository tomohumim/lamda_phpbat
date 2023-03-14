

## 用意したもの

* php
* node.js(serverless frameworkを入れるために必要　16じゃないと動かない）
* bref
* serverless framework
* IAMユーザー
* Laravelプロジェックト作成

## phpのインストール

```
sudo amazon-linux-extras install php7.3

php -v 
PHP 7.3.33 (cli) (built: Aug 11 2022 19:55:12) ( NTS )
Copyright (c) 1997-2018 The PHP Group
Zend Engine v3.3.33, Copyright (c) 1998-2018 Zend Technologies
```

##  node.jsをインストール
```
curl -fsSL https://rpm.nodesource.com/setup_16.x
sudo yum install -y nodejs
node -v
    v16.19.0
```

## brefをインストール
セットアップ用PHPスクリプトのダウンロード
```
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
```
composer-setup.php を実行して、Composer の実行ファイル(phar)を作成します
セットアップ終了後、ls コマンドで composer.phar ができたことを確認します。
```
php composer-setup.php

ls -l
```
セットアップスクリプト(composert-setup.php)は不要なので削除します
```
php -r "unlink('composer-setup.php');"
```
composer が利用できるようになったのかを確かめるため、作成された composer.phar を実行。
```
$ ./composer.phar -v
   ______
  / ____/___  ____ ___  ____  ____  ________  _____
 / /   / __ \/ __ `__ \/ __ \/ __ \/ ___/ _ \/ ___/
/ /___/ /_/ / / / / / / /_/ / /_/ (__  )  __/ /
\____/\____/_/ /_/ /_/ .___/\____/____/\___/_/
                    /_/
Composer version 1.2.0 2016-07-19 01:28:52

Usage:
  command [options] [arguments]

Options:
  -h, --help                     Display this help message
  -q, --quiet                    Do not output any message
  -V, --version                  Display this application version
      --ansi                     Force ANSI output
      --no-ansi                  Disable ANSI output
  -n, --no-interaction           Do not ask any interactive question
      --profile                  Display timing and memory usage information
      --no-plugins               Whether to disable plugins.
  -d, --working-dir=WORKING-DIR  If specified, use the given directory as working directory.
(以下略)
```
どこからでも(Globallyに)使えるようにするために、 /usr/local/bin フォルダに composer.phar を移動させる
```
mv composer.phar /usr/local/bin/composer
```
```
$ composer
   ______
  / ____/___  ____ ___  ____  ____  ________  _____
 / /   / __ \/ __ `__ \/ __ \/ __ \/ ___/ _ \/ ___/
/ /___/ /_/ / / / / / / /_/ / /_/ (__  )  __/ /
\____/\____/_/ /_/ /_/ .___/\____/____/\___/_/
                    /_/
Composer version 1.2.0 2016-07-19 01:28:52

Usage:
  command [options] [arguments]

Options:
  -h, --help                     Display this help message
  -q, --quiet                    Do not output any message
  -V, --version                  Display this application version
      --ansi                     Force ANSI output
      --no-ansi                  Disable ANSI output
  -n, --no-interaction           Do not ask any interactive question
      --profile                  Display timing and memory usage information
      --no-plugins               Whether to disable plugins.
  -d, --working-dir=WORKING-DIR  If specified, use the given directory as working directory.
(以下略)
```

## serverless framework
```
npm install -g serverless
serverless -v
```
AWS アクセス キーを作成する
iamのアクセスキーとシークレットキーを取得して入力する。
```
serverless config credentials --provider aws --key <key> --secret <secret>
```
設定されていることを確認する。
```   
cat ~/.aws/credentials

[bref-cli]
aws_access_key_id=xxxxxxxxxxx
aws_secret_access_key=xxxxxxxxxxxxxxxx

[default]
aws_access_key_id=xxxxxxxxxxxxxxxxxxxxxxx
aws_secret_access_key=xxxxxxxxxxxxxxxxxxxxxxxxxxxxx
[lambda-test]
aws_access_key_id=xxxxxxxxxxxxxxxxxxxxxx
aws_secret_access_key=xxxxxxxxxxxxxxxxxxxxx
```
## Laravelプロジェックト作成
```
$ composer create-project laravel/laravel bref-laravel-project
$ cd bref-laravel-project
```
brefインストール
```
composer require bref/bref bref/laravel-bridge
```

laravelでバッチを作成する
```
php artisan make:command [クラス名] 
```
/app/Console/Commands/バッチを実行するファイルが生成される。
今回は
lamda_phpbat/app/Console/Commands/TestMyBatch.php
<br>上記を作成。
```
 php artisan |grep command
  command [options] [arguments]
  -h, --help            Display help for the given command. When no command is given display help for the list command
      --env[=ENV]       The environment the command should run under
Available commands:
  help                   Display help for a command
  list                   List commands
 command
  command:test           Command description            ←実行したいバッチが登録されている
  make:command           Create a new Artisan command
  schedule:list          List the scheduled commands
  schedule:run           Run the scheduled commands
  schedule:test          Run a scheduled command
```
バッチを手動実行
```
php artisan command:test
```
serverless.yml 生成
```
php artisan vendor:publish --tag=serverless-config
```
<br>上記を実施するとserverless.ymlが生成される。
<br>serverless.ymlを編集する。

## AWSにデプロイ
```
serverless deploy
```
上記を実行するとseverless.ymlを参照し、.serverlessフォルダーが作成される。

cat ~/.aws/credentialsの一番上にあるiamユーザーを参照し、awsへデプロイする。
今回の例だとbref-cliユーザーが存在するawsアカウントにlamdaがデプロイされる。
別アカウントのawsにあげたい場合は別のawsアカウントにiamユーザーを作成する。
<br>exportコマンドでiamユーザーを指定してデプロイを実行する。
```
export AWS_PROFILE="<iamユーザー>"
```
シェルから抜けてしまうと元に戻るので.bash_profileなどに上記のコマンドを入れておくと良い。





