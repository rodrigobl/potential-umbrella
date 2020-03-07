var express = require("express");
var app = express();

app.get('/', function(req, res){
    console.log("Got a GET request for the homepage");
    res.send('Hello GET');
});

app.post('/', function(req, res){
    console.log("Got a POST request for the homepage");
    res.send('Hello POST');
});

app.delete('/del_user', function(req, res){
    console.log("Got a DELETE request for /del_user");
    res.send('Hello DELETE');
});

app.get('/list_user', function(req, res){
    console.log("Got a GET request for /list_user");
    res.send('Page Listing');
});

app.get('/ab*cd', function(req, res){
    console.log("Got a GET request for /ab*cd");
    res.send('Page Pattern Match');
});

app.get("/url", (req, res, next) =>{
    res.json(["Tony", "Lisa", "Michael", "Ginger", "Food"]);
});

var server = app.listen(3000, function (){
    var host = server.address().address;
    var port = server.address().port;

    console.log("Example app listening at http://%s:%s", host, port);
});