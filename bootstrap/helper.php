<?php
function get_default_avatar(){
    $path_arr = ['/storage',  env('AVATAR_PATH', 'uploads/avatar'), 'default.jpg'];
    return join_paths($path_arr);
}