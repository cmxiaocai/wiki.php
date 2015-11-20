<wiki type="config">
layout = post.html
title  = 阿里确认退出美团 阿里腾讯O2O正式开战
date   = 2015-11-20 14:38:58
summary= 凤凰科技讯 11月20日消息，近日据网友爆料，有多名广州商家反映，自己的经营门店不断受到美团工作人员的骚扰。美团的工作人员不但抢走收银台的支付宝指示牌，撕毁宣传海报，还威胁商家称，必须要停了支付宝，才能和美团继续合作。否则，就会提高对商家的提成比例。
</wiki>

# Nginx反向代理使用示例

## Nginx反向代理使用示例

### Nginx反向代理使用示例

#### Nginx反向代理使用示例

##### Nginx反向代理使用示例

---------

### proxy_pass配置说明

**结尾不带/**
> 访问 www.hiapk.dev/templets/v1/def.css 时指向 http://hit.stat.hiapk.com/templets/v1/def.css 文件

``` nginx
        location ^~ /templets/ {
                proxy_pass http://hit.stat.hiapk.com;
        }
```

**结尾带/**
> 访问 www.hiapk.dev/templets/v1/def.css 时指向 http://hit.stat.hiapk.com/def.css 文件

``` nginx
        location ^~ /templets/ {
                proxy_pass http://hit.stat.hiapk.com/;
        }
```


> 实例: 将站点templets反代至外网域名

``` nginx
        location ^~ /templets/ {
                proxy_pass http://hit.stat.hiapk.com;
        }

```

>  实例: 如果本地uploads文件不存在则使用线上地址

``` nginx
        location ^~ /uploads/ {
                root /data/wwwroot;
                if ( !-e $request_filename){
                        proxy_pass http://p5.image.hiapk.com;
                }
        }
```

### 根据域名转发
> 根据域名自动转发到相应服务器的特定端口
> /usr/local/nginx/conf/vhost/reverse-proxy.conf

``` nginx
server {
    listen       80;
    server_name  news.hiapk.dev;
    location / {
        proxy_redirect off;
        proxy_read_timeout 120;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_pass http://172.17.163.59:8202;
    }
    access_log /data/log/hiapk-news.access.log;
    error_log /data/log/hiapk-news.error.log;
}

server {
    listen       80;
    server_name  cms.hiapk.dev;
    location / {
        proxy_redirect off;
        proxy_read_timeout 120;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_pass http://172.17.163.59:8201;
    }
    access_log /data/log/hiapk-cms.access.log;
    error_log /data/log/hiapk-cms.error.log;
}
```

> 负载均衡

``` nginx

upstream monitor_server_news {
    server 172.17.163.59:8202;
    server 172.17.163.59:8203;
}

server {
    listen       80;
    server_name  news.hiapk.dev;
    location / {
        proxy_redirect off;
        proxy_read_timeout 120;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_pass http://monitor_server_news;
    }
    access_log /data/log/hiapk-news.access.log;
    error_log /data/log/hiapk-news.error.log;
}

```