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
echo '    ____             __                            _            __        ____'
echo '   / __ \____ ______/ /______ _____ ____  _____   (_)___  _____/ /_____ _/ / /'
echo '  / /_/ / __ `/ ___/ //_/ __ `/ __ `/ _ \/ ___/  / / __ \/ ___/ __/ __ `/ / /'
echo ' / ____/ /_/ / /__/ ,< / /_/ / /_/ /  __(__  )  / / / / (__  ) /_/ /_/ / / /'
echo '/_/    \__,_/\___/_/|_|\__,_/\__, /\___/____/  /_/_/ /_/____/\__/\__,_/_/_/'
echo '                            /____/'
echo "${gray}"


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

# script end
echo "${green}"
echo '    ____             __                                       __      __'
echo '   / __ \____ ______/ /______ _____ ____  _____   _    ____  / /__   / /'
echo '  / /_/ / __ `/ ___/ //_/ __ `/ __ `/ _ \/ ___/  (_)  / __ \/ //_/  / /'
echo ' / ____/ /_/ / /__/ ,< / /_/ / /_/ /  __(__  )  _    / /_/ / ,<    /_/'
echo '/_/    \__,_/\___/_/|_|\__,_/\__, /\___/____/  (_)   \____/_/|_|  (_)'
echo '                            /____/'
echo "${reset}"


