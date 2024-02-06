# MediaShop2000
A old style online shopping that sale all I.T. devices

## Project Structure
- **/_database** - Contains database configuration
  - `database.sql` - Database schema definition

- **/_docs** - Documentation folder
  - `requirement.md` - Project requirements documentation
  - /components - Additional documentation components

- **/css** - Stylesheets folder
  - /components - Styles for individual components
  - `style.css` - Main stylesheet

- **/i18n** - Internationalization folder
  - `lang.php` - Language files

- **/img** - Image assets folder

- **/includes** - Includes PHP files
  - `config.php` - Database configuration
  - `db.php` - Database connection setup
  - `utils.php` - Utility functions

- **/js** - JavaScript files folder

- **/templates** - HTML templates folder


## Vscode tools
- [Figma for VS Code](https://marketplace.visualstudio.com/items?itemName=figma.figma-vscode-extension)
- [Draw.io Integration](https://marketplace.visualstudio.com/items?itemName=hediet.vscode-drawio)
  
## To best view the project
### Install as [Docker](https://www.docker.com/)
1. Install Docker
2. Clone this reposity
   ```bash
   git clone
   ```
3. Build the docker image
   ```bash
   docker build -t mediashop2000 .
   ```
4. Run the docker container
   ```bash
   docker run -d -p 80:80 mediashop2000
   ```
5. Open your browser and go to http://localhost
6. To stop the container
   ```bash
   docker stop <container_id>
   ```
   - To get the container id run `docker ps` and copy the container id
   - To stop all running containers run `docker stop $(docker ps -a -q)`
   - To remove all containers run `docker rm $(docker ps -a -q)`


## Install as [XAMPP](https://www.apachefriends.org/it/index.html)
1. Download xampp and install it
2. Clone this reposity to xampp's htdocs folder
   ```bash
   cd C:\xampp\htdocs
   git clone https://github.com/AllgStudio/MediaShop2000_frontend.git .
   ```
   - Windows: C:\xampp\htdocs
   - Linux: /opt/lampp/htdocs
   - Mac: /Applications/XAMPP/xamppfiles/htdocs
3.  Start services Apache on Xampp panel
- web service will active in http://localhost/



sudo apt-get install -y php8.1-cli php8.1-common php8.1-mysql php8.1-zip php8.1-gd php8.1-mbstring php8.1-curl php8.1-xml php8.1-bcmath

## Initalization of the database
copy includes/config.php.template to includes/config.php