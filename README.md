# Description
This is a starter child theme that includes a variety of workflows for local, staging, and production environments. It's lightweight and fast with tasks running in milliseconds.

## Features
- ES6 support
- Bootstrap Media Queries (Optional)
- Yarn package manager
- Gulp workflow
- Support for multiple environments
- Optional Google Fonts, loaded asynchronously
- BrowserSync: injecting CSS, reloading on PHP file changes

## Instructions
1. Clone this into your themes folder
2. Run `yarn`
3. In style.css, update to your info
4. In gulpfile.js, change `server` variable to your local vhost (line 27)
5. In functions.php, if needed, change parent styles destination (line 21)

## Environments
Make sure to define your environment. Line 3 of functions.php attempts to automatically do this, but it won't work on production if you don't give a global ENV variable.

## Gulp Tasks
- `gulp`: run browsersync and watch src files
- `gulp build`: compile production assets
- `gulp dist`: compile production assets