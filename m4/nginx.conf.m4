##
# You should look at the following URL's in order to grasp a solid understanding
# of Nginx configuration files in order to fully unleash the power of Nginx.
# http://wiki.nginx.org/Pitfalls
# http://wiki.nginx.org/QuickStart
# http://wiki.nginx.org/Configuration
#
# Generally, you will want to move this file somewhere, and start with a clean
# file but keep this around for reference. Or just disable in sites-enabled.
#
# Please see /usr/share/doc/nginx-doc/examples/ for more detailed examples.
##

# Default server configuration
#
server
{
	listen 80;
	listen [::]:80;

	root __DOC_ROOT__;
	server_name __SITE_NAME__ __SITE_ALIAS__;
	

	location /
	{
		# First attempt to serve request as file, then
		# as directory, then fall back to displaying a 404.
		try_files $uri $uri/ $uri.html @extensionless-php;
		index index.php index.html;
	}

	# pass the PHP scripts to FastCGI server listening on 127.0.0.1:9000
	#
	location ~ \.php$
	{
		include snippets/fastcgi-php.conf;
		# With php7.0-fpm:
		fastcgi_pass unix:/run/php/php7.0-fpm.sock;
		fastcgi_param PHP_VALUE "include_path=\"__PHP_INCLUDE__\"";
	}

	location @extensionless-php
	{
		rewrite ^(.*)$ $1.php last;
	}

	# deny access to .htaccess files, if Apache's document root
	# concurs with nginx's one
	location ~ /\.ht {
		deny all;
	}
}
