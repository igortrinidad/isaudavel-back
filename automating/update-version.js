let fs = require('fs');
let fileName = '../package.json';
let file = require(fileName);

let oldVersion = file.version;
let versionArray = file.version.split('.');

let env = process.env.env;

versionArray[2] = parseInt(versionArray[2])+1

if(env == 'production'){
  	versionArray[1] = parseInt(versionArray[1])+1
}

file.version = versionArray.join(".");

fs.writeFile(fileName, JSON.stringify(file, null, 4));

console.log(file.version);

//Altera o arquivo build no config.xml de acordo com a nova versão do build
fs.readFile('../config.xml', 'utf8', function (err,data) {
  if (err) {
    return console.log(err);
  }

  let oldBuild = 'version="' + oldVersion + '"';
  let newBuild = 'version="' + file.version + '"';

  let result = data.replace(oldBuild, newBuild);

  fs.writeFile('../config.xml', result, 'utf8', function (err) {
     if (err) return console.log(err);
  });
});