NGINXFILE = /home/parker/Documents/Projects/sgtimesheets/sgtimesheet/nginx.conf
SITENAME  = sgtimesheets.localhost

default:
	sass -C --sourcemap=none lmcss/sass/main.scss htdocs/css/main.css

install:
	ln -s $(NGINXFILE) /etc/nginx/sites-enabled/$(SITENAME)


remove:
	unlink /etc/nginx/sites-enabled/$(SITENAME)
