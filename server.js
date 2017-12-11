const path = require('path');
const restify = require('restify');
const errors = require('restify-errors');
const restifyValidation = require('node-restify-validation');

const PROJECT_DIR = __dirname;
const PORT = Number(process.env.PORT || 3000);

const server = restify.createServer({
  name: 'ModernJukebox',
  version: '1.0.0',
});

server.use(restify.plugins.acceptParser(server.acceptable));
server.use(restify.plugins.queryParser());
server.use(restify.plugins.bodyParser());
server.use(restify.plugins.fullResponse());
server.use(restifyValidation.validationPlugin({
  errorsAsArray: false,
  forbidUndefinedVariables: false,
  errorHandler: new errors.InvalidArgumentError(),
}));

server.get('/.*/', restify.plugins.serveStatic({
  directory: path.join(PROJECT_DIR, 'dist'),
  default: 'index.html',
}));

server.listen(PORT, () => {
  /* eslint-disable no-console */
  console.log('%s listening at %s', server.name, server.url);
});
