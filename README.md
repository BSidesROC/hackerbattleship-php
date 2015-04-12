# hackerbattleship-php
PHP version of the hacker battleship scoring engine

REQUIRES
--------

 * PHP5 (tested with php5-fpm)
 * Postgresql

In Ubuntu based distributions, you can get all the dependencies by running:
<pre>sudo apt-get install postgresql php5-fpm php5-pgsql nginx</pre>

INSTALLATION
------------

**Create the database and users:**
 * As 'postgres' user:
<pre>
	createuser -lDERPS ctf
	createdb -O ctf ctf
	createuser -lDERPS ctf_ro
	createuser -lDERPS ctf_rw
	psql
	grant connect on database ctf to ctf_ro;
	grant connect on database ctf to ctf_rw;
	grant usage on schema public to ctf_ro;
	grant usage on schema public to ctf_rw;
	grant select on all tables in schema public to ctf_ro;
	grant select,insert,update,delete on all tables in schema public to ctf_ro;
	\q
</pre>

**Create the tables and functions:**
 * As any user:
<pre>psql -U ctf -h localhost < db/db.sql</pre>

**Setup the web server:**
 * Point the document root to *install_dir/htdocs*
 * An example nginx configuration is shown below (presumes php5-fpm on ubuntu)

<pre>
	server {
	  listen 0.0.0.0:80;
	  server_name hackerbattleship;
	  access_log /var/log/nginx/hackerbattleship.log;
	  error_log /var/log/nginx/hackerbattleship.log;

	  root /data/web/hackerbattleship/htdocs;

	  location / {
	    index index.php
	    try_files $uri $uri index.php;
	  }

	  location ~ \.php$ {
	    try_files $uri =404;
	    fastcgi_pass unix:/var/run/php5-fpm.sock;
	    fastcgi_index index.php;
	    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
	    include fastcgi_params;
	  }
	}
</pre>
