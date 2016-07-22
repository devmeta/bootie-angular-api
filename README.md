# AngularJS + Bootie PHP7 API

You can see it online [here](http://bootie-angularjs.devmeta.net)

## Features

- JWT integration
- ORM oriented
- Cache support
- GD image resize

## Dependencies

```
"php": ">=5.3.0",
"bootie/bootie": "dev-master",
"firebase/php-jwt": "dev-master"
```

## Installation

### Composer

```
composer install
```

### Folder Permission

```
cd /path/to/project
sudo chmod -R 777 ./storage ./public/upload
```

### Create database
```
mysql > CREATE DATABASE bootieng;
```

### Import database

```
cd /path/to/project
php cli create
php cli restore
```

### Config

```
mv ./config/config.sample.php config.php
nano ./config/config.php
```



## Panel access

```
username: admin
password: 1234
```

