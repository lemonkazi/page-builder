# Bloxby
---
Version : 1.4.1

# Build info
---
Bloxby uses Webpack to build and bundle everything for the front-end. To setup the build process locally, follow the following steps:

1. Install Nodejs dependencies: "npm install"
2. Run Webpack as a NPM command: "npm run build" or "npm run winbuild" on Windows OS
or
2. Run Webpack through the command line: "./node_modules/webpack/bin/webpack.js --optimize-minimize"

# Build info blocks
When moving the blocks over from the Blockery repo, special attention will need to be paid to handling of the skeleton.html file. In production, a single skeleton file is used and this file is copied over during the build process (the path is specified in the Webpack config file). Before running the build process, you will need to make sure the paths within the skeleton.html file are set correctly (since it will be loaded from a different location then the blocks itself).

# Webpack dev server
During development, you might want to opt to use webpack dev server. To enable this, start by editining "index.php" and set the ENVIRONMENT variable to "development". Next, run the command "npm start" (instead of "npm run build" for the regular build process). Finally, make sure you edit /application/config/webpack.php and set the "webpack_dev_url" to the correct value. 

Please note that the webpack dev server only runs for the Bloxby UI, not for the blocks loaded in the UI.