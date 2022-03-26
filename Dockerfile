# 借用官方的的 php-fpm 镜像
FROM php:7.2.4-fpm-stretch


# 复制内容
COPY . /treehole
WORKDIR /treehole
CMD ["php", "./app.php"]


# 安装 nginx
RUN apt-get update && apt-get install nginx -y && apt-get install vim -y

# 暴露 80 端口
EXPOSE 80
