<?php
include "class-zporg.php";
include "class-zporgsponsors.php";

zp_register_filter('content_macro', 'zporg::macros');