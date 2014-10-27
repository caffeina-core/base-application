Core - Application starter
================

Web application vanilla template.

## Installation

[![Deploy](https://www.herokucdn.com/deploy/button.png)](https://heroku.com/deploy?template=https://github.com/caffeina-core/base-application)

Create a new copy of this project via [composer](https://getcomposer.org/download/) 

```bash
$ composer create-project caffeina-core/base-application ./my-awesome-new-app
$ composer dumpautoload -o
```

Done.

## Front Controller

Like all URL-routed web app, you need to pass every request to the front controller `public/index.php`.

### NginX + PHP-FPM

Change `PATH_TO_YOUR_APP_DIR` to the absolute path of your project directory.

```bash
server {
        listen 80 default_server;

        root PATH_TO_YOUR_APP_DIR/public/;
        index index.php index.html index.htm;

        location / {
             try_files $uri /index.php$is_args$args;
        }

        # pass the PHP scripts to FastCGI server listening on /var/run/php5-fpm.sock
        location ~ \.php$ {
                try_files $uri /index.php =404;
                fastcgi_pass unix:/var/run/php5-fpm.sock;
                fastcgi_index index.php;
                fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
                include fastcgi_params;
        }
}
```

### Apache2

Assert that the document root of the virtualhost is pointing to `PATH_TO_YOUR_APP_DIR/public/`

```apache
<IfModule mod_rewrite.c>
  RewriteEngine On
  RewriteBase /
  RewriteRule ^index\.php$ - [L]
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteRule . /index.php [L]
</IfModule>
```
