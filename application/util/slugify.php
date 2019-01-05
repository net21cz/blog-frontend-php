<?php
function slugify($s){
   return strtolower(preg_replace('/-+/', '-', preg_replace('/[^A-Za-z0-9-]+/', '---', $s)));
}