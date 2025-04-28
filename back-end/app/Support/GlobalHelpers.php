<?php

if (! function_exists('currentUser')) {
    function currentUser()
    {
        return optional(auth('sanctum')->user());
    }
}
