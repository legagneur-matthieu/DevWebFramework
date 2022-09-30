cd /var/www
chown -R $SUDO_USER ./
chgrp -R www-data ./
chmod -R 775 ./
echo "Permissions updated ! \n"
