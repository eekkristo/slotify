# Simplyframe MVC example

This project was created and inspired by Udemy course
I have re-created the Udemy course using MVC pattern. 
There are however more features than in the original course. If you have come from that course then you can find this project interesting.

# Usage

This project already includes a template that has the basic features of most of the web pages. 

## Future and updates
Updates will be random and as is when I feel like it since it is a side project and I don't have much time to deal with it. 
Check contribution below to find what should be done.

## Please check Changelog for recent updates
[Changelog](./CHANGELOG.md)

### Major update - in progress

Recently I listened to the actual Spotify and thought to myself how have they hidden their audio files, so that you can't download it.

I started digging deeper and realized there is something called EME - Encrypted Media Extensions and ABR - Adaptive Bitrate Streaming. I decided to tinker around with it and see how far  I can go with it. This initial push already follows some of those scopes which kind of "put" it behind a DRM. I would not call it yet a full DRM but I dare you to try and download this file :). Additionally, the files are loaded via chunks which is quite neat :D

By no means I am an expert of it yet, but as time goes on I will try to adopt this and start testing how is it possible, if it even is to break the chunks and dump the files as real audio files.

I will leave some interesting links:

- https://w3c.github.io/encrypted-media/#mediaencryptedevent
- https://www.vdocipher.com/blog/adaptive-bitrate-streaming
- https://evermeet.cx/ffmpeg/
- https://developer.mozilla.org/en-US/docs/Web/Media/Formats/Audio_codecs#choosing_an_audio_codec
- https://nightly-dot-shaka-player-demo.appspot.com/docs/api/index.html

## Installation

### Via Docker and docker-compose
* This is the recommended way to start it and check it out. Does not require configuring anything yourself.
1. Download Docker Desktop 
2. Run inside the project folder the following command
```composer
docker-compose up 
```
or to run in the background window
```composer
docker-compose up -d 
```
* The following websites appear when docker container is running
1.  http://localhost:8443 - Application itself - 
2.  http://localhost:8080 - Mysql User Interface

## Default credentials
1. Website: 
   
> Username to login: demo@example.net
 
> Password to login: Password

Adminer gui credentials for accessing MySql DB server
1. Mysql User Interface 
   
> Username: root 

> Password: my-secret-pw

### Default credentials are defined inside .env variable

If you want to not define them directly you can set them up via environment variables inside the container.

* Until you have not removed the docker build files and/or containers the data will be available inside mysql database. Otherwise, data WILL BE LOST.
* To clean up everything run the following LINUX/OSX: (Sorry window guys, I don't have a command for you)
```composer
docker rmi $(docker images -a -q)
docker rm $(docker ps -a -q)
```
 ** NB! This will delete all of the images and containers inside your machine. If you have more images please run **

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

Please check [Issues.md](./ISSUES.md) for list of problems.

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
