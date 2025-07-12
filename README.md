# Connect4
Connect4 is the classic "connect four" game made with HTML, CSS, and JS, available in four languages. Play alone or with friends and unlock achievements on the site.

## Available languages
- English
- Spanish
- Portuguese
- French

## Game modes
Connect4 offers four game modes, divided into two sections: online modes and local modes. The available modes are:
- Quick Match: Play against a random opponent online.
- Play vs Friend: Play online against a friend by sending them a match request.
- Play vs Robot: Play against the AI at one of three difficulty levels (easy, normal, or hard).
- Two Players: Play locally against a friend on the same device.

## Project structure
<pre>
  ┌── app/                    Project source code
  │   ├── api/                Api controllers for endpoints
  │   ├── controllers/        Controllers of the app
  │   ├── enums/              Enumerator files
  │   └── models/             Application data models
  ├── config/                 Configuration files
  │   ├── config.php          Main system configuration
  │   ├── database/           Database-related files
  │   │   ├── database.php    File that controls database migrations
  │   │   └── migrations/     The app migrations
  │   └── routes/             Route-related settings
  │       ├── api.php         Api routes file for endpoints
  │       ├── Router.php      Router file
  │       └── web.php         Web routes file
  ├── public/                 Public files
  │   ├── css/                CSS files
  │   ├── font/               System fonts
  │   ├── img/                Images
  │   ├── js/                 Javascript files
  │   ├── translations/       Translation files
  │   ├── .htaccess           Access control file
  │   └── index.php           Main app file
  ├── resources/              Project views and components
  │   ├── components/         System components
  │   └── views/              Project views
  └── README.md               General project documentation
</pre>

## Get to Know the Project Structure
Below is a general overview of the project for anyone interested in understanding it in more depth.

### Structure
The project uses an MVC (Model-View-Controller) system inspired by the structure of the Laravel framework. The system's routes are defined in `config/routes/web.php` and are handled through `config/routes/Router.php`. The global configuration is defined in `config/config.php`.

### Controllers
The backend logic resides in the `Controllers`, which follow a predefined structure for returning `views` and HTTP responses. These controllers are located in `app/controllers/`. The project's APIs can be found in `app/api/`.
All Controllers are based on the structure defined in `app/controllers/Controller.php`.

### Views
The system's views are stored in `resources/views/`. These files are rendered into HTML and served to the user. Additionally, reusable components are defined in `resources/components/`.

### Models
The models are located in `app/models/` and contain the logic for interacting with the database. Each model follows a general structure defined in `app/models/Model.php`.
The project also implements a migration system, with migration files located in `config/database/migrations/`. To run them, execute `config/database/database.php`.

### Frontend and game logic
All frontend assets, including css and js files, are located in `public/`. Each view can include one or more of these files as needed.
The core structure for all game modes is defined in `public/js/game.js`, which contains both the game logic and the code for rendering all board animations.