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