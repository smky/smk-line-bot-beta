# smk-line-chat-bot

SourceCode:[LINE Messaging API SDK for PHP 3.13.0](https://github.com/line/line-bot-sdk-php)
Base image:[php:5.6-apache](https://hub.docker.com/_/php)

How to run this images

```sh
$ docker run -d -it --name my-php-line-bot -p 8080:80 -e LINEBOT_CHANNEL_TOKEN='<your channel token>' -e LINEBOT_CHANNEL_SECRET='<your channel secret>' kamas/smk-line-bot

```