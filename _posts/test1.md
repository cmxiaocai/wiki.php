<wiki type="config">
layout = post.html
title  = 阿里确认退出美团 阿里腾讯O2O正式开战
date   = 2015-11-20 14:38:58
summary= 凤凰科技讯 11月20日消息，近日据网友爆料，有多名广州商家反映，自己的经营门店不断受到美团工作人员的骚扰。美团的工作人员不但抢走收银台的支付宝指示牌，撕毁宣传海报，还威胁商家称，必须要停了支付宝，才能和美团继续合作。否则，就会提高对商家的提成比例。
</wiki>


# 环境配置

## 1.挂载/data目录
> 日常使用中尽量使用/data目录存储文件，但虚拟机故障还原后只有挂载目录的文件不会丢失。
> vda 系统损坏时会丢失
> vdb 数据不会丢失，所以要挂载vdb

``` bash
    mkdir /data
    mount /dev/vdb1 /data
```

> vim /etc/fstab 文件底部追加以下目录

```bash
    /dev/vdb1                /data                  ext4    defaults        0 0
```


## 2.将yum源更改为163源
> 因为centos自带的源速度太慢了,改为163源可以提高yum的速度

``` bash
    cd /etc/yum.repos.d/
    wget http://mirrors.163.com/.help/CentOS6-Base-163.repo
    mv CentOS-Base.repo CentOS-Base.repo.bak
    mv CentOS6-Base-163.repo CentOS-Base.repo
    yum clean all
    yum update
```

> 升级内核并重启系统( 如果不使用docker请忽略该步骤 )

``` bash
    yum install kernel-2.6.32-504.16.2.el6
    reboot
```

> 接着安装一些基础编译环境

``` bash
    yum install -y tar wget gcc gcc-c++ libxml2-devel zlib-devel bzip2-devel 
    yum install -y curl-devel libjpeg-devel libpng-devel libtiff-devel libxslt-devel
    yum install -y freetype-devel openssl openssl-devel vim subversion pcre-devel
```

## 3.创建worker用户
> 建议大家不要用root直接安装服务的习惯,之后的nginx等服务切换到worker安装

``` bash
    adduser worker
    passwd worker
```

# samba服务

## 1.检查setlinux
> 确保关闭setlinux

``` bash
    setenforce 0
    getenforce
    sed -i '/^SELINUX=/c\SELINUX=disabled' /etc/selinux/config
```

## 2.安装samba

``` bash
    yum install samba samba-client
```


> 在smb.conf文件默认加入以下配置
> vim /etc/samba/smb.conf

``` bash
    [data]
    comment = XiaoCai的KVM开发环境
    path = /data/wwwroot
    public = yes
    writable = yes
    printable = no
    write list = +staff
```

> 设置smb用户账户密码 ( 密码请和linux系统保持一致 )


``` bash
    smbpasswd -a worker
```

> 启动samba
> 记得关闭防火墙,或放开端口( service iptables stop )

    service smb start

## 3.愉快的访问挂载文件

    \\172.17.163.80\data

# 运行项目
## 1.从SVN检出项目代码
    su worker
    mkdir /data/wwwroot/www.hiapk.com
    svn checkout svn://10.79.156.50/product/nd/website/dedecms/dedecms_hiapk_com

## 2.从docker容器中运行项目
> 目前各个项目需要不同的环境版本，这意味着kvm中要装多套环境来满足开发。使用 docker容器运行项目能够保证运行环境与线上环境一致。并且可以避免你重复安装环境，也不用担心环境被你'搞坏了'。

*安装docker服务*

    rpm -ivh http://dl.Fedoraproject.org/pub/epel/6/x86_64/epel-release-6-8.noarch.rpm
    rpm --import /etc/pki/rpm-gpg/RPM-GPG-KEY-EPEL-6
    yum -y install docker-io
    yum upgrade device-mapper-libs
    service docker start

*拉取镜像*
    
    docker pull 172.17.163.105:8500/hiapk/dedecms_hiapk_com

*启动容器运行项目*

命令格式: docker run -i -t -v {代码目录}:/data/www -p {宿主机访问端口}:80 {镜像名称}

``` bash
    docker run -i -t -v /data/wwwroot/www.hiapk.com/:/data/www -p 8082:80 --privileged  hiapk-php53
```

*完成！访问项目*
http://172.17.163.80:8082/dede/



# 踩坑
> KVM虚拟机的内核版本和安装的docker版本，需采用以下两个版本,否则在构建镜像时会出现死机
> Docker version 1.6.2, build 7c8fca2/1.6.2
> yum install kernel-2.6.32-504.16.2.el6