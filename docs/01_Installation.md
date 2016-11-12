# Installation

## Via .phar

The easiest way to get it working is to download a tagged phpsa.phar release, and put this on your path. For example:

```
wget https://github.com/ovr/phpsa/releases/download/0.6.1/phpsa.phar
chmod +x phpsa.phar
sudo mv phpsa.phar /usr/local/bin/phpsa
```

## Via composer

The recommended way to install phpsa is via Composer.

1. If you do not have composer installed, download the [`composer.phar`](https://getcomposer.org/composer.phar) executable or use the installer.

``` sh
$ curl -sS https://getcomposer.org/installer | php
```

2. Run `php composer.phar require ovr/phpsa` or add a new requirement in your composer.json.

``` json
{
  "require": {
    "ovr/phpsa": "*"
  }
}
```

3. Run `php composer.phar update`


## Via source

```sh
git clone https://github.com/ovr/phpsa
cd phpsa
./bin/phpsa
```

Next: [Usage](./02_Usage.md)
