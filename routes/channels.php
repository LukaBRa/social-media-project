<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('notifications', function() {
    return true;
});

Broadcast::channel('chat', function() {
    return true;
});