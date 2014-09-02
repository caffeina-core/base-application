<?php

Event::on(404,function(){
  Response::html( View::from('special/error',[
    'code'    => 404,
    'message' => 'Page not found',
  ]));
});

Route::on('/',function(){
  return View::from('index');
});

