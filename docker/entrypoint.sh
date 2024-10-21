#!/bin/bash

composer install
# Check if the database exists and run migrations if necessary
if ! mysql -h mysql -u"${DB_USERNAME}" -p"${DB_PASSWORD}" -e "use ${DB_DATABASE};" > /dev/null 2>&1; then
  echo "Database does not exist. Running migrations and seeding..."
  php artisan migrate --seed
else
  echo "Database exists. Skipping migrations and seeding."
fi

# Check if APP_ENV is set to 'local'
if [ "${APP_ENV}" = "local" ]; then
  echo "Running in local environment, starting npm in development mode..."
  npm run dev
else
  echo "Running in non-local environment, building assets for production..."
  npm run build
fi

# Start supervisord to keep the container running and manage the services
exec supervisord -c /etc/supervisor/supervisord.conf
