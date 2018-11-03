NGINXFILE = __SITE_ROOT__/nginx.conf
SITENAME  = __SITE_NAME__

default:
	sass -C --sourcemap=none lmcss/sass/main.scss htdocs/css/main.css

install:
	ln -s $(NGINXFILE) /etc/nginx/sites-enabled/$(SITENAME)


remove:
	unlink /etc/nginx/sites-enabled/$(SITENAME)
