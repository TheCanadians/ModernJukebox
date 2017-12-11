FROM node:8-alpine
RUN mkdir -p /code
WORKDIR /code
ADD . /code
RUN npm set progress=false && \
    npm install -s --no-progress && \
    npm run build
CMD [ "npm", "start" ]
EXPOSE 3000
