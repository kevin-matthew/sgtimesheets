NGINXFILE = /home/kmarschke/git/sgtimesheet/nginx.conf
SITENAME  = sgtimesheets.localhost

default:

install:
	ln -s $(NGINXFILE) /etc/nginx/sites-enabled/$(SITENAME)


remove:
	unlink /etc/nginx/sites-enabled/$(SITENAME)
