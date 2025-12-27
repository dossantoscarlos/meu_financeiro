<?php return  [
  \App\Providers\EventServiceProvider::class => 
   [
    \Illuminate\Auth\Events\Registered::class => 
     [
      0 => \Illuminate\Auth\Listeners\SendEmailVerificationNotification::class,
    ],
  ],
];