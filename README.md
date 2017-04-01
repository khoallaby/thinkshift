## Install

* [Composer](https://getcomposer.org/download/)
* [Node.js](http://nodejs.org/) >= 6.9.x
* [Yarn](https://yarnpkg.com/en/docs/install)

```
sudo apt-get -y install git
git clone https://github.com/thinkshift/tsdevserver.com.git thinkshift
cd thinkshift/tools
chmod +x *.sh
bash install.sh
```

 
 

### Apache stuff

Set your DocumentRoot to 
* "thinkshift/platform/web/"

Conf file to modify on dev: 
* /opt/bitnami/apache2/conf/bitnami/bitnami.conf
 



### Build commands

* `yarn run start` — Compile assets when file changes are made, start Browsersync session
* `yarn run build` — Compile and optimize the files in your assets directory
* `yarn run build:production` — Compile assets for production

#### Additional commands

* `yarn run rmdist` — Remove your `dist/` folder
* `yarn run lint` — Run ESLint against your assets and build scripts
* `composer test` — Check your PHP for PSR-2 compliance with `phpcs`
