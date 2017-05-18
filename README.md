# Description
This is a starter child theme that includes a variety of workflows for local, staging, and production environments. It's super lightweight and fast with tasks running in the milliseconds.

## Features
- Yarn package manager
- Gulp workflow
- Support for multiple environments
- Optional Google Fonts, loaded asynchronously
- Browsersync: injecting CSS, reloading on PHP file changes

## Instructions
1. Clone this into your themes folder
2. Run `npm install`
3. In style.css, update to your info
4. In gulpfile.js, change `server` variable to your local vhost (line 25)
5. In functions.php, if needed, change parent styles destination (line 19)

## Environments
Make sure to add `define('WP_ENV', 'development');` to wp-config.php depending on the environment.

## Gulp Tasks
- `gulp`: run browsersync and watch src files
- `gulp build`: compile production assets
- `gulp dist`: compile production assets