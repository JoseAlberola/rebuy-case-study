# 🐳 Docker + PHP 8.2 + MySQL + Nginx + Symfony 6.2 Boilerplate

## Case Study Rebuy

### Description

API interface to manage products.
The API provides endpoints for CRUD operations (Create, Read, Update and Delete).

I have decided working in a Docker environment cause of my personal situation. I have been a while without using my personal laptop, which had some problems when starting in Windows, for that reason I decided to use it with Ubuntu, where I didn't have a WebServer installed and it also had an old version of PHP. After consideration, I thought about using docker containers, helping me to start developing faster and avoid spending to much time for preparing the environment.

It is composed by 3 containers:

- `nginx`, acting as the WebServer.
- `php`, the PHP-FPM container with the 8.2.7 version of PHP.
- `db` which is the MySQL database container with a **MySQL 8.0** image.

### Installation

1. Clone this repo.

2. If you are working with Docker Desktop for Mac, ensure **you have enabled `VirtioFS` for your sharing implementation**. `VirtioFS` brings improved I/O performance for operations on bind mounts. Enabling VirtioFS will automatically enable Virtualization framework.

3. Create the file `./.docker/.env.nginx.local` using `./.docker/.env.nginx` as template. The value of the variable `NGINX_BACKEND_DOMAIN` is the `server_name` used in NGINX. If you want to execute it locally, just write `localhost` 

4. Go inside folder `./docker` and run `docker compose up -d` to start containers.
   - It can happen that you are already using the ports that containers will use (Nginx port 80 and MySQL port 3306), if this is the case, you can stop your services using `sudo systemctl stop "service name"`

6. You should work inside the `php` container. This project is configured to work with [Remote Container](https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.remote-containers) extension for Visual Studio Code, so you could run `Reopen in container` command after open the project. Click (CTRL + Shift + P) and write `Dev Containers: Reopen in container`

7. Inside the `php` container, run `composer install` to install dependencies from `/var/www/symfony` folder.

8. Create the file `.env.local` in the root using `.env` as template. Use the following value for the `DATABASE_URL` environment variable:

```
DATABASE_URL=mysql://app_user:helloworld@db:3306/app_db?serverVersion=8.0.33
```

9. When we build the containers for the first time, the script `/database/schema.sql` will be executed, creating our database schema and inserting some data in it. You can check this file to see the data you can use.

### Useful commands
- `docker container ls -a` to see all the existing containers.
- `docker ps` to see all the running containers.
- `docker container rm container-name` to delete that container. Useful to clean the system.
- `docker volume ls` to see all the volumes.
- `docker volume rm volume-name` to delete that volume. Useful to restart the database from the beginning.
- `docker compose up -d` to build the containers and run them.
- `docker compose stop` to stop all the containers.
- `docker exec -it container-name bash` to enter to that container. 

### Important Links
- Postman Collection to access the API and see examples. [Postman](https://www.postman.com/planetary-astronaut-975741/workspace/rebuy/collection/13326509-623f2e4d-3dfb-4df5-9a3b-086d169772b9?action=share&creator=13326509)
