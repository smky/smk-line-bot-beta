#This image is base on LINE Messaging API SDK for PHP  and runon php:5.6-apache

How to run this images

$ docker run -d -it --name my-php-line-bot -p 8080:80 -e LINEBOT_CHANNEL_TOKEN= -e LINEBOT_CHANNEL_SECRET= kamas/smk-line-bot