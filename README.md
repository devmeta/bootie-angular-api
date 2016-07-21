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

### Import database

```
cd /path/to/project
zcat ./config/bootieng.sql.gz | mysql -u root -p bootieng
```

### Config

```
mv ./config/config.sample.php config.php
nano ./config/config.php
```

### Folder Permission

```
sudo chmod -R 777 ./storage
sudo chmod -R 777 ./public/upload
```

## Panel access

```
username: admin
password: 1234
```

