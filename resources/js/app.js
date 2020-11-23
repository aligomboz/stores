const { data } = require('jquery');
const { default: Echo } = require('laravel-echo');

require('./bootstrap');
window.Echo.channel('orders' /*+ userId*/)
    .listen('.orders_created' , function(data){
        alert(JSON.stringify(data));
        console.log(data);
    })//.عشان نيم سبيس 
window.Echo.private('App.User.' + userId)
    .notification(function (data){
        alert(data.message);
    });