#!/bin/bash

# we set the colors
purple=`tput setaf 12`
gray=`tput setaf 8`
green=`tput setaf 2`
red=`tput setaf 1`
reset=`tput sgr0`

# script begin
printf "\n\n"
echo "${green}"
echo '    ____               _           __     _            __        ____'
echo '   / __ \_________    (_)__  _____/ /_   (_)___  _____/ /_____ _/ / /'
echo '  / /_/ / ___/ __ \  / / _ \/ ___/ __/  / / __ \/ ___/ __/ __ `/ / /'
echo ' / ____/ /  / /_/ / / /  __/ /__/ /_   / / / / (__  ) /_/ /_/ / / /'
echo '/_/   /_/   \____/_/ /\___/\___/\__/  /_/_/ /_/____/\__/\__,_/_/_/'
echo '                /___/'
echo "${gray}"

# we clear all caches
rm -rf bootstrap/cache/*

printf "\n"
echo "${gray}=================================================${reset}"
printf "\n"

# environment detection
echo "${purple}▶${reset} Detecting current environment ..."
local=false
if grep -q APP_ENV=local ".env"; then
    local=true
    echo "${green}✔${reset} Environment detected : ${purple}Local${reset}"
else
    echo "${green}✔${reset} Environment detected : ${purple}Other than local (production / preprod / staging / ...)${reset}"
fi

printf "\n"
echo "${gray}=================================================${reset}"
printf "\n"

# composer install / update
echo "${purple}▶${reset} Installing and updating composer dependencies ...${reset}"
if [ "$local" = true ]; then
    echo "${purple}→ composer self-update${reset}"
    sudo composer self-update
    echo "${purple}→ dump-autoload -o${reset}"
    composer dump-autoload -o

    if [ -d vendor ]; then
        echo "${gray}vendor directory detected : running composer update${reset}"
        echo "${purple}→ composer update --verbose --prefer-dist --optimize-autoloader --no-progress --no-interaction${reset}"
        composer update --verbose --prefer-dist --optimize-autoloader --no-progress --no-interaction
    else
        echo "${gray}vendor directory not detected : running composer install${reset}"
        echo "${purple}→ composer install --verbose --prefer-dist --optimize-autoloader --no-progress --no-interaction${reset}"
        composer install --verbose --prefer-dist --optimize-autoloader --no-progress --no-interaction
    fi
    echo "${green}✔${reset} Composer dependencies (including require-dev) installed and updated${reset}"
else
    echo "${purple}→ dump-autoload -o${reset}"
    composer dump-autoload -o
    if [ -d vendor ]; then
        echo "${gray}vendor directory detected : running composer update${reset}"
        echo "${purple}→ composer update --no-dev --verbose --prefer-dist --optimize-autoloader --no-progress --no-interaction${reset}"
        composer update --no-dev --verbose --prefer-dist --optimize-autoloader --no-progress --no-interaction
    else
        echo "${gray}vendor directory not detected : running composer install${reset}"
        echo "${purple}→ composer install --no-dev --verbose --prefer-dist --optimize-autoloader --no-progress --no-interaction${reset}"
        composer install --no-dev --verbose --prefer-dist --optimize-autoloader --no-progress --no-interaction
    fi
    echo "${green}✔${reset} Composer dependencies (without require-dev) installed and updated${reset}"
fi

# npm install / update
if [ "$local" = true ]; then

    printf "\n"
    echo "${gray}=================================================${reset}"
    printf "\n"

    read -p "Do you want to execute the npm install process on your project ? [${green}y${reset}/${red}N${reset}]" -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]
    then
        echo "${purple}▶${reset} Installing npm dependencies ..."
        echo "${purple}→ php artisan npm:install${reset}"
        php artisan npm:install
        echo "${green}✔${reset} Npm dependencies installation done"
    fi
fi

printf "\n"
echo "${gray}=================================================${reset}"
printf "\n"

# bower install / update
echo "${purple}▶${reset} Installing and updating bower dependencies ..."
echo "${purple}→ bower install${reset}"
bower install
echo "${purple}→ bower update${reset}"
bower update
echo "${green}✔${reset} Bower dependencies installed and updated"

printf "\n"
echo "${gray}=================================================${reset}"
printf "\n"

# binaries installation
echo "${purple}▶${reset} Installing project binaries ..."
echo "${purple}→ sudo apt-get update${reset}"
sudo apt-get update
echo "${purple}→ sudo apt-get install optipng pngquant pngcrush gifsicle jpegoptim -y${reset}"
sudo apt-get install optipng pngquant pngcrush gifsicle jpegoptim -y
echo "${green}✔${reset} Binaries installed"

printf "\n"
echo "${gray}=================================================${reset}"
printf "\n"

# we prepare the storage folders
echo "${purple}▶${reset} Preparing storage folders ..."
echo "${purple}→ php artisan storage:prepare${reset}"
php artisan storage:prepare
echo "${green}✔${reset} Storage folders prepared"

# we prepare the symlinks
if [ "$local" = true ]; then

    printf "\n"
    echo "${gray}=================================================${reset}"
    printf "\n"

    echo "${purple}▶${reset} Preparing app symlinks ..."
    echo "${purple}→ php artisan symlinks:prepare${reset}"
    php artisan symlinks:prepare
    echo "${green}✔${reset} App symlinks created"
fi

printf "\n"
echo "${gray}=================================================${reset}"
printf "\n"

# we generate an app key
echo "${purple}▶${reset} Generating new application key ..."
echo "${purple}→ php artisan key:generate${reset}"
php artisan key:generate
echo "${green}✔${reset} New application key generated"

# we install mailcatcher
if [ "$local" = true ]; then

    printf "\n"
    echo "${gray}=================================================${reset}"
    printf "\n"

    read -p "Do you want to install mailcatcher on your project ? [${green}y${reset}/${red}N${reset}] " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]
    then
        echo "${purple}▶${reset} Installing mailcatcher ..."
        echo "${purple}→ php artisan mailcatcher:install${reset}"
        php artisan mailcatcher:install
        echo "${green}✔${reset} Mailcatcher installation done"
    fi
fi

printf "\n"
echo "${gray}=================================================${reset}"
printf "\n"

# database migration and seed
if [ "$local" = true ]; then
    read -p "Do you want to execute a database reset on your project ? [${green}y${reset}/${red}N${reset}] " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]
    then
        read -p "This command will destroy, rebuild and seed your database and cannot be undone. Are you really sure to do that ? [${green}y${reset}/${red}N${reset}] " -n 1 -r
        echo
        if [[ $REPLY =~ ^[Yy]$ ]]
        then
            echo "${purple}▶${reset} Reseting database ..."
            echo "${purple}→ php artisan database:drop --force${reset}"
            php artisan database:drop --force
            echo "${purple}→ php artisan migrate --force${reset}"
            php artisan migrate --force
            echo "${purple}→ php artisan db:seed --force${reset}"
            php artisan db:seed --force
            echo "${green}✔${reset} New database migrations and seeds done"
        else
            echo "${purple}▶${reset} Executing project migrations ..."
            echo "${purple}→ php artisan migrate --force${reset}"
            php artisan migrate --force
            echo "${green}✔${reset} Project migrations done"
        fi
    else
        echo "${purple}▶${reset} Executing project migrations ..."
        echo "${purple}→ php artisan migrate --force${reset}"
        php artisan migrate --force
        echo "${green}✔${reset} Project migrations done"
    fi
else
    echo "${purple}▶${reset} Executing project migrations ..."
    echo "${purple}→ php artisan migrate --force${reset}"
    php artisan migrate --force
    echo "${green}✔${reset} Project migrations done"
fi

printf "\n"
echo "${gray}=================================================${reset}"
printf "\n"

# locales installation
echo "${purple}▶${reset} Installing project locales ..."
echo "${purple}→ sudo locale-gen fr_FR.UTF-8${reset}"
sudo locale-gen fr_FR.UTF-8
echo "${purple}→ sudo locale-gen en_GB.UTF-8${reset}"
sudo locale-gen en_GB.UTF-8
echo "${purple}→ sudo dpkg-reconfigure locales --unseen-only --force${reset}"
sudo dpkg-reconfigure locales --unseen-only --force
echo "${green}✔${reset} Project locales installed"

printf "\n"
echo "${gray}=================================================${reset}"
printf "\n"

# project optimizations
echo "${purple}▶${reset} Executing project optimizations ..."
echo "${purple}→ php artisan project:optimize${reset}"
php artisan project:optimize
echo "${green}✔${reset} Project optimizations done"

printf "\n"
echo "${gray}=================================================${reset}"
printf "\n"

# upgrade permissions
echo "${purple}▶${reset} Upgrading permissions ..."
echo "${purple}→ chmod -R g+w storage${reset}"
sudo chmod -R g+w storage
echo "${purple}→ chmod -R g+w bootstrap/${reset}"
sudo chmod -R g+w bootstrap/
echo "${green}✔${reset} Permissions upgraded"

printf "\n"
echo "${gray}=================================================${reset}"
printf "\n"

# script end
echo "${green}"
echo '    ____           __        ____                              __     __'
echo '   /  _/___  _____/ /_____ _/ / /  _________  ____ ___  ____  / /__  / /____'
echo '   / // __ \/ ___/ __/ __ `/ / /  / ___/ __ \/ __ `__ \/ __ \/ / _ \/ __/ _ \'
echo ' _/ // / / (__  ) /_/ /_/ / / /  / /__/ /_/ / / / / / / /_/ / /  __/ /_/  __/'
echo '/___/_/ /_/____/\__/\__,_/_/_/   \___/\____/_/ /_/ /_/ .___/_/\___/\__/\___/'
echo '                                                    /_/'
echo "${reset}"