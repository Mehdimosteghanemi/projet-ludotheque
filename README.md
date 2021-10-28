# projet-lud-o-theque

## Hello welcome to our project

Just after cloning this project dont forget this few step to good initialisation

1- open your terminal in your favorit code editor (on VSC use ctrl + j)

2- Create a files .env.local on the root of the project :
* write this line on the file`DATABASE_URL="mysql://LOGIN:PASSWORD@127.0.0.1:3306/ludo_take?serverVersion=SQL_VERSION"`
* replace LOGIN by your login by your sql login
* replace PASSWORD by your login by your sql password
* replace SQL_VERSION by your login by your sql version
* to know your sql version write `mysql --version` you'll have return like this `mysql  Ver 15.1 Distrib 10.3.25-MariaDB, for debian-linux-gnu (x86_64) using readline 5.2`
* exemple for the version of the previous dot (do not reproduce this) `DATABASE_URL="mysql://leroy:qwerty@127.0.0.1:3306/ludo_take?serverVersion=mariadb-10.3.25"`

3- use this command on the order : 
* `composer install`
* `php bin/console d:d:c`
* `php bin/console d:s:u --force`

## Each time you want to modify CSS 
use this command : `sass -w public/sass/main.scss public/css/sass.css`
Whith this command you'll run sass compilator,each time you save in your public/sass/main.scss or in a files who is import in, sass will update the public/css/sass.css files.

### Never touch the files on public/css directory
Modify only the files and directory on public/sass

To close the runing sass use ctrl + c,
