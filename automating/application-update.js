var http = require('https');
var https = require('http');

function getTestPersonaLoginCredentials() {

    return http.get({
        host: 'weplanner-api.dev',
        path: '/oracle/system/application-update/$2y$10$R.zXYX4vsstPRwufOxnC6S1Ys6IsrUZZC8HhlyTLqELviu'
    }, function(response) {
      
      console.log(response);

    });

}

getTestPersonaLoginCredentials()