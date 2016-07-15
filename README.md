# wiki.php

> wiki.php运行在php平台，是基于Markdown的wiki程序。你的Markdown存放在 wiki.php/_posts 目录下，以.md后缀的文件形式存放，程序简陋不支持在线编辑和备份，你可以尝试将_posts目录纳入git或svn中达到团队协作。

## 如何使用

**检出代码:**

    cd /home/wwwroot
    git clone https://github.com/cmxiaocai/wiki.php.git

**加入nginx配置:**

    server {
        listen       80;
        server_name  wiki.loc;
        index index.php;
        root /home/wwwroot/wiki.php/;

        location ~ \.php$ {
            fastcgi_pass  127.0.0.1:10080;
            fastcgi_index index.php;
            fastcgi_param SCRIPT_FILENAME /home/wwwroot/wiki.php/$fastcgi_script_name;
            fastcgi_param ENVIRONMENT local;
            include       fastcgi_params;
        }
    }

**访问:**

    http://127.0.0.1

## 目录结构

    -_includes                         项目类文件
        ├─ bootstrap.php             用于引如其他文件
        ├─ make_lists.class.php      生成列表页
        ├─ make_posts.class.php      生成内容页
        ├─ match_title.class.php     Markdown中匹配标题索引
        ├─ parse_config.class.php    解析内容中配置属性
        ├─ parse_filetype.class.php  解析文件类型
        ├─ Parsedown.php             Markdown转换html
        └─ simple_html_dom.php       dom解析
    -_posts                            你的Markdown文件存放在这里
        ├─ .conf                     用于配置列表页面的示例文件
        └─ demo.md                   Markdown示例文件
    -_theme                            样式风格
        ├─ +images                   图片资源
        ├─ +sass                     模板样式
        ├─ lists.html                默认列表模板
        └─ post.html                 默认内容页模板
    -_uploads
    composer.json
    index.php                          入口文件
