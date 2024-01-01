# Simplyframe MVC example

This project was created and inspired by Udemy course
I have re-created the Udemy course using MVC pattern while trying to stay true to the logic behind the course.
There are however more features than in the original course. If you have come from that course then you can find this project interesting.

# Usage

Slotify already includes a template that has the basic features of most of the web pages. e.g

* Account creation
* Account activation via e-mail
* Password reset
* Remember me for different devices for user
* Visited page after forcing user to login
* Flash messages

## Future and updates
Updates will be random and as is when I feel like it since it is a side project and I don't have much time to deal with it. 
Check contributing below to find what should be done.

## Installation

### Via Docker and docker-compose
* This is the recommended way to start it and check it out. Does not require configuring anything yourself except for importing .sql, which we will talk below more.
1. Download Docker Desktop 
2. Run inside the project folder the following command
```composer
docker-compose up 
```
or to run in the background window
```composer
docker-compose up -d 
```
// If you have made changes to code, run the following
```composer
docker-compose up --build
```
* The following websites appear when docker container is running
1.  http://localhost:8000 - Application itself - 
2.  http://localhost:8080 - Mysql User Interface

## Docker-compose now imports the .sql automaticallt. Below step can be removed. Leaving it here just for reference only.

```
This can be ignored if you are using docker-compose to run the image locally
Currently .sql does not import itself automatically. Should be added a volume map in docker-compose and it should also automatically import it.
Importing .sql file
To get the full experience you should import the .sql file as well.
1. Navigate to http://localhost:8080
2. Log with default credentials
3. Select import
4. Choose slotify.sql and execute. Location is ``` App\Tests\Database\slotify.sql ```
```

## Default credentials
1. Website: 
Username to login
```
demo@example.net 
```
Password to login
```
Password 
```
Adminer gui credentials for accessing MySql DB server
2. Mysql User Interface: Username - 
```
root 
```
Password
```
my-secret-pw
```

### Default credentials are defined inside .env variable

* Until you have not removed the docker build files and/or containers the data will be available inside mysql database. Otherwise data WILL BE LOST.
* To clean up everything run the following LINUX/OSX: (Sorry window guys, I don't have a command for you)
```composer
docker rmi $(docker images -a -q)
docker rm $(docker ps -a -q)
```
 ** NB! This will delete all of the images and containers inside you machine. If you have more images then please run **
 Removing docker images
 ```
Find docker image <mvc-slotify_php-apache-environment>
docker image ls
// Copy IMAGE ID and run the following 
docker rmi <IMAGE ID>
 ```
Removing Container
 ```
 Find container <mvc-slotify_php-apache-environment>
 docker ps -a
 // Copy CONTAINER ID
 docker rm <CONTAINER ID>
 ```
## Docker issues 
There have been sometimes issues where you are unable to connect to the database. So far I have seen this issue in Linux.
Simply run:
```composer
docker-compose down  
```
```
sudo docker-compose up --build
```
## Docker dev environment
Docker Compose version 2.22 and later support ```watch``` command. 

New settings allows now to make changes without having to rebuild.
Simply run command: ` docker compose watch `
### Manual set-up
1. Download your favourite stack, e.g LAMPP, WAMPP, XAMPP etc...
2. Download composer https://getcomposer.org/download/
3. In your vhost.conf file set up virtual host to reference to /public folder
* https://www.cloudways.com/blog/configure-virtual-host-on-windows-10-for-wordpress/
4. Navigate to project folder and run the following command

```composer
composer install
```
* For database import use your UI or import it manually via terminal/cmd
## Mailgun & SMTP set up
1.  You need to register mailgun account and set-up API keys for email sending to work. There is free version which does not require credit card. However, you can only send emails do confirmed emails(approved emails to avoid sending spam)
2. https://app.mailgun.com/
3. Once you have set-up API configuration and "confirmed emails" and you register an account inside application then be sure to check your spam folder inbox. 
* You can also approve user manually inside database if needed

## Env and secret setup
1. You need to create an unique secret hash for Token generation.
     * Secret key for hashing. Use randomkeygen for unique random secret key
     * 256-bit key requirement ( 256-bit WEP Keys )
     * https://randomkeygen.com/ 
     * Copy the data from there and add it to secret.env ``` SECRET_KEY=<256-bit WEP KEY> ```
2. Also you should create secret.env file inside root folder and fill it with required data. Currently during build it will take default credentials from .env to just get it running without considering anything else. 

## Future and updates
In the next feature release the following will be added
* Friendly url, e.g instead of post/1 it will be post/my-awesome-post. (Mostly done)
* Admin / Artist interface to upload music and approve it by someone

# Issues and bugs
Currently there are quite many issues that have to be resolved before developing more features.
The following issues I have commented inside the codes. Can be found by installing TODO extension in Visual Code for example. 

```composer
#1	TODO: Implement Dispatch.php file inside Routes file that fill handle all of this data so we do not have to define this ourselces
#2	TODO: Implement update email and update password change
#3	TODO:: Make this better. Right now this kind of is all hard coded into here and due to that really hard to add more options
#4	FIXME: There is a bug if you add a song from your current playlist where you are another song the UI will flip out and render double your playlist
#5	FIXME: When you create a new playlist it will not be visible in the add to playlist. This is due to result is not empty. 
#6	TODO: Implement a delete song from playlist. Currently due to clearing optionsMenu we can't display delete from playlist function.
#7	TODO: Implement a $_POST check that deals with the data charset escape. Since js calls can be changed we should always assume the worst
#8	TODO: Implement a global json_encode parameter so that we do not have to render it ourselves constantly. Create a function for it and call it that way?
#9	FIXME:: Due to redirect back to root to store flash we are also taking the navbar and playbar with us 
#10	TODO: Fix this later with new function for redirect
#11	FIXME: Seperate Album and Artist Controllers to seperate files
#12	TODO: Make this better
#13	TODO: Make this better and
#14	TODO: Implement 404 artist not found designed page
#15	FIXME: Fix the search result. If no data found do not display any data about it
#16	TODO: Add song artist name 
#17	FIXME: Fix artist name in database 
#18	TODO:: Implement a global $_POST check. By default we should always escape charset as we do not trust the end user. 
#19	TODO: Implement a check where we will authorize artists / label owners and administrators with different privileges
```

## Contributing
Feel free to contribute to this project to fix the issues or add more stuff. To contribute create your own branch and request a pull request or fork your own clone. 
Some of the known issues would be awesome if someone fixed. Additionally the following which I will try to fix myself in priority.
1. Make Dockerfile and docker-compose.yml better ( During docker-compose up it should import the .sql file automatically. )
2. Implement a listener to so that if you develop and save a change, it will automatically render new settings, e.g nodemon or something
3. Change design to use bootstrap everywhere. More user-friendly
4. Fix the bugs that I have listed

## What, how and why ?
This Dockerfile should be possible to run inside a cluster as well, e.g Kubernetes. When I have time I will make a demo as well so that you could deploy this inside a Kubernetes cluster as well. I don't know many PHP projects and guides that show how to-do-it - most of them are really outdated and insecure. Hopefully I will soon be able to create some manuals on the side of containerization.


## License
[MIT](https://github.com/eekkristo/gublin/blob/main/LICENSE)
