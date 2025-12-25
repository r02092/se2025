FROM alpine
RUN <<EOS
echo https://dl-cdn.alpinelinux.org/alpine/v3.20/main >> /etc/apk/repositories
apk --no-cache add git mariadb=10.11.14-r0 mariadb-client=10.11.14-r0 php83-dom php83-fileinfo php83-intl php83-mbstring php83-pdo_mysql php83-phar php83-session php83-tokenizer php83-xml php83-xmlwriter
cat <<-EOF > ~/entrypoint.sh
	test -d /var/lib/mysql/mysql
	db_setup=\$?
	if [ "\$db_setup" = 1 ]; then
		mariadb-install-db --datadir=/var/lib/mysql --user=mysql
	fi
	mariadbd-safe &
	composer install
	mkdir -p /workspace/storage/framework/cache/data
	mkdir /workspace/storage/framework/sessions
	mkdir /workspace/storage/framework/testing
	mkdir /workspace/storage/framework/views
	if [ ! -e .env ]; then
		cp .env.sample .env
	fi
	if [ \`grep ^APP_KEY= .env\` = APP_KEY= ]; then
		php artisan key:generate
	fi
	if [ "\$db_setup" = 1 ]; then
		while ! mariadb -e 'SELECT 1;'; do
			sleep 1;
		done
		mariadb -e 'CREATE DATABASE dev_db;
			GRANT ALL PRIVILEGES ON dev_db.* TO dev_user@localhost IDENTIFIED BY "dev_pass";'
		mariadb -e 'CREATE DATABASE test_db;
			GRANT ALL PRIVILEGES ON test_db.* TO dev_user@localhost;'
		php artisan migrate
	fi
	php artisan serve --host=0.0.0.0
	EOF
ln -s /usr/bin/php83 /usr/local/bin/php
git config --global safe.directory /workspace
wget -O- https://getcomposer.org/installer | php -- --filename /usr/local/bin/composer
wget -O- https://raw.githubusercontent.com/reviewdog/reviewdog/fd59714416d6d9a1c0692d872e38e7f8448df4fc/install.sh | sh -s -- -b /usr/local/bin v0.18.1
EOS
WORKDIR /workspace
ENTRYPOINT ["sh", "/root/entrypoint.sh"]
