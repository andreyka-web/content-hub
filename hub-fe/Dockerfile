FROM node:18

# install simple http server for serving static content
RUN npm install -g http-server

# make the 'app' folder the current working directory
RUN mkdir /app && chown www-data:www-data /app

WORKDIR /app

# copy 'package.json' to install dependencies
COPY package*.json /app
 
# install dependencies
RUN npm install

# copy files and folders to the current working directory (i.e. 'app' folder)
COPY --chown=www-data:www-data . .

# build app for production with minification 
RUN npm run build   
# local-dev
# CMD ["npm", "run", "dev"]
CMD [ "http-server", "dist", "-p", "3001" ]